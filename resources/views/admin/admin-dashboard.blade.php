@extends('layouts.app')

@section('styles')
    <!-- Icons & vendor CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* =========================
           THEME PALETTE
        ==========================*/
        :root{
            --ink:#111827;
            --muted:#6b7280;
            --border:#e6e8ef;
            --card:#ffffff;
            --bg: radial-gradient(140% 120% at 10% 10%, #fff1f1 0%, #f4f7ff 35%, #eaf9ff 65%, #fcf0ff 100%);

            --gradient-hero: linear-gradient(120deg,#8a2be2 0%,#f43f5e 45%,#f59e0b 100%);
            --grad-blue: linear-gradient(135deg,#60a5fa 0%,#2563eb 100%);
            --grad-green: linear-gradient(135deg,#34d399 0%,#059669 100%);
            --grad-orange: linear-gradient(135deg,#fbbf24 0%,#f59e0b 100%);
            --grad-purple: linear-gradient(135deg,#a78bfa 0%,#7c3aed 100%);
            --grad-red: linear-gradient(135deg,#fb7185 0%,#e11d48 100%);
            --grad-teal: linear-gradient(135deg,#5eead4 0%,#14b8a6 100%);
            --grad-rose: linear-gradient(135deg,#fda4af 0%,#fb7185 100%);
            --shadow: 0 12px 30px rgba(16,24,40,.10);
        }

        body { background: var(--bg) fixed; }

        /* =========================
           HERO / BANNER
        ==========================*/
        .hero {
            border-radius: 22px;
            background: var(--gradient-hero);
            color: #fff;
            padding: 22px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }
        .hero:after{
            content:""; position:absolute; inset:auto -40px -40px auto;
            width:220px; height:220px; border-radius:50%;
            background: radial-gradient(closest-side, rgba(255,255,255,.35), transparent 70%);
            filter: blur(6px);
        }
        .hero .pill{
            background: rgba(255,255,255,.15);
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 999px;
            padding: 6px 12px;
            display:inline-flex; align-items:center; gap:8px;
            backdrop-filter: blur(4px);
            font-weight:600;
        }
        .hero .beddha{
            background: #fff; color:#111827; border-radius: 14px; padding:10px 14px;
            font-weight:800; letter-spacing:.2px;
            box-shadow: inset 0 0 0 1px rgba(0,0,0,.06);
        }

        /* =========================
           COLORFUL TILES
        ==========================*/
        .tile{
            border-radius: 16px;
            color:#fff;
            padding: 18px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
            min-height: 160px;
        }
        .tile .kicker{ opacity:.9; font-weight:700; display:flex; align-items:center; gap:8px; }
        .tile .count{ font-size:2.25rem; font-weight:900; line-height:1.05; margin-top:8px; }
        .tile a { color:#fff; text-decoration: underline; text-underline-offset: 3px; opacity:.9; }
        .tile a:hover{ opacity:1; }
        .tile .preview-list{ margin-top: 10px; max-height: 120px; overflow:auto; }
        .tile .preview-item{ background: rgba(255,255,255,.18); border:1px solid rgba(255,255,255,.25); border-radius: 12px; padding:8px; }
        .tile .avatar{ width:36px; height:36px; border-radius:50%; object-fit:cover; border:2px solid rgba(255,255,255,.6); }

        .blue{ background: var(--grad-blue); }
        .green{ background: var(--grad-green); }
        .orange{ background: var(--grad-orange); }
        .purple{ background: var(--grad-purple); }
        .red{ background: var(--grad-red); }
        .teal{ background: var(--grad-teal); }
        .rose{ background: var(--grad-rose); }

        /* =========================
           CONTENT CARDS
        ==========================*/
        .cardx{
            background: var(--card);
            border:1px solid var(--border);
            border-radius: 18px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .cardx-header{
            padding: 14px 18px;
            display:flex; align-items:center; justify-content:space-between;
            background: linear-gradient(90deg, rgba(255,255,255,.8), rgba(255,255,255,.5));
            border-bottom: 1px solid var(--border);
        }
        .cardx-title{ font-weight:800; color:var(--ink); }
        .muted{ color:var(--muted); font-weight:600; }

        /* Tabs */
        .nav-colorful .nav-link{
            border:1px solid var(--border);
            border-radius: 12px;
            font-weight:800;
            color: var(--ink);
            background: #fff;
            padding: .55rem 1rem;
        }
        .nav-colorful .nav-link.active{
            color:#fff;
            background: linear-gradient(90deg,#6366f1 0%, #22d3ee 100%);
            border-color: transparent;
        }

        /* Seba strip */
        .seba-strip{
            display:grid; grid-auto-flow: column; grid-auto-columns: max-content; gap:14px;
            overflow-x:auto; padding-bottom: 6px; scroll-snap-type: x mandatory;
        }
        .seba-item{ scroll-snap-align: start; }

        /* Chips */
        .chip{
            display:inline-flex; align-items:center; gap:6px;
            padding: 4px 10px; border-radius:999px; font-weight:700; font-size:.85rem;
            border:1px solid var(--border); background:#fff; color:var(--ink);
        }
        .chip.ok{ background:#ecfdf5; color:#065f46; border-color:#a7f3d0; }
        .chip.warn{ background:#fff7ed; color:#92400e; border-color:#fed7aa; }

        /* Util */
        .divider{ height:1px; background: var(--border); margin: 10px 0; }
        .small-note{ font-size:.85rem; }
    </style>
@endsection

@section('content')
<div class="container-fluid py-3">

    <!-- HERO / BANNER -->
    <div class="hero mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <span class="pill"><i class="bi bi-calendar2-week"></i> {{ \Carbon\Carbon::now()->format('d M Y') }}</span>
                <span class="pill"><i class="bi bi-clock-history"></i> <span id="live-time"></span></span>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="beddha"><i class="bi bi-award me-1"></i> Pratihari Beddha: <strong>{{ $pratihariBeddha ?: 'N/A' }}</strong></span>
                <span class="beddha"><i class="bi bi-people me-1"></i> Gochhikar Beddha: <strong>{{ $gochhikarBeddha ?: 'N/A' }}</strong></span>
            </div>
        </div>
    </div>

    <!-- COLORFUL KPI TILES -->
    <div class="row g-3 mb-3">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tile blue">
                <div class="kicker"><i class="bi bi-person-plus"></i> Today’s Registrations</div>
                <div class="count">{{ count($todayProfiles) }}</div>
                <div><a href="{{ route('admin.pratihari.filterUsers', 'today') }}">View all</a></div>
                <div class="preview-list mt-2">
                    @foreach ($todayProfiles->take(3) as $user)
                        <div class="preview-item d-flex align-items-center gap-2 mb-2">
                            <img class="avatar" src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}" alt="">
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                <div class="small-note">{{ $user->phone_no }}</div>
                            </div>
                            <a class="stretched-link" href="{{ route('admin.viewProfile', $user->pratihari_id) }}"></a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tile orange">
                <div class="kicker"><i class="bi bi-hourglass-split"></i> Pending Profiles</div>
                <div class="count">{{ count($pendingProfile) }}</div>
                <div><a href="{{ route('admin.pratihari.filterUsers', 'pending') }}">Review queue</a></div>
                <div class="preview-list mt-2">
                    @foreach ($pendingProfile->take(3) as $user)
                        <div class="preview-item d-flex align-items-center gap-2 mb-2">
                            <img class="avatar" src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}" alt="">
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                <div class="small-note">{{ $user->phone_no }}</div>
                            </div>
                            <a class="stretched-link" href="{{ route('admin.viewProfile', $user->pratihari_id) }}"></a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tile green">
                <div class="kicker"><i class="bi bi-person-check"></i> Active Profiles</div>
                <div class="count">{{ count($totalActiveUsers) }}</div>
                <div><a href="{{ route('admin.pratihari.filterUsers', 'approved') }}">View active</a></div>
                <div class="preview-list mt-2">
                    @foreach ($totalActiveUsers->take(3) as $user)
                        <div class="preview-item d-flex align-items-center gap-2 mb-2">
                            <img class="avatar" src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}" alt="">
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                <div class="small-note">{{ $user->phone_no }}</div>
                            </div>
                            <a class="stretched-link" href="{{ route('admin.viewProfile', $user->pratihari_id) }}"></a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tile purple">
                <div class="kicker"><i class="bi bi-clipboard2-data"></i> Incomplete Profiles</div>
                <div class="count">{{ count($incompleteProfiles) }}</div>
                <div><a href="{{ route('admin.pratihari.filterUsers', 'incomplete') }}">Complete details</a></div>
                <div class="preview-list mt-2">
                    @foreach ($incompleteProfiles->take(3) as $user)
                        <div class="preview-item d-flex align-items-center gap-2 mb-2">
                            <img class="avatar" src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}" alt="">
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                <div class="small-note">{{ $user->phone_no }}</div>
                            </div>
                            <a class="stretched-link" href="{{ route('admin.viewProfile', $user->pratihari_id) }}"></a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- SECOND ROW OF KPI TILES -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tile teal">
                <div class="kicker"><i class="bi bi-check2-circle"></i> Today Approved</div>
                <div class="count">{{ count($todayApprovedProfiles) }}</div>
                <div><a href="{{ route('admin.pratihari.filterUsers', 'todayapproved') }}">See list</a></div>
                <div class="preview-list mt-2">
                    @foreach ($todayApprovedProfiles->take(3) as $user)
                        <div class="preview-item d-flex align-items-center gap-2 mb-2">
                            <img class="avatar" src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}" alt="">
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                <div class="small-note">{{ $user->phone_no }}</div>
                            </div>
                            <a class="stretched-link" href="{{ route('admin.viewProfile', $user->pratihari_id) }}"></a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tile red">
                <div class="kicker"><i class="bi bi-x-circle"></i> Today Rejected</div>
                <div class="count">{{ count($todayRejectedProfiles) }}</div>
                <div><a href="{{ route('admin.pratihari.filterUsers', 'todayrejected') }}">See reasons</a></div>
                <div class="preview-list mt-2">
                    @foreach ($todayRejectedProfiles->take(3) as $user)
                        <div class="preview-item d-flex align-items-center gap-2 mb-2">
                            <img class="avatar" src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}" alt="">
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                <div class="small-note">{{ $user->phone_no }}</div>
                            </div>
                            <a class="stretched-link" href="{{ route('admin.viewProfile', $user->pratihari_id) }}"></a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tile rose">
                <div class="kicker"><i class="bi bi-arrow-repeat"></i> Updated Profiles</div>
                <div class="count">{{ count($updatedProfiles) }}</div>
                <div><a href="{{ route('admin.pratihari.filterUsers', 'updated') }}">Review updates</a></div>
                <div class="preview-list mt-2">
                    @foreach ($updatedProfiles->take(3) as $user)
                        <div class="preview-item d-flex align-items-center gap-2 mb-2">
                            <img class="avatar" src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}" alt="">
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                <div class="small-note">{{ $user->phone_no }}</div>
                            </div>
                            <a class="stretched-link" href="{{ route('admin.viewProfile', $user->pratihari_id) }}"></a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tile purple">
                <div class="kicker"><i class="bi bi-emoji-frown"></i> Total Rejected</div>
                <div class="count">{{ count($rejectedProfiles) }}</div>
                <div><a href="{{ route('admin.pratihari.filterUsers', 'rejected') }}">View all</a></div>
                <div class="preview-list mt-2">
                    @foreach ($rejectedProfiles->take(3) as $user)
                        <div class="preview-item d-flex align-items-center gap-2 mb-2">
                            <img class="avatar" src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}" alt="">
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                <div class="small-note">{{ $user->phone_no }}</div>
                            </div>
                            <a class="stretched-link" href="{{ route('admin.viewProfile', $user->pratihari_id) }}"></a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- SEBA + GOCHHIKAR PANELS -->
    <div class="row g-3 mb-4">
        <!-- Pratihari -->
        <div class="col-12 col-xl-8">
            <div class="cardx">
                <div class="cardx-header">
                    <div>
                        <div class="cardx-title">Today’s Pratihari Seba</div>
                        <div class="muted">Assigned users and Nijoga for today</div>
                    </div>
                </div>
                <div class="p-3">
                    <ul class="nav nav-pills nav-colorful gap-2" id="sebaTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-pratihari" data-bs-toggle="pill" data-bs-target="#pane-pratihari" type="button" role="tab">
                                <i class="bi bi-person-badge me-1"></i> Pratihari
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-nijoga" data-bs-toggle="pill" data-bs-target="#pane-nijoga" type="button" role="tab">
                                <i class="bi bi-clipboard2-check me-1"></i> Nijoga Assigned
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="pane-pratihari" role="tabpanel">
                            @forelse ($pratihariEvents as $label => $pratiharis)
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="fw-bold">{{ $label }}</span>
                                        <span class="chip ok"><i class="bi bi-calendar-event"></i> Today</span>
                                    </div>
                                    <div class="seba-strip">
                                        @foreach ($pratiharis as $user)
                                            <div class="seba-item">
                                                @include('partials._user_card', ['user' => $user])
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-light border d-flex align-items-center" role="alert">
                                    <i class="bi bi-info-circle me-2"></i> No seba assigned for today.
                                </div>
                            @endforelse
                        </div>

                        <div class="tab-pane fade" id="pane-nijoga" role="tabpanel">
                            @forelse ($nijogaAssign as $label => $nojoga)
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="fw-bold">{{ $label }}</span>
                                        <span class="chip warn"><i class="bi bi-clipboard2-pulse"></i> Nijoga</span>
                                    </div>
                                    <div class="seba-strip">
                                        @foreach ($nojoga as $user)
                                            <div class="seba-item">
                                                @include('partials._user_card', ['user' => $user])
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-light border d-flex align-items-center" role="alert">
                                    <i class="bi bi-info-circle me-2"></i> No nijoga seba assigned for today.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gochhikar -->
        <div class="col-12 col-xl-4">
            <div class="cardx h-100">
                <div class="cardx-header">
                    <div>
                        <div class="cardx-title">Gochhikar Today</div>
                        <div class="muted">Normal & Nijoga assignments</div>
                    </div>
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
                                        <div class="seba-item">
                                            @include('partials._user_card', ['user' => $user])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-light border" role="alert">No Gochhikar assigned (normal) for today.</div>
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
                                        <div class="seba-item">
                                            @include('partials._user_card', ['user' => $user])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-light border" role="alert">No Nijoga Gochhikar for today.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- APPLICATIONS (COLOR STRIP) -->
    <div class="row g-3 mb-3">
        <div class="col-12 col-md-4">
            <div class="tile teal">
                <div class="kicker"><i class="bi bi-calendar-check"></i> Today’s Applications</div>
                <div class="count">{{ count($todayApplications) }}</div>
                <div><a href="{{ route('admin.application.filter', 'today') }}">Open list</a></div>
                <div class="preview-list mt-2">
                    @foreach ($todayApplications->take(3) as $app)
                        <div class="preview-item d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-file-person fs-5"></i>
                            <div>
                                <div class="fw-bold">{{ $app->header ?? 'N/A' }}</div>
                                <div class="small-note">{{ $app->date ?? 'N/A' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="tile green">
                <div class="kicker"><i class="bi bi-file-earmark-check"></i> Approved Applications</div>
                <div class="count">{{ count($approvedApplication) }}</div>
                <div><a href="{{ route('admin.application.filter', 'approved') }}">Review</a></div>
                <div class="preview-list mt-2">
                    @foreach ($approvedApplication->take(3) as $app)
                        <div class="preview-item d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-check2-square fs-5"></i>
                            <div>
                                <div class="fw-bold">{{ $app->header ?? 'N/A' }}</div>
                                <div class="small-note">{{ $app->date ?? 'N/A' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="tile red">
                <div class="kicker"><i class="bi bi-file-earmark-x"></i> Rejected Applications</div>
                <div class="count">{{ count($rejectedApplication) }}</div>
                <div><a href="{{ route('admin.application.filter', 'rejected') }}">Inspect</a></div>
                <div class="preview-list mt-2">
                    @foreach ($rejectedApplication->take(3) as $app)
                        <div class="preview-item d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-x-octagon fs-5"></i>
                            <div>
                                <div class="fw-bold">{{ $app->header ?? 'N/A' }}</div>
                                <div class="small-note">{{ $app->date ?? 'N/A' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
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

        // Approve / Reject (delegated)
        document.addEventListener('click', function (e) {
            const approveBtn = e.target.closest('.approve-btn');
            const rejectBtn  = e.target.closest('.reject-btn');

            if (approveBtn) {
                const profileId = approveBtn.dataset.id;
                Swal.fire({
                    title: 'Approve profile?',
                    text: 'This will mark the profile as approved.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
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
                        });
                    }
                });
            }
        });
    </script>
@endsection
