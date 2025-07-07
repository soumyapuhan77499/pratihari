<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Exception\RequestException;
    use App\Services\WhatsappService;

class OtpController extends Controller
{
    private $apiUrl;
    private $clientId;
    private $clientSecret;

    public function __construct()
    {
        $this->apiUrl = 'https://auth.otpless.app';
        $this->clientId = 'Q9Z0F0NXFT3KG3IHUMA4U4LADMILH1CB';
        $this->clientSecret = '5rjidx7nav2mkrz9jo7f56bmj8zuc1r2';
    }

    // public function sendOtp(Request $request)
    // {
    //     if (!$request->expectsJson() && !$request->isJson()) {
    //         return response()->json(['message' => 'Only JSON requests are allowed'], 406);
    //     }
    
    //     $phoneNumber = $request->input('phone');
    
    //     if (!$phoneNumber) {
    //         return response()->json(['message' => 'Phone number is required.'], 422);
    //     }
    
    //     try {
    //         $client = new Client();
    //         $url = rtrim($this->apiUrl, '/') . '/auth/otp/v1/send';
    
    //         $response = $client->post($url, [
    //             'headers' => [
    //                 'Content-Type'  => 'application/json',
    //                 'clientId'      => $this->clientId,
    //                 'clientSecret'  => $this->clientSecret,
    //             ],
    //             'json' => ['phoneNumber' => $phoneNumber],
    //         ]);
    
    //         $body = json_decode($response->getBody(), true);
    //         Log::info("Send OTP Response: ", $body);
    
    //         if (!isset($body['orderId'])) {
    //             return response()->json(['message' => 'Failed to send OTP. Please try again.'], 400);
    //         }
    
    //         session(['otp_order_id' => $body['orderId']]);
    //         session(['otp_phone' => $phoneNumber]);
    
    //         return response()->json([
    //             'message' => 'OTP sent successfully',
    //             'order_id' => $body['orderId'],
    //             'phone' => $phoneNumber
    //         ], 200);
    //     } catch (RequestException $e) {
    //         Log::error("Send OTP Error: " . $e->getMessage());
    
    //         return response()->json([
    //             'message' => 'Failed to send OTP. Please try again.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    
    // ✅ Verify OTP Function
    // public function verifyOtp(Request $request)
    // {
    //     // Validate request inputs
    //     $validator = Validator::make($request->all(), [
    //         'orderId'   => 'required|string',
    //         'otp'       => 'required|digits:6',
    //         'phoneNumber' => 'required|string',
    //         'device_id' => 'nullable|string',
    //         'platform'  => 'nullable|string',
    //         'device_model' => 'nullable|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['message' => $validator->errors()->first()], 422);
    //     }

    //     // Extract values
    //     $orderId = $request->input('orderId');
    //     $otp = $request->input('otp');
    //     $phoneNumber = $request->input('phoneNumber');
    //     $deviceId = $request->input('device_id');
    //     $platform = $request->input('platform');
    //     $deviceModel = $request->input('device_model');

    //     $client = new Client();
    //     $url = rtrim($this->apiUrl, '/') . '/auth/otp/v1/verify';

    //     try {
    //         $response = $client->post($url, [
    //             'headers' => [
    //                 'Content-Type'  => 'application/json',
    //                 'clientId'      => $this->clientId,
    //                 'clientSecret'  => $this->clientSecret,
    //             ],
    //             'json' => [
    //                 'orderId' => $orderId,
    //                 'otp' => $otp,
    //                 'phoneNumber' => $phoneNumber,
    //             ],
    //         ]);

    //         $body = json_decode($response->getBody(), true);
    //         Log::info("Verify OTP Response: ", $body);

    //         if (!isset($body['isOTPVerified']) || !$body['isOTPVerified']) {
    //             return response()->json(['message' => 'Invalid OTP.'], 400);
    //         }

    //         $user = User::where('mobile_number', $phoneNumber)->first();

    //         if (!$user) {
    //             $user = User::create([
    //                 'pratihari_id' => 'PRATIHARI' . rand(10000, 99999),
    //                 'mobile_number' => $phoneNumber,
    //                 'order_id' => $orderId,
    //             ]);
    //         }

    //         // Store device details only if device_id is provided
    //         if ($deviceId) {
    //             UserDevice::updateOrCreate(
    //                 ['pratihari_id' => $user->pratihari_id, 'device_id' => $deviceId],
    //                 ['platform' => $platform, 'device_model' => $deviceModel]
    //             );
    //         }

    //         $token = $user->createToken('API Token')->plainTextToken;

    //         return response()->json([
    //             'message' => 'User authenticated successfully.',
    //             'user' => $user,
    //             'token' => $token,
    //             'token_type' => 'Bearer'
    //         ], 200);
    //     } catch (RequestException $e) {
    //         Log::error("Verify OTP Error: " . $e->getMessage());
    //         return response()->json(['message' => 'Failed to verify OTP. Please try again.'], 500);
    //     }
    // }

    // ✅ Logout Function
    public function userLogout(Request $request)
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $deviceId = $request->input('device_id');

        try {
            if ($deviceId) {
                $device = UserDevice::where('pratihari_id', $user->pratihari_id)->where('device_id', $deviceId)->first();
                if ($device) {
                    $device->delete();
                    $user->currentAccessToken()->delete();
                    Log::info("User logged out and device removed", ['pratihari_id' => $user->pratihari_id, 'device_id' => $deviceId]);
                } else {
                    return response()->json(['message' => 'Device not found.'], 404);
                }
            } else {
                $user->tokens()->delete();
                Log::info("User logged out from all devices", ['pratihari_id' => $user->pratihari_id]);
            }

            return response()->json(['message' => 'User logged out successfully.'], 200);
        } catch (\Exception $e) {
            Log::error("Logout Error: " . $e->getMessage());
            return response()->json(['message' => 'An error occurred while logging out.'], 500);
        }
    }

