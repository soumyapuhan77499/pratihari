@extends('layouts.app')

@section('styles')
    <!-- Bootstrap 5 + Icons (you can switch to bootstrap-icons if you prefer) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Cropper -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css"/>

    <style>
        :root{
            /* Match your dashboard brand (violet → cyan) */
            --brand-a: #7c3aed;
            --brand-b: #06b6d4;
            --surface: #ffffff;
            --muted: #64748b;
        }

        /* Simple page header matching dashboard */
        .page-header{
            background: linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff;
            border-radius: 1rem;
            padding: 1.25rem 1.25rem;
        }
        .page-header .title{
            font-weight: 800;
            letter-spacing: .3px;
        }

        /* Card */
        .card{
            border: 1px solid rgba(2,6,23,.08);
            border-radius: 1rem;
        }

        /* Small, calm section title inside the form */
        .section-title{
            font-weight: 700;
            font-size: .95rem;
            color: #0b1220;
        }
        .section-hint{
            color: var(--muted);
            font-size: .875rem;
        }

        /* Icon inside input group – keep minimalist */
        .input-group-text{
            background: #f5f7fb;
        }

        /* Tabs converted to simple pill nav (no heavy styling) */
        .nav-pills .nav-link{
            font-weight: 600;
        }
        .nav-pills .nav-link.active{
            background: linear-gradient(90deg,var(--brand-a),var(--brand-b));
        }

        /* Modal image scaling */
        #cropperImage{ max-width: 100%; max-height: 420px; }
    </style>
@endsection

