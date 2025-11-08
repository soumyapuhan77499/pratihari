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

        $cfg['from'] = $this->normalizeMsisdn($cfg['from_raw'], '91');
        if (!$cfg['from']) {
            throw new \RuntimeException('MSG91 sender (wa_number) is invalid. Check MSG91_WA_NUMBER.');
        }

        if (!in_array($cfg['body_params'], [1,2], true)) {
            throw new \RuntimeException('MSG91_WA_BODY_PARAMS must be 1 or 2.');
        }

        return $cfg;
    }

    /**
     * ✅ Build MSG91 bulk payload using to_and_components (EXPECTED FORMAT)
     * components must be an OBJECT keyed as body_1, body_2, ... each => {type:"text", value:"..."}
     * "to" must be an ARRAY of msisdns (strings).
     */
    private function buildMsg91Payload(
        string $fromIntegrated,
        array $toList,                 // <-- array of digits-only msisdns
        string $template,
        string $namespace,
        array $bodyValues,             // <-- ['123456'] or ['123456','TOKEN123']
        string $langCode = 'en_US',
        string $langPolicy = 'deterministic'
    ): array {
        // Build components object: body_1, body_2 ...
        $components = [];
        foreach ($bodyValues as $i => $val) {
            $components['body_' . ($i + 1)] = [
                'type'  => 'text',
                'value' => (string) $val,
            ];
        }

        return [
            "integrated_number" => $fromIntegrated, // digits only
            "content_type"      => "template",
            "payload"           => [
                "type"      => "template",
                "template"  => [
                    "name"      => $template,
                    "namespace" => $namespace,
                    "language"  => [
                        "code"   => $langCode,
                        "policy" => $langPolicy,
                    ],
                ],
                "to_and_components" => [
                    [
                        "to"         => $toList,     // array of "91XXXXXXXXXX"
                        "components" => $components, // object: { "body_1": {...}, "body_2": {...} }
                    ],
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
            'authkey'      => $authKey,
            'Authkey'      => $authKey,
        ])
        ->timeout(25)
        ->post($url, $payload);
    }

    /** Build consistent provider error response */
    private function providerErrorResponse(int $httpStatus, $providerBody, string $fallbackMessage = 'Failed to send OTP.')
    {
        $provider = [
            'http_status' => $httpStatus,
            'code'        => null,
            'type'        => null,
            'message'     => null,
            'raw'         => is_array($providerBody) ? $providerBody : (string) $providerBody,
        ];

        if (is_array($providerBody)) {
            $provider['type']    = $providerBody['type']    ?? null;
            $provider['message'] = $providerBody['message'] ?? ($providerBody['errors'] ?? null);
            if (isset($providerBody['errors']) && is_array($providerBody['errors']) && isset($providerBody['errors'][0])) {
                $first = $providerBody['errors'][0];
                $provider['code'] = $first['code'] ?? ($first['error_code'] ?? null);
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

        $toMsisdn = $this->normalizeMsisdn($request->mobile_number, '91');
        if (!$toMsisdn) {
            return response()->json(['message' => 'Phone number format is invalid.'], 422);
        }

        $otp        = (string) random_int(100000, 999999);
        $shortToken = Str::upper(Str::random(6)); // if template has 2 vars
        $expiresAt  = Carbon::now()->addMinutes(10);

        $user = User::updateOrCreate(
            ['mobile_number' => $toMsisdn],
            ['otp' => $otp, 'otp_expires_at' => $expiresAt]
        );

        // ⚠️ Build body values list in correct order for body_1, body_2 ...
        $bodyValues   = [$otp];
        if ($cfg['body_params'] === 2) {
            $bodyValues[] = $shortToken;
        }

        // ✅ Pass "to" as ARRAY, "components" as OBJECT
        $payload = $this->buildMsg91Payload(
            $cfg['from'],
            [$toMsisdn],            // <-- array as required by MSG91
            $cfg['template'],
            $cfg['namespace'],
            $bodyValues,
            $cfg['lang_code'],
            $cfg['lang_policy'],
        );

        try {
            $resp = $this->sendViaMsg91($payload, $cfg['auth']);
            $json = $resp->json();
            $body = $json ?: $resp->body();

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

            if (!$resp->successful()) {
                return $this->providerErrorResponse(
                    $resp->status(),
                    $body,
                    'Failed to send OTP.'
                );
            }

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
