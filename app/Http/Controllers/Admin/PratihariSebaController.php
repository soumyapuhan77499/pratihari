<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariNijogaMaster;
use App\Models\PratihariNijogaSebaAssign;
use App\Models\PratihariSebaBeddhaAssign;
use App\Models\PratihariSeba;
use App\Models\PratihariSebaMaster;
use App\Models\PratihariBeddhaMaster;
use App\Models\PratihariProfile;
use App\Models\PratihariSebaMapping;
use Illuminate\Support\Facades\DB;

use App\Models\PratihariSebaAssignTransaction;
use Illuminate\Support\Facades\Auth;

class PratihariSebaController extends Controller
{
    public function pratihariSeba()
    {
        $sebas = PratihariSebaMaster::where('status', 'active')
            ->whereIn('type', ['pratihari', 'gochhikar'])
            ->get();

        return view('admin.pratihari-seba-details', compact('sebas'));
    }

    public function getBeddhaBySeba($seba_id)
    {
        $beddhas = PratihariSebaBeddhaAssign::where('seba_id', $seba_id)
            ->join('master__beddha', 'master__seba_beddha_assign.beddha_id', '=', 'master__beddha.id')
            ->select(
                'master__beddha.id',
                'master__beddha.beddha_name',
                'master__seba_beddha_assign.beddha_status'
            )
            ->get();

        return response()->json($beddhas);
    }

