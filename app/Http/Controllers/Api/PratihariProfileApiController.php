<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariProfile;
use App\Models\PratihariFamily;
use App\Models\PratihariIdcard;
use App\Models\PratihariOccupation;
use App\Models\PratihariSeba;
use App\Models\PratihariSocialMedia;
use App\Models\PratihariAddress;
use App\Models\PratihariChildren;
use App\Models\MasterNijogaSeba;
use App\Models\PratihariDesignation;
use App\Models\PratihariNotice;
use App\Models\PratihariApplication;
use App\Models\DateBeddhaMapping;
use Carbon\Carbon;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config; // make sure this is at the top if needed

class PratihariProfileApiController extends Controller
{

public function saveProfile(Request $request)
{
    $user = Auth::user();

    $pratihariId = $user->pratihari_id;

    if (!$pratihariId) {
        return response()->json([
            'status' => 401,
            'message' => 'Unauthorized. Please log in.',
        ], 401);
    }

    try {
        // Find or create profile
        $pratihariProfile = PratihariProfile::where('pratihari_id', $pratihariId)->first();

        $isNew = false;

        if (!$pratihariProfile) {
            $isNew = true;
            $pratihariProfile = new PratihariProfile();
            $pratihariProfile->pratihari_id = $pratihariId;
        }

        // Assign profile fields
        $pratihariProfile->first_name = $request->first_name;
        $pratihariProfile->middle_name = $request->middle_name;
        $pratihariProfile->last_name = $request->last_name;
        $pratihariProfile->alias_name = $request->alias_name;
        $pratihariProfile->email = $request->email;
        $pratihariProfile->whatsapp_no = $request->whatsapp_no;
        $pratihariProfile->phone_no = $request->phone_no;
        $pratihariProfile->alt_phone_no = $request->alt_phone_no;
        $pratihariProfile->blood_group = $request->blood_group;
        $pratihariProfile->healthcard_no = $request->healthcard_no;

        // --- Generate nijoga_id only for new users ---
        if ($isNew) {
            $healthcard_no = $request->healthcard_no;
            $prefix = strtoupper(substr($healthcard_no, 0, 4));

            // Family sequence (YYY)
            $existingSameHealthcard = PratihariProfile::where('healthcard_no', $healthcard_no)
                ->whereNotNull('nijoga_id')
                ->get();

            if ($existingSameHealthcard->isEmpty()) {
                $familyCount = 1;
            } else {
                $lastFamily = $existingSameHealthcard->map(function ($member) {
                    return (int) substr($member->nijoga_id, 5, 3);
                })->max();
                $familyCount = $lastFamily + 1;
            }

            // Global serial (ZZZZ)
            $lastSerial = PratihariProfile::whereNotNull('nijoga_id')
                ->orderByDesc('id')
                ->get()
                ->map(function ($member) {
                    return (int) substr($member->nijoga_id, -4);
                })->max();

            $serialNumber = $lastSerial ? $lastSerial + 1 : 1;

            // Final nijoga_id
            $nijoga_id = sprintf('%s-%03d-%04d', $prefix, $familyCount, $serialNumber);
            $pratihariProfile->nijoga_id = $nijoga_id;
        }

        // --- File Uploads ---
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

        // --- Joining Date/Year ---
        if ($request->filled('joining_date')) {
            $pratihariProfile->joining_date = $request->joining_date;
            $pratihariProfile->joining_year = null;
        } elseif ($request->filled('joining_year')) {
            $pratihariProfile->joining_year = $request->joining_year;
            $pratihariProfile->joining_date = null;
        } else {
            $pratihariProfile->joining_date = null;
            $pratihariProfile->joining_year = null;
        }

        // --- DOB ---
        $pratihariProfile->date_of_birth = $request->date_of_birth;

        // Save
        $pratihariProfile->save();

        return response()->json([
            'status' => 200,
            'message' => $isNew ? 'User profile created successfully.' : 'User profile updated successfully.',
            'data' => $pratihariProfile,
        ], 200);

    } catch (\Exception $e) {
        \Log::error('Error in saving profile: ' . $e->getMessage());

        return response()->json([
            'status' => 500,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
}

public function getHomePage()
{
    try {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated',
            ], 401);
        }

        $pratihari_id = $user->pratihari_id;

        $profile = PratihariProfile::where('pratihari_id', $pratihari_id)->first();

        if (!$profile) {
            return response()->json([
                'status' => false,
                'message' => 'Profile not found',
            ], 404);
        }

        $photoBaseUrl = rtrim(config('app.photo_url'), '/') . '/';
        $profile->profile_photo_url = $profile->profile_photo
            ? $photoBaseUrl . ltrim($profile->profile_photo, '/')
            : null;

        $rejectReason = [
            'reason' => $profile->reject_reason ?? null,
        ];

        // === START: Pure Date-Based Beddha Calculation ===
        $today = \Carbon\Carbon::today();
        $baseDatePratihari = \Carbon\Carbon::parse('2025-07-01');
        $baseDateGochhikar = \Carbon\Carbon::parse('2025-06-16');

          $today = Carbon::today()->toDateString(); // e.g., '2025-07-18'

        $beddhaMapping = DateBeddhaMapping::where('date', $today)->first();

        $pratihariBeddha = $beddhaMapping->pratihari_beddha ?? 'N/A';
        $gochhikarBeddha = $beddhaMapping->gochhikar_beddha ?? 'N/A';

        return response()->json([
            'status' => true,
            'message' => 'Home Data fetched successfully',
            'data' => [
                'profile' => $profile,
                'reject_reason' => $rejectReason,
                'today_pratihari_beddha' => $pratihariBeddha,
                'today_gochhikar_beddha' => $gochhikarBeddha,
            ]
        ], 200);

    } catch (\Exception $e) {
        \Log::error('Error fetching home page data: ' . $e->getMessage());

        return response()->json([
            'status' => false,
            'message' => 'An error occurred while fetching home data',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function getAllData(Request $request)
{
    try {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $pratihari_id = $user->pratihari_id;
        $photoBaseUrl = rtrim(config('app.photo_url'), '/');

        // Fetch main records
        $profile = PratihariProfile::where('pratihari_id', $pratihari_id)->first();
        $family = PratihariFamily::where('pratihari_id', $pratihari_id)->first();
        $address = PratihariAddress::where('pratihari_id', $pratihari_id)->first();
        $idcard = PratihariIdcard::where('pratihari_id', $pratihari_id)->get();
        $occupation = PratihariOccupation::where('pratihari_id', $pratihari_id)->get();
        $socialMedia = PratihariSocialMedia::where('pratihari_id', $pratihari_id)->get();

        // 👉 Seba with seba_name
        $sebaDetailsRaw = PratihariSeba::with('sebaMaster')
            ->where('pratihari_id', $pratihari_id)
            ->get();

        $sebaDetails = $sebaDetailsRaw->map(function ($item) {
            return [
                'id' => $item->id,
                'seba_id' => $item->seba_id,
                'seba_name' => $item->sebaMaster->seba_name ?? null,
                'beddha_id' => $item->beddha_id,
            ];
        });

        // 📌 NEW: Load children records
        $children = PratihariChildren::where('pratihari_id', $pratihari_id)->get()->map(function ($child) use ($photoBaseUrl) {
            return [
                'id' => $child->id,
                'children_name' => $child->children_name,
                'date_of_birth' => $child->date_of_birth,
                'gender' => $child->gender,
                'photo' => $child->photo,
                'photo_url' => !empty($child->photo) ? $photoBaseUrl . '/' . ltrim($child->photo, '/') : null,
            ];
        });

        // Append full URLs to family photos
        if ($family) {
            $family->father_photo_url = !empty($family->father_photo) ? $photoBaseUrl . '/' . ltrim($family->father_photo, '/') : null;
            $family->mother_photo_url = !empty($family->mother_photo) ? $photoBaseUrl . '/' . ltrim($family->mother_photo, '/') : null;
            $family->spouse_photo_url = !empty($family->spouse_photo) ? $photoBaseUrl . '/' . ltrim($family->spouse_photo, '/') : null;
            $family->spouse_father_photo_url = !empty($family->spouse_father_photo) ? $photoBaseUrl . '/' . ltrim($family->spouse_father_photo, '/') : null;
            $family->spouse_mother_photo_url = !empty($family->spouse_mother_photo) ? $photoBaseUrl . '/' . ltrim($family->spouse_mother_photo, '/') : null;

            // 📌 Attach children array
            $family->children = $children;
        }

        // Append full URLs to profile photos
        if ($profile) {
            $profile->profile_photo = !empty($profile->profile_photo) ? $photoBaseUrl . '/' . ltrim($profile->profile_photo, '/') : null;
            $profile->healthcard_photo = !empty($profile->healthcard_photo) ? $photoBaseUrl . '/' . ltrim($profile->healthcard_photo, '/') : null;
        }

        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'pratihari_id' => $pratihari_id,
            'data' => [
                'profile' => $profile,
                'family' => $family,
                'address' => $address,
                'idcard' => $idcard,
                'occupation' => $occupation,
                'sebaDetails' => $sebaDetails,
                'socialMedia' => $socialMedia,
            ]
        ], 200);

    } catch (\Exception $e) {
        \Log::error('Error fetching data for pratihari_id ' . ($user->pratihari_id ?? 'unknown') . ': ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching data',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function manageDesignation()
{
    try {
        $photoBaseUrl = config('app.photo_url');

        $designations = PratihariDesignation::with('pratihariProfile')->get();

        $formatted = $designations->map(function ($designation) use ($photoBaseUrl) {
            $profile = $designation->pratihariProfile;

            $profilePhotoUrl = null;

            if ($profile && $profile->profile_photo) {
                $relativePath = ltrim($profile->profile_photo, '/');
                $profilePhotoUrl = rtrim($photoBaseUrl, '/') . '/' . $relativePath;
            }

            return [
                'id' => $designation->id,
                'year' => $designation->year,
                'designation' => $designation->designation,
                'pratihari' => [
                    'id' => $profile->id ?? null,
                    'pratihari_id' => $profile->pratihari_id ?? null,
                    'first_name' => $profile->first_name ?? null,
                    'middle_name' => $profile->middle_name ?? null,
                    'last_name' => $profile->last_name ?? null,
                    'full_name' => trim(
                        ($profile->first_name ?? '') . ' ' .
                        ($profile->middle_name ?? '') . ' ' .
                        ($profile->last_name ?? '')
                    ),
                    'profile_photo_url' => $profilePhotoUrl,
                ]
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Designations fetched successfully.',
            'data' => $formatted
        ], 200);

    } catch (\Exception $e) {
        \Log::error('Error fetching designations: ' . $e->getMessage());

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to fetch designations.',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function saveApplication(Request $request)
{
    try {

        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $pratihari_id = $user->pratihari_id;
        
        $photoPath = null;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = 'application_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/application'), $fileName);

            // Generate full URL using APP_PHOTO_URL
            $photoPath = Config::get('app.photo_url') . 'uploads/application/' . $fileName;
        }

        $application = PratihariApplication::create([
            'pratihari_id' => $pratihari_id,
            'date' => $request->date,
            'header' => $request->header,
            'body' => $request->body,
            'photo' => $photoPath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Application submitted successfully.',
            'data' => $application
        ], 200);

    } catch (\Exception $e) {
        \Log::error('Application Save Error: ' . $e->getMessage());

        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong.',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function getApplication()
{
    try {
       $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $pratihari_id = $user->pratihari_id;
        $photoBaseUrl = rtrim(config('app.photo_url'), '/') . '/';

        // Get applications
        $applications = PratihariApplication::where('pratihari_id', $pratihari_id)->get();

        // Append full photo URL
        $applications->transform(function ($app) use ($photoBaseUrl) {
            $app->photo_url = $app->photo
                ? (str_starts_with($app->photo, 'http') ? $app->photo : $photoBaseUrl . ltrim($app->photo, '/'))
                : null;
            return $app;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Applications fetched successfully.',
            'data' => $applications
        ], 200);

    } catch (\Exception $e) {
        \Log::error('Error fetching applications: ' . $e->getMessage());

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to fetch applications.',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function getPofileDataByPratihariId($pratihari_id)
{

    try {
        
        if (!$pratihari_id) {
            return response()->json([
                'success' => false,
                'message' => 'Missing pratihari_id in request'
            ], 400);
        }

        $photoBaseUrl = config('app.photo_url');

        // Fetch related data
        $profile = PratihariProfile::where('pratihari_id', $pratihari_id)->first();
        $family = PratihariFamily::where('pratihari_id', $pratihari_id)->first();
        $address = PratihariAddress::where('pratihari_id', $pratihari_id)->first();
        $idcard = PratihariIdcard::where('pratihari_id', $pratihari_id)->get();
        $occupation = PratihariOccupation::where('pratihari_id', $pratihari_id)->get();

        // Eager-load SebaMaster to get seba_name
        $sebaDetailsRaw = PratihariSeba::with('sebaMaster')
            ->where('pratihari_id', $pratihari_id)
            ->get();

        $sebaDetails = $sebaDetailsRaw->map(function ($item) {
            return [
                'id' => $item->id,
                'seba_id' => $item->seba_id,
                'seba_name' => $item->sebaMaster->seba_name ?? null,
                'beddha_id' => $item->beddha_id,
            ];
        });

        $socialMedia = PratihariSocialMedia::where('pratihari_id', $pratihari_id)->get();

        // Append full photo URLs
        if ($profile) {
            $profile->profile_photo_url = $profile->profile_photo
                ? rtrim($photoBaseUrl, '/') . '/' . ltrim($profile->profile_photo, '/')
                : null;
            $profile->health_card_photo_url = $profile->health_card_photo
                ? rtrim($photoBaseUrl, '/') . '/' . ltrim($profile->health_card_photo, '/')
                : null;
        }

        if ($family) {
            $family->father_photo_url = $family->father_photo
                ? rtrim($photoBaseUrl, '/') . '/' . ltrim($family->father_photo, '/')
                : null;
            $family->mother_photo_url = $family->mother_photo
                ? rtrim($photoBaseUrl, '/') . '/' . ltrim($family->mother_photo, '/')
                : null;
            $family->spouse_photo_url = $family->spouse_photo
                ? rtrim($photoBaseUrl, '/') . '/' . ltrim($family->spouse_photo, '/')
                : null;
            $family->spouse_father_photo_url = $family->spouse_father_photo
                ? rtrim($photoBaseUrl, '/') . '/' . ltrim($family->spouse_father_photo, '/')
                : null;
            $family->spouse_mother_photo_url = $family->spouse_mother_photo
                ? rtrim($photoBaseUrl, '/') . '/' . ltrim($family->spouse_mother_photo, '/')
                : null;
        }

        $idcard->transform(function ($item) use ($photoBaseUrl) {
            $item->id_photo_url = $item->id_photo
                ? rtrim($photoBaseUrl, '/') . '/' . ltrim($item->id_photo, '/')
                : null;
            return $item;
        });

        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'pratihari_id' => $pratihari_id,
            'data' => [
                'profile' => $profile,
                'family' => $family,
                'address' => $address,
                'idcard' => $idcard,
                'occupation' => $occupation,
                'sebaDetails' => $sebaDetails,
                'socialMedia' => $socialMedia,
            ]
        ], 200);

    } catch (\Exception $e) {
        \Log::error('Error fetching data for pratihari_id ' . ($pratihari_id ?? 'unknown') . ': ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching data',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function getApprovedProfiles()
{
    try {
        $photoBaseUrl = config('app.photo_url');

        $profiles = PratihariProfile::where('pratihari_status', 'approved')
            ->select([
                'pratihari_id',
                'first_name',
                'middle_name',
                'last_name',
                'alias_name',
                'email',
                'whatsapp_no',
                'phone_no',
                'alt_phone_no',
                'profile_photo',
            ])
            ->get()
            ->map(function ($item) use ($photoBaseUrl) {
                $item->full_name = trim("{$item->first_name} {$item->middle_name} {$item->last_name}");
                $item->profile_photo_url = $item->profile_photo 
                    ? rtrim($photoBaseUrl, '/') . '/' . ltrim($item->profile_photo, '/') 
                    : null;
                return $item;
            });

        return response()->json([
            'success' => true,
            'message' => 'Approved profiles fetched successfully',
            'data' => $profiles
        ], 200);
    } catch (\Exception $e) {
        \Log::error('Error fetching approved profiles: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Error fetching approved profiles',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function updateProfile(Request $request, $pratihari_id)
{
    // Validate incoming request
    $validator = Validator::make($request->all(), [
        'first_name' => 'required|string|max:255',
        // Add other validation rules as needed
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
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
        $pratihariProfile->blood_group = $request->blood_group;
        $pratihariProfile->healthcard_no = $request->health_card_no;
        $pratihariProfile->joining_date = $request->joining_date;
        $pratihariProfile->date_of_birth = $request->date_of_birth;

        if ($pratihariProfile->pratihari_status === 'rejected') {
            $pratihariProfile->pratihari_status = 'updated';
        }

        // Handle health card photo upload
        if ($request->hasFile('health_card_photo')) {
            $healthCardPhoto = $request->file('health_card_photo');

            if (!$healthCardPhoto->isValid()) {
                throw new \Exception('Health card photo upload failed. Please try again.');
            }

            $healthCardImageName = 'health_card_photo_' . time() . '.' . $healthCardPhoto->getClientOriginalExtension();
            $healthCardPhoto->move(public_path('uploads/health_card_photo'), $healthCardImageName);
            $pratihariProfile->health_card_photo = 'uploads/health_card_photo/' . $healthCardImageName;
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $profilePhoto = $request->file('profile_photo');

            if (!$profilePhoto->isValid()) {
                throw new \Exception('Profile photo upload failed. Please try again.');
            }

            $imageName = 'profile_' . time() . '.' . $profilePhoto->getClientOriginalExtension();
            $profilePhoto->move(public_path('uploads/profile_photos'), $imageName);
            $pratihariProfile->profile_photo = 'uploads/profile_photos/' . $imageName;
        }

        // Save updated profile
        $pratihariProfile->save();

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'data' => $pratihariProfile
        ], 200);

    } catch (\Exception $e) {
        \Log::error('Error updating profile: ' . $e->getMessage());

        return response()->json([
            'status' => false,
            'message' => 'An error occurred while updating the profile',
            'error' => $e->getMessage()
        ], 500);
    }
}

}