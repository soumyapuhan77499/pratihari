<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariNijogaMaster;
use App\Models\PratihariNijogaSebaAssign;
use App\Models\PratihariSebaBeddhaAssign;
use App\Models\PratihariSebaManagement;
use App\Models\PratihariSeba;
use App\Models\PratihariSebaMaster;

use Carbon\Carbon;


use Illuminate\Support\Facades\Auth;

class PratihariSebaApiController extends Controller
{

    public function getNijogas(Request $request)
    {
        try {
            $nijogas = PratihariNijogaMaster::all();
    
            return response()->json([
                'status' => 200,
                'message' => 'Nijogas fetched successfully',
                'data' => $nijogas
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    // Get Seba list based on Nijoga ID
    public function getSebaByNijoga($nijoga_id)
    {
        try {
            $sebas = PratihariNijogaSebaAssign::where('nijoga_id', $nijoga_id)
                ->join('master__seba', 'master__nijoga_seba_assign.seba_id', '=', 'master__seba.id')
                ->select('master__seba.id', 'master__seba.seba_name')
                ->get();

            return response()->json([
                'status' => 200,
                'message' => 'Sebas fetched successfully',
                'data' => $sebas
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getBeddha() 
    {
        try {
            // Fetch active records with related beddha details
            $sebaBeddhas = PratihariSebaBeddhaAssign::where('status', 'active')
                ->with('beddha') // Load beddha details
                ->get()
                ->groupBy('seba_id');

            $formattedData = $sebaBeddhas->map(function ($items, $sebaId) {
                // Fetch seba_name for this sebaId
                $seba = PratihariSebaMaster::find($sebaId);

            return [
        'id' => $sebaId,
        'name' => $seba ? $seba->seba_name : 'Unknown Seba',
        'bedha' => $items->map(function ($item) use ($sebaId) {
            return [
                'id' => $sebaId . '_' . $item->beddha->id,  // concatenated id here
                'name' => $item->beddha->beddha_name,
            ];
        })->values(),
        ];

        })->values();

        return response()->json([
            'status' => 200,
            'message' => 'Beddhas fetched successfully',
            'data' => $formattedData
        ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function saveSeba(Request $request)
    {
        try {
            $user = Auth::user(); 

            if (!$user) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized. Please log in.',
                ], 401);
            }

        
            $nijogaId = $request->nijoga_type;
            $sebaIds = $request->seba_id;
            $beddhaIds = $request->beddha_id ?? [];

            $savedData = []; // Store created records

            foreach ($sebaIds as $sebaId) {
                $beddhaList = isset($beddhaIds[$sebaId]) ? $beddhaIds[$sebaId] : [];
                $beddhaIdsString = !empty($beddhaList) ? implode(',', $beddhaList) : null;

                $seba = PratihariSeba::create([
                    'pratihari_id' => $user->pratihari_id, // Assuming `pratihari_id` refers to the authenticated user
                    'nijoga_id' => $nijogaId,
                    'seba_id' => $sebaId,
                    'beddha_id' => $beddhaIdsString,
                ]);

                $savedData[] = $seba; // Add the created record to response array
            }

            return response()->json([
                'status' => 200,
                'message' => 'Pratihari Seba details saved successfully',
                'data' => $savedData, // Include saved data in response
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function startSeba(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 400);
        }

        $pratihariId = $user->pratihari_id;
        $now = Carbon::now('Asia/Kolkata');
        $today = $now->toDateString();

        // ❗ Check for duplicate entry
        $existing = PratihariSebaManagement::where('pratihari_id', $pratihariId)
            ->where('seba_id', $request->seba_id)
            ->where('beddha_id', $request->beddha_id)
            ->where('date', $today)
            ->first();

        if ($existing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Seba already started for this Beddha and Seba today.'
            ], 400); // HTTP 409 Conflict
        }

        // ✅ Create new entry
        $record = PratihariSebaManagement::create([
            'pratihari_id' => $pratihariId,
            'seba_id'      => $request->seba_id,
            'beddha_id'    => $request->beddha_id,
            'date'         => $today,
            'start_time'   => $now->format('H:i:s'),
            'seba_status'  => 'started',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Seba started successfully',
            'data' => $record
        ], 200);
    }

    public function endSeba(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['error' => 'User not authenticated'], 400);
    }

    $pratihariId = $user->pratihari_id;
    $today = Carbon::now('Asia/Kolkata')->toDateString();

    $record = PratihariSebaManagement::where('pratihari_id', $pratihariId)
        ->where('seba_id', $request->seba_id)
        ->where('beddha_id', $request->beddha_id)
        ->where('date', $today)
        ->where('seba_status', 'started')
        ->first();

    if (!$record) {
        return response()->json([
            'status' => 'error',
            'message' => 'Seba already started for this Beddha and Seba today.' 
        ], 400);
    }

    $record->update([
        'end_time' => Carbon::now('Asia/Kolkata')->format('H:i:s'),
        'seba_status' => 'completed',
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Seba ended successfully.',
        'data' => $record
    ],200);
}

}
