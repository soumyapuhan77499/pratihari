<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariNijogaMaster;
use App\Models\PratihariNijogaSebaAssign;
use App\Models\PratihariSebaBeddhaAssign;
use App\Models\PratihariSeba;


class PratihariSebaController extends Controller
{
    public function pratihariSeba()
    {
        $nijogas = PratihariNijogaMaster::all(); // Fetch all Nijoga names

        return view('admin.pratihari-seba-details', compact('nijogas'));
    }


    public function getSebaByNijoga($nijoga_id)
{
    $sebas = PratihariNijogaSebaAssign::where('nijoga_id', $nijoga_id)
                                      ->join('master__seba', 'master__nijoga_seba_assign.seba_id', '=', 'master__seba.id')
                                      ->select('master__seba.id', 'master__seba.seba_name')
                                      ->get();
    return response()->json($sebas);
}

public function getBeddhaBySeba($seba_id)
{
    $beddhas = PratihariSebaBeddhaAssign::where('seba_id', $seba_id)
                                        ->join('master__beddha', 'master__seba_beddha_assign.beddha_id', '=', 'master__beddha.id')
                                        ->select('master__beddha.id', 'master__beddha.beddha_name')
                                        ->get();
    return response()->json($beddhas);
}

public function saveSeba(Request $request)
{
    try {
       
        $nijogaId = $request->nijoga_type;
        $sebaIds = $request->seba_id;
        $beddhaIds = $request->beddha_id ?? []; // Now correctly structured
        $pratihariId = $request->pratihari_id;

        foreach ($sebaIds as $sebaId) {
            // Get corresponding Beddha IDs for this Seba ID
            $beddhaList = isset($beddhaIds[$sebaId]) ? $beddhaIds[$sebaId] : [];
            $beddhaIdsString = !empty($beddhaList) ? implode(',', $beddhaList) : null;

            PratihariSeba::create([
                'pratihari_id' => $pratihariId,
                'nijoga_id' => $nijogaId,
                'seba_id' => $sebaId,
                'beddha_id' => $beddhaIdsString, // Now correctly saves only related beddhas
            ]);
        }

        return redirect()->route('admin.pratihariSocialMedia', ['pratihari_id' => $pratihariId])->with('success', 'Pratihari Seba details saved successfully');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()->withErrors($e->validator)->withInput();
    
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}

}
