@extends('layouts.app')

@section('styles')
    <!-- Font Awesome for Icons -->
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

        .card-header {
            background: linear-gradient(135deg, #f8f19e, #dcf809);
            /* Blue to Purple Gradient */
            color: rgb(78, 51, 251);
            font-size: 25px;
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
            background: linear-gradient(45deg, #a3d4f7, #fb76bf);
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
            background: linear-gradient(135deg, #6a11cb, #2575fc);
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
            /* Reverse Gradient on Hover */
            transform: translateY(-2px);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.3);
        }
    </style>
@endsection


@section('content')
  
    <!-- Profile Form -->
    <div class="row">
        <div class="col-12 mt-2">
            <div class="card">
                <div class="card-header" style="text-shadow: 2px 1px 3px rgba(0,0,0,0.4)"><i class="fas fa-user-circle"
                        style="font-size: 1.8rem;margin-right: 5px;color:rgb(251, 51, 64);text-shadow: 2px 1px 3px rgba(0,0,0,0.4)"></i>Pratihari
                    Profile</div>
                <ul class="nav nav-tabs flex-column flex-sm-row mt-2" role="tablist">

                    <li class="nav-item col-12 col-sm-auto">
                        <a class="nav-link" id="profile-tab" style="background-color: rgb(49, 49, 181);color: white"
                            data-toggle="tab" href="{{ route('admin.pratihariProfile') }}" role="tab"
                            aria-controls="profile" aria-selected="true">
                            <i class="fas fa-user" style="color: white"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item col-12 col-sm-auto">
                        <a class="nav-link" id="family-tab" data-toggle="tab" href="{{ route('admin.pratihariFamily') }}"
                            role="tab" aria-controls="family" aria-selected="true">
                            <i class="fas fa-users"></i> Family
                        </a>
                    </li>

                    <li class="nav-item col-12 col-sm-auto">
                        <a class="nav-link" id="id-card-tab" data-toggle="tab" href="{{ route('admin.pratihariIdcard') }}"
                            role="tab" aria-controls="id-card" aria-selected="false">
                            <i class="fas fa-id-card"></i> ID Card
                        </a>
                    </li>
                    <li class="nav-item col-12 col-sm-auto">
                        <a class="nav-link" id="address-tab" data-toggle="tab" href="{{ route('admin.pratihariAddress') }}"
                            role="tab" aria-controls="address" aria-selected="false">
                            <i class="fas fa-map-marker-alt"></i> Address
                        </a>
                    </li>
                    <li class="nav-item col-12 col-sm-auto">
                        <a class="nav-link" id="occupation-tab" data-toggle="tab"
                            href="{{ route('admin.pratihariOccupation') }}" role="tab" aria-controls="occupation"
                            aria-selected="false">
                            <i class="fas fa-briefcase"></i> Occupation
                        </a>
                    </li>

                    <li class="nav-item col-12 col-sm-auto">
                        <a class="nav-link" id="seba-details-tab" data-toggle="tab"
                            href="{{ route('admin.pratihariSeba') }}" role="tab" aria-controls="seba-details"
                            aria-selected="false">
                            <i class="fas fa-cogs"></i> Seba
                        </a>
                    </li>

                    <li class="nav-item col-12 col-sm-auto">
                        <a class="nav-link" id="social-media-tab" data-toggle="tab"
                            href="{{ route('admin.pratihariSocialMedia') }}" role="tab" aria-controls="social-media"
                            aria-selected="false">
                            <i class="fas fa-share-alt" style="margin-right: 2px"></i>Social Media
                        </a>
                    </li>
                </ul>

                <div class="card-body">
                    <form action="{{ route('admin.pratihari-profile.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- First Name -->
                            <div class="col-md-3">
                                <label for="first_name">First Name</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fa fa-user" style="color: blue"></i></span>
                                    <input type="text" name="first_name" id="first_name" class="form-control">
                                </div>
                            </div>
                    
                            <!-- Middle Name -->
                            <div class="col-md-3">
                                <label for="middle_name">Middle Name</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fa fa-user" style="color: blue"></i></span>
                                    <input type="text" name="middle_name" id="middle_name" class="form-control">
                                </div>
                            </div>
                    
                            <!-- Last Name -->
                            <div class="col-md-3">
                                <label for="last_name">Last Name</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fa fa-user" style="color: blue"></i></span>
                                    <input type="text" name="last_name" id="last_name" class="form-control">
                                </div>
                            </div>
                    
                            <!-- Alias Name -->
                            <div class="col-md-3">
                                <label for="alias_name">Alias Name</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fa fa-user-tag" style="color: blue"></i></span>
                                    <input type="text" name="alias_name" id="alias_name" class="form-control">
                                </div>
                            </div>
                    
                            <!-- Email -->
                            <div class="col-md-3">Email ID</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fa fa-envelope" style="color: blue"></i></span>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                            </div>
                    
                            <!-- WhatsApp No -->
                            <div class="col-md-3">
                                <label for="whatsapp_no">WhatsApp No</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fa fa-phone" style="color: blue"></i></span>
                                    <input type="tel" class="form-control" id="whatsapp_no" name="whatsapp_no" pattern="\d{10}" maxlength="10">
                                </div>
                            </div>
                    
                            <!-- Phone No -->
                            <div class="col-md-3">
                                <label for="phone_no">Phone No</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fa fa-phone" style="color: blue"></i></span>
                                    <input type="tel" class="form-control" id="phone_no" name="phone_no" pattern="\d{10}" maxlength="10">
                                </div>
                            </div>
                    
                            <!-- Alternative Phone No -->
                            <div class="col-md-3">
                                <label for="alt_phone_no">Alternative Phone No</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fa fa-phone" style="color: blue"></i></span>
                                    <input type="tel" class="form-control" id="alt_phone_no" name="alt_phone_no" pattern="\d{10}" maxlength="10">
                                </div>
                            </div>
                    
                            <!-- Blood Group -->
                            <div class="col-md-3">
                                <label for="blood_group">Blood Group</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fa fa-tint" style="color: blue"></i></span>
                                    <select class="form-control" id="blood_group" name="blood_group">
                                        <option value="">Select Blood Group</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                    </select>
                                </div>
                            </div>
                    
                            <!-- Health Card No -->
                            <div class="col-md-3">
                                <label for="health_card_no">Health Card No</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fa fa-id-card" style="color: blue"></i></span>
                                    <input type="text" class="form-control" id="health_card_no" name="health_card_no">
                                </div>
                            </div>
                    
                            <!-- Profile Photo -->
                            <div class="col-md-3">
                                <label for="profile_photo">Profile Photo</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fa fa-camera" style="color: blue"></i></span>
                                    <input type="file" class="form-control" id="profile_photo" name="profile_photo">
                                </div>
                            </div>
                    
                            <!-- Date of Birth -->
                            <div class="col-md-3">
                                <label for="date_of_birth">Date of Birth</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fa fa-calendar" style="color: blue"></i></span>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                                </div>
                            </div>
                    
                            <!-- Joining Details -->
                            <div class="col-md-12">
                                <div class="row align-items-center">
                                    <div class="col-md-3 d-flex align-items-center" style="margin-left: 10px">
                                        <input type="checkbox" id="remember_date" onchange="toggleDateField()" class="form-check-input me-2" style="height: 20px;width:20px">
                                        <label for="remember_date" class="form-check-label mt-2" style="margin-left: 5px">Remember Exact Date Of Joining?</label>
                                    </div>
                    
                                    <div class="col-md-3" id="yearField">
                                        <label for="joining_year">Year of Joining</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fa fa-calendar-alt" style="color: blue"></i></span>
                                            <select class="form-control" id="joining_date" name="joining_date">
                                                <option value="">Select Year</option>
                                                @for ($i = date('Y'); $i >= 1900; $i--)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                    
                                    <div class="col-md-3" id="dateField" style="display: none;">
                                        <label for="joining_date">Exact Date of Joining</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fa fa-calendar" style="color: rgb(30, 0, 255)"></i></span>
                                            <input type="date" id="joining_year" name="joining_year" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Submit Button -->
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-lg mt-3 w-50 custom-gradient-btn text-white">
                                    <i class="fa fa-save"></i> Submit
                                </button>
                            </div>
                        </div>
                    </form>
                    
                  
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
    <script>
        function toggleDateField() {
            let checkBox = document.getElementById("remember_date");
            let dateField = document.getElementById("dateField");
            let yearField = document.getElementById("yearField");
    
            if (checkBox.checked) {
                dateField.style.display = "block";
                yearField.style.display = "none";
            } else {
                dateField.style.display = "none";
                yearField.style.display = "block";
            }
        }
    </script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        @endif
    
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        @endif
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
