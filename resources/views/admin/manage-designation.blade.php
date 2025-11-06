@extends('layouts.app')

@section('styles')
    <!-- Bootstrap 5 + (optional) Font Awesome if your layout doesn't already include it -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- DataTables (Bootstrap 5 skin) -->
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap5.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet"/>

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
        .card{ border:1px solid var(--border); border-radius:14px; box-shadow:0 8px 22px rgba(2,6,23,.06); }
        .card-header{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff; font-weight:800; letter-spacing:.3px; text-transform:uppercase;
            border-radius:14px 14px 0 0; display:flex; align-items:center; gap:.6rem; justify-content:center;
        }
        .dt-buttons .btn{ border-radius:.5rem; }
        table.dataTable thead th{ white-space:nowrap; }
        .badge-year{ background:rgba(6,182,212,.12); color:#0b7285; border:1px solid rgba(6,182,212,.25); }
        .toolbar{
            display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;
        }
        .toolbar .btn-warning{ font-weight:700; }
    </style>
@endsection

@section('content')
    <!-- Header -->
    <div class="page-header mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <div class="title h4 mb-0"><i class="fa-solid fa-user-tie me-2"></i>Manage Designation</div>
                <div class="small opacity-75">View and manage Pratihari designations by year.</div>
            </div>
            <a href="{{ url('admin/add-designation') }}" class="btn btn-warning fw-bold">
                <i class="fa-solid fa-square-plus me-1"></i> Add Designation
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-header">
                    <i class="fa-solid fa-clipboard-list"></i> Designation List
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="designationTable" class="table table-bordered table-striped align-middle w-100">
                            <thead>
                                <tr>
                                    <th style="width:60px">SlNo</th>
                                    <th>Year</th>
                                    <th>Pratihari Name</th>
                                    <th>Designation</th>
                                    <th style="width:110px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($designations as $designation)
                                    @php $profile = $designation->pratihariProfile; @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="badge rounded-pill badge-year px-3 py-2">
                                                <i class="fa-regular fa-calendar me-1"></i>{{ $designation->year }}
                                            </span>
                                        </td>
                                        <td>
                                            <i class="fa-regular fa-id-badge me-1 text-primary"></i>
                                            {{ trim(($profile->first_name ?? '').' '.($profile->middle_name ?? '').' '.($profile->last_name ?? '')) }}
                                        </td>
                                        <td>
                                            <i class="fa-solid fa-briefcase me-1 text-success"></i>
                                            {{ $designation->designation }}
                                        </td>
                                        <td class="text-center">
                                            <form id="delete-form-{{ $designation->id }}"
                                                  action="{{ route('deleteDesignation', $designation->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="confirmDelete({{ $designation->id }})"
                                                        title="Delete">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>SlNo</th>
                                    <th>Year</th>
                                    <th>Pratihari Name</th>
                                    <th>Designation</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div> <!-- /table-responsive -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery (DataTables needs it) + Bootstrap 5 bundle -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables + extensions (BS5 builds) -->
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/responsive.bootstrap5.min.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Delete confirm
        function confirmDelete(id) {
            Swal.fire({
                title: 'Delete this designation?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
            });
        }

        // DataTable init
        $(function () {
            const table = $('#designationTable').DataTable({
                responsive: true,
                lengthChange: true,
                pageLength: 10,
                order: [[1,'desc'], [0,'asc']], // Year desc, then SlNo
                dom:
                    "<'row mb-2'<'col-md-6 d-flex align-items-center'l><'col-md-6 d-flex justify-content-md-end justify-content-start toolbar'B>>" +
                    "<'row'<'col-12'tr>>" +
                    "<'row mt-2'<'col-md-5'i><'col-md-7 d-flex justify-content-md-end justify-content-start'p>>",
                buttons: [
                    { extend: 'copyHtml5', className: 'btn btn-outline-secondary btn-sm', text: '<i class="fa-regular fa-copy me-1"></i>Copy' },
                    { extend: 'excelHtml5', className: 'btn btn-outline-success btn-sm', text: '<i class="fa-regular fa-file-excel me-1"></i>Excel', title: 'Designations' },
                    { extend: 'pdfHtml5', className: 'btn btn-outline-danger btn-sm', text: '<i class="fa-regular fa-file-pdf me-1"></i>PDF', title: 'Designations', orientation: 'landscape', pageSize: 'A4' },
                    { extend: 'print', className: 'btn btn-outline-primary btn-sm', text: '<i class="fa-solid fa-print me-1"></i>Print', title: 'Designations' }
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search..."
                }
            });

            // Move an extra “Add Designation” button into toolbar if you want duplication at top-right:
            // $('.toolbar').prepend(`<a href="{{ url('admin/add-designation') }}" class="btn btn-warning btn-sm fw-bold"><i class="fa-solid fa-square-plus me-1"></i>Add</a>`);
        });
    </script>
@endsection
