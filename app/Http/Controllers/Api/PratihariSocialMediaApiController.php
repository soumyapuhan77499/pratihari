<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariSocialMedia;
use Illuminate\Support\Facades\Auth;


class PratihariSocialMediaApiController extends Controller
{
public function saveSocialMedia(Request $request)
{
    try {
        $user = Auth::user(); 

        if (!$user || !$user->pratihari_id) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Please log in.',
            ], 401);
        }

        $pratihariId = $user->pratihari_id;

        // Check if a record already exists
        $socialMedia = PratihariSocialMedia::where('pratihari_id', $pratihariId)->first();

        if (!$socialMedia) {
            $socialMedia = new PratihariSocialMedia();
            $socialMedia->pratihari_id = $pratihariId;
        }

        // Set or update social media fields
        $socialMedia->facebook_url = $request->facebook;
        $socialMedia->instagram_url = $request->instagram;
        $socialMedia->youtube_url = $request->youtube;
        $socialMedia->twitter_url = $request->twitter;
        $socialMedia->linkedin_url = $request->linkedin;

        $socialMedia->save();

        return response()->json([
            'status' => true,
            'message' => 'Pratihari Social Media details saved successfully',
            'data' => $socialMedia
        ], 200);

    } catch (\Exception $e) {
        \Log::error('Error saving social media: ' . $e->getMessage());
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong',
            'error' => $e->getMessage()
        ], 500);
    }
}


public function getSocialMedia(Request $request)
{
    try {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized. Please log in.',
            ], 401);
        }

        $pratihariId = $user->pratihari_id;

        $socialMedia = PratihariSocialMedia::where('pratihari_id', $pratihariId)->first();

        if (!$socialMedia) {
            return response()->json([
                'status' => true,
                'message' => 'Social media details not found.',
            ], 200);
        }

        return response()->json([
            'status' => true,
            'message' => 'Pratihari Social Media details fetched successfully',
            'data' => $socialMedia
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => true,
            'message' => 'Something went wrong',
            'error' => $e->getMessage()
        ], 500);
    }
}

}
