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
use App\Models\PratihariBeddhaMaster;

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
    public function getSebaByNijoga()
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
            // Step 1: Get all sebas
            $allSebas = PratihariSebaMaster::where('status','active')->all()->keyBy('id');

            // Step 2: Get all beddhas
            $allBeddhas = PratihariBeddhaMaster::all()->keyBy('id');

            // Step 3: Get active assignments with beddha relation
            $assignments = PratihariSebaBeddhaAssign::where('status', 'active')
                ->with('beddha')
                ->get();

            // Step 4: Group assignments by seba_id
            $groupedAssignments = $assignments->groupBy('seba_id');

            $result = [];

            // Step 5: For each seba (including those without assignments)
            foreach ($allSebas as $sebaId => $seba) {
                $items = $groupedAssignments->get($sebaId, collect());

                $result[] = [
                    'id' => $sebaId,
                    'name' => $seba->seba_name,
                    'bedha' => $items->map(function ($item) use ($sebaId) {
                        return [
                            'id' => $sebaId . '_' . $item->beddha->id,
                            'name' => $item->beddha->beddha_name,
                        ];
                    })->values()
                ];
            }

            return response()->json([
                'status' => 200,
                'message' => 'Beddhas fetched successfully',
                'data' => collect($result)->values()
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

    public function sebaDate(Request $request)
    {
        try {
            $pratihariId = $request->input('pratihari_id');
            $events = [];

            if ($pratihariId) {
                $sebas = PratihariSeba::with('sebaMaster')
                    ->where('pratihari_id', $pratihariId)
                    ->get();

                foreach ($sebas as $seba) {
                    $sebaName = $seba->sebaMaster->seba_name ?? 'Unknown Seba';
                    $beddhaIds = $seba->beddha_id;

                    foreach ($beddhaIds as $beddhaId) {
                        $beddhaId = (int) trim($beddhaId);

                        if ($beddhaId >= 1 && $beddhaId <= 47) {
                            $startDate = Carbon::create(2025, 6, 1)->addDays($beddhaId - 1);
                            $endDate = Carbon::create(2030, 12, 31);
                            $nextDate = $startDate->copy();

                            while ($nextDate->lte($endDate)) {
                                $events[] = [
                                    'title' => "$sebaName - $beddhaId",
                                    'start' => $nextDate->toDateString(),
                                    'extendedProps' => [
                                        'sebaName' => $sebaName,
                                        'beddhaId' => $beddhaId
                                    ]
                                ];
                                $nextDate->addDays(47);
                            }
                        }
                    }
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Events fetched successfully.',
                'data' => $events
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching seba dates: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Server Error. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
