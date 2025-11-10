@extends('layouts.app')

@section('styles')
    <!-- Bootstrap 5 + Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- DataTables (Bootstrap 5 skin) -->
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet" />

    <style>
        :root{
            --brand-a:#7c3aed; /* violet */
            --brand-b:#06b6d4; /* cyan   */
            --ink:#0b1220;
            --muted:#64748b;
            --border:rgba(2,6,23,.10);
        }

        /* Page header */
        .page-header{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff;border-radius:1rem;padding:1rem 1.25rem;
            box-shadow:0 10px 24px rgba(6,182,212,.18);
        }
        .page-header .title{font-weight:800;letter-spacing:.3px;}

        /* Card + table look */
        .custom-card{ border:1px solid var(--border); border-radius:14px; box-shadow:0 8px 22px rgba(2,6,23,.06); }
        .card-header.gradient{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff; font-weight:800; letter-spacing:.3px; text-transform:uppercase;
            border-radius:14px 14px 0 0; display:flex; align-items:center; justify-content:center; gap:.6rem;
        }
        .table thead th{
            background:#f8fafc; font-weight:800; color:var(--ink); border-bottom:2px solid #e2e8f0;
        }
        .table td{ vertical-align: middle; }

        /* Toolbar */
        .toolbar .btn{
            border-radius:10px; font-weight:700;
        }
        .btn-brand{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            border:0; color:#fff; box-shadow:0 14px 30px rgba(124,58,237,.25);
        }
        .btn-brand:hover{ opacity:.96; }

        /* Badges for dates */
        .date-badge{ background:#eef2ff; color:#3730a3; font-weight:700; }

        /* Modal */
        .modal-header{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b)); color:#fff;
        }
        .form-label{ font-weight:700; color:var(--ink); }
    </style>
@endsection

