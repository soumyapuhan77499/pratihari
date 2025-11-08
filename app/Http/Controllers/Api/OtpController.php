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
    /**
     * Normalize a phone number to MSG91-style E.164 digits **without plus**.
     * Examples:
     *   "9812345678"       -> "919812345678"
     *   "+91-98123 45678"  -> "919812345678"
     *   "09812345678"      -> "919812345678"
     */
    private function normalizeMsisdn(?string $raw, string $defaultCountryCode = '91'): string
    {
        $digits = preg_replace('/\D+/', '', (string) $raw);
        if ($digits === '') return '';

        // 10-digit local => prefix country code
        if (strlen($digits) === 10) {
            return $defaultCountryCode . $digits;
        }

        // 11-digit starting with 0 => strip 0, then prefix country code
        if (strlen($digits) === 11 && $digits[0] === '0') {
            return $defaultCountryCode . substr($digits, 1);
        }

        // Already looks like CC + national (e.g., 91xxxxxxxxxx)
        return $digits;
    }

    /** Build MSG91 WhatsApp Template payload */
    private function buildMsg91Payload(string $toMsisdn, string $otp): array
    {
        $integrated = preg_replace('/\D+/', '', (string) env('MSG91_WA_NUMBER', '')); // digits only (no "+")
        $template   = env('MSG91_WA_TEMPLATE', '');
        $namespace  = env('MSG91_WA_NAMESPACE', '');

        // Minimal payload with a single body var (body_1). Adjust if your template needs more.
        return [
            "integrated_number" => $integrated,           // e.g., 919124420330
            "content_type"      => "template",
            "payload" => [
                "messaging_product" => "whatsapp",
                "type"              => "template",
                "template" => [
                    "name"      => $template,
                    "language"  => [
                        "code"   => "en_US",
                        "policy" => "deterministic"
                    ],
                    "namespace" => $namespace,
                    "to_and_components" => [[
                        "to" => [$toMsisdn],
                        "components" => [
                            // Your template variable mapping. Rename/extend keys to match MSG91 template vars.
                            "body_1" => [
                                "type"  => "text",
                                "value" => $otp
                            ],
                            // Example button var if your template uses a URL button param:
                            // "button_1" => ["subtype" => "url", "type" => "text", "value" => "https://yourapp.example/verify"]
                        ]
                    ]]
                ]
            ]
        ];
    }

    // ===================== SEND OTP =====================
    public function sendOtp(Request $request)
    {
        $v = Validator::make($request->all(), [
            'mobile'    => 'required|string',
            'device_id' => 'nullable|string|max:255',
        ]);

        if ($v->fails()) {
            return response()->json(['message' => 'Invalid input', 'errors' => $v->errors()], 422);
        }

        $rawMobile = $request->input('mobile');
        $msisdn    = $this->normalizeMsisdn($rawMobile, '91');
        if ($msisdn === '' || strlen($msisdn) < 12) {
            return response()->json(['message' => 'Invalid mobile number'], 422);
        }

        // Find or create user by mobile_number
        $user = User::where('mobile_number', $msisdn)->first();
        if (!$user) {
            $user = new User();
            $user->mobile_number = $msisdn;
            $user->pratihari_id  = $user->pratihari_id ?: ('PRATIHARI' . str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT));
        }

        $otpLength = (int) env('OTP_LENGTH', 6);
        $otp       = str_pad((string) random_int(0, (10 ** $otpLength) - 1), $otpLength, '0', STR_PAD_LEFT);
        $ttlMin    = (int) env('OTP_TTL_MINUTES', 10);

        $user->otp    = $otp; // You can swap to hashing if you prefer (and compare via hash on verify)
        $user->expiry = Carbon::now()->addMinutes($ttlMin);
        $user->channel = 'whatsapp';
        $user->save();

        // Send via MSG91 WhatsApp Template API
        $payload  = $this->buildMsg91Payload($msisdn, $otp);
        $authkey  = env('MSG91_AUTHKEY', '');
        $endpoint = 'https://api.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/bulk/';

        try {
            $resp = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'authkey'      => $authkey,
                    ])
                    ->timeout(20)
                    ->post($endpoint, $payload);

            // Log minimal info (avoid logging otp in production)
            Log::info('MSG91 WA sendOtp response', [
                'to'       => $msisdn,
                'status'   => $resp->status(),
                'ok'       => $resp->successful(),
            ]);

            if (!$resp->successful()) {
                return response()->json([
                    'message' => 'Failed to send OTP via WhatsApp',
                    'status'  => $resp->status(),
                    'error'   => $resp->json() ?: $resp->body(),
                ], 502);
            }

            return response()->json([
                'message'       => 'OTP sent successfully via WhatsApp',
                'expires_in'    => $ttlMin * 60,  // seconds
                'mobile_number' => $msisdn,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('MSG91 WA sendOtp exception: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Could not send OTP at the moment',
            ], 500);
        }
    }

    // ===================== VERIFY OTP =====================
    public function verifyOtp(Request $request)
    {
        $v = Validator::make($request->all(), [
            'mobile'    => 'required|string',
            'otp'       => 'required|string',
            'device_id' => 'nullable|string|max:255',
        ]);

        if ($v->fails()) {
            return response()->json(['message' => 'Invalid input', 'errors' => $v->errors()], 422);
        }

        $msisdn = $this->normalizeMsisdn($request->input('mobile'), '91');
        if ($msisdn === '' || strlen($msisdn) < 12) {
            return response()->json(['message' => 'Invalid mobile number'], 422);
        }

        $user = User::where('mobile_number', $msisdn)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!$user->otp || !$user->expiry) {
            return response()->json(['message' => 'No OTP pending verification'], 400);
        }

        if (Carbon::now()->greaterThan($user->expiry)) {
            return response()->json(['message' => 'OTP expired'], 400);
        }

        if (trim($request->input('otp')) !== (string) $user->otp) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        // OTP OK — clear it, login, and optionally bind device
        $user->otp = null;
        $user->expiry = null;
        $user->save();

        // Issue Sanctum token
        $token = $user->createToken('mobile-login')->plainTextToken;

        // Save device if provided
        if ($request->filled('device_id')) {
            try {
                UserDevice::updateOrCreate(
                    [
                        'pratihari_id' => $user->pratihari_id,
                        'device_id'    => $request->input('device_id'),
                    ],
                    ['last_seen_at' => Carbon::now()]
                );
            } catch (\Throwable $e) {
                Log::warning('verifyOtp: device save failed: '.$e->getMessage());
            }
        }

        return response()->json([
            'message'       => 'OTP verified successfully',
            'token'         => $token,
            'pratihari_id'  => $user->pratihari_id,
            'mobile_number' => $msisdn,
        ], 200);
    }

    // ===== Your existing logout stays here =====
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
            Log::error("Logout Error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while logging out.'], 500);
        }
    }
}
