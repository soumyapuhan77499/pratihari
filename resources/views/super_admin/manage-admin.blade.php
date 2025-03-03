@extends('layouts.app')

@section('styles')
<!-- Add SweetAlert CSS if needed -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.2/dist/sweetalert2.min.css">
@endsection

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-header d-flex justify-content-between align-items-center">
    <div class="left-content">
        <span class="main-content-title mg-b-0 mg-b-lg-1">MANAGE ADMIN</span>
    </div>

    <div class="d-flex align-items-center">
        <a href="{{ route('superadmin.addAdmin') }}" class="btn btn-primary me-3">
            <i class="fa fa-plus"></i> Add Admin
        </a>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Admin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Manage Admin</li>
        </ol>
    </div>
</div>

<div class="container mt-4">
    <div class="contact-card">
        <div class="table-responsive export-table">
            <table id="file-datatable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Admin Name</th>
                        <th>Mobile No</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admins as $index => $admin)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><img src="{{ asset($admin->photo) }}" width="50" height="50"></td>
                        <td>{{ $admin->first_name }} {{ $admin->last_name }}</td>
                        <td>{{ $admin->mobile_no }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editAdmin({{ $admin }})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteAdmin({{ $admin->id }})">Delete</button>
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

<!-- Edit Admin Modal -->
<div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editAdminForm">
            @csrf
            <input type="hidden" id="admin_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>First Name</label>
                        <input type="text" id="first_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Last Name</label>
                        <input type="text" id="last_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Mobile No</label>
                        <input type="text" id="mobile_no" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Photo</label>
                        <input type="file" id="photo" class="form-control">
                        <img id="currentPhoto" src="" width="50" height="50" class="mt-2">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateAdmin()">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.2/dist/sweetalert2.all.min.js"></script>
<script>
    function editAdmin(admin) {
        $('#admin_id').val(admin.id);
        $('#first_name').val(admin.first_name);
        $('#last_name').val(admin.last_name);
        $('#mobile_no').val(admin.mobile_no);
        $('#currentPhoto').attr('src', '{{ asset('/') }}' + admin.photo);

        $('#editAdminModal').modal('show');
    }

    function updateAdmin() {
        let formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('first_name', $('#first_name').val());
        formData.append('last_name', $('#last_name').val());
        formData.append('mobile_no', $('#mobile_no').val());
        if ($('#photo')[0].files.length > 0) {
            formData.append('photo', $('#photo')[0].files[0]);
        }

        let adminId = $('#admin_id').val();
        $.ajax({
            url: '{{ url("super-admin/update") }}/' + adminId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire('Success', response.message, 'success')
                    .then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON.message, 'error');
            }
        });
    }

    function deleteAdmin(adminId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This admin will be soft deleted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("super-admin/delete") }}/' + adminId,
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        Swal.fire('Deleted!', response.message, 'success')
                            .then(() => location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON.message, 'error');
                    }
                });
            }
        });
    }
</script>
@endsection
