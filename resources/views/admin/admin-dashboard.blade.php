@extends('layouts.app')

@section('title', 'Pratihari Dashboard')

@section('styles')
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet" />

    <!-- (Optional) plugins you use elsewhere -->
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />

    <style>
        :root {
            --brand-a: #7c3aed;
            --brand-b: #06b6d4;
            --ink: #0b1220;
            --ink-soft: #334155;
            --muted: #64748b;
            --surface: #fff;
            --surface-2: #f7f8fb;
            --panel: #fff;
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

        html,
        body {
            height: 100%
        }

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

        * {
            outline-color: var(--brand-b)
        }

        :is(a, button, .panel, .kpi, .pill, .nav-link):focus-visible {
            box-shadow: 0 0 0 4px var(--ring)
        }

        /* Appbar */
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
            transition: transform .15s, box-shadow .15s;
        }

        .btn-theme:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow)
        }

        /* Panels */
        .panel {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: var(--shadow)
        }

        .panel-head {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            background: var(--g-soft);
            border-top-left-radius: 18px;
            border-top-right-radius: 18px
        }

        .section-title {
            font-weight: 800;
            letter-spacing: .2px
        }

        .subtle {
            color: var(--muted)
        }

        /* Header block */
        .gradient-header {
            position: relative;
            border-radius: 18px;
            padding: 24px;
            background: var(--g-brand);
            color: #fff;
            overflow: hidden;
            box-shadow: var(--shadow)
        }

        .gradient-header .bg-icons {
            position: absolute;
            inset: 0;
            pointer-events: none;
            opacity: .14;
            background: radial-gradient(160px 160px at 15% 40%, #fff 0, transparent 60%),
                radial-gradient(130px 130px at 85% 30%, #fff 0, transparent 60%);
            mix-blend-mode: soft-light
        }

        .icon-hero {
            display: inline-grid;
            place-items: center;
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .18);
            color: #fff;
            font-size: 20px;
            border: 1px solid rgba(255, 255, 255, .35)
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
            color: var(--ink-soft)
        }

        .pill .dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--brand-a)
        }

        /* Tabs */
        .tabs .nav-link {
            border: 1px solid var(--border);
            background: var(--panel);
            color: var(--ink-soft);
            font-weight: 800;
            border-radius: 12px;
            padding: .55rem 1rem
        }

        .tabs .nav-link.active {
            color: #fff;
            background: var(--g-brand);
            border-color: transparent
        }

        /* USER GRID (denser and smaller cards) */
        .usergrid-wrap {
            max-height: 460px;
            overflow: auto;
            padding: 2px
        }

        .usergrid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 12px
        }

        /* smaller */
        /* Make the whole card clickable (anchor) */
        .usercard {
            display: block;
            background: color-mix(in oklab, var(--panel) 94%, var(--surface-2));
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 10px 10px 12px;
            min-height: 130px;
            text-align: center;
            transition: transform .18s, box-shadow .18s, border-color .18s;
            box-shadow: none;
            color: inherit;
            text-decoration: none;
            cursor: pointer;
        }

        .usercard:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
            border-color: rgba(124, 58, 237, .35);
            color: inherit;
            text-decoration: none;
        }

        /* Smaller avatar */
        .photo-wrap {
            position: relative;
            width: 64px;
            height: 64px;
            border-radius: 9999px;
            overflow: hidden;
            border: 1px solid var(--border);
            background: #f1f5f9;
            margin: 6px auto 8px;
        }

        .photo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .20s ease
        }

        .usercard:hover .photo-wrap img {
            transform: scale(1.4)
        }

        /* softer zoom for smaller circle */

        .uname {
            font-weight: 800;
            line-height: 1.1;
            text-align: center;
            font-size: .9rem;
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        /* KPI */
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
            background: var(--kpi-grad, var(--g-brand))
        }

        .kpi .meta {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            color: var(--ink-soft)
        }

        .kpi .meta i {
            display: inline-grid;
            place-items: center;
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: #eee;
            color: #444
        }

        .kpi .value {
            font-size: clamp(1.8rem, 2.6vw, 2.3rem);
            font-weight: 800;
            letter-spacing: -.4px;
            color: var(--ink)
        }

        .kpi .subtle {
            color: var(--muted)
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
            background: var(--kpi-grad);
            border-radius: 999px
        }

        .kpi.violet {
            --kpi-grad: linear-gradient(90deg, #8b5cf6, #a78bfa)
        }

        .kpi.cyan {
            --kpi-grad: linear-gradient(90deg, #06b6d4, #22d3ee)
        }

        .kpi.emerald {
            --kpi-grad: linear-gradient(90deg, #10b981, #34d399)
        }

        .kpi.amber {
            --kpi-grad: linear-gradient(90deg, #f59e0b, #fbbf24)
        }

        .kpi.rose {
            --kpi-grad: linear-gradient(90deg, #f43f5e, #fb7185)
        }

        .kpi.blue {
            --kpi-grad: linear-gradient(90deg, #3b82f6, #60a5fa)
        }

        .kpi.indigo {
            --kpi-grad: linear-gradient(90deg, #4f46e5, #818cf8)
        }

        .kpi.fuchsia {
            --kpi-grad: linear-gradient(90deg, #d946ef, #f0abfc)
        }

        @media (max-width:576px) {
            .usergrid-wrap {
                max-height: 400px
            }
        }
    </style>
@endsection

@section('content')
    @php
        // Small helpers for display text
        $fullName = function ($u) {
            $n = trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? ''));
            return $n !== '' ? $n : $u->name ?? '—';
        };
        $initials = function ($u) {
            $f = trim($u->first_name ?? '');
            $l = trim($u->last_name ?? '');
            $both = strtoupper(($f ? mb_substr($f, 0, 1) : '') . ($l ? mb_substr($l, 0, 1) : ''));
            if ($both === '') {
                $n = trim($u->name ?? '' ?: ($u->first_name ?? '') . ' ' . ($u->last_name ?? ''));
                $both = $n ? strtoupper(mb_substr($n, 0, 1)) : 'P';
            }
            return $both;
        };
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
                <div class="d-none d-sm-flex align-items-center gap-2 subtle">
                    <i class="bi bi-clock-history"></i><span id="live-time"></span>
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
            <div class="bg-icons"></div>
            <div class="d-flex align-items-start align-items-md-center gap-3 flex-column flex-md-row">
                <span class="icon-hero"><i class="bi bi-speedometer2"></i></span>
                <div class="flex-grow-1">
                    <div class="fw-bold fs-5">Pratihari Admin Dashboard</div>
                    <div class="small">Rounded avatars with hover zoom. Clean, scrollable grids.</div>
                </div>
                <div class="d-flex gap-2 ms-md-auto">
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

            <div class="d-flex flex-wrap align-items-center gap-2 mt-3">
                <span class="pill"><i class="bi bi-badge-ad"></i><strong class="ms-1 me-1">Pratihari
                        Beddha:</strong>{{ $pratihariBeddha ?: 'N/A' }}</span>
                <span class="pill"><i class="bi bi-people"></i><strong class="ms-1 me-1">Gochhikar
                        Beddha:</strong>{{ $gochhikarBeddha ?: 'N/A' }}</span>
            </div>
        </div>

        <div class="row g-3">
            <!-- LEFT: Pratihari & Nijoga -->
            <div class="col-12 col-xl-8">
                <div class="panel">
                    <div class="px-3 pt-3">
                        <div class="rounded-3 p-3 text-white" style="background:var(--g-brand);box-shadow:var(--shadow);">
                            <div class="d-flex align-items-center gap-2">
                                <span class="icon-hero" style="background:transparent;border-color:rgba(255,255,255,.35);">
                                    <i class="bi bi-person-badge"></i>
                                </span>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Today’s Pratihari Seba</div>
                                    <div class="small" style="opacity:.9;">Assigned users (grid · scroll inside)</div>
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
                        style="background:linear-gradient(180deg,rgba(124,58,237,.06),rgba(6,182,212,.06));border-bottom-left-radius:18px;border-bottom-right-radius:18px;">
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
                            <div class="tab-pane fade show active" id="pratihari-pane" role="tabpanel"
                                aria-labelledby="pratihari-tab">
                                @forelse ($pratihariEvents as $label => $entries)
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-semibold">{{ $label }}</span>
                                                <span class="badge text-bg-light border">{{ count($entries) }}</span>
                                            </div>
                                        </div>
                                        <div class="usergrid-wrap">
                                            <div class="usergrid">
                                                @foreach ($entries as $e)
                                                    @php
                                                        $u = $e['profile'];
                                                        $name = $fullName($u);
                                                        $photo = $u->photo_url ?? null;
                                                        $ini = $initials($u);
                                                    @endphp
                                                    <a href="{{ route('admin.viewProfile', ['pratihari_id' => $u->pratihari_id]) }}"
                                                        class="usercard" title="{{ $name }}">
                                                        <div class="photo-wrap">
                                                            @if ($photo)
                                                                <img src="{{ $photo }}"
                                                                    alt="{{ $name }}">
                                                            @else
                                                                <img src="https://placehold.co/160x160?text={{ urlencode($ini) }}"
                                                                    alt="{{ $name }}">
                                                            @endif
                                                        </div>
                                                        <div class="uname">{{ $name }}</div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-light border d-flex align-items-center">
                                        <i class="bi bi-info-circle me-2"></i>No seba assigned for today.
                                    </div>
                                @endforelse
                            </div>

                            <!-- Nijoga -->
                            <div class="tab-pane fade" id="nijoga-pane" role="tabpanel" aria-labelledby="nijoga-tab">
                                @forelse ($nijogaAssign as $label => $entries)
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-semibold">{{ $label }}</span>
                                                <span class="badge text-bg-light border">{{ count($entries) }}</span>
                                            </div>
                                        </div>
                                        <div class="usergrid-wrap">
                                            <div class="usergrid">
                                                @foreach ($entries as $e)
                                                    @php
                                                        $u = $e['profile'];
                                                        $name = $fullName($u);
                                                        $photo = $u->photo_url ?? null;
                                                        $ini = $initials($u);
                                                    @endphp
                                                    <a href="{{ route('admin.viewProfile', ['pratihari_id' => $u->pratihari_id]) }}"
                                                        class="usercard" title="{{ $name }}">
                                                        <div class="photo-wrap">
                                                            @if ($photo)
                                                                <img src="{{ $photo }}"
                                                                    alt="{{ $name }}">
                                                            @else
                                                                <img src="https://placehold.co/160x160?text={{ urlencode($ini) }}"
                                                                    alt="{{ $name }}">
                                                            @endif
                                                        </div>
                                                        <div class="uname">{{ $name }}</div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-light border d-flex align-items-center">
                                        <i class="bi bi-info-circle me-2"></i>No nijoga seba assigned for today.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Gochhikar -->
            <div class="col-12 col-xl-4">
                <div class="panel">
                    <div class="px-3 pt-3">
                        <div class="rounded-3 p-3 text-white" style="background:var(--g-brand);box-shadow:var(--shadow);">
                            <div class="d-flex align-items-center gap-2">
                                <span class="icon-hero"
                                    style="background:transparent;border-color:rgba(255,255,255,.35);">
                                    <i class="bi bi-people"></i>
                                </span>
                                @php
                                    $gochhikarCount = collect($gochhikarEvents)->flatten(1)->count();
                                    $nijogaCount = collect($nijogaGochhikarEvents)->flatten(1)->count();
                                @endphp
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Gochhikar Today</div>
                                    <div class="small" style="opacity:.9;">Normal & Nijoga lists</div>
                                </div>
                                <span class="badge bg-light text-dark border-0">{{ $gochhikarCount + $nijogaCount }}
                                    total</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-3">
                        <ul class="nav nav-pills tabs gap-2 mb-3" id="gochhikarTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active d-flex align-items-center gap-2" id="gochhikar-tab"
                                    data-bs-toggle="pill" data-bs-target="#gochhikar-pane" type="button" role="tab"
                                    aria-controls="gochhikar-pane" aria-selected="true">
                                    <i class="bi bi-check2-circle"></i><span>Gochhikar</span>
                                    <span class="badge text-bg-light border ms-1">{{ $gochhikarCount }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center gap-2" id="nijoga-g-tab"
                                    data-bs-toggle="pill" data-bs-target="#nijoga-g-pane" type="button" role="tab"
                                    aria-controls="nijoga-g-pane" aria-selected="false">
                                    <i class="bi bi-exclamation-circle"></i><span>Nijoga Assign</span>
                                    <span class="badge text-bg-light border ms-1">{{ $nijogaCount }}</span>
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="gochhikarTabsContent">
                            <!-- Normal -->
                            <div class="tab-pane fade show active" id="gochhikar-pane" role="tabpanel"
                                aria-labelledby="gochhikar-tab">
                                @forelse ($gochhikarEvents as $label => $users)
                                    <div class="mb-3">
                                        <div class=" d-flex align-items-center justify-content-between mb-2">
                                            <div class="small fw-semibold">{{ $label }}</div>
                                            <span
                                                class="badge rounded-pill text-bg-light border">{{ count($users) }}</span>
                                        </div>
                                        <div class="usergrid-wrap">
                                            <div class="usergrid">
                                                @foreach ($users as $u)
                                                    @php
                                                        $name = $fullName($u);
                                                        $photo = $u->photo_url ?? null;
                                                        $ini = $initials($u);
                                                    @endphp
                                                    <a href="{{ route('admin.viewProfile', ['pratihari_id' => $u->pratihari_id]) }}"
                                                        class="usercard" title="{{ $name }}">
                                                        <div class="photo-wrap">
                                                            @if ($photo)
                                                                <img src="{{ $photo }}"
                                                                    alt="{{ $name }}">
                                                            @else
                                                                <img src="https://placehold.co/160x160?text={{ urlencode($ini) }}"
                                                                    alt="{{ $name }}">
                                                            @endif
                                                        </div>
                                                        <div class="uname">{{ $name }}</div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-light border d-flex align-items-center">
                                        <i class="bi bi-info-circle me-2"></i>No Gochhikar assigned (normal) for today.
                                    </div>
                                @endforelse
                            </div>

                            <!-- Nijoga -->
                            <div class="tab-pane fade" id="nijoga-g-pane" role="tabpanel"
                                aria-labelledby="nijoga-g-tab">
                                @forelse ($nijogaGochhikarEvents as $label => $users)
                                    <div class="mb-3">
                                        <div class=" d-flex align-items-center justify-content-between mb-2">
                                            <div class="small fw-semibold">{{ $label }}</div>
                                            <span
                                                class="badge rounded-pill text-bg-light border">{{ count($users) }}</span>
                                        </div>
                                        <div class="usergrid-wrap">
                                            <div class="usergrid">
                                                @foreach ($users as $u)
                                                    @php
                                                        $name = $fullName($u);
                                                        $photo = $u->photo_url ?? null;
                                                        $ini = $initials($u);
                                                    @endphp
                                                    <a href="{{ route('admin.viewProfile', ['pratihari_id' => $u->pratihari_id]) }}"
                                                        class="usercard" title="{{ $name }}">
                                                        <div class="photo-wrap">
                                                            @if ($photo)
                                                                <img src="{{ $photo }}"
                                                                    alt="{{ $name }}">
                                                            @else
                                                                <img src="https://placehold.co/160x160?text={{ urlencode($ini) }}"
                                                                    alt="{{ $name }}">
                                                            @endif
                                                        </div>
                                                        <div class="uname">{{ $name }}</div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-light border d-flex align-items-center">
                                        <i class="bi bi-info-circle me-2"></i>No Nijoga Gochhikar for today.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI GRID (unchanged counts) -->
            <div class="col-12 mt-4">
                <div class="row g-3">
                    <a href="{{ route('admin.pratihari.filterUsers', 'today') }}">
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="kpi violet h-100">
                                <div class="meta"><i class="bi bi-person-plus"></i> Today’s Registrations</div>

                                <div class="value mt-2">{{ count($todayProfiles) }}</div>
                                <div class="subtle">New profiles created</div>
                                <div class="kpi-progress"><span style="--p:38%"></span></div>
                            </div>
                        </div>
                    </a>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi amber h-100">
                            <div class="meta"><i class="bi bi-hourglass-split"></i> Pending Profiles</div>
                            <div class="value mt-2">{{ count($pendingProfile) }}</div>
                            <div class="subtle">Awaiting review</div>
                            <div class="kpi-progress"><span style="--p:62%"></span></div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi emerald h-100">
                            <div class="meta"><i class="bi bi-person-check"></i> Active Profiles</div>
                            <div class="value mt-2">{{ count($totalActiveUsers) }}</div>
                            <div class="subtle">Approved & active</div>
                            <div class="kpi-progress"><span style="--p:74%"></span></div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi cyan h-100">
                            <div class="meta"><i class="bi bi-clipboard2-data"></i> Incomplete Profiles</div>
                            <div class="value mt-2">{{ count($incompleteProfiles) }}</div>
                            <div class="subtle">Need more info</div>
                            <div class="kpi-progress"><span style="--p:45%"></span></div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi blue h-100">
                            <div class="meta"><i class="bi bi-check2-circle"></i> Today Approved</div>
                            <div class="value mt-2">{{ count($todayApprovedProfiles) }}</div>
                            <div class="subtle">Approved today</div>
                            <div class="kpi-progress"><span style="--p:52%"></span></div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi rose h-100">
                            <div class="meta"><i class="bi bi-x-circle"></i> Today Rejected</div>
                            <div class="value mt-2">{{ count($todayRejectedProfiles) }}</div>
                            <div class="subtle">Rejected today</div>
                            <div class="kpi-progress"><span style="--p:30%"></span></div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi indigo h-100">
                            <div class="meta"><i class="bi bi-arrow-repeat"></i> Updated Profiles</div>
                            <div class="value mt-2">{{ count($updatedProfiles) }}</div>
                            <div class="subtle">Recently modified</div>
                            <div class="kpi-progress"><span style="--p:66%"></span></div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi fuchsia h-100">
                            <div class="meta"><i class="bi bi-emoji-frown"></i> Total Rejected</div>
                            <div class="value mt-2">{{ count($rejectedProfiles) }}</div>
                            <div class="subtle">All-time rejected</div>
                            <div class="kpi-progress"><span style="--p:22%"></span></div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi cyan h-100">
                            <div class="meta"><i class="bi bi-calendar-check"></i> Today’s Applications</div>
                            <div class="value mt-2">{{ count($todayApplications) }}</div>
                            <div class="subtle">Submitted today</div>
                            <div class="kpi-progress"><span style="--p:48%"></span></div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi emerald h-100">
                            <div class="meta"><i class="bi bi-file-earmark-check"></i> Approved Applications</div>
                            <div class="value mt-2">{{ count($approvedApplication) }}</div>
                            <div class="subtle">Accepted</div>
                            <div class="kpi-progress"><span style="--p:70%"></span></div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="kpi rose h-100">
                            <div class="meta"><i class="bi bi-file-earmark-x"></i> Rejected Applications</div>
                            <div class="value mt-2">{{ count($rejectedApplication) }}</div>
                            <div class="subtle">Declined</div>
                            <div class="kpi-progress"><span style="--p:28%"></span></div>
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
@endsection

@section('scripts')
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

        // Theme toggle
        (function() {
            const html = document.documentElement;
            const saved = localStorage.getItem('theme') || '';
            html.classList.toggle('dark', saved ? saved === 'dark' : window.matchMedia('(prefers-color-scheme: dark)')
                .matches);
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

        // Restore tabs via hash (optional)
        (function() {
            const groups = ['#sebaTabs', '#gochhikarTabs'];
            groups.forEach(sel => {
                document.querySelectorAll(`${sel} button[data-bs-toggle="pill"]`).forEach(btn => {
                    btn.addEventListener('shown.bs.tab', e => {
                        const target = e.target.getAttribute('data-bs-target');
                        history.replaceState(null, '', target);
                    });
                });
            });
            const hash = window.location.hash;
            if (hash) {
                const btn = document.querySelector(`button[data-bs-target="${hash}"]`);
                if (btn) new bootstrap.Tab(btn).show();
            }
        })();
    </script>
@endsection
