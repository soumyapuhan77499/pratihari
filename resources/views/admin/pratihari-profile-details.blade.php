@extends('layouts.app')

@section('styles')
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icon set: Bootstrap Icons (switched from Font Awesome) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Cropper -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />

    <style>
        :root {
            --ink: #0f172a;
            /* slate-900 */
            --muted: #64748b;
            /* slate-500 */
            --card: rgba(255, 255, 255, .9);
            --bd: rgba(15, 23, 42, .12);
            --pri: #6366f1;
            /* indigo-500 */
            --pri-2: #22d3ee;
            /* cyan-400 */
            --pri-3: #3b82f6;
            /* blue-500 */
            --ok: #22c55e;
            /* green-500 */
            --err: #ef4444;
            /* red-500 */
            --focus: rgba(99, 102, 241, .2);
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: "Poppins", system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji";
            color: var(--ink);
            background:
                radial-gradient(1200px 700px at -10% -10%, rgba(99, 102, 241, .22), transparent 60%),
                radial-gradient(900px 600px at 110% 10%, rgba(34, 211, 238, .22), transparent 55%),
                linear-gradient(180deg, #f8fafc 0%, #ffffff 45%, #f1f5f9 100%);
        }

        /* Decorative aurora ribbon */
        .aurora {
            position: relative;
        }

        .aurora::before {
            content: "";
            position: absolute;
            inset: -2px;
            background: conic-gradient(from 180deg at 50% 50%, var(--pri), var(--pri-2), var(--pri-3), var(--pri));
            filter: blur(28px) saturate(1.1);
            opacity: .18;
            z-index: 0;
            border-radius: 22px;
        }

        .page-wrap {
            position: relative;
            z-index: 1;
        }

        .sp-card {
            background: var(--card);
            border: 1px solid var(--bd);
            border-radius: 20px;
            box-shadow: 0 18px 55px rgba(15, 23, 42, .08);
            overflow: hidden;
        }

        .card-header.glow {
            display: flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 1), rgba(59, 130, 246, .95) 55%, rgba(34, 211, 238, .95));
            color: #fff;
            font-weight: 800;
            letter-spacing: .3px;
            border-bottom: 1px solid rgba(255, 255, 255, .2);
            padding: 18px 20px;
        }

        .seed {
            display: inline-grid;
            place-items: center;
            width: 36px;
            height: 36px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .18);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .2);
        }

        /* Tabs */
        .nav.sp-tabs {
            --bs-nav-link-color: var(--ink);
            --bs-nav-link-hover-color: var(--pri);
            gap: .5rem;
        }

        .nav.sp-tabs .nav-link {
            position: relative;
            background: #fff;
            border: 1px solid var(--bd);
            color: var(--ink);
            font-weight: 700;
            border-radius: 12px;
            padding: .6rem .9rem;
            transition: all .18s ease;
        }

        .nav.sp-tabs .nav-link:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(15, 23, 42, .06);
        }

        .nav.sp-tabs .nav-link.active {
            color: #fff;
            border-color: transparent;
            background: linear-gradient(135deg, var(--pri), var(--pri-2));
            box-shadow: 0 12px 24px rgba(99, 102, 241, .25);
        }

        .nav.sp-tabs .nav-link i {
            vertical-align: -2px;
        }

        /* Form controls */
        .form-label {
            font-weight: 700;
            color: var(--ink);
        }

        .input-group-text {
            background: linear-gradient(135deg, var(--pri), var(--pri-3));
            color: #fff;
            border: none;
            font-weight: 700;
        }

        .form-control,
        .form-select {
            padding-top: .7rem;
            padding-bottom: .7rem;
            border-radius: 12px;
            border: 1px solid var(--bd);
            box-shadow: 0 1px 0 rgba(15, 23, 42, .02) inset;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--pri);
            box-shadow: 0 0 0 .25rem var(--focus);
        }

        .muted {
            color: var(--muted);
            font-size: .92rem;
        }

        /* Buttons */
        .btn-aurora {
            border: none;
            color: #fff;
            font-weight: 800;
            padding: .9rem 1.2rem;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--pri), var(--pri-2) 65%, var(--pri-3));
            box-shadow: 0 16px 30px rgba(99, 102, 241, .25);
            letter-spacing: .3px;
        }

        .btn-aurora:hover {
            filter: brightness(.97);
            transform: translateY(-1px);
        }

        /* Modal */
        .modal-content {
            border: none;
            border-radius: 18px;
            box-shadow: 0 22px 60px rgba(15, 23, 42, .22);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--pri), var(--pri-3));
            color: #fff;
            border: none;
            border-radius: 18px 18px 0 0;
        }

        .btn-outline-secondary {
            border-radius: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="aurora">
        <div class="page-wrap container-fluid px-0 px-sm-2">
            <div class="row justify-content-center">
                <div class="col-12 col-xxl-10 mt-4">
                    <div class="sp-card shadow-lg">
                        <div class="card-header glow">
                            <span class="seed"><i class="bi bi-stars fs-5"></i></span>
                            <span class="fs-5">Pratihari • Sacred Profile</span>
                        </div>

                        <div class="px-3 px-sm-4 pt-3 pt-sm-4">
                            <!-- Tabs -->
                            <ul class="nav sp-tabs flex-column flex-sm-row mb-3" id="spTab" role="tablist">
                                <li class="nav-item col-12 col-sm-auto" role="presentation">
                                    <button class="nav-link active w-100" id="profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#profile-pane" type="button" role="tab"
                                        aria-controls="profile-pane" aria-selected="true">
                                        <i class="bi bi-person-lines-fill me-2"></i> Profile
                                    </button>
                                </li>
                                <li class="nav-item col-12 col-sm-auto" role="presentation">
                                    <button class="nav-link w-100" id="family-tab" data-bs-toggle="tab"
                                        data-bs-target="#family-pane" type="button" role="tab"
                                        aria-controls="family-pane" aria-selected="false">
                                        <i class="bi bi-people-fill me-2"></i> Family
                                    </button>
                                </li>
                                <li class="nav-item col-12 col-sm-auto" role="presentation">
                                    <button class="nav-link w-100" id="id-card-tab" data-bs-toggle="tab"
                                        data-bs-target="#id-card-pane" type="button" role="tab"
                                        aria-controls="id-card-pane" aria-selected="false">
                                        <i class="bi bi-badge-ad-fill me-2"></i> ID Card
                                    </button>
                                </li>
                                <li class="nav-item col-12 col-sm-auto" role="presentation">
                                    <button class="nav-link w-100" id="address-tab" data-bs-toggle="tab"
                                        data-bs-target="#address-pane" type="button" role="tab"
                                        aria-controls="address-pane" aria-selected="false">
                                        <i class="bi bi-geo-alt-fill me-2"></i> Address
                                    </button>
                                </li>
                                <li class="nav-item col-12 col-sm-auto" role="presentation">
                                    <button class="nav-link w-100" id="occupation-tab" data-bs-toggle="tab"
                                        data-bs-target="#occupation-pane" type="button" role="tab"
                                        aria-controls="occupation-pane" aria-selected="false">
                                        <i class="bi bi-briefcase-fill me-2"></i> Occupation
                                    </button>
                                </li>
                                <li class="nav-item col-12 col-sm-auto" role="presentation">
                                    <button class="nav-link w-100" id="seba-details-tab" data-bs-toggle="tab"
                                        data-bs-target="#seba-pane" type="button" role="tab" aria-controls="seba-pane"
                                        aria-selected="false">
                                        <i class="bi bi-gem me-2"></i> Seba
                                    </button>
                                </li>
                                <li class="nav-item col-12 col-sm-auto" role="presentation">
                                    <button class="nav-link w-100" id="social-media-tab" data-bs-toggle="tab"
                                        data-bs-target="#social-pane" type="button" role="tab"
                                        aria-controls="social-pane" aria-selected="false">
                                        <i class="bi bi-share-fill me-2"></i> Social
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
                                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                                    <input type="text" name="first_name" id="first_name"
                                                        class="form-control" placeholder="e.g., Raghav">
                                                </div>
                                            </div>
                                            <!-- Middle Name -->
                                            <div class="col-md-3">
                                                <label for="middle_name" class="form-label">Middle Name</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                                    <input type="text" name="middle_name" id="middle_name"
                                                        class="form-control" placeholder="Optional">
                                                </div>
                                            </div>
                                            <!-- Last Name -->
                                            <div class="col-md-3">
                                                <label for="last_name" class="form-label">Last Name</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                                    <input type="text" name="last_name" id="last_name"
                                                        class="form-control" placeholder="e.g., Mishra">
                                                </div>
                                            </div>
                                            <!-- Alias Name -->
                                            <div class="col-md-3">
                                                <label for="alias_name" class="form-label">Alias Name</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="bi bi-person-badge-fill"></i></span>
                                                    <input type="text" name="alias_name" id="alias_name"
                                                        class="form-control" placeholder="e.g., Raghav Ji">
                                                </div>
                                            </div>

                                            <!-- Email -->
                                            <div class="col-md-3">
                                                <label for="email" class="form-label">Email ID</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="bi bi-envelope-fill"></i></span>
                                                    <input type="email" class="form-control" id="email"
                                                        name="email" placeholder="name@example.com">
                                                </div>
                                            </div>

                                            <!-- WhatsApp -->
                                            <div class="col-md-3">
                                                <label for="whatsapp_no" class="form-label">WhatsApp No</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="bi bi-telephone-fill"></i></span>
                                                    <input type="tel" class="form-control" id="whatsapp_no"
                                                        name="whatsapp_no" pattern="\d{10}" maxlength="10"
                                                        placeholder="10 digits">
                                                </div>
                                                <div class="muted mt-1">Numbers only, no spaces.</div>
                                            </div>

                                            <!-- Primary Phone -->
                                            <div class="col-md-3">
                                                <label for="phone_no" class="form-label">Primary Phone No</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="bi bi-telephone-fill"></i></span>
                                                    <input type="tel" class="form-control" id="phone_no"
                                                        name="phone_no" pattern="\d{10}" maxlength="10"
                                                        placeholder="10 digits">
                                                </div>
                                            </div>

                                            <!-- Blood Group -->
                                            <div class="col-md-3">
                                                <label for="blood_group" class="form-label">Blood Group</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="bi bi-droplet-half"></i></span>
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
                                                    <span class="input-group-text"><i
                                                            class="bi bi-credit-card-2-front-fill"></i></span>
                                                    <input type="text" class="form-control" id="healthcard_no"
                                                        name="healthcard_no" placeholder="ID number">
                                                </div>
                                            </div>

                                            <!-- Health Card Photo -->
                                            <div class="col-md-3">
                                                <label for="health_card_photo" class="form-label">Health Card
                                                    Photo</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-image-fill"></i></span>
                                                    <input type="file" class="form-control" id="health_card_photo"
                                                        name="health_card_photo" accept="image/*">
                                                </div>
                                            </div>

                                            <!-- Profile Photo (with Cropper) -->
                                            <div class="col-md-3">
                                                <label for="profile_photo" class="form-label">Profile Photo</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="bi bi-camera-fill"></i></span>
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
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <img id="cropperImage"
                                                                style="max-width: 100%; max-height: 420px;">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="button" class="btn btn-aurora"
                                                                id="cropImageBtn">
                                                                <i class="bi bi-check2-circle me-1"></i> Save and Continue
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Date of Birth -->
                                            <div class="col-md-3">
                                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="bi bi-calendar2-week-fill"></i></span>
                                                    <input type="date" class="form-control" id="date_of_birth"
                                                        name="date_of_birth">
                                                </div>
                                            </div>

                                            <!-- Joining Details -->
                                            <div class="col-12">
                                                <div class="row g-3 align-items-end">
                                                    <div class="col-md-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="" id="remember_date"
                                                                onchange="toggleDateField()">
                                                            <label class="form-check-label" for="remember_date">
                                                                Remember Exact Date Of Joining?
                                                            </label>
                                                        </div>
                                                        <div class="muted mt-1">If unchecked, you can select only the year.
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4" id="dateField">
                                                        <label for="joining_year" class="form-label">Year of
                                                            Joining</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="bi bi-calendar-event-fill"></i></span>
                                                            <select class="form-select" id="joining_year"
                                                                name="joining_year">
                                                                <option value="">Select Year</option>
                                                                @for ($i = date('Y'); $i >= 1900; $i--)
                                                                    <option value="{{ $i }}">{{ $i }}
                                                                    </option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Submit -->
                                            <div class="col-12 text-center mt-3">
                                                <button type="submit" class="btn btn-aurora btn-lg w-50">
                                                    <i class="bi bi-floppy2-fill me-2"></i> Submit
                                                </button>
                                                <div class="muted mt-2">By submitting, you invoke seva with truthful
                                                    details. ✨</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- FAMILY (placeholder) -->
                                    <div class="tab-pane fade" id="family-pane" role="tabpanel"
                                        aria-labelledby="family-tab">
                                        <div class="alert alert-info mb-0">
                                            This section can hold family details in the next iteration. Current fields are
                                            under <strong>Profile</strong>.
                                        </div>
                                    </div>

                                    <!-- ID CARD (placeholder) -->
                                    <div class="tab-pane fade" id="id-card-pane" role="tabpanel"
                                        aria-labelledby="id-card-tab">
                                        <div class="alert alert-info mb-0">
                                            Manage ID related uploads & numbers. For now, add them in
                                            <strong>Profile</strong>.
                                        </div>
                                    </div>

                                    <!-- ADDRESS (placeholder) -->
                                    <div class="tab-pane fade" id="address-pane" role="tabpanel"
                                        aria-labelledby="address-tab">
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
        function toggleDateField() {
            const checkbox = document.getElementById("remember_date");
            const dateField = document.getElementById("dateField");

            if (checkbox.checked) {
                dateField.innerHTML = `
                    <label for="joining_date" class="form-label">Date of Joining</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar-event-fill"></i></span>
                        <input type="date" class="form-control" id="joining_date" name="joining_date">
                    </div>
                `;
            } else {
                const currentYear = new Date().getFullYear();
                let options = `<option value="">Select Year</option>`;
                for (let i = currentYear; i >= 1900; i--) {
                    options += `<option value="\${i}">\${i}</option>`;
                }
                dateField.innerHTML = `
                    <label for="joining_year" class="form-label">Year of Joining</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar-event-fill"></i></span>
                        <select class="form-select" id="joining_year" name="joining_year">
                            ${options}
                        </select>
                    </div>
                `;
            }
        }
    </script>

    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#22c55e',
                confirmButtonText: 'OK'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#ef4444',
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
