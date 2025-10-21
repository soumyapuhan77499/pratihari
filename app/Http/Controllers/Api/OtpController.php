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
    /** Normalize a phone number to E.164 without "+" (MSG91 style: 9198xxxxxxxx). */
    private function normalizeMsisdn(?string $raw, string $defaultCountryCode = '91'): string
    {
        $digits = preg_replace('/\D+/', '', (string) $raw);
        if ($digits === '') return '';

        // 10 digits -> assume local, prefix country code
        if (strlen($digits) === 10 && $defaultCountryCode) {
            return $defaultCountryCode . $digits;
        }

        // 11 digits starting with 0 -> strip 0, then prefix country code
        if (strlen($digits) === 11 && $digits[0] === '0' && $defaultCountryCode) {
            return $defaultCountryCode . substr($digits, 1);
        }

        // Already has country code
        return $digits;
    }

    /** Build MSG91 WhatsApp Template payload (align with your approved template variables). */
    private function buildMsg91Payload(
        string $fromIntegrated,
        string $toMsisdn,
        string $template,
        string $namespace,
        string $otp,
        string $shortToken
    ): array {
        return [
            "integrated_number" => $fromIntegrated,         // e.g., 919124420330
            "content_type"      => "template",
            "payload" => [
                "messaging_product" => "whatsapp",
                "type"              => "template",
                "template" => [
                    "name"      => $template,              // e.g., nitiapp
                    "language"  => ["code" => "en", "policy" => "deterministic"],
                    "namespace" => $namespace,
                    "to_and_components" => [[
                        "to" => [$toMsisdn],              // e.g., 9197xxxxxxxx
                        "components" => [
                            // Map to your template placeholders:
                            // body_1 -> {{1}} in body
                            // button_1 (URL) -> {{1}} on first URL button (if exists)
                            "body_1"   => ["type" => "text", "value" => (string) $otp],
                            "button_1" => ["subtype" => "url", "type" => "text", "value" => $shortToken],
                        ]
                    ]]
                ]
            ]
        ];
    }

    /** Send WhatsApp template via MSG91 */
    private function sendViaMsg91(array $payload, string $authKey)
    {
        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
            // IMPORTANT: matches .env key below (MSG91_AUTH_KEY)
            'authkey'      => $authKey,
        ])->post('https://api.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/bulk/', $payload);
    }

    // ==== Logout ============================================================
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

    // ==== SEND OTP ==========================================================
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => ['required','string','regex:/^\+?\d{10,15}$/'],
        ], [
            'mobile_number.regex' => 'The mobile number must be 10 to 15 digits (you may include a leading +).'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        // Normalize numbers to MSG91-expected form (no "+")
        $toMsisdn       = $this->normalizeMsisdn($request->mobile_number, '91');
        $integratedFrom = $this->normalizeMsisdn((string) config('services.msg91.wa_number'), '91');

        if (!$toMsisdn || !$integratedFrom) {
            return response()->json(['message' => 'Phone number format is invalid.'], 422);
        }

        $otp        = (string) random_int(100000, 999999);
        $shortToken = Str::upper(Str::random(6));
        $expiresAt  = Carbon::now()->addMinutes(10);

        // Persist OTP (+ expiry)
        $user = User::updateOrCreate(
            ['mobile_number' => $toMsisdn],
            ['otp' => $otp, 'otp_expires_at' => $expiresAt]
        );

        // Build WhatsApp payload
        $payload = $this->buildMsg91Payload(
            $integratedFrom,
            $toMsisdn,
            (string) config('services.msg91.wa_template'),
            (string) config('services.msg91.wa_namespace'),
            $otp,
            $shortToken
        );

        try {
            $resp = $this->sendViaMsg91($payload, (string) config('services.msg91.auth_key'));

            // Log status & body either way
            Log::info('MSG91 response', [
                'status' => $resp->status(),
                'body'   => $resp->json() ?: $resp->body(),
            ]);

            if (!$resp->successful()) {
                return response()->json(['message' => 'Failed to send OTP. Please try again.'], 502);
            }

            $json = $resp->json();

            // Some MSG91 failures can be logical errors inside 200; check a common success flag if present
            if (is_array($json) && isset($json['type']) && $json['type'] === 'error') {
                return response()->json(['message' => 'OTP could not be sent. MSG91 error.'], 502);
            }

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully via WhatsApp.',
                'debug_otp' => app()->environment('local') ? $otp : null, // never expose in prod
                'expires_in_seconds' => 600,
            ], 200);

        } catch (\Throwable $e) {
            Log::error('MSG91 exception: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'OTP service unavailable. Please try again.'], 503);
        }
    }

    // ==== VERIFY OTP ========================================================
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => ['required','string','regex:/^\+?\d{10,15}$/'],
            'otp' => ['required','digits:6'],
        ], [
            'mobile_number.regex' => 'The mobile number must be 10 to 15 digits (you may include a leading +).'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $toMsisdn = $this->normalizeMsisdn($request->mobile_number, '91');

        $user = User::where('mobile_number', $toMsisdn)->first();
        if (!$user || !$user->otp) {
            return response()->json(['message' => 'OTP not found. Please request a new one.'], 404);
        }

        // Enforce expiry
        if ($user->otp_expires_at && Carbon::parse($user->otp_expires_at)->isPast()) {
            // clear expired OTP to avoid reuse
            $user->otp = null;
            $user->save();
            return response()->json(['message' => 'OTP expired. Please request a new one.'], 410);
        }

        if (!hash_equals((string) $user->otp, (string) $request->otp)) {
            return response()->json(['message' => 'Invalid OTP.'], 401);
        }

        // Clear OTP
        $user->otp = null;
        $user->otp_expires_at = null;

        // Assign pratihari_id if missing
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
