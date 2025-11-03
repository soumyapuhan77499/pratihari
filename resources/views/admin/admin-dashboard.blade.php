@extends('layouts.app')

@section('styles')
    <!-- Icons & vendor CSS you already use -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --brand-1: #6a11cb;
            /* primary */
            --brand-2: #2575fc;
            /* secondary */
            --brand-3: #f8c66d;
            /* accent */
            --ok: #1db954;
            --warn: #f59f00;
            --danger: #e03131;
            --muted: #6c757d;

            --surface: #ffffff;
            --surface-soft: #f8f9fb;
            --ink: #1f2937;
            --ink-soft: #3d4a5d;
            --border: #e6e8ef;
            --shadow: 0 10px 24px rgba(16, 24, 40, .06);
        }

        /* Page shell */
        body {
            background: linear-gradient(180deg, #eef2f9 0%, #fbfbfd 100%) no-repeat fixed;
        }

        /* Cards */
        .ux-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow);
        }

        .ux-card__head {
            border-bottom: 1px solid var(--border);
            padding: 14px 18px;
            background: linear-gradient(90deg, rgba(106, 17, 203, .08), rgba(37, 117, 252, .08));
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }

        .ux-title {
            font-weight: 700;
            color: var(--ink);
            letter-spacing: .2px;
        }

        .ux-subtle {
            color: var(--muted);
        }

        /* Top banner */
        .banner {
            background: radial-gradient(110% 160% at 0% 0%, #f8f9ff 0%, #eef4ff 45%, #ffffff 100%);
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: var(--shadow);
            padding: 18px;
        }

        .banner-pill {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 999px;
            padding: 6px 10px;
            display: inline-flex;
            gap: 8px;
            align-items: center;
            font-weight: 600;
        }

        /* Stat grid */
        .stat {
            position: relative;
            overflow: hidden;
            padding: 16px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .stat:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .stat .badge-pill {
            border-radius: 999px;
            background: rgba(106, 17, 203, .08);
            color: var(--brand-1);
            font-weight: 600;
            padding: 6px 10px;
            font-size: .85rem;
        }

        .stat .count {
            font-size: 2rem;
            font-weight: 800;
            color: var(--ink);
            line-height: 1.1;
        }

        .stat .label {
            color: var(--ink-soft);
            font-weight: 600;
        }

        /* Seba user strip */
        .seba-strip {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: max-content;
            gap: 14px;
            overflow-x: auto;
            padding-bottom: 4px;
            scroll-snap-type: x mandatory;
        }

        .seba-card {
            scroll-snap-align: start;
            min-width: 220px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--surface-soft);
            padding: 12px;
        }

        .seba-card .avatar {
            width: 54px;
            height: 54px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: var(--shadow);
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 600;
            background: #fff;
            border: 1px solid var(--border);
            color: var(--ink-soft);
        }

        .chip.ok {
            color: var(--ok);
            border-color: rgba(29, 185, 84, .35);
            background: rgba(29, 185, 84, .06);
        }

        .chip.warn {
            color: var(--warn);
            border-color: rgba(245, 159, 0, .35);
            background: rgba(245, 159, 0, .08);
        }

        .chip.danger {
            color: var(--danger);
            border-color: rgba(224, 49, 49, .35);
            background: rgba(224, 49, 49, .08);
        }

        /* Tab pills */
        .nav-modern .nav-link {
            border: 1px solid var(--border);
            background: #fff;
            color: var(--ink-soft);
            font-weight: 700;
            border-radius: 12px;
            padding: .6rem 1rem;
        }

        .nav-modern .nav-link.active {
            color: #fff;
            background: linear-gradient(90deg, var(--brand-1), var(--brand-2));
            border-color: transparent;
            box-shadow: var(--shadow);
        }

        /* Lists */
        .user-list {
            max-height: 300px;
            overflow: auto;
        }

        .user-item:hover {
            background: #fafbff;
        }

        /* Helpers */
        .text-gradient {
            background: linear-gradient(90deg, var(--brand-1), var(--brand-2));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .divider {
            height: 1px;
            background: var(--border);
            margin: 8px 0;
        }

        .small-muted {
            font-size: .85rem;
            color: var(--muted);
        }

        /* Make scrollbars slimmer (WebKit) */
        .seba-strip::-webkit-scrollbar,
        .user-list::-webkit-scrollbar {
            height: 8px;
            width: 8px;
        }

        .seba-strip::-webkit-scrollbar-thumb,
        .user-list::-webkit-scrollbar-thumb {
            background: #d7d9e0;
            border-radius: 8px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-3">
        <!-- Banner -->
        <div class="banner mb-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="banner-pill">
                        <i class="bi bi-calendar-week"></i>
                        <span>{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
                    </div>
                    <div class="banner-pill">
                        <i class="bi bi-badge-ad"></i>
                        <span class="text-gradient fw-bold">Pratihari Beddha: {{ $pratihariBeddha ?: 'N/A' }}</span>
                    </div>
                    <div class="banner-pill">
                        <i class="bi bi-people"></i>
                        <span class="text-gradient fw-bold">Gochhikar Beddha: {{ $gochhikarBeddha ?: 'N/A' }}</span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 small-muted">
                    <i class="bi bi-clock-history"></i>
                    <span id="live-time"></span>
                </div>
            </div>
        </div>

        <!-- Top content row -->
        <div class="row g-3">
            <!-- Left: Pratihari panel -->
            <div class="col-12 col-xl-8">
                <div class="ux-card h-100">
                    <div class="ux-card__head d-flex align-items-center justify-content-between">
                        <div>
                            <div class="ux-title">Today’s Pratihari Seba</div>
                            <div class="small-muted">See assigned seba users and Nijoga (if any) for today.</div>
                        </div>
                    </div>

                    <div class="p-3">
                        <ul class="nav nav-pills nav-modern gap-2" id="sebaTabs" role="tablist">
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

                        <div class="tab-content mt-3" id="sebaTabsContent">
                            <!-- Pratihari -->
                            <div class="tab-pane fade show active" id="pratihari-pane" role="tabpanel"
                                aria-labelledby="pratihari-tab">
                                @forelse ($pratihariEvents as $label => $pratiharis)
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="fw-bold">{{ $label }}</span>
                                            <span class="chip ok"><i class="bi bi-calendar-event"></i> Today</span>
                                        </div>
                                        <div class="seba-strip">
                                            @foreach ($pratiharis as $user)
                                                @include('partials._user_card', ['user' => $user])
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
                                        <div class="seba-strip">
                                            @foreach ($nojoga as $user)
                                                @include('partials._user_card', ['user' => $user])
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

            <!-- Right: Gochhikar panel -->
            <div class="col-12 col-xl-4">
                <div class="ux-card h-100">
                    <div class="ux-card__head">
                        <div class="ux-title">Gochhikar Today</div>
                        <div class="small-muted">Normal & Nijoga assignments.</div>
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
                                    <div class="seba-strip">
                                        @foreach ($users as $user)
                                            @include('partials._user_card', ['user' => $user])
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-light border" role="alert">
                                    No Gochhikar assigned (normal) for today.
                                </div>
                            @endforelse
                        </div>

                        <div class="divider"></div>

                        <div class="mb-2">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-bold">Nijoga Assign</span>
                                <span class="chip warn"><i class="bi bi-exclamation-circle"></i> Nijoga</span>
                            </div>
                            @forelse ($nijogaGochhikarEvents as $label => $users)
                                <div class="mb-2">
                                    <div class="small fw-semibold mb-1">{{ $label }}</div>
                                    <div class="seba-strip">
                                        @foreach ($users as $user)
                                            @include('partials._user_card', ['user' => $user])
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

            <!-- Stat tiles -->
            <div class="col-12 mt-2">
                <div class="row g-3">
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="badge-pill"><i class="bi bi-person-plus"></i> Today’s Registrations</span>
                                <a class="small-muted text-decoration-none"
                                    href="{{ route('admin.pratihari.filterUsers', 'today') }}">View</a>
                            </div>
                            <div class="count mt-2">{{ count($todayProfiles) }}</div>
                            <div class="label">New profiles created</div>
                            <div class="user-list mt-2">
                                @foreach ($todayProfiles->take(5) as $user)
                                    <div
                                        class="user-item d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <img class="rounded-circle"
                                                src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}"
                                                alt="Profile" width="36" height="36">
                                            <div>
                                                <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}"
                                                    class="fw-semibold text-decoration-none">{{ $user->first_name }}
                                                    {{ $user->last_name }}</a>
                                                <div class="small-muted">{{ $user->phone_no }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="badge-pill"><i class="bi bi-hourglass-split"></i> Pending Profiles</span>
                                <a class="small-muted text-decoration-none"
                                    href="{{ route('admin.pratihari.filterUsers', 'pending') }}">View</a>
                            </div>
                            <div class="count mt-2">{{ count($pendingProfile) }}</div>
                            <div class="label">Awaiting review</div>
                            <div class="user-list mt-2">
                                @foreach ($pendingProfile->take(5) as $user)
                                    <div
                                        class="user-item d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <img class="rounded-circle"
                                                src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}"
                                                alt="Profile" width="36" height="36">
                                            <div>
                                                <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}"
                                                    class="fw-semibold text-decoration-none">{{ $user->first_name }}
                                                    {{ $user->last_name }}</a>
                                                <div class="small-muted">{{ $user->phone_no }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="badge-pill"><i class="bi bi-person-check"></i> Active Profiles</span>
                                <a class="small-muted text-decoration-none"
                                    href="{{ route('admin.pratihari.filterUsers', 'approved') }}">View</a>
                            </div>
                            <div class="count mt-2">{{ count($totalActiveUsers) }}</div>
                            <div class="label">Approved & active</div>
                            <div class="user-list mt-2">
                                @foreach ($totalActiveUsers->take(5) as $user)
                                    <div
                                        class="user-item d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <img class="rounded-circle"
                                                src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}"
                                                alt="Profile" width="36" height="36">
                                            <div>
                                                <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}"
                                                    class="fw-semibold text-decoration-none">{{ $user->first_name }}
                                                    {{ $user->last_name }}</a>
                                                <div class="small-muted">{{ $user->phone_no }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="badge-pill"><i class="bi bi-clipboard2-data"></i> Incomplete Profiles</span>
                                <a class="small-muted text-decoration-none"
                                    href="{{ route('admin.pratihari.filterUsers', 'incomplete') }}">View</a>
                            </div>
                            <div class="count mt-2">{{ count($incompleteProfiles) }}</div>
                            <div class="label">Need more info</div>
                            <div class="user-list mt-2">
                                @foreach ($incompleteProfiles->take(5) as $user)
                                    <div
                                        class="user-item d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <img class="rounded-circle"
                                                src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}"
                                                alt="Profile" width="36" height="36">
                                            <div>
                                                <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}"
                                                    class="fw-semibold text-decoration-none">{{ $user->first_name }}
                                                    {{ $user->last_name }}</a>
                                                <div class="small-muted">{{ $user->phone_no }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="badge-pill"><i class="bi bi-check2-circle"></i> Today Approved</span>
                                <a class="small-muted text-decoration-none"
                                    href="{{ route('admin.pratihari.filterUsers', 'todayapproved') }}">View</a>
                            </div>
                            <div class="count mt-2">{{ count($todayApprovedProfiles) }}</div>
                            <div class="label">Approved today</div>
                            <div class="user-list mt-2">
                                @foreach ($todayApprovedProfiles->take(5) as $user)
                                    <div
                                        class="user-item d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <img class="rounded-circle"
                                                src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}"
                                                alt="Profile" width="36" height="36">
                                            <div>
                                                <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}"
                                                    class="fw-semibold text-decoration-none">{{ $user->first_name }}
                                                    {{ $user->last_name }}</a>
                                                <div class="small-muted">{{ $user->phone_no }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="badge-pill"><i class="bi bi-x-circle"></i> Today Rejected</span>
                                <a class="small-muted text-decoration-none"
                                    href="{{ route('admin.pratihari.filterUsers', 'todayrejected') }}">View</a>
                            </div>
                            <div class="count mt-2">{{ count($todayRejectedProfiles) }}</div>
                            <div class="label">Rejected today</div>
                            <div class="user-list mt-2">
                                @foreach ($todayRejectedProfiles->take(5) as $user)
                                    <div
                                        class="user-item d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <img class="rounded-circle"
                                                src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}"
                                                alt="Profile" width="36" height="36">
                                            <div>
                                                <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}"
                                                    class="fw-semibold text-decoration-none">{{ $user->first_name }}
                                                    {{ $user->last_name }}</a>
                                                <div class="small-muted">{{ $user->phone_no }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="badge-pill"><i class="bi bi-arrow-repeat"></i> Updated Profiles</span>
                                <a class="small-muted text-decoration-none"
                                    href="{{ route('admin.pratihari.filterUsers', 'updated') }}">View</a>
                            </div>
                            <div class="count mt-2">{{ count($updatedProfiles) }}</div>
                            <div class="label">Recently modified</div>
                            <div class="user-list mt-2">
                                @foreach ($updatedProfiles->take(5) as $user)
                                    <div
                                        class="user-item d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <img class="rounded-circle"
                                                src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}"
                                                alt="Profile" width="36" height="36">
                                            <div>
                                                <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}"
                                                    class="fw-semibold text-decoration-none">{{ $user->first_name }}
                                                    {{ $user->last_name }}</a>
                                                <div class="small-muted">{{ $user->phone_no }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="badge-pill"><i class="bi bi-emoji-frown"></i> Total Rejected</span>
                                <a class="small-muted text-decoration-none"
                                    href="{{ route('admin.pratihari.filterUsers', 'rejected') }}">View</a>
                            </div>
                            <div class="count mt-2">{{ count($rejectedProfiles) }}</div>
                            <div class="label">All-time rejected</div>
                            <div class="user-list mt-2">
                                @foreach ($rejectedProfiles->take(5) as $user)
                                    <div
                                        class="user-item d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <img class="rounded-circle"
                                                src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}"
                                                alt="Profile" width="36" height="36">
                                            <div>
                                                <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}"
                                                    class="fw-semibold text-decoration-none">{{ $user->first_name }}
                                                    {{ $user->last_name }}</a>
                                                <div class="small-muted">{{ $user->phone_no }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Applications -->
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="badge-pill"><i class="bi bi-calendar-check"></i> Today’s Applications</span>
                                <a class="small-muted text-decoration-none"
                                    href="{{ route('admin.application.filter', 'today') }}">View</a>
                            </div>
                            <div class="count mt-2">{{ count($todayApplications) }}</div>
                            <div class="label">Submitted today</div>
                            <div class="user-list mt-2">
                                @foreach ($todayApplications->take(5) as $app)
                                    <div
                                        class="user-item d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-file-person fs-5 text-primary"></i>
                                            <div>
                                                <div class="fw-semibold">{{ $app->header ?? 'N/A' }}</div>
                                                <div class="small-muted">{{ $app->date ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="badge-pill"><i class="bi bi-file-earmark-check"></i> Approved
                                    Applications</span>
                                <a class="small-muted text-decoration-none"
                                    href="{{ route('admin.application.filter', 'approved') }}">View</a>
                            </div>
                            <div class="count mt-2">{{ count($approvedApplication) }}</div>
                            <div class="label">Accepted</div>
                            <div class="user-list mt-2">
                                @foreach ($approvedApplication->take(5) as $app)
                                    <div
                                        class="user-item d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-file-person fs-5 text-success"></i>
                                            <div>
                                                <div class="fw-semibold">{{ $app->header ?? 'N/A' }}</div>
                                                <div class="small-muted">{{ $app->date ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="badge-pill"><i class="bi bi-file-earmark-x"></i> Rejected Applications</span>
                                <a class="small-muted text-decoration-none"
                                    href="{{ route('admin.application.filter', 'rejected') }}">View</a>
                            </div>
                            <div class="count mt-2">{{ count($rejectedApplication) }}</div>
                            <div class="label">Declined</div>
                            <div class="user-list mt-2">
                                @foreach ($rejectedApplication->take(5) as $app)
                                    <div
                                        class="user-item d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-file-person fs-5 text-danger"></i>
                                            <div>
                                                <div class="fw-semibold">{{ $app->header ?? 'N/A' }}</div>
                                                <div class="small-muted">{{ $app->date ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div><!-- /row -->
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

        // Approve / Reject handlers
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
                    confirmButtonColor: '#1db954',
                    cancelButtonColor: '#6c757d',
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
                    inputAttributes: {
                        'aria-label': 'Type your reason here'
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#e03131',
                    cancelButtonColor: '#6c757d',
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
                                body: JSON.stringify({
                                    reason: result.value
                                })
                            })
                            .then(r => r.json())
                            .then(resp => {
                                Swal.fire('Rejected', resp.message || 'Profile rejected.', 'error')
                                    .then(() => location.reload());
                            });
                    }
                });
            }
        });
    </script>
@endsection
