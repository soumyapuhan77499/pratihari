@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        #custom-calendar {
            max-width: 100%;
            margin: 0 auto;
            height: 900px !important;
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .fc .fc-toolbar-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #f8c66d;
        }

        .fc-button {
            background-color: #f8c66d !important;
            border: none !important;
            border-radius: 6px !important;
        }

        .fc-button-primary:not(:disabled):hover {
            background-color: #f8c66d !important;
        }

        .fc-event {
            background-color: #e96a01 !important;
            border: none !important;
            border-radius: 4px !important;
            padding: 2px 4px;
            font-size: 0.685rem;
        }

        .fc-daygrid-day-number {
            font-weight: 600;
            font-size: 14px;
        }

        .fc-daygrid-day {
            background-color: #f8f9fa;
        }

        .user-list-card {
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
            height: 400px;
            display: flex;
            flex-direction: column;
        }

        .user-list-item {
            transition: background 0.3s ease;
        }

        .user-list-item:hover {
            background-color: #f9f9f9;
        }

        .user-img {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border: 2px solid #ccc;
        }

        .card-header .badge {
            font-size: 0.85rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .seba-user-list {
            gap: 1rem;
            scroll-snap-type: x mandatory;
            padding-bottom: 1rem;
        }

        .seba-user-card {
            min-width: 160px;
            border-radius: 12px;
            background: #e9f7ef;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 1rem;
            scroll-snap-align: start;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #28a745;
            margin-bottom: 0.5rem;
        }

        .online-indicator {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 12px;
            height: 12px;
            background-color: #28a745;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.1);
        }

        /* Dashboard Banner Card */
        .dashboard-banner {
            background: linear-gradient(90deg, #007bff 0%, #6a11cb 100%);
            color: #181717;
            border-radius: 10px;
        }

        /* Text block */
        .banner-text h2 {
            font-weight: bold;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .banner-text small {
            font-size: 0.8rem;
            font-style: italic;
            color: #4a4949;
        }

        /* Date & Time badge styling */
        .datetime-box .date-time-badge {
            background-color: #ffffff;
            color: #2c3e50;
            padding: 3px 6px;
            border-radius: 6px;
            font-weight: 600;
            font-family: 'Courier New', Courier, monospace;
            font-size: 16px;
        }

        .tab-wrapper {
            background: linear-gradient(135deg, #fefefe, #f9f9f9);
            border: 1px solid #e5e5e5;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-radius: 16px;
            transition: all 0.3s ease-in-out;
        }

        .tab-modern .nav-link {
            font-weight: 600;
            font-size: 1.05rem;
            color: #6c757d;
            background-color: #f1f1f1;
            padding: 0.75rem 2.5rem;
            border-radius: 50px;
            transition: all 0.3s ease-in-out;
            box-shadow: inset 0 0 0 rgba(0, 0, 0, 0);
            border: none;
        }

        .tab-modern .nav-link:hover {
            background-color: #e9ecef;
            color: #e96a01;
        }

        .tab-modern .nav-link.active {
            color: #fff;
            background: linear-gradient(to right, #f8c66d, #e96a01);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
            font-weight: bold;
            border: none;
        }

        .nav-pills .nav-link {
            font-size: 18px;
            padding: 12px 0;
            text-align: center;
            border-radius: 8px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <!-- Active Users -->
            <div class="col-8 mb-4">
                <div class="card shadow-sm" style="border-radius: 12px; background: #fff;">
                    <div class="card-header text-white mt-4"
                        style="background: linear-gradient(90deg, #007bff 0%, #6a11cb 100%); min-height: 60px; position: relative;">
                        <div class="container-fluid">
                            <div class="row align-items-center justify-content-between">
                                <!-- Left: Beddha Display -->
                                <div class="col-6 d-flex align-items-center">
                                    <span class="fw-bold fs-5 text-white">
                                        Pratihari Beddha : {{ $pratihariBeddha ?: 'N/A' }}
                                    </span>
                                </div>

                                <!-- Right: Date and Time aligned right -->
                                <div class="col-6 d-flex justify-content-end gap-2 align-items-center datetime-box">
                                    <span class="date-time-badge">{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid mt-4">
                        <div class="row justify-content-center">
                            <div class="col-lg-10">
                                <ul class="nav nav-pills tab-modern row w-100 m-0" id="profileTabs" role="tablist">
                                    <li class="nav-item col-6 p-1" role="presentation">
                                        <button class="nav-link active w-100" id="pratihari-tab" data-bs-toggle="pill"
                                            data-bs-target="#pratihari" type="button" role="tab"
                                            aria-controls="pratihari" aria-selected="true">
                                            <i class="bi bi-person-badge me-2"></i> Pratihari
                                        </button>
                                    </li>
                                    <li class="nav-item col-6 p-1" role="presentation">
                                        <button class="nav-link w-100" id="nojoga-tab" data-bs-toggle="pill"
                                            data-bs-target="#nijoga_assign" type="button" role="tab"
                                            aria-controls="nijoga_assign" aria-selected="false">
                                            <i class="bi bi-person-lines-fill me-2"></i> Nijoga Assigned
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>


                    <div class="card-body p-3">
                        <div class="tab-content mt-4" id="profileTabsContent">
                            {{-- Pratihari Tab --}}
                            <div class="tab-pane fade show active" id="pratihari" role="tabpanel"
                                aria-labelledby="pratihari-tab">
                                @forelse ($pratihariEvents as $label => $pratiharis)
                                    <div class="mb-4">
                                        <h6 class="fw-bold">{{ $label }}</h6>
                                        <div class="d-flex overflow-auto seba-user-list">
                                            @foreach ($pratiharis as $user)
                                                @include('partials._user_card', ['user' => $user])
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">No seba assigned for today.</p>
                                @endforelse
                            </div>

                            {{-- Gochhikar Tab --}}
                            <div class="tab-pane fade" id="nijoga_assign" role="tabpanel" aria-labelledby="nijoga-tab">
                                @forelse ($nijogaAssign as $label => $nojoga)
                                    <div class="mb-4">
                                        <h6 class="fw-bold">{{ $label }}</h6>
                                        <div class="d-flex overflow-auto seba-user-list">
                                            @foreach ($nojoga as $user)
                                                @include('partials._user_card', ['user' => $user])
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">No nijoga seba assigned for today.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 mb-4">
                <div class="card shadow-sm" style="border-radius: 12px; background: #fff;">
                    <div class="card-header text-white mt-4"
                        style="background: linear-gradient(90deg, #007bff 0%, #6a11cb 100%); min-height: 60px;">
                        <div class="container-fluid">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-12 d-flex align-items-center">
                                    <span class="fw-bold fs-5 text-white">
                                        Gochhikar Beddha : {{ $gochhikarBeddha ?: 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-3">
                        <div class="mt-4">

                            <div class="mb-3">
                                <h5 class="fw-bold text-primary">Gochhikar</h5>
                            </div>

                            {{-- Seba ID = 9, beddha status = 1 --}}
                            @forelse ($gochhikarEvents as $label => $users)
                                <div class="mb-4">
                                    <h6 class="fw-bold">{{ $label }}</h6>
                                    <div class="d-flex overflow-auto seba-user-list">
                                        @foreach ($users as $user)
                                            @include('partials._user_card', ['user' => $user])
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">No Gochhikar assigned (normal) for today.</p>
                            @endforelse

                            <div class="mb-3">
                                <h5 class="fw-bold text-primary">Nijoga Assign</h5>
                            </div>

                            {{-- Seba ID = 9, beddha status = 0 --}}
                            @forelse ($nijogaGochhikarEvents as $label => $users)
                                <div class="mb-4">
                                    <h6 class="fw-bold">{{ $label }}</h6>
                                    <div class="d-flex overflow-auto seba-user-list">
                                        @foreach ($users as $user)
                                            @include('partials._user_card', ['user' => $user])
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">No Nijoga Gochhikar for today.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-12 mb-4">
                <div class="card shadow-sm" style="width: 100%; border-radius: 12px; overflow-x: auto; background: #fff;">
                    <div class="card-header text-white"
                        style="background: linear-gradient(90deg, #007bff 0%, #6a11cb 100%);">
                        <i class="bi bi-people-fill me-2"></i>
                        <span class="fw-bold">Today Pratihari Seba</span>
                        <span class="ms-3">
                            <span id="today-date" class="badge bg-light text-dark"></span>
                            <span id="current-time" class="badge bg-light text-dark"></span>
                        </span>

                    </div>
                    <div class="card-body p-3" style="display: flex; gap: 1rem; overflow-x: auto;">
                        <!-- Example static user cards -->
                        <div class="d-flex flex-column align-items-center p-3"
                            style="min-width: 180px; border-radius: 8px; background: #f8f9fa; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                            <img src="{{ asset('assets/img/brand/monk.png') }}" alt="User"
                                class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;">
                            <div class="fw-semibold">User Name 1</div>
                            <div class="text-muted small">+91 99999 11111</div>
                        </div>
                        <div class="d-flex flex-column align-items-center p-3"
                            style="min-width: 180px; border-radius: 8px; background: #f8f9fa; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                            <img src="{{ asset('assets/img/brand/monk.png') }}" alt="User"
                                class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;">
                            <div class="fw-semibold">User Name 2</div>
                            <div class="text-muted small">+91 88888 22222</div>
                        </div>
                        <div class="d-flex flex-column align-items-center p-3"
                            style="min-width: 180px; border-radius: 8px; background: #f8f9fa; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                            <img src="{{ asset('assets/img/brand/monk.png') }}" alt="User"
                                class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;">
                            <div class="fw-semibold">User Name 3</div>
                            <div class="text-muted small">+91 77777 33333</div>
                        </div>
                        <div class="d-flex flex-column align-items-center p-3"
                            style="min-width: 180px; border-radius: 8px; background: #f8f9fa; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                            <img src="{{ asset('assets/img/brand/monk.png') }}" alt="User"
                                class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;">
                            <div class="fw-semibold">User Name 1</div>
                            <div class="text-muted small">+91 99999 11111</div>
                        </div>
                        <div class="d-flex flex-column align-items-center p-3"
                            style="min-width: 180px; border-radius: 8px; background: #f8f9fa; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                            <img src="{{ asset('assets/img/brand/monk.png') }}" alt="User"
                                class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;">
                            <div class="fw-semibold">User Name 2</div>
                            <div class="text-muted small">+91 88888 22222</div>
                        </div>
                        <div class="d-flex flex-column align-items-center p-3"
                            style="min-width: 180px; border-radius: 8px; background: #f8f9fa; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                            <img src="{{ asset('assets/img/brand/monk.png') }}" alt="User"
                                class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;">
                            <div class="fw-semibold">User Name 3</div>
                            <div class="text-muted small">+91 77777 33333</div>
                        </div>
                        <div class="d-flex flex-column align-items-center p-3"
                            style="min-width: 180px; border-radius: 8px; background: #f8f9fa; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                            <img src="{{ asset('assets/img/brand/monk.png') }}" alt="User"
                                class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;">
                            <div class="fw-semibold">User Name 1</div>
                            <div class="text-muted small">+91 99999 11111</div>
                        </div>
                        <div class="d-flex flex-column align-items-center p-3"
                            style="min-width: 180px; border-radius: 8px; background: #f8f9fa; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                            <img src="{{ asset('assets/img/brand/monk.png') }}" alt="User"
                                class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;">
                            <div class="fw-semibold">User Name 2</div>
                            <div class="text-muted small">+91 88888 22222</div>
                        </div>
                        <div class="d-flex flex-column align-items-center p-3"
                            style="min-width: 180px; border-radius: 8px; background: #f8f9fa; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                            <img src="{{ asset('assets/img/brand/monk.png') }}" alt="User"
                                class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;">
                            <div class="fw-semibold">User Name 3</div>
                            <div class="text-muted small">+91 77777 33333</div>
                        </div>

                        <!-- Add more static user cards as needed -->
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card user-list-card shadow-sm">

                    <div class="card-header d-flex justify-content-between align-items-center text-white"
                        style="background-color: #17c3ce;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-plus-fill me-2 fs-5"></i> Today’s Registrations
                        </div>
                        <span class="badge bg-light text-dark">{{ count($todayProfiles) }}</span>
                    </div>

                    <div class="card-body p-0">
                        @foreach ($todayProfiles->take(5) as $user)
                            <div
                                class="user-list-item d-flex align-items-center justify-content-between p-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}"
                                        alt="Profile Photo" class="user-img rounded-circle me-3">
                                    <div>
                                        <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}">
                                            <div class="fw-semibold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                        </a>
                                        <div class="text-muted small">{{ $user->phone_no }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer bg-white text-center">
                        <a href="{{ route('admin.pratihari.filterUsers', 'today') }}"
                            class="text-decoration-none fw-semibold">
                            View More &rarr;
                        </a>
                    </div>

                </div>
            </div>

            <div class="col-md-3">
                <div class="card user-list-card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center text-white"
                        style="background-color: #faa409;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-x-fill me-2 fs-5"></i> Pending Profiles
                        </div>
                        <span class="badge bg-light text-dark">{{ count($pendingProfile) }}</span>
                    </div>

                    <div class="card-body p-0">
                        @foreach ($pendingProfile->take(5) as $user)
                            <div
                                class="user-list-item d-flex align-items-center justify-content-between p-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}"
                                        alt="Profile Photo" class="user-img rounded-circle me-3">
                                    <div>
                                        <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}">
                                            <div class="fw-semibold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                        </a>
                                        <div class="text-muted small">{{ $user->phone_no }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer bg-white text-center">
                        <a href="{{ route('admin.pratihari.filterUsers', 'pending') }}"
                            class="text-decoration-none fw-semibold">
                            View More &rarr;
                        </a>
                    </div>

                </div>
            </div>

            <div class="col-md-3">
                <div class="card user-list-card shadow-sm" style="height: 400px; display: flex; flex-direction: column;">
                    <div class="card-header d-flex justify-content-between align-items-center text-white"
                        style="background-color: #24960b;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-check-fill me-2 fs-5"></i> Active Profiles
                        </div>
                        <span class="badge bg-light text-dark">{{ count($totalActiveUsers) }}</span>
                    </div>

                    <div class="card-body p-0 overflow-auto" style="flex: 1 1 auto;">
                        @foreach ($totalActiveUsers->take(5) as $user)
                            <div
                                class="user-list-item d-flex align-items-center justify-content-between p-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}"
                                        alt="Profile Photo" class="user-img rounded-circle me-3">
                                    <div>
                                        <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}">
                                            <div class="fw-semibold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                        </a>
                                        <div class="text-muted small">{{ $user->phone_no }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer bg-white text-center">
                        <a href="{{ route('admin.pratihari.filterUsers', 'approved') }}"
                            class="text-decoration-none fw-semibold">
                            View More &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card user-list-card shadow-sm">

                    <div class="card-header d-flex justify-content-between align-items-center text-white"
                        style="background-color: #efb86a;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-plus-fill me-2 fs-5"></i> Incomplete Profiles
                        </div>
                        <span class="badge bg-light text-dark">{{ count($incompleteProfiles) }}</span>
                    </div>

                    <div class="card-body p-0">
                        @foreach ($incompleteProfiles->take(5) as $user)
                            <div
                                class="user-list-item d-flex align-items-center justify-content-between p-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}"
                                        alt="Profile Photo" class="user-img rounded-circle me-3">
                                    <div>
                                        <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}">
                                            <div class="fw-semibold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                        </a>
                                        <div class="text-muted small">{{ $user->phone_no }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer bg-white text-center">
                        <a href="{{ route('admin.pratihari.filterUsers', 'incomplete') }}"
                            class="text-decoration-none fw-semibold">
                            View More &rarr;
                        </a>
                    </div>

                </div>
            </div>

            <div class="col-md-3">
                <div class="card user-list-card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center text-white"
                        style="background-color: #0d6efd;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check2-circle me-2 fs-5"></i> Today Approved Profiles
                        </div>
                        <span class="badge bg-light text-dark">{{ count($todayApprovedProfiles) }}</span>
                    </div>

                    <div class="card-body p-0 overflow-auto" style="flex: 1 1 auto;">
                        @foreach ($todayApprovedProfiles->take(5) as $user)
                            <div
                                class="user-list-item d-flex align-items-center justify-content-between p-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}"
                                        alt="Profile Photo" class="user-img rounded-circle me-3">
                                    <div>
                                        <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}">
                                            <div class="fw-semibold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                        </a>
                                        <div class="text-muted small">{{ $user->phone_no }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer bg-white text-center">
                        <a href="{{ route('admin.pratihari.filterUsers', 'todayapproved') }}"
                            class="text-decoration-none fw-semibold">
                            View More &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card user-list-card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center text-white"
                        style="background-color: #dc3545;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-x-circle-fill me-2 fs-5"></i> Today Rejected Profiles
                        </div>
                        <span class="badge bg-light text-dark">{{ count($todayRejectedProfiles) }}</span>
                    </div>

                    <div class="card-body p-0 overflow-auto" style="flex: 1 1 auto;">
                        @foreach ($todayRejectedProfiles->take(5) as $user)
                            <div
                                class="user-list-item d-flex align-items-center justify-content-between p-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}"
                                        alt="Profile Photo" class="user-img rounded-circle me-3">
                                    <div>
                                        <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}">
                                            <div class="fw-semibold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                        </a>
                                        <div class="text-muted small">{{ $user->phone_no }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer bg-white text-center">
                        <a href="{{ route('admin.pratihari.filterUsers', 'todayrejected') }}"
                            class="text-decoration-none fw-semibold">
                            View More &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card user-list-card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center text-white"
                        style="background-color: #ffc107;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i> Updated Profiles
                        </div>
                        <span class="badge bg-light text-dark">{{ count($updatedProfiles) }}</span>
                    </div>

                    <div class="card-body p-0">
                        @foreach ($updatedProfiles->take(5) as $user)
                            <div
                                class="user-list-item d-flex align-items-center justify-content-between p-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}"
                                        alt="Profile Photo" class="user-img rounded-circle me-3">
                                    <div>
                                        <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}">
                                            <div class="fw-semibold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                        </a>
                                        <div class="text-muted small">{{ $user->phone_no }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer bg-white text-center">
                        <a href="{{ route('admin.pratihari.filterUsers', 'updated') }}"
                            class="text-decoration-none fw-semibold">
                            View More &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card user-list-card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center text-white"
                        style="background-color: #dc3545;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-x-fill me-2 fs-5"></i>Total Rejected Profiles
                        </div>
                        <span class="badge bg-light text-dark">{{ count($rejectedProfiles) }}</span>
                    </div>

                    <div class="card-body p-0">
                        @foreach ($rejectedProfiles->take(5) as $user)
                            <div
                                class="user-list-item d-flex align-items-center justify-content-between p-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}"
                                        alt="Profile Photo" class="user-img rounded-circle me-3">
                                    <div>
                                        <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}">
                                            <div class="fw-semibold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                        </a>
                                        <div class="text-muted small">{{ $user->phone_no }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer bg-white text-center">
                        <a href="{{ route('admin.pratihari.filterUsers', 'rejected') }}"
                            class="text-decoration-none fw-semibold">
                            View More &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card user-list-card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center text-white"
                        style="background-color: #6f42c1;"> {{-- purple tone --}}
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar-check-fill me-2 fs-5"></i> Today’s Applications
                        </div>
                        <span class="badge bg-light text-dark">{{ count($todayApplications) }}</span>
                    </div>

                    <div class="card-body p-0 overflow-auto" style="flex: 1 1 auto;">
                        @foreach ($todayApplications->take(5) as $app)
                            <div
                                class="user-list-item d-flex align-items-center justify-content-between p-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-person me-3 fs-4 text-primary"></i>
                                    <div>
                                        <div class="fw-semibold">{{ $app->header ?? 'N/A' }}</div>
                                        <div class="text-muted small">{{ $app->date ?? 'App ID N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer bg-white text-center">
                        <a href="{{ route('admin.application.filter', 'today') }}"
                            class="text-decoration-none fw-semibold">
                            View More &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card user-list-card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center text-white"
                        style="background-color: #198754;"> {{-- Bootstrap success green --}}
                        <div class="d-flex align-items-center">
                            <i class="bi bi-file-earmark-check-fill me-2 fs-5"></i> Approved Applications
                        </div>
                        <span class="badge bg-light text-dark">{{ count($approvedApplication) }}</span>
                    </div>

                    <div class="card-body p-0 overflow-auto" style="flex: 1 1 auto;">
                        @foreach ($approvedApplication->take(5) as $app)
                            <div
                                class="user-list-item d-flex align-items-center justify-content-between p-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-person me-3 fs-4 text-success"></i>
                                    <div>
                                        <div class="fw-semibold">{{ $app->header ?? 'N/A' }}</div>
                                        <div class="text-muted small">{{ $app->date ?? 'App ID N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer bg-white text-center">
                        <a href="{{ route('admin.application.filter', 'approved') }}"
                            class="text-decoration-none fw-semibold">
                            View More &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card user-list-card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center text-white"
                        style="background-color: #dc3545;"> {{-- Bootstrap red --}}
                        <div class="d-flex align-items-center">
                            <i class="bi bi-file-earmark-x-fill me-2 fs-5"></i> Rejected Applications
                        </div>
                        <span class="badge bg-light text-dark">{{ count($rejectedApplication) }}</span>
                    </div>

                    <div class="card-body p-0 overflow-auto" style="flex: 1 1 auto;">
                        @foreach ($rejectedApplication->take(5) as $app)
                            <div
                                class="user-list-item d-flex align-items-center justify-content-between p-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-person me-3 fs-4 text-danger"></i>
                                    <div>
                                        <div class="fw-semibold">{{ $app->header ?? 'N/A' }}</div>
                                        <div class="text-muted small">{{ $app->date ?? 'App ID N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer bg-white text-center">
                        <a href="{{ route('admin.application.filter', 'rejected') }}"
                            class="text-decoration-none fw-semibold">
                            View More &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="row mb-4 mt-4">
            <div class="col-lg-12">
                <div class="card custom-card"
                    style="background: linear-gradient(90deg, #f8c66d 0%, #e96a01 100%); color: #1d1818;">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-calendar-event me-3" style="font-size: 2rem;"></i>
                        <div>
                            <h4 class="mb-0" style="font-weight: bold;">Pratihari Seba Calendar</h4>
                            <small>View and manage Seba assignments for Pratihari users below.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- <div class="row">
            <!-- Filter by Pratihari Name -->
            <div class="col-lg-4 mb-4 mx-auto d-flex">
                <div class="card custom-card w-100">
                    <div class="card-header text-white" style="background-color: #f8c66d">
                        <i class="bi bi-filter me-2"></i>Filter by Pratihari Name
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ url()->current() }}">
                            <div class="mb-3">
                                <select class="form-select" name="pratihari_id" onchange="this.form.submit()">
                                    <option value="">-- Select Pratihari Name --</option>
                                    @foreach ($profile_name as $profile)
                                        <option value="{{ $profile->pratihari_id }}"
                                            {{ request('pratihari_id') == $profile->pratihari_id ? 'selected' : '' }}>
                                            {{ $profile->first_name }} {{ $profile->middle_name }}
                                            {{ $profile->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4 mx-auto d-flex">
                <div class="card custom-card w-100">
                    <div class="card-header text-white" style="background-color: #e96a01">
                        <i class="bi bi-filter me-2"></i>Filter by Gochhikar Name
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ url()->current() }}">
                            <div class="mb-3">
                                <select class="form-select" name="pratihari_id" onchange="this.form.submit()">
                                    <option value="">-- Select Gochhikar Name --</option>
                                    @foreach ($gochhikar_name as $gochhikar)
                                        <option value="{{ $gochhikar->pratihari_id }}"
                                            {{ request('pratihari_id') == $profile->pratihari_id ? 'selected' : '' }}>
                                            {{ $gochhikar->first_name }} {{ $gochhikar->middle_name }}
                                            {{ $gochhikar->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Calendar -->
            <div class="col-lg-12 mb-4">
                <div class="card custom-card">
                    <div class="card-header text-white" style="background-color: #f8c66d">
                        <i class="bi bi-calendar-event me-2"></i>Custom Calendar
                    </div>
                    <div class="card-body">
                        <div id="custom-calendar"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="eventModalLabel">Seba Details</h5>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Seba Name:</strong> <span id="modalSebaName"></span></p>
                        <p><strong>Beddha ID:</strong> <span id="modalBeddhaId"></span></p>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- <div class="col-lg-12 mb-3">
            <div class="card custom-card"
                style="background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%); color: #1d1818;">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-table me-3" style="font-size: 2rem;"></i>
                    <div>
                        <h4 class="mb-0" style="font-weight: bold;">User Profiles List</h4>
                        <small>Below is the list of all registered Pratihari users with their details and actions.</small>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- <div class="col-lg-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-body">
                    <div class="table-responsive export-table">
                        <table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Photo</th>
                                    <th>View</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Occupation</th>
                                    <th>Health Card No</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($profiles as $index => $profile)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('admin.viewProfile', $profile->pratihari_id) }}">
                                                <img src="{{ asset($profile->profile_photo) }}" class="profile-photo"
                                                    alt="Profile Photo" class="br-5" width="50" height="50">
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.viewProfile', $profile->pratihari_id) }}"
                                                style="background-color:rgb(76, 2, 82);color: white" class="btn btn-sm">
                                                View Profile
                                            </a>
                                        </td>
                                        <td>{{ $profile->first_name }} {{ $profile->middle_name }}
                                            {{ $profile->last_name }}
                                        </td>
                                        <td>{{ $profile->phone_no }}</td>
                                        <td>{{ $profile->occupation->occupation_type ?? 'N/A' }}</td>
                                        <td>{{ $profile->healthcard_no }}</td>
                                        <td>{{ $profile->status }}</td>
                                        <td>
                                            @if ($profile->pratihari_status === 'approved')
                                                <button class="btn btn-success btn-sm" disabled>Approved</button>
                                            @elseif ($profile->pratihari_status === 'rejected')
                                                <button class="btn btn-danger btn-sm" disabled>Rejected</button>
                                            @elseif ($profile->pratihari_status === 'pending')
                                                <button class="btn btn-success btn-sm approve-btn"
                                                    data-id="{{ $profile->id }}">Approve</button>
                                                <button class="btn btn-danger btn-sm reject-btn"
                                                    data-id="{{ $profile->id }}">Reject</button>
                                            @elseif ($profile->pratihari_status === 'updated')
                                                <button class="btn btn-success btn-sm approve-btn"
                                                    data-id="{{ $profile->id }}">Approve</button>
                                                <button class="btn btn-danger btn-sm reject-btn"
                                                    data-id="{{ $profile->id }}">Reject</button>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}

    </div>
@endsection

@section('scripts')
    <!-- Internal Data tables -->
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.approve-btn').click(function() {
                let profileId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to approve this profile?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Approve'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/pratihari/approve/' + profileId,
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire('Approved!', response.message, 'success')
                                    .then(() => {
                                        location.reload();
                                    });
                            }
                        });
                    }
                });
            });

            $('.reject-btn').click(function() {
                let profileId = $(this).data('id');

                Swal.fire({
                    title: 'Reject Profile',
                    input: 'textarea',
                    inputLabel: 'Reason for rejection',
                    inputPlaceholder: 'Type your reason here...',
                    inputAttributes: {
                        'aria-label': 'Type your reason here'
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Reject',
                    preConfirm: (reason) => {
                        if (!reason) {
                            Swal.showValidationMessage('Reject reason is required');
                        }
                        return reason;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send AJAX with reason
                        $.ajax({
                            url: '/admin/pratihari/reject/' + profileId,
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                reason: result.value
                            },
                            success: function(response) {
                                Swal.fire('Rejected!', response.message, 'error').then(
                                    () => {
                                        location.reload();
                                    });
                            }
                        });
                    }
                });
            });

        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('custom-calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 500,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    const urlParams = new URLSearchParams(window.location.search);
                    const pratihariId = urlParams.get('pratihari_id');

                    if (pratihariId) {
                        fetch(
                                `{{ route('admin.sebaDate') }}?pratihari_id=${encodeURIComponent(pratihariId)}&_=no_cache`
                            )
                            .then(response => {
                                if (!response.ok) throw new Error("Failed to load events");
                                return response.json();
                            })
                            .then(data => successCallback(data))
                            .catch(error => {
                                console.error("Error loading events:", error);
                                failureCallback(error);
                            });
                    } else {
                        successCallback([]);
                    }
                },
                eventClick: function(info) {
                    const sebaName = info.event.extendedProps.sebaName;
                    const beddhaId = info.event.extendedProps.beddhaId;

                    document.getElementById('modalSebaName').innerText = sebaName;
                    document.getElementById('modalBeddhaId').innerText = beddhaId;

                    const modal = new bootstrap.Modal(document.getElementById('eventModal'));
                    modal.show();
                }

            });

            calendar.render();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateEl = document.getElementById('today-date');
            const timeEl = document.getElementById('current-time');

            const now = new Date();

            const options = {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };
            dateEl.textContent = now.toLocaleDateString('en-US', options);

            function updateTime() {
                const current = new Date();
                timeEl.textContent = current.toLocaleTimeString();
            }

            updateTime();
            setInterval(updateTime, 1000);
        });
    </script>
@endsection
