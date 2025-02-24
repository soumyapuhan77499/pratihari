@extends('layouts.app')

@section('styles')
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@endsection

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center">
    <div class="left-content">
        <span class="main-content-title mg-b-0 mg-b-lg-1">MANAGE SAHI</span>
    </div>

    <div class="d-flex align-items-center">
        <a href="{{ route('addSahi') }}" class="btn btn-primary me-3">
            <i class="fa fa-plus"></i> Add Sahi
        </a>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Sahi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Manage Sahi</li>
        </ol>
    </div>
</div>



    <div class="container mt-4">
        <div class="contact-card">
            <h5 class="mb-3"><i class="fas fa-map-marker-alt"></i> Sahi List</h5>
            <div class="table-responsive export-table">
                <table id="file-datatable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sahi Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sahis as $index => $sahi)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $sahi->sahi_name }}</td>
                                <td>
                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-info edit-btn"
                                        data-id="{{ $sahi->id }}"
                                        data-sahi_name="{{ $sahi->sahi_name }}"
                                      >
                                        <i class="fa-solid fa-edit"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $sahi->id }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No Groups available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editSahiModal" tabindex="-1" aria-labelledby="editSahiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Sahi</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editSahiForm">
                    @csrf
                    <input type="hidden" id="edit_sahi_id">
                    <div class="form-group">
                        <label for="edit_sahi_name">Sahi Name</label>
                        <input type="text" class="form-control" id="edit_sahi_name" name="sahi_name" required>
                    </div>
                   
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/table-data.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function () {
        // Open Edit Modal
        $('.edit-btn').click(function () {
            $('#edit_sahi_id').val($(this).data('id'));
            $('#edit_sahi_name').val($(this).data('sahi_name')); // Fixed issue here
            $('#editSahiModal').modal('show');
        });
    
        // Update Sahi
        $('#editSahiForm').submit(function (e) {
            e.preventDefault();
            let id = $('#edit_sahi_id').val();
    
            $.ajax({
                url: `/admin/sahi/update/${id}`,
                type: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    if (response.success) {
                        Swal.fire("Updated!", response.message, "success").then(() => location.reload());
                    }
                },
                error: function (xhr) {
                    Swal.fire("Error!", xhr.responseJSON.message, "error");
                }
            });
        });
    
        // Delete Sahi (Soft Delete)
        $(document).on("click", ".delete-btn", function () {
            let id = $(this).data("id");
    
            Swal.fire({
                title: "Are you sure?",
                text: "This will mark the Sahi as deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/sahi/delete/${id}`,
                        type: "POST", 
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            Swal.fire("Deleted!", response.message, "success").then(() => location.reload());
                        },
                        error: function (xhr) {
                            Swal.fire("Error!", xhr.responseJSON.message, "error");
                        }
                    });
                }
            });
        });
    });
</script>    

    
<script>
    $(document).ready(function () {
        $("#editSahiForm").submit(function (e) {
            e.preventDefault();

            // Simulating form submission (replace with actual AJAX request if needed)
            setTimeout(function () {
                $("#editSahiModal").modal("hide"); // Close modal after submission
            }, 500); // Delay for a smooth transition
        });
    });
</script>

@endsection
