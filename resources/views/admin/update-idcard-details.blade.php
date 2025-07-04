@extends('layouts.app')

@section('styles')
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-group {
            position: relative;
        }

        .form-group i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #007bff;
        }

        .form-control {
            padding-left: 35px;
        }

        .card-header {
            background: linear-gradient(135deg, #f8f19e, #dcf809);
            /* Blue to Purple Gradient */
            color: rgb(51, 101, 251);
            font-size: 20px;
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

        /* Increase checkbox size */
        .largerCheckbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
            margin-top: 35px;
        }

        .nav-tabs {
            border-bottom: 3px solid #007bff;
            background-image: linear-gradient(170deg, #F7CE68 0%, #FBAB7E 100%);
            padding: 10px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
        }

        .nav-tabs .nav-link {
            color: #fff;
            font-weight: bold;
            border-radius: 10px;
            padding: 10px 15px;
            margin: 5px 0;
            text-align: center;
            text-transform: uppercase;
        }

        .nav-tabs .nav-link.active {
            background-color: #ff416c;
            color: #fff;
            border: 2px solid #ff416c;
            box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.1);
        }

        .nav-tabs .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #ff416c;
            border: 2px solid #ff416c;
        }

        .tab-content {
            padding: 20px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }

        .nav-tabs .nav-item {
            flex: 1;

        }

        .nav-tabs .nav-link {
            display: block;
        }

        .nav-tabs .nav-item.col-12 {
            margin-bottom: 10px;
        }

        .nav-tabs .nav-link i {
            color: rgb(29, 5, 108);
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .nav-tabs {
                flex-wrap: wrap;
                overflow-x: auto;
                white-space: nowrap;
            }

            .nav-item {
                flex-grow: 1;
                text-align: center;
            }

            .nav-tabs .nav-link {
                padding: 12px 15px;
                font-size: 14px;
            }
        }

        .custom-gradient-btn {
            background-image: linear-gradient(170deg, #F7CE68 0%, #FBAB7E 100%);
            border: none;
            color: white;
            padding: 12px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .custom-gradient-btn:hover {
            background: linear-gradient(135deg, #2575fc, #6a11cb);
            /* Reverse Gradient on Hover */
            transform: translateY(-2px);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.3);
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mt-2">
            
                <ul class="nav nav-tabs flex-column flex-sm-row mt-2" role="tablist">

                    <li class="nav-item col-12 col-sm-auto">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#"
                            role="tab" aria-controls="profile" aria-selected="true">
                            <i class="fas fa-user"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item col-12 col-sm-auto">
                        <a class="nav-link" id="family-tab" data-toggle="tab" href="#"
                            role="tab" aria-controls="family" aria-selected="true">
                            <i class="fas fa-users"></i> Family
                        </a>
                    </li>

                    <li class="nav-item col-12 col-sm-auto">
                        <a class="nav-link" id="id-card-tab" style="background-color:#e96a01;color: white"
                            data-toggle="tab" href="{{ route('admin.pratihariIdcard') }}" role="tab"
                            aria-controls="id-card" aria-selected="false">
                            <i class="fas fa-id-card" style="color: white"></i> ID Card
                        </a>
                    </li>
                    <li class="nav-item col-12 col-sm-auto">
                        <a class="nav-link" id="address-tab" data-toggle="tab" href="#"
                            role="tab" aria-controls="address" aria-selected="false">
                            <i class="fas fa-map-marker-alt"></i> Address
                        </a>
                    </li>
                    <li class="nav-item col-12 col-sm-auto">
                        <a class="nav-link" id="occupation-tab" data-toggle="tab"
                            href="#" role="tab" aria-controls="occupation"
                            aria-selected="false">
                            <i class="fas fa-briefcase"></i> Occupation
                        </a>
                    </li>

                    <li class="nav-item col-12 col-sm-auto">
                        <a class="nav-link" id="seba-details-tab" data-toggle="tab"
                            href="#" role="tab" aria-controls="seba-details"
                            aria-selected="false">
                            <i class="fas fa-cogs"></i> Seba
                        </a>
                    </li>

                    <li class="nav-item col-12 col-sm-auto">
                        <a class="nav-link" id="social-media-tab" data-toggle="tab"
                            href="#" role="tab" aria-controls="social-media"
                            aria-selected="false">
                            <i class="fas fa-share-alt" style="margin-right: 2px"></i>Social Media
                        </a>
                    </li>

                </ul>
                <div class="card-body">
                    <form action="{{ route('idcard.update', $pratihariId) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="pratihari_id" value="{{ $pratihariId }}">

                        <div id="id-section">
                            @foreach ($idCards as $idCard)
                                <div class="row id-entry mb-3 align-items-center">
                                    <div class="col-md-3">
                                        <label>ID Type</label>
                                        <select class="form-control" name="id_type[]" required>
                                            <option value="" disabled>Select ID Type</option>
                                            @foreach (['Aadhar Card', 'Voter ID', 'Driving License', 'Passport', 'PAN Card'] as $option)
                                                <option value="{{ $option }}"
                                                    {{ $idCard->id_type == $option ? 'selected' : '' }}>
                                                    {{ $option }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>ID Number</label>
                                        <input type="text" class="form-control" name="id_number[]" required
                                            value="{{ $idCard->id_number }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="id_photo">ID Photo Upload</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-camera"
                                                    style="color: #f5c12e"></i></span>
                                            <input type="file" class="form-control" name="id_photo[]">
                                        </div>
                                        @if (!empty($idCard->id_photo))
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-primary btn-sm view-image"
                                                    data-bs-toggle="modal" data-bs-target="#imageModal"
                                                    data-image="{{ $idCard->id_photo }}">
                                                    View
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal fade" id="imageModal" tabindex="-1"
                                        aria-labelledby="imageModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="imageModalLabel">ID Photo</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img id="modalImage" src="" class="img-fluid"
                                                        alt="ID Photo">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-danger w-100 mt-4 remove-btn"><i
                                                class="fas fa-minus"></i> Remove</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center">
                            <button type="button" class="btn btn-success mt-3" id="add-id-btn"><i
                                    class="fas fa-plus"></i> Add ID</button>
                            <button type="submit" class="btn mt-3 w-50 custom-gradient-btn"><i class="fa fa-save"></i>
                                Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById("add-id-btn").addEventListener("click", function() {
            let newIdEntry = document.createElement("div");
            newIdEntry.classList.add("row", "id-entry", "mb-3");
            newIdEntry.innerHTML = `
            <div class="col-md-3">
                <label class="id_type">ID Type</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-id-card" style="color: #f5c12e"></i></span>
                    <select class="form-control id-type-select" name="id_type[]" required>
                        <option value="" disabled selected>Select ID Type</option>
                        <option value="Aadhar Card">Aadhar Card</option>
                        <option value="Voter ID">Voter ID</option>
                        <option value="Driving License">Driving License</option>
                        <option value="Passport">Passport</option>
                        <option value="PAN Card">PAN Card</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <label class="id_number">ID Number</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-id-card" style="color: #f5c12e"></i></span>
                    <input type="text" class="form-control" name="id_number[]" required placeholder="Enter ID Number">
                </div>
            </div>
            <div class="col-md-3">
                <label class="id_photo">ID Photo Upload</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-camera" style="color: #f5c12e"></i></span>
                    <input type="file" class="form-control" name="id_photo[]">
                </div>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn remove-btn" style="color: white; margin-top: 26px; width: 100%; background-color: rgb(251, 51, 71);">
                    <i class="fas fa-minus"></i> Remove
                </button>
            </div>
        `;
            document.getElementById("id-section").appendChild(newIdEntry);
            newIdEntry.querySelector(".remove-btn").addEventListener("click", function() {
                newIdEntry.remove();
            });
        });
        document.querySelectorAll(".remove-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                this.closest(".id-entry").remove();
            });
        });
    </script>

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
        document.querySelectorAll('.view-image').forEach(button => {
            button.addEventListener('click', function() {
                let imageUrl = this.getAttribute('data-image');
                document.getElementById('modalImage').src = imageUrl;
            });
        });
    </script>


    <!-- Bootstrap JavaScript (Popper.js included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
