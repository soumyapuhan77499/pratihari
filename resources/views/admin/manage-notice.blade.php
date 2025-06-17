@extends('layouts.app')

@section('styles')
    <!-- Data table css -->
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet" />
    <!-- INTERNAL Select2 css -->
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.6.2/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <span class="main-content-title mg-b-0 mg-b-lg-1">Manage Notice</span>
        </div>
        <div class="justify-content-center mt-2">
            <ol class="breadcrumb d-flex justify-content-between align-items-center">
                <a href="{{ url('admin/add-notice') }}" class="breadcrumb-item tx-15 btn btn-warning">Add Notice</a>
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
                                    <th class="border-bottom-0">Notice</th>
                                    <th class="border-bottom-0">From Date</th>
                                    <th class="border-bottom-0">To Date</th>
                                    <th class="border-bottom-0">Description</th>
                                    <th class="border-bottom-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($notices as $notice)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $notice->notice_name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($notice->from_date)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($notice->to_date)->format('d-m-Y') }}</td>
                                        <td>{{ $notice->description }}</td>
                                        <td style="color:#B7070A;font-size: 15px">
                                            <button class="btn btn-success" data-toggle="modal"
                                                data-target="#editNoticeModal" data-id="{{ $notice->id }}"
                                                data-name="{{ $notice->notice_name }}"
                                                data-from="{{ $notice->from_date }}" data-to="{{ $notice->to_date }}"
                                                data-description="{{ $notice->description }}">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            <form id="delete-form-{{ $notice->id }}"
                                                action="{{ route('deleteNotice', $notice->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger"
                                                    onclick="confirmDelete({{ $notice->id }})">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                            <!-- Modal -->
                            <div class="modal fade" id="editNoticeModal" tabindex="-1"
                                aria-labelledby="editNoticeModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="POST" id="editNoticeForm">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Notice</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" id="notice-id">
                                                <div class="form-group">
                                                    <label>Notice Name</label>
                                                    <input type="text" name="notice_name" class="form-control"
                                                        id="notice-name" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>From Date</label>
                                                    <input type="date" name="from_date" class="form-control"
                                                        id="notice-from" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>To Date</label>
                                                    <input type="date" name="to_date" class="form-control" id="notice-to"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <textarea name="description" class="form-control" id="notice-description" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Update
                                                    Notice</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </table>
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
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#editNoticeModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                $('#editNoticeForm').attr('action', '/admin/update-notice/' + id);
                $('#notice-id').val(id);
                $('#notice-name').val(button.data('name'));
                $('#notice-from').val(button.data('from'));
                $('#notice-to').val(button.data('to'));
                $('#notice-description').val(button.data('description'));
            });

        });
    </script>



    <script>
        // Hide success/error message after 3 seconds
        setTimeout(function() {
            document.getElementById('Message').style.display = 'none';
        }, 3000);
    </script>

    <!-- jQuery & Bootstrap JS FIRST -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.6.2/js/bootstrap.min.js"></script>
@endsection
