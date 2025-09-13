<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\WhatsappOtp;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Exception\RequestException;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Str;

class OtpController extends Controller
{
  
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

    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string',
        ]);

        $otp = rand(100000, 999999);
        $phone = $request->mobile_number;
        $shortToken = Str::random(6);

        // Update or create user
        $user = User::updateOrCreate(
            ['mobile_number' => $phone],
            ['otp' => $otp]
        );

        // 🔑 Env values
        $authKey   = env('MSG91_AUTHKEY');
        $waNumber  = env('MSG91_WA_NUMBER');
        $template  = env('MSG91_WA_TEMPLATE');
        $namespace = env('MSG91_WA_NAMESPACE');

        $payload = [
            "integrated_number" => $waNumber,
            "content_type" => "template",
            "payload" => [
                "messaging_product" => "whatsapp",
                "type" => "template",
                "template" => [
                    "name" => $template,
                    "language" => [
                        "code" => "en",
                        "policy" => "deterministic"
                    ],
                    "namespace" => $namespace,
                    "to_and_components" => [
                        [
                            "to" => [$phone],
                            "components" => [
                                "body_1" => [
                                    "type" => "text",
                                    "value" => (string) $otp
                                ],
                                "button_1" => [
                                    "subtype" => "url",
                                    "type" => "text",
                                    "value" => $shortToken
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'authkey' => $authKey,
        ])->post(
            'https://api.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/bulk/',
            $payload
        );

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
            'otp' => $otp, // ⚠️ only for testing
            'token' => $shortToken,
            'api_response' => $response->json()
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string',
            'otp' => 'required|string'
        ]);

        $user = User::where('mobile_number', $request->mobile_number)->first();

        if (!$user || $user->otp !== $request->otp) {
            return response()->json([
                'message' => 'Invalid OTP or mobile number.'
            ], 401);
        }

        // Clear OTP
        $user->otp = null;

        // Assign pratihari_id if not already set
        if (empty($user->pratihari_id)) {
            $user->pratihari_id = 'PRATIHARI' . rand(10000, 99999);
        }

        $user->save();

        // Create Sanctum token
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'User authenticated successfully.',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 200);
    }
}