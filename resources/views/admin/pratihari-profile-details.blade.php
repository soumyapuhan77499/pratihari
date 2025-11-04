@extends('layouts.app')

@section('styles')
    <!-- Bootstrap 5 + Font Awesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Cropper -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />

    <style>
        :root {
            /* Brand palette */
            --brand-a: #7c3aed;
            /* violet  */
            --brand-b: #06b6d4;
            /* cyan    */
            --brand-c: #22c55e;
            /* emerald */
            --ink: #0b1220;
            --muted: #64748b;
            --border: rgba(2, 6, 23, .10);
            --ring: rgba(6, 182, 212, .28);

            /* Chip base colors */
            --violet: #7c3aed;
            --indigo: #6366f1;
            --cyan: #06b6d4;
            --sky: #0ea5e9;
            --emerald: #22c55e;
            --green: #34d399;
            --amber: #f59e0b;
            --orange: #f97316;
            --rose: #fb7185;
            --pink: #ec4899;
            --slate: #334155;
            --slate-2: #64748b;
        }

        /* Page header */
        .page-header {
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            color: #fff;
            border-radius: 1rem;
            padding: 1.05rem 1.25rem;
            box-shadow: 0 10px 24px rgba(6, 182, 212, .18);
        }

        .page-header .title {
            font-weight: 800;
            letter-spacing: .3px;
        }

        /* Tab bar */
        .tabbar {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 8px 22px rgba(2, 6, 23, .06);
            padding: .35rem;
            overflow: auto;
            scrollbar-width: thin;
        }

        .tabbar .nav {
            flex-wrap: nowrap;
            gap: .35rem;
        }

        .tabbar .nav-link {
            display: flex;
            align-items: center;
            gap: .55rem;
            border: 1px solid transparent;
            background: #f8fafc;
            color: var(--muted);
            border-radius: 11px;
            padding: .55rem .9rem;
            font-weight: 700;
            white-space: nowrap;
            transition: transform .12s ease, background .2s ease, color .2s ease, border-color .2s ease;
        }

        .tabbar .nav-link:hover {
            background: #eef2ff;
            color: var(--ink);
            transform: translateY(-1px);
            border-color: rgba(124, 58, 237, .25);
        }

        .tabbar .nav-link.active {
            color: #fff !important;
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            border-color: transparent;
            box-shadow: 0 10px 18px rgba(124, 58, 237, .25);
        }

        .tabbar .nav-link i {
            font-size: .95rem;
        }

        /* Card */
        .card {
            border: 1px solid var(--border);
            border-radius: 1rem;
        }

        /* Section headings */
        .section-title {
            font-weight: 800;
            color: var(--ink);
        }

        .section-hint {
            color: var(--muted);
            font-size: .9rem;
        }

        /* ===== Underline input style ===== */
        .form-label {
            font-weight: 600;
            margin-bottom: .35rem;
        }

        .input-group {
            display: flex;
            align-items: center;
            gap: .6rem;
            border-bottom: 2px solid var(--border);
            padding-bottom: .25rem;
            background: transparent;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .input-group:focus-within {
            border-bottom-color: var(--brand-b);
            box-shadow: 0 6px 0 -5px var(--ring);
        }

        .form-control,
        .form-select {
            border: 0 !important;
            border-radius: 0 !important;
            background: transparent !important;
            padding: .45rem 0 .25rem 0;
            height: auto;
            box-shadow: none !important;
            color: var(--ink);
        }

        .form-control::placeholder {
            color: #9aa4b2;
        }

        .form-select {
            padding-right: 1.6rem;
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
        }

        /* ===== Chips (icon pills) — solid + vibrant ===== */
        .input-group .chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            min-width: 40px;
            height: 40px;
            border-radius: 10px;
            color: #fff;
            /* icon color */
            box-shadow: 0 6px 16px rgba(2, 6, 23, .12);
            flex: 0 0 40px;
        }

        .chip i {
            font-size: 1rem;
            line-height: 1;
            color: #fff !important;
        }

        /* force white icons */

        /* Per-color chips (safe gradients, no fancy features) */
        .chip--violet {
            background: linear-gradient(135deg, var(--violet), var(--indigo));
        }

        .chip--indigo {
            background: linear-gradient(135deg, var(--indigo), var(--violet));
        }

        .chip--cyan {
            background: linear-gradient(135deg, var(--cyan), var(--sky));
        }

        .chip--emerald {
            background: linear-gradient(135deg, var(--emerald), var(--green));
        }

        .chip--amber {
            background: linear-gradient(135deg, var(--amber), var(--orange));
        }

        .chip--rose {
            background: linear-gradient(135deg, var(--rose), var(--pink));
        }

        .chip--slate {
            background: linear-gradient(135deg, var(--slate), var(--slate-2));
        }

        /* Layout tweaks */
        .row.g-3 {
            --bs-gutter-x: 1rem;
            --bs-gutter-y: 1rem;
        }

        .tab-pane {
            padding: 1rem .25rem;
        }

        .btn-brand {
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            border: 0;
            color: #fff;
            box-shadow: 0 14px 30px rgba(124, 58, 237, .25);
        }

        .btn-brand:hover {
            opacity: .96;
        }

        #cropperImage {
            max-width: 100%;
            max-height: 420px;
        }

        .divider {
            height: 1px;
            background: var(--border);
            margin: 1rem 0;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid my-3">
        <!-- Page header -->
        <div class="page-header mb-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <div class="title h4 mb-0">Add / Edit Pratihari Profile</div>
                    <div class="small opacity-75">A clean, aligned, and colorful form consistent with your dashboard.</div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabbar mb-3">
            <ul class="nav" id="profileTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="tab-profile" data-bs-toggle="tab" data-bs-target="#pane-profile"
                        type="button" role="tab" aria-controls="pane-profile" aria-selected="true">
                        <i class="fa-solid fa-user"></i> Profile
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-family" data-bs-toggle="tab" data-bs-target="#pane-family"
                        type="button" role="tab" aria-controls="pane-family" aria-selected="false">
                        <i class="fa-solid fa-users"></i> Family
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-id" data-bs-toggle="tab" data-bs-target="#pane-id" type="button"
                        role="tab" aria-controls="pane-id" aria-selected="false">
                        <i class="fa-solid fa-id-card"></i> ID Card
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-address" data-bs-toggle="tab" data-bs-target="#pane-address"
                        type="button" role="tab" aria-controls="pane-address" aria-selected="false">
                        <i class="fa-solid fa-location-dot"></i> Address
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-occupation" data-bs-toggle="tab" data-bs-target="#pane-occupation"
                        type="button" role="tab" aria-controls="pane-occupation" aria-selected="false">
                        <i class="fa-solid fa-briefcase"></i> Occupation
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-seba" data-bs-toggle="tab" data-bs-target="#pane-seba" type="button"
                        role="tab" aria-controls="pane-seba" aria-selected="false">
                        <i class="fa-solid fa-gears"></i> Seba
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-social" data-bs-toggle="tab" data-bs-target="#pane-social"
                        type="button" role="tab" aria-controls="pane-social" aria-selected="false">
                        <i class="fa-solid fa-share-nodes"></i> Social Media
                    </button>
                </li>
            </ul>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.pratihari-profile.store') }}" method="POST" enctype="multipart/form-data"
                    novalidate>
                    @csrf
                    <input type="hidden" name="cropped_profile_photo" id="cropped_profile_photo">

                    <div class="tab-content" id="profileTabsContent">
                        {{-- ===================== TAB: PROFILE ===================== --}}
                        <div class="tab-pane fade show active" id="pane-profile" role="tabpanel"
                            aria-labelledby="tab-profile">
                            <div class="mb-2">
                                <div class="section-title">Basic Information</div>
                                <div class="section-hint">Provide the member’s core identity details.</div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-md-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <div class="input-group">
                                        <span class="chip chip--violet"><i class="fa-regular fa-user"></i></span>
                                        <input type="text" class="form-control" id="first_name" name="first_name"
                                            autocomplete="off" placeholder="Enter first name">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="middle_name" class="form-label">Middle Name</label>
                                    <div class="input-group">
                                        <span class="chip chip--slate"><i class="fa-regular fa-user"></i></span>
                                        <input type="text" class="form-control" id="middle_name" name="middle_name"
                                            autocomplete="off" placeholder="(Optional)">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <div class="input-group">
                                        <span class="chip chip--indigo"><i class="fa-regular fa-user"></i></span>
                                        <input type="text" class="form-control" id="last_name" name="last_name"
                                            autocomplete="off" placeholder="Enter last name">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="alias_name" class="form-label">Alias Name</label>
                                    <div class="input-group">
                                        <span class="chip chip--rose"><i class="fa-solid fa-user-pen"></i></span>
                                        <input type="text" class="form-control" id="alias_name" name="alias_name"
                                            autocomplete="off" placeholder="Also known as">
                                    </div>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <!-- Contact -->
                            <div class="mt-1 mb-2">
                                <div class="section-title">Contact</div>
                                <div class="section-hint">How can we reach the member?</div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-md-4">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="chip chip--cyan"><i class="fa-regular fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email"
                                            autocomplete="off" placeholder="name@example.com">
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="whatsapp_no" class="form-label">WhatsApp No</label>
                                    <div class="input-group">
                                        <span class="chip chip--emerald"><i class="fa-solid fa-phone"></i></span>
                                        <input type="tel" class="form-control" id="whatsapp_no" name="whatsapp_no"
                                            pattern="\d{10}" maxlength="10" autocomplete="off"
                                            placeholder="10-digit number">
                                    </div>
                                    <div class="form-text">10 digits only.</div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="phone_no" class="form-label">Primary Phone No</label>
                                    <div class="input-group">
                                        <span class="chip chip--amber"><i class="fa-solid fa-square-phone"></i></span>
                                        <input type="tel" class="form-control" id="phone_no" name="phone_no"
                                            pattern="\d{10}" maxlength="10" autocomplete="off"
                                            placeholder="10-digit number">
                                    </div>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <!-- Health & Photo -->
                            <div class="mt-1 mb-2">
                                <div class="section-title">Health & Photo</div>
                                <div class="section-hint">Blood group, card and profile picture.</div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-md-3">
                                    <label for="blood_group" class="form-label">Blood Group</label>
                                    <div class="input-group">
                                        <span class="chip chip--rose"><i class="fa-solid fa-droplet"></i></span>
                                        <select class="form-select" id="blood_group" name="blood_group">
                                            <option value="">Select Blood Group</option>
                                            @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                                                <option value="{{ $bg }}">{{ $bg }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="healthcard_no" class="form-label">Health Card No</label>
                                    <div class="input-group">
                                        <span class="chip chip--slate"><i class="fa-regular fa-id-card"></i></span>
                                        <input type="text" class="form-control" id="healthcard_no"
                                            name="healthcard_no" autocomplete="off" placeholder="Card number">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="health_card_photo" class="form-label">Health Card Photo</label>
                                    <div class="input-group">
                                        <span class="chip chip--indigo"><i class="fa-regular fa-image"></i></span>
                                        <input type="file" class="form-control" id="health_card_photo"
                                            name="health_card_photo" accept="image/*">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="profile_photo" class="form-label">Profile Photo</label>
                                    <div class="input-group">
                                        <span class="chip chip--violet"><i class="fa-solid fa-camera"></i></span>
                                        <input type="file" class="form-control" id="profile_photo"
                                            name="original_photo" accept="image/*">
                                    </div>
                                </div>
                            </div>

                            <!-- Cropper Modal -->
                            <div class="modal fade" id="cropperModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title fw-bold">Crop Profile Photo</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img id="cropperImage" alt="Crop area">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-brand" id="cropImageBtn">
                                                <i class="fa-solid fa-scissors me-1"></i>Save & Continue
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <!-- Dates -->
                            <div class="mt-1 mb-2">
                                <div class="section-title">Dates</div>
                                <div class="section-hint">Birth date and joining details.</div>
                            </div>

                            <div class="row g-3 align-items-end">
                                <div class="col-12 col-md-4">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <div class="input-group">
                                        <span class="chip chip--cyan"><i class="fa-regular fa-calendar"></i></span>
                                        <input type="date" class="form-control" id="date_of_birth"
                                            name="date_of_birth">
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" value="1"
                                            id="remember_date" onchange="toggleDateField()">
                                        <label class="form-check-label" for="remember_date">Remember exact Date of
                                            Joining?</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4" id="dateField">
                                    <label for="joining_year" class="form-label">Year of Joining</label>
                                    <div class="input-group">
                                        <span class="chip chip--cyan"><i class="fa-regular fa-calendar-days"></i></span>
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
                                <button type="submit" class="btn btn-lg px-5 btn-brand">
                                    <i class="fa-regular fa-floppy-disk me-2"></i>Submit
                                </button>
                            </div>
                        </div>

                        {{-- ===================== TAB: FAMILY ===================== --}}
                        <div class="tab-pane fade" id="pane-family" role="tabpanel" aria-labelledby="tab-family">
                            <div class="mb-2">
                                <div class="section-title">Family Details</div>
                                <div class="section-hint">Add parents, spouse, and dependents.</div>
                            </div>
                            <div class="row g-3">
                                <div class="col-12 col-md-4">
                                    <label class="form-label" for="father_name">Father’s Name</label>
                                    <div class="input-group">
                                        <span class="chip chip--slate"><i class="fa-solid fa-user"></i></span>
                                        <input type="text" class="form-control" id="father_name" name="father_name"
                                            placeholder="Enter name">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label" for="mother_name">Mother’s Name</label>
                                    <div class="input-group">
                                        <span class="chip chip--rose"><i class="fa-solid fa-person-dress"></i></span>
                                        <input type="text" class="form-control" id="mother_name" name="mother_name"
                                            placeholder="Enter name">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label" for="spouse_name">Spouse Name</label>
                                    <div class="input-group">
                                        <span class="chip chip--violet"><i
                                                class="fa-solid fa-people-arrows-left-right"></i></span>
                                        <input type="text" class="form-control" id="spouse_name" name="spouse_name"
                                            placeholder="Enter name">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ===================== TAB: ID CARD ===================== --}}
                        <div class="tab-pane fade" id="pane-id" role="tabpanel" aria-labelledby="tab-id">
                            <div class="mb-2">
                                <div class="section-title">Identity Card</div>
                                <div class="section-hint">Govt-issued identity details.</div>
                            </div>
                            <div class="row g-3">
                                <div class="col-12 col-md-4">
                                    <label class="form-label" for="aadhaar_no">Aadhaar Number</label>
                                    <div class="input-group">
                                        <span class="chip chip--indigo"><i class="fa-regular fa-id-card"></i></span>
                                        <input type="text" class="form-control" id="aadhaar_no" name="aadhaar_no"
                                            placeholder="XXXX XXXX XXXX">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label" for="pan_no">PAN</label>
                                    <div class="input-group">
                                        <span class="chip chip--amber"><i class="fa-solid fa-id-badge"></i></span>
                                        <input type="text" class="form-control" id="pan_no" name="pan_no"
                                            placeholder="ABCDE1234F">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ===================== TAB: ADDRESS ===================== --}}
                        <div class="tab-pane fade" id="pane-address" role="tabpanel" aria-labelledby="tab-address">
                            <div class="mb-2">
                                <div class="section-title">Address</div>
                                <div class="section-hint">Current and permanent address.</div>
                            </div>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="address_line">Address Line</label>
                                    <div class="input-group">
                                        <span class="chip chip--cyan"><i class="fa-solid fa-location-dot"></i></span>
                                        <input type="text" class="form-control" id="address_line" name="address_line"
                                            placeholder="Street, area">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label class="form-label" for="city">City</label>
                                    <div class="input-group">
                                        <span class="chip chip--emerald"><i class="fa-solid fa-city"></i></span>
                                        <input type="text" class="form-control" id="city" name="city"
                                            placeholder="City">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label class="form-label" for="pincode">Pincode</label>
                                    <div class="input-group">
                                        <span class="chip chip--slate"><i class="fa-solid fa-hashtag"></i></span>
                                        <input type="text" class="form-control" id="pincode" name="pincode"
                                            maxlength="6" placeholder="6 digits">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ===================== TAB: OCCUPATION ===================== --}}
                        <div class="tab-pane fade" id="pane-occupation" role="tabpanel"
                            aria-labelledby="tab-occupation">
                            <div class="mb-2">
                                <div class="section-title">Occupation</div>
                                <div class="section-hint">Employment and skills.</div>
                            </div>
                            <div class="row g-3">
                                <div class="col-12 col-md-4">
                                    <label class="form-label" for="profession">Profession</label>
                                    <div class="input-group">
                                        <span class="chip chip--violet"><i class="fa-solid fa-briefcase"></i></span>
                                        <input type="text" class="form-control" id="profession" name="profession"
                                            placeholder="Your role">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label" for="organization">Organization</label>
                                    <div class="input-group">
                                        <span class="chip chip--indigo"><i class="fa-solid fa-building"></i></span>
                                        <input type="text" class="form-control" id="organization" name="organization"
                                            placeholder="Company / Org">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ===================== TAB: SEBA ===================== --}}
                        <div class="tab-pane fade" id="pane-seba" role="tabpanel" aria-labelledby="tab-seba">
                            <div class="mb-2">
                                <div class="section-title">Seba</div>
                                <div class="section-hint">Volunteering and services.</div>
                            </div>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="seba_area">Area of Service</label>
                                    <div class="input-group">
                                        <span class="chip chip--emerald"><i
                                                class="fa-solid fa-handshake-angle"></i></span>
                                        <input type="text" class="form-control" id="seba_area" name="seba_area"
                                            placeholder="e.g., Event coordination">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ===================== TAB: SOCIAL ===================== --}}
                        <div class="tab-pane fade" id="pane-social" role="tabpanel" aria-labelledby="tab-social">
                            <div class="mb-2">
                                <div class="section-title">Social Media</div>
                                <div class="section-hint">Links to public profiles.</div>
                            </div>
                            <div class="row g-3">
                                <div class="col-12 col-md-4">
                                    <label class="form-label" for="facebook">Facebook</label>
                                    <div class="input-group">
                                        <span class="chip chip--indigo"><i class="fa-brands fa-facebook-f"></i></span>
                                        <input type="url" class="form-control" id="facebook" name="facebook"
                                            placeholder="https://facebook.com/username">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label" for="instagram">Instagram</label>
                                    <div class="input-group">
                                        <span class="chip chip--rose"><i class="fa-brands fa-instagram"></i></span>
                                        <input type="url" class="form-control" id="instagram" name="instagram"
                                            placeholder="https://instagram.com/username">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label" for="twitter">Twitter/X</label>
                                    <div class="input-group">
                                        <span class="chip chip--slate"><i class="fa-brands fa-x-twitter"></i></span>
                                        <input type="url" class="form-control" id="twitter" name="twitter"
                                            placeholder="https://x.com/handle">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- /tab-content -->
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
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: @json(session('success')),
                confirmButtonColor: '#0ea5e9'
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: @json(session('error')),
                confirmButtonColor: '#ef4444'
            });
        </script>
    @endif

    <!-- Bootstrap + Cropper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <script>
        function toggleDateField() {
            const checked = document.getElementById("remember_date").checked;
            const wrap = document.getElementById("dateField");
            if (checked) {
                wrap.innerHTML = `
                    <label for="joining_date" class="form-label">Date of Joining</label>
                    <div class="input-group">
                        <span class="chip chip--cyan"><i class="fa-regular fa-calendar-days"></i></span>
                        <input type="date" class="form-control" id="joining_date" name="joining_date">
                    </div>`;
            } else {
                const y = new Date().getFullYear();
                let opts = '<option value="">Select Year</option>';
                for (let i = y; i >= 1900; i--) {
                    opts += `<option value="${i}">${i}</option>`;
                }
                wrap.innerHTML = `
                    <label for="joining_year" class="form-label">Year of Joining</label>
                    <div class="input-group">
                        <span class="chip chip--cyan"><i class="fa-regular fa-calendar-days"></i></span>
                        <select class="form-select" id="joining_year" name="joining_year">${opts}</select>
                    </div>`;
            }
        }

        // Cropper
        let cropper;
        const fileInput = document.getElementById('profile_photo');
        const imageEl = document.getElementById('cropperImage');
        const modalEl = document.getElementById('cropperModal');
        const modal = new bootstrap.Modal(modalEl);

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
            const canvas = cropper.getCroppedCanvas({
                width: 300,
                height: 300
            });
            canvas.toBlob((blob) => {
                const file = new File([blob], 'profile_photo.jpg', {
                    type: 'image/jpeg'
                });
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
                document.getElementById('cropped_profile_photo').value = canvas.toDataURL('image/jpeg');
                modal.hide();
            }, 'image/jpeg');
        });
    </script>
@endsection
