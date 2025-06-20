<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\PratihariProfile;
use App\Models\PratihariFamily;
use App\Models\PratihariIdcard;
use App\Models\PratihariAddress;
use App\Models\PratihariSeba;
use App\Models\PratihariSocialMedia;
use App\Models\PratihariOccupation;
use App\Models\PratihariApplication;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class AdminController extends Controller
{
    private $apiUrl = 'https://auth.otpless.app';
    private $clientId = 'Q9Z0F0NXFT3KG3IHUMA4U4LADMILH1CB';
    private $clientSecret = '5rjidx7nav2mkrz9jo7f56bmj8zuc1r2';

    public function showOtpForm()
    {
        return view('admin.admin-login');
    }
    
    public function dashboard()
    {
        $todayCount = PratihariProfile::whereDate('created_at', Carbon::today())->count();

        $incompleteProfiles = PratihariProfile::where('pratihari_status',['pending','rejected'])->where(function ($query) {
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
        ->count();
        $totalActiveUsers = PratihariProfile::where('status', 'active')->where('pratihari_status', 'approved')->count();

        $updatedProfile = PratihariProfile::where('status', 'active')->where('pratihari_status', 'updated')->count();

        $pendingProfile = PratihariProfile::where('status', 'active')->where('pratihari_status', 'pending')->count();

        $rejectedUsers = PratihariProfile::where('pratihari_status', 'rejected')->count();

        $profiles = PratihariProfile::with(['occupation', 'address'])->where('status','active')->get();

        $todayApplication = PratihariApplication::whereDate('created_at', Carbon::today())
        ->where('status', 'active')
        ->count();

        $profile_name = PratihariProfile::where('status', 'active')->where('pratihari_status', 'approved')->get();

        $user = Auth::user();

        $profileStatus = [];
        if ($user) {
            $pratihari_id = $user->pratihari_id;
            $tables = [
                'profile' => PratihariProfile::where('pratihari_id', $pratihari_id)->exists(),
                'family' => PratihariFamily::where('pratihari_id', $pratihari_id)->exists(),
                'id_card' => PratihariIdcard::where('pratihari_id', $pratihari_id)->exists(),
                'address' => PratihariAddress::where('pratihari_id', $pratihari_id)->exists(),
                'seba' => PratihariSeba::where('pratihari_id', $pratihari_id)->exists(),
                'social_media' => PratihariSocialMedia::where('pratihari_id', $pratihari_id)->exists(),
            ];
        }

        return view('admin.admin-dashboard', compact(
            'todayCount',
            'incompleteProfiles',
            'totalActiveUsers',
            'rejectedUsers',
            'updatedProfile',
            'pendingProfile',
            'todayApplication',
            'profiles',
            'profile_name'
        ));
    }

    public function pratihariManageProfile()
    {

        return view('admin.pratihari-manage-profile', compact('profiles'));
    }

    public function sendOtp(Request $request)
    {
        $phoneNumber = '+91' . $request->input('phone');
        $admin = Admin::where('mobile_no', $phoneNumber)->first();

        if (!$admin) {
            return back()->with('message', 'Your number is not registered. Please contact the Super Admin.');
        }

        try {
            $client = new Client();
            $response = $client->post("{$this->apiUrl}/auth/otp/v1/send", [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'clientId'      => $this->clientId,
                    'clientSecret'  => $this->clientSecret,
                ],
                'json' => ['phoneNumber' => $phoneNumber],
            ]);

            $body = json_decode($response->getBody(), true);

            if (!isset($body['orderId'])) {
                return back()->with('message', 'Failed to send OTP. No Order ID received.');
            }

            // Store phone number and order ID in session
            Session::put('otp_phone', $phoneNumber);
            Session::put('otp_order_id', $body['orderId']);

            return back()->with(['otp_sent' => true, 'message' => 'OTP sent successfully.']);
        } catch (RequestException $e) {
            return back()->with('message', 'Failed to send OTP due to an error.');
        }
    }

    public function verifyOtp(Request $request)
    {
        $otp = $request->input('otp');
        $phoneNumber = session('otp_phone');
        $orderId = session('otp_order_id'); // Ensure this is set in sendOtp
    
        if (!$orderId || !$phoneNumber) {
            return redirect()->back()->with('message', 'Session expired. Please request OTP again.');
        }
    
        $client = new Client();
        $url = rtrim($this->apiUrl, '/') . '/auth/otp/v1/verify';
    
        try {
            $response = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'clientId' => $this->clientId,
                    'clientSecret' => $this->clientSecret,
                ],
                'json' => [
                    'orderId' => $orderId,
                    'otp' => $otp,
                    'phoneNumber' => $phoneNumber,
                ],
            ]);
    
            $body = json_decode($response->getBody(), true);
    
            if (isset($body['isOTPVerified']) && $body['isOTPVerified']) {
                $admin = Admin::where('mobile_no', $phoneNumber)->first();
    
                if (!$admin) {
                    // Create new admin record
                    $admin = Admin::firstOrCreate(
                        ['mobile_no' => $phoneNumber],
                        ['admin' => 'ADMIN' . rand(10000, 99999), 'order_id' => $orderId]
                    );
                }
    
                // Login user
                Auth::guard('admins')->login($admin);
    
                // Clear session values
                Session::forget(['otp_phone', 'otp_order_id']);
    
                return redirect()->route('admin.dashboard')->with('success', 'User authenticated successfully.');
            } else {
                return redirect()->back()->with('message', $body['message'] ?? 'Invalid OTP');
            }
        } catch (RequestException $e) {
            return redirect()->back()->with('message', 'Failed to verify OTP due to an error.');
        }
    }

    public function logout()
    {
        Auth::guard('admins')->logout();

        return redirect()->route('admin.AdminLogin');
    }

   public function sebaDate(Request $request)
{
    $pratihariId = $request->input('pratihari_id');
    $events = [];

    if ($pratihariId) {
        $sebas = PratihariSeba::with('sebaMaster')->where('pratihari_id', $pratihariId)->get();

        foreach ($sebas as $seba) {
            $sebaName = $seba->sebaMaster->seba_name ?? 'Unknown Seba';
            $beddhaIds = $seba->beddha_id;

            foreach ($beddhaIds as $beddhaId) {
                $beddhaId = (int) trim($beddhaId);

                if ($beddhaId >= 1 && $beddhaId <= 47) {
                    $startDate = Carbon::create(2025, 7, 1)->addDays($beddhaId - 1);
                    $endDate = Carbon::create(2030, 12, 31);
                    $nextDate = $startDate->copy();

                    while ($nextDate->lte($endDate)) {
                        $events[] = [
                            'title' => "Beddha-$beddhaId",
                            'start' => $nextDate->toDateString(),
                            'extendedProps' => [
                                'sebaName' => $sebaName,
                                'beddhaId' => $beddhaId
                            ]
                        ];
                        $nextDate->addDays(47);
                    }
                }
            }
        }
    }

    return response()->json($events);
}




}
