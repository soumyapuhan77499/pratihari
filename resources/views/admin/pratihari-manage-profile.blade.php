@extends('layouts.app')

@section('styles')
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .card-header {
            background: linear-gradient(135deg, #f8f19e, #dcf809);
            /* Blue to Purple Gradient */
            color: rgb(78, 51, 251);
            font-size: 23px;
            font-weight: bold;
            text-align: center;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            /* Rounded top corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Adds a shadow effect */
            letter-spacing: 1px;
            /* Improves readability */
            text-transform: uppercase;
            /* Makes it look more professional */
        }

        .btn-primary {
            background: #007bff;
            border: none;
            font-size: 16px;
            padding: 10px 20px;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .alert-success {
            font-weight: bold;
            text-align: center;
        }

        .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            transition: transform 0.3s ease;
            display: block;
        }

        .profile-img:hover {
            transform: scale(4);
        }
    </style>
@endsection
@section('content')
    <!-- Profile Form -->
    <div class="row">
        <div class="col-12 mt-2">
            <div class="card">
                <div class="card-header" style="text-shadow: 2px 1px 3px rgba(0,0,0,0.4)"><i class="fas fa-user-circle"
                        style="font-size: 2rem;margin-right: 5px;color:rgb(251, 51, 64);text-shadow: 2px 1px 3px rgba(0,0,0,0.4)"></i>Pratihari
                    Manage Profile</div>

                <div class="card-body">

                    <div class="table-responsive  export-table">
                        <table id="file-datatable" class="table table-bordered ">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Photo</th>
                                    <th>View</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Address</th>
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
                                            <img src="{{ asset('storage/' . $profile->profile_photo) }}" alt="Profile Photo" class="br-5" width="50" height="50">
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.viewProfile', $profile->pratihari_id) }}"
                                                class="btn btn-primary btn-sm">
                                                View Profile
                                            </a>
                                        </td>
                                        <td>{{ $profile->first_name }} {{ $profile->middle_name }} {{ $profile->last_name }}
                                        </td>
                                        <td>{{ $profile->phone_no }}</td>
                                        <td>
                                            <button class="btn btn-info btn-sm view-address" data-bs-toggle="modal"
                                                data-bs-target="#addressModal"
                                                data-address="{{ $profile->address->address ?? 'N/A' }}"
                                                data-district="{{ $profile->address->district ?? 'N/A' }}"
                                                data-state="{{ $profile->address->state ?? 'N/A' }}"
                                                data-country="{{ $profile->address->country ?? 'N/A' }}"
                                                data-pincode="{{ $profile->address->pincode ?? 'N/A' }}"
                                                data-landmark="{{ $profile->address->landmark ?? 'N/A' }}"
                                                data-police-station="{{ $profile->address->police_station ?? 'N/A' }}">
                                                <i class="fas fa-map-marker-alt"></i> View
                                            </button>
                                        </td>

                                        <td>{{ $profile->occupation->occupation_type ?? 'N/A' }}</td>
                                        <td>{{ $profile->healthcard_no }}</td>
                                        <td>{{ $profile->status }}</td>

                                        <td>
                                            <button class="btn btn-success btn-sm">Approve</button>
                                            <button class="btn btn-danger btn-sm">Reject</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>

        <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-address-card"></i> Pratihari Address Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th><i class="fas fa-map-marker-alt"></i> Address</th>
                                    <td id="modal-address">N/A</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-city"></i> District</th>
                                    <td id="modal-district">N/A</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-flag"></i> State</th>
                                    <td id="modal-state">N/A</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-globe"></i> Country</th>
                                    <td id="modal-country">N/A</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-map-pin"></i> Pincode</th>
                                    <td id="modal-pincode">N/A</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-road"></i> Landmark</th>
                                    <td id="modal-landmark">N/A</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-building"></i> Police Station</th>
                                    <td id="modal-police-station">N/A</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: "{{ session('success') }}",
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                @endif

                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: "{{ session('error') }}",
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                @endif
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll(".view-address").forEach(button => {
                    button.addEventListener("click", function() {
                        document.getElementById("modal-address").textContent = this.getAttribute(
                            "data-address");
                        document.getElementById("modal-district").textContent = this.getAttribute(
                            "data-district");
                        document.getElementById("modal-state").textContent = this.getAttribute(
                            "data-state");
                        document.getElementById("modal-country").textContent = this.getAttribute(
                            "data-country");
                        document.getElementById("modal-pincode").textContent = this.getAttribute(
                            "data-pincode");
                        document.getElementById("modal-landmark").textContent = this.getAttribute(
                            "data-landmark");
                        document.getElementById("modal-police-station").textContent = this.getAttribute(
                            "data-police-station");
                    });
                });
            });
        </script>



        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Bootstrap JS (for modal) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @endsection
