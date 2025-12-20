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
            'pratihari_id'    => ['required'],
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

            // NEW child fields
            'children.*.marital_status'      => ['nullable', 'in:single,married,divorced,widowed'],
            'children.*.spouse_select'       => ['nullable'], // id OR 'manual'
            'children.*.spouse_name_manual'  => ['nullable', 'string', 'max:255'],
        ];

        $validator = Validator::make($request->all(), $rules);

        // Conditional validations
        $validator->after(function ($v) use ($request) {
            // Main spouse required only if member is married
            if ($request->marital_status === 'married') {
                $sel = $request->input('spouse_select');

                if (empty($sel)) {
                    $v->errors()->add('spouse_select', 'Spouse name is required.');
                } elseif ($sel === 'manual') {
                    if (empty($request->input('spouse_name_manual'))) {
                        $v->errors()->add('spouse_name_manual', 'Please enter spouse name (manual).');
                    }
                } else {
                    // Must be existing PratihariProfile id
                    if (!ctype_digit((string)$sel) || !PratihariProfile::where('id', $sel)->exists()) {
                        $v->errors()->add('spouse_select', 'Selected spouse is invalid.');
                    }
                }
            }

            // Children spouse required only if child marital_status = married
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

                    if (!$hasAny) {
                        continue;
                    }

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

            $pratihariId = (int) $request->input('pratihari_id');

            // Helper for unique filenames
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
                    $resolvedSpouseName = $spouse?->name; // change if your column differs
                }
            }

            // Save Family Data
            $family = new PratihariFamily();
            $family->pratihari_id = $pratihariId;

            // Father Handling
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

            // Mother Handling
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

            // Keep your existing column name (you used maritial_status)
            $family->maritial_status    = $request->marital_status;

            // UPDATED: spouse_name from dropdown/manual
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

            // Save Children Data ONLY when member is married
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

                    if (!$hasAny) {
                        continue;
                    }

                    if (empty($child['dob']) || empty($child['photo'])) {
                        throw new \Exception('Child Date of Birth and Photo are required.');
                    }

                    // Resolve CHILD spouse_name if child married
                    $childSpouseName = null;
                    $childMaritalStatus = $child['marital_status'] ?? null;

                    if ($childMaritalStatus === 'married') {
                        if (($child['spouse_select'] ?? null) === 'manual') {
                            $childSpouseName = $child['spouse_name_manual'] ?? null;
                        } else {
                            $sp = PratihariProfile::find($child['spouse_select'] ?? null);
                            $childSpouseName = $sp?->name; // change if your column differs
                        }
                    }

                    $childData = new PratihariChildren();
                    $childData->pratihari_id    = $pratihariId;
                    $childData->children_name   = $child['name'] ?? null;
                    $childData->date_of_birth   = $child['dob'] ?? null;
                    $childData->gender          = $child['gender'] ?? null;

                    // NEW fields
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

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            $userMessage = 'Something went wrong while saving family details. Please try again.';

            if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
                $raw = $e->errorInfo[2] ?? $e->getMessage();
                if (Str::contains($raw, 'pratihari_id')) {
                    $userMessage = 'Family details for this member already exist.';
                } else {
                    $userMessage = 'Duplicate entry detected. Please check the values and try again.';
                }
            }

            Log::error('DB error in saveFamily: ' . $e->getMessage(), ['exception' => $e]);

            return back()->withInput()->with('error', $userMessage);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error in saveFamily: ' . $e->getMessage(), ['exception' => $e]);

            return back()->withInput()->with('error', $e->getMessage() ?: 'Something went wrong while saving family details. Please try again.');
        }
    }

public function edit($pratihari_id)
{
    $family = PratihariFamily::with('children')->where('pratihari_id', $pratihari_id)->first();
    return view('admin.update-family-details', compact('family'));
}

public function updateFamily(Request $request, $pratihariId)
{
    try {
        $family = PratihariFamily::where('pratihari_id', $pratihariId)->first();

        if (!$family) {
            throw new \Exception('Family record not found.');
        }

        // Update Family Data
        $family->father_name = $request->father_name;
        $family->mother_name = $request->mother_name;
        $family->maritial_status = $request->marital_status;
        $family->spouse_name = $request->spouse_name;

        // Handle File Uploads
        if ($request->hasFile('father_photo')) {
            $fatherPhoto = $request->file('father_photo');
            $fatherPhotoName = time() . '_father.' . $fatherPhoto->getClientOriginalExtension();
            $fatherPhoto->move(public_path('uploads/family'), $fatherPhotoName);
            $family->father_photo = 'uploads/family/' . $fatherPhotoName;
        }

        if ($request->hasFile('mother_photo')) {
            $motherPhoto = $request->file('mother_photo');
            $motherPhotoName = time() . '_mother.' . $motherPhoto->getClientOriginalExtension();
            $motherPhoto->move(public_path('uploads/family'), $motherPhotoName);
            $family->mother_photo = 'uploads/family/' . $motherPhotoName;
        }

        if ($request->hasFile('spouse_photo')) {
            $spousePhoto = $request->file('spouse_photo');
            $spousePhotoName = time() . '_spouse.' . $spousePhoto->getClientOriginalExtension();
            $spousePhoto->move(public_path('uploads/family'), $spousePhotoName);
            $family->spouse_photo = 'uploads/family/' . $spousePhotoName;
        }

        $family->save();

        // Update Children Data
        if ($request->has('children')) {
            foreach ($request->children as $child) {
                $childData = PratihariChildren::where('id', $child['id'])->where('pratihari_id', $pratihariId)->first();
                
                if (!$childData) {
                    $childData = new PratihariChildren();
                    $childData->pratihari_id = $pratihariId;
                }

                $childData->children_name = $child['name'];
                $childData->date_of_birth = $child['dob'];
                $childData->gender = $child['gender'];

                if (isset($child['photo']) && $request->hasFile("children.{$child['id']}.photo")) {
                    $childPhoto = $request->file("children.{$child['id']}.photo");
                    $childPhotoName = time() . '_child.' . $childPhoto->getClientOriginalExtension();
                    $childPhoto->move(public_path('uploads/children'), $childPhotoName);
                    $childData->photo = 'uploads/children/' . $childPhotoName;
                }

                $childData->save();
            }
        }
        
        return redirect()->route('admin.viewProfile', ['pratihari_id' => $pratihariId])->with('success', 'Family data updated successfully');


    } catch (\Exception $e) {
        return back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }

}

}
