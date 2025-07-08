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
use App\Models\PratihariDesignation;
use App\Models\PratihariApplication;
use Carbon\Carbon;

class PratihariProfileController extends Controller
{
    public function pratihariProfile()
    {
        return view('admin.pratihari-profile-details');
    }

    public function saveProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            // add more validation as needed
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $pratihariProfile = new PratihariProfile();

            $pratihariId = 'PRATIHARI' . rand(10000, 99999);
            $pratihariProfile->pratihari_id = $pratihariId;
            $pratihariProfile->first_name = $request->first_name;
            $pratihariProfile->middle_name = $request->middle_name;
            $pratihariProfile->last_name = $request->last_name;
            $pratihariProfile->alias_name = $request->alias_name;
            $pratihariProfile->email = $request->email;
            $pratihariProfile->whatsapp_no = $request->whatsapp_no;
            $pratihariProfile->phone_no = $request->phone_no;
            // $pratihariProfile->alt_phone_no = $request->alt_phone_no;
            $pratihariProfile->blood_group = $request->blood_group;
            $pratihariProfile->healthcard_no = $request->healthcard_no;

            if ($request->hasFile('healthcard_photo')) {
                $file = $request->file('healthcard_photo');
                $filename = 'healthcard_photo_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/healthcard_photo'), $filename);
                $pratihariProfile->healthcard_photo = 'uploads/healthcard_photo/' . $filename;
            }

            if ($request->hasFile('original_photo')) {
                $file = $request->file('original_photo');
                $filename = 'profile_photo_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/profile_photos'), $filename);
                $pratihariProfile->profile_photo = 'uploads/profile_photos/' . $filename;
            }

            // Handle joining date/year separately:
            if ($request->filled('joining_date')) {
                // exact date selected
                $pratihariProfile->joining_date = $request->joining_date; // format: YYYY-MM-DD
            } elseif ($request->filled('joining_year')) {
                // only year selected - store as YYYY-01-01 (or change column type to year or string as needed)
                $pratihariProfile->joining_date = $request->joining_year;
            } else {
                // no date selected
                $pratihariProfile->joining_date = null;
            }

            $pratihariProfile->date_of_birth = $request->date_of_birth;

            $pratihariProfile->save();

            return redirect()->route('admin.pratihariFamily', ['pratihari_id' => $pratihariProfile->pratihari_id])->with('success', 'User added successfully!');
        } catch (\Exception $e) {
            \Log::error('Error in Pratihari Profile Store: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function pratihariManageProfile()
    {
        $profiles = PratihariProfile::with(['occupation', 'address'])->where('pratihari_status','pending')->get();

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
        
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $profile = PratihariProfile::findOrFail($id);
        $profile->update([
            'pratihari_status' => 'rejected',
            'reject_reason' => $request->input('reason'), // make sure this field exists in your table
        ]);

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
            // $pratihariProfile->alt_phone_no = $request->alt_phone_no;
            $pratihariProfile->blood_group = $request->blood_group;
            $pratihariProfile->healthcard_no = $request->health_card_no;
            $pratihariProfile->joining_date = $request->joining_date;
            $pratihariProfile->date_of_birth = $request->date_of_birth;

            if ($pratihariProfile->pratihari_status === 'rejected') {
              $pratihariProfile->pratihari_status = 'updated';
            }
            
            // Handle health card photo upload if exists
            if ($request->hasFile('health_card_photo')) {
                $healthCardPhoto = $request->file('health_card_photo');

                // Check if the file is valid
                if (!$healthCardPhoto->isValid()) {
                    throw new \Exception('Health card photo upload failed. Please try again.');
                }

                // Generate unique file name
                $healthCardImageName = 'health_card_photo_' . time() . '.' . $healthCardPhoto->getClientOriginalExtension();

                // Move file to public/uploads/health_card_photo
                $healthCardPhoto->move(public_path('uploads/health_card_photo'), $healthCardImageName);

                // Store relative path in database
                $pratihariProfile->health_card_photo = 'uploads/health_card_photo/' . $healthCardImageName;
            }

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

    public function filterUsers($filter)
    {
        if ($filter === 'approved' || $filter === 'rejected' || $filter === 'updated' || $filter === 'pending') {
            $profiles = PratihariProfile::where('pratihari_status', $filter)->get();
        } elseif ($filter === 'todayrejected') {
            $profiles = PratihariProfile::whereDate('updated_at', Carbon::today())->where('pratihari_status', 'rejected')->get();
         } elseif ($filter === 'todayapproved') {
            $profiles = PratihariProfile::whereDate('updated_at', Carbon::today())->where('pratihari_status', 'approved')->get();
        } elseif ($filter === 'today') {
            $profiles = PratihariProfile::whereDate('created_at', Carbon::today())->get();
        } elseif ($filter === 'incomplete') {
            $profiles = PratihariProfile::where('pratihari_status',['pending','rejected'])->where(function ($query) {
                $query->whereNull('email')
                    ->orWhereNull('phone_no')
                    ->orWhereNull('blood_group');
            })
            // OR profiles with missing family info
            ->orWhereDoesntHave('family', function ($query) {
                $query->whereNotNull('father_name')
                    ->whereNotNull('mother_name')
                    ->whereNotNull('maritial_status'); // add more checks if needed
            })
            // OR profiles with no children records
            ->orWhereDoesntHave('children')
            // OR profiles with missing id card details
            ->orWhereDoesntHave('idcard', function ($query) {
                $query->whereNotNull('id_type')
                    ->whereNotNull('id_number')
                    ->whereNotNull('id_photo');
            })
            // OR profiles with missing occupation details
            ->orWhereDoesntHave('occupation', function ($query) {
                $query->where(function ($q) {
                    $q->whereNotNull('occupation_type')
                    ->orWhereNotNull('extra_activity');
                });
            })
            // OR profiles with missing address
            ->orWhereDoesntHave('address')
            // OR profiles with missing seba details
            ->orWhereDoesntHave('seba')
            // OR profiles with missing social media
            ->orWhereDoesntHave('socialMedia')
            ->get();
        } else {
            abort(404);
        }

        return view('admin.pratihari-filter-user', compact('profiles', 'filter'));
    }

    public function saveDesignation(Request $request)
    {
        // Validation
        $request->validate([
            'year' => 'required|integer',
            'pratihari_id' => 'required|exists:pratihari__profile_details,pratihari_id',
            'designation' => 'required|string|max:255',
        ]);

        // Create the designation
        PratihariDesignation::create([
            'pratihari_id' => $request->pratihari_id,
            'year' => $request->year,
            'designation' => $request->designation,
        ]);

        return redirect()->back()->with('success', 'Designation added successfully.');
    }

    public function addDesignation(Request $request)
    {
        $profiles = PratihariProfile::where('status','active')->where('pratihari_status','approved')->get();

        return view('admin.add-designation', compact('profiles'));

    }

    public function manageDesignation()
    {
        $designations = PratihariDesignation::with('pratihariProfile')->get();

        return view('admin.manage-designation', compact('designations'));
    }

    public function deleteDesignation($id)
    {
        $designation = PratihariDesignation::findOrFail($id);
        $designation->delete();

        return redirect()->back()->with('success', 'Designation deleted successfully.');
    }

    public function manageApplication()
    {
        $applications = PratihariApplication::with('profile')
        ->whereIn('status', ['pending', 'approved', 'rejected'])
        ->get();

        return view('admin.manage-application', compact('applications'));
    }

    public function filterApplication($type = null)
{
    $query = PratihariApplication::with('profile');

    if ($type === 'approved') {
        $query->where('status', 'approved');
    } elseif ($type === 'rejected') {
        $query->where('status', 'rejected');
    } elseif ($type === 'today') {
        $query->whereDate('created_at', Carbon::today());
    }

    $applications = $query->latest()->get();

    return view('admin.manage-application', compact('applications', 'type'));
}


    public function updateApplication(Request $request, $id)
        {
            try {
                
                $application = PratihariApplication::findOrFail($id);

                $application->header = $request->header;
                $application->body = $request->body;

                if ($request->hasFile('photo')) {
                    $file = $request->file('photo');
                    $fileName = 'application_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/application'), $fileName);

                    // Full URL using .env APP_PHOTO_URL
                    $fullPath = config('app.photo_url') . 'uploads/application/' . $fileName;

                    $application->photo = $fullPath;
                }

                $application->save();

                return redirect()->back()->with('success', 'Application updated successfully.');
            } catch (\Exception $e) {
                \Log::error('Application update failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Failed to update application.');
            }
        }

      public function approveApplication($id)
{
    $application = PratihariApplication::findOrFail($id);
    $application->status = 'approved';
    $application->save();

    return redirect()->back()->with('success', 'Application approved successfully.');
}

public function rejectApplication(Request $request, $id)
{
    $request->validate([
        'rejection_reason' => 'required|string|max:1000',
    ]);

    $application = PratihariApplication::findOrFail($id);
    $application->status = 'rejected';
    $application->rejection_reason = $request->rejection_reason;
    $application->save();

    return redirect()->back()->with('success', 'Application rejected with reason.');
}
}