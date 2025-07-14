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

            $savedIdCards = [];

            foreach ($request->id_type as $key => $type) {
                // Check if ID card with this type already exists for the user
                $idCard = PratihariIdcard::where('pratihari_id', $pratihariId)
                                        ->where('id_type', $type)
                                        ->first();

                if (!$idCard) {
                    $idCard = new PratihariIdcard();
                    $idCard->pratihari_id = $pratihariId;
                    $idCard->id_type = $type;
                }

                $idCard->id_number = $request->id_number[$key];

                // Handle file upload
                if ($request->hasFile('id_photo') && isset($request->file('id_photo')[$key])) {
                    $idPhoto = $request->file('id_photo')[$key];

                    if ($idPhoto->isValid()) {
                        $imageName = time() . "_id_{$key}." . $idPhoto->getClientOriginalExtension();
                        $idPhoto->move(public_path('uploads/id_photo'), $imageName);
                        $idCard->id_photo = asset('uploads/id_photo/' . $imageName);
                    }
                }

                $idCard->save();
                $savedIdCards[] = $idCard;
            }

            return response()->json([
                'status' => true,
                'message' => 'ID cards saved successfully.',
                'data' => $savedIdCards,
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error saving ID cards: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while saving ID cards.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