//    public function sendOtp(Request $request, WhatsappService $whatsappService)
// {
//     if (!$request->expectsJson() && !$request->isJson()) {
//         return response()->json(['message' => 'Only JSON requests are allowed'], 406);
//     }

//     $phoneNumber = $request->input('phone');

//     if (!$phoneNumber) {
//         return response()->json(['message' => 'Phone number is required.'], 422);
//     }

//     try {
//         $otp = rand(100000, 999999);

//         // Call service and get full HTTP response
//         $response = $whatsappService->sendOtp($phoneNumber, $otp);

//         if ($response->successful()) {
//             session(['otp_phone' => '91' . $phoneNumber]);
//             session(['otp' => $otp]);

//             return response()->json([
//                 'message' => 'OTP sent successfully via WhatsApp.',
//                 'phone' => $phoneNumber
//             ], 200);
//         } else {
//             $body = json_decode($response->body(), true);
//             $errorMsg = $body['message'] ?? 'Unknown error from MSG91.';

//             return response()->json([
//                 'message' => 'Failed to send OTP.',
//                 'error' => $errorMsg,
//                 'status' => $response->status(),
//                 'details' => $body
//             ], 400);
//         }
//     } catch (\Exception $e) {
//         Log::error("WhatsApp OTP Send Exception: " . $e->getMessage());

//         return response()->json([
//             'message' => 'Failed to send OTP due to exception.',
//             'error' => $e->getMessage()
//         ], 500);
//     }
// }

// public function verifyOtp(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'otp'       => 'required|digits:6',
//         'phone'     => 'required|string',
//         'device_id' => 'nullable|string',
//         'platform'  => 'nullable|string',
//         'device_model' => 'nullable|string',
//     ]);

//     if ($validator->fails()) {
//         return response()->json(['message' => $validator->errors()->first()], 422);
//     }

//     $phone = $request->input('phone');
//     $inputOtp = $request->input('otp');
//     $deviceId = $request->input('device_id');
//     $platform = $request->input('platform');
//     $deviceModel = $request->input('device_model');

//     $storedOtp = session('otp');
//     $storedPhone = session('otp_phone');

//     if (!$storedOtp || !$storedPhone) {
//         return response()->json(['message' => 'Session expired. Please request OTP again.'], 400);
//     }

//     if ($inputOtp != $storedOtp || $storedPhone !== '91' . $phone) {
//         return response()->json(['message' => 'Invalid OTP.'], 400);
//     }

//     try {
//         // Lookup or create user
//         $user = User::where('mobile_number', '91' . $phone)->first();

//         if (!$user) {
//             $user = User::create([
//                 'pratihari_id' => 'PRATIHARI' . rand(10000, 99999),
//                 'mobile_number' => '91' . $phone,
//             ]);
//         }

//         // Save device info
//         if ($deviceId) {
//             UserDevice::updateOrCreate(
//                 ['pratihari_id' => $user->pratihari_id, 'device_id' => $deviceId],
//                 ['platform' => $platform, 'device_model' => $deviceModel]
//             );
//         }

//         // Create API token
//         $token = $user->createToken('API Token')->plainTextToken;

//         // Clear OTP session
//         session()->forget(['otp', 'otp_phone']);

//         return response()->json([
//             'message' => 'User authenticated successfully.',
//             'user' => $user,
//             'token' => $token,
//             'token_type' => 'Bearer'
//         ], 200);
//     } catch (\Exception $e) {
//         Log::error("WhatsApp OTP Verify Error: " . $e->getMessage());

//         return response()->json(['message' => 'Failed to verify OTP. Please try again.'], 500);
//     }
// }

public function sendOtp(Request $request)
{
    if (!$request->expectsJson() && !$request->isJson()) {
        return response()->json(['message' => 'Only JSON requests are allowed'], 406);
    }

    $phoneNumber = $request->input('phone');

    if (!$phoneNumber) {
        return response()->json(['message' => 'Phone number is required.'], 422);
    }

$fullPhone = '+91' . $phoneNumber;

    // Lookup user with static OTP
    $user = User::where('mobile_number', $fullPhone)->first();

    if (!$user || !$user->otp) {
        return response()->json(['message' => 'This number is not registered or OTP not set.'], 404);
    }

    // Store phone & OTP in session for verification
    session(['otp_phone' => $fullPhone]);
    session(['otp' => $user->otp]);

    return response()->json([
        'message' => 'OTP is preset in the database. Use it to verify.',
        'phone' => $phoneNumber
    ], 200);
}
public function verifyOtp(Request $request)
{
    $validator = Validator::make($request->all(), [
        'otp'   => 'required|digits:6',
        'phone' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => $validator->errors()->first()], 422);
    }

    $phone = $request->input('phone');
    $inputOtp = $request->input('otp');
    $fullPhone = '91' . $phone;

    // Lookup user with matching phone and OTP (both must match)
    $user = User::where('mobile_number', $fullPhone)
                ->where('otp', $inputOtp)
                ->first();

    if (!$user) {
        return response()->json(['message' => 'Invalid mobile number or OTP.'], 400);
    }

    // Create API token
    $token = $user->createToken('API Token')->plainTextToken;

    return response()->json([
        'message' => 'User authenticated successfully.',
        'user' => [
            'id' => $user->id,
            'pratihari_id' => $user->pratihari_id,
            'mobile_number' => $user->mobile_number,
            // add any other user fields you want here
        ],
        'token' => $token,
        'token_type' => 'Bearer'
    ], 200);
}

}
