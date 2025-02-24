<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PratihariProfile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\PratihariAddress;
use App\Models\PratihariFamily;
use App\Models\PratihariChildren;
use App\Models\PratihariIdcard;
use App\Models\PratihariSeba;
use App\Models\PratihariOccupation;
use App\Models\PratihariSocialMedia;


class PratihariProfileController extends Controller
{
    public function pratihariProfile()
    {
        return view('admin.pratihari-profile-details');
    }

    public function saveProfile(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        try {

            // Create new PratihariProfile object
            $pratihariProfile = new PratihariProfile();
    
            $pratihariId = 'PRATIHARI' . rand(10000, 99999);

            // Set the profile data
            $pratihariProfile->pratihari_id = $pratihariId; // Add the generated pratihari_id
            $pratihariProfile->first_name = $request->first_name;
            $pratihariProfile->middle_name = $request->middle_name;
            $pratihariProfile->last_name = $request->last_name;
            $pratihariProfile->alias_name = $request->alias_name;
            $pratihariProfile->email = $request->email;
            $pratihariProfile->whatsapp_no = $request->whatsapp_no;
            $pratihariProfile->phone_no = $request->phone_no;
            $pratihariProfile->alt_phone_no = $request->alt_phone_no;
            $pratihariProfile->blood_group = $request->blood_group;
            $pratihariProfile->healthcard_no = $request->health_card_no;
    
            // Handle profile photo upload if exists
            if ($request->hasFile('profile_photo')) {
                $profilePhoto = $request->file('profile_photo');
                // Check if the file is an image
                if (!$profilePhoto->isValid()) {
                    throw new \Exception('Profile photo upload failed. Please try again.');
                }
                $profilePhotoPath = $profilePhoto->store('uploads/profile_photos', 'public');
                $pratihariProfile->profile_photo = $profilePhotoPath;
            }

            // Set the joining year
            $pratihariProfile->joining_date = $request->joining_date;
            $pratihariProfile->joining_year = $request->joining_year;

            $pratihariProfile->date_of_birth = $request->date_of_birth;

    
            // Save the profile
            $pratihariProfile->save();
            
        // After saving the profile
         return redirect()->route('admin.pratihariFamily', ['pratihari_id' => $pratihariProfile->pratihari_id])->with('success', 'User added successfully!');
    
        } catch (\Exception $e) {
            // Log the exception and display the specific error message
            \Log::error('Error in Pratihari Profile Store: ' . $e->getMessage());
    
            // Return a detailed error message
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function pratihariManageProfile()
    {

        $profiles = PratihariProfile::with(['occupation', 'address'])->get();

        return view('admin.pratihari-manage-profile', compact('profiles'));


    }

    public function getPratihariAddress(Request $request)
    {
        \Log::info('Fetching address for pratihari_id: ' . $request->pratihari_id); // Debugging Log
    
        $address = PratihariAddress::where('pratihari_id', $request->pratihari_id)->first();
        
        if (!$address) {
            return response()->json(['error' => 'Address not found'], 404);
        }
    
        return response()->json($address);
    }

    public function viewProfile($pratihari_id)
    {
        // Fetch Profile Details
        $profile = PratihariProfile::with([ 'address'])->where('pratihari_id', $pratihari_id)->first();

        // Fetch Family Details
        $family = PratihariFamily::where('pratihari_id', $pratihari_id)->first();

        $children = PratihariChildren::where('pratihari_id', $pratihari_id)->get();

        $idcard = PratihariIdcard::where('pratihari_id', $pratihari_id)->get();

        $occupation = PratihariOccupation::where('pratihari_id', $pratihari_id)->get();


        $sebaDetails = PratihariSeba::with(['beddhaMaster','sebaMaster','nijogaMaster'])->where('pratihari_id', $pratihari_id)->get();

        $socialMedia = PratihariSocialMedia::where('pratihari_id', $pratihari_id)->first();

        // Check if profile exists
        if (!$profile) {
            return redirect()->back()->with('error', 'Profile not found');
        }

        return view('admin.view-pratihari-profile', compact('profile', 'family','children','idcard','occupation','sebaDetails','socialMedia'));
    }
    

}
