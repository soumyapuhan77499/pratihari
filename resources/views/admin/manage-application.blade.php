@extends('layouts.app')

@section('styles')
    <!-- Data table css -->
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet" />
    <!-- INTERNAL Select2 css -->
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.6.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
          .card-header {
            background: linear-gradient(90deg, #007bff 0%, #6a11cb 100%);
            color: rgb(240, 242, 248);
            font-size: 25px;
            font-weight: bold;
            text-align: center;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            letter-spacing: 1px;
            text-transform: uppercase;
        }
    </style>
@endsection

@section('content')
<div class="card-header mt-4">Pratihari Application</div>
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <span class="main-content-title mg-b-0 mg-b-lg-1">Manage Application</span>
        </div>
        <div class="justify-content-center mt-2">
            <ol class="breadcrumb d-flex justify-content-between align-items-center">
                <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Dashboard</a></li>
            </ol>
        </div>
    </div>

    <!-- Row -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-body">
                    <div class="table-responsive export-table">
                        <table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">SlNo</th>
                                    <th class="border-bottom-0">Date</th>
                                    <th class="border-bottom-0">Pratihari Name</th>
                                    <th class="border-bottom-0">Header</th>
                                    <th class="border-bottom-0">Body</th>
                                    <th class="border-bottom-0">Photo</th>
                                    <th class="border-bottom-0">Update</th>
                                    <th class="border-bottom-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($applications as $application)
                                    @php
                                        $profile = $application->profile;
                                        $fullName = trim(
                                            ($profile->first_name ?? '') .
                                                ' ' .
                                                ($profile->middle_name ?? '') .
                                                ' ' .
                                                ($profile->last_name ?? ''),
                                        );
                                    @endphp

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $application->created_at->format('d-m-Y') }}</td>
                                        <td>{{ $fullName ?: 'Unknown' }}</td>
                                        <td>{{ $application->header }}</td>
                                        <td>
                                            <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#viewBodyModal" data-body="{{ $application->body }}">
                                                View Text
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#viewPhotoModal" data-photo="{{ $application->photo }}">
                                                View Photo
                                            </button>
                                        </td>

                                        <td style="color:#B7070A;font-size: 15px">
                                            <button class="btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#editApplicationModal" data-id="{{ $application->id }}"
                                                data-header="{{ $application->header }}"
                                                data-body="{{ $application->body }}">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                        </td>

                                        <td style="font-size: 15px">
                                            @if ($application->status === 'pending')
                                                <button class="btn btn-success btn-sm"
                                                    onclick="confirmApprove({{ $application->id }})">Approve</button>
                                                <button class="btn btn-danger btn-sm"
                                                    onclick="confirmReject({{ $application->id }})">Reject</button>

                                                <!-- Hidden forms -->
                                                <form id="approve-form-{{ $application->id }}"
                                                    action="{{ route('application.approve', $application->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('PATCH')
                                                </form>

                                                <form id="reject-form-{{ $application->id }}"
                                                    action="{{ route('application.reject', $application->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="rejection_reason"
                                                        id="rejection-reason-{{ $application->id }}">
                                                </form>
                                            @elseif ($application->status === 'approved')
                                                <button class="btn btn-outline-success btn-sm" disabled>Approved</button>
                                            @elseif ($application->status === 'rejected')
                                                <button class="btn btn-outline-danger btn-sm" disabled>Rejected</button>
                                                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#rejectionReasonModal-{{ $application->id }}">
                                                     Reason
                                                </button>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                             
                            </tbody>
                        </table>

                           @foreach ($applications as $application)
                                    @if ($application->status === 'rejected')
                                        <div class="modal fade" id="rejectionReasonModal-{{ $application->id }}"
                                            tabindex="-1"
                                            aria-labelledby="rejectionReasonModalLabel-{{ $application->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title"
                                                            id="rejectionReasonModalLabel-{{ $application->id }}">Rejection
                                                            Reason</h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>{{ $application->rejection_reason ?: 'No reason provided.' }}
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm"
                                                            data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editApplicationModal" tabindex="-1" aria-labelledby="editModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form method="POST" id="editApplicationForm" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Application</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" id="edit-id">
                                            <div class="mb-3">
                                                <label>Header</label>
                                                <input type="text" class="form-control" name="header"
                                                    id="edit-header" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Body</label>
                                                <textarea class="form-control" name="body" id="edit-body" rows="4" required></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label>Update Photo (optional)</label>
                                                <input type="file" class="form-control" name="photo">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- View Body Modal -->
                        <div class="modal fade" id="viewBodyModal" tabindex="-1" aria-labelledby="viewBodyModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Application Body</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p id="modal-body-text"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- View Photo Modal -->
                        <div class="modal fade" id="viewPhotoModal" tabindex="-1" aria-labelledby="viewPhotoModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Application Photo</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img id="modal-photo-img" src="" alt="Application Photo"
                                            class="img-fluid" style="max-height: 400px;">
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->
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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Hide success/error message after 3 seconds
        setTimeout(function() {
            document.getElementById('Message').style.display = 'none';
        }, 3000);
    </script>

    <!-- jQuery & Bootstrap JS FIRST -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.6.2/js/bootstrap.min.js"></script>

    <script>
        // Edit modal fill
        $('#editApplicationModal').on('show.bs.modal', function(event) {
            let button = $(event.relatedTarget);
            let id = button.data('id');
            let header = button.data('header');
            let body = button.data('body');

            $('#edit-id').val(id);
            $('#edit-header').val(header);
            $('#edit-body').val(body);

            $('#editApplicationForm').attr('action', '/admin/application/update/' + id);
        });
    </script>

    <script>
        // View Body Modal
        $('#viewBodyModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const bodyText = button.data('body');
            $('#modal-body-text').text(bodyText);
        });

        // View Photo Modal
        $('#viewPhotoModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const photoPath = button.data('photo');

            // Build full URL
            const fullPhotoUrl = photoPath ? '{{ config('app.photo_url') }}' + photoPath : '';
            $('#modal-photo-img').attr('src', fullPhotoUrl);
        });
    </script>

    <script>
        function confirmApprove(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to approve this application.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, approve it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('approve-form-' + id).submit();
                }
            });
        }

        function confirmReject(id) {
            Swal.fire({
                title: 'Reject Application',
                input: 'textarea',
                inputLabel: 'Reason for rejection',
                inputPlaceholder: 'Enter reason...',
                inputAttributes: {
                    'aria-label': 'Type your message here'
                },
                showCancelButton: true,
                confirmButtonText: 'Reject',
                cancelButtonText: 'Cancel',
                preConfirm: (reason) => {
                    if (!reason) {
                        Swal.showValidationMessage('Rejection reason is required');
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('rejection-reason-' + id).value = result.value;
                    document.getElementById('reject-form-' + id).submit();
                }
            });
        }
    </script>
@endsection
