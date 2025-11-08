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
        :root {
            --brand-a: #7c3aed;
            --brand-b: #06b6d4;
            --c-violet: #8b5cf6;
            --c-cyan: #06b6d4;
            --c-emerald: #10b981;
            --c-amber: #f59e0b;
            --c-rose: #f43f5e;
            --c-blue: #3b82f6;
            --c-indigo: #4f46e5;
            --c-fuchsia: #d946ef;
            --ok: #16a34a;
            --warn: #f59e0b;
            --danger: #ef4444;
            --ink: #0b1220;
            --ink-soft: #334155;
            --muted: #64748b;
            --surface: #ffffff;
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
            --surface-2: #070b14;
            --panel: #0f172a;
            --border: rgba(148, 163, 184, .18);
            --ring: rgba(59, 130, 246, .35);
            --shadow: 0 16px 38px rgba(0, 0, 0, .45);
        }

        html, body { height: 100%; }

        body {
            font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial;
            background:
                radial-gradient(1100px 540px at -10% -10%, rgba(124, 58, 237, .10), transparent 60%),
                radial-gradient(1100px 540px at 110% -10%, rgba(6, 182, 212, .10), transparent 60%),
                var(--surface-2) fixed;
            color: var(--ink);
        }

        html.dark body {
            background:
                radial-gradient(1100px 540px at -10% -10%, rgba(124, 58, 237, .20), transparent 60%),
                radial-gradient(1100px 540px at 110% -10%, rgba(6, 182, 212, .16), transparent 60%),
                var(--surface) fixed;
        }

        * { outline-color: var(--brand-b); }
        :is(a, button, .panel, .kpi, .pill, .nav-link, .chip):focus-visible { box-shadow: 0 0 0 4px var(--ring); }

        /* Sticky app bar */
        .appbar {
            position: sticky;
            top: 0;
            z-index: 40;
            backdrop-filter: blur(10px);
            background: color-mix(in oklab, var(--panel) 80%, transparent);
            border-bottom: 1px solid var(--border);
        }

        .brand { font-weight: 800; letter-spacing: .3px; background: var(--g-brand); -webkit-background-clip: text; background-clip: text; color: transparent; }

        .btn-theme {
            display: inline-flex; align-items: center; gap: 8px;
            border: 1px solid var(--border); background: var(--panel);
            border-radius: 999px; padding: 8px 12px; font-weight: 700;
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .btn-theme:hover { transform: translateY(-1px); box-shadow: var(--shadow); }

        /* Panels */
        .panel { background: var(--panel); border: 1px solid var(--border); border-radius: 18px; box-shadow: var(--shadow); }
        .panel-head { padding: 14px 16px; border-bottom: 1px solid var(--border); background: var(--g-soft); border-top-left-radius: 18px; border-top-right-radius: 18px; }
        .section-title { font-weight: 800; letter-spacing: .2px; }
        .subtle { color: var(--muted); }
        .divider { height: 1px; background: var(--border); }

        /* Gradient overview header block */
        .gradient-header {
            position: relative; border-radius: 18px; padding: 24px;
            background: var(--g-brand); color: #fff; overflow: hidden; box-shadow: var(--shadow);
        }
        .gradient-header .bg-icons { position: absolute; inset: 0; pointer-events: none; opacity: .14;
            background: radial-gradient(160px 160px at 15% 40%, #fff 0, transparent 60%),
                        radial-gradient(130px 130px at 85% 30%, #fff 0, transparent 60%); mix-blend-mode: soft-light; }
        .icon-hero { display: inline-flex; place-content:center; width:46px; height:46px; border-radius:12px; background: rgba(255,255,255,.18); color:#fff; font-size:20px; border: 1px solid rgba(255,255,255,.35); }
        .title { font-weight: 800; letter-spacing: .3px; font-size: clamp(20px, 2.2vw, 26px); }
        .subtitle { color: rgba(255, 255, 255, .92); }

        /* Pills */
        .pill { display:inline-flex; align-items:center; gap:10px; padding:10px 14px; border-radius: 999px; font-weight:700; border:1px solid var(--border); background: var(--panel); color: var(--ink-soft); }
        .pill .dot { width:8px; height:8px; border-radius:999px; background: var(--brand-a); }

        /* Tabs */
        .tabs .nav-link { border: 1px solid var(--border); background: var(--panel); color: var(--ink-soft); font-weight: 800; border-radius: 12px; padding: .55rem 1rem; }
        .tabs .nav-link.active { color: #fff; background: var(--g-brand); border-color: transparent; }

        /* Horizontal strip */
        .strip {
            display: grid; grid-auto-flow: column; grid-auto-columns: clamp(140px, 24vw, 180px);
            gap: 14px; overflow-x: auto; padding: 2px 2px 10px; scroll-snap-type: x mandatory;
        }
        .strip::-webkit-scrollbar { height: 8px; }
        .strip::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
        html.dark .strip::-webkit-scrollbar-thumb { background: #475569; }

        /* Compact user card */
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
            display: flex; flex-direction: column; align-items: center; justify-content: flex-start; gap: 10px;
        }
        .user-card:hover { transform: translateY(-2px); box-shadow: var(--shadow); border-color: rgba(124, 58, 237, .35); }
        .avatar {
            width: 84px; height: 84px; border-radius: 16px; overflow: hidden;
            display: grid; place-items:center; background: #eef2ff; color:#4f46e5; font-weight: 800;
            border: 1px solid var(--border);
        }
        .avatar img { width: 100%; height: 100%; object-fit: cover; display:block; }
        .user-name { font-weight: 800; line-height: 1.2; }
        .user-meta { font-size: .8rem; color: var(--muted); }
        .badge-soft {
            border: 1px solid var(--border); background: #fff; color: var(--ink-soft);
            border-radius: 999px; padding: 2px 8px; font-size: .75rem; font-weight: 700;
        }

        /* Chips */
        .chip { display:inline-flex; align-items:center; gap:6px; padding:4px 10px; border-radius: 999px; font-size:.8rem; font-weight:700; border:1px solid var(--border); background: var(--panel); color: var(--ink-soft); }
        .chip.ok { color: var(--ok); background: rgba(16, 185, 129, .10); border-color: rgba(16, 185, 129, .25); }
        .chip.warn { color: var(--warn); background: rgba(245, 158, 11, .12); border-color: rgba(245, 158, 11, .25); }

        /* KPIs */
        .kpi { position: relative; background: var(--panel); border: 1px solid var(--border); border-radius: 16px; padding: 16px 16px 14px; box-shadow: var(--shadow); overflow: hidden; }
        .kpi::before { content: ''; position: absolute; inset: 0 0 auto 0; height: 6px; background: var(--kpi-grad, var(--g-brand)); }
        .kpi::after { content: ''; position: absolute; inset: auto -20% -20% -20%; height: 60%; background: radial-gradient(60% 40% at 80% 0%, color-mix(in oklab, var(--kpi-accent, #7c3aed) 28%, transparent), transparent 70%); opacity:.35; }
        .kpi .meta { display:flex; align-items:center; gap:10px; font-weight:700; color: var(--ink-soft); }
        .kpi .meta i { display: inline-grid; place-items:center; width: 34px; height: 34px; border-radius: 10px; background: var(--kpi-icon-bg, #eee); color: var(--kpi-icon, #444); }
        .kpi .value { font-size: clamp(1.8rem, 2.6vw, 2.3rem); font-weight: 800; letter-spacing: -.4px; color: var(--ink); }
        .kpi .subtle { color: var(--muted); }
        .kpi.violet { --kpi-grad: linear-gradient(90deg, #8b5cf6, #a78bfa); --kpi-icon-bg: rgba(139,92,246,.12); --kpi-icon:#8b5cf6; --kpi-accent:#8b5cf6; }
        .kpi.cyan   { --kpi-grad: linear-gradient(90deg, #06b6d4, #22d3ee); --kpi-icon-bg: rgba(6,182,212,.12); --kpi-icon:#06b6d4; --kpi-accent:#06b6d4; }
        .kpi.emerald{ --kpi-grad: linear-gradient(90deg, #10b981, #34d399); --kpi-icon-bg: rgba(16,185,129,.12); --kpi-icon:#10b981; --kpi-accent:#10b981; }
        .kpi.amber  { --kpi-grad: linear-gradient(90deg, #f59e0b, #fbbf24); --kpi-icon-bg: rgba(245,158,11,.12); --kpi-icon:#f59e0b; --kpi-accent:#f59e0b; }
        .kpi.rose   { --kpi-grad: linear-gradient(90deg, #f43f5e, #fb7185); --kpi-icon-bg: rgba(244,63,94,.12); --kpi-icon:#f43f5e; --kpi-accent:#f43f5e; }
        .kpi.blue   { --kpi-grad: linear-gradient(90deg, #3b82f6, #60a5fa); --kpi-icon-bg: rgba(59,130,246,.12); --kpi-icon:#3b82f6; --kpi-accent:#3b82f6; }
        .kpi.indigo { --kpi-grad: linear-gradient(90deg, #4f46e5, #818cf8); --kpi-icon-bg: rgba(79,70,229,.12); --kpi-icon:#4f46e5; --kpi-accent:#4f46e5; }
        .kpi.fuchsia{ --kpi-grad: linear-gradient(90deg, #d946ef, #f0abfc); --kpi-icon-bg: rgba(217,70,239,.12); --kpi-icon:#d946ef; --kpi-accent:#d946ef; }
        .kpi-progress { height: 6px; background: rgba(148,163,184,.3); border-radius: 999px; overflow: hidden; margin-top: 10px; }
        .kpi-progress>span { display:block; height:100%; width: var(--p,40%); background: var(--kpi-grad); border-radius:999px; }

        .mono { font-family: "JetBrains Mono", ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }

        @media (max-width: 576px) { .header-actions { margin-top: 12px; } }
    </style>
@endsection

@section('content')

    {{-- =========================
         Precompute JSON payloads
         ========================= --}}
    @php
        use Illuminate\Support\Facades\Storage;

        // Helper to avatar URL (public disk or absolute). Fallback: null.
        $avatarUrl = function($u) {
            $cand = $u->photo ?? $u->profile_photo ?? $u->profile_photo_path ?? $u->avatar ?? null;
            if (!$cand) return null;
            if (preg_match('/^https?:\/\//i', $cand)) return $cand;
            if (Storage::disk('public')->exists($cand)) return Storage::disk('public')->url($cand);
            return asset($cand);
        };

        // Initials for placeholder block
        $initials = function($u) {
            $f = trim($u->first_name ?? '');
            $l = trim($u->last_name ?? '');
            $fi = $f !== '' ? mb_substr($f,0,1) : '';
            $li = $l !== '' ? mb_substr($l,0,1) : '';
            $both = strtoupper($fi.$li);
            if ($both === '') {
                $name = trim(($u->name ?? '') ?: (($u->first_name ?? '').' '.($u->last_name ?? '')));
                $both = $name ? strtoupper(mb_substr($name,0,1)) : 'P';
            }
            return $both;
        };

        // Name formatter
        $fullName = function($u) {
            $n = trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? ''));
            return $n !== '' ? $n : ($u->name ?? '—');
        };

        // Map helpers used elsewhere (kept)
        $mapProfile = function($u) {
            return [
                'name'  => trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? '')),
                'phone' => $u->phone_no ?? '',
                'link'  => route('admin.viewProfile', $u->pratihari_id),
            ];
        };
        $mapProfileNoLink = function($u) {
            return [
                'name'  => trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? '')),
                'phone' => $u->phone_no ?? '',
            ];
        };
        $mapApplication = function($a) {
            return [
                'name'  => $a->header ?? 'N/A',
                'phone' => '',
                'meta'  => $a->date ?? '',
                'link'  => '',
            ];
        };

        // KPI arrays
        $arrTodayProfiles        = $todayProfiles->map($mapProfile)->values();
        $arrPendingProfiles      = $pendingProfile->map($mapProfile)->values();
        $arrActiveProfiles       = $totalActiveUsers->map($mapProfile)->values();
        $arrIncompleteProfiles   = $incompleteProfiles->map($mapProfile)->values();
        $arrTodayApproved        = $todayApprovedProfiles->map($mapProfile)->values();
        $arrTodayRejected        = $todayRejectedProfiles->map($mapProfile)->values();
        $arrUpdatedProfiles      = $updatedProfiles->map($mapProfile)->values();
        $arrRejectedAll          = $rejectedProfiles->map($mapProfile)->values();

        $arrAppsToday            = $todayApplications->map($mapApplication)->values();
        $arrAppsApproved         = $approvedApplication->map($mapApplication)->values();
        $arrAppsRejected         = $rejectedApplication->map($mapApplication)->values();
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
                    <a class="btn btn-sm btn-outline-light" href="{{ route('admin.pratihari.filterUsers', 'today') }}" style="color: white">
                        <i class="bi bi-funnel"></i> Today
                    </a>
                    <a class="btn btn-sm btn-outline-light" href="{{ route('admin.pratihari.filterUsers', 'approved') }}" style="color: white">
                        <i class="bi bi-check2-circle"></i> Approved
                    </a>
                    <a class="btn btn-sm btn-outline-light" href="{{ route('admin.pratihari.filterUsers', 'pending') }}" style="color: white">
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

            <!-- Left: Pratihari Seba & Nijoga (cards with photos) -->
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
                                @forelse ($pratihariEvents as $label => $entries)
                                    @php $count = count($entries); @endphp
                                    <div class="mb-3">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold">{{ $label }}</span>
                                                <span class="badge text-bg-light border">{{ $count }}</span>
                                            </div>
                                            <span class="chip ok"><i class="bi bi-calendar-event"></i> Today</span>
                                        </div>

                                        <div class="strip">
                                            @foreach($entries as $e)
                                                @php
                                                    $u = $e['profile'];
                                                    $name = $fullName($u);
                                                    $photo = $avatarUrl($u);
                                                    $ini = $initials($u);
                                                    $beddha = $e['beddha'] ?? null;
                                                    $by = $e['assigned_by'] ?? 'Unknown';
                                                @endphp
                                                <div class="user-card" title="{{ $name }}">
                                                    <div class="avatar">
                                                        @if($photo)
                                                            <img src="{{ $photo }}" alt="{{ $name }}">
                                                        @else
                                                            <span>{{ $ini }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="user-name text-truncate w-100">{{ $name }}</div>
                                                    @if($beddha !== null)
                                                        <div class="user-meta">Beddha: <span class="badge-soft">{{ $beddha }}</span></div>
                                                    @endif
                                                    <div class="user-meta">
                                                        {{ $u->phone_no ? '☎ ' . $u->phone_no : '' }}
                                                    </div>
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

                            <!-- Nijoga (Admin-assigned = beddha_status 0) -->
                            <div class="tab-pane fade" id="nijoga-pane" role="tabpanel" aria-labelledby="nijoga-tab">
                                @forelse ($nijogaAssign as $label => $entries)
                                    @php $count = count($entries); @endphp
                                    <div class="mb-3">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold">{{ $label }}</span>
                                                <span class="badge text-bg-light border">{{ $count }}</span>
                                            </div>
                                            <span class="chip warn"><i class="bi bi-clipboard2-pulse"></i> Nijoga</span>
                                        </div>

                                        <div class="strip">
                                            @foreach($entries as $e)
                                                @php
                                                    $u = $e['profile'];
                                                    $name = $fullName($u);
                                                    $photo = $avatarUrl($u);
                                                    $ini = $initials($u);
                                                    $beddha = $e['beddha'] ?? null;
                                                @endphp
                                                <div class="user-card" title="{{ $name }}">
                                                    <div class="avatar">
                                                        @if($photo)
                                                            <img src="{{ $photo }}" alt="{{ $name }}">
                                                        @else
                                                            <span>{{ $ini }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="user-name text-truncate w-100">{{ $name }}</div>
                                                    @if($beddha !== null)
                                                        <div class="user-meta">Beddha: <span class="badge-soft">{{ $beddha }}</span></div>
                                                    @endif
                                                    <div class="user-meta">
                                                        {{ $u->phone_no ? '☎ ' . $u->phone_no : '' }}
                                                    </div>
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

            <!-- Right: Gochhikar Today (cards with photos) -->
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
                                    <div class="mb-2">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div class="small fw-semibold">{{ $label }}</div>
                                            <span class="badge rounded-pill text-bg-light border">{{ count($users) }}</span>
                                        </div>
                                        <div class="strip">
                                            @foreach($users as $u)
                                                @php
                                                    $name = $fullName($u);
                                                    $photo = $avatarUrl($u);
                                                    $ini = $initials($u);
                                                @endphp
                                                <div class="user-card" title="{{ $name }}">
                                                    <div class="avatar">
                                                        @if($photo)
                                                            <img src="{{ $photo }}" alt="{{ $name }}">
                                                        @else
                                                            <span>{{ $ini }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="user-name text-truncate w-100">{{ $name }}</div>
                                                    <div class="user-meta">{{ $u->phone_no ? '☎ ' . $u->phone_no : '' }}</div>
                                                </div>
                                            @endforeach
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
                                    <div class="mb-2">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div class="small fw-semibold">{{ $label }}</div>
                                            <span class="badge rounded-pill text-bg-light border">{{ count($users) }}</span>
                                        </div>
                                        <div class="strip">
                                            @foreach($users as $u)
                                                @php
                                                    $name = $fullName($u);
                                                    $photo = $avatarUrl($u);
                                                    $ini = $initials($u);
                                                @endphp
                                                <div class="user-card" title="{{ $name }}">
                                                    <div class="avatar">
                                                        @if($photo)
                                                            <img src="{{ $photo }}" alt="{{ $name }}">
                                                        @else
                                                            <span>{{ $ini }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="user-name text-truncate w-100">{{ $name }}</div>
                                                    <div class="user-meta">{{ $u->phone_no ? '☎ ' . $u->phone_no : '' }}</div>
                                                </div>
                                            @endforeach
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
            </div>

            <!-- ===== KPI GRID (unchanged) ===== -->
            <div class="col-12 mt-1">
                <div class="row g-3">

                    <!-- Today’s Registrations -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi violet h-100">
                            <div class="meta">
                                <i class="bi bi-person-plus"></i>
                                Today’s Registrations
                                <a href="#"
                                   class="ms-auto subtle text-decoration-none kpi-viewall"
                                   data-title="Today’s Registrations"
                                   data-type="profiles"
                                   data-users='@json($arrTodayProfiles)'>
                                    View all
                                </a>
                            </div>
                            <div class="value mt-2">{{ count($todayProfiles) }}</div>
                            <div class="subtle">New profiles created</div>
                            <div class="kpi-progress"><span style="--p: 38%;"></span></div>
                        </div>
                    </div>

                    <!-- Pending Profiles -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi amber h-100">
                            <div class="meta">
                                <i class="bi bi-hourglass-split"></i>
                                Pending Profiles
                                <a href="#"
                                   class="ms-auto subtle text-decoration-none kpi-viewall"
                                   data-title="Pending Profiles"
                                   data-type="pending"
                                   data-users='@json($arrPendingProfiles)'>
                                    View all
                                </a>
                            </div>
                            <div class="value mt-2">{{ count($pendingProfile) }}</div>
                            <div class="subtle">Awaiting review</div>
                            <div class="kpi-progress"><span style="--p: 62%;"></span></div>
                        </div>
                    </div>

                    <!-- Active Profiles -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi emerald h-100">
                            <div class="meta">
                                <i class="bi bi-person-check"></i>
                                Active Profiles
                                <a href="#"
                                   class="ms-auto subtle text-decoration-none kpi-viewall"
                                   data-title="Active Profiles"
                                   data-type="approved"
                                   data-users='@json($arrActiveProfiles)'>
                                    View all
                                </a>
                            </div>
                            <div class="value mt-2">{{ count($totalActiveUsers) }}</div>
                            <div class="subtle">Approved & active</div>
                            <div class="kpi-progress"><span style="--p: 74%;"></span></div>
                        </div>
                    </div>

                    <!-- Incomplete Profiles -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi cyan h-100">
                            <div class="meta">
                                <i class="bi bi-clipboard2-data"></i>
                                Incomplete Profiles
                                <a href="#"
                                   class="ms-auto subtle text-decoration-none kpi-viewall"
                                   data-title="Incomplete Profiles"
                                   data-type="incomplete"
                                   data-users='@json($arrIncompleteProfiles)'>
                                    View all
                                </a>
                            </div>
                            <div class="value mt-2">{{ count($incompleteProfiles) }}</div>
                            <div class="subtle">Need more info</div>
                            <div class="kpi-progress"><span style="--p: 45%;"></span></div>
                        </div>
                    </div>

                    <!-- Today Approved -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi blue h-100">
                            <div class="meta">
                                <i class="bi bi-check2-circle"></i>
                                Today Approved
                                <a href="#"
                                   class="ms-auto subtle text-decoration-none kpi-viewall"
                                   data-title="Today Approved"
                                   data-type="todayapproved"
                                   data-users='@json($arrTodayApproved)'>
                                    View all
                                </a>
                            </div>
                            <div class="value mt-2">{{ count($todayApprovedProfiles) }}</div>
                            <div class="subtle">Approved today</div>
                            <div class="kpi-progress"><span style="--p: 52%;"></span></div>
                        </div>
                    </div>

                    <!-- Today Rejected -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi rose h-100">
                            <div class="meta">
                                <i class="bi bi-x-circle"></i>
                                Today Rejected
                                <a href="#"
                                   class="ms-auto subtle text-decoration-none kpi-viewall"
                                   data-title="Today Rejected"
                                   data-type="todayrejected"
                                   data-users='@json($arrTodayRejected)'>
                                    View all
                                </a>
                            </div>
                            <div class="value mt-2">{{ count($todayRejectedProfiles) }}</div>
                            <div class="subtle">Rejected today</div>
                            <div class="kpi-progress"><span style="--p: 30%;"></span></div>
                        </div>
                    </div>

                    <!-- Updated Profiles -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi indigo h-100">
                            <div class="meta">
                                <i class="bi bi-arrow-repeat"></i>
                                Updated Profiles
                                <a href="#"
                                   class="ms-auto subtle text-decoration-none kpi-viewall"
                                   data-title="Updated Profiles"
                                   data-type="updated"
                                   data-users='@json($arrUpdatedProfiles)'>
                                    View all
                                </a>
                            </div>
                            <div class="value mt-2">{{ count($updatedProfiles) }}</div>
                            <div class="subtle">Recently modified</div>
                            <div class="kpi-progress"><span style="--p: 66%;"></span></div>
                        </div>
                    </div>

                    <!-- Total Rejected -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi fuchsia h-100">
                            <div class="meta">
                                <i class="bi bi-emoji-frown"></i>
                                Total Rejected
                                <a href="#"
                                   class="ms-auto subtle text-decoration-none kpi-viewall"
                                   data-title="Total Rejected"
                                   data-type="rejected"
                                   data-users='@json($arrRejectedAll)'>
                                    View all
                                </a>
                            </div>
                            <div class="value mt-2">{{ count($rejectedProfiles) }}</div>
                            <div class="subtle">All-time rejected</div>
                            <div class="kpi-progress"><span style="--p: 22%;"></span></div>
                        </div>
                    </div>

                    <!-- Applications: Today -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi cyan h-100">
                            <div class="meta">
                                <i class="bi bi-calendar-check"></i>
                                Today’s Applications
                                <a href="#"
                                   class="ms-auto subtle text-decoration-none kpi-viewall"
                                   data-title="Today’s Applications"
                                   data-type="apps_today"
                                   data-users='@json($arrAppsToday)'>
                                    View all
                                </a>
                            </div>
                            <div class="value mt-2">{{ count($todayApplications) }}</div>
                            <div class="subtle">Submitted today</div>
                            <div class="kpi-progress"><span style="--p: 48%;"></span></div>
                        </div>
                    </div>

                    <!-- Applications: Approved -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi emerald h-100">
                            <div class="meta">
                                <i class="bi bi-file-earmark-check"></i>
                                Approved Applications
                                <a href="#"
                                   class="ms-auto subtle text-decoration-none kpi-viewall"
                                   data-title="Approved Applications"
                                   data-type="apps_approved"
                                   data-users='@json($arrAppsApproved)'>
                                    View all
                                </a>
                            </div>
                            <div class="value mt-2">{{ count($approvedApplication) }}</div>
                            <div class="subtle">Accepted</div>
                            <div class="kpi-progress"><span style="--p: 70%;"></span></div>
                        </div>
                    </div>

                    <!-- Applications: Rejected -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi rose h-100">
                            <div class="meta">
                                <i class="bi bi-file-earmark-x"></i>
                                Rejected Applications
                                <a href="#"
                                   class="ms-auto subtle text-decoration-none kpi-viewall"
                                   data-title="Rejected Applications"
                                   data-type="apps_rejected"
                                   data-users='@json($arrAppsRejected)'>
                                    View all
                                </a>
                            </div>
                            <div class="value mt-2">{{ count($rejectedApplication) }}</div>
                            <div class="subtle">Declined</div>
                            <div class="kpi-progress"><span style="--p: 28%;"></span></div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="col-12">
                        <div class="panel mb-3">
                            <div class="panel-head d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="section-title">Quick Actions</div>
                                    <div class="subtle">Frequently used filters & links.</div>
                                </div>
                            </div>
                            <div class="p-3 d-grid gap-2">
                                <a class="btn btn-outline-primary d-flex align-items-center justify-content-between"
                                   href="{{ route('admin.pratihari.filterUsers', 'today') }}">
                                    <span><i class="bi bi-funnel me-2"></i> Filter Today</span>
                                    <i class="bi bi-arrow-right-short fs-5"></i>
                                </a>
                                <a class="btn btn-outline-success d-flex align-items-center justify-content-between"
                                   href="{{ route('admin.pratihari.filterUsers', 'approved') }}">
                                    <span><i class="bi bi-check2-circle me-2"></i> View Approved</span>
                                    <i class="bi bi-arrow-right-short fs-5"></i>
                                </a>
                                <a class="btn btn-outline-warning d-flex align-items-center justify-content-between"
                                   href="{{ route('admin.pratihari.filterUsers', 'pending') }}">
                                    <span><i class="bi bi-hourglass-split me-2"></i> View Pending</span>
                                    <i class="bi bi-arrow-right-short fs-5"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                </div><!-- /row -->
            </div>

        </div>
    </div>

    <!-- Modal used by KPI tiles (kept) -->
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
                            <tbody id="kpiListModalBody"><!-- filled by JS --></tbody>
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
        (function themeInit() {
            const html = document.documentElement;
            const saved = localStorage.getItem('theme') || '';
            if (saved) {
                html.classList.toggle('dark', saved === 'dark');
            } else {
                const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                html.classList.toggle('dark', systemDark);
            }
            updateThemeButton();
        })();

        function updateThemeButton() {
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
        (function() {
            const triggerTabList = [].slice.call(document.querySelectorAll('#sebaTabs button[data-bs-toggle="pill"]'));
            triggerTabList.forEach(function(triggerEl) {
                triggerEl.addEventListener('shown.bs.tab', function(event) {
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

        // Enable tooltips (if any)
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

        // Select2 (if user list provided somewhere else)
        if (window.jQuery && $("#sebaUserSelect").length) {
            $("#sebaUserSelect").select2({ width: '100%', placeholder: "Select user…" });
        }

        // Approve / Reject handlers (kept; used elsewhere)
        document.addEventListener('click', function(e) {
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
                        }).then(r => r.json())
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
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({ reason: result.value })
                        }).then(r => r.json())
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

        // ===== KPI tiles -> Modal list (kpiListModal) =====
        (function(){
            document.addEventListener('click', function(e){
                const a = e.target.closest('.kpi-viewall');
                if(!a) return;

                e.preventDefault();
                const title = a.getAttribute('data-title') || 'List';
                const raw = a.getAttribute('data-users') || '[]';
                let rows = [];
                try { rows = JSON.parse(raw); } catch(_) { rows = []; }

                const modalEl = document.getElementById('kpiListModal');
                const modal = new bootstrap.Modal(modalEl);
                const titleEl = document.getElementById('kpiListModalLabel');
                const bodyEl = document.getElementById('kpiListModalBody');

                titleEl.textContent = title;

                bodyEl.innerHTML = rows.map((r, i) => {
                    const name  = r.name  || '—';
                    const phone = r.phone || '—';
                    const meta  = r.meta  || '';
                    const link  = r.link  || '';

                    const telBtn = phone && phone !== '—'
                        ? `<a class="btn btn-sm btn-outline-primary" href="tel:${phone}" title="Call"><i class="bi bi-telephone"></i></a>`
                        : `<button class="btn btn-sm btn-outline-secondary" disabled><i class="bi bi-telephone"></i></button>`;

                    const viewBtn = link
                        ? `<a class="btn btn-sm btn-outline-secondary" href="${link}"><i class="bi bi-box-arrow-up-right"></i></a>`
                        : `<button class="btn btn-sm btn-outline-secondary" disabled><i class="bi bi-box-arrow-up-right"></i></button>`;

                    return `
                        <tr>
                            <td class="text-muted">${i+1}</td>
                            <td>${name}</td>
                            <td class="d-none d-sm-table-cell">${meta}</td>
                            <td>${ phone !== '—' ? `<a href="tel:${phone}">${phone}</a>` : '—' }</td>
                            <td class="d-flex gap-1">${telBtn}${viewBtn}</td>
                        </tr>`;
                }).join('');

                modal.show();
            }, false);
        })();
    </script>
@endsection
