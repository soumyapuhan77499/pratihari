@extends('layouts.app')

@section('title', 'Pratihari Dashboard')

@section('styles')
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Friendly, legible fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=JetBrains+Mono:wght@500&display=swap"
        rel="stylesheet" />

    <!-- DataTables / Select2 CSS (keep if used elsewhere) -->
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />

    <style>
        /* =========================
           Colorful Design Tokens
        ========================= */
        :root{
            --brand-a:#7c3aed; --brand-b:#06b6d4;
            --c-violet:#8b5cf6; --c-cyan:#06b6d4; --c-emerald:#10b981; --c-amber:#f59e0b;
            --c-rose:#f43f5e; --c-blue:#3b82f6; --c-indigo:#4f46e5; --c-fuchsia:#d946ef;
            --ok:#16a34a; --warn:#f59e0b; --danger:#ef4444;
            --ink:#0b1220; --ink-soft:#334155; --muted:#64748b;
            --surface:#ffffff; --surface-2:#f7f8fb; --panel:#ffffff;
            --border:rgba(2,6,23,.08); --ring:rgba(6,182,212,.35); --shadow:0 12px 34px rgba(2,6,23,.10);
            --g-brand:linear-gradient(90deg, var(--brand-a), var(--brand-b));
            --g-soft:linear-gradient(180deg, rgba(124,58,237,.08), rgba(6,182,212,.08));
        }
        html.dark{
            --ink:#e5e7eb; --ink-soft:#cbd5e1; --muted:#94a3b8;
            --surface:#0a0f1d; --surface-2:#070b14; --panel:#0f172a;
            --border:rgba(148,163,184,.18); --ring:rgba(59,130,246,.35);
            --shadow:0 16px 38px rgba(0,0,0,.45);
        }
        html, body{ height:100%; }
        body{
            font-family:'Inter', system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial;
            background:
                radial-gradient(1100px 540px at -10% -10%, rgba(124,58,237,.10), transparent 60%),
                radial-gradient(1100px 540px at 110% -10%, rgba(6,182,212,.10), transparent 60%),
                var(--surface-2) fixed;
            color:var(--ink);
        }
        html.dark body{
            background:
                radial-gradient(1100px 540px at -10% -10%, rgba(124,58,237,.20), transparent 60%),
                radial-gradient(1100px 540px at 110% -10%, rgba(6,182,212,.16), transparent 60%),
                var(--surface) fixed;
        }
        *{ outline-color: var(--brand-b); }
        :is(a,button,.panel,.kpi,.pill,.nav-link,.chip):focus-visible{ box-shadow:0 0 0 4px var(--ring); }

        /* Sticky app bar */
        .appbar{
            position: sticky; top: 0; z-index: 40;
            backdrop-filter: blur(10px);
            background: color-mix(in oklab, var(--panel) 80%, transparent);
            border-bottom:1px solid var(--border);
        }
        .brand{ font-weight:800; letter-spacing:.3px; background:var(--g-brand); -webkit-background-clip:text; background-clip:text; color:transparent; }
        .btn-theme{
            display:inline-flex; align-items:center; gap:8px; border:1px solid var(--border); background: var(--panel);
            border-radius: 999px; padding:8px 12px; font-weight:700; transition: transform .15s ease, box-shadow .15s ease;
        }
        .btn-theme:hover{ transform:translateY(-1px); box-shadow: var(--shadow); }

        /* Panels */
        .panel{ background:var(--panel); border:1px solid var(--border); border-radius:18px; box-shadow: var(--shadow); }
        .panel-head{ padding:14px 16px; border-bottom:1px solid var(--border); background:var(--g-soft); border-top-left-radius:18px; border-top-right-radius:18px; }
        .section-title{ font-weight:800; letter-spacing:.2px; }
        .subtle{ color:var(--muted); }
        .divider{ height:1px; background:var(--border); }

        /* Gradient overview header block */
        .gradient-header{
            position:relative; border-radius:18px; padding:24px; background: var(--g-brand); color:#fff; overflow:hidden; box-shadow: var(--shadow);
        }
        .gradient-header .bg-icons{
            position:absolute; inset:0; pointer-events:none; opacity:.14;
            background: radial-gradient(160px 160px at 15% 40%, #fff 0, transparent 60%),
                        radial-gradient(130px 130px at 85% 30%, #fff 0, transparent 60%);
            mix-blend-mode: soft-light;
        }
        .icon-hero{
            display:inline-flex; align-items:center; justify-content:center; width:46px; height:46px; border-radius:12px;
            background: rgba(255,255,255,.18); color:#fff; font-size:20px; border:1px solid rgba(255,255,255,.35);
        }
        .title{ font-weight:800; letter-spacing:.3px; font-size: clamp(20px, 2.2vw, 26px); }
        .subtitle{ color:rgba(255,255,255,.92); }

        /* Pills */
        .pill{
            display:inline-flex; align-items:center; gap:10px; padding:10px 14px; border-radius:999px; font-weight:700;
            border:1px solid var(--border); background:var(--panel); color:var(--ink-soft);
        }
        .pill .dot{ width:8px; height:8px; border-radius:999px; background:var(--brand-a); }

        /* Tabs */
        .tabs .nav-link{
            border:1px solid var(--border); background:var(--panel); color:var(--ink-soft); font-weight:800; border-radius:12px; padding:.55rem 1rem;
        }
        .tabs .nav-link.active{ color:#fff; background: var(--g-brand); border-color: transparent; }

        /* Horizontal strip */
        .strip{
            display:grid; grid-auto-flow:column; grid-auto-columns: clamp(260px, 38vw, 420px);
            gap:14px; overflow-x:auto; padding:2px 2px 10px; scroll-snap-type:x mandatory;
        }
        .mini-card{
            scroll-snap-align:start;
            background: color-mix(in oklab, var(--panel) 90%, var(--surface-2));
            border:1px solid var(--border); border-radius:14px; padding:14px;
            min-height:88px; display:flex; align-items:center; justify-content:space-between; gap:12px;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
            text-decoration:none; color:inherit;
        }
        .mini-card:hover{ transform:translateY(-2px); box-shadow: var(--shadow); border-color: rgba(124,58,237,.35); }
        .mini-card .label{ font-weight:800; }
        .mini-card .meta{ color:var(--muted); font-size:.9rem; }
        .mini-card .count{ font-weight:800; font-size:1.4rem; }
        .mini-card .go{ font-size:1.4rem; opacity:.7; }

        /* Chips */
        .chip{ display:inline-flex; align-items:center; gap:6px; padding:4px 10px; border-radius:999px; font-size:.8rem; font-weight:700; border:1px solid var(--border); background:var(--panel); color:var(--ink-soft); }
        .chip.ok{ color:var(--ok); background:rgba(16,185,129,.10); border-color:rgba(16,185,129,.25); }
        .chip.warn{ color:var(--warn); background:rgba(245,158,11,.12); border-color:rgba(245,158,11,.25); }
        .chip.danger{ color:var(--danger); background:rgba(244,63,94,.12); border-color:rgba(244,63,94,.25); }

        /* Scrollbars */
        .strip::-webkit-scrollbar,.list-max::-webkit-scrollbar{ height:8px; width:8px; }
        .strip::-webkit-scrollbar-thumb,.list-max::-webkit-scrollbar-thumb{ background:#cbd5e1; border-radius:8px; }
        html.dark .strip::-webkit-scrollbar-thumb, html.dark .list-max::-webkit-scrollbar-thumb{ background:#475569; }

        /* KPIs */
        .kpi{ position:relative; background:var(--panel); border:1px solid var(--border); border-radius:16px; padding:16px 16px 14px; box-shadow: var(--shadow); overflow:hidden; }
        .kpi::before{ content:''; position:absolute; inset:0 0 auto 0; height:6px; background: var(--kpi-grad, var(--g-brand)); }
        .kpi::after{ content:''; position:absolute; inset:auto -20% -20% -20%; height:60%; background: radial-gradient(60% 40% at 80% 0%, color-mix(in oklab, var(--kpi-accent,#7c3aed) 28%, transparent), transparent 70%); opacity:.35; }
        .kpi .meta{ display:flex; align-items:center; gap:10px; font-weight:700; color:var(--ink-soft); }
        .kpi .meta i{ display:inline-grid; place-items:center; width:34px; height:34px; border-radius:10px; background: var(--kpi-icon-bg, #eee); color: var(--kpi-icon, #444); }
        .kpi .value{ font-size: clamp(1.8rem, 2.6vw, 2.3rem); font-weight:800; letter-spacing:-.4px; color: var(--ink); }
        .kpi .subtle{ color: var(--muted); }
        .list-max{ max-height:320px; overflow:auto; }
        .list-row:hover{ background: color-mix(in oklab, var(--panel) 85%, var(--surface-2)); }

        .kpi.violet  { --kpi-grad: linear-gradient(90deg, #8b5cf6, #a78bfa); --kpi-icon-bg: rgba(139,92,246,.12); --kpi-icon:#8b5cf6; --kpi-accent:#8b5cf6; }
        .kpi.cyan    { --kpi-grad: linear-gradient(90deg, #06b6d4, #22d3ee); --kpi-icon-bg: rgba(6,182,212,.12);  --kpi-icon:#06b6d4; --kpi-accent:#06b6d4; }
        .kpi.emerald { --kpi-grad: linear-gradient(90deg, #10b981, #34d399); --kpi-icon-bg: rgba(16,185,129,.12); --kpi-icon:#10b981; --kpi-accent:#10b981; }
        .kpi.amber   { --kpi-grad: linear-gradient(90deg, #f59e0b, #fbbf24); --kpi-icon-bg: rgba(245,158,11,.12);  --kpi-icon:#f59e0b; --kpi-accent:#f59e0b; }
        .kpi.rose    { --kpi-grad: linear-gradient(90deg, #f43f5e, #fb7185); --kpi-icon-bg: rgba(244,63,94,.12);  --kpi-icon:#f43f5e; --kpi-accent:#f43f5e; }
        .kpi.blue    { --kpi-grad: linear-gradient(90deg, #3b82f6, #60a5fa); --kpi-icon-bg: rgba(59,130,246,.12);  --kpi-icon:#3b82f6; --kpi-accent:#3b82f6; }
        .kpi.indigo  { --kpi-grad: linear-gradient(90deg, #4f46e5, #818cf8); --kpi-icon-bg: rgba(79,70,229,.12);   --kpi-icon:#4f46e5; --kpi-accent:#4f46e5; }
        .kpi.fuchsia { --kpi-grad: linear-gradient(90deg, #d946ef, #f0abfc); --kpi-icon-bg: rgba(217,70,239,.12);  --kpi-icon:#d946ef; --kpi-accent:#d946ef; }

        .kpi-progress{ height:6px; background:rgba(148,163,184,.3); border-radius:999px; overflow:hidden; margin-top:10px; }
        .kpi-progress > span{ display:block; height:100%; width:var(--p, 40%); background: var(--kpi-grad); border-radius:999px; }

        .mono{ font-family:"JetBrains Mono", ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }

        @media (max-width: 576px){ .header-actions{ margin-top: 12px; } }
    </style>
@endsection

@section('content')
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
        <!-- ===== GRADIENT HEADER / OVERVIEW ===== -->
        <div class="gradient-header mb-3">
            <div class="bg-icons"></div>
            <div class="d-flex align-items-start align-items-md-center gap-3 flex-column flex-md-row">
                <span class="icon-hero"><i class="bi bi-speedometer2"></i></span>
                <div class="flex-grow-1">
                    <div class="title">Pratihari Admin Dashboard</div>
                    <div class="subtitle">A colorful, icon-led overview for quick scanning and action.</div>
                </div>
                <div class="header-actions d-flex gap-2 ms-md-auto">
                    <a class="btn btn-sm btn-outline-light" href="{{ route('admin.pratihari.filterUsers', 'today') }}">
                        <i class="bi bi-funnel"></i> Today
                    </a>
                    <a class="btn btn-sm btn-outline-light" href="{{ route('admin.pratihari.filterUsers', 'approved') }}">
                        <i class="bi bi-check2-circle"></i> Approved
                    </a>
                    <a class="btn btn-sm btn-outline-light" href="{{ route('admin.pratihari.filterUsers', 'pending') }}">
                        <i class="bi bi-hourglass-split"></i> Pending
                    </a>
                </div>
            </div>

            <!-- Summary row -->
            <div class="d-flex flex-wrap align-items-center gap-2 mt-3">
                <span class="pill">
                    <i class="bi bi-badge-ad"></i>
                    <span class="fw-bold">Pratihari Beddha:</span>
                    <span class="ms-1">{{ $pratihariBeddha ?: 'N/A' }}</span>
                </span>
                <span class="pill">
                    <i class="bi bi-people"></i>
                    <span class="fw-bold">Gochhikar Beddha:</span>
                    <span class="ms-1">{{ $gochhikarBeddha ?: 'N/A' }}</span>
                </span>
            </div>
        </div>

        <!-- ===== MAIN GRID ===== -->
        <div class="row g-3">

            <!-- Left: Pratihari Seba & Nijoga (count-only, clickable) -->
            <div class="col-12 col-xl-8">
                <div class="panel">
                    <!-- Compact gradient sub-header -->
                    <div class="px-3 pt-3">
                        <div class="rounded-3 p-3 text-white" style="background: var(--g-brand); box-shadow: var(--shadow);">
                            <div class="d-flex align-items-center gap-2">
                                <span class="icon-hero" style="background: transparent; border-color: rgba(255,255,255,.35); color:#fff;">
                                    <i class="bi bi-person-badge"></i>
                                </span>
                                <div class="flex-grow-1">
                                    <div class="fw-bold" style="letter-spacing:.2px;">Today’s Pratihari Seba</div>
                                    <div class="small" style="opacity:.9;">Assigned Seba users and Nijoga (if any)</div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-light text-dark border-0">
                                        {{ collect($pratihariEvents)->flatten(1)->count() }} Pratihari
                                    </span>
                                    <span class="badge bg-light text-dark border-0">
                                        {{ collect($nijogaAssign)->flatten(1)->count() }} Nijoga
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subtle gradient background -->
                    <div class="p-3" style="background: linear-gradient(180deg, rgba(124,58,237,.06), rgba(6,182,212,.06)); border-bottom-left-radius:18px; border-bottom-right-radius:18px;">
                        <!-- Tabs -->
                        <ul class="nav nav-pills tabs gap-2 mb-3" id="sebaTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pratihari-tab" data-bs-toggle="pill"
                                        data-bs-target="#pratihari-pane" type="button" role="tab"
                                        aria-controls="pratihari-pane" aria-selected="true">
                                    <i class="bi bi-person-badge me-1"></i> Pratihari
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="nijoga-tab" data-bs-toggle="pill" data-bs-target="#nijoga-pane"
                                        type="button" role="tab" aria-controls="nijoga-pane" aria-selected="false">
                                    <i class="bi bi-clipboard2-check me-1"></i> Nijoga Assigned
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="sebaTabsContent">
                            <!-- Pratihari -->
                            <div class="tab-pane fade show active" id="pratihari-pane" role="tabpanel" aria-labelledby="pratihari-tab">
                                @forelse ($pratihariEvents as $label => $pratiharis)
                                    @php
                                        $pratihariList = $pratiharis->map(function($u){
                                            return [
                                                'name'   => trim(($u->first_name ?? '').' '.($u->last_name ?? '')),
                                                'phone'  => $u->phone_no ?? '',
                                            ];
                                        });
                                    @endphp
                                    <div class="mb-3">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold">{{ $label }}</span>
                                                <span class="badge text-bg-light border">{{ count($pratiharis) }}</span>
                                            </div>
                                            <span class="chip ok"><i class="bi bi-calendar-event"></i> Today</span>
                                        </div>
                                        <div class="strip">
                                            <a href="#" class="mini-card summary-card"
                                               data-title="Pratihari · {{ $label }}"
                                               data-users='@json($pratihariList)'
                                               aria-label="View all {{ count($pratiharis) }} Pratihari in {{ $label }}">
                                                <div>
                                                    <div class="label">All Pratihari</div>
                                                    <div class="meta">Click to view list</div>
                                                </div>
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="count">{{ count($pratiharis) }}</span>
                                                    <i class="bi bi-arrow-right-circle go"></i>
                                                </div>
                                            </a>
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
                                    @php
                                        $nijogaList = $nojoga->map(function($u){
                                            return [
                                                'name'   => trim(($u->first_name ?? '').' '.($u->last_name ?? '')),
                                                'phone'  => $u->phone_no ?? '',
                                            ];
                                        });
                                    @endphp
                                    <div class="mb-3">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold">{{ $label }}</span>
                                                <span class="badge text-bg-light border">{{ count($nojoga) }}</span>
                                            </div>
                                            <span class="chip warn"><i class="bi bi-clipboard2-pulse"></i> Nijoga</span>
                                        </div>
                                        <div class="strip">
                                            <a href="#" class="mini-card summary-card"
                                               data-title="Nijoga · {{ $label }}"
                                               data-users='@json($nijogaList)'
                                               aria-label="View all {{ count($nojoga) }} Nijoga in {{ $label }}">
                                                <div>
                                                    <div class="label">All Nijoga</div>
                                                    <div class="meta">Click to view list</div>
                                                </div>
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="count">{{ count($nojoga) }}</span>
                                                    <i class="bi bi-arrow-right-circle go"></i>
                                                </div>
                                            </a>
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

            <!-- Right: Gochhikar Today + Quick Actions (count-only, clickable) -->
            <div class="col-12 col-xl-4">
                <div class="panel mb-3">
                    <!-- Compact gradient sub-header -->
                    <div class="px-3 pt-3">
                        <div class="rounded-3 p-3 text-white" style="background: var(--g-brand); box-shadow: var(--shadow);">
                            <div class="d-flex align-items-center gap-2">
                                <span class="icon-hero" style="border-color: rgba(255,255,255,.35);">
                                    <i class="bi bi-people"></i>
                                </span>
                                <div class="flex-grow-1">
                                    <div class="fw-bold" style="letter-spacing:.2px;">Gochhikar Today</div>
                                    <div class="small" style="opacity:.9;">Normal &amp; Nijoga assignments</div>
                                </div>
                                <span class="badge bg-light text-dark border-0">
                                    {{ collect($gochhikarEvents)->flatten(1)->count() + collect($nijogaGochhikarEvents)->flatten(1)->count() }} total
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-3">
                        @php
                            $gochhikarCount = collect($gochhikarEvents)->flatten(1)->count();
                            $nijogaCount = collect($nijogaGochhikarEvents)->flatten(1)->count();
                        @endphp

                        <!-- Tabs -->
                        <ul class="nav nav-pills tabs gap-2 mb-3" id="gochhikarTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active d-flex align-items-center gap-2" id="gochhikar-tab"
                                        data-bs-toggle="pill" data-bs-target="#gochhikar-pane" type="button" role="tab"
                                        aria-controls="gochhikar-pane" aria-selected="true">
                                    <i class="bi bi-check2-circle"></i>
                                    <span>Gochhikar</span>
                                    <span class="badge text-bg-light border ms-1">{{ $gochhikarCount }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center gap-2" id="nijoga-g-tab"
                                        data-bs-toggle="pill" data-bs-target="#nijoga-g-pane" type="button" role="tab"
                                        aria-controls="nijoga-g-pane" aria-selected="false">
                                    <i class="bi bi-exclamation-circle"></i>
                                    <span>Nijoga Assign</span>
                                    <span class="badge text-bg-light border ms-1">{{ $nijogaCount }}</span>
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="gochhikarTabsContent">
                            <!-- Gochhikar (Normal) -->
                            <div class="tab-pane fade show active" id="gochhikar-pane" role="tabpanel" aria-labelledby="gochhikar-tab" tabindex="0">
                                @forelse ($gochhikarEvents as $label => $users)
                                    @php
                                        $gList = collect($users)->map(function($u){
                                            return [
                                                'name'  => trim(($u->first_name ?? '').' '.($u->last_name ?? '')),
                                                'phone' => $u->phone_no ?? '',
                                            ];
                                        });
                                    @endphp
                                    <div class="mb-2">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div class="small fw-semibold">{{ $label }}</div>
                                            <span class="badge rounded-pill text-bg-light border">{{ count($users) }}</span>
                                        </div>
                                        <div class="strip">
                                            <a href="#" class="mini-card summary-card"
                                               data-title="Gochhikar · {{ $label }}"
                                               data-users='@json($gList)'
                                               aria-label="View all {{ count($users) }} Gochhikar in {{ $label }}">
                                                <div>
                                                    <div class="label">All Gochhikar</div>
                                                    <div class="meta">Click to view list</div>
                                                </div>
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="count">{{ count($users) }}</span>
                                                    <i class="bi bi-arrow-right-circle go"></i>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-light border d-flex align-items-center" role="alert">
                                        <i class="bi bi-info-circle me-2"></i>
                                        No Gochhikar assigned (normal) for today.
                                    </div>
                                @endforelse
                            </div>

                            <!-- Nijoga (Gochhikar) -->
                            <div class="tab-pane fade" id="nijoga-g-pane" role="tabpanel" aria-labelledby="nijoga-g-tab" tabindex="0">
                                @forelse ($nijogaGochhikarEvents as $label => $users)
                                    @php
                                        $ngList = collect($users)->map(function($u){
                                            return [
                                                'name'  => trim(($u->first_name ?? '').' '.($u->last_name ?? '')),
                                                'phone' => $u->phone_no ?? '',
                                            ];
                                        });
                                    @endphp
                                    <div class="mb-2">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div class="small fw-semibold">{{ $label }}</div>
                                            <span class="badge rounded-pill text-bg-light border">{{ count($users) }}</span>
                                        </div>
                                        <div class="strip">
                                            <a href="#" class="mini-card summary-card"
                                               data-title="Nijoga · {{ $label }}"
                                               data-users='@json($ngList)'
                                               aria-label="View all {{ count($users) }} Nijoga Gochhikar in {{ $label }}">
                                                <div>
                                                    <div class="label">All Nijoga</div>
                                                    <div class="meta">Click to view list</div>
                                                </div>
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="count">{{ count($users) }}</span>
                                                    <i class="bi bi-arrow-right-circle go"></i>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-light border d-flex align-items-center" role="alert">
                                        <i class="bi bi-info-circle me-2"></i>
                                        No Nijoga Gochhikar for today.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="panel mb-3">
                    <div class="panel-head d-flex align-items-center justify-content-between">
                        <div>
                            <div class="section-title">Quick Actions</div>
                            <div class="subtle">Frequently used filters & links.</div>
                        </div>
                    </div>
                    <div class="p-3 d-grid gap-2">
                        <a class="btn btn-outline-primary d-flex align-items-center justify-content-between" href="{{ route('admin.pratihari.filterUsers', 'today') }}">
                            <span><i class="bi bi-funnel me-2"></i> Filter Today</span>
                            <i class="bi bi-arrow-right-short fs-5"></i>
                        </a>
                        <a class="btn btn-outline-success d-flex align-items-center justify-content-between" href="{{ route('admin.pratihari.filterUsers', 'approved') }}">
                            <span><i class="bi bi-check2-circle me-2"></i> View Approved</span>
                            <i class="bi bi-arrow-right-short fs-5"></i>
                        </a>
                        <a class="btn btn-outline-warning d-flex align-items-center justify-content-between" href="{{ route('admin.pratihari.filterUsers', 'pending') }}">
                            <span><i class="bi bi-hourglass-split me-2"></i> View Pending</span>
                            <i class="bi bi-arrow-right-short fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- ===== KPI GRID ===== -->
            <div class="col-12 mt-1">
                <div class="row g-3">
                    <!-- Today’s Registrations -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi violet h-100">
                            <div class="meta">
                                <i class="bi bi-person-plus"></i>
                                Today’s Registrations
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.pratihari.filterUsers', 'today') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($todayProfiles) }}</div>
                            <div class="subtle">New profiles created</div>
                            <div class="kpi-progress"><span style="--p: 38%;"></span></div>
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
                        <div class="kpi amber h-100">
                            <div class="meta">
                                <i class="bi bi-hourglass-split"></i>
                                Pending Profiles
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.pratihari.filterUsers', 'pending') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($pendingProfile) }}</div>
                            <div class="subtle">Awaiting review</div>
                            <div class="kpi-progress"><span style="--p: 62%;"></span></div>
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
                        <div class="kpi emerald h-100">
                            <div class="meta">
                                <i class="bi bi-person-check"></i>
                                Active Profiles
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.pratihari.filterUsers', 'approved') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($totalActiveUsers) }}</div>
                            <div class="subtle">Approved & active</div>
                            <div class="kpi-progress"><span style="--p: 74%;"></span></div>
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
                        <div class="kpi cyan h-100">
                            <div class="meta">
                                <i class="bi bi-clipboard2-data"></i>
                                Incomplete Profiles
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.pratihari.filterUsers', 'incomplete') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($incompleteProfiles) }}</div>
                            <div class="subtle">Need more info</div>
                            <div class="kpi-progress"><span style="--p: 45%;"></span></div>
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
                        <div class="kpi blue h-100">
                            <div class="meta">
                                <i class="bi bi-check2-circle"></i>
                                Today Approved
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.pratihari.filterUsers', 'todayapproved') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($todayApprovedProfiles) }}</div>
                            <div class="subtle">Approved today</div>
                            <div class="kpi-progress"><span style="--p: 52%;"></span></div>
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
                        <div class="kpi rose h-100">
                            <div class="meta">
                                <i class="bi bi-x-circle"></i>
                                Today Rejected
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.pratihari.filterUsers', 'todayrejected') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($todayRejectedProfiles) }}</div>
                            <div class="subtle">Rejected today</div>
                            <div class="kpi-progress"><span style="--p: 30%;"></span></div>
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
                        <div class="kpi indigo h-100">
                            <div class="meta">
                                <i class="bi bi-arrow-repeat"></i>
                                Updated Profiles
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.pratihari.filterUsers', 'updated') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($updatedProfiles) }}</div>
                            <div class="subtle">Recently modified</div>
                            <div class="kpi-progress"><span style="--p: 66%;"></span></div>
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
                        <div class="kpi fuchsia h-100">
                            <div class="meta">
                                <i class="bi bi-emoji-frown"></i>
                                Total Rejected
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.pratihari.filterUsers', 'rejected') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($rejectedProfiles) }}</div>
                            <div class="subtle">All-time rejected</div>
                            <div class="kpi-progress"><span style="--p: 22%;"></span></div>
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
                        <div class="kpi cyan h-100">
                            <div class="meta">
                                <i class="bi bi-calendar-check"></i>
                                Today’s Applications
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.application.filter', 'today') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($todayApplications) }}</div>
                            <div class="subtle">Submitted today</div>
                            <div class="kpi-progress"><span style="--p: 48%;"></span></div>
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
                        <div class="kpi emerald h-100">
                            <div class="meta">
                                <i class="bi bi-file-earmark-check"></i>
                                Approved Applications
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.application.filter', 'approved') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($approvedApplication) }}</div>
                            <div class="subtle">Accepted</div>
                            <div class="kpi-progress"><span style="--p: 70%;"></span></div>
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
                        <div class="kpi rose h-100">
                            <div class="meta">
                                <i class="bi bi-file-earmark-x"></i>
                                Rejected Applications
                                <a class="ms-auto subtle text-decoration-none" href="{{ route('admin.application.filter', 'rejected') }}">View</a>
                            </div>
                            <div class="value mt-2">{{ count($rejectedApplication) }}</div>
                            <div class="subtle">Declined</div>
                            <div class="kpi-progress"><span style="--p: 28%;"></span></div>
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

    <!-- Reusable modal for showing lists -->
    <div class="modal fade" id="listModal" tabindex="-1" aria-labelledby="listModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold" id="listModalLabel">List</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:56px;">#</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                </tr>
                            </thead>
                            <tbody id="listModalBody">
                                <!-- filled by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- DataTables / helpers (keep if you use them) -->
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

    <!-- Select2, SweetAlert, Bootstrap -->
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Live clock
        (function tick() {
            const el = document.getElementById('live-time');
            if (el) el.textContent = new Date().toLocaleTimeString();
            setTimeout(tick, 1000);
        })();

        // Theme toggle with persistence
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

        // Preserve active tab via URL hash (left)
        (function(){
            const triggerTabList = [].slice.call(document.querySelectorAll('#sebaTabs button[data-bs-toggle="pill"]'));
            triggerTabList.forEach(function (triggerEl) {
                triggerEl.addEventListener('shown.bs.tab', function (event) {
                    const target = event.target.getAttribute('data-bs-target');
                    history.replaceState(null, '', target);
                });
            });
            const hash = window.location.hash;
            if (hash) {
                const tabTrigger = document.querySelector(`#sebaTabs button[data-bs-target="${hash}"]`);
                if (tabTrigger) new bootstrap.Tab(tabTrigger).show();
            }
        })();

        // Enable tooltips if any appear in other parts
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

        // Select2 (if user list provided)
        if (window.jQuery && $("#sebaUserSelect").length) {
            $("#sebaUserSelect").select2({ width: '100%', placeholder: "Select user…" });
        }

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
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
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
                        if (!reason) Swal.showValidationMessage('Reject reason is required');
                        return reason;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/pratihari/reject/${profileId}`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
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

        // ===== Summary card -> Modal list =====
        (function(){
            const modalEl = document.getElementById('listModal');
            const modal = new bootstrap.Modal(modalEl);
            const tbody = document.getElementById('listModalBody');
            const titleEl = document.getElementById('listModalLabel');

            function renderRows(users){
                if(!Array.isArray(users)) return '';
                return users.map((u, idx) => {
                    const name  = (u && u.name)  ? u.name  : '—';
                    const phone = (u && u.phone) ? u.phone : '—';
                    return `<tr>
                        <td class="text-muted">${idx+1}</td>
                        <td>${name}</td>
                        <td><a href="tel:${phone}">${phone}</a></td>
                    </tr>`;
                }).join('');
            }

            document.addEventListener('click', function(e){
                const card = e.target.closest('.summary-card');
                if(!card) return;
                e.preventDefault();

                titleEl.textContent = card.getAttribute('data-title') || 'List';
                let users = [];
                try { users = JSON.parse(card.getAttribute('data-users') || '[]'); } catch(e){ users = []; }
                tbody.innerHTML = renderRows(users);
                modal.show();
            });
        })();
    </script>
@endsection
