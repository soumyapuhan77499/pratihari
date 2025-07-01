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

class PratihariSebaController extends Controller
{
   // Controller methods
    public function pratihariSeba()
    {
        $sebas = PratihariSebaMaster::where('status', 'active')->get(); 
            
        return view('admin.pratihari-seba-details', compact('sebas'));
    }

    public function getBeddhaBySeba($seba_id)
    {
        $beddhas = PratihariSebaBeddhaAssign::where('seba_id', $seba_id)
            ->join('master__beddha', 'master__seba_beddha_assign.beddha_id', '=', 'master__beddha.id')
            ->select('master__beddha.id', 'master__beddha.beddha_name')
            ->get();
        return response()->json($beddhas);
    }

//     public function getBeddha($sebaId)
// {
//     $beddhaIds = PratihariSebaBeddhaAssign::where('seba_id', $sebaId)->pluck('beddha_id');
//     $beddhas = PratihariBeddhaMaster::whereIn('id', $beddhaIds)->get();

//     return response()->json($beddhas);
// }


    public function saveSeba(Request $request)
    {
        try {
            $sebaIds = $request->seba_id;
            $beddhaIds = $request->beddha_id ?? [];
            $pratihariId = $request->pratihari_id;

            foreach ($sebaIds as $sebaId) {
                // Get corresponding Beddha IDs for this Seba ID
                $beddhaList = isset($beddhaIds[$sebaId]) ? $beddhaIds[$sebaId] : [];

                // Skip if no Beddha IDs are provided
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

            return redirect()->route('admin.pratihariSocialMedia', ['pratihari_id' => $pratihariId])
                            ->with('success', 'Pratihari Seba details saved successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
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

            PratihariSeba::where('pratihari_id', $pratihariId)->delete();

            foreach ($sebaIds as $sebaId) {
                $beddhaList = isset($beddhaIds[$sebaId]) ? $beddhaIds[$sebaId] : [];
                $beddhaIdsString = !empty($beddhaList) ? implode(',', $beddhaList) : null;

                PratihariSeba::create([
                    'pratihari_id' => $pratihariId,
                    'seba_id' => $sebaId,
                    'beddha_id' => $beddhaIdsString,
                ]);
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

        // Get all Pratiharis with full name
        $pratiharis = PratihariProfile::all()->mapWithKeys(function ($item) {
            $fullName = trim("{$item->first_name} {$item->middle_name} {$item->last_name}");
            return [$item->pratihari_id => $fullName];
        });

        // Get all sebas (active)
        $sebas = PratihariSebaMaster::where('status', 'active')->get();
        $assignedBeddhas = [];
        $beddhas = [];
        $sebaNames = [];

        foreach ($sebas as $seba) {
            $seba_id = $seba->id;
            $sebaNames[$seba_id] = $seba->seba_name;

            // All available beddhas for this seba
            $beddhaIds = PratihariSebaBeddhaAssign::where('seba_id', $seba_id)->pluck('beddha_id');
            $beddhas[$seba_id] = PratihariBeddhaMaster::whereIn('id', $beddhaIds)->get();

            // Assigned beddhas for this seba & pratihari
            $assignedBeddhaStr = PratihariSeba::where('pratihari_id', $pratihari_id)
                ->where('seba_id', $seba_id)
                ->value('beddha_id');

            $assignedBeddhas[$seba_id] = is_array($assignedBeddhaStr)
                ? $assignedBeddhaStr
                : ($assignedBeddhaStr ? explode(',', $assignedBeddhaStr) : []);
        }

        return view('admin.assign-pratihari-seba', compact(
            'pratiharis',
            'sebas',
            'beddhas',
            'assignedBeddhas',
            'sebaNames'
        ));
    }

    public function savePratihariAssignSeba(Request $request)
{
    try {
        $sebaIds = $request->input('seba_id', []);
        $beddhaIds = $request->input('beddha_id', []);
        $pratihariId = $request->input('pratihari_id');

        if (!$pratihariId) {
            return redirect()->back()->with('error', 'Missing pratihari_id in request.');
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
        }

        return redirect()->back()->with('success', 'Assignments updated successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()->withErrors($e->validator)->withInput();

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}

}
