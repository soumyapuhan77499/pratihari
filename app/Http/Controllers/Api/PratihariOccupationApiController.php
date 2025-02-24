<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\PratihariOccupation;

class PratihariOccupationApiController extends Controller
{
    public function saveOccupation(Request $request)
    {
        try {
            // Authenticate the user
            $user = Auth::user();
            
            if (!$user || !$user->pratihari_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized. Please log in.',
                ], 401);
            }

            $pratihariId = $user->pratihari_id;

            // Convert the extra_activity array into a comma-separated string
            $extraActivities = $request->extra_activity ? implode(',', $request->extra_activity) : null;

            // Save occupation details
            $occupation = PratihariOccupation::create([
                'pratihari_id' => $pratihariId, // Fixed variable name issue
                'occupation_type' => $request->occupation,
                'extra_activity' => $extraActivities, // Stores activities as "Music, Dance, Painting"
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Occupation details saved successfully.',
                'data' => $occupation,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error saving occupation details: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to save occupation details.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
