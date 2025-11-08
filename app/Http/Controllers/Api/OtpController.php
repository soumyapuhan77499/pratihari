<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserDevice;
use Carbon\Carbon;

class OtpController extends Controller
{
    /**
     * Normalize a phone number to MSG91-style E.164 digits **without plus**.
     */
    private function normalizeMsisdn(?string $raw, string $defaultCountryCode = '91'): string
    {
        $digits = preg_replace('/\D+/', '', (string) $raw);
        if ($digits === '') return '';

        if (strlen($digits) === 10) {
            return $defaultCountryCode . $digits;
        }
        if (strlen($digits) === 11 && $digits[0] === '0') {
            return $defaultCountryCode . substr($digits, 1);
        }
        return $digits;
    }

    /**
     * Compute the parameter value for a dynamic URL button ({{1}}) in the WhatsApp template.
     * Modes (set in .env):
     *  - otp    : the OTP itself
     *  - mobile : the normalized msisdn
     *  - both   : "<msisdn>-<otp>"
     *  - custom : use MSG91_WA_BUTTON_PARAM_CUSTOM value
     */
    private function makeButtonParam(string $msisdn, string $otp): string
    {
        $mode = strtolower((string) env('MSG91_WA_BUTTON_PARAM_MODE', 'otp'));
        return match ($mode) {
            'mobile' => $msisdn,
            'both'   => $msisdn.'-'.$otp,
            'custom' => (string) env('MSG91_WA_BUTTON_PARAM_CUSTOM', $otp),
            default  => $otp,
        };
    }

    /**
     * (Optional) Build a full verify URL for your own use (e.g., show in body text or logs).
     * Not used by MSG91 unless your template has a text variable to place it.
     */
    private function makeVerifyUrl(string $msisdn, string $otp): string
    {
        $base = rtrim((string) env('APP_URL', 'https://example.com'), '/');
        return $base.'/verify-otp?m='.$msisdn.'&otp='.$otp;
    }

    /**
     * Build MSG91 WhatsApp Template payload.
     * If MSG91_WA_URL_BUTTON=on, includes `button_1` with the required `value` param.
     * Your approved template must have a dynamic URL button (e.g., https://.../{{1}}) to use this.
     */
    private function buildMsg91Payload(string $toMsisdn, string $otp): array
    {
        $integrated = preg_replace('/\D+/', '', (string) env('MSG91_WA_NUMBER', '')); // digits only
        $template   = (string) env('MSG91_WA_TEMPLATE', '');
        $namespace  = (string) env('MSG91_WA_NAMESPACE', '');

        // Body variables — adjust to match your template (add body_2, header_1 etc. if needed).
        $components = [
            "body_1" => [
                "type"  => "text",
                "value" => $otp, // show OTP in body
            ],
        ];

        // Include the URL button parameter ONLY if your template expects it.
        if (strtolower((string) env('MSG91_WA_URL_BUTTON', 'off')) === 'on') {
            $components["button_1"] = [
                "subtype" => "url",
                "type"    => "text",
                // IMPORTANT: for a dynamic URL button, MSG91 expects the **parameter only**,
                // which replaces {{1}} in the approved URL.
                "value"   => $this->makeButtonParam($toMsisdn, $otp),
            ];
        }

        return [
            "integrated_number" => $integrated, // e.g., 919124420330 (no +)
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
                        "to"         => [$toMsisdn],
                        "components" => $components
                    ]],
                ],
            ],
        ];
    }

    // ===================== SEND OTP =====================
    public function sendOtp(Request $request)
    {
        // accept mobile or mobile_number
        $request->merge([
            'mobile_number' => $request->input('mobile_number') ?? $request->input('mobile_number')
        ]);

        $v = Validator::make($request->all(), [
            'mobile_number'    => 'required|string',
            'device_id' => 'nullable|string|max:255',
        ]);

        if ($v->fails()) {
            return response()->json(['message' => 'Invalid input', 'errors' => $v->errors()], 422);
        }

        $msisdn = $this->normalizeMsisdn($request->input('mobile_number'), '91');
        if ($msisdn === '' || strlen($msisdn) < 12) {
            return response()->json(['message' => 'Invalid mobile number'], 422);
        }

        // Find or create user by mobile_number
        $user = User::where('mobile_number', $msisdn)->first();
        if (!$user) {
            $user = new User();
            $user->mobile_number = $msisdn;
            $user->pratihari_id  = $user->pratihari_id ?: ('PRATIHARI'.str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT));
        }

        $otpLength = (int) env('OTP_LENGTH', 6);
        $otp       = str_pad((string) random_int(0, (10 ** $otpLength) - 1), $otpLength, '0', STR_PAD_LEFT);
        $ttlMin    = (int) env('OTP_TTL_MINUTES', 10);

        $user->otp     = $otp;
        $user->expiry  = Carbon::now()->addMinutes($ttlMin);
        $user->channel = 'whatsapp';
        $user->save();

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

            Log::info('MSG91 WA sendOtp response', [
                'to'     => $msisdn,
                'status' => $resp->status(),
                'ok'     => $resp->successful(),
            ]);

            if (!$resp->successful()) {
                return response()->json([
                    'message' => 'Failed to send OTP via WhatsApp',
                    'status'  => $resp->status(),
                    'error'   => $resp->json() ?: $resp->body(),
                    'hint'    => 'If the error is "Button at index 0 of type Url requires a parameter", set MSG91_WA_URL_BUTTON=on and ensure your template has a dynamic URL like https://.../{{1}}.',
                ], 502);
            }

            return response()->json([
                'message'       => 'OTP sent successfully via WhatsApp',
                'expires_in'    => $ttlMin * 60,
                'mobile_number' => $msisdn,
                'verify_url'    => $this->makeVerifyUrl($msisdn, $otp), // optional, for your client use
            ], 200);
        } catch (\Throwable $e) {
            Log::error('MSG91 WA sendOtp exception: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Could not send OTP at the moment'], 500);
        }
    }

    // ===================== VERIFY OTP =====================
    public function verifyOtp(Request $request)
    {
        $request->merge([
            'mobile_number' => $request->input('mobile_number') ?? $request->input('mobile_number')
        ]);

        $v = Validator::make($request->all(), [
            'mobile_number'    => 'required|string',
            'otp'       => 'required|string',
            'device_id' => 'nullable|string|max:255',
        ]);

        if ($v->fails()) {
            return response()->json(['message' => 'Invalid input', 'errors' => $v->errors()], 422);
        }

        $msisdn = $this->normalizeMsisdn($request->input('mobile_number'), '91');
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

        $token = $user->createToken('mobile-login')->plainTextToken;

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
}
