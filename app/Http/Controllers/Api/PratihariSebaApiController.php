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
use App\Models\DateBeddhaMapping;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
            // Step 1: Get all active sebas
            $allSebas = PratihariSebaMaster::where('status', 'active')->get()->keyBy('id');

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

    // public function sebaDate(Request $request)
    // {
    //     try {
    //         $pratihariId = $request->input('pratihari_id');
    //         $events = [];

    //         if ($pratihariId) {
    //             $sebas = PratihariSeba::with('sebaMaster')
    //                 ->where('pratihari_id', $pratihariId)
    //                 ->get();

    //             foreach ($sebas as $seba) {
    //                 $sebaName = $seba->sebaMaster->seba_name ?? 'Unknown Seba';
    //                 $beddhaIds = $seba->beddha_id;

    //                 foreach ($beddhaIds as $beddhaId) {
    //                     $beddhaId = (int) trim($beddhaId);

    //                     if ($beddhaId >= 1 && $beddhaId <= 47) {
    //                         $startDate = Carbon::create(2025, 6, 1)->addDays($beddhaId - 1);
    //                         $endDate = Carbon::create(2030, 12, 31);
    //                         $nextDate = $startDate->copy();

    //                         while ($nextDate->lte($endDate)) {
    //                             $events[] = [
    //                                 'title' => "$sebaName - $beddhaId",
    //                                 'start' => $nextDate->toDateString(),
    //                                 'extendedProps' => [
    //                                     'sebaName' => $sebaName,
    //                                     'beddhaId' => $beddhaId
    //                                 ]
    //                             ];
    //                             $nextDate->addDays(47);
    //                         }
    //                     }
    //                 }
    //             }
    //         }

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Events fetched successfully.',
    //             'data' => $events
    //         ], 200);

    //     } catch (\Exception $e) {
    //         Log::error('Error fetching seba dates: ' . $e->getMessage());

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Server Error. Please try again later.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function todayBeddha()
    {
        $sebas = PratihariSeba::with(['sebaMaster', 'pratihari'])->get();
        $today = Carbon::today();
        $baseDate = Carbon::create(2025, 5, 22);
        $endDate = Carbon::create(2050, 12, 31);
        $todayBeddhaIds = [];

        foreach ($sebas as $seba) {
            $beddhaIds = is_array($seba->beddha_id) ? $seba->beddha_id : explode(',', $seba->beddha_id);

            foreach ($beddhaIds as $beddhaId) {
                $beddhaId = (int) trim($beddhaId);

                if ($beddhaId >= 1 && $beddhaId <= 47) {
                    $start = $baseDate->copy()->addDays($beddhaId - 1);
                    while ($start->lte($endDate)) {
                        if ($start->equalTo($today)) {
                            $todayBeddhaIds[] = $beddhaId;
                            break;
                        }
                        $start->addDays(47);
                    }
                }
            }
        }

        // Remove duplicate Beddha IDs
        $todayBeddhaIds = array_unique($todayBeddhaIds);
        $currentBeddhaDisplay = implode(', ', $todayBeddhaIds);

        return response()->json([
            'date' => $today->toDateString(),
            'beddha_ids_today' => $todayBeddhaIds,
            'beddha_ids_display' => $currentBeddhaDisplay,
        ]);
    }

    public function sebaDate()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            }

            $pratihariId = $user->pratihari_id;
            $data = [];

            $sebas = PratihariSeba::with('sebaMaster')
                        ->where('pratihari_id', $pratihariId)
                        ->get();

            foreach ($sebas as $seba) {
                $sebaName = $seba->sebaMaster->seba_name ?? 'Unknown Seba';
                $sebaId = $seba->seba_id;
                $beddhaIds = $seba->beddha_id;

                foreach ($beddhaIds as $beddhaId) {
                    $beddhaId = (int) trim($beddhaId);
                    if ($beddhaId < 1 || $beddhaId > 47) continue;

                    $intervalDays = ($sebaId == 9) ? 16 : 47;
                    $startDate = Carbon::create(2025, 5, 22)->addDays($beddhaId - 1);
                    $endDate = Carbon::create(2050, 12, 31);
                    $nextDate = $startDate->copy();

                    while ($nextDate->lte($endDate)) {
                        $dateStr = $nextDate->toDateString();

                        $data[$dateStr][] = [
                            'seba' => $sebaName,
                            'beddha_id' => $beddhaId,
                        ];

                        $nextDate->addDays($intervalDays);
                    }
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Seba data loaded successfully',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTodaySebaAssignments(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user || !$user->pratihari_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access: no associated pratihari_id.',
                ], 403);
            }

            $pratihariId = $user->pratihari_id;
            $today = Carbon::today();

            $baseDatePratihari = Carbon::create(2025, 7, 1);
            $endDatePratihari = Carbon::create(2050, 12, 31);

            $baseDateGochhikar = Carbon::create(2025, 7, 1);
            $endDateGochhikar = Carbon::create(2055, 12, 31);

            $pratihariEvents = [];
            $nijogaAssign = [];

            $gochhikarEvents = [];
            $nijogaGochhikarEvents = [];

            $todayPratihariBeddhaIds = [];
            $todayGochhikarBeddhaIds = [];

            // ✅ Filter by logged-in pratihari only
            $sebas = PratihariSeba::with(['sebaMaster', 'pratihari', 'beddhaAssigns'])
                ->where('pratihari_id', $pratihariId)
                ->get();

            foreach ($sebas as $seba) {
                $sebaId = $seba->seba_id;
                $sebaName = $seba->sebaMaster->seba_name ?? 'Unknown Seba';
                $beddhaIds = is_array($seba->beddha_id) ? $seba->beddha_id : explode(',', $seba->beddha_id);

                foreach ($beddhaIds as $beddhaId) {
                    $beddhaId = (int) trim($beddhaId);
                    if ($beddhaId < 1 || $beddhaId > 47) continue;

                    $beddhaStatus = $seba->beddhaAssigns->where('beddha_id', $beddhaId)->first()->beddha_status ?? null;
                    if ($beddhaStatus === null) continue;

                    $assignedUser = $seba->pratihari;
                    $interval = ($sebaId == 9) ? 16 : 47;

                    if ($sebaId == 9) {
                        $start = $baseDateGochhikar->copy()->addDays($beddhaId - 1);

                        while ($start->lte($endDateGochhikar)) {
                            if ($start->equalTo($today)) {
                                $label = "$sebaName | Beddha $beddhaId";
                                if ($assignedUser) {
                                    if ($beddhaStatus == 1) {
                                        $gochhikarEvents[$label][] = $assignedUser;
                                    } else {
                                        $nijogaGochhikarEvents[$label][] = $assignedUser;
                                    }
                                    $todayGochhikarBeddhaIds[] = $beddhaId;
                                }
                                break;
                            }
                            $start->addDays($interval);
                        }
                    } else {
                        $start = $baseDatePratihari->copy()->addDays($beddhaId - 1);

                        while ($start->lte($endDatePratihari)) {
                            if ($start->equalTo($today)) {
                                $label = "$sebaName | Beddha $beddhaId";
                                if ($assignedUser) {
                                    if ($beddhaStatus == 1) {
                                        $pratihariEvents[$label][] = $assignedUser;
                                    } else {
                                        $nijogaAssign[$label][] = $assignedUser;
                                    }
                                    $todayPratihariBeddhaIds[] = $beddhaId;
                                }
                                break;
                            }
                            $start->addDays($interval);
                        }
                    }
                }
            }

            // Deduplicate users
            $pratihariEvents = collect($pratihariEvents)->map(fn($u) => collect($u)->unique('pratihari_id')->values())->toArray();
            $nijogaAssign = collect($nijogaAssign)->map(fn($u) => collect($u)->unique('pratihari_id')->values())->toArray();
            $gochhikarEvents = collect($gochhikarEvents)->map(fn($u) => collect($u)->unique('pratihari_id')->values())->toArray();
            $nijogaGochhikarEvents = collect($nijogaGochhikarEvents)->map(fn($u) => collect($u)->unique('pratihari_id')->values())->toArray();

            return response()->json([
                'status' => true,
                'message' => 'Today\'s seba assignments fetched successfully.',
                'pratihari_events' => $pratihariEvents,
                'nijoga_pratihari_events' => $nijogaAssign,
                'gochhikar_events' => $gochhikarEvents,
                'nijoga_gochhikar_events' => $nijogaGochhikarEvents,
                'today_pratihari_beddhas' => array_unique($todayPratihariBeddhaIds),
                'today_gochhikar_beddhas' => array_unique($todayGochhikarBeddhaIds),
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching seba assignments.',
                'error' => $e->getMessage(), // Remove in production
            ], 500);
        }
    }

     public function storeDateBeddhaMapping(Request $request)
    {
        $startDate = Carbon::create('2025', '01', '01');
        $endDate = Carbon::create('2030', '12', '31'); // UPDATED END DATE

        $beddhaId = 33;
        $insertData = [];

        while ($startDate->lte($endDate)) {
            $insertData[] = [
                'date' => $startDate->toDateString(),
                'pratihari_beddha' => $beddhaId
            ];

            // Increment and loop beddha_id
            $beddhaId = ($beddhaId % 47) + 1;
            $startDate->addDay();
        }

        // Optional: Clear existing date range to avoid duplication
        DateBeddhaMapping::whereBetween('date', ['2025-01-01', '2030-12-31'])->delete();

        // Insert all at once (can be chunked for performance if needed)
        DateBeddhaMapping::insert($insertData);

        return response()->json(['message' => 'Date-Beddha mapping from 2025 to 2030 created successfully.']);
    }
}
