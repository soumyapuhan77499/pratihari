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
use Illuminate\Support\Facades\Schema;   // <-- add this

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
    $today     = Carbon::today();
    $todayStr  = $today->toDateString();

    // ---------- Profiles / Applications ----------

    $todayProfiles = PratihariProfile::whereDate('created_at', $today)->get();

    $todayApprovedProfiles = PratihariProfile::whereDate('updated_at', $today)
        ->where('pratihari_status', 'approved')->get();

    $todayRejectedProfiles = PratihariProfile::whereDate('updated_at', $today)
        ->where('pratihari_status', 'rejected')->get();

    /**
     * FIX FOR COLLATION ERROR:
     * Instead of a huge whereDoesntHave() chain (which triggers collation conflicts
     * when comparing pratihari_id across tables), we:
     *   1) Load pending + rejected profiles with all needed relations.
     *   2) Detect "incomplete" in PHP.
     */
    $candidateProfiles = PratihariProfile::with([
            'family',
            'children',
            'idcard',
            'occupation',
            'address',
            'seba',
            'socialMedia',
        ])
        ->whereIn('pratihari_status', ['pending', 'rejected'])
        ->get();

    $incompleteProfiles = $candidateProfiles->filter(function ($p) {
        // Basic fields
        if (empty($p->email) || empty($p->phone_no) || empty($p->blood_group)) {
            return true;
        }

        // Family block
        $family = $p->family;
        if (
            !$family ||
            empty($family->father_name) ||
            empty($family->mother_name) ||
            empty($family->maritial_status)
        ) {
            return true;
        }

        // Children
        if (!$p->children || $p->children->count() === 0) {
            return true;
        }

        // Id card
        $idcard = $p->idcard;
        if (
            !$idcard ||
            empty($idcard->id_type) ||
            empty($idcard->id_number) ||
            empty($idcard->id_photo)
        ) {
            return true;
        }

        // Occupation
        $occupation = $p->occupation;
        if (
            !$occupation ||
            (empty($occupation->occupation_type) && empty($occupation->extra_activity))
        ) {
            return true;
        }

        // Address
        if (!$p->address || $p->address->count() === 0) {
            return true;
        }

        // Seba
        if (!$p->seba || $p->seba->count() === 0) {
            return true;
        }

        // Social media
        if (!$p->socialMedia || $p->socialMedia->count() === 0) {
            return true;
        }

        // If all checks passed, profile is "complete"
        return false;
    })->values();

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

    // ---------- Seba master groups ----------
    $pratihariSebaIds = PratihariSebaMaster::where('type', 'pratihari')->pluck('id');
    $gochhikarSebaIds = PratihariSebaMaster::where('type', 'gochhikar')->pluck('id');

    $pratihariIds = PratihariSeba::whereIn('seba_id', $pratihariSebaIds)->pluck('pratihari_id')->unique();
    $gochhikarIds = PratihariSeba::whereIn('seba_id', $gochhikarSebaIds)->pluck('pratihari_id')->unique();

    $profile_name   = PratihariProfile::whereIn('pratihari_id', $pratihariIds)->get();
    $gochhikar_name = PratihariProfile::whereIn('pratihari_id', $gochhikarIds)->get();

    // ---------- Current user table presence (optional UI checklist) ----------
    $profileStatus = [];
    if (Auth::check()) {
        $pid = Auth::user()->pratihari_id;
        $profileStatus = [
            'profile'      => PratihariProfile::where('pratihari_id', $pid)->where('pratihari_status','approved')->exists(),
            'family'       => PratihariFamily::where('pratihari_id', $pid)->exists(),
            'id_card'      => PratihariIdcard::where('pratihari_id', $pid)->exists(),
            'address'      => PratihariAddress::where('pratihari_id', $pid)->exists(),
            'seba'         => PratihariSeba::where('pratihari_id', $pid)->exists(),
            'social_media' => PratihariSocialMedia::where('pratihari_id', $pid)->exists(),
        ];
    }

    // ---------- Today’s mapped beddha numbers ----------
    $beddhaMapping   = DateBeddhaMapping::where('date', $todayStr)->first();
    $pratihariBeddha = $beddhaMapping->pratihari_beddha ?? 'N/A';
    $gochhikarBeddha = $beddhaMapping->gochhikar_beddha ?? 'N/A';

    $todayPrBeddha = is_numeric($pratihariBeddha) ? (int) $pratihariBeddha : null;
    $todayGoBeddha = is_numeric($gochhikarBeddha) ? (int) $gochhikarBeddha : null;

    // ---------- LEFT PANEL: PRATIHARI ----------
    $pratihariEvents = [];
    $nijogaAssign    = [];

    if ($todayPrBeddha) {
        $sebas = PratihariSeba::with(['sebaMaster', 'pratihari', 'beddhaAssigns'])
            ->whereIn('seba_id', $pratihariSebaIds)
            ->where('status', 'active')
            ->get();

        foreach ($sebas as $seba) {
            // Thanks to model accessor, this is an array of ints
            $beddhaIds = collect($seba->beddha_id)->map(fn($v) => (int) $v)->all();
            if (!in_array($todayPrBeddha, $beddhaIds)) continue;

            $sebaName   = $seba->sebaMaster?->seba_name ?? 'Unknown Seba';
            $assign     = $seba->beddhaAssigns->firstWhere('beddha_id', $todayPrBeddha);
            $assignedBy = ($assign && (int) $assign->beddha_status === 1) ? 'User' : 'Admin';

            $label = "{$sebaName} | Beddha {$todayPrBeddha}";
            $entry = [
                'profile'     => $seba->pratihari,
                'beddha'      => $todayPrBeddha,
                'assigned_by' => $assignedBy,
            ];

            if ($assignedBy === 'User') {
                $pratihariEvents[$label][] = $entry;
            } else {
                $nijogaAssign[$label][]    = $entry;
            }
        }

        // Deduplicate by profile id
        $pratihariEvents = collect($pratihariEvents)->map(fn($arr) =>
            collect($arr)->unique(fn($e) => $e['profile']?->pratihari_id)->values()->all()
        )->toArray();

        $nijogaAssign = collect($nijogaAssign)->map(fn($arr) =>
            collect($arr)->unique(fn($e) => $e['profile']?->pratihari_id)->values()->all()
        )->toArray();
    }

    // ---------- RIGHT PANEL: GOCHHIKAR ----------
    $gochhikarEvents = [];
    $nijogaGochhikarEvents = [];

    if ($todayGoBeddha) {
        $gsebas = PratihariSeba::with(['sebaMaster', 'pratihari', 'beddhaAssigns'])
            ->whereIn('seba_id', $gochhikarSebaIds)
            ->where('status', 'active')
            ->get();

        foreach ($gsebas as $seba) {
            $ids = collect($seba->beddha_id)->map(fn($v) => (int) $v)->all();
            if (!in_array($todayGoBeddha, $ids)) continue;

            $sebaName = $seba->sebaMaster?->seba_name ?? 'Unknown Seba';
            $assign   = $seba->beddhaAssigns->firstWhere('beddha_id', $todayGoBeddha);
            $label    = "{$sebaName} | Beddha {$todayGoBeddha}";

            if ($assign && (int) $assign->beddha_status === 1) {
                $gochhikarEvents[$label][] = $seba->pratihari;
            } else {
                $nijogaGochhikarEvents[$label][] = $seba->pratihari;
            }
        }

        $gochhikarEvents = collect($gochhikarEvents)->map(fn($arr) =>
            collect($arr)->unique('pratihari_id')->values()->all()
        )->toArray();

        $nijogaGochhikarEvents = collect($nijogaGochhikarEvents)->map(fn($arr) =>
            collect($arr)->unique('pratihari_id')->values()->all()
        )->toArray();
    }

    // ---------- Display chips ----------
    $currentPratihariBeddhaDisplay = $todayPrBeddha ? (string) $todayPrBeddha : '—';
    $currentGochhikarBeddhaDisplay = $todayGoBeddha ? (string) $todayGoBeddha : '—';

    // ---------- Return view ----------
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
        'gochhikarEvents',
        'nijogaGochhikarEvents',
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

    public function sebaCalendar(Request $request)
    {
        // Get seba_ids by type
        $pratihariSebaIds = PratihariSebaMaster::where('type', 'pratihari')->pluck('id');
        $gochhikarSebaIds = PratihariSebaMaster::where('type', 'gochhikar')->pluck('id');

        // Get unique pratihari_ids (only active if column exists)
        $pratihariIds = PratihariSeba::whereIn('seba_id', $pratihariSebaIds)
            ->when(
                Schema::hasColumn('pratihari__seba_details', 'status'),
                fn ($q) => $q->where('status', 'active')
            )
            ->distinct()
            ->pluck('pratihari_id');

        $gochhikarIds = PratihariSeba::whereIn('seba_id', $gochhikarSebaIds)
            ->when(
                Schema::hasColumn('pratihari__seba_details', 'status'),
                fn ($q) => $q->where('status', 'active')
            )
            ->distinct()
            ->pluck('pratihari_id');

        // Fetch profile names (sorted & only approved)
        $profile_name = PratihariProfile::whereIn('pratihari_id', $pratihariIds)
            ->where('pratihari_status', 'approved')
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('last_name')
            ->get();

        $gochhikar_name = PratihariProfile::whereIn('pratihari_id', $gochhikarIds)
            ->where('pratihari_status', 'approved')
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('last_name')
            ->get();

        // Optional: normalize inverted date range in query
        $from = $request->query('from');
        $to   = $request->query('to');
        if ($from && $to && $from > $to) {
            // swap and redirect
            return redirect()->to(
                $request->fullUrlWithQuery(['from' => $to, 'to' => $from])
            );
        }

        return view('admin.seba-calendar', compact('profile_name', 'gochhikar_name'));
    }


   public function sebaDate(Request $request)
{
    $pratihariId = $request->input('pratihari_id');
    $gochhikarId = $request->input('gochhikar_id');

    // Date range (optional)
    $from = $request->input('from'); // YYYY-MM-DD
    $to   = $request->input('to');   // YYYY-MM-DD

    // Normalize & guard
    $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
    $toDate   = $to   ? Carbon::parse($to)->endOfDay()   : null;

    if ($fromDate && $toDate && $fromDate->gt($toDate)) {
        // swap if inverted
        [$fromDate, $toDate] = [$toDate, $fromDate];
    }

    // Build base query: always load sebaMaster + pratihari (for name)
    $sebasQuery = PratihariSeba::with(['sebaMaster', 'pratihari']);

    // Only active rows if status column exists
    if (Schema::hasColumn('pratihari__seba_details', 'status')) {
        $sebasQuery->where('status', 'active');
    }

    /**
     * Filter by identity if provided
     * - pratihari_id => only sebā whose master.type = 'pratihari'
     * - gochhikar_id => only sebā whose master.type = 'gochhikar'
     * If neither is provided => all sebā (for global date range search).
     */
    if ($pratihariId) {
        $sebasQuery
            ->where('pratihari_id', $pratihariId)
            ->whereHas('sebaMaster', function ($q) {
                $q->where('type', 'pratihari');
            });
    }

    if ($gochhikarId) {
        $sebasQuery
            ->where('pratihari_id', $gochhikarId)
            ->whereHas('sebaMaster', function ($q) {
                $q->where('type', 'gochhikar');
            });
    }

    $sebas = $sebasQuery->get();

    $events = [];

    foreach ($sebas as $seba) {
        $sebaName = $seba->sebaMaster->seba_name ?? 'Unknown Seba';
        $sebaType = $seba->sebaMaster->type ?? null; // 'pratihari' | 'gochhikar'
        $sebaId   = $seba->seba_id;

        // Pratihari full name for modal
        $profile  = $seba->pratihari;
        $fullName = $profile
            ? trim(($profile->first_name ?? '') . ' ' . ($profile->middle_name ?? '') . ' ' . ($profile->last_name ?? ''))
            : '';

        // Accessor already returns array; just normalize
        $beddhaIds = is_array($seba->beddha_id)
            ? $seba->beddha_id
            : array_filter(array_map('trim', explode(',', (string) $seba->beddha_id)));

        foreach ($beddhaIds as $rawBeddhaId) {
            $beddhaId = (int) $rawBeddhaId;
            if ($beddhaId < 1 || $beddhaId > 47) {
                continue;
            }

            // ======= PATTERN LOGIC (unchanged, but now bounded by date range) =======

            if ($sebaType === 'gochhikar') {
                $intervalDays = 16;
                $seedStart    = Carbon::create(2026, 1, 1)->addDays($beddhaId - 1);
                $seedEnd      = Carbon::create(2055, 12, 31)->endOfDay();
            } else { // default to 'pratihari' pattern
                $intervalDays = 47;
                $seedStart    = Carbon::create(2025, 5, 22)->addDays($beddhaId - 1);
                $seedEnd      = Carbon::create(2050, 12, 31)->endOfDay();
            }

            // Cap end by global "to" (if provided)
            $hardEnd = $toDate ? min($seedEnd, $toDate->copy()) : $seedEnd;

            // If we have a fromDate after seedStart, jump forward to the first occurrence >= fromDate
            if ($fromDate && $fromDate->gt($seedStart)) {
                $daysDiff = $seedStart->diffInDays($fromDate, false); // negative if from < seedStart
                $skip     = (int) ceil(max(0, $daysDiff) / $intervalDays);
                $nextDate = $seedStart->copy()->addDays($skip * $intervalDays);
            } else {
                $nextDate = $seedStart->copy();
            }

            // Generate all occurrences within [fromDate..hardEnd]
            while ($nextDate->lte($hardEnd)) {
                // Respect lower bound if present
                if (!$fromDate || $nextDate->gte($fromDate)) {
                    $events[] = [
                        'title' => ($fullName ? $fullName . ' - ' : '') . "{$sebaName} (Beddha {$beddhaId})",
                        'start' => $nextDate->toDateString(),
                        'allDay' => true,
                        'extendedProps' => [
                            'sebaName'      => $sebaName,
                            'beddhaId'      => $beddhaId,
                            'sebaId'        => $sebaId,
                            'sebaType'      => $sebaType,
                            'pratihariName' => $fullName,
                        ],
                    ];
                }

                $nextDate->addDays($intervalDays);
            }
        }
    }

    return response()->json($events);
}

}
