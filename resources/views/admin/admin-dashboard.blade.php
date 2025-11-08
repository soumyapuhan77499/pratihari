@extends('layouts.app')

@section('title', 'Pratihari Dashboard')

@section('styles')
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=JetBrains+Mono:wght@500&display=swap"
        rel="stylesheet" />

    <!-- DataTables / Select2 CSS (keep if used elsewhere) -->
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />

    <style>
        /* tokens and layout … (unchanged except new .user-card rules) */
        :root {
            --brand-a: #7c3aed;
            --brand-b: #06b6d4;
            --ink: #0b1220;
            --ink-soft: #334155;
            --muted: #64748b;
            --surface-2: #f7f8fb;
            --panel: #ffffff;
            --border: rgba(2, 6, 23, .08);
            --ring: rgba(6, 182, 212, .35);
            --shadow: 0 12px 34px rgba(2, 6, 23, .10);
            --g-brand: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            --g-soft: linear-gradient(180deg, rgba(124, 58, 237, .08), rgba(6, 182, 212, .08));
        }

        html.dark {
            --ink: #e5e7eb;
            --ink-soft: #cbd5e1;
            --muted: #94a3b8;
            --surface: #0a0f1d;
            --panel: #0f172a;
            --border: rgba(148, 163, 184, .18);
            --shadow: 0 16px 38px rgba(0, 0, 0, .45);
        }

        html,
        body {
            height: 100%
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial;
            background: radial-gradient(1100px 540px at -10% -10%, rgba(124, 58, 237, .10), transparent 60%), radial-gradient(1100px 540px at 110% -10%, rgba(6, 182, 212, .10), transparent 60%), var(--surface-2) fixed;
            color: var(--ink);
        }

        * {
            outline-color: var(--brand-b);
        }

        .appbar {
            position: sticky;
            top: 0;
            z-index: 40;
            backdrop-filter: blur(10px);
            background: color-mix(in oklab, var(--panel) 80%, transparent);
            border-bottom: 1px solid var(--border);
        }

        .brand {
            font-weight: 800;
            letter-spacing: .3px;
            background: var(--g-brand);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .btn-theme {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid var(--border);
            background: var(--panel);
            border-radius: 999px;
            padding: 8px 12px;
            font-weight: 700;
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .btn-theme:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        .panel {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: var(--shadow);
        }

        .gradient-header {
            position: relative;
            border-radius: 18px;
            padding: 24px;
            background: var(--g-brand);
            color: #fff;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .icon-hero {
            display: inline-grid;
            place-items: center;
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .18);
            border: 1px solid rgba(255, 255, 255, .35);
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 999px;
            font-weight: 700;
            border: 1px solid var(--border);
            background: var(--panel);
            color: var(--ink-soft);
        }

        .tabs .nav-link {
            border: 1px solid var(--border);
            background: var(--panel);
            color: var(--ink-soft);
            font-weight: 800;
            border-radius: 12px;
            padding: .55rem 1rem;
        }

        .tabs .nav-link.active {
            color: #fff;
            background: var(--g-brand);
            border-color: transparent;
        }

        .strip {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: clamp(140px, 24vw, 180px);
            gap: 14px;
            overflow-x: auto;
            padding: 2px 2px 10px;
            scroll-snap-type: x mandatory;
        }

        .strip::-webkit-scrollbar {
            height: 8px
        }

        .strip::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px
        }

        html.dark .strip::-webkit-scrollbar-thumb {
            background: #475569
        }

        .user-card {
            scroll-snap-align: start;
            border: 1px solid var(--border);
            background: color-mix(in oklab, var(--panel) 92%, var(--surface-2));
            border-radius: 14px;
            padding: 12px;
            text-align: center;
            box-shadow: var(--shadow);
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
            min-height: 180px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px
        }

        .user-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
            border-color: rgba(124, 58, 237, .35)
        }

        .avatar {
            width: 84px;
            height: 84px;
            border-radius: 16px;
            overflow: hidden;
            display: grid;
            place-items: center;
            background: #eef2ff;
            color: #4f46e5;
            font-weight: 800;
            border: 1px solid var(--border)
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block
        }

        .user-name {
            font-weight: 800;
            line-height: 1.2
        }

        .user-meta {
            font-size: .8rem;
            color: var(--muted)
        }

        .badge-soft {
            border: 1px solid var(--border);
            background: #fff;
            color: var(--ink-soft);
            border-radius: 999px;
            padding: 2px 8px;
            font-size: .75rem;
            font-weight: 700
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 700;
            border: 1px solid var(--border);
            background: var(--panel);
            color: var(--ink-soft)
        }

        .chip.ok {
            color: #16a34a;
            background: rgba(16, 185, 129, .10);
            border-color: rgba(16, 185, 129, .25)
        }

        .chip.warn {
            color: #f59e0b;
            background: rgba(245, 158, 11, .12);
            border-color: rgba(245, 158, 11, .25)
        }

        .kpi {
            position: relative;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 16px 16px 14px;
            box-shadow: var(--shadow);
            overflow: hidden
        }

        .kpi::before {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 6px;
            background: var(--kpi-grad, linear-gradient(90deg, #7c3aed, #06b6d4))
        }

        .kpi .meta {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            color: var(--ink-soft)
        }

        .kpi .value {
            font-size: clamp(1.8rem, 2.6vw, 2.3rem);
            font-weight: 800;
            letter-spacing: -.4px;
            color: var(--ink)
        }

        .kpi-progress {
            height: 6px;
            background: rgba(148, 163, 184, .3);
            border-radius: 999px;
            overflow: hidden;
            margin-top: 10px
        }

        .kpi-progress>span {
            display: block;
            height: 100%;
            width: var(--p, 40%);
            background: linear-gradient(90deg, #7c3aed, #06b6d4);
            border-radius: 999px
        }

        .mono {
            font-family: "JetBrains Mono", ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace
        }
    </style>
@endsection

@section('content')
    @php
        use Illuminate\Support\Facades\Storage;

        /* ------- NULL-SAFE HELPERS ------- */

        /** Safe avatar URL from several possible fields; returns null if none */
        $avatarUrl = function ($u) {
            if (!$u) {
                return null;
            }
            $cand =
                data_get($u, 'photo') ??
                (data_get($u, 'profile_photo') ?? (data_get($u, 'profile_photo_path') ?? data_get($u, 'avatar')));
            if (!$cand) {
                return null;
            }
            if (preg_match('/^https?:\/\//i', $cand)) {
                return $cand;
            }
            if (Storage::disk('public')->exists($cand)) {
                return Storage::disk('public')->url($cand);
            }
            return asset($cand);
        };

        /** Initials fallback for avatar block */
        $initials = function ($u) {
            $f = trim((string) data_get($u, 'first_name', ''));
            $l = trim((string) data_get($u, 'last_name', ''));
            $fi = $f !== '' ? mb_substr($f, 0, 1) : '';
            $li = $l !== '' ? mb_substr($l, 0, 1) : '';
            $both = strtoupper($fi . $li);
            if ($both === '') {
                $name = trim((string) (data_get($u, 'name', '') ?: $f . ' ' . $l));
                $both = $name ? strtoupper(mb_substr($name, 0, 1)) : 'P';
            }
            return $both;
        };

        /** Full name */
        $fullName = function ($u) {
            $n = trim((string) (data_get($u, 'first_name', '') . ' ' . data_get($u, 'last_name', '')));
            return $n !== '' ? $n : (data_get($u, 'name', '—') ?: '—');
        };

        // KPI arrays (unchanged)
        $mapProfile = function ($u) {
            return [
                'name' => trim(data_get($u, 'first_name', '') . ' ' . data_get($u, 'last_name', '')),
                'phone' => data_get($u, 'phone_no', ''),
                'link' => route('admin.viewProfile', data_get($u, 'pratihari_id')),
            ];
        };
        $mapApplication = function ($a) {
            return [
                'name' => data_get($a, 'header', 'N/A'),
                'phone' => '',
                'meta' => data_get($a, 'date', ''),
                'link' => '',
            ];
        };

        $arrTodayProfiles = $todayProfiles->map($mapProfile)->values();
        $arrPendingProfiles = $pendingProfile->map($mapProfile)->values();
        $arrActiveProfiles = $totalActiveUsers->map($mapProfile)->values();
        $arrIncompleteProfiles = $incompleteProfiles->map($mapProfile)->values();
        $arrTodayApproved = $todayApprovedProfiles->map($mapProfile)->values();
        $arrTodayRejected = $todayRejectedProfiles->map($mapProfile)->values();
        $arrUpdatedProfiles = $updatedProfiles->map($mapProfile)->values();
        $arrRejectedAll = $rejectedProfiles->map($mapProfile)->values();

        $arrAppsToday = $todayApplications->map($mapApplication)->values();
        $arrAppsApproved = $approvedApplication->map($mapApplication)->values();
        $arrAppsRejected = $rejectedApplication->map($mapApplication)->values();
    @endphp

    <!-- App Bar -->
    <div class="appbar py-2 mt-4">
        <div class="container-fluid d-flex align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <span class="brand fs-4">Pratihari Admin</span>
                <span class="pill d-none d-md-inline">
                    <span class="dot"></span>{{ \Carbon\Carbon::now()->format('d M Y') }}
                </span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="d-none d-sm-flex align-items-center gap-2 subtle mono">
                    <i class="bi bi-clock-history"></i>
                    <span id="live-time"></span>
                </div>
                <button id="themeToggle" class="btn-theme" type="button" aria-pressed="false">
                    <i class="bi bi-moon-stars" id="themeIcon"></i>
                    <span class="d-none d-md-inline">Theme</span>
                </button>
            </div>
        </div>
    </div>

    <div class="container-fluid py-3">
        <!-- Header -->
        <div class="gradient-header mb-3">
            <div class="d-flex align-items-start align-items-md-center gap-3 flex-column flex-md-row">
                <span class="icon-hero"><i class="bi bi-speedometer2"></i></span>
                <div class="flex-grow-1">
                    <div class="title">Pratihari Admin Dashboard</div>
                    <div class="subtitle">A colorful, icon-led overview for quick scanning and action.</div>
                </div>
                <div class="header-actions d-flex gap-2 ms-md-auto">
                    <a class="btn btn-sm btn-outline-light" href="{{ route('admin.pratihari.filterUsers', 'today') }}"
                        style="color: white"><i class="bi bi-funnel"></i> Today</a>
                    <a class="btn btn-sm btn-outline-light" href="{{ route('admin.pratihari.filterUsers', 'approved') }}"
                        style="color: white"><i class="bi bi-check2-circle"></i> Approved</a>
                    <a class="btn btn-sm btn-outline-light" href="{{ route('admin.pratihari.filterUsers', 'pending') }}"
                        style="color: white"><i class="bi bi-hourglass-split"></i> Pending</a>
                </div>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2 mt-3">
                <span class="pill"><i class="bi bi-badge-ad"></i><span class="fw-bold ms-1">Pratihari Beddha:</span><span
                        class="ms-1">{{ $pratihariBeddha ?: 'N/A' }}</span></span>
                <span class="pill"><i class="bi bi-people"></i><span class="fw-bold ms-1">Gochhikar Beddha:</span><span
                        class="ms-1">{{ $gochhikarBeddha ?: 'N/A' }}</span></span>
            </div>
        </div>

        <div class="row g-3">
            <!-- LEFT: Pratihari & Nijoga -->
            <div class="col-12 col-xl-8">
                <div class="panel">
                    <div class="px-3 pt-3">
                        <div class="rounded-3 p-3 text-white"
                            style="background: var(--g-brand); box-shadow: var(--shadow);">
                            <div class="d-flex align-items-center gap-2">
                                <span class="icon-hero" style="background:transparent;border-color:rgba(255,255,255,.35)"><i
                                        class="bi bi-person-badge"></i></span>
                                <div class="flex-grow-1">
                                    <div class="fw-bold" style="letter-spacing:.2px;">Today’s Pratihari Seba</div>
                                    <div class="small" style="opacity:.9;">Assigned Seba users and Nijoga (if any)</div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span
                                        class="badge bg-light text-dark border-0">{{ collect($pratihariEvents)->flatten(1)->count() }}
                                        Pratihari</span>
                                    <span
                                        class="badge bg-light text-dark border-0">{{ collect($nijogaAssign)->flatten(1)->count() }}
                                        Nijoga</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-3"
                        style="background: linear-gradient(180deg, rgba(124,58,237,.06), rgba(6,182,212,.06)); border-bottom-left-radius:18px; border-bottom-right-radius:18px;">
                        <ul class="nav nav-pills tabs gap-2 mb-3" id="sebaTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pratihari-tab" data-bs-toggle="pill"
                                    data-bs-target="#pratihari-pane" type="button" role="tab"> <i
                                        class="bi bi-person-badge me-1"></i> Pratihari </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="nijoga-tab" data-bs-toggle="pill" data-bs-target="#nijoga-pane"
                                    type="button" role="tab"> <i class="bi bi-clipboard2-check me-1"></i> Nijoga
                                    Assigned </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="sebaTabsContent">
                            <!-- Pratihari -->
                            <div class="tab-pane fade show active" id="pratihari-pane" role="tabpanel">
                                @forelse ($pratihariEvents as $label => $entries)
                                    @php $count = is_iterable($entries) ? count($entries) : 0; @endphp
                                    <div class="mb-3">
                                        <div
                                            class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold">{{ $label }}</span>
                                                <span class="badge text-bg-light border">{{ $count }}</span>
                                            </div>
                                            <span class="chip ok"><i class="bi bi-calendar-event"></i> Today</span>
                                        </div>

                                        <div class="strip">
                                            @foreach ($entries as $e)
                                                @php
                                                    $u = data_get($e, 'profile'); // may be null
                                                    if (!$u) {
                                                        continue;
                                                    }
                                                    $name = $fullName($u);
                                                    $photo = $avatarUrl($u);
                                                    $ini = $initials($u);
                                                    $phone = data_get($u, 'phone_no');
                                                    $beddha = data_get($e, 'beddha');
                                                @endphp
                                                <div class="user-card" title="{{ $name }}">
                                                    <div class="avatar">
                                                        @if ($photo)
                                                            <img src="{{ $photo }}" alt="{{ $name }}">
                                                        @else
                                                            <span>{{ $ini }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="user-name text-truncate w-100">{{ $name }}</div>
                                                    @if (!is_null($beddha))
                                                        <div class="user-meta">Beddha: <span
                                                                class="badge-soft">{{ $beddha }}</span></div>
                                                    @endif
                                                    <div class="user-meta">{{ $phone ? '☎ ' . $phone : '' }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-light border d-flex align-items-center" role="alert">
                                        <i class="bi bi-info-circle me-2"></i>No seba assigned for today.
                                    </div>
                                @endforelse
                            </div>

                            <!-- Nijoga -->
                            <div class="tab-pane fade" id="nijoga-pane" role="tabpanel">
                                @forelse ($nijogaAssign as $label => $entries)
                                    @php $count = is_iterable($entries) ? count($entries) : 0; @endphp
                                    <div class="mb-3">
                                        <div
                                            class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold">{{ $label }}</span>
                                                <span class="badge text-bg-light border">{{ $count }}</span>
                                            </div>
                                            <span class="chip warn"><i class="bi bi-clipboard2-pulse"></i> Nijoga</span>
                                        </div>

                                        <div class="strip">
                                            @foreach ($entries as $e)
                                                @php
                                                    $u = data_get($e, 'profile');
                                                    if (!$u) {
                                                        continue;
                                                    }
                                                    $name = $fullName($u);
                                                    $photo = $avatarUrl($u);
                                                    $ini = $initials($u);
                                                    $phone = data_get($u, 'phone_no');
                                                    $beddha = data_get($e, 'beddha');
                                                @endphp
                                                <div class="user-card" title="{{ $name }}">
                                                    <div class="avatar">
                                                        @if ($photo)
                                                            <img src="{{ $photo }}" alt="{{ $name }}">
                                                        @else
                                                            <span>{{ $ini }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="user-name text-truncate w-100">{{ $name }}</div>
                                                    @if (!is_null($beddha))
                                                        <div class="user-meta">Beddha: <span
                                                                class="badge-soft">{{ $beddha }}</span></div>
                                                    @endif
                                                    <div class="user-meta">{{ $phone ? '☎ ' . $phone : '' }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-light border d-flex align-items-center" role="alert">
                                        <i class="bi bi-info-circle me-2"></i>No nijoga seba assigned for today.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Gochhikar Today -->
            <div class="col-12 col-xl-4">
                <div class="panel mb-3">
                    <div class="px-3 pt-3">
                        <div class="rounded-3 p-3 text-white"
                            style="background: var(--g-brand); box-shadow: var(--shadow);">
                            <div class="d-flex align-items-center gap-2">
                                <span class="icon-hero" style="border-color: rgba(255,255,255,.35);"><i
                                        class="bi bi-people"></i></span>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Gochhikar Today</div>
                                    <div class="small" style="opacity:.9;">Normal & Nijoga assignments</div>
                                </div>
                                <span class="badge bg-light text-dark border-0">
                                    {{ collect($gochhikarEvents)->flatten(1)->count() + collect($nijogaGochhikarEvents)->flatten(1)->count() }}
                                    total
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-3">
                        @php
                            $gochhikarCount = collect($gochhikarEvents)->flatten(1)->count();
                            $nijogaCount = collect($nijogaGochhikarEvents)->flatten(1)->count();
                        @endphp

                        <ul class="nav nav-pills tabs gap-2 mb-3" id="gochhikarTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active d-flex align-items-center gap-2" id="gochhikar-tab"
                                    data-bs-toggle="pill" data-bs-target="#gochhikar-pane" type="button"
                                    role="tab">
                                    <i class="bi bi-check2-circle"></i><span>Gochhikar</span>
                                    <span class="badge text-bg-light border ms-1">{{ $gochhikarCount }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center gap-2" id="nijoga-g-tab"
                                    data-bs-toggle="pill" data-bs-target="#nijoga-g-pane" type="button" role="tab">
                                    <i class="bi bi-exclamation-circle"></i><span>Nijoga Assign</span>
                                    <span class="badge text-bg-light border ms-1">{{ $nijogaCount }}</span>
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="gochhikarTabsContent">
                            <!-- Gochhikar -->
                            <div class="tab-pane fade show active" id="gochhikar-pane" role="tabpanel">
                                @forelse ($gochhikarEvents as $label => $users)
                                    <div class="mb-2">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div class="small fw-semibold">{{ $label }}</div>
                                            <span
                                                class="badge rounded-pill text-bg-light border">{{ count($users) }}</span>
                                        </div>
                                        <div class="strip">
                                            @foreach ($users as $u)
                                                @php
                                                    if (!$u) {
                                                        continue;
                                                    }
                                                    $name = $fullName($u);
                                                    $photo = $avatarUrl($u);
                                                    $ini = $initials($u);
                                                    $phone = data_get($u, 'phone_no');
                                                @endphp
                                                <div class="user-card" title="{{ $name }}">
                                                    <div class="avatar">
                                                        @if ($photo)
                                                            <img src="{{ $photo }}" alt="{{ $name }}">
                                                        @else
                                                            <span>{{ $ini }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="user-name text-truncate w-100">{{ $name }}</div>
                                                    <div class="user-meta">{{ $phone ? '☎ ' . $phone : '' }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-light border d-flex align-items-center" role="alert">
                                        <i class="bi bi-info-circle me-2"></i>No Gochhikar assigned (normal) for today.
                                    </div>
                                @endforelse
                            </div>

                            <!-- Nijoga Gochhikar -->
                            <div class="tab-pane fade" id="nijoga-g-pane" role="tabpanel">
                                @forelse ($nijogaGochhikarEvents as $label => $users)
                                    <div class="mb-2">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div class="small fw-semibold">{{ $label }}</div>
                                            <span
                                                class="badge rounded-pill text-bg-light border">{{ count($users) }}</span>
                                        </div>
                                        <div class="strip">
                                            @foreach ($users as $u)
                                                @php
                                                    if (!$u) {
                                                        continue;
                                                    }
                                                    $name = $fullName($u);
                                                    $photo = $avatarUrl($u);
                                                    $ini = $initials($u);
                                                    $phone = data_get($u, 'phone_no');
                                                @endphp
                                                <div class="user-card" title="{{ $name }}">
                                                    <div class="avatar">
                                                        @if ($photo)
                                                            <img src="{{ $photo }}" alt="{{ $name }}">
                                                        @else
                                                            <span>{{ $ini }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="user-name text-truncate w-100">{{ $name }}</div>
                                                    <div class="user-meta">{{ $phone ? '☎ ' . $phone : '' }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-light border d-flex align-items-center" role="alert">
                                        <i class="bi bi-info-circle me-2"></i>No Nijoga Gochhikar for today.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI GRID (unchanged, still uses KPI modal) -->
            @includeWhen(true, 'admin.partials.kpi-grid', [
                'todayProfiles' => $todayProfiles,
                'pendingProfile' => $pendingProfile,
                'totalActiveUsers' => $totalActiveUsers,
                'incompleteProfiles' => $incompleteProfiles,
                'todayApprovedProfiles' => $todayApprovedProfiles,
                'todayRejectedProfiles' => $todayRejectedProfiles,
                'updatedProfiles' => $updatedProfiles,
                'rejectedProfiles' => $rejectedProfiles,
                'todayApplications' => $todayApplications,
                'approvedApplication' => $approvedApplication,
                'rejectedApplication' => $rejectedApplication,
                'arrTodayProfiles' => $arrTodayProfiles,
                'arrPendingProfiles' => $arrPendingProfiles,
                'arrActiveProfiles' => $arrActiveProfiles,
                'arrIncompleteProfiles' => $arrIncompleteProfiles,
                'arrTodayApproved' => $arrTodayApproved,
                'arrTodayRejected' => $arrTodayRejected,
                'arrUpdatedProfiles' => $arrUpdatedProfiles,
                'arrRejectedAll' => $arrRejectedAll,
                'arrAppsToday' => $arrAppsToday,
                'arrAppsApproved' => $arrAppsApproved,
                'arrAppsRejected' => $arrAppsRejected,
            ])
        </div>
    </div>

    <!-- KPI Modal (kept) -->
    <div class="modal fade" id="kpiListModal" tabindex="-1" aria-labelledby="kpiListModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold" id="kpiListModalLabel">List</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:56px;">#</th>
                                    <th>Name</th>
                                    <th class="d-none d-sm-table-cell">Meta</th>
                                    <th style="width:120px;">Phone</th>
                                    <th style="width:110px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="kpiListModalBody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer"><button class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">Close</button></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- vendor scripts ... -->
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/js/table-data.js') }}"></script>

    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // live clock
        (function tick() {
            const el = document.getElementById('live-time');
            if (el) el.textContent = new Date().toLocaleTimeString();
            setTimeout(tick, 1000);
        })();

        // theme
        (function() {
            const html = document.documentElement;
            const saved = localStorage.getItem('theme') || '';
            if (saved) {
                html.classList.toggle('dark', saved === 'dark');
            } else {
                html.classList.toggle('dark', window.matchMedia('(prefers-color-scheme: dark)').matches);
            }
            updateThemeButton();
        })();

        function updateThemeButton() {
            const isDark = document.documentElement.classList.contains('dark');
            const icon = document.getElementById('themeIcon');
            const btn = document.getElementById('themeToggle');
            if (!icon || !btn) return;
            btn.setAttribute('aria-pressed', isDark ? 'true' : 'false');
            icon.className = isDark ? 'bi bi-sun' : 'bi bi-moon-stars';
        }
        document.getElementById('themeToggle')?.addEventListener('click', () => {
            const html = document.documentElement;
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
            updateThemeButton();
        });

        // remember active tab for left section
        (function() {
            const tabs = [...document.querySelectorAll('#sebaTabs button[data-bs-toggle="pill"]')];
            tabs.forEach(t => t.addEventListener('shown.bs.tab', ev => {
                const target = ev.target.getAttribute('data-bs-target');
                history.replaceState(null, '', target);
            }));
            const hash = window.location.hash;
            if (hash) {
                const t = document.querySelector('#sebaTabs button[data-bs-target="' + hash + '"]');
                if (t) new bootstrap.Tab(t).show();
            }
        })();

        // KPI modal opener (unchanged)
        (function() {
            document.addEventListener('click', function(e) {
                const a = e.target.closest('.kpi-viewall');
                if (!a) return;
                e.preventDefault();
                const title = a.getAttribute('data-title') || 'List';
                const raw = a.getAttribute('data-users') || '[]';
                let rows = [];
                try {
                    rows = JSON.parse(raw);
                } catch (_) {
                    rows = [];
                }

                const modalEl = document.getElementById('kpiListModal');
                const modal = new bootstrap.Modal(modalEl);
                document.getElementById('kpiListModalLabel').textContent = title;

                const bodyEl = document.getElementById('kpiListModalBody');
                bodyEl.innerHTML = rows.map((r, i) => {
                    const name = r.name || '—';
                    const phone = r.phone || '—';
                    const meta = r.meta || '';
                    const link = r.link || '';
                    const telBtn = phone && phone !== '—' ?
                        `<a class="btn btn-sm btn-outline-primary" href="tel:${phone}" title="Call"><i class="bi bi-telephone"></i></a>` :
                        `<button class="btn btn-sm btn-outline-secondary" disabled><i class="bi bi-telephone"></i></button>`;
                    const viewBtn = link ?
                        `<a class="btn btn-sm btn-outline-secondary" href="${link}"><i class="bi bi-box-arrow-up-right"></i></a>` :
                        `<button class="btn btn-sm btn-outline-secondary" disabled><i class="bi bi-box-arrow-up-right"></i></button>`;
                    return `
                        <tr>
                            <td class="text-muted">${i+1}</td>
                            <td>${name}</td>
                            <td class="d-none d-sm-table-cell">${meta}</td>
                            <td>${ phone!=='—' ? `<a href="tel:${phone}">${phone}</a>` : '—' }</td>
                            <td class="d-flex gap-1">${telBtn}${viewBtn}</td>
                        </tr>`;
                }).join('');

                modal.show();
            }, false);
        })();
    </script>
@endsection
