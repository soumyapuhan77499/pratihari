@extends('layouts.app')

@section('styles')
    <!-- One Bootstrap + One Font Awesome (avoid version conflicts) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root{
            /* Brand system */
            --brand-a:#7c3aed; /* violet */
            --brand-b:#06b6d4; /* cyan   */
            --accent:#f5c12e;  /* amber  */
            --ink:#0b1220;
            --muted:#64748b;
            --border:rgba(2,6,23,.10);
            --surface:#ffffff;
            --soft:#f8fafc;
        }

        .card {
            border:1px solid var(--border);
            border-radius:14px;
            box-shadow:0 8px 22px rgba(2,6,23,.06);
        }

        .card-header {
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            color:#fff;
            font-size:20px;
            font-weight:800;
            text-align:center;
            padding:14px 16px;
            border-radius:14px 14px 0 0;
            letter-spacing:.3px;
        }

        /* Tabs */
        .nav-tabs{
            border:0;
            background:#fff;
            border-radius:12px;
            padding:.4rem;
            box-shadow:0 6px 18px rgba(2,6,23,.06);
        }
        .nav-tabs .nav-link{
            border:1px solid transparent;
            background:var(--soft);
            color:var(--muted);
            border-radius:10px;
            font-weight:700;
            margin:.2rem;
            padding:.6rem .9rem;
            display:flex; align-items:center; gap:.5rem;
            transition:all .18s ease;
            white-space:nowrap;
        }
        .nav-tabs .nav-link i{font-size:.95rem;}
        .nav-tabs .nav-link:hover{
            background:#eef2ff; color:var(--ink);
            transform:translateY(-1px);
            border-color:rgba(124,58,237,.25);
        }
        .nav-tabs .nav-link.active{
            color:#fff !important;
            background:linear-gradient(90deg, var(--brand-a), var(--brand-b));
            border-color:transparent;
            box-shadow:0 10px 18px rgba(124,58,237,.22);
        }

        .tab-content{
            background:#fff;
            border-radius:12px;
            padding:16px;
        }

        /* Inputs with icons */
        .input-group-text{
            background:#fff;
            border-right:0;
            border-top-left-radius:10px;
            border-bottom-left-radius:10px;
        }
        .input-group .form-control{
            border-left:0;
            border-top-right-radius:10px;
            border-bottom-right-radius:10px;
        }

        .custom-gradient-btn{
            background:linear-gradient(90deg, var(--brand-a), var(--brand-b));
            border:0;color:#fff;
            padding:12px 18px;
            font-weight:800;
            border-radius:10px;
            box-shadow:0 12px 24px rgba(124,58,237,.22);
        }
        .custom-gradient-btn:hover{opacity:.96}

        /* Helpers */
        label{font-weight:600; color:#1f2937}
        .section-gap > [class^="col-"]{margin-bottom:12px}

        /* Responsive tabs row wrap */
        @media (max-width: 768px){
            .nav-tabs{overflow-x:auto; white-space:nowrap}
        }
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    {{-- Back button on the left --}}
                    <a href="{{ route('admin.viewProfile', ['pratihari_id' => $profile->pratihari_id]) }}"
                        class="btn btn-light btn-sm d-inline-flex align-items-center">
                        <i class="fa-solid fa-arrow-left me-1"></i>
                        <span>Back to Profile</span>
                    </a>

                    {{-- Title on the right / center-ish --}}
                    <div class="text-uppercase fw-bold d-flex align-items-center">
                        <i class="fa-solid fa-location-dot me-2"></i>
                        <span>Profile Details</span>
                    </div>
                </div>

            <div class="p-3">
                <ul class="nav nav-tabs flex-nowrap" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="profile-tab"
                           href="{{ route('admin.pratihariProfile') }}" role="tab" aria-selected="true">
                            <i class="fas fa-user"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-users"></i> Family</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-id-card"></i> ID Card</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-map-marker-alt"></i> Address</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-briefcase"></i> Occupation</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-cogs"></i> Seba</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-share-alt"></i> Social Media</a></li>
                </ul>
            </div>

            <div class="card-body pt-0">
                <form
                    action="{{ isset($profile) ? route('admin.pratihari-profile.update', $profile->pratihari_id) : route('admin.pratihari-profile.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($profile)) @method('PUT') @endif

                    <div class="row section-gap">
                        <!-- First Name -->
                        <div class="col-md-3">
                            <label for="first_name">First Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user" style="color:var(--accent)"></i></span>
                                <input type="text" name="first_name" id="first_name" class="form-control"
                                       value="{{ old('first_name', $profile->first_name ?? '') }}">
                            </div>
                        </div>

                        <!-- Middle Name -->
                        <div class="col-md-3">
                            <label for="middle_name">Middle Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user" style="color:var(--accent)"></i></span>
                                <input type="text" name="middle_name" id="middle_name" class="form-control"
                                       value="{{ old('middle_name', $profile->middle_name ?? '') }}">
                            </div>
                        </div>

                        <!-- Last Name -->
                        <div class="col-md-3">
                            <label for="last_name">Last Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user" style="color:var(--accent)"></i></span>
                                <input type="text" name="last_name" id="last_name" class="form-control"
                                       value="{{ old('last_name', $profile->last_name ?? '') }}">
                            </div>
                        </div>

                        <!-- Alias Name -->
                        <div class="col-md-3">
                            <label for="alias_name">Alias Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user-tag" style="color:var(--accent)"></i></span>
                                <input type="text" name="alias_name" id="alias_name" class="form-control"
                                       value="{{ old('alias_name', $profile->alias_name ?? '') }}">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-3">
                            <label for="email">Email ID</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-envelope" style="color:var(--accent)"></i></span>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="{{ old('email', $profile->email ?? '') }}">
                            </div>
                        </div>

                        <!-- WhatsApp No -->
                        <div class="col-md-3">
                            <label for="whatsapp_no">WhatsApp No</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-phone" style="color:var(--accent)"></i></span>
                                <input type="tel" class="form-control" id="whatsapp_no" name="whatsapp_no"
                                       pattern="\d{10}" maxlength="10" inputmode="numeric"
                                       value="{{ old('whatsapp_no', $profile->whatsapp_no ?? '') }}">
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="col-md-3">
                            <label for="phone_no">Phone No</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-phone" style="color:var(--accent)"></i></span>
                                <input type="tel" class="form-control" id="phone_no" name="phone_no"
                                       pattern="\d{10}" maxlength="10" inputmode="numeric"
                                       value="{{ old('phone_no', $profile->phone_no ?? '') }}">
                            </div>
                        </div>

                        <!-- Alternative Phone -->
                        <div class="col-md-3">
                            <label for="alt_phone_no">Alternative Phone No</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-phone" style="color:var(--accent)"></i></span>
                                <input type="tel" class="form-control" id="alt_phone_no" name="alt_phone_no"
                                       pattern="\d{10}" maxlength="10" inputmode="numeric"
                                       value="{{ old('alt_phone_no', $profile->alt_phone_no ?? '') }}">
                            </div>
                        </div>

                        <!-- Blood Group -->
                        <div class="col-md-3">
                            <label for="blood_group">Blood Group</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-tint" style="color:var(--accent)"></i></span>
                                <select class="form-control" id="blood_group" name="blood_group">
                                    <option value="">Select Blood Group</option>
                                    @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $group)
                                        <option value="{{ $group }}" {{ old('blood_group', $profile->blood_group ?? '') == $group ? 'selected' : '' }}>
                                            {{ $group }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Health Card No (fixed old() key) -->
                        <div class="col-md-3">
                            <label for="healthcard_no">Health Card No</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-id-card" style="color:var(--accent)"></i></span>
                                <input type="text" class="form-control" id="healthcard_no" name="healthcard_no"
                                       value="{{ old('healthcard_no', $profile->healthcard_no ?? '') }}">
                            </div>
                        </div>

                        <!-- Health Card Photo -->
                        <div class="col-md-3">
                            <label for="health_card_photo">Health Card Photo</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-camera" style="color:var(--accent)"></i></span>
                                <input type="file" class="form-control" id="health_card_photo" name="health_card_photo">
                            </div>
                            @if (isset($profile) && $profile->health_card_photo)
                                <button type="button" class="btn btn-sm mt-2 btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#healthCardModal">
                                    <i class="fa-solid fa-image me-1"></i> View Photo
                                </button>

                                <div class="modal fade" id="healthCardModal" tabindex="-1" aria-labelledby="healthCardModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="healthCardModalLabel">Health Card Photo</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ asset($profile->health_card_photo) }}" alt="Health Card Photo" class="img-fluid rounded">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Profile Photo -->
                        <div class="col-md-3">
                            <label for="profile_photo">Profile Photo</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-camera" style="color:var(--accent)"></i></span>
                                <input type="file" class="form-control" id="profile_photo" name="profile_photo">
                            </div>
                            @if (isset($profile) && $profile->profile_photo)
                                <button type="button" class="btn btn-sm mt-2 btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#profileModal">
                                    <i class="fa-solid fa-image me-1"></i> View Photo
                                </button>

                                <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="profileModalLabel">Profile Photo</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ asset($profile->profile_photo) }}" alt="Profile Photo" class="img-fluid rounded">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Date of Birth -->
                        <div class="col-md-3">
                            <label for="date_of_birth">Date of Birth</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-calendar" style="color:var(--accent)"></i></span>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                       value="{{ old('date_of_birth', $profile->date_of_birth ?? '') }}">
                            </div>
                        </div>

                        <!-- Joining (Year or Date with toggle) -->
                        <div class="col-md-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <label class="mb-0" for="joining_date">Joining</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="remember_date" onchange="toggleDateField()">
                                    <label class="form-check-label small" for="remember_date">Use full date</label>
                                </div>
                            </div>
                            <div id="yearField">
                                <div class="input-group mt-1">
                                    <span class="input-group-text"><i class="fa fa-calendar-alt" style="color:var(--accent)"></i></span>
                                    <select class="form-control" id="joining_date" name="joining_date">
                                        <option value="">Select Year</option>
                                        @for ($i = date('Y'); $i >= 1900; $i--)
                                            <option value="{{ $i }}" {{ old('joining_date', $profile->joining_date ?? '') == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="col-12 text-center mt-1">
                            <button type="submit" class="btn btn-lg w-50 custom-gradient-btn">
                                <i class="fa fa-save me-1"></i> {{ isset($profile) ? 'Update' : 'Submit' }}
                            </button>
                        </div>
                    </div><!-- /row -->
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- SweetAlert (flash) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
    <script>
        Swal.fire({icon:'success',title:'Success!',text:@json(session('success')),confirmButtonColor:'#0ea5e9'});
    </script>
    @endif
    @if (session('error'))
    <script>
        Swal.fire({icon:'error',title:'Error!',text:@json(session('error')),confirmButtonColor:'#ef4444'});
    </script>
    @endif

    <!-- Bootstrap JS bundle (one include) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle year <-> full date for joining_date
        function toggleDateField() {
            const checkbox = document.getElementById("remember_date");
            const yearField = document.getElementById("yearField");
            if (checkbox.checked) {
                yearField.innerHTML = `
                    <div class="input-group mt-1">
                        <span class="input-group-text">
                            <i class="fa fa-calendar-alt" style="color:var(--accent)"></i>
                        </span>
                        <input type="date" class="form-control" id="joining_date" name="joining_date"
                               value="{{ old('joining_date', (isset($profile) && strlen((string)$profile->joining_date) > 4) ? $profile->joining_date : '') }}">
                    </div>
                `;
            } else {
                const currentYear = new Date().getFullYear();
                let options = `<option value="">Select Year</option>`;
                for (let i = currentYear; i >= 1900; i--) {
                    const selected = (String(i) === `{{ old('joining_date', $profile->joining_date ?? '') }}`) ? 'selected' : '';
                    options += `<option value="${i}" ${selected}>${i}</option>`;
                }
                yearField.innerHTML = `
                    <div class="input-group mt-1">
                        <span class="input-group-text">
                            <i class="fa fa-calendar-alt" style="color:var(--accent)"></i>
                        </span>
                        <select class="form-control" id="joining_date" name="joining_date">
                            ${options}
                        </select>
                    </div>
                `;
            }
        }
    </script>
@endsection
