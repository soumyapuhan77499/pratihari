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

        // 10 local digits -> prefix country code
        if (strlen($digits) === 10 && $defaultCountryCode) {
            return $defaultCountryCode . $digits;
        }

        // 11 digits starting with 0 -> strip 0, then prefix country code
        if (strlen($digits) === 11 && $digits[0] === '0' && $defaultCountryCode) {
            return $defaultCountryCode . substr($digits, 1);
        }

        return $digits;
    }

    /** Validate & fetch MSG91 config, return array or throw \RuntimeException */
    private function msg91ConfigOrFail(): array
    {
        $cfg = [
            'auth'        => (string) config('services.msg91.auth_key'),
            'template'    => (string) config('services.msg91.wa_template'),
            'namespace'   => (string) config('services.msg91.wa_namespace'),
            'from_raw'    => (string) config('services.msg91.wa_number'),
            'lang_code'   => (string) config('services.msg91.wa_lang_code', 'en_US'),
            'lang_policy' => (string) config('services.msg91.wa_lang_policy', 'deterministic'),
            'body_params' => (int)    config('services.msg91.wa_body_params', 1),
        ];

        $missing = [];
        foreach (['auth','template','namespace','from_raw'] as $k) {
            if (empty($cfg[$k])) $missing[] = $k;
        }
        if ($missing) {
            throw new \RuntimeException('MSG91 config missing keys: '.implode(', ', $missing));
        }

        // Normalize sender (integrated WA number) to digits only
        $cfg['from'] = $this->normalizeMsisdn($cfg['from_raw'], '91');
        if (!$cfg['from']) {
            throw new \RuntimeException('MSG91 sender (wa_number) is invalid. Check MSG91_WA_NUMBER.');
        }

        if (!in_array($cfg['body_params'], [1,2], true)) {
            throw new \RuntimeException('MSG91_WA_BODY_PARAMS must be 1 or 2.');
        }

        return $cfg;
    }

    /** Build MSG91 WhatsApp template payload (1 or 2 body variables) */
    private function buildMsg91Payload(
        string $fromIntegrated,
        string $toMsisdn,
        string $template,
        string $namespace,
        array $bodyParams, // array of ['type'=>'text','text'=>'...']
        string $langCode = 'en_US',
        string $langPolicy = 'deterministic'
    ): array {
        return [
            "integrated_number" => $fromIntegrated, // digits only
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
            // Some infra expects lowercase, some capitalized
            'authkey'      => $authKey,
            'Authkey'      => $authKey,
        ])
        ->timeout(25)
        ->post($url, $payload);
    }

    /** Create a uniform, **detailed** error response for Postman */
    private function providerErrorResponse(int $httpStatus, $providerBody, string $fallbackMessage = 'Failed to send OTP.')
    {
        // Extract useful fields if JSON; else return string body
        $provider = [
            'http_status' => $httpStatus,
            'code'        => null,
            'type'        => null,
            'message'     => null,
            'raw'         => is_array($providerBody) ? $providerBody : (string) $providerBody,
        ];

        if (is_array($providerBody)) {
            // Common MSG91 fields: 'type', 'message', sometimes 'errors' or 'error'
            $provider['type']    = $providerBody['type']    ?? null;
            $provider['message'] = $providerBody['message'] ?? null;
            // attempt to surface nested codes/messages if provided
            if (isset($providerBody['errors']) && is_array($providerBody['errors']) && isset($providerBody['errors'][0])) {
                $first = $providerBody['errors'][0];
                $provider['code'] = $first['code']    ?? ($first['error_code'] ?? null);
                if (!$provider['message'] && isset($first['message'])) {
                    $provider['message'] = $first['message'];
                }
            }
            if (isset($providerBody['error'])) {
                $err = $providerBody['error'];
                if (is_array($err)) {
                    $provider['code'] = $provider['code'] ?? ($err['code'] ?? ($err['error_code'] ?? null));
                    if (!$provider['message']) $provider['message'] = $err['message'] ?? null;
                } elseif (is_string($err)) {
                    $provider['message'] = $provider['message'] ?? $err;
                }
            }
        }

        return response()->json([
            'success'  => false,
            'message'  => $fallbackMessage,
            'provider' => $provider,
        ], 502);
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

        try {
            $cfg = $this->msg91ConfigOrFail();
        } catch (\RuntimeException $e) {
            Log::error('MSG91 config error: '.$e->getMessage());
            // Also expose this clearly in API response
            return response()->json([
                'success'  => false,
                'message'  => 'OTP service is not configured properly.',
                'provider' => [
                    'http_status' => 500,
                    'type'        => 'config_error',
                    'message'     => $e->getMessage(),
                ],
            ], 500);
        }

        // Normalize destination to MSG91 format (digits, no +)
        $toMsisdn = $this->normalizeMsisdn($request->mobile_number, '91');
        if (!$toMsisdn) {
            return response()->json(['message' => 'Phone number format is invalid.'], 422);
        }

        // Create OTP & (optional) token
        $otp        = (string) random_int(100000, 999999);
        $shortToken = Str::upper(Str::random(6)); // used only if template expects 2 vars
        $expiresAt  = Carbon::now()->addMinutes(10);

        // Persist user/OTP (mutator will normalize +91 -> digits)
        $user = User::updateOrCreate(
            ['mobile_number' => $toMsisdn],
            ['otp' => $otp, 'otp_expires_at' => $expiresAt]
        );

        // Build BODY parameters according to template variable count
        $bodyParams = [
            ["type" => "text", "text" => $otp],
        ];
        if ($cfg['body_params'] === 2) {
            $bodyParams[] = ["type" => "text", "text" => $shortToken];
        }

        $payload = $this->buildMsg91Payload(
            $cfg['from'],
            $toMsisdn,
            $cfg['template'],
            $cfg['namespace'],
            $bodyParams,
            $cfg['lang_code'],
            $cfg['lang_policy'],
        );

        try {
            $resp = $this->sendViaMsg91($payload, $cfg['auth']);
            $json = $resp->json();
            $body = $json ?: $resp->body();

            // Log minimal but actionable info (no secrets)
            Log::info('MSG91 sendOtp response', [
                'http_status' => $resp->status(),
                'ok'          => $resp->successful(),
                'to'          => $toMsisdn,
                'from'        => $cfg['from'],
                'template'    => $cfg['template'],
                'namespace'   => $cfg['namespace'],
                'body_params' => $cfg['body_params'],
                'resp'        => is_array($body) ? $body : (string) $body,
            ]);

            // Hard failures (HTTP non-2xx)
            if (!$resp->successful()) {
                return $this->providerErrorResponse(
                    $resp->status(),
                    $body,
                    'Failed to send OTP.'
                );
            }

            // Soft failures (MSG91 returns 200 but type=error)
            if (is_array($json) && isset($json['type']) && strtolower((string) $json['type']) === 'error') {
                return $this->providerErrorResponse(
                    200,
                    $json,
                    'OTP could not be sent.'
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully via WhatsApp.',
                'debug_otp' => app()->environment('local') ? $otp : null,
                'expires_in_seconds' => 600,
            ], 200);

        } catch (\Throwable $e) {
            Log::error('MSG91 exception: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success'  => false,
                'message'  => 'OTP service unavailable. Please try again.',
                'provider' => [
                    'http_status' => 503,
                    'type'        => 'exception',
                    'message'     => $e->getMessage(),
                ],
            ], 503);
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

        // Success -> clear OTP
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
