@extends('layouts.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

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
            background-image: linear-gradient(170deg, #FBAB7E 0%, #F7CE68 100%);
            color: #f6f3f1;
            font-size: 25px;
            font-weight: bold;
            text-align: center;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .custom-gradient-btn {
            background-image: linear-gradient(170deg, #ddb95e 100%, #f59d6a 0%);
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
    <div class="row">
        <div class="col-12 mt-2">
            <div class="card">
                <div class="card-header"><i class="fa fa-map-marker-alt" style="color: rgb(247, 247, 251)"></i>  Add Admin Details</div>
                <div class="card-body">
                    <form action="{{ route('superadmin.saveAdminRegister') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <label for="first_name">First Name</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text" style="background-color: #FBAB7E"><i class="fa fa-user" style="color: rgb(234, 234, 238)"></i></span>
                                    <input type="text" name="first_name" id="first_name" class="form-control">
                                </div>
                            </div>
                    
                            <!-- Middle Name -->
                            <div class="col-md-6">
                                <label for="middle_name">Last Name</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text" style="background-color: #FBAB7E"><i class="fa fa-user" style="color: rgb(246, 246, 247)"></i></span>
                                    <input type="text" name="last_name" id="last_name" class="form-control">
                                </div>
                            </div>
                    
                            <div class="col-md-6">
                                <label for="phonenumber">Phone No</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text" style="background-color: #FBAB7E" ><i class="fa fa-phone" style="color: rgb(234, 234, 243)"></i></span>
                                    <input type="tel" class="form-control" id="phonenumber" name="phonenumber" pattern="\d{10}" maxlength="10">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="photo">Admin Photo</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text" style="background-color: #FBAB7E" ><i class="fa fa-camera" style="color: rgb(249, 249, 254)"></i></span>
                                    <input type="file" class="form-control" id="photo" name="photo">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-lg mt-3 w-50 custom-gradient-btn text-white">
                                <i class="fa fa-save"></i> Submit
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                timer: 5000,
                showConfirmButton: true
            });
        });
    </script>
@endif

@endsection
