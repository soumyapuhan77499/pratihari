<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserDevice;
use Carbon\Carbon;

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
                $device = UserDevice::where('pratihari_id', $user->pratihari_id)
                    ->where('device_id', $deviceId)->first();
                if ($device) {
                    $device->delete();
                    $user->currentAccessToken()?->delete();
                    Log::info("User logged out and device removed", [
                        'pratihari_id' => $user->pratihari_id, 'device_id' => $deviceId
                    ]);
                } else {
                    return response()->json(['message' => 'Device not found.'], 404);
                }
            } else {
                $user->tokens()->delete();
                Log::info("User logged out from all devices", [
                    'pratihari_id' => $user->pratihari_id
                ]);
            }

            return response()->json(['message' => 'User logged out successfully.'], 200);
        } catch (\Throwable $e) {
            Log::error("Logout Error: " . $e->getMessage());
            return response()->json(['message' => 'An error occurred while logging out.'], 500);
        }
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => ['required','string','regex:/^\d{10,15}$/'],
        ], [
            'mobile_number.regex' => 'The mobile number must be 10 to 15 digits.'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $phone      = $request->mobile_number;
        $otp        = random_int(100000, 999999);
        $shortToken = Str::upper(Str::random(6));
        $expiresAt  = Carbon::now()->addMinutes(10);

        // Store OTP and expiry on user
        $user = User::updateOrCreate(
            ['mobile_number' => $phone],
            ['otp' => (string)$otp, 'otp_expires_at' => $expiresAt]
        );

        // MSG91 env
        $authKey   = (string) env('MSG91_AUTHKEY');
        $waNumber  = (string) env('MSG91_WA_NUMBER');
        $template  = (string) env('MSG91_WA_TEMPLATE');
        $namespace = (string) env('MSG91_WA_NAMESPACE');

        $payload = [
            "integrated_number" => $waNumber,
            "content_type" => "template",
            "payload" => [
                "messaging_product" => "whatsapp",
                "type" => "template",
                "template" => [
                    "name" => $template,
                    "language" => ["code" => "en", "policy" => "deterministic"],
                    "namespace" => $namespace,
                    "to_and_components" => [[
                        "to" => [$phone],
                        "components" => [
                            "body_1"   => ["type" => "text", "value" => (string)$otp],
                            "button_1" => ["subtype" => "url", "type" => "text", "value" => $shortToken],
                        ]
                    ]]
                ]
            ]
        ];

        try {
            $resp = Http::withHeaders([
                'Content-Type' => 'application/json',
                'authkey' => $authKey,
            ])->post('https://api.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/bulk/', $payload);

            if (!$resp->successful()) {
                Log::error('MSG91 send error', ['status' => $resp->status(), 'body' => $resp->body()]);
                return response()->json(['message' => 'Failed to send OTP. Please try again.'], 502);
            }
        } catch (\Throwable $e) {
            Log::error('MSG91 exception: ' . $e->getMessage());
            return response()->json(['message' => 'OTP service unavailable. Please try again.'], 503);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully.',
            'debug_otp' => app()->environment('local') ? $otp : null,
            'token_hint' => $shortToken,
            'expires_in_seconds' => 600,
        ], 200);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => ['required','string','regex:/^\d{10,15}$/'],
            'otp' => ['required','digits:6'],
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $user = User::where('mobile_number', $request->mobile_number)->first();
        if (!$user || !$user->otp) {
            return response()->json(['message' => 'OTP not found. Please request a new one.'], 404);
        }
        if (!empty($user->otp_expires_at) && now()->greaterThan(Carbon::parse($user->otp_expires_at))) {
            return response()->json(['message' => 'OTP has expired. Please request a new one.'], 410);
        }
        if ($user->otp !== $request->otp) {
            return response()->json(['message' => 'Invalid OTP.'], 401);
        }

        // Clear OTP
        $user->otp = null;
        $user->otp_expires_at = null;

        if (empty($user->pratihari_id)) {
            $user->pratihari_id = 'PRATIHARI' . random_int(10000, 99999);
        }
        $user->save();

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'User authenticated successfully.',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 200);
    }
}
