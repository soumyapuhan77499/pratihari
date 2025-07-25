@extends('layouts.app')

@section('styles')
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Cropper CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">


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
            background-image: linear-gradient(170deg, #FBAB7E 0%, #F7CE68 100%);
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


        .preview-image {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .preview-image:hover {
            transform: scale(2.5);
            /* Increase size on hover */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            /* Optional shadow for better effect */
        }
    </style>
@endsection

@section('content')
    <!-- Profile Form -->
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
                        <a class="nav-link" id="family-tab" style="background-color: #e96a01;color: white" data-toggle="tab"
                            href="#" role="tab" aria-controls="family"
                            aria-selected="true">
                            <i class="fas fa-users" style="color: white"></i> Family
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
                    <form action="{{ route('admin.pratihari-family.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="pratihari_id" value="{{ request('pratihari_id') }}">

                        <div class="row">

                            <!-- Father Details Section -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Father Details</h5>

                                <!-- Father Name Dropdown -->
                                <div class="mb-3">
                                    <label for="father_name">Father Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background-color: #FBAB7E">
                                            <i class="fa fa-user" style="color: white"></i>
                                        </span>
                                        <!-- Father Name -->
                                        <select class="form-control" id="father_name_select" name="father_id">
                                            <option value="">Select Father's Name</option>
                                            <option value="other" style="color: red">If father name not found then click
                                            </option>
                                            @foreach ($familyDetails as $family)
                                                <option value="{{ $family->id }}"
                                                    data-photo="{{ asset($family->father_photo) }}">
                                                    {{ $family->father_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- New Father Name (Hidden Initially) -->
                                <div class="mb-3" id="father_name_input_div" style="display: none;">
                                    <label for="father_name">Enter Father's Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background-color: #FBAB7E">
                                            <i class="fa fa-user" style="color: white"></i>
                                        </span>
                                        <input type="text" class="form-control" name="father_name"
                                            placeholder="Enter Father's Name">
                                    </div>
                                </div>

                                <!-- Father Photo Preview -->
                                <div class="mb-3" id="father_photo_preview_div" style="display: none;">
                                    <label>Father Photo</label>
                                    <div>
                                        <img id="father_photo_preview" src="" class="img-thumbnail preview-image"
                                            style="height: 100px; width: 100px;" />
                                    </div>
                                </div>

                                <!-- Upload New Father Photo -->
                                <div class="mb-3" id="father_photo_upload_div" style="display: none;">
                                    <label>Upload Father Photo</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background-color: #FBAB7E">
                                            <i class="fa fa-camera" style="color: white"></i>
                                        </span>
                                        <input type="file" class="form-control" name="father_photo">
                                    </div>
                                </div>
                            </div>

                            <!-- Mother Details Section -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Mother Details</h5>

                                <!-- Mother Name Dropdown -->
                                <div class="mb-3">
                                    <label for="mother_name">Mother Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background-color: #FBAB7E">
                                            <i class="fa fa-user" style="color: white"></i>
                                        </span>

                                        <select class="form-control" id="mother_name_select" name="mother_id">
                                            <option value="">Select Mother's Name</option>
                                            <option value="other" style="color: red">If Mother name not found then click
                                            </option>
                                            @foreach ($familyDetails as $family)
                                                <option value="{{ $family->id }}"
                                                    data-photo="{{ asset($family->mother_photo) }}">
                                                    {{ $family->mother_name }}
                                                </option>
                                            @endforeach
                                        </select>


                                    </div>
                                </div>

                                <!-- New Mother Name (Hidden Initially) -->
                                <div class="mb-3" id="mother_name_input_div" style="display: none;">
                                    <label for="mother_name">Enter Mother's Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background-color: #FBAB7E">
                                            <i class="fa fa-user" style="color: white"></i>
                                        </span>
                                        <input type="text" class="form-control" name="mother_name"
                                            placeholder="Enter Mother's Name">
                                    </div>
                                </div>

                                <!-- Mother Photo Preview -->
                                <div class="mb-3" id="mother_photo_preview_div" style="display: none;">
                                    <label>Mother Photo</label>
                                    <div>
                                        <img id="mother_photo_preview" src="" class="img-thumbnail preview-image"
                                            style="height: 100px; width: 100px;" />
                                    </div>
                                </div>

                                <!-- Upload New Mother Photo -->
                                <div class="mb-3" id="mother_photo_upload_div" style="display: none;">
                                    <label>Upload Mother Photo</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background-color: #FBAB7E">
                                            <i class="fa fa-camera" style="color: white"></i>
                                        </span>
                                        <input type="file" class="form-control" name="mother_photo">
                                    </div>
                                </div>
                            </div>

                            <!-- Marital Status -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fa fa-heart" style="color: #e96a01"></i> Marital
                                    Status</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="married" name="marital_status" value="married"
                                        class="form-check-input">
                                    <label for="married" class="form-check-label">Married</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="unmarried" name="marital_status" value="unmarried"
                                        class="form-check-input">
                                    <label for="unmarried" class="form-check-label">Unmarried</label>
                                </div>
                            </div>

                            <!-- Spouse Details (Hidden Initially) -->
                            <div class="row" id="spouseDetails" style="display: none;">
                                <div class="col-md-6 mb-3">
                                    <label for="spouse_name">Spouse Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background-color: #FBAB7E"><i
                                                class="fa fa-user" style="color: white"></i></span>
                                        <input type="text" class="form-control" name="spouse_name"
                                            placeholder="Enter Spouse's Name">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="spouse_photo">Spouse Photo</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background-color: #FBAB7E"><i
                                                class="fa fa-camera" style="color: white"></i></span>
                                        <input type="file" class="form-control" name="spouse_photo">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="spouse_father_name">Spouse Father's Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background-color: #FBAB7E">
                                            <i class="fa fa-user" style="color: white"></i>
                                        </span>
                                        <input type="text" class="form-control" name="spouse_father_name"
                                            placeholder="Enter Father's Name">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="spouse_mother_name">Spouse Mother's Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background-color: #FBAB7E">
                                            <i class="fa fa-user" style="color: white"></i>
                                        </span>
                                        <input type="text" class="form-control" name="spouse_mother_name"
                                            placeholder="Enter Mother's Name">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Upload Spouse Father Photo</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background-color: #FBAB7E">
                                            <i class="fa fa-camera" style="color: white"></i>
                                        </span>
                                        <input type="file" class="form-control" name="spouse_father_photo">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Upload Spouse Mother Photo</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background-color: #FBAB7E">
                                            <i class="fa fa-camera" style="color: white"></i>
                                        </span>
                                        <input type="file" class="form-control" name="spouse_mother_photo">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Children Section -->
                            <div class="col-12" id="spouseDetail" style="display: none;">
                                <h5 class="mt-3"><i class="fas fa-child" style="color: #e96a01"></i> Children Details
                                </h5>
                                <button type="button" class="btn btn-sm btn-success" id="addChild">
                                    <i class="fa fa-plus-circle"></i> Add Child
                                </button>
                                <div id="childrenContainer"></div>
                            </div>

                            <!-- Submit Button -->
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
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Show spouse details when "Married" is selected
            $('input[name="marital_status"]').change(function() {
                if ($('#married').is(':checked')) {
                    $('#spouseDetails').slideDown();
                    $('#spouseDetail').slideDown();

                } else {
                    $('#spouseDetails').slideUp();
                    $('#spouseDetail').slideUp();

                }
            });

            // Function to add a new child entry
            $('#addChild').click(function() {
                let childIndex = $('.child-row').length + 1;
                let childHtml = `
                <div class="row child-row mt-3 border p-3 rounded bg-light">
                    <div class="col-md-4">
                        <label class="form-label"><i class="fa fa-user" style="color: #e96a01"></i> Child Name</label>
                        <input type="text" class="form-control" name="children[${childIndex}][name]" placeholder="Enter Child's Name">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><i class="fa fa-calendar" style="color: #e96a01"></i> Date of Birth</label>
                        <input type="date" class="form-control" name="children[${childIndex}][dob]">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label"><i class="fa fa-venus-mars" style="color: #e96a01"></i> Gender</label>
                        <select class="form-control" name="children[${childIndex}][gender]">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label"><i class="fa fa-camera" style="color: #e96a01"></i> Photo</label>
                        <input type="file" class="form-control" name="children[${childIndex}][photo]">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger removeChild"><i class="fa fa-trash-alt"></i></button>
                    </div>
                </div>`;
                $('#childrenContainer').append(childHtml);
            });

            // Remove child entry
            $(document).on('click', '.removeChild', function() {
                $(this).closest('.child-row').remove();
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
        function handleFamilySelection(type) {
            const select = document.getElementById(`${type}_name_select`);
            const selectedOption = select.options[select.selectedIndex];
            const selectedValue = select.value;
            const photoUrl = selectedOption.getAttribute('data-photo');

            const inputDiv = document.getElementById(`${type}_name_input_div`);
            const uploadDiv = document.getElementById(`${type}_photo_upload_div`);
            const previewDiv = document.getElementById(`${type}_photo_preview_div`);
            const previewImg = document.getElementById(`${type}_photo_preview`);
            const previewLink = document.getElementById(`${type}_photo_link`);

            if (selectedValue === 'other') {
                // Show input and upload fields for new entry
                inputDiv.style.display = 'block';
                uploadDiv.style.display = 'block';
                previewDiv.style.display = 'none';
                previewImg.src = '';
                previewLink.href = '#';
            } else if (selectedValue) {
                // Show photo preview for existing selection
                inputDiv.style.display = 'none';
                uploadDiv.style.display = 'none';
                previewDiv.style.display = 'block';
                previewImg.src = photoUrl;
                previewLink.href = photoUrl; // Set link to photo URL
            } else {
                // Reset everything if no valid option selected
                inputDiv.style.display = 'none';
                uploadDiv.style.display = 'none';
                previewDiv.style.display = 'none';
                previewImg.src = '';
                previewLink.href = '#';
            }
        }

        // Attach event listeners
        document.getElementById('father_name_select').addEventListener('change', function() {
            handleFamilySelection('father');
        });

        document.getElementById('mother_name_select').addEventListener('change', function() {
            handleFamilySelection('mother');
        });
    </script>

    <!-- Cropper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