@section('content')
    <!-- Top Header -->
    <div class="page-header mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <div class="title h4 mb-0"><i class="fa-solid fa-bullhorn me-2"></i>Manage Notice</div>
                <div class="small opacity-75">Create, update, and remove notices. Use search/export from the table header.</div>
            </div>
            <div class="toolbar">
                <a href="{{ url('admin/add-notice') }}" class="btn btn-warning">
                    <i class="fa-solid fa-plus me-1"></i> Add Notice
                </a>
            </div>
        </div>
    </div>

    <!-- Breadcrumb (compact) -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Manage Notice</li>
        </ol>
    </nav>

    <!-- Table Card -->
    <div class="card custom-card overflow-hidden">
        <div class="card-header gradient">
            <i class="fa-solid fa-clipboard-list"></i> <span>Notices</span>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div id="flashSuccess" class="alert alert-success d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div id="flashError" class="alert alert-danger d-flex align-items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
                </div>
            @endif

            <div class="table-responsive export-table">
                <table id="file-datatable" class="table table-striped table-hover text-nowrap align-middle">
                    <thead>
                        <tr>
                            <th>SlNo</th>
                            <th>Photo</th> <!-- NEW -->
                            <th>Notice</th>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>Description</th>
                            <th style="width:160px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($notices as $notice)
                            @php
                                $photoUrl = $notice->notice_photo ? asset('storage/'.$notice->notice_photo) : '';
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <!-- NEW: photo thumbnail -->
                                <td>
                                    @if($photoUrl)
                                        <img src="{{ $photoUrl }}" class="rounded border"
                                             style="width:60px;height:60px;object-fit:cover" alt="Photo">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td class="fw-bold">{{ $notice->notice_name }}</td>
                                <td>
                                    <span class="badge rounded-pill date-badge">
                                        {{ \Carbon\Carbon::parse($notice->from_date)->format('d-m-Y') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill date-badge">
                                        {{ \Carbon\Carbon::parse($notice->to_date)->format('d-m-Y') }}
                                    </span>
                                </td>
                                <td class="text-wrap" style="max-width: 420px;">
                                    {{ $notice->description }}
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-success"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editNoticeModal"
                                                data-id="{{ $notice->id }}"
                                                data-name="{{ $notice->notice_name }}"
                                                data-from="{{ $notice->from_date }}"
                                                data-to="{{ $notice->to_date }}"
                                                data-description="{{ $notice->description }}"
                                                data-photo-url="{{ $photoUrl }}">
                                            <i class="fa fa-edit"></i>
                                        </button>

                                        <form id="delete-form-{{ $notice->id }}"
                                              action="{{ route('deleteNotice', $notice->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete({{ $notice->id }})">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Edit Modal -->
                <div class="modal fade" id="editNoticeModal" tabindex="-1" aria-labelledby="editNoticeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <form method="POST" id="editNoticeForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Notice</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="notice-id">
                                    <div class="row g-3">
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label class="form-label">Notice Name</label>
                                                <input type="text" name="notice_name" class="form-control" id="notice-name" required maxlength="150">
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">From Date</label>
                                                    <input type="date" name="from_date" class="form-control" id="notice-from" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">To Date</label>
                                                    <input type="date" name="to_date" class="form-control" id="notice-to" required>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control" id="notice-description" rows="4" placeholder="Optional"></textarea>
                                            </div>
                                        </div>

                                        <!-- Right column: current preview + upload -->
                                        <div class="col-md-4">
                                            <label class="form-label d-block">Current Photo</label>
                                            <div class="border rounded p-2 text-center">
                                                <img id="edit-photo-preview" src="" alt="No photo" class="img-fluid rounded"
                                                     style="max-height:180px;object-fit:contain;display:none;">
                                                <div id="no-photo-text" class="text-muted small">No photo available</div>
                                            </div>

                                            <div class="mt-3">
                                                <label class="form-label">Upload New Photo <span class="text-hint">(optional)</span></label>
                                                <input type="file" class="form-control" id="notice-photo" name="notice_photo" accept="image/*">
                                                <small class="text-muted d-block mt-1">Max 2MB (JPG/PNG/WebP). Uploading replaces the existing photo.</small>

                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" value="1" id="remove-photo" name="remove_photo">
                                                    <label class="form-check-label" for="remove-photo">Remove existing photo</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- /row -->
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-brand">
                                        <i class="fa-regular fa-floppy-disk me-1"></i> Update Notice
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!-- /modal -->
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery (needed for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap 5 bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables + Buttons -->
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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // DataTable init
        $(function () {
            const dt = $('#file-datatable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'copy',  className: 'btn btn-light btn-sm' },
                    { extend: 'csv',   className: 'btn btn-light btn-sm' },
                    { extend: 'excel', className: 'btn btn-light btn-sm' },
                    { extend: 'pdf',   className: 'btn btn-light btn-sm' },
                    { extend: 'print', className: 'btn btn-light btn-sm' },
                    { extend: 'colvis',className: 'btn btn-light btn-sm' },
                ],
                responsive: true,
                pageLength: 10,
                order: [[0,'asc']]
            });

            // Flash messages auto-hide
            setTimeout(() => $('#flashSuccess').slideUp(200), 3000);
            setTimeout(() => $('#flashError').slideUp(200), 3000);
        });

        // Delete confirm
        function confirmDelete(id) {
            Swal.fire({
                title: 'Delete this notice?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0ea5e9',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Yes, delete it'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        // Populate Edit Modal + photo preview
        document.addEventListener('DOMContentLoaded', function(){
            const editModal = document.getElementById('editNoticeModal');
            const form = document.getElementById('editNoticeForm');
            const inputFile = document.getElementById('notice-photo');
            const imgPreview = document.getElementById('edit-photo-preview');
            const noPhotoTxt = document.getElementById('no-photo-text');

            // Show modal: fill data
            editModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id    = button.getAttribute('data-id');
                const name  = button.getAttribute('data-name') || '';
                const from  = button.getAttribute('data-from') || '';
                const to    = button.getAttribute('data-to') || '';
                const desc  = button.getAttribute('data-description') || '';
                const photo = button.getAttribute('data-photo-url') || '';

                // Set form action (match your route)
                form.setAttribute('action', '/admin/notice/update/' + id); // adjust if route differs

                // Fill fields
                document.getElementById('notice-id').value = id;
                document.getElementById('notice-name').value = name;
                // Ensure yyyy-mm-dd for date inputs
                document.getElementById('notice-from').value = from ? from.substring(0,10) : '';
                document.getElementById('notice-to').value   = to ? to.substring(0,10) : '';
                document.getElementById('notice-description').value = desc;

                // Reset file input & remove checkbox
                inputFile.value = '';
                document.getElementById('remove-photo').checked = false;

                // Set current preview
                if (photo) {
                    imgPreview.src = photo;
                    imgPreview.style.display = 'block';
                    noPhotoTxt.style.display = 'none';
                } else {
                    imgPreview.removeAttribute('src');
                    imgPreview.style.display = 'none';
                    noPhotoTxt.style.display = 'block';
                }
            }, false);

            // Live preview when selecting a new image
            inputFile.addEventListener('change', function(){
                if (this.files && this.files[0]) {
                    const url = URL.createObjectURL(this.files[0]);
                    imgPreview.src = url;
                    imgPreview.style.display = 'block';
                    noPhotoTxt.style.display = 'none';
                }
            });
        });
    </script>
@endsection