    public function saveSeba(Request $request)
    {
        try {
            DB::beginTransaction(); // Start DB transaction

            $sebaIds = $request->seba_id;
            $beddhaIds = $request->beddha_id ?? [];
            $pratihariId = $request->pratihari_id;

            // 🔁 Save all selected seba+beddha pairs
            foreach ($sebaIds as $sebaId) {
                $beddhaList = $beddhaIds[$sebaId] ?? [];

                if (empty($beddhaList)) {
                    continue;
                }

                $beddhaIdsString = implode(',', $beddhaList);

                PratihariSeba::create([
                    'pratihari_id' => $pratihariId,
                    'seba_id' => $sebaId,
                    'beddha_id' => $beddhaIdsString,
                ]);
            }

            // 🔁 Prepare mapping data for pratihari__seba_mapping
            $mappingData = ['pratihari_id' => $pratihariId];

            foreach ($sebaIds as $sebaId) {
                $beddhaList = $beddhaIds[$sebaId] ?? [];

                if (empty($beddhaList)) {
                    continue;
                }

                $beddhaIdsString = implode(',', $beddhaList);

                if (in_array((int)$sebaId, [1, 2, 3, 4, 5, 8])) {
                    // ✅ Use correct column names like 'seba_1', 'seba_2', ...
                    $mappingData["seba_$sebaId"] = $beddhaIdsString;
                }
            }

            // ✅ Save or update PratihariSebaMapping
            PratihariSebaMapping::updateOrCreate(
                ['pratihari_id' => $pratihariId],
                $mappingData
            );

            DB::commit();

            return redirect()->route('admin.pratihariSocialMedia', ['pratihari_id' => $pratihariId])
                            ->with('success', 'Pratihari Seba details saved successfully');
                            
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function edit($pratihari_id)
    {
        $assignedSebas = PratihariSeba::where('pratihari_id', $pratihari_id)
            ->pluck('seba_id')
            ->toArray();

        $assignedBeddhas = [];
        $beddhas = [];
        $sebaNames = [];

        foreach ($assignedSebas as $seba_id) {
            $beddhaIds = PratihariSebaBeddhaAssign::where('seba_id', $seba_id)->pluck('beddha_id');
            $beddhas[$seba_id] = PratihariBeddhaMaster::whereIn('id', $beddhaIds)->get();

            // Assigned Beddha IDs
            $assignedBeddhaStr = PratihariSeba::where('pratihari_id', $pratihari_id)
                ->where('seba_id', $seba_id)
                ->value('beddha_id');

            $assignedBeddhas[$seba_id] = is_array($assignedBeddhaStr) ? $assignedBeddhaStr : ($assignedBeddhaStr ? explode(',', $assignedBeddhaStr) : []);

            $sebaNames[$seba_id] = PratihariSebaMaster::where('id', $seba_id)->value('seba_name');
        }

        $sebas = PratihariSebaMaster::where('status', 'active')->get();

        return view('admin.update-seba-details', compact(
            'pratihari_id',
            'assignedSebas',
            'beddhas',
            'assignedBeddhas',
            'sebaNames',
            'sebas'
        ));
    }

    public function update(Request $request, $pratihariId)
    {
        try {
            $sebaIds = $request->seba_id;
            $beddhaIds = $request->beddha_id ?? [];

            // 🔄 Step 1: Delete old seba entries
            PratihariSeba::where('pratihari_id', $pratihariId)->delete();

            // 🔁 Step 2: Recreate PratihariSeba and prepare mapping data
            $mappingData = [];

            foreach ($sebaIds as $sebaId) {
                $sebaId = (int) $sebaId;

                $beddhaList = $beddhaIds[$sebaId] ?? [];
                $beddhaIdsString = !empty($beddhaList) ? implode(',', $beddhaList) : null;

                // Save to PratihariSeba table
                PratihariSeba::create([
                    'pratihari_id' => $pratihariId,
                    'seba_id' => $sebaId,
                    'beddha_id' => $beddhaIdsString,
                ]);

                // 💡 Map to correct seba_X columns only
                if (in_array($sebaId, [1, 2, 3, 4, 5, 8]) && $beddhaIdsString) {
                    $mappingData["seba_$sebaId"] = $beddhaIdsString;
                }
            }

            // 🔄 Step 3: Update or insert PratihariSebaMapping
            if (!empty($mappingData)) {
                $mappingData['pratihari_id'] = $pratihariId;

                PratihariSebaMapping::updateOrCreate(
                    ['pratihari_id' => $pratihariId],
                    $mappingData
                );
            }

            return redirect()->route('admin.viewProfile', ['pratihari_id' => $pratihariId])
                            ->with('success', 'Pratihari Seba details updated successfully');
                            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function PratihariSebaAssign(Request $request)
    {
        $pratihari_id = $request->get('pratihari_id');
        $year         = $request->get('year'); // if you want to use year later

        // ✅ Only approved pratiharis
        $pratiharis = PratihariProfile::query()
            ->where('pratihari_status', 'approved')
            ->orderBy('first_name')
            ->get()
            ->mapWithKeys(function ($item) {
                $fullName = trim("{$item->first_name} {$item->middle_name} {$item->last_name}");
                return [$item->pratihari_id => $fullName];
            });

        $sebas           = PratihariSebaMaster::where('status', 'active')->get();
        $assignedBeddhas = [];
        $beddhas         = [];
        $sebaNames       = [];

        foreach ($sebas as $seba) {
            $seba_id = $seba->id;
            $sebaNames[$seba_id] = $seba->seba_name;

            $beddhaIds = PratihariSebaBeddhaAssign::where('seba_id', $seba_id)
                ->where('beddha_status', 0)
                ->pluck('beddha_id');

            $beddhas[$seba_id] = PratihariBeddhaMaster::whereIn('id', $beddhaIds)->get();

            // Assigned beddhas for selected pratihari + seba (+ year if needed)
            $assignedValue = null;

            if ($pratihari_id) {
                // use ->value() to get raw column value; it can still be string, json-string, array (if cast), or collection
                $assignedValue = PratihariSeba::where('pratihari_id', $pratihari_id)
                    ->where('seba_id', $seba_id)
                    // ->where('year', $year) // uncomment if you want to filter by year
                    ->value('beddha_id');
            }

            // Normalize to array of IDs
            $assignedArray = [];

            if (is_null($assignedValue) || $assignedValue === '') {
                $assignedArray = [];
            } elseif (is_array($assignedValue)) {
                // already an array
                $assignedArray = array_values($assignedValue);
            } elseif ($assignedValue instanceof \Illuminate\Support\Collection) {
                $assignedArray = $assignedValue->values()->all();
            } elseif (is_string($assignedValue)) {
                $trimmed = trim($assignedValue);

                // If JSON array string like "[1,2,3]" -> json_decode
                if ($this->looksLikeJsonArray($trimmed)) {
                    $decoded = json_decode($trimmed, true);
                    $assignedArray = is_array($decoded) ? array_values($decoded) : [];
                } else {
                    // fallback: comma-separated string "1,2,3"
                    // explode and trim each item, ignore empty pieces
                    $parts = array_filter(array_map('trim', explode(',', $trimmed)), function ($v) {
                        return $v !== '';
                    });
                    $assignedArray = array_values($parts);
                }
            } else {
                // final fallback: try json decode if possible
                $maybeJson = @json_decode($assignedValue, true);
                if (is_array($maybeJson)) {
                    $assignedArray = array_values($maybeJson);
                } else {
                    $assignedArray = [];
                }
            }

            // ensure values are simple scalars (string/int)
            $assignedBeddhas[$seba_id] = array_map(function ($v) {
                // keep as string/int (trim)
                return is_scalar($v) ? (string)$v : (string)json_encode($v);
            }, $assignedArray);
        }

        return view('admin.assign-pratihari-seba', compact(
            'pratiharis',
            'sebas',
            'beddhas',
            'assignedBeddhas',
            'sebaNames',
            'pratihari_id',
            'year'
        ));
    }

    private function looksLikeJsonArray(string $s): bool
    {
        $s = trim($s);
        return (substr($s, 0, 1) === '[' && substr($s, -1) === ']');
    }
        
    public function savePratihariAssignSeba(Request $request)
    {
        try {
            $admins = Auth::guard('admins')->user();

            if (! $admins) {
                return redirect()->back()->with('error', 'User not authenticated.');
            }

            $assigned_by = $admins->admin_id;
            $sebaIds = $request->input('seba_id', []);
            $beddhaIdsFromRequest = $request->input('beddha_id', []); // array keyed by seba_id
            $pratihariId = $request->input('pratihari_id');
            $year = $request->input('year');

            if (! $pratihariId || ! $year) {
                return redirect()->back()->with('error', 'Missing pratihari_id or year in request.');
            }

            DB::transaction(function () use (
                $sebaIds,
                $beddhaIdsFromRequest,
                $pratihariId,
                $assigned_by,
                $year
            ) {
                foreach ($sebaIds as $sebaId) {
                    // Requested admin selection for this seba (may be missing)
                    $requestedList = $beddhaIdsFromRequest[$sebaId] ?? [];
                    // normalize to ints and unique
                    $requestedList = collect($requestedList)->map(fn($v) => (int) $v)->filter()->unique()->values()->all();

                    // All beddhas that are admin-assignable for this seba (beddha_status = 0)
                    $adminAllowed = PratihariSebaBeddhaAssign::where('seba_id', $sebaId)
                        ->where('beddha_status', 0)
                        ->pluck('beddha_id')
                        ->map(fn($v) => (int) $v)
                        ->unique()
                        ->values()
                        ->all();

                    // Keep only admin-allowed beddhas from the requested selection
                    $adminSelected = collect($requestedList)->intersect($adminAllowed)->values()->all();

                    // Load existing PratihariSeba record if any (we want to preserve user-assigned beddhas)
                    $existing = PratihariSeba::where('pratihari_id', $pratihariId)
                        ->where('seba_id', $sebaId)
                        ->first();

                    $existingBeddhas = $existing ? $existing->beddha_id : []; // accessor returns array

                    // Determine which of existing beddhas are admin-allowed (so considered admin-owned)
                    $existingAdmin = collect($existingBeddhas)->intersect($adminAllowed)->values()->all();
                    $existingUser = collect($existingBeddhas)->diff($existingAdmin)->values()->all();

                    // Final saved beddhas = keep user-assigned always + adminSelected (from form)
                    $finalBeddhas = collect($existingUser)->merge($adminSelected)->unique()->values()->all();

                    if (empty($finalBeddhas)) {
                        // nothing remains -> delete row if exists
                        if ($existing) {
                            $existing->delete();
                        }
                        // still record admin transaction as empty (admin cleared)
                        PratihariSebaAssignTransaction::create([
                            'pratihari_id' => $pratihariId,
                            'assigned_by' => $assigned_by,
                            'seba_id' => $sebaId,
                            'beddha_id' => '', // admin cleared
                            'year' => $year,
                            'date_time' => now('Asia/Kolkata'),
                        ]);
                        continue;
                    }

                    // Save (create or update). Use array so model mutator stores CSV consistently.
                    PratihariSeba::updateOrCreate(
                        [
                            'pratihari_id' => $pratihariId,
                            'seba_id' => $sebaId,
                        ],
                        [
                            'beddha_id' => $finalBeddhas,
                        ]
                    );

                    // Log the admin-assigned list (only the admin-provided/allowed set)
                    $adminCsvForLog = implode(',', $adminSelected);
                    PratihariSebaAssignTransaction::create([
                        'pratihari_id' => $pratihariId,
                        'assigned_by' => $assigned_by,
                        'seba_id' => $sebaId,
                        'beddha_id' => $adminCsvForLog,
                        'year' => $year,
                        'date_time' => now('Asia/Kolkata'),
                    ]);
                }
            });

            return redirect()->back()->with('success', 'Assignments updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Error in savePratihariAssignSeba: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

}
