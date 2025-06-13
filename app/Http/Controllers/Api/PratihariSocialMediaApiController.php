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
        // Validate the input
        $user = Auth::user(); 

          if (!$user) {
              return response()->json([
                  'status' => 401,
                  'message' => 'Unauthorized. Please log in.',
              ], 401);
          }

        // Generate pratihari_id if not exists
        $pratihariId = $user->pratihari_id;
        // Save social media details
        $socialMedia = new PratihariSocialMedia();
        $socialMedia->pratihari_id = $pratihariId;
        $socialMedia->facebook_url = $request->facebook;
        $socialMedia->instagram_url = $request->instagram;
        $socialMedia->youtube_url = $request->youtube;
        $socialMedia->twitter_url = $request->twitter;
        $socialMedia->linkedin_url = $request->linkedin;
        $socialMedia->save();

        // Return JSON response
        return response()->json([
            'status' => 200,
            'message' => 'Pratihari Social Media details saved successfully',
            'data' => $socialMedia
        ], 200);

    } catch (\Exception $e) {
        // Handle errors and return a 500 response
        return response()->json([
            'status' => 500,
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
