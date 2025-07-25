@extends('layouts.app')

@section('styles')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
            background-image: linear-gradient(170deg,#F7CE68  0%, #FBAB7E 100%);
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
            background-image: linear-gradient(170deg,#F7CE68  0%, #FBAB7E 100%);
            /* Purple to Blue Gradient */
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
            transform: translateY(-2px);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.3);
        }

    </style>
@endsection

@section('content')
    <!-- Display Success Message -->

    <div class="row">
        <div class="col-12 mt-4">
            <div class="card shadow-lg">
               
                <ul class="nav nav-tabs flex-column flex-sm-row" role="tablist">

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
                        <a class="nav-link" id="id-card-tab" data-toggle="tab" href="#"
                            role="tab" aria-controls="id-card" aria-selected="false">
                            <i class="fas fa-id-card"></i> ID Card
                        </a>
                    </li>
                    <li class="nav-item col-12 col-sm-auto">
                        <a class="nav-link" id="address-tab" data-toggle="tab" href="#"
                            role="tab" aria-controls="address" aria-selected="false">
                            <i class="fas fa-map-marker-alt"></i> Address
                        </a>
                    </li>
                    <li class="nav-item col-12 col-sm-auto">
                        <a class="nav-link" id="occupation-tab"  style="background-color: #e96a01;color: white"
                            data-toggle="tab" href="#" role="tab"
                            aria-controls="occupation" aria-selected="false">
                            <i class="fas fa-briefcase" style="color: white"></i> Occupation
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
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <form action="{{ route('admin.pratihari-occupation.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <input type="hidden" name="pratihari_id" value="{{ request('pratihari_id') }}">
                                    <!-- Occupation Field -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="occupation">Occupation</label>
                                            <div class="input-group">
                                                <span class="input-group-text" style="width: 35px;background-color:  #FBAB7E"><i
                                                        class="fas fa-briefcase" style="color: white"></i></span>
                                                <input type="text" name="occupation" id="occupation"
                                                    class="form-control" placeholder="Enter Occupation">
                                            </div>
                                        </div>
                                    </div>

                                      <!-- Extra Curriculum Activities (Multiple Add) -->
                                      <div class="col-md-6">
                                        <label for="extra_activity">Extra Curriculum Activity</label>
                                        <div id="extraActivityContainer">
                                            <div class="input-group mb-2">
                                                <span class="input-group-text" style="background-color:  #FBAB7E"><i class="fas fa-briefcase" style="color: rgb(243, 243, 246)"></i></span>
                                                <input type="text" name="extra_activity[]" class="form-control" placeholder="Enter Curriculum Activity">
                                                <button type="button" class="btn btn-success addMore" style="background-color: #e96a01;color: white"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                                                        
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-lg mt-3 w-50 custom-gradient-btn"
                                            style="color: white">
                                            <i class="fa fa-save"></i> Submit
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
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
    document.addEventListener("DOMContentLoaded", function () {
        const container = document.getElementById("extraActivityContainer");

        container.addEventListener("click", function (e) {
            if (e.target.closest('.addMore')) {
                const newField = document.createElement("div");
                newField.classList.add("input-group", "mb-2");
                newField.innerHTML = `
                    <span class="input-group-text" style="background-color: #FBAB7E"><i class="fas fa-briefcase" style="color: white"></i></span>
                    <input type="text" name="extra_activity[]" class="form-control" placeholder="Enter Curriculum Activity">
                    <button type="button" class="btn btn-danger remove"><i class="fas fa-trash"></i></button>
                `;
                container.appendChild(newField);
            }

            if (e.target.closest('.remove')) {
                e.target.closest(".input-group").remove();
            }
        });
    });
</script>


    <!-- FontAwesome for Icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
