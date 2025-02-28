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
        $pratihariProfile->healthcard_no = $request->health_card_no;
        $pratihariProfile->joining_date = $request->joining_date;
        $pratihariProfile->joining_year = $request->joining_year;
        $pratihariProfile->alt_phone_no = $request->alt_phone_no;
        $pratihariProfile->date_of_birth = $request->date_of_birth;

        // Save profile photo
        if ($request->hasFile('profile_photo')) {
            $profilePhoto = $request->file('profile_photo');

            // Check if the file is valid
            if (!$profilePhoto->isValid()) {
                throw new \Exception('Profile photo upload failed. Please try again.');
            }

            // Generate unique file name
            $imageName = time() . '.' . $profilePhoto->getClientOriginalExtension();

            // Move file to public/uploads/profile_photos
            $profilePhoto->move(public_path('uploads/profile_photos'), $imageName);

            // Store relative path in database
            $pratihariProfile->profile_photo = 'uploads/profile_photos/' . $imageName;
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

}
