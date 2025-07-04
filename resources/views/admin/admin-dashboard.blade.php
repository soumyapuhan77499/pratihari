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

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1.2rem rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            font-weight: bold;
            font-size: 1rem;
            text-transform: uppercase;
            background-color: rgba(0, 0, 0, 0.05);
            border-bottom: 2px solid #dee2e6;
        }

        .card-title {
            font-size: 25px;
            font-weight: bold;
            color: white;
        }

        .card-body p {
            margin: 0.2rem 0;
            color: white;
        }

        /* Specific background colors with contrast text */
        .bg-primary {
            background-color: #007bff !important;
            color: #fff !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
            color: #212529 !important;
        }

        .bg-success {
            background-color: #28a745 !important;
            color: #fff !important;
        }

        .bg-danger {
            background-color: #dc3545 !important;
            color: #fff !important;
        }

        @media (max-width: 768px) {
            .card-title {
                font-size: 1.5rem;
            }

            .card-header {
                font-size: 0.9rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row mb-4 mt-4">
            <div class="col-lg-12">
                <div class="card custom-card" style="background: linear-gradient(90deg, #007bff 0%, #6a11cb 100%); color: #100f0f;">
                    <div class="card-body d-flex align-items-center">
                        <div>
                            <h2 class="mb-0" style="font-weight: bold;">Pratihari Admin Dashboard</h2>
                            <small>Welcome to the admin dashboard. Manage users, view statistics, and monitor activities here.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Active Users -->
            <div class="col-md-3">
                <a href="{{ route('admin.pratihari.filterUsers', 'approved') }}" style="text-decoration:none;">
                    <div class="card text-dark bg-success mb-3">
                        <div class="card-header">
                            <i class="bi bi-people-fill me-2"></i>Active Users
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $totalActiveUsers }}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Rejected Users -->
            <div class="col-md-3">
                <a href="{{ route('admin.pratihari.filterUsers', 'pending') }}" style="text-decoration:none;">
                    <div class="card text-dark mb-3">
                        <div class="card-header">
                            <i class="bi bi-person-x-fill me-2"></i>Pending Users
                        </div>
                        <div class="card-body" style="background-color: #35dc3b">
                            <h5 class="card-title">{{ $pendingProfile }}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Rejected Users -->
            <div class="col-md-3">
                <a href="{{ route('admin.pratihari.filterUsers', 'rejected') }}" style="text-decoration:none;">
                    <div class="card text-dark bg-danger mb-3">
                        <div class="card-header">
                            <i class="bi bi-person-x-fill me-2"></i>Rejected Users
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $rejectedUsers }}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('admin.pratihari.filterUsers', 'updated') }}" style="text-decoration:none;">
                    <div class="card text-dark bg-warning mb-3">
                        <div class="card-header">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>Updated Profiles
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $updatedProfile }}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Today's Registrations -->
            <div class="col-md-3">
                <a href="{{ route('admin.pratihari.filterUsers', 'today') }}" style="text-decoration:none;">
                    <div class="card text-dark mb-3">
                        <div class="card-header">
                            <i class="bi bi-person-plus-fill me-2"></i>Today's Registrations
                        </div>
                        <div class="card-body" style="background-color: #6aefeb">
                            <h5 class="card-title">{{ $todayCount }}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('admin.pratihari.filterUsers', 'incomplete') }}" style="text-decoration:none;">
                    <div class="card text-dark mb-3">
                        <div class="card-header">
                            <i class="bi bi-person-plus-fill me-2"></i>Incomplete Profiles
                        </div>
                        <div class="card-body" style="background-color: #efb86a">
                            <h5 class="card-title">{{ $incompleteProfiles }}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('today.application.filterUsers') }}" style="text-decoration:none;">
                    <div class="card text-dark mb-3">
                        <div class="card-header">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>Todays Applications
                        </div>
                        <div class="card-body" style="background-color: #38075e">
                            <h5 class="card-title">{{ $todayApplication }}</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row mb-4 mt-4">
            <div class="col-lg-12">
                <div class="card custom-card" style="background: linear-gradient(90deg, #f8c66d 0%, #e96a01 100%); color: #1d1818;">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-calendar-event me-3" style="font-size: 2rem;"></i>
                        <div>
                            <h4 class="mb-0" style="font-weight: bold;">Pratihari Seba Calendar</h4>
                            <small>View and manage Seba assignments for Pratihari users below.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Filter by Pratihari Name -->
            <div class="col-lg-4 mb-4 mx-auto d-flex justify-content-center">
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
                                            {{ $profile->first_name }} {{ $profile->middle_name }} {{ $profile->last_name }}
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
        </div>


        <div class="col-lg-12 mb-3">
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
        </div>

        <div class="col-lg-12">
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
        </div>

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
@endsection
