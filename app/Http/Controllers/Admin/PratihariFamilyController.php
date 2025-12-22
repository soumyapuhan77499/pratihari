<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariProfile;
use App\Models\PratihariFamily;
use App\Models\PratihariChildren;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class PratihariFamilyController extends Controller
{

public function pratihariFamily()
{
    $familyDetails = PratihariFamily::where('status', 'active')->get();

    // ✅ Fetch name parts; we'll show $p->full_name in blade
    $pratiharis = PratihariProfile::select('id', 'first_name', 'middle_name', 'last_name')
        ->orderBy('first_name')
        ->orderBy('middle_name')
        ->orderBy('last_name')
        ->get();

    return view('admin.pratihari-family-details', compact('familyDetails', 'pratiharis'));
}

public function saveFamily(Request $request)
{
    $rules = [
        // ✅ FIX: pratihari_id is string like PRATIHARI23504
        'pratihari_id'    => ['required', 'string', 'max:30', 'regex:/^PRATIHARI[0-9]+$/i'],

        'marital_status'  => ['required', 'in:married,unmarried'],

        'father_id'       => ['nullable'],
        'mother_id'       => ['nullable'],
        'father_name'     => ['nullable', 'string', 'max:255'],
        'mother_name'     => ['nullable', 'string', 'max:255'],

        'father_photo'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        'mother_photo'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

        // MAIN SPOUSE (dropdown + manual)
        'spouse_select'        => ['nullable'], // id OR 'manual'
        'spouse_name_manual'   => ['nullable', 'string', 'max:255'],

        'spouse_father_name'   => ['nullable', 'string', 'max:255'],
        'spouse_mother_name'   => ['nullable', 'string', 'max:255'],

        'spouse_photo'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        'spouse_father_photo'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        'spouse_mother_photo'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

        'children'                 => ['nullable', 'array'],
        'children.*.name'          => ['nullable', 'string', 'max:255'],
        'children.*.gender'        => ['nullable', 'in:male,female'],

        // Required when a child row is filled
        'children.*.dob'           => ['required_with:children.*.name,children.*.gender,children.*.photo,children.*.marital_status', 'date'],
        'children.*.photo'         => ['required_with:children.*.name,children.*.gender,children.*.dob,children.*.marital_status', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

        // Child new fields
        'children.*.marital_status'      => ['nullable', 'in:single,married,divorced,widowed'],
        'children.*.spouse_select'       => ['nullable'], // id OR 'manual'
        'children.*.spouse_name_manual'  => ['nullable', 'string', 'max:255'],
    ];

    $validator = Validator::make($request->all(), $rules);

    $validator->after(function ($v) use ($request) {

        if ($request->marital_status === 'married') {
            $sel = $request->input('spouse_select');

            if (empty($sel)) {
                $v->errors()->add('spouse_select', 'Spouse name is required.');
            } elseif ($sel === 'manual') {
                if (empty($request->input('spouse_name_manual'))) {
                    $v->errors()->add('spouse_name_manual', 'Please enter spouse name (manual).');
                }
            } else {
                if (!ctype_digit((string)$sel) || !PratihariProfile::where('id', $sel)->exists()) {
                    $v->errors()->add('spouse_select', 'Selected spouse is invalid.');
                }
            }
        }

        if ($request->marital_status === 'married' && is_array($request->children)) {
            foreach ((array) $request->children as $i => $child) {

                $hasAny =
                    !empty($child['name']) ||
                    !empty($child['dob']) ||
                    !empty($child['gender']) ||
                    !empty($child['photo']) ||
                    !empty($child['marital_status']) ||
                    !empty($child['spouse_select']) ||
                    !empty($child['spouse_name_manual']);

                if (!$hasAny) continue;

                $childStatus = $child['marital_status'] ?? null;

                if ($childStatus === 'married') {
                    $csel = $child['spouse_select'] ?? null;

                    if (empty($csel)) {
                        $v->errors()->add("children.$i.spouse_select", "Child spouse is required (row $i).");
                    } elseif ($csel === 'manual') {
                        if (empty($child['spouse_name_manual'])) {
                            $v->errors()->add("children.$i.spouse_name_manual", "Enter child spouse name manually (row $i).");
                        }
                    } else {
                        if (!ctype_digit((string)$csel) || !PratihariProfile::where('id', $csel)->exists()) {
                            $v->errors()->add("children.$i.spouse_select", "Invalid child spouse selection (row $i).");
                        }
                    }
                }
            }
        }
    });

    $validator->validate();

    try {
        DB::beginTransaction();

        // ✅ FIX: keep as string, not int
        $pratihariId = strtoupper(trim((string) $request->input('pratihari_id')));

        $makeName = function (string $suffix, \Illuminate\Http\UploadedFile $file) {
            return now()->format('YmdHis') . '_' . Str::uuid()->toString() . '_' . $suffix . '.' . $file->getClientOriginalExtension();
        };

        // Resolve MAIN spouse_name (from dropdown/manual)
        $resolvedSpouseName = null;
        if ($request->marital_status === 'married') {
            if ($request->spouse_select === 'manual') {
                $resolvedSpouseName = $request->spouse_name_manual;
            } else {
                $spouse = PratihariProfile::find($request->spouse_select);
                // ✅ FIX: use accessor full_name (first+middle+last)
                $resolvedSpouseName = $spouse?->full_name;
            }
        }

        $family = new PratihariFamily();
        $family->pratihari_id = $pratihariId;

        // Father
        if ($request->father_id === 'other') {
            $family->father_name = $request->father_name;

            if ($request->hasFile('father_photo')) {
                $fatherPhoto = $request->file('father_photo');
                $fatherPhotoName = $makeName('father', $fatherPhoto);
                $fatherPhoto->move(public_path('uploads/family'), $fatherPhotoName);
                $family->father_photo = asset('uploads/family/' . $fatherPhotoName);
            }
        } elseif (!empty($request->father_id)) {
            $selectedFather = PratihariFamily::find($request->father_id);
            $family->father_name  = $selectedFather?->father_name;
            $family->father_photo = $selectedFather?->father_photo;
        } else {
            $family->father_name  = $request->father_name;
        }

        // Mother
        if ($request->mother_id === 'other') {
            $family->mother_name = $request->mother_name;

            if ($request->hasFile('mother_photo')) {
                $motherPhoto = $request->file('mother_photo');
                $motherPhotoName = $makeName('mother', $motherPhoto);
                $motherPhoto->move(public_path('uploads/family'), $motherPhotoName);
                $family->mother_photo = asset('uploads/family/' . $motherPhotoName);
            }
        } elseif (!empty($request->mother_id)) {
            $selectedMother = PratihariFamily::find($request->mother_id);
            $family->mother_name  = $selectedMother?->mother_name;
            $family->mother_photo = $selectedMother?->mother_photo;
        } else {
            $family->mother_name  = $request->mother_name;
        }

        $family->maritial_status    = $request->marital_status;   // keep your column spelling
        $family->spouse_name        = $resolvedSpouseName;
        $family->spouse_father_name = $request->spouse_father_name;
        $family->spouse_mother_name = $request->spouse_mother_name;

        if ($request->hasFile('spouse_photo')) {
            $spousePhoto = $request->file('spouse_photo');
            $spousePhotoName = $makeName('spouse', $spousePhoto);
            $spousePhoto->move(public_path('uploads/family'), $spousePhotoName);
            $family->spouse_photo = asset('uploads/family/' . $spousePhotoName);
        }

        if ($request->hasFile('spouse_father_photo')) {
            $spouseFatherPhoto = $request->file('spouse_father_photo');
            $spouseFatherPhotoName = $makeName('spouse_father', $spouseFatherPhoto);
            $spouseFatherPhoto->move(public_path('uploads/family'), $spouseFatherPhotoName);
            $family->spouse_father_photo = asset('uploads/family/' . $spouseFatherPhotoName);
        }

        if ($request->hasFile('spouse_mother_photo')) {
            $spouseMotherPhoto = $request->file('spouse_mother_photo');
            $spouseMotherPhotoName = $makeName('spouse_mother', $spouseMotherPhoto);
            $spouseMotherPhoto->move(public_path('uploads/family'), $spouseMotherPhotoName);
            $family->spouse_mother_photo = asset('uploads/family/' . $spouseMotherPhotoName);
        }

        $family->save();

        // Children save
        if ($request->marital_status === 'married' && $request->has('children')) {
            foreach ((array) $request->children as $child) {

                $hasAny =
                    !empty($child['name']) ||
                    !empty($child['dob']) ||
                    !empty($child['gender']) ||
                    !empty($child['photo']) ||
                    !empty($child['marital_status']) ||
                    !empty($child['spouse_select']) ||
                    !empty($child['spouse_name_manual']);

                if (!$hasAny) continue;

                if (empty($child['dob']) || empty($child['photo'])) {
                    throw new \Exception('Child Date of Birth and Photo are required.');
                }

                $childSpouseName = null;
                $childMaritalStatus = $child['marital_status'] ?? null;

                if ($childMaritalStatus === 'married') {
                    if (($child['spouse_select'] ?? null) === 'manual') {
                        $childSpouseName = $child['spouse_name_manual'] ?? null;
                    } else {
                        $sp = PratihariProfile::find($child['spouse_select'] ?? null);
                        $childSpouseName = $sp?->full_name; // ✅ use full_name
                    }
                }

                $childData = new PratihariChildren();
                $childData->pratihari_id    = $pratihariId;  // ✅ string saved correctly
                $childData->children_name   = $child['name'] ?? null;
                $childData->date_of_birth   = $child['dob'] ?? null;
                $childData->gender          = $child['gender'] ?? null;
                $childData->marital_status  = $childMaritalStatus;
                $childData->spouse_name     = $childSpouseName;

                if (isset($child['photo']) && $child['photo'] instanceof \Illuminate\Http\UploadedFile) {
                    $childPhoto = $child['photo'];
                    $childPhotoName = $makeName('child', $childPhoto);
                    $childPhoto->move(public_path('uploads/children'), $childPhotoName);
                    $childData->photo = asset('uploads/children/' . $childPhotoName);
                }

                $childData->save();
            }
        }

        DB::commit();

        return redirect()
            ->route('admin.pratihariIdcard', ['pratihari_id' => $family->pratihari_id])
            ->with('success', 'Family data saved successfully');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error in saveFamily: ' . $e->getMessage(), ['exception' => $e]);
        return back()->withInput()->with('error', $e->getMessage() ?: 'Something went wrong while saving family details. Please try again.');
    }
}

public function edit($pratihari_id)
{
    $family = PratihariFamily::with('children')
        ->where('pratihari_id', $pratihari_id)
        ->firstOrFail();

    // For spouse dropdowns (main + child)
    $pratiharis = PratihariProfile::select('id', 'first_name', 'middle_name', 'last_name')
        ->orderBy('first_name')
        ->orderBy('middle_name')
        ->orderBy('last_name')
        ->get();

    return view('admin.update-family-details', compact('family', 'pratiharis'));
}
public function updateFamily(Request $request, $pratihariId)
{
    // ---- Validation rules (update) ----
    $rules = [
        'marital_status' => ['required', 'in:married,unmarried'],

        'father_name' => ['nullable', 'string', 'max:255'],
        'mother_name' => ['nullable', 'string', 'max:255'],

        'father_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        'mother_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

        // Spouse (select/manual)
        'spouse_select'       => ['nullable'],
        'spouse_name_manual'  => ['nullable', 'string', 'max:255'],
        'spouse_photo'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

        // Children array
        'children'                => ['nullable', 'array'],
        'children.*.id'           => ['nullable', 'integer'],
        'children.*.name'         => ['nullable', 'string', 'max:255'],
        'children.*.dob'          => ['nullable', 'date'],
        'children.*.gender'       => ['nullable', 'in:male,female'],
        'children.*.photo'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

        // Child new fields
        'children.*.marital_status'     => ['nullable', 'in:single,married,divorced,widowed'],
        'children.*.spouse_select'      => ['nullable'],
        'children.*.spouse_name_manual' => ['nullable', 'string', 'max:255'],
    ];

    $validator = Validator::make($request->all(), $rules);

    $validator->after(function ($v) use ($request) {

        // Main spouse required only if married
        if ($request->input('marital_status') === 'married') {
            $sel = $request->input('spouse_select');

            if (empty($sel)) {
                $v->errors()->add('spouse_select', 'Spouse is required when marital status is married.');
            } elseif ($sel === 'manual') {
                if (empty($request->input('spouse_name_manual'))) {
                    $v->errors()->add('spouse_name_manual', 'Please enter spouse name (manual).');
                }
            } else {
                if (!ctype_digit((string)$sel) || !PratihariProfile::where('id', $sel)->exists()) {
                    $v->errors()->add('spouse_select', 'Selected spouse is invalid.');
                }
            }
        }

        // Children: if any field in a row is filled -> require NAME, DOB, GENDER.
        // Photo required ONLY for new child (no id) OR if they upload a new photo.
        $children = (array) $request->input('children', []);
        foreach ($children as $i => $child) {

            $hasAny =
                !empty($child['name']) ||
                !empty($child['dob']) ||
                !empty($child['gender']) ||
                !empty($child['marital_status']) ||
                !empty($child['spouse_select']) ||
                !empty($child['spouse_name_manual']) ||
                $request->hasFile("children.$i.photo");

            if (!$hasAny) continue;

            if (empty($child['name']))   $v->errors()->add("children.$i.name", "Child name is required (row ".($i+1).").");
            if (empty($child['dob']))    $v->errors()->add("children.$i.dob",  "Child date of birth is required (row ".($i+1).").");
            if (empty($child['gender'])) $v->errors()->add("children.$i.gender", "Child gender is required (row ".($i+1).").");

            // Require photo for brand-new child
            $isNew = empty($child['id']);
            if ($isNew && !$request->hasFile("children.$i.photo")) {
                $v->errors()->add("children.$i.photo", "Child photo is required for new child (row ".($i+1).").");
            }

            // Child spouse required if child is married
            $childStatus = $child['marital_status'] ?? null;
            if ($childStatus === 'married') {
                $csel = $child['spouse_select'] ?? null;

                if (empty($csel)) {
                    $v->errors()->add("children.$i.spouse_select", "Child spouse is required (row ".($i+1).").");
                } elseif ($csel === 'manual') {
                    if (empty($child['spouse_name_manual'])) {
                        $v->errors()->add("children.$i.spouse_name_manual", "Enter child spouse name manually (row ".($i+1).").");
                    }
                } else {
                    if (!ctype_digit((string)$csel) || !PratihariProfile::where('id', $csel)->exists()) {
                        $v->errors()->add("children.$i.spouse_select", "Invalid child spouse selection (row ".($i+1).").");
                    }
                }
            }
        }
    });

    $validator->validate();

    try {
        DB::beginTransaction();

        $family = PratihariFamily::where('pratihari_id', $pratihariId)->first();
        if (!$family) {
            throw new \Exception('Family record not found.');
        }

        $makeName = function (string $suffix, \Illuminate\Http\UploadedFile $file) {
            return now()->format('YmdHis') . '_' . Str::uuid()->toString() . '_' . $suffix . '.' . $file->getClientOriginalExtension();
        };

        // ----- Resolve spouse name from select/manual -----
        $resolvedSpouseName = null;
        if ($request->input('marital_status') === 'married') {
            if ($request->input('spouse_select') === 'manual') {
                $resolvedSpouseName = $request->input('spouse_name_manual');
            } else {
                $spouse = PratihariProfile::find($request->input('spouse_select'));
                $resolvedSpouseName = $spouse ? trim($spouse->first_name.' '.$spouse->middle_name.' '.$spouse->last_name) : null;
            }
        }

        // ----- Update family fields -----
        $family->father_name      = $request->input('father_name');
        $family->mother_name      = $request->input('mother_name');

        // Keep your DB column spelling (your earlier code uses maritial_status)
        $family->maritial_status  = $request->input('marital_status');
        $family->spouse_name      = $resolvedSpouseName;

        // ----- Upload family photos -----
        if ($request->hasFile('father_photo')) {
            $f = $request->file('father_photo');
            $name = $makeName('father', $f);
            $f->move(public_path('uploads/family'), $name);
            $family->father_photo = 'uploads/family/' . $name;
        }

        if ($request->hasFile('mother_photo')) {
            $m = $request->file('mother_photo');
            $name = $makeName('mother', $m);
            $m->move(public_path('uploads/family'), $name);
            $family->mother_photo = 'uploads/family/' . $name;
        }

        if ($request->hasFile('spouse_photo')) {
            $s = $request->file('spouse_photo');
            $name = $makeName('spouse', $s);
            $s->move(public_path('uploads/family'), $name);
            $family->spouse_photo = 'uploads/family/' . $name;
        }

        $family->save();

        // ----- Update children (add/edit/delete) -----
        $incoming = (array) $request->input('children', []);
        $keepIds = [];

        foreach ($incoming as $index => $child) {

            $hasAny =
                !empty($child['name']) ||
                !empty($child['dob']) ||
                !empty($child['gender']) ||
                !empty($child['marital_status']) ||
                !empty($child['spouse_select']) ||
                !empty($child['spouse_name_manual']) ||
                $request->hasFile("children.$index.photo");

            if (!$hasAny) continue;

            $childId = $child['id'] ?? null;

            $childModel = null;
            if (!empty($childId)) {
                $childModel = PratihariChildren::where('id', $childId)
                    ->where('pratihari_id', $pratihariId)
                    ->first();
            }

            if (!$childModel) {
                $childModel = new PratihariChildren();
                $childModel->pratihari_id = $pratihariId;
            }

            // Resolve child spouse name (only if married)
            $childSpouseName = null;
            $childMaritalStatus = $child['marital_status'] ?? null;

            if ($childMaritalStatus === 'married') {
                if (($child['spouse_select'] ?? null) === 'manual') {
                    $childSpouseName = $child['spouse_name_manual'] ?? null;
                } else {
                    $sp = PratihariProfile::find($child['spouse_select'] ?? null);
                    $childSpouseName = $sp ? trim($sp->first_name.' '.$sp->middle_name.' '.$sp->last_name) : null;
                }
            }

            $childModel->children_name  = $child['name'] ?? null;
            $childModel->date_of_birth  = $child['dob'] ?? null;
            $childModel->gender         = $child['gender'] ?? null;

            // New fields
            $childModel->marital_status = $childMaritalStatus;
            $childModel->spouse_name    = $childSpouseName;

            // Photo upload (IMPORTANT: use array index, not id)
            if ($request->hasFile("children.$index.photo")) {
                $cf = $request->file("children.$index.photo");
                $name = $makeName('child', $cf);
                $cf->move(public_path('uploads/children'), $name);
                $childModel->photo = 'uploads/children/' . $name;
            }

            $childModel->save();

            $keepIds[] = $childModel->id;
        }

        // Delete removed children (anything not submitted)
        PratihariChildren::where('pratihari_id', $pratihariId)
            ->when(count($keepIds) > 0, function ($q) use ($keepIds) {
                $q->whereNotIn('id', $keepIds);
            }, function ($q) {
                // If no children submitted at all, delete all
            })
            ->delete();

        DB::commit();

        return redirect()
            ->route('admin.viewProfile', ['pratihari_id' => $pratihariId])
            ->with('success', 'Family data updated successfully');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error in updateFamily: ' . $e->getMessage(), ['exception' => $e]);
        return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}

}
