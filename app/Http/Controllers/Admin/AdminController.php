<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Admin;
use App\Models\PratihariProfile;
use App\Models\PratihariFamily;
use App\Models\PratihariIdcard;
use App\Models\PratihariAddress;
use App\Models\PratihariSeba;
use App\Models\PratihariSocialMedia;
use App\Models\PratihariOccupation;
use App\Models\PratihariApplication;
use App\Models\PratihariSebaMaster;
use App\Models\DateBeddhaMapping;
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
    $today = Carbon::today();

    // ---- Profiles / Applications (as before) ----
    $todayProfiles = PratihariProfile::whereDate('created_at', $today)->get();

    $todayApprovedProfiles = PratihariProfile::whereDate('updated_at', $today)
        ->where('pratihari_status', 'approved')->get();

    $todayRejectedProfiles = PratihariProfile::whereDate('updated_at', $today)
        ->where('pratihari_status', 'rejected')->get();

    $incompleteProfiles = PratihariProfile::query()
        ->whereIn('pratihari_status', ['pending', 'rejected'])
        ->where(function ($q) {
            $q->whereNull('email')
              ->orWhereNull('phone_no')
              ->orWhereNull('blood_group')
              ->orWhereDoesntHave('family', function ($qq) {
                  $qq->whereNotNull('father_name')
                     ->whereNotNull('mother_name')
                     ->whereNotNull('maritial_status');
              })
              ->orWhereDoesntHave('children')
              ->orWhereDoesntHave('idcard', function ($qq) {
                  $qq->whereNotNull('id_type')
                     ->whereNotNull('id_number')
                     ->whereNotNull('id_photo');
              })
              ->orWhereDoesntHave('occupation', function ($qq) {
                  $qq->where(function ($qqq) {
                      $qqq->whereNotNull('occupation_type')
                          ->orWhereNotNull('extra_activity');
                  });
              })
              ->orWhereDoesntHave('address')
              ->orWhereDoesntHave('seba')
              ->orWhereDoesntHave('socialMedia');
        })
        ->get();

    $totalActiveUsers = PratihariProfile::where('status', 'active')
        ->where('pratihari_status', 'approved')->get();

    $updatedProfiles = PratihariProfile::where('status', 'active')
        ->where('pratihari_status', 'updated')->get();

    $pendingProfile = PratihariProfile::where('status', 'active')
        ->where('pratihari_status', 'pending')->get();

    $rejectedProfiles = PratihariProfile::where('pratihari_status', 'rejected')->get();

    $profiles = PratihariProfile::with(['occupation', 'address'])
        ->where('status', 'active')->get();

    $todayApplications = PratihariApplication::whereDate('created_at', $today)
        ->where('status', 'active')->get();

    $approvedApplication = PratihariApplication::where('status', 'approved')->get();
    $rejectedApplication = PratihariApplication::where('status', 'rejected')->get();

    // ---- Seba ID groups (unchanged utility) ----
    $pratihariSebaIds = PratihariSebaMaster::where('type', 'pratihari')->pluck('id');
    $gochhikarSebaIds = PratihariSebaMaster::where('type', 'gochhikar')->pluck('id');

    $pratihariIds = PratihariSeba::whereIn('seba_id', $pratihariSebaIds)->pluck('pratihari_id')->unique();
    $gochhikarIds = PratihariSeba::whereIn('seba_id', $gochhikarSebaIds)->pluck('pratihari_id')->unique();

    $profile_name   = PratihariProfile::whereIn('pratihari_id', $pratihariIds)->get();
    $gochhikar_name = PratihariProfile::whereIn('pratihari_id', $gochhikarIds)->get();

    // ---- Current user table presence (optional UI checklist) ----
    $user = Auth::user();
    $profileStatus = [];
    if ($user) {
        $pid = $user->pratihari_id;
        $profileStatus = [
            'profile'      => PratihariProfile::where('pratihari_id', $pid)->exists(),
            'family'       => PratihariFamily::where('pratihari_id', $pid)->exists(),
            'id_card'      => PratihariIdcard::where('pratihari_id', $pid)->exists(),
            'address'      => PratihariAddress::where('pratihari_id', $pid)->exists(),
            'seba'         => PratihariSeba::where('pratihari_id', $pid)->exists(),
            'social_media' => PratihariSocialMedia::where('pratihari_id', $pid)->exists(),
        ];
    }

    // ---- Today’s mapped beddha numbers ----
    $todayStr = $today->toDateString();
    $beddhaMapping   = DateBeddhaMapping::where('date', $todayStr)->first();

    // If mapping not present, fall back to "N/A"
    $pratihariBeddha = $beddhaMapping->pratihari_beddha ?? 'N/A';
    $gochhikarBeddha = $beddhaMapping->gochhikar_beddha ?? 'N/A';

    $todayPrBeddha = is_numeric($pratihariBeddha) ? (int)$pratihariBeddha : null;
    $todayGoBeddha = is_numeric($gochhikarBeddha) ? (int)$gochhikarBeddha : null;

    // ---- Build events filtered strictly by TODAY’s beddha number ----
    // Structure we’ll pass to Blade:
    // $pratihariEvents = [
    //   'SebaName | Beddha X' => [
    //      ['profile'=>PratihariProfile, 'beddha'=>X, 'assigned_by'=>'User'|'Admin']
    //   ], ...
    // ]
    // $nijogaAssign = same grouping but only for Admin (0) if you want to show in Nijoga tab.

    $pratihariEvents = [];
    $nijogaAssign    = [];

    if ($todayPrBeddha) {
        // Load all sebas of type 'pratihari' that include today’s beddha
        $sebas = PratihariSeba::with(['sebaMaster', 'pratihari', 'beddhaAssigns'])
            ->whereIn('seba_id', $pratihariSebaIds)
            ->get();

        foreach ($sebas as $seba) {
            $sebaName = $seba->sebaMaster?->seba_name ?? 'Unknown Seba';
            $beddhaIds = $seba->beddha_id; // accessor returns array

            if (!in_array($todayPrBeddha, $beddhaIds ?? [], true)) {
                continue;
            }

            $assign = $seba->beddhaAssigns->firstWhere('beddha_id', $todayPrBeddha);
            // 1 = user assign; 0 = admin assign; null = unknown/fallback admin
            $assignedBy = ($assign && (int)$assign->beddha_status === 1) ? 'User' : 'Admin';

            $label = "{$sebaName} | Beddha {$todayPrBeddha}";
            $entry = [
                'profile'      => $seba->pratihari,
                'beddha'       => $todayPrBeddha,
                'assigned_by'  => $assignedBy,
            ];

            if ($assignedBy === 'User') {
                $pratihariEvents[$label][] = $entry;
            } else {
                $nijogaAssign[$label][] = $entry;
            }
        }

        // Deduplicate by profile pratihari_id
        $pratihariEvents = collect($pratihariEvents)->map(function ($arr) {
            return collect($arr)->unique(fn($e) => $e['profile']?->pratihari_id)->values()->all();
        })->toArray();

        $nijogaAssign = collect($nijogaAssign)->map(function ($arr) {
            return collect($arr)->unique(fn($e) => $e['profile']?->pratihari_id)->values()->all();
        })->toArray();
    }

    // Displays (for the small “current beddha” chips elsewhere if needed)
    $currentPratihariBeddhaDisplay = $todayPrBeddha ? (string)$todayPrBeddha : '—';
    $currentGochhikarBeddhaDisplay = $todayGoBeddha ? (string)$todayGoBeddha : '—';

    return view('admin.admin-dashboard', compact(
        'todayProfiles',
        'incompleteProfiles',
        'totalActiveUsers',
        'rejectedProfiles',
        'updatedProfiles',
        'pendingProfile',
        'todayApplications',
        'profiles',
        'profile_name',
        'gochhikar_name',
        'todayApprovedProfiles',
        'todayRejectedProfiles',
        'approvedApplication',
        'rejectedApplication',
        'todayStr',
        'currentPratihariBeddhaDisplay',
        'currentGochhikarBeddhaDisplay',
        'nijogaAssign',
        'pratihariEvents',
        'pratihariBeddha',
        'gochhikarBeddha',
        'profileStatus'
    ));
}

    public function pratihariManageProfile()
    {

        return view('admin.pratihari-manage-profile', compact('profiles'));
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $inputPhone = trim($request->phone);

        // Validate & normalize recipient for India WhatsApp
        if (!$this->isValidIndianMobile($inputPhone)) {
            return redirect()->back()->with([
                'error' => 'Please enter a valid Indian mobile number (10 digits, starts with 6–9).',
            ])->withInput();
        }

        $toMsisdn = $this->normalizeMsisdn($inputPhone, '91'); // e.g., 917749968976

        $otp        = (string) random_int(100000, 999999);
        $shortToken = Str::upper(Str::random(6));

        // Check if admin exists by the number they typed (store "local" form in DB)
        $admin = Admin::where('mobile_no', $inputPhone)->first();
        if (!$admin) {
            return redirect()->back()->with([
                'error' => 'Mobile number not registered. Please contact super admin.'
            ])->withInput();
        }

        // Save OTP
        $admin->otp = $otp;
        $admin->save();

        // ENV config
        $authKey   = env('MSG91_AUTHKEY');
        $tplName   = env('MSG91_WA_TEMPLATE');
        $namespace = env('MSG91_WA_NAMESPACE');
        $waNumber  = env('MSG91_WA_NUMBER', '');

        // Normalize integrated (sender) number to digits-only with country code (no plus)
        $integratedNumber = $this->normalizeMsisdn((string) $waNumber, '91');

        // Build template
        $template = [
            'name'     => $tplName,
            'language' => ['code' => 'en', 'policy' => 'deterministic'],
        ];
        if (!empty($namespace)) {
            $template['namespace'] = $namespace;
        }

        $payload = [
            'integrated_number' => $integratedNumber,      // e.g., 919124420330
            'content_type'      => 'template',
            'payload'           => [
                'messaging_product' => 'whatsapp',
                'to'   => $toMsisdn,                       // e.g., 917749968976
                'type' => 'template',
                'template' => $template + [
                    'components' => [
                        [
                            'type'       => 'body',
                            'parameters' => [
                                [ 'type' => 'text', 'text' => $otp ],
                            ],
                        ],
                        [
                            'type'       => 'button',
                            'sub_type'   => 'url',
                            'index'      => '0',
                            'parameters' => [
                                [ 'type' => 'text', 'text' => $shortToken ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'authkey'      => $authKey,
            ])->post('https://api.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/', $payload);

            $result = $response->json() ?? [];
            \Log::info('MSG91 OTP Response', ['status' => $response->status(), 'body' => $result]);

            $ok = $response->status() === 200 && (($result['status'] ?? '') === 'success');

            if (!$ok) {
                // Common MSG91 error: "Outbound restricted due to blocked prefixes"
                $err = $result['errors'] ?? ($result['message'] ?? 'Unknown error');
                $errStr = is_string($err) ? $err : json_encode($err);

                // Provide a helpful hint for prefix issues
                if (stripos($errStr, 'blocked prefixes') !== false) {
                    $errStr .= ' — Ensure the recipient is in E.164 format with country code (e.g., 917XXXXXXXXX) and the number/operator prefix is allowed for your WhatsApp Business account. Also verify opt-in if required.';
                }

                return redirect()->back()->with([
                    'error' => 'Failed to send OTP. MSG91 error: ' . $errStr,
                ])->withInput();
            }

            return redirect()->back()->with([
                'otp_sent'  => true,
                'otp_phone' => $inputPhone,
                'message'   => 'OTP sent to your WhatsApp.',
            ]);

        } catch (\Throwable $e) {
            \Log::error('MSG91 OTP Exception: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return redirect()->back()->with([
                'error' => 'Server error. Please try again later.',
            ])->withInput();
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile_no' => 'required|string',
            'otp'       => 'required|string',
        ]);

        $admin = Admin::where('mobile_no', $request->mobile_no)->first();
        if (!$admin) {
            return back()->withErrors([
                'mobile_no' => 'Mobile number not found. Please contact super admin.',
            ])->withInput();
        }

        $sentOtp = trim((string) $request->otp);
        $dbOtp   = trim((string) ($admin->otp ?? ''));

        if ($dbOtp === '' || !hash_equals($dbOtp, $sentOtp)) {
            return back()->withErrors([
                'otp' => 'Invalid OTP. Please try again.',
            ])->withInput();
        }

        $admin->otp = null;

        if (empty($admin->admin_id)) {
            $admin->admin_id = 'ADMIN' . random_int(10000, 99999);
        }

        $admin->save();

        Auth::guard('admins')->login($admin);

        return redirect()->route('admin.dashboard')->with('success', 'OTP verified. You are logged in.');
    }

    private function normalizeMsisdn(string $raw, string $defaultCc = '91'): string
    {
        $digits = preg_replace('/\D+/', '', $raw ?? '');
        if (!$digits) return '';

        // If already starts with country code, keep; otherwise prepend default CC
        if (!Str::startsWith($digits, $defaultCc)) {
            // Drop leading zeros before appending country code
            $digits = ltrim($digits, '0');
            $digits = $defaultCc . $digits;
        }

        return $digits;
    }

    private function isValidIndianMobile(string $raw): bool
    {
        $d = preg_replace('/\D+/', '', $raw ?? '');
        // If it already has 91 prefixed, strip it for local validation
        if (Str::startsWith($d, '91')) {
            $d = substr($d, 2);
        }
        return (bool) preg_match('/^[6-9]\d{9}$/', $d);
    }

    public function logout()
    {
        Auth::guard('admins')->logout();

        return redirect()->route('admin.AdminLogin');
    }

    public function sebaCalendar()
    {
        // Get seba_ids by type
        $pratihariSebaIds = PratihariSebaMaster::where('type', 'pratihari')->pluck('id');
        $gochhikarSebaIds = PratihariSebaMaster::where('type', 'gochhikar')->pluck('id');

        // Get unique pratihari_ids
        $pratihariIds = PratihariSeba::whereIn('seba_id', $pratihariSebaIds)
            ->distinct()
            ->pluck('pratihari_id');

        $gochhikarIds = PratihariSeba::whereIn('seba_id', $gochhikarSebaIds)
            ->distinct()
            ->pluck('pratihari_id');

        // Fetch profile names
        $profile_name = PratihariProfile::whereIn('pratihari_id', $pratihariIds)->get();
        $gochhikar_name = PratihariProfile::whereIn('pratihari_id', $gochhikarIds)->get();

        return view('admin.seba-calendar', compact('profile_name', 'gochhikar_name'));
    }

    public function sebaDate(Request $request)
    {
        $pratihariId = $request->input('pratihari_id');
        $events = [];

        if ($pratihariId) {
            $sebas = PratihariSeba::with('sebaMaster')
                ->where('pratihari_id', $pratihariId)
                ->get();

            foreach ($sebas as $seba) {
                $sebaName = $seba->sebaMaster->seba_name ?? 'Unknown Seba';
                $sebaType = $seba->sebaMaster->type ?? null; // Fetch from master__seba.type
                $sebaId = $seba->seba_id;
                $beddhaIds = is_array($seba->beddha_id) ? $seba->beddha_id : explode(',', $seba->beddha_id);

                foreach ($beddhaIds as $beddhaId) {
                    $beddhaId = (int) trim($beddhaId);

                    if ($beddhaId < 1 || $beddhaId > 47) continue;

                    if ($sebaType === 'gochhikar') {
                        $intervalDays = 16;
                        $startDate = Carbon::create(2026, 1, 1)->addDays($beddhaId - 1);
                        $endDate = Carbon::create(2055, 12, 31);
                    } else {
                        $intervalDays = 47;
                        $startDate = Carbon::create(2025, 5, 22)->addDays($beddhaId - 1);
                        $endDate = Carbon::create(2050, 12, 31);
                    }

                    $nextDate = $startDate->copy();

                    while ($nextDate->lte($endDate)) {
                        $events[] = [
                            'title' => "$sebaName - Beddha $beddhaId",
                            'start' => $nextDate->toDateString(),
                            'extendedProps' => [
                                'sebaName' => $sebaName,
                                'beddhaId' => $beddhaId,
                                'sebaId' => $sebaId
                            ]
                        ];
                        $nextDate->addDays($intervalDays);
                    }
                }
            }
        }

        return response()->json($events);
    }

}
