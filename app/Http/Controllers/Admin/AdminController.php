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

    // public function dashboard()
    // {

    //     $todayProfiles = PratihariProfile::whereDate('created_at', Carbon::today())->get();

    //     $todayApprovedProfiles = PratihariProfile::whereDate('updated_at', Carbon::today())->where('pratihari_status', 'approved')->get();

    //     $todayRejectedProfiles = PratihariProfile::whereDate('updated_at', Carbon::today())->where('pratihari_status', 'rejected')->get();

    //     $incompleteProfiles = PratihariProfile::where('pratihari_status',['pending','rejected'])->where(function ($query) {
    //         $query->whereNull('email')
    //             ->orWhereNull('phone_no')
    //             ->orWhereNull('blood_group');
    //     })
    //     // OR profiles with missing family info
    //     ->orWhereDoesntHave('family', function ($query) {
    //         $query->whereNotNull('father_name')
    //             ->whereNotNull('mother_name')
    //             ->whereNotNull('maritial_status'); // add more checks if needed
    //     })
    //     // OR profiles with no children records
    //     ->orWhereDoesntHave('children')
    //     // OR profiles with missing id card details
    //     ->orWhereDoesntHave('idcard', function ($query) {
    //         $query->whereNotNull('id_type')
    //             ->whereNotNull('id_number')
    //             ->whereNotNull('id_photo');
    //     })
    //     // OR profiles with missing occupation details
    //     ->orWhereDoesntHave('occupation', function ($query) {
    //         $query->where(function ($q) {
    //             $q->whereNotNull('occupation_type')
    //               ->orWhereNotNull('extra_activity');
    //         });
    //     })
    //     // OR profiles with missing address
    //     ->orWhereDoesntHave('address')
    //     // OR profiles with missing seba details
    //     ->orWhereDoesntHave('seba')
    //     // OR profiles with missing social media
    //     ->orWhereDoesntHave('socialMedia')
    //     ->get();

    //     $totalActiveUsers = PratihariProfile::where('status', 'active')->where('pratihari_status', 'approved')->get();

    //     $updatedProfiles = PratihariProfile::where('status', 'active')->where('pratihari_status', 'updated')->get();

    //     $pendingProfile = PratihariProfile::where('status', 'active')->where('pratihari_status', 'pending')->get();

    //     $rejectedProfiles = PratihariProfile::where('pratihari_status', 'rejected')->get();

    //     $profiles = PratihariProfile::with(['occupation', 'address'])->where('status','active')->get();

    //     $todayApplications = PratihariApplication::whereDate('created_at', Carbon::today())
    //     ->where('status', 'active')
    //     ->get();

    //     $approvedApplication = PratihariApplication::where('status', 'approved')->get();

    //     $rejectedApplication = PratihariApplication::where('status', 'rejected')->get();

    //     // Fetch seba IDs based on type from master__seba table
    //     $pratihariSebaIds = PratihariSebaMaster::where('type', 'pratihari')->pluck('id');
    //     $gochhikarSebaIds = PratihariSebaMaster::where('type', 'gochhikar')->pluck('id');

    //     // Get all unique Pratihari IDs linked to pratihari type
    //     $pratihariIds = PratihariSeba::whereIn('seba_id', $pratihariSebaIds)
    //         ->pluck('pratihari_id')
    //         ->unique();

    //     // Get all unique Pratihari IDs linked to gochhikar type
    //     $gochhikarIds = PratihariSeba::whereIn('seba_id', $gochhikarSebaIds)
    //         ->pluck('pratihari_id')
    //         ->unique();

    //     // Fetch profile details for each group
    //     $profile_name = PratihariProfile::whereIn('pratihari_id', $pratihariIds)->get();
    //     $gochhikar_name = PratihariProfile::whereIn('pratihari_id', $gochhikarIds)->get();

    //     $user = Auth::user();

    //     $profileStatus = [];
    //     if ($user) {
    //         $pratihari_id = $user->pratihari_id;
    //         $tables = [
    //             'profile' => PratihariProfile::where('pratihari_id', $pratihari_id)->exists(),
    //             'family' => PratihariFamily::where('pratihari_id', $pratihari_id)->exists(),
    //             'id_card' => PratihariIdcard::where('pratihari_id', $pratihari_id)->exists(),
    //             'address' => PratihariAddress::where('pratihari_id', $pratihari_id)->exists(),
    //             'seba' => PratihariSeba::where('pratihari_id', $pratihari_id)->exists(),
    //             'social_media' => PratihariSocialMedia::where('pratihari_id', $pratihari_id)->exists(),
    //         ];
    //     }

    //     $today = Carbon::today();
    //     $baseDatePratihari = Carbon::create(2025, 7, 1);
    //     $endDatePratihari = Carbon::create(2050, 12, 31);

    //     $baseDateGochhikar = Carbon::create(2025, 7, 1);
    //     $endDateGochhikar = Carbon::create(2055, 12, 31);

    //     $today = Carbon::today();

    //     $pratihariEvents = [];
    //     $nijogaAssign = [];

    //     $gochhikarEvents = [];
    //     $nijogaGochhikarEvents = [];

    //     $todayPratihariBeddhaIds = [];
    //     $todayGochhikarBeddhaIds = [];

    //     $sebas = PratihariSeba::with(['sebaMaster', 'pratihari', 'beddhaAssigns'])->get();

    //     foreach ($sebas as $seba) {
    //         $sebaType = $seba->type;
    //         $sebaName = $seba->sebaMaster->seba_name ?? 'Unknown Seba';
    //         $beddhaIds = is_array($seba->beddha_id) ? $seba->beddha_id : explode(',', $seba->beddha_id);

    //         foreach ($beddhaIds as $beddhaId) {
    //             $beddhaId = (int) trim($beddhaId);
    //             if ($beddhaId < 1 || $beddhaId > 47) continue;

    //             $beddhaStatus = $seba->beddhaAssigns->where('beddha_id', $beddhaId)->first()->beddha_status ?? null;
    //             if ($beddhaStatus === null) continue;

    //             $user = $seba->pratihari;
    //             $interval = ($sebaType = "gochhikar") ? 16 : 47;

    //             if ($sebaId = "gochhikar") {
    //                 $start = $baseDateGochhikar->copy()->addDays($beddhaId - 1);
    //                 while ($start->lte($endDateGochhikar)) {
    //                     if ($start->equalTo($today)) {
    //                         $label = "$sebaName | Beddha $beddhaId";
    //                         if ($user) {
    //                             if ($beddhaStatus == 1) {
    //                                 $gochhikarEvents[$label][] = $user;
    //                             } else {
    //                                 $nijogaGochhikarEvents[$label][] = $user;
    //                             }
    //                             $todayGochhikarBeddhaIds[] = $beddhaId;
    //                         }
    //                         break;
    //                     }
    //                     $start->addDays($interval);
    //                 }
    //             }

    //             else {
    //                 $start = $baseDatePratihari->copy()->addDays($beddhaId - 1);
    //                 while ($start->lte($endDatePratihari)) {
    //                     if ($start->equalTo($today)) {
    //                         $label = "$sebaName | Beddha $beddhaId";
    //                         if ($user) {
    //                             if ($beddhaStatus == 1) {
    //                                 $pratihariEvents[$label][] = $user;
    //                             } else {
    //                                 $nijogaAssign[$label][] = $user;
    //                             }
    //                             $todayPratihariBeddhaIds[] = $beddhaId;
    //                         }
    //                         break;
    //                     }
    //                     $start->addDays($interval);
    //                 }
    //             }

    //         }
    //     }

    //     // Deduplicate users
    //     $pratihariEvents = collect($pratihariEvents)->map(fn($u) => collect($u)->unique('pratihari_id')->values()->all())->toArray();
    //     $nijogaAssign = collect($nijogaAssign)->map(fn($u) => collect($u)->unique('pratihari_id')->values()->all())->toArray();
    //     $gochhikarEvents = collect($gochhikarEvents)->map(fn($u) => collect($u)->unique('pratihari_id')->values()->all())->toArray();
    //     $nijogaGochhikarEvents = collect($nijogaGochhikarEvents)->map(fn($u) => collect($u)->unique('pratihari_id')->values()->all())->toArray();

    //     // Separate beddha display
    //     $currentPratihariBeddhaDisplay = implode(', ', array_unique($todayPratihariBeddhaIds));
    //     $currentGochhikarBeddhaDisplay = implode(', ', array_unique($todayGochhikarBeddhaIds));

    //      $today = Carbon::today()->toDateString(); // e.g., '2025-07-18'

    //     $beddhaMapping = DateBeddhaMapping::where('date', $today)->first();

    //     $pratihariBeddha = $beddhaMapping->pratihari_beddha ?? 'N/A';
    //     $gochhikarBeddha = $beddhaMapping->gochhikar_beddha ?? 'N/A';

    //         return view('admin.admin-dashboard', compact(
    //             'todayProfiles',
    //             'incompleteProfiles',
    //             'totalActiveUsers',
    //             'rejectedProfiles',
    //             'updatedProfiles',
    //             'pendingProfile',
    //             'todayApplications',
    //             'profiles',
    //             'profile_name',
    //             'gochhikar_name',
    //             'todayApprovedProfiles',
    //             'todayRejectedProfiles',
    //             'approvedApplication',
    //             'rejectedApplication',
    //             'today',
    //             'currentPratihariBeddhaDisplay',
    //             'currentGochhikarBeddhaDisplay',
    //             'nijogaAssign',
    //             'pratihariEvents',
    //             'gochhikarEvents',
    //             'nijogaGochhikarEvents',
    //             'pratihariBeddha',
    //             'gochhikarBeddha',
    //         ));
    // }

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
                'profile'      => PratihariProfile::where('pratihari_id', $pid)->exists(),
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

        $todayPrBeddha = is_numeric($pratihariBeddha) ? (int)$pratihariBeddha : null;
        $todayGoBeddha = is_numeric($gochhikarBeddha) ? (int)$gochhikarBeddha : null;

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
                $beddhaIds = collect($seba->beddha_id)->map(fn($v) => (int)$v)->all();
                if (!in_array($todayPrBeddha, $beddhaIds)) continue;

                $sebaName   = $seba->sebaMaster?->seba_name ?? 'Unknown Seba';
                $assign     = $seba->beddhaAssigns->firstWhere('beddha_id', $todayPrBeddha);
                $assignedBy = ($assign && (int)$assign->beddha_status === 1) ? 'User' : 'Admin';

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
                $ids = collect($seba->beddha_id)->map(fn($v) => (int)$v)->all();
                if (!in_array($todayGoBeddha, $ids)) continue;

                $sebaName = $seba->sebaMaster?->seba_name ?? 'Unknown Seba';
                $assign   = $seba->beddhaAssigns->firstWhere('beddha_id', $todayGoBeddha);
                $label    = "{$sebaName} | Beddha {$todayGoBeddha}";

                if ($assign && (int)$assign->beddha_status === 1) {
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
        $currentPratihariBeddhaDisplay = $todayPrBeddha ? (string)$todayPrBeddha : '—';
        $currentGochhikarBeddhaDisplay = $todayGoBeddha ? (string)$todayGoBeddha : '—';

        // ---------- Return view (EVERYTHING DEFINED) ----------
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
 private function isValidIndianMobile(?string $raw): bool
    {
        if (!$raw) return false;
        // Accept with/without +91, spaces, dashes, etc.; validate final 10 digits start 6-9
        $digits = preg_replace('/\D+/', '', $raw);
        if (Str::startsWith($digits, '91') && strlen($digits) === 12) {
            $digits = substr($digits, -10);
        }
        return (bool) preg_match('/^[6-9]\d{9}$/', $digits);
    }

    private function normalizeMsisdn(?string $raw, string $cc = '91'): string
    {
        // Returns countrycode+number, digits only, no plus. E.g. "+91 77499 68976" -> "917749968976"
        $digits = preg_replace('/\D+/', '', (string) $raw);
        if ($digits === '') return '';
        // Strip leading zeros
        $digits = ltrim($digits, '0');

        // If already starts with country code and length 12 for India, keep
        if (Str::startsWith($digits, $cc)) {
            return $digits;
        }

        // If it looks like a local 10-digit mobile, prepend country code
        if (strlen($digits) === 10) {
            return $cc . $digits;
        }

        // Fallback: if someone passed +9177.. or 0091.. we already stripped non-digits; just ensure it starts with cc
        if (!Str::startsWith($digits, $cc)) {
            return $cc . $digits;
        }

        return $digits;
    }

    private function tenDigitLocal(string $raw): string
    {
        // Extract the canonical 10-digit local mobile to match DB storage like "7749968976"
        $digits = preg_replace('/\D+/', '', $raw);
        if (Str::startsWith($digits, '91') && strlen($digits) >= 12) {
            $digits = substr($digits, -10);
        }
        return substr($digits, -10);
    }

    public function sendOtp(Request $request)
    {
        // 1) Validate input early
        $request->validate([
            'phone' => ['required', 'string'],
        ]);

        $inputPhone = trim($request->phone);

        if (!$this->isValidIndianMobile($inputPhone)) {
            return back()->with([
                'error' => 'Please enter a valid Indian mobile number (10 digits, starts with 6–9).',
            ])->withInput();
        }

        // 2) Canonicalize numbers
        $toMsisdn = $this->normalizeMsisdn($inputPhone, '91'); // digits-only E.164 without '+', e.g. 917749968976
        $local10  = $this->tenDigitLocal($inputPhone);        // e.g. 7749968976

        // 3) Find Admin by how you actually store it (most apps store 10-digit local)
        $admin = Admin::where('mobile_no', $local10)->first();
        if (!$admin) {
            // If you store with +91, try that as a fallback
            $maybePlus = '+91' . $local10;
            $admin = Admin::where('mobile_no', $maybePlus)->first();
        }

        if (!$admin) {
            return back()->with([
                'error' => 'Mobile number not registered. Please contact super admin.'
            ])->withInput();
        }

        // 4) Prepare OTP + expiry
        $otp        = (string) random_int(100000, 999999);
        $shortToken = Str::upper(Str::random(6));
        $admin->otp = $otp;

        // Optional: add expiry (5 minutes)
        if (schemaHasColumn('admins', 'otp_expires_at')) {
            $admin->otp_expires_at = Carbon::now()->addMinutes(5);
        }

        $admin->save();

        // 5) ENV guardrails
        $authKey   = (string) env('MSG91_AUTHKEY', '');
        $tplName   = (string) env('MSG91_WA_TEMPLATE', '');
        $namespace = (string) env('MSG91_WA_NAMESPACE', '');
        $waNumber  = (string) env('MSG91_WA_NUMBER', ''); // your integrated WA business number

        if ($authKey === '' || $tplName === '' || $waNumber === '') {
            Log::warning('MSG91 config missing', compact('authKey', 'tplName', 'waNumber', 'namespace'));
            return back()->with([
                'error' => 'OTP service is not configured. Please contact support.',
            ]);
        }

        $integratedNumber = $this->normalizeMsisdn($waNumber, '91'); // e.g. 919124420330

        // 6) Build WhatsApp template payload (MSG91 WA v5)
        $template = [
            'name'     => $tplName,
            'language' => ['code' => 'en', 'policy' => 'deterministic'],
        ];
        if ($namespace !== '') {
            $template['namespace'] = $namespace;
        }

        $payload = [
            'integrated_number' => $integratedNumber,
            'content_type'      => 'template',
            'payload'           => [
                'messaging_product' => 'whatsapp',
                'to'                => $toMsisdn,
                'type'              => 'template',
                'template'          => $template + [
                    'components' => [
                        [
                            'type'       => 'body',
                            'parameters' => [
                                ['type' => 'text', 'text' => $otp],
                            ],
                        ],
                        [
                            'type'       => 'button',
                            'sub_type'   => 'url',
                            'index'      => '0',
                            'parameters' => [
                                ['type' => 'text', 'text' => $shortToken],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        // 7) Call MSG91 and handle *all* common shapes of success/error
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'authkey'      => $authKey,
            ])->post('https://api.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/', $payload);

            $status = $response->status();
            $body   = $response->json();

            // Log everything for diagnosis (safe: no OTP in logs)
            Log::info('MSG91 OTP Response', [
                'http_status' => $status,
                'response'    => $body,
                'to'          => $toMsisdn,
                'integrated'  => $integratedNumber,
                'template'    => $tplName,
            ]);

            // MSG91 sometimes returns 200 or 202 with various "status"/"type"/"message"
            $apiOk = false;
            if ($response->successful() || $status === 202) {
                $apiStatus = data_get($body, 'status') ?: data_get($body, 'type') ?: data_get($body, 'message');
                // Treat "success" (case-insensitive) as OK
                if (is_string($apiStatus) && strcasecmp($apiStatus, 'success') === 0) {
                    $apiOk = true;
                }
                // Some accounts get a 202 with a GUID and no "status", still valid -> treat >=200 <300 with no explicit error as OK
                if (!$apiOk && $response->successful() && !data_get($body, 'errors')) {
                    $apiOk = true;
                }
            }

            if (!$apiOk) {
                // Build helpful error message
                $err     = data_get($body, 'errors') ?: data_get($body, 'error') ?: data_get($body, 'message');
                $errStr  = is_string($err) ? $err : json_encode($err);

                if ($errStr) {
                    // Common hints
                    if (stripos($errStr, 'blocked prefix') !== false || stripos($errStr, 'blocked prefixes') !== false) {
                        $errStr .= ' — Verify the number has country code (e.g., 917XXXXXXXXX) and that this prefix is allowed on your WABA. Confirm customer opt-in if required.';
                    }
                    if (stripos($errStr, 'template') !== false && stripos($errStr, 'not') !== false) {
                        $errStr .= ' — Check that the WhatsApp template name/namespace matches an approved template in MSG91 and that variables count matches.';
                    }
                }

                return back()->with([
                    'error'      => 'Failed to send OTP. MSG91 error: ' . ($errStr ?: 'Unknown error'),
                    'otp_phone'  => $local10,
                    'otp_sent'   => false,
                ])->withInput();
            }

            return back()->with([
                'otp_sent'  => true,
                'otp_phone' => $local10,
                'message'   => 'OTP sent to your WhatsApp.',
            ]);

        } catch (\Throwable $e) {
            Log::error('MSG91 OTP Exception: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with([
                'error' => 'Server error while sending OTP. Please try again.',
            ])->withInput();
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile_no' => ['required', 'string'],
            'otp'       => ['required', 'string'],
        ]);

        $local10 = $this->tenDigitLocal($request->mobile_no);

        $admin = Admin::where('mobile_no', $local10)->first();
        if (!$admin) {
            // Try "+91XXXXXXXXXX" style if that’s how it’s stored
            $admin = Admin::where('mobile_no', '+91'.$local10)->first();
        }

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

        // Optional: check expiry (only if column exists)
        if (schemaHasColumn('admins', 'otp_expires_at') && $admin->otp_expires_at) {
            if (Carbon::parse($admin->otp_expires_at)->isPast()) {
                return back()->withErrors([
                    'otp' => 'OTP expired. Please request a new one.',
                ])->withInput();
            }
        }

        // Clear OTP
        $admin->otp = null;
        if (schemaHasColumn('admins', 'otp_expires_at')) {
            $admin->otp_expires_at = null;
        }

        // Ensure admin_id
        if (empty($admin->admin_id)) {
            $admin->admin_id = 'ADMIN' . random_int(10000, 99999);
        }

        $admin->save();

        Auth::guard('admins')->login($admin);

        return redirect()->route('admin.dashboard')->with('success', 'OTP verified. You are logged in.');
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
