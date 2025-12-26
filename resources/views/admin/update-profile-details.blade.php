@extends('layouts.app')

@section('styles')
    <!-- Bootstrap 5 + Font Awesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Cropper -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />

    <style>
        :root {
            --brand-a: #7c3aed;
            --brand-b: #06b6d4;
            --brand-c: #22c55e;
            --ink: #0b1220;
            --muted: #64748b;
            --border: rgba(2, 6, 23, .10);
            --ring: rgba(6, 182, 212, .28);
        }

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

        .card {
            border: 1px solid var(--border);
            border-radius: 1rem;
        }

        .section-title {
            font-weight: 800;
            color: var(--ink);
        }

        .section-hint {
            color: var(--muted);
            font-size: .9rem;
        }

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

        .input-group .chip,
        .switch-tile .chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            min-width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            color: #fff;
            flex: 0 0 40px;
            box-shadow: 0 6px 16px rgba(2, 6, 23, .12);
        }

        .chip i {
            font-size: 1rem;
            line-height: 1;
            color: #fff !important;
        }

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

        .switch-tile {
            display: flex;
            align-items: center;
            gap: .85rem;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: .75rem .85rem;
            background: #fff;
            box-shadow: 0 8px 18px rgba(2, 6, 23, .05);
            height: 100%;
        }

        .switch-meta {
            flex: 1;
            min-width: 0;
        }

        .switch-meta .switch-title {
            font-weight: 800;
            color: var(--ink);
            margin: 0;
            line-height: 1.2;
        }

        .switch-meta .switch-hint {
            color: var(--muted);
            font-size: .85rem;
            margin: .15rem 0 0 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .switch-tile .form-switch {
            margin: 0;
        }

        .switch-tile .form-check-input {
            width: 3.1rem;
            height: 1.55rem;
            border: 0;
            background-color: #e2e8f0;
            box-shadow: inset 0 0 0 1px rgba(2, 6, 23, .12);
            cursor: pointer;
        }

        .switch-tile .form-check-input:focus {
            box-shadow: 0 0 0 .25rem var(--ring), inset 0 0 0 1px rgba(2, 6, 23, .12);
        }

        .switch-tile .form-check-input:checked {
            background-image: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            box-shadow: 0 6px 18px rgba(124, 58, 237, .18);
        }

        .photo-preview {
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 8px 18px rgba(2, 6, 23, .05);
        }

        .photo-preview .ph {
            width: 100%;
            height: 160px;
            object-fit: cover;
            display: block;
            background: #f1f5f9;
        }

        .photo-preview .meta {
            padding: .65rem .85rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
        }

        .photo-preview .meta .t {
            font-weight: 800;
            color: var(--ink);
            margin: 0;
            font-size: .95rem;
        }

        .photo-preview .meta .s {
            color: var(--muted);
            margin: 0;
            font-size: .82rem;
        }
    </style>
@endsection

@section('content')
    @php
        $isEdit = isset($profile) && !empty($profile->pratihari_id);
        $defaultImg = asset('assets/img/1.jpeg');

        $currentProfilePhoto =
            $isEdit && !empty($profile->profile_photo) ? asset($profile->profile_photo) : $defaultImg;
        $currentHealthCardPhoto =
            $isEdit && !empty($profile->health_card_photo) ? asset($profile->health_card_photo) : $defaultImg;

        // joining_date can be "YYYY" OR "YYYY-MM-DD"
        $joiningRaw = old('joining_date', $isEdit ? $profile->joining_date ?? '' : '');
        $joiningLooksLikeDate = is_string($joiningRaw) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $joiningRaw);
    @endphp

    <div class="container-fluid my-3">
        <!-- Header -->
        <div class="page-header mb-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <div class="title h4 mb-0">
                        {{ $isEdit ? 'Edit Pratihari Profile' : 'Add Pratihari Profile' }}
                        @if ($isEdit)
                            <span class="small opacity-75 ms-2">({{ $profile->pratihari_id }})</span>
                        @endif
                    </div>
                    <div class="small opacity-75">Profile form (supports create + update).</div>
                </div>

                @if ($isEdit)
                    <a href="{{ route('admin.viewProfile', ['pratihari_id' => $profile->pratihari_id]) }}"
                        class="btn btn-light btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back to Profile
                    </a>
                @endif
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabbar mb-3">
            <ul class="nav" id="profileTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="tab-profile" data-bs-toggle="tab" data-bs-target="#pane-profile"
                        type="button" role="tab" aria-controls="pane-profile" aria-selected="true"><i
                            class="fa-solid fa-user"></i> Profile</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-family" data-bs-toggle="tab" data-bs-target="#pane-family"
                        type="button" role="tab" aria-controls="pane-family" aria-selected="false"><i
                            class="fa-solid fa-users"></i> Family</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-id" data-bs-toggle="tab" data-bs-target="#pane-id" type="button"
                        role="tab" aria-controls="pane-id" aria-selected="false"><i class="fa-solid fa-id-card"></i> ID
                        Card</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-address" data-bs-toggle="tab" data-bs-target="#pane-address"
                        type="button" role="tab" aria-controls="pane-address" aria-selected="false"><i
                            class="fa-solid fa-location-dot"></i> Address</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-occupation" data-bs-toggle="tab" data-bs-target="#pane-occupation"
                        type="button" role="tab" aria-controls="pane-occupation" aria-selected="false"><i
                            class="fa-solid fa-briefcase"></i> Occupation</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-seba" data-bs-toggle="tab" data-bs-target="#pane-seba" type="button"
                        role="tab" aria-controls="pane-seba" aria-selected="false"><i class="fa-solid fa-gears"></i>
                        Seba</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-social" data-bs-toggle="tab" data-bs-target="#pane-social"
                        type="button" role="tab" aria-controls="pane-social" aria-selected="false"><i
                            class="fa-solid fa-share-nodes"></i> Social Media</button>
                </li>
            </ul>
        </div>

        <!-- Form -->
        <div class="card shadow-sm">
            <div class="card-body">

                <form
                    action="{{ $isEdit ? route('admin.pratihari-profile.update', $profile->pratihari_id) : route('admin.pratihari-profile.store') }}"
                    method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <input type="hidden" name="cropped_profile_photo" id="cropped_profile_photo">

                    <div class="tab-content" id="profileTabsContent">
                        {{-- PROFILE --}}
                        <div class="tab-pane fade show active" id="pane-profile" role="tabpanel"
                            aria-labelledby="tab-profile">

                            <div class="mb-2">
                                <div class="section-title">Basic Information</div>
                                <div class="section-hint">Provide the member’s core identity details.</div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-md-3">
                                    <label for="first_name" class="form-label">First Name <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="chip"><i class="fa-regular fa-user"></i></span>
                                        <input type="text" class="form-control" id="first_name" name="first_name"
                                            autocomplete="off"
                                            value="{{ old('first_name', $isEdit ? $profile->first_name ?? '' : '') }}"
                                            placeholder="Enter first name">
                                    </div>
                                    @error('first_name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="middle_name" class="form-label">Middle Name</label>
                                    <div class="input-group">
                                        <span class="chip"><i class="fa-regular fa-user"></i></span>
                                        <input type="text" class="form-control" id="middle_name" name="middle_name"
                                            autocomplete="off"
                                            value="{{ old('middle_name', $isEdit ? $profile->middle_name ?? '' : '') }}"
                                            placeholder="(Optional)">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <div class="input-group">
                                        <span class="chip"><i class="fa-regular fa-user"></i></span>
                                        <input type="text" class="form-control" id="last_name" name="last_name"
                                            autocomplete="off"
                                            value="{{ old('last_name', $isEdit ? $profile->last_name ?? '' : '') }}"
                                            placeholder="Enter last name">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="alias_name" class="form-label">Alias Name</label>
                                    <div class="input-group">
                                        <span class="chip"><i class="fa-solid fa-user-pen"></i></span>
                                        <input type="text" class="form-control" id="alias_name" name="alias_name"
                                            autocomplete="off"
                                            value="{{ old('alias_name', $isEdit ? $profile->alias_name ?? '' : '') }}"
                                            placeholder="Also known as">
                                    </div>
                                </div>
                            </div>

                            {{-- Bhagari + Baristha Bhai Pua (edit-safe) --}}
                            <div class="row g-3 mt-1">
                                <div class="col-12 col-md-6">
                                    <div class="switch-tile">
                                        <span class="chip"><i class="fa-solid fa-user-check"></i></span>
                                        <div class="switch-meta">
                                            <p class="switch-title mb-0">Bhagari</p>
                                            <p class="switch-hint mb-0">Toggle “Yes” if applicable</p>
                                        </div>

                                        <input type="hidden" name="bhagari" value="0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="bhagari"
                                                name="bhagari" value="1"
                                                {{ old('bhagari', $isEdit ? (int) ($profile->bhagari ?? 0) : 0) ? 'checked' : '' }}>
                                            <label class="form-check-label visually-hidden" for="bhagari">Bhagari</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="switch-tile">
                                        <span class="chip"><i class="fa-solid fa-user-tie"></i></span>
                                        <div class="switch-meta">
                                            <p class="switch-title mb-0">Baristha Bhai Pua</p>
                                            <p class="switch-hint mb-0">Toggle “Yes” if applicable</p>
                                        </div>

                                        <input type="hidden" name="baristha_bhai_pua" value="0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="baristha_bhai_pua"
                                                name="baristha_bhai_pua" value="1"
                                                {{ old('baristha_bhai_pua', $isEdit ? (int) ($profile->baristha_bhai_pua ?? 0) : 0) ? 'checked' : '' }}>
                                            <label class="form-check-label visually-hidden"
                                                for="baristha_bhai_pua">Baristha Bhai Pua</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <div class="mt-1 mb-2">
                                <div class="section-title">Contact</div>
                                <div class="section-hint">How can we reach the member?</div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-md-4">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="chip"><i class="fa-regular fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email"
                                            autocomplete="off"
                                            value="{{ old('email', $isEdit ? $profile->email ?? '' : '') }}"
                                            placeholder="name@example.com">
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="whatsapp_no" class="form-label">WhatsApp No</label>
                                    <div class="input-group">
                                        <span class="chip"><i class="fa-solid fa-phone"></i></span>
                                        <input type="tel" class="form-control" id="whatsapp_no" name="whatsapp_no"
                                            pattern="\d{10}" maxlength="10" autocomplete="off"
                                            value="{{ old('whatsapp_no', $isEdit ? $profile->whatsapp_no ?? '' : '') }}"
                                            placeholder="10-digit number">
                                    </div>
                                    <div class="form-text">10 digits only.</div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="phone_no" class="form-label">Primary Phone No</label>
                                    <div class="input-group">
                                        <span class="chip"><i class="fa-solid fa-square-phone"></i></span>
                                        <input type="tel" class="form-control" id="phone_no" name="phone_no"
                                            pattern="\d{10}" maxlength="10" autocomplete="off"
                                            value="{{ old('phone_no', $isEdit ? $profile->phone_no ?? '' : '') }}"
                                            placeholder="10-digit number">
                                    </div>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <div class="mt-1 mb-2">
                                <div class="section-title">Health & Photo</div>
                                <div class="section-hint">Blood group, card and profile picture.</div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-md-3">
                                    <label for="blood_group" class="form-label">Blood Group</label>
                                    <div class="input-group">
                                        <span class="chip"><i class="fa-solid fa-droplet"></i></span>
                                        <select class="form-select" id="blood_group" name="blood_group">
                                            <option value="">Select Blood Group</option>
                                            @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                                                <option value="{{ $bg }}"
                                                    {{ old('blood_group', $isEdit ? $profile->blood_group ?? '' : '') === $bg ? 'selected' : '' }}>
                                                    {{ $bg }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="healthcard_no" class="form-label">Health Card No</label>
                                    <div class="input-group">
                                        <span class="chip"><i class="fa-regular fa-id-card"></i></span>
                                        <input type="text" class="form-control" id="healthcard_no"
                                            name="healthcard_no" autocomplete="off"
                                            value="{{ old('healthcard_no', $isEdit ? $profile->healthcard_no ?? '' : '') }}"
                                            placeholder="Card number">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="health_card_photo" class="form-label">Health Card Photo</label>
                                    <div class="input-group">
                                        <span class="chip"><i class="fa-regular fa-image"></i></span>
                                        <input type="file" class="form-control" id="health_card_photo"
                                            name="health_card_photo" accept="image/*">
                                    </div>
                                    @error('health_card_photo')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="profile_photo" class="form-label">Profile Photo</label>
                                    <div class="input-group">
                                        <span class="chip"><i class="fa-solid fa-camera"></i></span>
                                        {{-- IMPORTANT: name must be profile_photo (controller expects profile_photo) --}}
                                        <input type="file" class="form-control" id="profile_photo"
                                            name="profile_photo" accept="image/*">
                                    </div>
                                    @error('profile_photo')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Current photo previews (default to assets/img/1.jpeg) --}}
                            <div class="row g-3 mt-2">
                                <div class="col-12 col-md-6">
                                    <div class="photo-preview">
                                        <img class="ph" src="{{ $currentProfilePhoto }}"
                                            alt="Profile Photo Preview">
                                        <div class="meta">
                                            <div>
                                                <p class="t mb-0">Profile Photo</p>
                                                <p class="s mb-0">Current image (default if none)</p>
                                            </div>
                                            @if ($isEdit && !empty($profile->profile_photo))
                                                <span class="badge text-bg-success">Saved</span>
                                            @else
                                                <span class="badge text-bg-secondary">Default</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="photo-preview">
                                        <img class="ph" src="{{ $currentHealthCardPhoto }}"
                                            alt="Health Card Photo Preview">
                                        <div class="meta">
                                            <div>
                                                <p class="t mb-0">Health Card Photo</p>
                                                <p class="s mb-0">Current image (default if none)</p>
                                            </div>
                                            @if ($isEdit && !empty($profile->health_card_photo))
                                                <span class="badge text-bg-success">Saved</span>
                                            @else
                                                <span class="badge text-bg-secondary">Default</span>
                                            @endif
                                        </div>
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
                                                <i class="fa-solid fa-scissors me-1"></i> Save & Continue
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <div class="mt-1 mb-2">
                                <div class="section-title">Dates</div>
                                <div class="section-hint">Birth date and joining details.</div>
                            </div>

                            <div class="row g-3 align-items-end">
                                <div class="col-12 col-md-4">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <div class="input-group">
                                        <span class="chip"><i class="fa-regular fa-calendar"></i></span>
                                        <input type="date" class="form-control" id="date_of_birth"
                                            name="date_of_birth"
                                            value="{{ old('date_of_birth', $isEdit && !empty($profile->date_of_birth) ? \Illuminate\Support\Carbon::parse($profile->date_of_birth)->format('Y-m-d') : '') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" value="1"
                                            id="remember_date" onchange="toggleDateField()">
                                        <label class="form-check-label" for="remember_date">
                                            Remember exact Date of Joining?
                                        </label>
                                    </div>
                                </div>

                                {{-- We always post "joining_date" (controller update reads joining_date) --}}
                                <div class="col-12 col-md-4" id="dateField"></div>
                                <input type="hidden" id="joining_date_hidden" value="{{ $joiningRaw }}">
                            </div>

                            <div class="text-center mt-5">
                                <button type="submit" class="btn btn-lg px-5 btn-brand"  style="color: white">
                                    <i class="fa-regular fa-floppy-disk me-2"></i>
                                    {{ $isEdit ? 'Update' : 'Submit' }}
                                </button>
                            </div>
                        </div>

                        {{-- FAMILY --}}
                        <div class="tab-pane fade" id="pane-family" role="tabpanel" aria-labelledby="tab-family">
                            <div class="mb-2">
                                <div class="section-title">Family Details</div>
                                <div class="section-hint">Add parents, spouse, and dependents.</div>
                            </div>
                        </div>

                        {{-- ID CARD --}}
                        <div class="tab-pane fade" id="pane-id" role="tabpanel" aria-labelledby="tab-id">
                            <div class="mb-2">
                                <div class="section-title">Identity Card</div>
                                <div class="section-hint">Govt-issued identity details.</div>
                            </div>
                        </div>

                        {{-- ADDRESS --}}
                        <div class="tab-pane fade" id="pane-address" role="tabpanel" aria-labelledby="tab-address">
                            <div class="mb-2">
                                <div class="section-title">Address</div>
                                <div class="section-hint">Current and permanent address.</div>
                            </div>
                        </div>

                        {{-- OCCUPATION --}}
                        <div class="tab-pane fade" id="pane-occupation" role="tabpanel"
                            aria-labelledby="tab-occupation">
                            <div class="mb-2">
                                <div class="section-title">Occupation</div>
                                <div class="section-hint">Employment and skills.</div>
                            </div>
                        </div>

                        {{-- SEBA --}}
                        <div class="tab-pane fade" id="pane-seba" role="tabpanel" aria-labelledby="tab-seba">
                            <div class="mb-2">
                                <div class="section-title">Seba</div>
                                <div class="section-hint">Volunteering and services.</div>
                            </div>
                        </div>

                        {{-- SOCIAL --}}
                        <div class="tab-pane fade" id="pane-social" role="tabpanel" aria-labelledby="tab-social">
                            <div class="mb-2">
                                <div class="section-title">Social Media</div>
                                <div class="section-hint">Links to public profiles.</div>
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
        function buildYearSelect(selectedYear) {
            const y = new Date().getFullYear();
            let opts = '<option value="">Select Year</option>';
            for (let i = y; i >= 1900; i--) {
                const sel = (String(selectedYear) === String(i)) ? 'selected' : '';
                opts += `<option value="${i}" ${sel}>${i}</option>`;
            }
            return `
                <label for="joining_date" class="form-label">Year of Joining</label>
                <div class="input-group">
                    <span class="chip"><i class="fa-regular fa-calendar-days"></i></span>
                    <select class="form-select" id="joining_date" name="joining_date">${opts}</select>
                </div>`;
        }

        function buildDateInput(selectedDate) {
            return `
                <label for="joining_date" class="form-label">Date of Joining</label>
                <div class="input-group">
                    <span class="chip"><i class="fa-regular fa-calendar-days"></i></span>
                    <input type="date" class="form-control" id="joining_date" name="joining_date" value="${selectedDate || ''}">
                </div>`;
        }

        function toggleDateField(forceMode = null) {
            const wrap = document.getElementById("dateField");
            const checked = (forceMode === null) ?
                document.getElementById("remember_date").checked :
                (forceMode === 'date');

            const current = document.getElementById('joining_date_hidden')?.value || '';

            // If checked => date input, else => year select (still posts as joining_date)
            if (checked) {
                wrap.innerHTML = buildDateInput(current);
            } else {
                wrap.innerHTML = buildYearSelect(current);
            }
        }

        // Initialize Joining field using existing value
        (function initJoining() {
            const current = document.getElementById('joining_date_hidden')?.value || '';
            const looksLikeDate = /^\d{4}-\d{2}-\d{2}$/.test(current);

            if (looksLikeDate) {
                document.getElementById('remember_date').checked = true;
                toggleDateField('date');
            } else {
                document.getElementById('remember_date').checked = false;
                toggleDateField('year');
            }
        })();

        // Cropper
        let cropper;
        const fileInput = document.getElementById('profile_photo');
        const imageEl = document.getElementById('cropperImage');
        const modalEl = document.getElementById('cropperModal');
        const modal = new bootstrap.Modal(modalEl);

        if (fileInput) {
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
        }

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
