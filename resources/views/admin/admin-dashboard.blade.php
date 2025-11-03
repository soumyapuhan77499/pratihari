@extends('layouts.app')

@section('styles')
    <!-- Icons & vendor CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Professional fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet"/>

    <style>
        /* =========================
           Design Tokens
        ========================= */
        :root{
            /* Brand */
            --brand-1:#4f46e5; /* indigo-600 */
            --brand-2:#0ea5e9; /* sky-500 */
            --brand-3:#22c55e; /* green-500 */

            /* Accents */
            --ok:#16a34a;
            --warn:#f59e0b;
            --danger:#ef4444;

            /* Text */
            --ink:#0b1220;         /* slate-950 */
            --ink-soft:#334155;    /* slate-600 */
            --muted:#64748b;       /* slate-500 */

            /* Surfaces (light) */
            --surface:#ffffff;
            --surface-2:#f6f7fb;   /* app bg */
            --surface-3:#f0f3ff;   /* panel tint */
            --border:rgba(2,6,23,.08);
            --ring:rgba(79,70,229,.35);
            --shadow:0 10px 30px rgba(2,6,23,.08);

            /* Gradients */
            --g-brand:linear-gradient(90deg, var(--brand-1), var(--brand-2));
            --g-soft:linear-gradient(180deg, rgba(79,70,229,.06), rgba(14,165,233,.06));
        }

        html.dark{
            --ink:#e5e7eb;
            --ink-soft:#cbd5e1;
            --muted:#94a3b8;

            --surface:#0b1020;
            --surface-2:#0a0f1c;
            --surface-3:#0f172a;
            --border:rgba(148,163,184,.18);
            --ring:rgba(56,189,248,.35);
            --shadow:0 12px 36px rgba(0,0,0,.35);
        }

        /* =========================
           App Shell
        ========================= */
        html, body { height:100%; }
        body{
            font-family:'Inter', system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial;
            background:
                radial-gradient(1200px 600px at -10% -10%, rgba(79,70,229,.08), transparent 60%),
                radial-gradient(1200px 600px at 110% -10%, rgba(14,165,233,.08), transparent 60%),
                var(--surface-2) fixed;
            color:var(--ink);
        }
        html.dark body{
            background:
                radial-gradient(1200px 600px at -10% -10%, rgba(79,70,229,.18), transparent 60%),
                radial-gradient(1200px 600px at 110% -10%, rgba(14,165,233,.14), transparent 60%),
                #070b14 fixed;
        }
        * { outline-color: var(--brand-2); }

        /* =========================
           Top App Bar
        ========================= */
        .appbar{
            position: sticky; top: 0; z-index: 40;
            backdrop-filter: blur(10px);
            background: color-mix(in oklab, var(--surface) 70%, transparent);
            border-bottom: 1px solid var(--border);
        }
        .brand{
            font-weight: 800;
            letter-spacing:.2px;
            background: var(--g-brand);
            -webkit-background-clip: text; background-clip: text; color: transparent;
        }
        .btn-theme{
            display:inline-flex; align-items:center; gap:8px;
            border:1px solid var(--border); background: var(--surface);
            border-radius: 999px; padding:8px 12px; font-weight:700;
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .btn-theme:hover{ transform: translateY(-1px); box-shadow: var(--shadow); }

        /* =========================
           Panels & Sections
        ========================= */
        .panel{
            background: var(--surface);
            border:1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow);
        }
        .panel-head{
            padding: 14px 16px;
            border-bottom:1px solid var(--border);
            background: var(--g-soft);
            border-top-left-radius: 16px; border-top-right-radius: 16px;
        }
        .section-title{
            font-weight:800; letter-spacing:.2px;
        }
        .subtle{ color:var(--muted); }

        /* =========================
           Banner / Overview
        ========================= */
        .overview{
            background: var(--surface);
            border:1px solid var(--border);
            border-radius:16px;
            padding:16px;
            box-shadow: var(--shadow);
        }
        .pill{
            display:inline-flex; align-items:center; gap:8px;
            padding:8px 12px; border-radius:999px; font-weight:700;
            border:1px solid var(--border);
            background: var(--surface);
            color:var(--ink-soft);
        }
        .pill .dot{
            width:8px; height:8px; border-radius:50%;
            background: var(--brand-1);
        }

        /* =========================
           Cards & Lists
        ========================= */
        .strip{
            display:grid; grid-auto-flow:column; grid-auto-columns:max-content;
            gap:14px; overflow-x:auto; padding-bottom:6px; scroll-snap-type:x mandatory;
        }
        .mini-card{
            scroll-snap-align:start; min-width:220px;
            background: color-mix(in oklab, var(--surface) 80%, var(--surface-3));
            border:1px solid var(--border); border-radius:14px; padding:12px;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }
        .mini-card:hover{ transform:translateY(-2px); box-shadow: var(--shadow); border-color: rgba(79,70,229,.35); }
        .avatar{
            width:48px; height:48px; border-radius:50%; object-fit:cover;
            border:2px solid var(--surface); box-shadow: var(--shadow);
        }
        .chip { display:inline-flex; align-items:center; gap:6px; padding:4px 10px; border-radius:999px; font-size:.8rem; font-weight:700; border:1px solid var(--border); background:var(--surface); color:var(--ink-soft); }
        .chip.ok{ color:var(--ok); background:rgba(22,163,74,.08); border-color:rgba(22,163,74,.25); }
        .chip.warn{ color:var(--warn); background:rgba(245,158,11,.10); border-color:rgba(245,158,11,.25); }
        .chip.danger{ color:var(--danger); background:rgba(239,68,68,.10); border-color:rgba(239,68,68,.25); }
        .list-max{ max-height:320px; overflow:auto; }
        .list-row:hover{ background: color-mix(in oklab, var(--surface) 85%, var(--surface-3)); }

        /* =========================
           KPIs
        ========================= */
        .kpi{
            background: var(--surface);
            border:1px solid var(--border);
            border-radius:14px;
            padding:16px;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }
        .kpi:hover{ transform: translateY(-2px); box-shadow: var(--shadow); border-color: rgba(79,70,229,.25); }
        .kpi .meta{ display:flex; align-items:center; gap:8px; font-weight:700; color:var(--ink-soft); }
        .kpi .value{ font-size: clamp(1.8rem, 2.6vw, 2.3rem); font-weight:800; letter-spacing:-.5px; }

        /* =========================
           Tabs
        ========================= */
        .tabs .nav-link{
            border:1px solid var(--border); background:var(--surface);
            color:var(--ink-soft); font-weight:800; letter-spacing:.2px;
            border-radius:12px; padding:.55rem 1rem;
        }
        .tabs .nav-link.active{
            color:#fff; background: var(--g-brand); border-color: transparent;
        }

        /* =========================
           Utilities
        ========================= */
        .mono{ font-family:"JetBrains Mono", ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
        .divider{ height:1px; background:var(--border); }
        .text-gradient{ background: var(--g-brand); -webkit-background-clip:text; background-clip:text; color:transparent; }
        :is(a,button,.kpi,.mini-card,.chip,.nav-link,.form-control):focus-visible{ box-shadow: 0 0 0 4px var(--ring); }

        /* Scrollbars */
        .strip::-webkit-scrollbar, .list-max::-webkit-scrollbar { height:8px; width:8px; }
        .strip::-webkit-scrollbar-thumb, .list-max::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:8px; }
        html.dark .strip::-webkit-scrollbar-thumb, html.dark .list-max::-webkit-scrollbar-thumb { background:#475569; }
    </style>
@endsection

@section('content')
    <!-- App Bar -->
    <div class="appbar py-2">
        <div class="container-fluid d-flex align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <span class="brand fs-4">Pratihari Admin</span>
                <span class="pill d-none d-md-inline">
                    <span class="dot"></span>
                    {{ \Carbon\Carbon::now()->format('d M Y') }}
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
        <!-- Overview -->
        <div class="overview mb-3">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-lg">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="pill"><i class="bi bi-badge-ad"></i> <span class="fw-bold text-gradient">Pratihari Beddha:</span> <span class="ms-1">{{ $pratihariBeddha ?: 'N/A' }}</span></span>
                        <span class="pill"><i class="bi bi-people"></i> <span class="fw-bold text-gradient">Gochhikar Beddha:</span> <span class="ms-1">{{ $gochhikarBeddha ?: 'N/A' }}</span></span>
                    </div>
                </div>
                <div class="col-12 col-lg-auto">
                    <div class="d-flex gap-2">
                        <a class="btn btn-outline-primary fw-bold" href="{{ route('admin.pratihari.filterUsers', 'today') }}"><i class="bi bi-funnel"></i> Today</a>
                        <a class="btn btn-outline-secondary fw-bold" href="{{ route('admin.pratihari.filterUsers', 'approved') }}"><i class="bi bi-check2-circle"></i> Approved</a>
                        <a class="btn btn-outline-warning fw-bold" href="{{ route('admin.pratihari.filterUsers', 'pending') }}"><i class="bi bi-hourglass-split"></i> Pending</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="row g-3">
            <!-- Left column -->
            <div class="col-12 col-xl-8">
                <div class="panel">
                    <div class="panel-head d-flex align-items-center justify-content-between">
                        <div>
                            <div class="section-title">Today’s Pratihari Seba</div>
                            <div class="subtle">Assigned Seba users and Nijoga (if any).</div>
                        </div>
                    </div>
                    <div class="p-3">
                        <!-- Tabs -->
                        <ul class="nav nav-pills tabs gap-2" id="sebaTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pratihari-tab" data-bs-toggle="pill" data-bs-target="#pratihari-pane" type="button" role="tab" aria-controls="pratihari-pane" aria-selected="true">
                                    <i class="bi bi-person-badge me-1"></i> Pratihari
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="nijoga-tab" data-bs-toggle="pill" data-bs-target="#nijoga-pane" type="button" role="tab" aria-controls="nijoga-pane" aria-selected="false">
                                    <i class="bi bi-clipboard2-check me-1"></i> Nijoga Assigned
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content mt-3" id="sebaTabsContent">
                            <!-- Pratihari -->
                            <div class="tab-pane fade show active" id="pratihari-pane" role="tabpanel" aria-labelledby="pratihari-tab">
                                @forelse ($pratihariEvents as $label => $pratiharis)
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="fw-bold">{{ $label }}</span>
                                            <span class="chip ok"><i class="bi bi-calendar-event"></i> Today</span>
                                        </div>
                                        <div class="strip">
                                            @foreach ($pratiharis as $user)
                                                <div class="mini-card">
                                                    @include('partials._user_card', ['user' => $user])
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-light border d-flex align-items-center" role="alert">
                                        <i class="bi bi-info-circle me-2"></i>
                                        No seba assigned for today.
                                    </div>
                                @endforelse
                            </div>

                            <!-- Nijoga -->
                            <div class="tab-pane fade" id="nijoga-pane" role="tabpanel" aria-labelledby="nijoga-tab">
                                @forelse ($nijogaAssign as $label => $nojoga)
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="fw-bold">{{ $label }}</span>
                                            <span class="chip warn"><i class="bi bi-clipboard2-pulse"></i> Nijoga</span>
                                        </div>
                                        <div class="strip">
                                            @foreach ($nojoga as $user)
                                                <div class="mini-card">
                                                    @include('partials._user_card', ['user' => $user])
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-light border d-flex align-items-center" role="alert">
                                        <i class="bi bi-info-circle me-2"></i>
                                        No nijoga seba assigned for today.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right column -->
            <div class="col-12 col-xl-4">
                <div class="panel h-100">
                    <div class="panel-head">
                        <div class="section-title">Gochhikar Today</div>
                        <div class="subtle">Normal & Nijoga assignments</div>
                    </div>
                    <div class="p-3">
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-bold">Gochhikar</span>
                                <span class="chip ok"><i class="bi bi-check2-circle"></i> Normal</span>
                            </div>
                            @forelse ($gochhikarEvents as $label => $users)
                                <div class="mb-2">
                                    <div class="small fw-semibold mb-1">{{ $label }}</div>
                                    <div class="strip">
                                        @foreach ($users as $user)
                                            <div class="mini-card">
                                                @include('partials._user_card', ['user' => $user])
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-light border" role="alert">
                                    No Gochhikar assigned (normal) for today.
                                </div>
                            @endforelse
                        </div>

                        <div class="divider my-3"></div>

                        <div class="mb-2">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-bold">Nijoga Assign</span>
                                <span class="chip warn"><i class="bi bi-exclamation-circle"></i> Nijoga</span>
                            </div>
                            @forelse ($nijogaGochhikarEvents as $label => $users)
                                <div class="mb-2">
                                    <div class="small fw-semibold mb-1">{{ $label }}</div>
                                    <div class="strip">
                                        @foreach ($users as $user)
                                            <div class="mini-card">
                                                @include('partials._user_card', ['user' => $user])
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-light border" role="alert">
                                    No Nijoga Gochhikar for today.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPIs -->
            <div class="col-12 mt-1">
                <div class="row g-3">
                    <!-- Today’s Registrations -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi h-100">
                            <div class="meta">
                                <i class="bi bi-person-plus"></i> Today’s Registrations
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.pratihari.filterUsers', 'today') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($todayProfiles) }}</div>
                            <div class="subtle">New profiles created</div>
                            <div class="list-max mt-2">
                                @foreach ($todayProfiles->take(5) as $user)
                                    <div class="list-row d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <img class="avatar" src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}" alt="Profile">
                                            <div>
                                                <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}" class="fw-semibold text-decoration-none">{{ $user->first_name }} {{ $user->last_name }}</a>
                                                <div class="subtle">{{ $user->phone_no }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Pending Profiles -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi h-100">
                            <div class="meta">
                                <i class="bi bi-hourglass-split"></i> Pending Profiles
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.pratihari.filterUsers', 'pending') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($pendingProfile) }}</div>
                            <div class="subtle">Awaiting review</div>
                            <div class="list-max mt-2">
                                @foreach ($pendingProfile->take(5) as $user)
                                    <div class="list-row d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <img class="avatar" src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}" alt="Profile">
                                            <div>
                                                <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}" class="fw-semibold text-decoration-none">{{ $user->first_name }} {{ $user->last_name }}</a>
                                                <div class="subtle">{{ $user->phone_no }}</div>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-outline-success approve-btn" data-id="{{ $user->pratihari_id }}"><i class="bi bi-check2"></i></button>
                                            <button class="btn btn-sm btn-outline-danger reject-btn" data-id="{{ $user->pratihari_id }}"><i class="bi bi-x"></i></button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Active Profiles -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi h-100">
                            <div class="meta">
                                <i class="bi bi-person-check"></i> Active Profiles
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.pratihari.filterUsers', 'approved') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($totalActiveUsers) }}</div>
                            <div class="subtle">Approved & active</div>
                            <div class="list-max mt-2">
                                @foreach ($totalActiveUsers->take(5) as $user)
                                    <div class="list-row d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <img class="avatar" src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}" alt="Profile">
                                            <div>
                                                <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}" class="fw-semibold text-decoration-none">{{ $user->first_name }} {{ $user->last_name }}</a>
                                                <div class="subtle">{{ $user->phone_no }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Incomplete Profiles -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi h-100">
                            <div class="meta">
                                <i class="bi bi-clipboard2-data"></i> Incomplete Profiles
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.pratihari.filterUsers', 'incomplete') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($incompleteProfiles) }}</div>
                            <div class="subtle">Need more info</div>
                            <div class="list-max mt-2">
                                @foreach ($incompleteProfiles->take(5) as $user)
                                    <div class="list-row d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <img class="avatar" src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}" alt="Profile">
                                            <div>
                                                <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}" class="fw-semibold text-decoration-none">{{ $user->first_name }} {{ $user->last_name }}</a>
                                                <div class="subtle">{{ $user->phone_no }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Today Approved -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi h-100">
                            <div class="meta">
                                <i class="bi bi-check2-circle"></i> Today Approved
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.pratihari.filterUsers', 'todayapproved') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($todayApprovedProfiles) }}</div>
                            <div class="subtle">Approved today</div>
                            <div class="list-max mt-2">
                                @foreach ($todayApprovedProfiles->take(5) as $user)
                                    <div class="list-row d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <img class="avatar" src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}" alt="Profile">
                                            <div>
                            <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}" class="fw-semibold text-decoration-none">{{ $user->first_name }} {{ $user->last_name }}</a>
                                                <div class="subtle">{{ $user->phone_no }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Today Rejected -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi h-100">
                            <div class="meta">
                                <i class="bi bi-x-circle"></i> Today Rejected
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.pratihari.filterUsers', 'todayrejected') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($todayRejectedProfiles) }}</div>
                            <div class="subtle">Rejected today</div>
                            <div class="list-max mt-2">
                                @foreach ($todayRejectedProfiles->take(5) as $user)
                                    <div class="list-row d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <img class="avatar" src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}" alt="Profile">
                                            <div>
                                                <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}" class="fw-semibold text-decoration-none">{{ $user->first_name }} {{ $user->last_name }}</a>
                                                <div class="subtle">{{ $user->phone_no }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Updated Profiles -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi h-100">
                            <div class="meta">
                                <i class="bi bi-arrow-repeat"></i> Updated Profiles
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.pratihari.filterUsers', 'updated') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($updatedProfiles) }}</div>
                            <div class="subtle">Recently modified</div>
                            <div class="list-max mt-2">
                                @foreach ($updatedProfiles->take(5) as $user)
                                    <div class="list-row d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <img class="avatar" src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}" alt="Profile">
                                            <div>
                                                <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}" class="fw-semibold text-decoration-none">{{ $user->first_name }} {{ $user->last_name }}</a>
                                                <div class="subtle">{{ $user->phone_no }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Total Rejected -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi h-100">
                            <div class="meta">
                                <i class="bi bi-emoji-frown"></i> Total Rejected
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.pratihari.filterUsers', 'rejected') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($rejectedProfiles) }}</div>
                            <div class="subtle">All-time rejected</div>
                            <div class="list-max mt-2">
                                @foreach ($rejectedProfiles->take(5) as $user)
                                    <div class="list-row d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <img class="avatar" src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}" alt="Profile">
                                            <div>
                                                <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}" class="fw-semibold text-decoration-none">{{ $user->first_name }} {{ $user->last_name }}</a>
                                                <div class="subtle">{{ $user->phone_no }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Applications: Today -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi h-100">
                            <div class="meta">
                                <i class="bi bi-calendar-check"></i> Today’s Applications
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.application.filter', 'today') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($todayApplications) }}</div>
                            <div class="subtle">Submitted today</div>
                            <div class="list-max mt-2">
                                @foreach ($todayApplications->take(5) as $app)
                                    <div class="list-row d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-file-person fs-5 text-primary"></i>
                                            <div>
                                                <div class="fw-semibold">{{ $app->header ?? 'N/A' }}</div>
                                                <div class="subtle">{{ $app->date ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Applications: Approved -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi h-100">
                            <div class="meta">
                                <i class="bi bi-file-earmark-check"></i> Approved Applications
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.application.filter', 'approved') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($approvedApplication) }}</div>
                            <div class="subtle">Accepted</div>
                            <div class="list-max mt-2">
                                @foreach ($approvedApplication->take(5) as $app)
                                    <div class="list-row d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-file-person fs-5 text-success"></i>
                                            <div>
                                                <div class="fw-semibold">{{ $app->header ?? 'N/A' }}</div>
                                                <div class="subtle">{{ $app->date ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Applications: Rejected -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi h-100">
                            <div class="meta">
                                <i class="bi bi-file-earmark-x"></i> Rejected Applications
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.application.filter', 'rejected') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($rejectedApplication) }}</div>
                            <div class="subtle">Declined</div>
                            <div class="list-max mt-2">
                                @foreach ($rejectedApplication->take(5) as $app)
                                    <div class="list-row d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-file-person fs-5 text-danger"></i>
                                            <div>
                                                <div class="fw-semibold">{{ $app->header ?? 'N/A' }}</div>
                                                <div class="subtle">{{ $app->date ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div><!-- /row -->
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Vendors -->
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
        // Live time
        (function tick() {
            const el = document.getElementById('live-time');
            if (el) el.textContent = new Date().toLocaleTimeString();
            setTimeout(tick, 1000);
        })();

        // Theme toggle
        (function themeInit(){
            const html = document.documentElement;
            const saved = localStorage.getItem('theme') || '';
            if(saved){
                html.classList.toggle('dark', saved === 'dark');
            } else {
                const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                html.classList.toggle('dark', systemDark);
            }
            updateThemeButton();
        })();

        function updateThemeButton(){
            const isDark = document.documentElement.classList.contains('dark');
            const btn = document.getElementById('themeToggle');
            const icon = document.getElementById('themeIcon');
            if (!btn || !icon) return;
            btn.setAttribute('aria-pressed', isDark ? 'true' : 'false');
            icon.className = isDark ? 'bi bi-sun' : 'bi bi-moon-stars';
        }

        document.getElementById('themeToggle')?.addEventListener('click', () => {
            const html = document.documentElement;
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
            updateThemeButton();
        });

        // Approve / Reject handlers
        document.addEventListener('click', function (e) {
            const approveBtn = e.target.closest('.approve-btn');
            const rejectBtn = e.target.closest('.reject-btn');

            if (approveBtn) {
                const profileId = approveBtn.dataset.id;
                Swal.fire({
                    title: 'Approve profile?',
                    text: 'This will mark the profile as approved.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#16a34a',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Approve'
                }).then((res) => {
                    if (res.isConfirmed) {
                        fetch(`/admin/pratihari/approve/${profileId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({})
                        })
                        .then(r => r.json())
                        .then(resp => {
                            Swal.fire('Approved!', resp.message || 'Profile approved.', 'success')
                                .then(() => location.reload());
                        }).catch(() => {
                            Swal.fire('Error', 'Unable to approve. Try again.', 'error');
                        });
                    }
                });
            }

            if (rejectBtn) {
                const profileId = rejectBtn.dataset.id;
                Swal.fire({
                    title: 'Reject Profile',
                    input: 'textarea',
                    inputLabel: 'Reason for rejection',
                    inputPlaceholder: 'Type your reason here...',
                    inputAttributes: { 'aria-label': 'Type your reason here' },
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Reject',
                    preConfirm: (reason) => {
                        if (!reason) {
                            Swal.showValidationMessage('Reject reason is required');
                        }
                        return reason;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/pratihari/reject/${profileId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({ reason: result.value })
                        })
                        .then(r => r.json())
                        .then(resp => {
                            Swal.fire('Rejected', resp.message || 'Profile rejected.', 'error')
                                .then(() => location.reload());
                        }).catch(() => {
                            Swal.fire('Error', 'Unable to reject. Try again.', 'error');
                        });
                    }
                });
            }
        });
    </script>
@endsection
