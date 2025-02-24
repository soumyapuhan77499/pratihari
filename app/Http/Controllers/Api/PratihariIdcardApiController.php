<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariIdcard;
use Illuminate\Support\Facades\Auth;

class PratihariIdcardApiController extends Controller
{
    public function saveIdcard(Request $request)
{
    try {
        $user = Auth::user();
            
        $pratihariId = $user->pratihari_id;

        if (!$pratihariId) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized. Please log in.',
            ], 401);
        }

        // Save ID cards
        $savedIdCards = [];
        foreach ($request->id_type as $key => $type) {
            $idCard = new PratihariIdcard();
            $idCard->pratihari_id = $pratihariId;
            $idCard->id_type = $request->id_type[$key];
            $idCard->id_number = $request->id_number[$key];

            // Handle file upload
            if ($request->hasFile("id_photo.$key") && $request->file("id_photo.$key")->isValid()) {
                $imagePath = $request->file("id_photo.$key")->store('public/id_photos');
                $idCard->id_photo = $imagePath;
            }

            $idCard->save();
            $savedIdCards[] = $idCard;
        }

        // Return success response
        return response()->json([
            'status' => true,
            'message' => 'ID cards added successfully',
            'data' => $savedIdCards,
        ], 200);

    } catch (\Exception $e) {
        // Return error response
        return response()->json([
            'status' => false,
            'message' => 'An error occurred',
            'error' => $e->getMessage(),
        ], 500);
    }
}
}
