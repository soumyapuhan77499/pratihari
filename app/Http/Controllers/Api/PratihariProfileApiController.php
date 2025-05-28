<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariProfile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PratihariProfileApiController extends Controller
{
    public function saveProfile(Request $request)
{
  
    try {
          // Authenticate user
          $user = Auth::user(); 

          if (!$user) {
              return response()->json([
                  'status' => 401,
                  'message' => 'Unauthorized. Please log in.',
              ], 401);
          }

        // Generate pratihari_id if not exists
        $pratihariId = $user->pratihari_id;

        // Create new profile object
        $pratihariProfile = new PratihariProfile();
        $pratihariProfile->pratihari_id = $pratihariId;
        $pratihariProfile->first_name = $request->first_name;
        $pratihariProfile->middle_name = $request->middle_name;
        $pratihariProfile->last_name = $request->last_name;
        $pratihariProfile->alias_name = $request->alias_name;
        $pratihariProfile->email = $request->email;
        $pratihariProfile->whatsapp_no = $request->whatsapp_no;
        $pratihariProfile->phone_no = $request->phone_no;
        $pratihariProfile->blood_group = $request->blood_group;
        $pratihariProfile->healthcard_no = $request->healthcard_no;
        $pratihariProfile->joining_date = $request->joining_date;
        $pratihariProfile->joining_year = $request->joining_year;
        $pratihariProfile->alt_phone_no = $request->alt_phone_no;
        $pratihariProfile->date_of_birth = $request->date_of_birth;

       if ($request->hasFile('profile_photo')) {
    $file = $request->file('profile_photo');
    $filename = 'profile_photo_' . time() . '.' . $file->getClientOriginalExtension();
    $destinationPath = public_path('uploads/profile_photos');
    // Create directory if not exists
    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
    }
    $file->move($destinationPath, $filename);
    $pratihariProfile->profile_photo = 'uploads/profile_photos/' . $filename;
}

        // Save profile
        $pratihariProfile->save();

        return response()->json([
            'status' => 200,
            'message' => 'User profile created successfully.',
            'data' => $pratihariProfile
        ], 200);
    } catch (\Exception $e) {
        \Log::error('Error in saving profile: ' . $e->getMessage());

        return response()->json([
            'status' => 500,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
}

public function getProfile(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }

    $pratihari_id = $user->pratihari_id;

    $profile = PratihariProfile::where('pratihari_id', $pratihari_id)->first();

    if (!$profile) {
        return response()->json(['error' => 'Profile not found'], 404);
    }

    $photoBaseUrl = config('app.photo_url');

    if ($profile->profile_photo) {
        // Ensure no double slashes
        $profile_photo = ltrim($profile->profile_photo, '/');
        $profile->profile_photo_url = rtrim($photoBaseUrl, '/') . '/' . $profile_photo;
    } else {
        $profile->profile_photo_url = null;
    }

    return response()->json([
        'pratihari_id' => $pratihari_id,
        'profile' => $profile,
    ]);
}
}