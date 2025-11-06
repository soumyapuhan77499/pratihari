@extends('layouts.app')

@section('styles')
    <!-- Bootstrap 5 + Font Awesome 6 (match profile/family/id-card) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root{
            /* Brand palette (same as profile page) */
            --brand-a:#7c3aed; /* violet */
            --brand-b:#06b6d4; /* cyan   */
            --brand-c:#22c55e; /* emerald */
            --ink:#0b1220;
            --muted:#64748b;
            --border:rgba(2,6,23,.10);
            --ring:rgba(6,182,212,.28);
        }

        /* Page header */
        .page-header{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff;border-radius:1rem;padding:1.05rem 1.25rem;
            box-shadow:0 10px 24px rgba(6,182,212,.18);
        }
        .page-header .title{font-weight:800;letter-spacing:.3px;}

        /* Tabbar */
        .tabbar{background:#fff;border:1px solid var(--border);border-radius:14px;
            box-shadow:0 8px 22px rgba(2,6,23,.06);padding:.35rem;overflow:auto;scrollbar-width:thin;}
        .tabbar .nav{flex-wrap:nowrap;gap:.35rem;}
        .tabbar .nav-link{
            display:flex;align-items:center;gap:.55rem;border:1px solid transparent;
            background:#f8fafc;color:var(--muted);border-radius:11px;
            padding:.55rem .9rem;font-weight:700;white-space:nowrap;
            transition:transform .12s ease, background .2s ease, color .2s ease, border-color .2s ease;
        }
        .tabbar .nav-link:hover{background:#eef2ff;color:var(--ink);transform:translateY(-1px);border-color:rgba(124,58,237,.25);}
        .tabbar .nav-link.active{color:#fff!important;background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            border-color:transparent;box-shadow:0 10px 18px rgba(124,58,237,.25);}
        .tabbar .nav-link i{font-size:.95rem;}
        .tabbar::-webkit-scrollbar{ height:8px; }
        .tabbar::-webkit-scrollbar-thumb{ background:#e2e8f0;border-radius:8px; }
        .tabbar::-webkit-scrollbar-track{ background:transparent; }

        /* Cards/sections */
        .card{border:1px solid var(--border);border-radius:1rem;}
        .section-title{font-weight:800;color:var(--ink);}
        .section-hint{color:var(--muted);font-size:.9rem;}
        .divider{height:1px;background:var(--border);margin:1rem 0;}

        /* Underline input rows (avoid .input-group conflicts) */
        .underline-group{
            display:flex;align-items:center;gap:.6rem;border-bottom:2px solid var(--border);
            padding-bottom:.25rem;background:transparent;transition:border-color .2s ease,box-shadow .2s ease;
        }
        .underline-group:focus-within{border-bottom-color:var(--brand-b);box-shadow:0 6px 0 -5px var(--ring);}
        .form-label{font-weight:600;margin-bottom:.35rem;}
        .form-control,.form-select,textarea.form-control{
            border:0!important;border-radius:0!important;background:transparent!important;
            padding:.45rem 0 .25rem 0;height:auto;box-shadow:none!important;color:var(--ink);
        }
        .form-control::placeholder, textarea.form-control::placeholder{color:#9aa4b2;}
        .form-select{padding-right:1.6rem;background-clip:padding-box;}
        .form-control:focus,.form-select:focus,textarea.form-control:focus{outline:none;}

        /* Brand chips */
        .chip{
            display:inline-flex;align-items:center;justify-content:center;
            width:40px;min-width:40px;height:40px;border-radius:10px;
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff;flex:0 0 40px;
            box-shadow:0 6px 16px rgba(2,6,23,.12);
        }
        .chip i{font-size:1rem;line-height:1;color:#fff !important;}

        /* Layout */
        .row.g-3{--bs-gutter-x:1rem;--bs-gutter-y:1rem;}
        .tab-pane{padding:1rem .25rem;}

        /* Buttons */
        .btn-brand{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            border:0;color:#fff;box-shadow:0 14px 30px rgba(124,58,237,.25);
        }
        .btn-brand:hover{opacity:.96;}
        .btn-brand:disabled{opacity:.6;box-shadow:none;cursor:not-allowed;}

        /* Accessibility focus */
        :focus-visible{outline:2px solid transparent;box-shadow:0 0 0 3px var(--ring) !important;border-radius:10px;}

        /* Small screens: chip size */
        @media (max-width: 400px){
            .chip{width:34px;min-width:34px;height:34px;border-radius:8px;}
        }

        @media (prefers-reduced-motion: reduce){
            *{animation-duration:.01ms !important;animation-iteration-count:1 !important;transition:none !important;}
        }
    </style>
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid my-3">
    <!-- Header -->
    <div class="page-header mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <div class="title h4 mb-0">Pratihari • Address</div>
                <div class="small opacity-75">Same design and colors as the other sections.</div>
            </div>
        </div>
    </div>

    <!-- Tabs (button tabs; Address active) -->
    <div class="tabbar mb-3">
        <ul class="nav" id="profileTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link" id="tab-profile" data-bs-toggle="tab" data-bs-target="#pane-profile" type="button" role="tab" aria-controls="pane-profile" aria-selected="false">
                    <i class="fa-solid fa-user"></i> Profile
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-family" data-bs-toggle="tab" data-bs-target="#pane-family" type="button" role="tab" aria-controls="pane-family" aria-selected="false">
                    <i class="fa-solid fa-users"></i> Family
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-id" data-bs-toggle="tab" data-bs-target="#pane-id" type="button" role="tab" aria-controls="pane-id" aria-selected="false">
                    <i class="fa-solid fa-id-card"></i> ID Card
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link active" id="tab-address" data-bs-toggle="tab" data-bs-target="#pane-address" type="button" role="tab" aria-controls="pane-address" aria-selected="true">
                    <i class="fa-solid fa-location-dot"></i> Address
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-occupation" data-bs-toggle="tab" data-bs-target="#pane-occupation" type="button" role="tab" aria-controls="pane-occupation" aria-selected="false">
                    <i class="fa-solid fa-briefcase"></i> Occupation
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-seba" data-bs-toggle="tab" data-bs-target="#pane-seba" type="button" role="tab" aria-controls="pane-seba" aria-selected="false">
                    <i class="fa-solid fa-gears"></i> Seba
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-social" data-bs-toggle="tab" data-bs-target="#pane-social" type="button" role="tab" aria-controls="pane-social" aria-selected="false">
                    <i class="fa-solid fa-share-nodes"></i> Social Media
                </button>
            </li>
        </ul>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.pratihari-address.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <input type="hidden" name="pratihari_id" value="{{ request('pratihari_id') }}">

                <div class="tab-content" id="tabsContent">
                    <!-- PROFILE (placeholder) -->
                    <div class="tab-pane fade" id="pane-profile" role="tabpanel" aria-labelledby="tab-profile">
                        <div class="text-muted">Profile section is managed on the Profile tab.</div>
                    </div>

                    <!-- FAMILY (placeholder) -->
                    <div class="tab-pane fade" id="pane-family" role="tabpanel" aria-labelledby="tab-family">
                        <div class="text-muted">Family section is managed on the Family tab.</div>
                    </div>

                    <!-- ID (placeholder) -->
                    <div class="tab-pane fade" id="pane-id" role="tabpanel" aria-labelledby="tab-id">
                        <div class="text-muted">ID Card section is managed on the ID Card tab.</div>
                    </div>

                    <!-- ADDRESS (active) -->
                    <div class="tab-pane fade show active" id="pane-address" role="tabpanel" aria-labelledby="tab-address">
                        <div class="mb-2">
                            <div class="section-title">Current Address</div>
                            <div class="section-hint">Fill in the member’s present address details.</div>
                        </div>

                        <div class="row g-3">
                            <!-- Sahi -->
                            <div class="col-md-3">
                                <label class="form-label" for="sahi">Sahi</label>
                                <div class="underline-group">
                                    <span class="chip" aria-hidden="true"><i class="fa-solid fa-location-crosshairs"></i></span>
                                    <select class="form-select" id="sahi" name="sahi">
                                        <option value="">Select Sahi</option>
                                        @foreach ($sahiList as $sahi)
                                            <option value="{{ $sahi->sahi_name }}">{{ $sahi->sahi_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Landmark -->
                            <div class="col-md-3">
                                <label class="form-label" for="landmark">Landmark</label>
                                <div class="underline-group">
                                    <span class="chip" aria-hidden="true"><i class="fa-solid fa-location-arrow"></i></span>
                                    <input type="text" class="form-control" id="landmark" name="landmark" placeholder="Nearby landmark" autocomplete="off">
                                </div>
                            </div>

                            <!-- Post -->
                            <div class="col-md-3">
                                <label class="form-label" for="post">Post Office</label>
                                <div class="underline-group">
                                    <span class="chip" aria-hidden="true"><i class="fa-regular fa-envelope"></i></span>
                                    <input type="text" class="form-control" id="post" name="post" placeholder="Post office" autocomplete="off">
                                </div>
                            </div>

                            <!-- Police station -->
                            <div class="col-md-3">
                                <label class="form-label" for="police_station">Police Station</label>
                                <div class="underline-group">
                                    <span class="chip" aria-hidden="true"><i class="fa-solid fa-shield-halved"></i></span>
                                    <input type="text" class="form-control" id="police_station" name="police_station" placeholder="Police station" autocomplete="off">
                                </div>
                            </div>

                            <!-- Pincode -->
                            <div class="col-md-3">
                                <label class="form-label" for="pincode">Pincode</label>
                                <div class="underline-group">
                                    <span class="chip" aria-hidden="true"><i class="fa-solid fa-map-pin"></i></span>
                                    <input type="text" class="form-control" id="pincode" name="pincode" inputmode="numeric" maxlength="6" pattern="\d{6}" placeholder="6-digit pincode">
                                </div>
                            </div>

                            <!-- District -->
                            <div class="col-md-3">
                                <label class="form-label" for="district">District</label>
                                <div class="underline-group">
                                    <span class="chip" aria-hidden="true"><i class="fa-solid fa-city"></i></span>
                                    <input type="text" class="form-control" id="district" name="district" placeholder="District" autocomplete="off">
                                </div>
                            </div>

                            <!-- State -->
                            <div class="col-md-3">
                                <label class="form-label" for="state">State</label>
                                <div class="underline-group">
                                    <span class="chip" aria-hidden="true"><i class="fa-regular fa-map"></i></span>
                                    <input type="text" class="form-control" id="state" name="state" placeholder="State" autocomplete="off">
                                </div>
                            </div>

                            <!-- Country -->
                            <div class="col-md-3">
                                <label class="form-label" for="country">Country</label>
                                <div class="underline-group">
                                    <span class="chip" aria-hidden="true"><i class="fa-solid fa-globe"></i></span>
                                    <input type="text" class="form-control" id="country" name="country" placeholder="Country" autocomplete="off">
                                </div>
                            </div>

                            <!-- Address text -->
                            <div class="col-12">
                                <label class="form-label" for="address">Address</label>
                                <div class="underline-group">
                                    <span class="chip" aria-hidden="true"><i class="fa-regular fa-address-card"></i></span>
                                    <textarea class="form-control" id="address" name="address" rows="2" placeholder="Street, area, house no."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <!-- Permanent Address toggle -->
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="same_as_permanent_address" name="same_as_permanent_address">
                                    <label class="form-check-label" for="same_as_permanent_address">
                                        This address is <strong>not</strong> the same as the permanent address
                                    </label>
                                </div>
                            </div>

                            <!-- Permanent Address (hidden by default) -->
                            <div class="col-12" id="permanent-address-section" style="display:none;">
                                <div class="mt-2 mb-2">
                                    <div class="section-title">Permanent Address</div>
                                    <div class="section-hint">Provide the member’s permanent residence details.</div>
                                </div>

                                <div class="row g-3">
                                    <!-- Permanent Sahi -->
                                    <div class="col-md-3">
                                        <label class="form-label" for="per_sahi">Permanent Sahi</label>
                                        <div class="underline-group">
                                            <span class="chip" aria-hidden="true"><i class="fa-solid fa-location-crosshairs"></i></span>
                                            <select class="form-select" id="per_sahi" name="per_sahi">
                                                <option value="">Select Sahi</option>
                                                @foreach ($sahiList as $sahi)
                                                    <option value="{{ $sahi->sahi_name }}">{{ $sahi->sahi_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Permanent Landmark -->
                                    <div class="col-md-3">
                                        <label class="form-label" for="per_landmark">Permanent Landmark</label>
                                        <div class="underline-group">
                                            <span class="chip" aria-hidden="true"><i class="fa-solid fa-location-arrow"></i></span>
                                            <input type="text" class="form-control" id="per_landmark" name="per_landmark" placeholder="Nearby landmark" autocomplete="off">
                                        </div>
                                    </div>

                                    <!-- Permanent Post -->
                                    <div class="col-md-3">
                                        <label class="form-label" for="per_post">Permanent Post Office</label>
                                        <div class="underline-group">
                                            <span class="chip" aria-hidden="true"><i class="fa-regular fa-envelope"></i></span>
                                            <input type="text" class="form-control" id="per_post" name="per_post" placeholder="Post office" autocomplete="off">
                                        </div>
                                    </div>

                                    <!-- Permanent Police Station -->
                                    <div class="col-md-3">
                                        <label class="form-label" for="per_police_station">Permanent Police Station</label>
                                        <div class="underline-group">
                                            <span class="chip" aria-hidden="true"><i class="fa-solid fa-shield-halved"></i></span>
                                            <input type="text" class="form-control" id="per_police_station" name="per_police_station" placeholder="Police station" autocomplete="off">
                                        </div>
                                    </div>

                                    <!-- Permanent Pincode -->
                                    <div class="col-md-3">
                                        <label class="form-label" for="per_pincode">Permanent Pincode</label>
                                        <div class="underline-group">
                                            <span class="chip" aria-hidden="true"><i class="fa-solid fa-map-pin"></i></span>
                                            <input type="text" class="form-control" id="per_pincode" name="per_pincode" inputmode="numeric" maxlength="6" pattern="\d{6}" placeholder="6-digit pincode">
                                        </div>
                                    </div>

                                    <!-- Permanent District -->
                                    <div class="col-md-3">
                                        <label class="form-label" for="per_district">Permanent District</label>
                                        <div class="underline-group">
                                            <span class="chip" aria-hidden="true"><i class="fa-solid fa-city"></i></span>
                                            <input type="text" class="form-control" id="per_district" name="per_district" placeholder="District" autocomplete="off">
                                        </div>
                                    </div>

                                    <!-- Permanent State -->
                                    <div class="col-md-3">
                                        <label class="form-label" for="per_state">Permanent State</label>
                                        <div class="underline-group">
                                            <span class="chip" aria-hidden="true"><i class="fa-regular fa-map"></i></span>
                                            <input type="text" class="form-control" id="per_state" name="per_state" placeholder="State" autocomplete="off">
                                        </div>
                                    </div>

                                    <!-- Permanent Country -->
                                    <div class="col-md-3">
                                        <label class="form-label" for="per_country">Permanent Country</label>
                                        <div class="underline-group">
                                            <span class="chip" aria-hidden="true"><i class="fa-solid fa-globe"></i></span>
                                            <input type="text" class="form-control" id="per_country" name="per_country" placeholder="Country" autocomplete="off">
                                        </div>
                                    </div>

                                    <!-- Permanent Address -->
                                    <div class="col-12">
                                        <label class="form-label" for="per_address">Permanent Address</label>
                                        <div class="underline-group">
                                            <span class="chip" aria-hidden="true"><i class="fa-regular fa-address-card"></i></span>
                                            <textarea class="form-control" id="per_address" name="per_address" rows="2" placeholder="Street, area, house no."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-lg px-5 btn-brand">
                                <i class="fa-regular fa-floppy-disk me-2"></i>Submit
                            </button>
                        </div>
                    </div>

                    <!-- Other placeholders to keep the tab structure uniform -->
                    <div class="tab-pane fade" id="pane-occupation" role="tabpanel" aria-labelledby="tab-occupation">
                        <div class="text-muted">Occupation section is available on the Occupation tab.</div>
                    </div>
                    <div class="tab-pane fade" id="pane-seba" role="tabpanel" aria-labelledby="tab-seba">
                        <div class="text-muted">Seba section is available on the Seba tab.</div>
                    </div>
                    <div class="tab-pane fade" id="pane-social" role="tabpanel" aria-labelledby="tab-social">
                        <div class="text-muted">Social section is available on the Social tab.</div>
                    </div>
                </div> <!-- /tab-content -->
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

    <!-- Bootstrap 5.3 bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    (function(){
        // Toggle permanent address visibility (checkbox indicates NOT same, so show when checked)
        const toggle = document.getElementById('same_as_permanent_address');
        const section = document.getElementById('permanent-address-section');

        function updatePermanentVisibility(){
            section.style.display = toggle?.checked ? 'block' : 'none';
        }
        if(toggle){
            toggle.addEventListener('change', updatePermanentVisibility);
            updatePermanentVisibility(); // init on load
        }
    })();
    </script>
@endsection
