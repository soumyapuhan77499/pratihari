@extends('layouts.app')

@section('styles')
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@300..800&family=Fraunces:ital,wght@0,400..900;1,400..900&display=swap"
        rel="stylesheet">

    <!-- Icons & CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">

    <style>
        :root {
            /* Fresh, distinctive palette */
            --sp-ink: #1d2340;
            /* Deep ink */
            --sp-gold: #d8a824;
            /* Gold */
            --sp-rose: #e03a65;
            /* Rose */
            --sp-teal: #1ea7a3;
            /* Teal */
            --sp-cream: #fff9ee;
            /* Warm paper */
            --sp-light: #fbfaf5;
            --sp-muted: #6b7280;
            --sp-field-bg: #ffffff;
            /* Inputs background */
            --sp-field-border: #e6e6ef;
            /* Inputs border */
            --sp-focus: #7c5cff;
            /* Focus ring */
            --sp-ok: #16a34a;
            /* Success */
            --sp-danger: #dc2626;
            /* Error */
        }

        /* Subtle layered background */
        body {
            font-family: "Manrope", system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, "Helvetica Neue", Arial, "Apple Color Emoji", "Segoe UI Emoji";
            color: var(--sp-ink);
            background:
                radial-gradient(1200px 700px at -10% -10%, rgba(124, 92, 255, .18), transparent 60%),
                radial-gradient(900px 600px at 110% 10%, rgba(30, 167, 163, .18), transparent 55%),
                linear-gradient(180deg, #ffffff 0%, #ffffff 38%, var(--sp-cream) 100%);
            min-height: 100vh;
        }

        .page-title {
            font-family: "Fraunces", ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;
            color: var(--sp-ink);
            font-weight: 800;
            letter-spacing: .3px;
        }

        /* Card with soft glass feel */
        .sp-card {
            border: none;
            border-radius: 20px;
            background: rgba(255, 255, 255, .9);
            box-shadow: 0 20px 50px rgba(29, 35, 64, .08);
            backdrop-filter: blur(6px);
        }

        /* Header with aurora gradient */
        .card-header.spiritual {
            background: linear-gradient(135deg, var(--sp-gold), var(--sp-rose) 55%, #7c5cff);
            color: #fff;
            font-size: 22px;
            font-weight: 800;
            padding: 18px;
            border-radius: 20px 20px 0 0;
            letter-spacing: .6px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-header.spiritual .seed {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .25);
            display: inline-grid;
            place-items: center;
        }

        /* Tabs (Bootstrap 5) */
        .nav-pills.sp-tabs {
            --bs-nav-link-padding-y: .6rem;
        }

        .nav-pills.sp-tabs .nav-link {
            color: var(--sp-ink);
            font-weight: 700;
            border-radius: 14px;
            padding: 10px 14px;
            background: #fff;
            border: 1px solid rgba(29, 35, 64, .08);
            box-shadow: 0 4px 12px rgba(29, 35, 64, .04);
            transition: transform .08s ease, box-shadow .2s ease;
        }

        .nav-pills.sp-tabs .nav-link:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(29, 35, 64, .08);
        }

        .nav-pills.sp-tabs .nav-link i {
            color: var(--sp-ink);
        }

        .nav-pills.sp-tabs .nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, var(--sp-gold), var(--sp-rose));
            border-color: transparent;
            box-shadow: 0 10px 22px rgba(224, 58, 101, .28);
        }

        .nav-pills.sp-tabs .nav-link.active i {
            color: #fff;
        }

        /* Labels — clearer + friendlier */
        .form-label {
            font-weight: 800;
            color: #0f172a;
            /* Slate-900 for contrast */
            letter-spacing: .2px;
        }

        /* Input groups */
        .input-group-text {
            background: linear-gradient(135deg, var(--sp-gold), var(--sp-rose));
            color: #fff;
            border: none;
            font-weight: 800;
            border-radius: 12px 0 0 12px;
        }

        /* Inputs + selects */
        .form-control,
        .form-select {
            padding-top: .7rem;
            padding-bottom: .7rem;
            border-radius: 12px;
            border: 1px solid var(--sp-field-border);
            background: var(--sp-field-bg);
            box-shadow: 0 1px 0 rgba(29, 35, 64, .02) inset;
            transition: box-shadow .2s ease, border-color .2s ease, transform .06s ease;
        }

        .form-control:hover,
        .form-select:hover {
            border-color: #d5d6ea;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--sp-focus);
            box-shadow: 0 0 0 .25rem rgba(124, 92, 255, .18);
        }

        .form-control::placeholder {
            color: #9aa0b2;
        }

        /* Checkbox */
        .form-check-input {
            width: 1.2rem;
            height: 1.2rem;
            cursor: pointer;
            border-radius: 6px;
            border-color: #cdd2e1;
        }

        .form-check-input:checked {
            background-color: var(--sp-rose);
            border-color: var(--sp-rose);
        }

        /* Submit button */
        .btn-sp {
            background: linear-gradient(135deg, var(--sp-gold), var(--sp-rose));
            border: none;
            color: #fff;
            font-weight: 900;
            padding: 12px 18px;
            border-radius: 16px;
            box-shadow: 0 12px 26px rgba(224, 58, 101, .25);
            letter-spacing: .4px;
        }

        .btn-sp:hover {
            filter: brightness(0.97);
            transform: translateY(-1px);
        }

        .btn-sp:active {
            transform: translateY(0);
        }

        /* Modal */
        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 24px 60px rgba(29, 35, 64, .2);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--sp-gold), var(--sp-rose));
            color: #fff;
            border: none;
            border-radius: 20px 20px 0 0;
        }

        /* Helpers */
        .muted {
            color: var(--sp-muted);
            font-size: .95rem;
        }

        /* Accessibility: bigger tap targets on small screens */
        @media (max-width: 576px) {
            .nav-pills.sp-tabs .nav-link {
                padding: 12px 14px;
            }

            .form-control,
            .form-select {
                padding-top: .85rem;
                padding-bottom: .85rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card sp-card shadow-lg">
                <div class="card-header spiritual">
                    <span class="seed"><i class="fa-solid fa-om"></i></span>
                    Pratihari • Sacred Profile
                </div>

                <div class="px-3 px-sm-4 pt-3 pt-sm-4">
                    <!-- Tabs -->
                    <ul class="nav nav-pills sp-tabs flex-column flex-sm-row gap-2 gap-sm-3 mb-3 mb-sm-4" id="spTab"
                        role="tablist">
                        <li class="nav-item col-12 col-sm-auto" role="presentation">
                            <button class="nav-link active w-100" id="profile-tab" data-bs-toggle="tab"
                                data-bs-target="#profile-pane" type="button" role="tab" aria-controls="profile-pane"
                                aria-selected="true">
                                <i class="fas fa-user me-2"></i> Profile
                            </button>
                        </li>
                        <li class="nav-item col-12 col-sm-auto" role="presentation">
                            <button class="nav-link w-100" id="family-tab" data-bs-toggle="tab"
                                data-bs-target="#family-pane" type="button" role="tab" aria-controls="family-pane"
                                aria-selected="false">
                                <i class="fas fa-users me-2"></i> Family
                            </button>
                        </li>
                        <li class="nav-item col-12 col-sm-auto" role="presentation">
                            <button class="nav-link w-100" id="id-card-tab" data-bs-toggle="tab"
                                data-bs-target="#id-card-pane" type="button" role="tab" aria-controls="id-card-pane"
                                aria-selected="false">
                                <i class="fas fa-id-card me-2"></i> ID Card
                            </button>
                        </li>
                        <li class="nav-item col-12 col-sm-auto" role="presentation">
                            <button class="nav-link w-100" id="address-tab" data-bs-toggle="tab"
                                data-bs-target="#address-pane" type="button" role="tab" aria-controls="address-pane"
                                aria-selected="false">
                                <i class="fas fa-map-marker-alt me-2"></i> Address
                            </button>
                        </li>
                        <li class="nav-item col-12 col-sm-auto" role="presentation">
                            <button class="nav-link w-100" id="occupation-tab" data-bs-toggle="tab"
                                data-bs-target="#occupation-pane" type="button" role="tab"
                                aria-controls="occupation-pane" aria-selected="false">
                                <i class="fas fa-briefcase me-2"></i> Occupation
                            </button>
                        </li>
                        <li class="nav-item col-12 col-sm-auto" role="presentation">
                            <button class="nav-link w-100" id="seba-details-tab" data-bs-toggle="tab"
                                data-bs-target="#seba-pane" type="button" role="tab" aria-controls="seba-pane"
                                aria-selected="false">
                                <i class="fas fa-cogs me-2"></i> Seba
                            </button>
                        </li>
                        <li class="nav-item col-12 col-sm-auto" role="presentation">
                            <button class="nav-link w-100" id="social-media-tab" data-bs-toggle="tab"
                                data-bs-target="#social-pane" type="button" role="tab" aria-controls="social-pane"
                                aria-selected="false">
                                <i class="fas fa-share-alt me-2"></i> Social
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body pt-0">
                    <form action="{{ route('admin.pratihari-profile.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="cropped_profile_photo" id="cropped_profile_photo">

                        <div class="tab-content" id="spTabContent">
                            <!-- PROFILE -->
                            <div class="tab-pane fade show active" id="profile-pane" role="tabpanel"
                                aria-labelledby="profile-tab">
                                <div class="row g-3">
                                    <!-- First Name -->
                                    <div class="col-md-3">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                                            <input type="text" name="first_name" id="first_name" class="form-control"
                                                placeholder="e.g., Raghav" autocomplete="given-name">
                                        </div>
                                    </div>
                                    <!-- Middle Name -->
                                    <div class="col-md-3">
                                        <label for="middle_name" class="form-label">Middle Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                                            <input type="text" name="middle_name" id="middle_name"
                                                class="form-control" placeholder="Optional"
                                                autocomplete="additional-name">
                                        </div>
                                    </div>
                                    <!-- Last Name -->
                                    <div class="col-md-3">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                                            <input type="text" name="last_name" id="last_name" class="form-control"
                                                placeholder="e.g., Mishra" autocomplete="family-name">
                                        </div>
                                    </div>
                                    <!-- Alias Name -->
                                    <div class="col-md-3">
                                        <label for="alias_name" class="form-label">Alias Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-user-tag"></i></span>
                                            <input type="text" name="alias_name" id="alias_name" class="form-control"
                                                placeholder="e.g., Raghav Ji">
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-3">
                                        <label for="email" class="form-label">Email ID</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                            <input type="email" class="form-control" id="email" name="email"
                                                placeholder="name@example.com" autocomplete="email">
                                        </div>
                                    </div>

                                    <!-- WhatsApp -->
                                    <div class="col-md-3">
                                        <label for="whatsapp_no" class="form-label">WhatsApp No</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                            <input type="tel" class="form-control" id="whatsapp_no"
                                                name="whatsapp_no" pattern="\d{10}" maxlength="10"
                                                placeholder="10 digits" inputmode="numeric" autocomplete="tel-national">
                                        </div>
                                        <div class="muted mt-1">Numbers only, no spaces.</div>
                                    </div>

                                    <!-- Primary Phone -->
                                    <div class="col-md-3">
                                        <label for="phone_no" class="form-label">Primary Phone No</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                            <input type="tel" class="form-control" id="phone_no" name="phone_no"
                                                pattern="\d{10}" maxlength="10" placeholder="10 digits"
                                                inputmode="numeric" autocomplete="tel">
                                        </div>
                                    </div>

                                    <!-- Blood Group -->
                                    <div class="col-md-3">
                                        <label for="blood_group" class="form-label">Blood Group</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-tint"></i></span>
                                            <select class="form-select" id="blood_group" name="blood_group">
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
                                        <label for="healthcard_no" class="form-label">Health Card No</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-id-card"></i></span>
                                            <input type="text" class="form-control" id="healthcard_no"
                                                name="healthcard_no" placeholder="ID number" autocomplete="off">
                                        </div>
                                    </div>

                                    <!-- Health Card Photo -->
                                    <div class="col-md-3">
                                        <label for="health_card_photo" class="form-label">Health Card Photo</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-image"></i></span>
                                            <input type="file" class="form-control" id="health_card_photo"
                                                name="health_card_photo" accept="image/*">
                                        </div>
                                    </div>

                                    <!-- Profile Photo (with Cropper) -->
                                    <div class="col-md-3">
                                        <label for="profile_photo" class="form-label">Profile Photo</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-camera"></i></span>
                                            <input type="file" class="form-control" id="profile_photo"
                                                name="original_photo" accept="image/*">
                                        </div>
                                        <div class="muted mt-1">Square crop suggested for best avatar.</div>
                                    </div>

                                    <!-- Modal for Cropping -->
                                    <div class="modal fade" id="cropperModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Crop Profile Photo</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img id="cropperImage" style="max-width: 100%; max-height: 420px;">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-sp" id="cropImageBtn">
                                                        <i class="fa fa-check me-1"></i> Save and Continue
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Date of Birth -->
                                    <div class="col-md-3">
                                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            <input type="date" class="form-control" id="date_of_birth"
                                                name="date_of_birth" autocomplete="bday">
                                        </div>
                                    </div>

                                    <!-- Joining Details -->
                                    <div class="col-12">
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="remember_date" onchange="toggleDateField()">
                                                    <label class="form-check-label" for="remember_date">Remember Exact
                                                        Date Of Joining?</label>
                                                </div>
                                                <div class="muted mt-1">If unchecked, you can select only the year.</div>
                                            </div>

                                            <div class="col-md-4" id="dateField">
                                                <label for="joining_year" class="form-label">Year of Joining</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fa fa-calendar-alt"></i></span>
                                                    <select class="form-select" id="joining_year"
                                                        name="joining_year"></select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit -->
                                    <div class="col-12 text-center mt-3">
                                        <button type="submit" class="btn btn-sp btn-lg w-50">
                                            <i class="fa fa-save me-2"></i> Submit
                                        </button>
                                        <div class="muted mt-2">By submitting, you invoke seva with truthful details. 🙏
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- FAMILY (placeholder) -->
                            <div class="tab-pane fade" id="family-pane" role="tabpanel" aria-labelledby="family-tab">
                                <div class="alert alert-info mb-0">
                                    This section can hold family details in the next iteration. Current form fields are
                                    under <strong>Profile</strong>.
                                </div>
                            </div>

                            <!-- ID CARD (placeholder) -->
                            <div class="tab-pane fade" id="id-card-pane" role="tabpanel" aria-labelledby="id-card-tab">
                                <div class="alert alert-info mb-0">
                                    Manage ID related uploads & numbers. For now, add them in <strong>Profile</strong>.
                                </div>
                            </div>

                            <!-- ADDRESS (placeholder) -->
                            <div class="tab-pane fade" id="address-pane" role="tabpanel" aria-labelledby="address-tab">
                                <div class="alert alert-info mb-0">
                                    Address fields can be added here as needed.
                                </div>
                            </div>

                            <!-- OCCUPATION (placeholder) -->
                            <div class="tab-pane fade" id="occupation-pane" role="tabpanel"
                                aria-labelledby="occupation-tab">
                                <div class="alert alert-info mb-0">
                                    Occupation details will be available here in future versions.
                                </div>
                            </div>

                            <!-- SEBA (placeholder) -->
                            <div class="tab-pane fade" id="seba-pane" role="tabpanel"
                                aria-labelledby="seba-details-tab">
                                <div class="alert alert-info mb-0">
                                    Service (Seba) preferences & contributions can be configured here.
                                </div>
                            </div>

                            <!-- SOCIAL (placeholder) -->
                            <div class="tab-pane fade" id="social-pane" role="tabpanel"
                                aria-labelledby="social-media-tab">
                                <div class="alert alert-info mb-0">
                                    Add social links & handles here in the next update.
                                </div>
                            </div>
                        </div>
                    </form>
                </div> <!-- /card-body -->
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function toggleDateField() {
            const checkbox = document.getElementById("remember_date");
            const dateField = document.getElementById("dateField");

            if (checkbox.checked) {
                dateField.innerHTML = `
                    <label for="joining_date" class="form-label">Date of Joining</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-calendar-alt"></i></span>
                        <input type="date" class="form-control" id="joining_date" name="joining_date">
                    </div>
                `;
            } else {
                const currentYear = new Date().getFullYear();
                let options = `<option value="">Select Year</option>`;
                for (let i = currentYear; i >= 1900; i--) {
                    options += `<option value="${i}">${i}</option>`;
                }
                dateField.innerHTML = `
                    <label for="joining_year" class="form-label">Year of Joining</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-calendar-alt"></i></span>
                        <select class="form-select" id="joining_year" name="joining_year">${options}</select>
                    </div>
                `;
            }
        }

        // Initialize year options on load for better UX
        document.addEventListener('DOMContentLoaded', toggleDateField);
    </script>

    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#7c5cff',
                confirmButtonText: 'OK'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#e03a65',
                confirmButtonText: 'OK'
            });
        @endif
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
        let cropper;

        document.getElementById('profile_photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const image = document.getElementById('cropperImage');
                    image.src = event.target.result;

                    const cropperModal = new bootstrap.Modal(document.getElementById('cropperModal'));
                    cropperModal.show();

                    document.getElementById('cropperModal').addEventListener('shown.bs.modal',
                    function onShown() {
                        document.getElementById('cropperModal').removeEventListener('shown.bs.modal',
                            onShown);
                        if (cropper) cropper.destroy();
                        cropper = new Cropper(image, {
                            aspectRatio: 1,
                            viewMode: 2,
                            autoCropArea: 1,
                        });
                    }, {
                        once: true
                    });
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('cropImageBtn').addEventListener('click', function() {
            if (!cropper) return;
            const croppedCanvas = cropper.getCroppedCanvas({
                width: 300,
                height: 300
            });
            croppedCanvas.toBlob(function(blob) {
                const file = new File([blob], 'profile_photo.jpg', {
                    type: 'image/jpeg'
                });

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);

                const fileInput = document.getElementById('profile_photo');
                fileInput.files = dataTransfer.files;

                const base64 = croppedCanvas.toDataURL('image/jpeg');
                document.getElementById('cropped_profile_photo').value = base64;

                bootstrap.Modal.getInstance(document.getElementById('cropperModal')).hide();
            }, 'image/jpeg');
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
