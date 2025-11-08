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
        $today     = \Carbon\Carbon::today();
        $todayStr  = $today->toDateString();

        // ---------- Profiles / Applications ----------
        $todayProfiles = \App\Models\PratihariProfile::whereDate('created_at', $today)->get();

        $todayApprovedProfiles = \App\Models\PratihariProfile::whereDate('updated_at', $today)
            ->where('pratihari_status', 'approved')->get();

        $todayRejectedProfiles = \App\Models\PratihariProfile::whereDate('updated_at', $today)
            ->where('pratihari_status', 'rejected')->get();

        $incompleteProfiles = \App\Models\PratihariProfile::query()
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

        $totalActiveUsers = \App\Models\PratihariProfile::where('status', 'active')
            ->where('pratihari_status', 'approved')->get();

        $updatedProfiles = \App\Models\PratihariProfile::where('status', 'active')
            ->where('pratihari_status', 'updated')->get();

        $pendingProfile = \App\Models\PratihariProfile::where('status', 'active')
            ->where('pratihari_status', 'pending')->get();

        $rejectedProfiles = \App\Models\PratihariProfile::where('pratihari_status', 'rejected')->get();

        $profiles = \App\Models\PratihariProfile::with(['occupation', 'address'])
            ->where('status', 'active')->get();

        $todayApplications = \App\Models\PratihariApplication::whereDate('created_at', $today)
            ->where('status', 'active')->get();

        $approvedApplication = \App\Models\PratihariApplication::where('status', 'approved')->get();
        $rejectedApplication = \App\Models\PratihariApplication::where('status', 'rejected')->get();

        // ---------- Seba master groups ----------
        $pratihariSebaIds = \App\Models\PratihariSebaMaster::where('type', 'pratihari')->pluck('id');
        $gochhikarSebaIds = \App\Models\PratihariSebaMaster::where('type', 'gochhikar')->pluck('id');

        $pratihariIds = \App\Models\PratihariSeba::whereIn('seba_id', $pratihariSebaIds)->pluck('pratihari_id')->unique();
        $gochhikarIds = \App\Models\PratihariSeba::whereIn('seba_id', $gochhikarSebaIds)->pluck('pratihari_id')->unique();

        $profile_name   = \App\Models\PratihariProfile::whereIn('pratihari_id', $pratihariIds)->get();
        $gochhikar_name = \App\Models\PratihariProfile::whereIn('pratihari_id', $gochhikarIds)->get();

        // ---------- Current user table presence (optional UI checklist) ----------
        $profileStatus = [];
        if (\Illuminate\Support\Facades\Auth::check()) {
            $pid = \Illuminate\Support\Facades\Auth::user()->pratihari_id ?? null;
            if ($pid) {
                $profileStatus = [
                    'profile'      => \App\Models\PratihariProfile::where('pratihari_id', $pid)->exists(),
                    'family'       => \App\Models\PratihariFamily::where('pratihari_id', $pid)->exists(),
                    'id_card'      => \App\Models\PratihariIdcard::where('pratihari_id', $pid)->exists(),
                    'address'      => \App\Models\PratihariAddress::where('pratihari_id', $pid)->exists(),
                    'seba'         => \App\Models\PratihariSeba::where('pratihari_id', $pid)->exists(),
                    'social_media' => \App\Models\PratihariSocialMedia::where('pratihari_id', $pid)->exists(),
                ];
            }
        }

        // ---------- Today’s mapped beddha numbers ----------
        $beddhaMapping   = \App\Models\DateBeddhaMapping::where('date', $todayStr)->first();
        $pratihariBeddha = $beddhaMapping->pratihari_beddha ?? 'N/A';
        $gochhikarBeddha = $beddhaMapping->gochhikar_beddha ?? 'N/A';

        $todayPrBeddha = is_numeric($pratihariBeddha) ? (int)$pratihariBeddha : null;
        $todayGoBeddha = is_numeric($gochhikarBeddha) ? (int)$gochhikarBeddha : null;

        // ---------- LEFT PANEL: PRATIHARI ----------
        $pratihariEvents = [];
        $nijogaAssign    = [];

        if ($todayPrBeddha) {
            $sebas = \App\Models\PratihariSeba::with(['sebaMaster', 'pratihari', 'beddhaAssigns'])
                ->whereIn('seba_id', $pratihariSebaIds)
                ->where('status', 'active')
                ->get();

            foreach ($sebas as $seba) {
                // Ensure beddha_id is array<int>, skip if not present
                $beddhaIds = collect((array) $seba->beddha_id)->map(fn($v) => (int)$v)->all();
                if (!in_array($todayPrBeddha, $beddhaIds, true)) continue;

                $sebaName   = $seba->sebaMaster?->seba_name ?? 'Unknown Seba';
                $assign     = $seba->beddhaAssigns?->firstWhere('beddha_id', $todayPrBeddha);
                $assignedBy = ($assign && (int)($assign->beddha_status ?? 0) === 1) ? 'User' : 'Admin';

                // profile can be null; only push if present
                $profile = $seba->pratihari;
                if (!$profile) continue;

                $label = "{$sebaName} | Beddha {$todayPrBeddha}";
                $entry = [
                    'profile'     => $profile,
                    'beddha'      => $todayPrBeddha,
                    'assigned_by' => $assignedBy,
                ];

                if ($assignedBy === 'User') {
                    $pratihariEvents[$label][] = $entry;
                } else {
                    $nijogaAssign[$label][]    = $entry;
                }
            }

            // Deduplicate (by profile id), guard nulls defensively
            $pratihariEvents = collect($pratihariEvents)->map(function ($arr) {
                return collect($arr)
                    ->filter(fn($e) => data_get($e, 'profile.pratihari_id'))  // remove null profiles
                    ->unique(fn($e) => data_get($e, 'profile.pratihari_id'))
                    ->values()->all();
            })->toArray();

            $nijogaAssign = collect($nijogaAssign)->map(function ($arr) {
                return collect($arr)
                    ->filter(fn($e) => data_get($e, 'profile.pratihari_id'))
                    ->unique(fn($e) => data_get($e, 'profile.pratihari_id'))
                    ->values()->all();
            })->toArray();
        }

        // ---------- RIGHT PANEL: GOCHHIKAR ----------
        $gochhikarEvents = [];
        $nijogaGochhikarEvents = [];

        if ($todayGoBeddha) {
            $gsebas = \App\Models\PratihariSeba::with(['sebaMaster', 'pratihari', 'beddhaAssigns'])
                ->whereIn('seba_id', $gochhikarSebaIds)
                ->where('status', 'active')
                ->get();

            foreach ($gsebas as $seba) {
                $ids = collect((array) $seba->beddha_id)->map(fn($v) => (int)$v)->all();
                if (!in_array($todayGoBeddha, $ids, true)) continue;

                $sebaName = $seba->sebaMaster?->seba_name ?? 'Unknown Seba';
                $assign   = $seba->beddhaAssigns?->firstWhere('beddha_id', $todayGoBeddha);
                $label    = "{$sebaName} | Beddha {$todayGoBeddha}";

                $profile = $seba->pratihari;
                if (!$profile) continue;

                if ($assign && (int)($assign->beddha_status ?? 0) === 1) {
                    $gochhikarEvents[$label][] = $profile;
                } else {
                    $nijogaGochhikarEvents[$label][] = $profile;
                }
            }

            $gochhikarEvents = collect($gochhikarEvents)->map(function ($arr) {
                return collect($arr)
                    ->filter(fn($u) => data_get($u, 'pratihari_id'))
                    ->unique('pratihari_id')
                    ->values()->all();
            })->toArray();

            $nijogaGochhikarEvents = collect($nijogaGochhikarEvents)->map(function ($arr) {
                return collect($arr)
                    ->filter(fn($u) => data_get($u, 'pratihari_id'))
                    ->unique('pratihari_id')
                    ->values()->all();
            })->toArray();
        }

        // ---------- Display chips ----------
        $currentPratihariBeddhaDisplay = $todayPrBeddha ? (string)$todayPrBeddha : '—';
        $currentGochhikarBeddhaDisplay = $todayGoBeddha ? (string)$todayGoBeddha : '—';

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
