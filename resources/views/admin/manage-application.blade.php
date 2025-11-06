@extends('layouts.app')

@section('styles')
    <!-- DataTables (Bootstrap 5 skins) -->
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet" />

    <!-- Select2 -->
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />

    <!-- Bootstrap 5 + Font Awesome (single source of truth) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

    <style>
        :root{
            --brand-a:#7c3aed; /* violet */
            --brand-b:#06b6d4; /* cyan   */
            --ink:#0b1220;
            --muted:#64748b;
            --border:rgba(2,6,23,.10);
        }

        .page-header{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff;border-radius:1rem;padding:1rem 1.25rem;
            box-shadow:0 10px 24px rgba(6,182,212,.18);
        }
        .page-header .title{font-weight:800;letter-spacing:.3px;}

        .card-header{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff;font-size:1.1rem;font-weight:800;text-transform:uppercase;
            border-radius:14px 14px 0 0; display:flex; align-items:center; gap:.6rem;
        }

        .card.custom-card{ border:1px solid var(--border); border-radius:14px; box-shadow:0 8px 22px rgba(2,6,23,.06); }

        .dt-buttons .btn{ margin-right:.25rem; }

        .badge-soft{
            background:#f8fafc;border:1px solid var(--border);color:var(--ink);
        }

        .table thead th{
            white-space:nowrap;
        }
    </style>
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header mt-3 mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <div class="title h4 mb-1"><i class="fa-solid fa-file-pen me-2"></i>Pratihari Application</div>
                <div class="small opacity-75">Manage incoming applications — review, edit, approve or reject.</div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manage Application</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Table card -->
    <div class="card custom-card overflow-hidden">
        <div class="card-header">
            <i class="fa-solid fa-table"></i> Applications
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-regular fa-circle-check me-1"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-regular fa-triangle-exclamation me-1"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive export-table">
                <table id="file-datatable" class="table table-bordered table-striped align-middle text-nowrap key-buttons border-bottom">
                    <thead class="table-light">
                        <tr>
                            <th>SlNo</th>
                            <th>Date</th>
                            <th>Pratihari Name</th>
                            <th>Header</th>
                            <th>Body</th>
                            <th>Photo</th>
                            <th>Update</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($applications as $application)
                        @php
                            $profile = $application->profile;
                            $fullName = trim(($profile->first_name ?? '').' '.($profile->middle_name ?? '').' '.($profile->last_name ?? ''));
                            $status = $application->status;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge badge-soft">{{ $application->created_at->format('d-m-Y') }}</span></td>
                            <td>{{ $fullName ?: 'Unknown' }}</td>
                            <td>{{ $application->header }}</td>
                            <td>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#viewBodyModal" data-body="{{ e($application->body) }}">
                                    <i class="fa-regular fa-eye me-1"></i> View Text
                                </button>
                            </td>
                            <td>
                                <button class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#viewPhotoModal"
                                        data-photo="{{ $application->photo ? asset($application->photo) : '' }}">
                                    <i class="fa-regular fa-image me-1"></i> View Photo
                                </button>
                            </td>
                            <td>
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editApplicationModal"
                                        data-id="{{ $application->id }}"
                                        data-header="{{ e($application->header) }}"
                                        data-body="{{ e($application->body) }}">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                            </td>
                            <td>
                                @if ($status === 'pending')
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-success" onclick="confirmApprove({{ $application->id }})">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" onclick="confirmReject({{ $application->id }})">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>

                                    <!-- Hidden forms -->
                                    <form id="approve-form-{{ $application->id }}"
                                          action="{{ route('application.approve', $application->id) }}"
                                          method="POST" class="d-none">
                                        @csrf
                                        @method('PATCH')
                                    </form>

                                    <form id="reject-form-{{ $application->id }}"
                                          action="{{ route('application.reject', $application->id) }}"
                                          method="POST" class="d-none">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="rejection_reason" id="rejection-reason-{{ $application->id }}">
                                    </form>
                                @elseif ($status === 'approved')
                                    <span class="badge text-bg-success"><i class="fa-solid fa-circle-check me-1"></i>Approved</span>
                                @elseif ($status === 'rejected')
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge text-bg-danger"><i class="fa-solid fa-ban me-1"></i>Rejected</span>
                                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#rejectionReasonModal-{{ $application->id }}">
                                            <i class="fa-regular fa-message"></i> Reason
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <!-- Rejection reason modals -->
                @foreach ($applications as $application)
                    @if ($application->status === 'rejected')
                        <div class="modal fade" id="rejectionReasonModal-{{ $application->id }}" tabindex="-1"
                             aria-labelledby="rejectionReasonModalLabel-{{ $application->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="rejectionReasonModalLabel-{{ $application->id }}">
                                            <i class="fa-regular fa-message me-1"></i> Rejection Reason
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-0">{{ $application->rejection_reason ?: 'No reason provided.' }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                <!-- Edit Modal -->
                <div class="modal fade" id="editApplicationModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" id="editApplicationForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><i class="fa-regular fa-pen-to-square me-1"></i> Edit Application</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="edit-id">
                                    <div class="mb-3">
                                        <label class="form-label">Header</label>
                                        <input type="text" class="form-control" name="header" id="edit-header" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Body</label>
                                        <textarea class="form-control" name="body" id="edit-body" rows="4" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Update Photo (optional)</label>
                                        <input type="file" class="form-control" name="photo" accept="image/*">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-regular fa-floppy-disk me-1"></i> Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- View Body Modal -->
                <div class="modal fade" id="viewBodyModal" tabindex="-1" aria-labelledby="viewBodyModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fa-regular fa-file-lines me-1"></i> Application Body</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p id="modal-body-text" class="mb-0"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- View Photo Modal -->
                <div class="modal fade" id="viewPhotoModal" tabindex="-1" aria-labelledby="viewPhotoModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fa-regular fa-image me-1"></i> Application Photo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img id="modal-photo-img" src="" alt="Application Photo" class="img-fluid rounded" style="max-height: 420px;">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /modals -->

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery (required by DataTables), Bootstrap 5 bundle -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables -->
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

    <!-- Select2 (if you later add filters) -->
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Fill Edit modal
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

        // View Body Modal
        $('#viewBodyModal').on('show.bs.modal', function(event) {
            const bodyText = $(event.relatedTarget).data('body') || '';
            $('#modal-body-text').text(bodyText);
        });

        // View Photo Modal
        $('#viewPhotoModal').on('show.bs.modal', function(event) {
            const photoUrl = $(event.relatedTarget).data('photo') || '';
            $('#modal-photo-img').attr('src', photoUrl);
        });

        // Approve / Reject
        function swalToast(icon, title){
            return Swal.fire({ icon, title, showConfirmButton:false, timer:1300 });
        }

        window.confirmApprove = function(id){
            Swal.fire({
                title: 'Approve application?',
                text: "This will mark the application as approved.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, approve',
                cancelButtonText: 'Cancel'
            }).then((res) => {
                if(res.isConfirmed){
                    document.getElementById('approve-form-'+id).submit();
                }
            });
        }

        window.confirmReject = function(id){
            Swal.fire({
                title: 'Reject Application',
                input: 'textarea',
                inputLabel: 'Reason for rejection',
                inputPlaceholder: 'Enter reason...',
                inputAttributes: { 'aria-label': 'Type your message here' },
                showCancelButton: true,
                confirmButtonText: 'Reject',
                cancelButtonText: 'Cancel',
                preConfirm: (reason) => {
                    if (!reason) { Swal.showValidationMessage('Rejection reason is required'); }
                    return reason;
                }
            }).then((res) => {
                if(res.isConfirmed){
                    document.getElementById('rejection-reason-'+id).value = res.value;
                    document.getElementById('reject-form-'+id).submit();
                }
            });
        }

        // Optional: tweak DataTables defaults if table-data.js doesn’t
        $(function(){
            // Auto-hide alerts
            setTimeout(() => $('.alert').alert('close'), 3500);
        });
    </script>
@endsection
