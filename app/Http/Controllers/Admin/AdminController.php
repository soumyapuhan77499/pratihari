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

    $incompleteProfiles = PratihariProfile::where(function ($query) {
        $query->whereNull('email')
              ->orWhereNull('phone_no')
              ->orWhereNull('address_id') // Replace with your actual foreign key if needed
              ->orWhereNull('blood_group');
    })->count();

    $totalActiveUsers = PratihariProfile::where('pratihari_status', 'active')->count();

    // Logged-in user's profile completion
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

        $profileStatus['filled'] = array_keys(array_filter($tables));
        $profileStatus['empty'] = array_keys(array_filter($tables, fn($filled) => !$filled));
    }

    return view('admin.admin-dashboard', compact(
        'todayCount',
        'incompleteProfiles',
        'totalActiveUsers',
        'profileStatus'
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
    
}
