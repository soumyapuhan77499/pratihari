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

    // public function saveSeba(Request $request)
    // {
    //     try {
    //         $sebaIds = $request->seba_id;
    //         $beddhaIds = $request->beddha_id ?? [];
    //         $pratihariId = $request->pratihari_id;

    //         foreach ($sebaIds as $sebaId) {
    //             // Get corresponding Beddha IDs for this Seba ID
    //             $beddhaList = isset($beddhaIds[$sebaId]) ? $beddhaIds[$sebaId] : [];

    //             // Skip if no Beddha IDs are provided
    //             if (empty($beddhaList)) {
    //                 continue;
    //             }

    //             $beddhaIdsString = implode(',', $beddhaList);

    //             PratihariSeba::create([
    //                 'pratihari_id' => $pratihariId,
    //                 'seba_id' => $sebaId,
    //                 'beddha_id' => $beddhaIdsString,
    //             ]);
    //         }

    //         return redirect()->route('admin.pratihariSocialMedia', ['pratihari_id' => $pratihariId])
    //                         ->with('success', 'Pratihari Seba details saved successfully');

    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return redirect()->back()->withErrors($e->validator)->withInput();

    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
    //     }
    // }

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

        $sebas            = PratihariSebaMaster::where('status', 'active')->get();
        $assignedBeddhas  = [];
        $beddhas          = [];
        $sebaNames        = [];

        foreach ($sebas as $seba) {
            $seba_id = $seba->id;
            $sebaNames[$seba_id] = $seba->seba_name;

            $beddhaIds = PratihariSebaBeddhaAssign::where('seba_id', $seba_id)
                ->where('beddha_status', 0)
                ->pluck('beddha_id');

            $beddhas[$seba_id] = PratihariBeddhaMaster::whereIn('id', $beddhaIds)->get();

            // Assigned beddhas for selected pratihari + seba (+ year if needed)
            $assignedBeddhaStr = null;

            if ($pratihari_id) {
                $assignedBeddhaStr = PratihariSeba::where('pratihari_id', $pratihari_id)
                    ->where('seba_id', $seba_id)
                    // ->where('year', $year)  // uncomment if `year` column exists and you want to filter
                    ->value('beddha_id');
            }

            $assignedBeddhas[$seba_id] = $assignedBeddhaStr
                ? explode(',', $assignedBeddhaStr)
                : [];
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

    public function savePratihariAssignSeba(Request $request)
    {
        try {
            $admins = Auth::guard('admins')->user();

            if (!$admins) {
                return redirect()->back()->with('error', 'User not authenticated.');
            }

            $assigned_by = $admins->admin_id;
            $sebaIds = $request->input('seba_id', []);
            $beddhaIds = $request->input('beddha_id', []);
            $pratihariId = $request->input('pratihari_id');
            $year = $request->input('year');

            if (!$pratihariId || !$year) {
                return redirect()->back()->with('error', 'Missing pratihari_id or year in request.');
            }

            foreach ($sebaIds as $sebaId) {
                $beddhaList = $beddhaIds[$sebaId] ?? [];

                if (empty($beddhaList)) {
                    PratihariSeba::where('pratihari_id', $pratihariId)
                        ->where('seba_id', $sebaId)
                        ->delete();
                    continue;
                }

                $beddhaIdsString = implode(',', $beddhaList);

                PratihariSeba::updateOrCreate(
                    [
                        'pratihari_id' => $pratihariId,
                        'seba_id' => $sebaId,
                    ],
                    [
                        'beddha_id' => $beddhaIdsString,
                    ]
                );

                PratihariSebaAssignTransaction::create([
                    'pratihari_id' => $pratihariId,
                    'assigned_by' => $assigned_by,
                    'seba_id' => $sebaId,
                    'beddha_id' => $beddhaIdsString,
                    'year' => $year,
                    'date_time' => now('Asia/Kolkata'),
                ]);
            }

            return redirect()->back()->with('success', 'Assignments updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Error in savePratihariAssignSeba: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

//     public function getBeddha($sebaId)
// {
//     $beddhaIds = PratihariSebaBeddhaAssign::where('seba_id', $sebaId)->pluck('beddha_id');
//     $beddhas = PratihariBeddhaMaster::whereIn('id', $beddhaIds)->get();

//     return response()->json($beddhas);
// }

}
