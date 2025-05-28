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
             
            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');
                $filename = 'profile_photo_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/profile_photos'), $filename);
                $pratihariProfile->profile_photo = 'uploads/profile_photos/' . $filename;
            }

            // Set the joining year
            $pratihariProfile->joining_date = $request->joining_date;
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

    public function approve($id)
    {
        $profile = PratihariProfile::findOrFail($id);

        // Generate `nijoga_id` using the first 4 digits of healthcard_no + 4 random digits
        $nijogaId = substr($profile->healthcard_no, 0, 4) . rand(1000, 9999);

        $profile->update([
            'pratihari_status' => 'approved',
            'nijoga_id' => $nijogaId
        ]);

        return response()->json(['message' => 'Profile approved successfully!']);
    }

    public function reject($id)
    {
        $profile = PratihariProfile::findOrFail($id);
        $profile->update(['pratihari_status' => 'rejected']);

        return response()->json(['message' => 'Profile rejected successfully!']);
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
        $profile = PratihariProfile::with(['address.sahiDetail'])->where('pratihari_id', $pratihari_id)->first();
    
        // Fetch Family and Children Details
        $family = PratihariFamily::where('pratihari_id', $pratihari_id)->first();

        $children = PratihariChildren::where('pratihari_id', $pratihari_id)->get() ?? collect(); 
    
        // Fetch ID Card Details
        $idcard = PratihariIdcard::where('pratihari_id', $pratihari_id)->get() ?? collect(); 
    
        // Fetch Other Details
        $occupation = PratihariOccupation::where('pratihari_id', $pratihari_id)->get() ?? collect();

        $sebaDetails = PratihariSeba::where('pratihari_id', $pratihari_id)->get() ?? collect(); 

        $socialMedia = PratihariSocialMedia::where('pratihari_id', $pratihari_id)->first();
    
        // Completion Percentages
        $profileCompletion = $profile ? $profile->getCompletionPercentage() : 0;
    
        $familyCompletion = $family ? round((collect([
            'father_name', 'father_photo', 'mother_name', 'mother_photo',
            'maritial_status', 'spouse_name', 'spouse_photo'
        ])->filter(fn($field) => !empty($family->$field))->count() / 7) * 100) : 0;
    
        $childrenCompletion = $children->count() > 0 ? 100 : 0;
    
        $idcardCompletion = $idcard->count() > 0 && isset($idcard[0]) ? round((collect([
            'id_type', 'id_number', 'id_photo'
        ])->filter(fn($field) => !empty($idcard[0]->$field))->count() / 3) * 100) : 0;
    
        $occupation_chat = PratihariOccupation::where('pratihari_id', $pratihari_id)->get();

        $hasOccupationData = $occupation_chat->isNotEmpty() && 
            $occupation_chat->first(function ($item) {
                return !empty($item->occupation_type) || !empty($item->extra_activity);
            });
        
        $occupationCompletion = $hasOccupationData ? 100 : 0;
        
        $addressCompletion = $profile && $profile->address ? 100 : 0;
        
        $sebaCompletion = $sebaDetails->count() > 0 ? 100 : 0;

        $socialmediaCompletion = $socialMedia ? 100 : 0;
    
        return view('admin.view-pratihari-profile', compact(
            'profile', 'family', 'children', 'idcard', 'occupation', 'sebaDetails', 'socialMedia'
        ), [
            'profileCompletion' => $profileCompletion ?? 0,
            'familyCompletion' => $familyCompletion ?? 0,
            'idcardCompletion' => $idcardCompletion ?? 0,
            'childrenCompletion' => $childrenCompletion ?? 0,
            'addressCompletion' => $addressCompletion ?? 0,
            'occupationCompletion' => $occupationCompletion ?? 0,
            'sebaCompletion' => $sebaCompletion ?? 0,
            'socialmediaCompletion' => $socialmediaCompletion ?? 0,
        ]);
    }

    public function edit($pratihari_id)
    {
        $profile = PratihariProfile::where('pratihari_id', $pratihari_id)->first();

        return view('admin.update-profile-details', compact('profile', 'pratihari_id'));

    }

    public function updateProfile(Request $request, $pratihari_id)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Find the existing profile by ID
            $pratihariProfile = PratihariProfile::where('pratihari_id', $pratihari_id)->firstOrFail();

            // Update the profile data
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
            $pratihariProfile->joining_date = $request->joining_date;
            $pratihariProfile->date_of_birth = $request->date_of_birth;

            // Handle profile photo upload if exists
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

            // Save the updated profile
            $pratihariProfile->save();

            return redirect()->route('admin.viewProfile', ['pratihari_id' => $pratihari_id])->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            // Log the exception and display the specific error message
            \Log::error('Error in Pratihari Profile Update: ' . $e->getMessage());

            // Return a detailed error message
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

}