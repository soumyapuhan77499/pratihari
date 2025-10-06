@extends('layouts.app')

@section('styles')
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (kept, per your stack) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Cropper (single version only) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css"/>

    <style>
        :root{
            --ink: #1f2544;            /* Deep Indigo */
            --gold: #caa66a;           /* Refined Gold */
            --cream: #f9f6ef;          /* Soft Cream */
            --muted: #6b7280;          /* Slate */
            --line: rgba(31,37,68,.14);
            --focus: rgba(202,166,106,.25);
            --pill-bg: #ffffff;
            --pill-active: linear-gradient(135deg, #caa66a, #8a78c9);
        }

        html, body { height: 100%; }
        body{
            font-family: "Inter", system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial;
            color: var(--ink);
            background:
                radial-gradient(1100px 600px at -10% -20%, rgba(202,166,106,.22), transparent 60%),
                radial-gradient(900px 600px at 110% 10%, rgba(138,120,201,.22), transparent 55%),
                linear-gradient(180deg, #ffffff 0%, #ffffff 40%, var(--cream) 100%);
        }

        .card.sp{
            background: rgba(255,255,255,.92);
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 18px 55px rgba(31,37,68,.09);
            overflow: hidden;
        }

        .card-header.sp{
            display:flex; align-items:center; gap:12px;
            background: linear-gradient(135deg, #1f2544, #3b2f7b 60%, #7a68c7);
            color:#fff; padding: 18px 20px;
            border-bottom: 1px solid rgba(255,255,255,.18);
        }
        .seed{
            display:inline-grid; place-items:center;
            width:36px;height:36px;border-radius:50%;
            background: rgba(255,255,255,.18);
            box-shadow: inset 0 0 0 1px rgba(255,255,255,.2);
        }
        .card-header.sp .title{
            font-weight: 800; letter-spacing:.3px;
        }

        /* Tabs */
        .nav.sp-tabs{ gap:.5rem; }
        .nav.sp-tabs .nav-link{
            background: var(--pill-bg);
            border: 1px solid var(--line);
            color: var(--ink);
            font-weight: 700;
            border-radius: 12px;
            padding: .65rem .9rem;
            transition: all .18s ease;
            display: inline-flex; align-items: center; gap:.5rem;
        }
        .nav.sp-tabs .nav-link:hover{
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(31,37,68,.08);
        }
        .nav.sp-tabs .nav-link.active{
            color:#fff; border-color: transparent;
            background: var(--pill-active);
            box-shadow: 0 12px 26px rgba(31,37,68,.18);
        }
        .nav.sp-tabs .nav-link i{ font-size: 1rem; }

        .tab-pane{ padding: .25rem; }

        /* Form controls */
        .form-label{ font-weight: 700; color: var(--ink); }
        .input-group-text{
            background: linear-gradient(135deg, #caa66a, #8a78c9);
            color:#fff; border:none; font-weight:700;
        }
        .form-control, .form-select{
            padding-top:.7rem; padding-bottom:.7rem;
            border-radius: 12px;
            border:1px solid var(--line);
            box-shadow: 0 1px 0 rgba(31,37,68,.02) inset;
        }
        .form-control:focus, .form-select:focus{
            border-color: #caa66a;
            box-shadow: 0 0 0 .25rem var(--focus);
        }
        .muted{ color:var(--muted); font-size:.92rem; }

        /* Buttons */
        .btn-sp{
            border:none; color:#fff; font-weight:800;
            padding: .9rem 1.2rem; border-radius: 14px;
            background: linear-gradient(135deg, #caa66a, #8a78c9 70%, #a18cd1);
            box-shadow: 0 16px 32px rgba(161,140,209,.25);
            letter-spacing:.3px;
        }
        .btn-sp:hover{ filter: brightness(.97); transform: translateY(-1px); }

        /* Modal */
        .modal-content{
            border:none; border-radius: 18px;
            box-shadow: 0 22px 60px rgba(31,37,68,.18);
        }
        .modal-header{
            background: linear-gradient(135deg, #1f2544, #7a68c7);
            color:#fff; border:none; border-radius:18px 18px 0 0;
        }

        /* Utility spacing for sections */
        .section-sp{ margin-top: .25rem; }
    </style>
@endsection

@section('content')
<div class="container-fluid px-0 px-sm-2">
    <div class="row justify-content-center">
        <div class="col-12 col-xxl-10 mt-4">
            <div class="card sp">
                <div class="card-header sp">
                    <span class="seed"><i class="fa-solid fa-om"></i></span>
                    <span class="title">Pratihari • Sacred Profile</span>
                </div>

                <div class="px-3 px-sm-4 pt-3 pt-sm-4">
                    <!-- Tabs -->
                    <ul class="nav sp-tabs flex-column flex-sm-row mb-3" id="spTab" role="tablist">
                        <li class="nav-item col-12 col-sm-auto" role="presentation">
                            <button class="nav-link active w-100" id="profile-tab"
                                    data-bs-toggle="tab" data-bs-target="#profile-pane" type="button" role="tab"
                                    aria-controls="profile-pane" aria-selected="true">
                                <i class="fa-solid fa-user"></i> Profile
                            </button>
                        </li>
                        <li class="nav-item col-12 col-sm-auto" role="presentation">
                            <button class="nav-link w-100" id="family-tab"
                                    data-bs-toggle="tab" data-bs-target="#family-pane" type="button" role="tab"
                                    aria-controls="family-pane" aria-selected="false">
                                <i class="fa-solid fa-people-group"></i> Family
                            </button>
                        </li>
                        <li class="nav-item col-12 col-sm-auto" role="presentation">
                            <button class="nav-link w-100" id="id-card-tab"
                                    data-bs-toggle="tab" data-bs-target="#id-card-pane" type="button" role="tab"
                                    aria-controls="id-card-pane" aria-selected="false">
                                <i class="fa-solid fa-id-card-clip"></i> ID Card
                            </button>
                        </li>
                        <li class="nav-item col-12 col-sm-auto" role="presentation">
                            <button class="nav-link w-100" id="address-tab"
                                    data-bs-toggle="tab" data-bs-target="#address-pane" type="button" role="tab"
                                    aria-controls="address-pane" aria-selected="false">
                                <i class="fa-solid fa-location-dot"></i> Address
                            </button>
                        </li>
                        <li class="nav-item col-12 col-sm-auto" role="presentation">
                            <button class="nav-link w-100" id="occupation-tab"
                                    data-bs-toggle="tab" data-bs-target="#occupation-pane" type="button" role="tab"
                                    aria-controls="occupation-pane" aria-selected="false">
                                <i class="fa-solid fa-briefcase"></i> Occupation
                            </button>
                        </li>
                        <li class="nav-item col-12 col-sm-auto" role="presentation">
                            <button class="nav-link w-100" id="seba-details-tab"
                                    data-bs-toggle="tab" data-bs-target="#seba-pane" type="button" role="tab"
                                    aria-controls="seba-pane" aria-selected="false">
                                <i class="fa-solid fa-hands-praying"></i> Seba
                            </button>
                        </li>
                        <li class="nav-item col-12 col-sm-auto" role="presentation">
                            <button class="nav-link w-100" id="social-media-tab"
                                    data-bs-toggle="tab" data-bs-target="#social-pane" type="button" role="tab"
                                    aria-controls="social-pane" aria-selected="false">
                                <i class="fa-solid fa-share-nodes"></i> Social
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body pt-0">
                    <form action="{{ route('admin.pratihari-profile.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="cropped_profile_photo" id="cropped_profile_photo">

                        <div class="tab-content" id="spTabContent">
                            <!-- PROFILE -->
                            <div class="tab-pane fade show active section-sp" id="profile-pane" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="row g-3">
                                    <!-- First Name -->
                                    <div class="col-md-3">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="e.g., Raghav">
                                        </div>
                                    </div>
                                    <!-- Middle Name -->
                                    <div class="col-md-3">
                                        <label for="middle_name" class="form-label">Middle Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                            <input type="text" name="middle_name" id="middle_name" class="form-control" placeholder="Optional">
                                        </div>
                                    </div>
                                    <!-- Last Name -->
                                    <div class="col-md-3">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="e.g., Mishra">
                                        </div>
                                    </div>
                                    <!-- Alias Name -->
                                    <div class="col-md-3">
                                        <label for="alias_name" class="form-label">Alias Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-user-tag"></i></span>
                                            <input type="text" name="alias_name" id="alias_name" class="form-control" placeholder="e.g., Raghav Ji">
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-3">
                                        <label for="email" class="form-label">Email ID</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
                                        </div>
                                    </div>

                                    <!-- WhatsApp -->
                                    <div class="col-md-3">
                                        <label for="whatsapp_no" class="form-label">WhatsApp No</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                            <input type="tel" class="form-control" id="whatsapp_no" name="whatsapp_no" pattern="\d{10}" maxlength="10" placeholder="10 digits">
                                        </div>
                                        <div class="muted mt-1">Numbers only, no spaces.</div>
                                    </div>

                                    <!-- Primary Phone -->
                                    <div class="col-md-3">
                                        <label for="phone_no" class="form-label">Primary Phone No</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                            <input type="tel" class="form-control" id="phone_no" name="phone_no" pattern="\d{10}" maxlength="10" placeholder="10 digits">
                                        </div>
                                    </div>

                                    <!-- Blood Group -->
                                    <div class="col-md-3">
                                        <label for="blood_group" class="form-label">Blood Group</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-droplet"></i></span>
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
                                            <span class="input-group-text"><i class="fa-solid fa-id-card-clip"></i></span>
                                            <input type="text" class="form-control" id="healthcard_no" name="healthcard_no" placeholder="ID number">
                                        </div>
                                    </div>

                                    <!-- Health Card Photo -->
                                    <div class="col-md-3">
                                        <label for="health_card_photo" class="form-label">Health Card Photo</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-image"></i></span>
                                            <input type="file" class="form-control" id="health_card_photo" name="health_card_photo" accept="image/*">
                                        </div>
                                    </div>

                                    <!-- Profile Photo (Cropper) -->
                                    <div class="col-md-3">
                                        <label for="profile_photo" class="form-label">Profile Photo</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-camera"></i></span>
                                            <input type="file" class="form-control" id="profile_photo" name="original_photo" accept="image/*">
                                        </div>
                                        <div class="muted mt-1">Square crop suggested for best avatar.</div>
                                    </div>

                                    <!-- Cropper Modal -->
                                    <div class="modal fade" id="cropperModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Crop Profile Photo</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img id="cropperImage" style="max-width: 100%; max-height: 420px;">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-sp" id="cropImageBtn">
                                                        <i class="fa-solid fa-check me-1"></i> Save and Continue
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Date of Birth -->
                                    <div class="col-md-3">
                                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
                                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                                        </div>
                                    </div>

                                    <!-- Joining Details -->
                                    <div class="col-12">
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="remember_date" onchange="toggleDateField()">
                                                    <label class="form-check-label" for="remember_date">
                                                        Remember Exact Date Of Joining?
                                                    </label>
                                                </div>
                                                <div class="muted mt-1">If unchecked, you can select only the year.</div>
                                            </div>

                                            <div class="col-md-4" id="dateField">
                                                <label for="joining_year" class="form-label">Year of Joining</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa-solid fa-calendar-check"></i></span>
                                                    <select class="form-select" id="joining_year" name="joining_year">
                                                        <option value="">Select Year</option>
                                                        @for ($i = date('Y'); $i >= 1900; $i--)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit -->
                                    <div class="col-12 text-center mt-3">
                                        <button type="submit" class="btn btn-sp btn-lg w-50">
                                            <i class="fa-solid fa-floppy-disk me-2"></i> Submit
                                        </button>
                                        <div class="muted mt-2">By submitting, you invoke seva with truthful details. ✨</div>
                                    </div>
                                </div>
                            </div>

                            <!-- FAMILY (placeholder) -->
                            <div class="tab-pane fade section-sp" id="family-pane" role="tabpanel" aria-labelledby="family-tab">
                                <div class="alert alert-info mb-0">
                                    This section can hold family details in the next iteration. Current fields are under <strong>Profile</strong>.
                                </div>
                            </div>

                            <!-- ID CARD (placeholder) -->
                            <div class="tab-pane fade section-sp" id="id-card-pane" role="tabpanel" aria-labelledby="id-card-tab">
                                <div class="alert alert-info mb-0">
                                    Manage ID related uploads & numbers. For now, add them in <strong>Profile</strong>.
                                </div>
                            </div>

                            <!-- ADDRESS (placeholder) -->
                            <div class="tab-pane fade section-sp" id="address-pane" role="tabpanel" aria-labelledby="address-tab">
                                <div class="alert alert-info mb-0">
                                    Address fields can be added here as needed.
                                </div>
                            </div>

                            <!-- OCCUPATION (placeholder) -->
                            <div class="tab-pane fade section-sp" id="occupation-pane" role="tabpanel" aria-labelledby="occupation-tab">
                                <div class="alert alert-info mb-0">
                                    Occupation details will be available here in future versions.
                                </div>
                            </div>

                            <!-- SEBA (placeholder) -->
                            <div class="tab-pane fade section-sp" id="seba-pane" role="tabpanel" aria-labelledby="seba-details-tab">
                                <div class="alert alert-info mb-0">
                                    Service (Seba) preferences & contributions can be configured here.
                                </div>
                            </div>

                            <!-- SOCIAL (placeholder) -->
                            <div class="tab-pane fade section-sp" id="social-pane" role="tabpanel" aria-labelledby="social-media-tab">
                                <div class="alert alert-info mb-0">
                                    Add social links & handles here in the next update.
                                </div>
                            </div>
                        </div> <!-- /tab-content -->
                    </form>
                </div> <!-- /card-body -->
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
                        <span class="input-group-text"><i class="fa-solid fa-calendar-check"></i></span>
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
                        <span class="input-group-text"><i class="fa-solid fa-calendar-check"></i></span>
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
                confirmButtonColor: '#caa66a',
                confirmButtonText: 'OK'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#7a68c7',
                confirmButtonText: 'OK'
            });
        @endif
    </script>

    <!-- CropperJS -->
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

                    document.getElementById('cropperModal').addEventListener('shown.bs.modal', function onShown(){
                        document.getElementById('cropperModal').removeEventListener('shown.bs.modal', onShown);
                        if (cropper) cropper.destroy();
                        cropper = new Cropper(image, {
                            aspectRatio: 1,
                            viewMode: 2,
                            autoCropArea: 1,
                        });
                    }, { once: true });
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('cropImageBtn').addEventListener('click', function() {
            if (!cropper) return;
            const croppedCanvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
            croppedCanvas.toBlob(function(blob) {
                const file = new File([blob], 'profile_photo.jpg', { type: 'image/jpeg' });

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

    <!-- Bootstrap 5 Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