@section('content')
<div class="container-fluid my-3">
    <!-- Page header -->
    <div class="page-header mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <div class="title h4 mb-0">Add / Edit Pratihari Profile</div>
                <div class="small opacity-75">A clean, user-friendly form aligned with your dashboard style.</div>
            </div>
        </div>
    </div>

    <!-- Tabs (lightweight) -->
    <ul class="nav nav-pills gap-2 mb-3 flex-wrap">
        <li class="nav-item">
            <span class="nav-link active"><i class="fa-solid fa-user me-2"></i>Profile</span>
        </li>
        <li class="nav-item">
            <span class="nav-link"><i class="fa-solid fa-users me-2"></i>Family</span>
        </li>
        <li class="nav-item">
            <span class="nav-link"><i class="fa-solid fa-id-card me-2"></i>ID Card</span>
        </li>
        <li class="nav-item">
            <span class="nav-link"><i class="fa-solid fa-location-dot me-2"></i>Address</span>
        </li>
        <li class="nav-item">
            <span class="nav-link"><i class="fa-solid fa-briefcase me-2"></i>Occupation</span>
        </li>
        <li class="nav-item">
            <span class="nav-link"><i class="fa-solid fa-gears me-2"></i>Seba</span>
        </li>
        <li class="nav-item">
            <span class="nav-link"><i class="fa-solid fa-share-nodes me-2"></i>Social Media</span>
        </li>
    </ul>

    <!-- Form Card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.pratihari-profile.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <input type="hidden" name="cropped_profile_photo" id="cropped_profile_photo">

                <!-- Basic Info -->
                <div class="mb-3">
                    <div class="section-title">Basic Information</div>
                    <div class="section-hint">Provide the member’s core identity details.</div>
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="first_name" class="form-label fw-semibold">First Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                            <input type="text" class="form-control" id="first_name" name="first_name" autocomplete="off">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="middle_name" class="form-label fw-semibold">Middle Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                            <input type="text" class="form-control" id="middle_name" name="middle_name" autocomplete="off">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="last_name" class="form-label fw-semibold">Last Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                            <input type="text" class="form-control" id="last_name" name="last_name" autocomplete="off">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="alias_name" class="form-label fw-semibold">Alias Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-user-tag"></i></span>
                            <input type="text" class="form-control" id="alias_name" name="alias_name" autocomplete="off">
                        </div>
                    </div>
                </div>

                <!-- Contact -->
                <div class="mt-4 mb-3">
                    <div class="section-title">Contact</div>
                    <div class="section-hint">How can we reach the member?</div>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" autocomplete="off">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="whatsapp_no" class="form-label fw-semibold">WhatsApp No</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                            <input type="tel" class="form-control" id="whatsapp_no" name="whatsapp_no" pattern="\d{10}" maxlength="10" autocomplete="off">
                        </div>
                        <div class="form-text">10 digits only.</div>
                    </div>

                    <div class="col-md-4">
                        <label for="phone_no" class="form-label fw-semibold">Primary Phone No</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                            <input type="tel" class="form-control" id="phone_no" name="phone_no" pattern="\d{10}" maxlength="10" autocomplete="off">
                        </div>
                    </div>
                </div>

                <!-- Health & Photo -->
                <div class="mt-4 mb-3">
                    <div class="section-title">Health & Photo</div>
                    <div class="section-hint">Blood group, card and profile picture.</div>
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="blood_group" class="form-label fw-semibold">Blood Group</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-droplet"></i></span>
                            <select class="form-select" id="blood_group" name="blood_group">
                                <option value="">Select Blood Group</option>
                                @foreach (['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                    <option value="{{ $bg }}">{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="healthcard_no" class="form-label fw-semibold">Health Card No</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-regular fa-id-card"></i></span>
                            <input type="text" class="form-control" id="healthcard_no" name="healthcard_no" autocomplete="off">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="health_card_photo" class="form-label fw-semibold">Health Card Photo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-regular fa-image"></i></span>
                            <input type="file" class="form-control" id="health_card_photo" name="health_card_photo" accept="image/*">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="profile_photo" class="form-label fw-semibold">Profile Photo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-camera"></i></span>
                            <input type="file" class="form-control" id="profile_photo" name="original_photo" accept="image/*">
                        </div>
                        <input type="hidden" name="cropped_profile_photo" id="cropped_profile_photo_hidden">
                    </div>
                </div>

                <!-- Cropper Modal -->
                <div class="modal fade" id="cropperModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title fw-bold">Crop Profile Photo</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img id="cropperImage" alt="Crop area">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" id="cropImageBtn">
                                    <i class="fa-solid fa-scissors me-1"></i>Save & Continue
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dates -->
                <div class="mt-4 mb-3">
                    <div class="section-title">Dates</div>
                    <div class="section-hint">Birth date and joining details.</div>
                </div>

                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="date_of_birth" class="form-label fw-semibold">Date of Birth</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" value="1" id="remember_date" onchange="toggleDateField()">
                            <label class="form-check-label" for="remember_date">
                                Remember exact Date of Joining?
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4" id="dateField">
                        <label for="joining_year" class="form-label fw-semibold">Year of Joining</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-regular fa-calendar-days"></i></span>
                            <select class="form-select" id="joining_year" name="joining_year">
                                <option value="">Select Year</option>
                                @for ($i = date('Y'); $i >= 1900; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-lg px-5 text-white"
                            style="background: linear-gradient(90deg,var(--brand-a),var(--brand-b)); border: 0;">
                        <i class="fa-regular fa-floppy-disk me-2"></i>Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- SweetAlert (flash) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
    <script>
        Swal.fire({ icon:'success', title:'Success!', text:@json(session('success')), confirmButtonColor:'#0ea5e9' });
    </script>
    @endif

    @if (session('error'))
    <script>
        Swal.fire({ icon:'error', title:'Error!', text:@json(session('error')), confirmButtonColor:'#ef4444' });
    </script>
    @endif

    <!-- Bootstrap + Cropper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <script>
        // Toggle: Year vs Full Date
        function toggleDateField() {
            const checked = document.getElementById("remember_date").checked;
            const wrap = document.getElementById("dateField");
            if (checked) {
                wrap.innerHTML = `
                    <label for="joining_date" class="form-label fw-semibold">Date of Joining</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-regular fa-calendar-days"></i></span>
                        <input type="date" class="form-control" id="joining_date" name="joining_date">
                    </div>
                `;
            } else {
                const yearOptions = (() => {
                    const y = new Date().getFullYear();
                    let html = '<option value="">Select Year</option>';
                    for (let i=y; i>=1900; i--) html += `<option value="${i}">${i}</option>`;
                    return html;
                })();
                wrap.innerHTML = `
                    <label for="joining_year" class="form-label fw-semibold">Year of Joining</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-regular fa-calendar-days"></i></span>
                        <select class="form-select" id="joining_year" name="joining_year">${yearOptions}</select>
                    </div>
                `;
            }
        }

        // Cropper
        let cropper;
        const fileInput = document.getElementById('profile_photo');
        const imageEl  = document.getElementById('cropperImage');
        const modalEl  = document.getElementById('cropperModal');
        const modal    = new bootstrap.Modal(modalEl);

        fileInput.addEventListener('change', (e) => {
            const file = e.target.files?.[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (ev) => {
                imageEl.src = ev.target.result;
                modal.show();
            };
            reader.readAsDataURL(file);
        });

        modalEl.addEventListener('shown.bs.modal', () => {
            cropper?.destroy();
            cropper = new Cropper(imageEl, {
                aspectRatio: 1,
                viewMode: 2,
                autoCropArea: 1
            });
        });

        document.getElementById('cropImageBtn').addEventListener('click', () => {
            if (!cropper) return;
            const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
            canvas.toBlob((blob) => {
                const file = new File([blob], 'profile_photo.jpg', { type: 'image/jpeg' });
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;

                // Also store base64 if you want it server-side
                document.getElementById('cropped_profile_photo').value = canvas.toDataURL('image/jpeg');
                modal.hide();
            }, 'image/jpeg');
        });
    </script>
@endsection
