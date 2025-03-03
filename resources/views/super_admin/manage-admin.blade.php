@extends('layouts.app')

@section('styles')
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@endsection

@section('content')
    <div class="breadcrumb-header d-flex justify-content-between align-items-center">
        <div class="left-content">
            <span class="main-content-title mg-b-0 mg-b-lg-1">MANAGE ADMIN</span>
        </div>
        <div class="d-flex align-items-center">
            <a href="{{ route('superadmin.addAdmin') }}" class="btn btn-primary me-3">
                <i class="fa fa-plus"></i> Add Admin
            </a>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active">Manage Admin</li>
            </ol>
        </div>
    </div>

    <div class="container mt-4">
        <div class="contact-card">
            <h5 class="mb-3"><i class="fas fa-map-marker-alt"></i> Admin List</h5>
            <div class="table-responsive export-table">
                <table id="file-datatable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Photo</th>
                            <th>Admin Name</th>
                            <th>Phone</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admins as $index => $admin)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><img src="{{ asset($admin->photo) }}" class="rounded-circle" width="50"></td>
                                <td>{{ $admin->first_name }} {{ $admin->last_name }}</td>
                                <td>{{ $admin->mobile_no }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info edit-btn" data-id="{{ $admin->id }}"
                                        data-first_name="{{ $admin->first_name }}"
                                        data-last_name="{{ $admin->last_name }}" data-mobile_no="{{ $admin->mobile_no }}"
                                        data-photo="{{ asset($admin->photo) }}">
                                        <i class="fa-solid fa-edit"></i>
                                    </button>

                                    <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $admin->id }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No Admins available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editAdminModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editAdminForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="edit_admin_id">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Admin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label>First Name</label>
                            <input type="text" class="form-control" id="edit_first_name" name="first_name">
                        </div>
                        <div class="mb-3">
                            <label>Last Name</label>
                            <input type="text" class="form-control" id="edit_last_name" name="last_name">
                        </div>
                        <div class="mb-3">
                            <label>Mobile No</label>
                            <input type="text" class="form-control" id="edit_mobile_no" name="mobile_no">
                        </div>
                        <div class="mb-3">
                            <label>Photo</label>
                            <input type="file" class="form-control" name="photo">
                            <img id="current_photo" class="mt-2" width="50">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {
    $('.edit-btn').on('click', function() {
        let id = $(this).data('id');
        $('#edit_admin_id').val(id);
        $('#edit_first_name').val($(this).data('first_name'));
        $('#edit_last_name').val($(this).data('last_name'));
        $('#edit_mobile_no').val($(this).data('mobile_no'));
        $('#current_photo').attr('src', $(this).data('photo'));
        $('#editAdminModal').modal('show');
    });

    $('#editAdminForm').on('submit', function(e) {
        e.preventDefault();

        let id = $('#edit_admin_id').val();
        let formData = new FormData(this);

        $.ajax({
            url: `/super-admin/update/${id}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire('Updated!', response.message, 'success').then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Failed to update admin', 'error');
            }
        });
    });

    $('.delete-btn').on('click', function() {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This will mark the admin as deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/super-admin/delete/${id}`,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire('Deleted!', response.message, 'success').then(() => location.reload());
                    },
                    error: function() {
                        Swal.fire('Error!', 'Failed to delete admin', 'error');
                    }
                });
            }
        });
    });
});

    </script>
@endsection
