@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

     <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
          
    <style>
        #custom-calendar {
            max-width: 100%;
            margin: 0 auto;
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
        <h2 class="mb-4">Pratihari Admin Dashboard</h2>
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
                        <div class="card-body"  style="background-color: #6aefeb">
                            <h5 class="card-title">{{ $todayCount }}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('admin.pratihari.filterUsers', 'incomplete') }}" style="text-decoration:none;">
                    <div class="card text-dark mb-3" >
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
                    <div class="card text-dark mb-3" >
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

        <div class="col-lg-12 mb-3">
            <div class="card custom-card" style="background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%); color: #1d1818;">
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
                                        <td>{{ $profile->first_name }} {{ $profile->middle_name }} {{ $profile->last_name }}
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
                                                <button class="btn btn-success btn-sm approve-btn" data-id="{{ $profile->id }}">Approve</button>
                                                <button class="btn btn-danger btn-sm reject-btn"  data-id="{{ $profile->id }}">Reject</button>
                                            @elseif ($profile->pratihari_status === 'updated')
                                                <button class="btn btn-success btn-sm approve-btn"  data-id="{{ $profile->id }}">Approve</button>
                                                <button class="btn btn-danger btn-sm reject-btn"  data-id="{{ $profile->id }}">Reject</button>
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

        <div class="col-lg-12 mb-4">
            <div class="card custom-card">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-calendar-event me-2"></i>Custom Calendar
                </div>
                <div class="card-body">
                    <div id="custom-calendar"></div>
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
                var calendarEl = document.getElementById('custom-calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    height: 500,
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    events: [
                        // Example events, replace or load dynamically as needed
                        {
                            title: 'Sample Event',
                            start: new Date().toISOString().slice(0,10)
                        }
                    ]
                });
                calendar.render();
            });
        </script>
@endsection
