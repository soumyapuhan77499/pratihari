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
    /** Normalize to digits (E.164 w/o "+"): e.g. 91XXXXXXXXXX */
    private function normalizeMsisdn(?string $raw, string $defaultCountryCode = '91'): string
    {
        $digits = preg_replace('/\D+/', '', (string) $raw);
        if ($digits === '') return '';

        if (strlen($digits) === 10 && $defaultCountryCode) {
            return $defaultCountryCode . $digits;
        }

        if (strlen($digits) === 11 && $digits[0] === '0' && $defaultCountryCode) {
            return $defaultCountryCode . substr($digits, 1);
        }

        return $digits;
    }

    private function buildMsg91Payload(
        string $fromIntegrated,
        string $toMsisdn,
        string $template,
        string $namespace,
        string $otp,
        ?string $shortToken = null,
        string $langCode = 'en_US',
        string $langPolicy = 'deterministic'
    ): array {
        // If your WA template has only one variable, send only OTP param.
        $bodyParams = [
            ["type" => "text", "text" => (string) $otp],
        ];
        if ($shortToken) {
            $bodyParams[] = ["type" => "text", "text" => (string) $shortToken];
        }

        return [
            "integrated_number" => $fromIntegrated,   // digits only
            "content_type"      => "template",
            "payload"           => [
                "messaging_product" => "whatsapp",
                "type"              => "template",
                "template" => [
                    "name"       => $template,
                    "language"   => [
                        "code"   => $langCode,
                        "policy" => $langPolicy,
                    ],
                    "namespace"  => $namespace,
                    "components" => [
                        [
                            "type"       => "body",
                            "parameters" => $bodyParams,
                        ],
                    ],
                ],
                "to" => [
                    ["phone_number" => $toMsisdn], // digits only
                ],
            ],
        ];
    }

    private function sendViaMsg91(array $payload, string $authKey)
    {
        $url = 'https://api.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/bulk/';

        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
            // Both casings to dodge infra/proxy quirks
            'authkey'      => $authKey,
            'Authkey'      => $authKey,
        ])->timeout(25)->post($url, $payload);
    }

    // ----------------- Logout -----------------
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
                    Log::info("User logged out & device removed", [
                        'pratihari_id' => $user->pratihari_id,
                        'device_id'    => $deviceId
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
            Log::error("Logout Error: ".$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred while logging out.'], 500);
        }
    }

    // ----------------- SEND OTP -----------------
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => ['required','string','regex:/^\+?\d{10,15}$/'],
        ], [
            'mobile_number.regex' => 'The mobile number must be 10 to 15 digits (a leading + is allowed).'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        // Normalize to MSG91 format (digits, no +)
        $toMsisdn       = $this->normalizeMsisdn($request->mobile_number, '91');
        $integratedFrom = $this->normalizeMsisdn((string) config('services.msg91.wa_number'), '91');

        if (!$toMsisdn || !$integratedFrom) {
            return response()->json(['message' => 'Phone number format is invalid.'], 422);
        }

        $otp        = (string) random_int(100000, 999999);
        $shortToken = Str::upper(Str::random(6));     // keep optional in payload
        $expiresAt  = Carbon::now()->addMinutes(10);

        // Persist (set mutator will normalize +91 -> digits)
        $user = User::updateOrCreate(
            ['mobile_number' => $toMsisdn],
            ['otp' => $otp, 'otp_expires_at' => $expiresAt]
        );

        $payload = $this->buildMsg91Payload(
            $integratedFrom,
            $toMsisdn,
            (string) config('services.msg91.wa_template'),
            (string) config('services.msg91.wa_namespace'),
            $otp,
            $shortToken,
            (string) config('services.msg91.wa_lang_code', 'en_US'),
            (string) config('services.msg91.wa_lang_policy', 'deterministic'),
        );

        try {
            $resp   = $this->sendViaMsg91($payload, (string) config('services.msg91.auth_key'));
            $json   = $resp->json();
            $body   = $json ?: $resp->body();

            Log::info('MSG91 sendOtp response', [
                'status'   => $resp->status(),
                'headers'  => $resp->headers(),
                'body'     => $body,
                'payload'  => $payload,
                'to'       => $toMsisdn,
                'from'     => $integratedFrom,
            ]);

            if (!$resp->successful()) {
                $msg = 'Failed to send OTP. Please try again.';
                if (is_array($json) && isset($json['message'])) {
                    $msg .= ' Reason: ' . $json['message'];
                }
                return response()->json(['message' => $msg], 502);
            }

            // MSG91 sometimes returns 200 + {"type":"error", ...}
            if (is_array($json) && isset($json['type']) && strtolower((string) $json['type']) === 'error') {
                $reason = $json['message'] ?? 'Unknown MSG91 error';
                return response()->json(['message' => 'OTP could not be sent. '.$reason], 502);
            }

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully via WhatsApp.',
                'debug_otp' => app()->environment('local') ? $otp : null,
                'expires_in_seconds' => 600,
            ], 200);

        } catch (\Throwable $e) {
            Log::error('MSG91 exception: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'OTP service unavailable. Please try again.'], 503);
        }
    }

    // ----------------- VERIFY OTP -----------------
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => ['required','string','regex:/^\+?\d{10,15}$/'],
            'otp' => ['required','digits:6'],
        ], [
            'mobile_number.regex' => 'The mobile number must be 10 to 15 digits (a leading + is allowed).'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $toMsisdn = $this->normalizeMsisdn($request->mobile_number, '91');

        $user = User::where('mobile_number', $toMsisdn)->first();
        if (!$user || !$user->otp) {
            return response()->json(['message' => 'OTP not found. Please request a new one.'], 404);
        }

        if ($user->otp_expires_at && Carbon::parse($user->otp_expires_at)->isPast()) {
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();
            return response()->json(['message' => 'OTP expired. Please request a new one.'], 410);
        }

        if (!hash_equals((string) $user->otp, (string) $request->otp)) {
            return response()->json(['message' => 'Invalid OTP.'], 401);
        }

        // success -> clear OTP
        $user->otp = null;
        $user->otp_expires_at = null;

        if (empty($user->pratihari_id)) {
            $user->pratihari_id = 'PRATIHARI' . random_int(10000, 99999);
        }
        $user->save();

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message'    => 'User authenticated successfully.',
            'token'      => $token,
            'token_type' => 'Bearer',
            'user'       => $user,
        ], 200);
    }
}
