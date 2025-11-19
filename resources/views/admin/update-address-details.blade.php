@extends('layouts.app')

@section('styles')
    <!-- Single Bootstrap + Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root{
            --brand-a:#7c3aed; /* violet */
            --brand-b:#06b6d4; /* cyan   */
            --accent:#f5c12e;  /* amber  */
            --ink:#0b1220;
            --muted:#64748b;
            --border:rgba(2,6,23,.10);
            --soft:#f8fafc;
        }

        .card{ border:1px solid var(--border); border-radius:14px; box-shadow:0 8px 22px rgba(2,6,23,.06); }
        .card-header{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff; font-weight:800; letter-spacing:.3px; text-transform:uppercase;
            border-radius:14px 14px 0 0;
        }

        /* Top Nav (acts like section tabs) */
        .tabbar{ background:#fff; border-radius:12px; padding:.4rem; box-shadow:0 6px 18px rgba(2,6,23,.06); }
        .tabbar .nav-link{
            border:1px solid transparent; background:var(--soft); color:var(--muted);
            border-radius:10px; font-weight:700; margin:.2rem; padding:.6rem .9rem;
            display:flex; align-items:center; gap:.5rem; white-space:nowrap; transition:all .18s ease;
        }
        .tabbar .nav-link:hover{ background:#eef2ff; color:var(--ink); transform:translateY(-1px); border-color:rgba(124,58,237,.25); }
        .tabbar .nav-link.active{
            color:#fff !important; background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            border-color:transparent; box-shadow:0 10px 18px rgba(124,58,237,.22);
        }

        /* Inputs with icons */
        label{ font-weight:600; color:#1f2937 }
        .input-group-text{
            background:#fff; border-right:0;
            border-top-left-radius:10px; border-bottom-left-radius:10px;
        }
        .input-group .form-control, .input-group .form-select{
            border-left:0; border-top-right-radius:10px; border-bottom-right-radius:10px;
        }

        /* Buttons */
        .btn-brand{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            border:0; color:#fff; font-weight:800; border-radius:10px;
            box-shadow:0 12px 24px rgba(124,58,237,.22);
        }
        .btn-brand:hover{ opacity:.96 }
        .btn-amber{ background-color:var(--accent); color:#1f2937; border:0; }
        .btn-amber:hover{ filter:brightness(.95); }

        .help{ font-size:.825rem; color:var(--muted); }

        @media (max-width: 768px){
            .tabbar{ overflow-x:auto; white-space:nowrap; }
        }
    </style>
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="row">
    <div class="col-12 mt-3">
        <div class="card shadow-lg">
            <div class="card-header py-3 text-center">
                <a href="{{ route('admin.pratihariManageProfile')}}" class="btn btn-danger">
                            </i> Back
                        </a>
                <i class="fa-solid fa-location-dot me-2"></i> Address Details

            </div>
    
            <!-- Section Nav (routes where available; Address marked active) -->
            <div class="px-3 pt-3">
                <ul class="nav tabbar flex-nowrap" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.pratihariProfile') }}">
                            <i class="fas fa-user"></i> Profile
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.pratihariFamily') }}">
                            <i class="fas fa-users"></i> Family
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.pratihariIdcard') }}">
                            <i class="fas fa-id-card"></i> ID Card
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.pratihariAddress') }}">
                            <i class="fas fa-map-marker-alt"></i> Address
                        </a>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-briefcase"></i> Occupation</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-cogs"></i> Seba</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-share-alt"></i> Social Media</a></li>
                </ul>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.pratihari-address.update', $pratihariAddress->pratihari_id) }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="pratihari_id" value="{{ $pratihariAddress->pratihari_id }}">

                    <h5 class="mb-3"><i class="fa-solid fa-map-pin me-2 text-success"></i>Current Address</h5>

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="sahi">Sahi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-map-marker-alt" style="color:var(--accent)"></i></span>
                                <select class="form-select" id="sahi" name="sahi">
                                    <option value="">Select Sahi</option>
                                    @foreach ($sahiList as $sahi)
                                        <option value="{{ $sahi->id }}" {{ (old('sahi', $pratihariAddress->sahi) == $sahi->id) ? 'selected' : '' }}>
                                            {{ $sahi->sahi_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="help">Choose your street/locality (Sahi).</div>
                        </div>

                        <div class="col-md-3">
                            <label for="landmark">Landmark</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-location-arrow" style="color:var(--accent)"></i></span>
                                <input type="text" class="form-control" id="landmark" name="landmark"
                                       value="{{ old('landmark', $pratihariAddress->landmark) }}"
                                       placeholder="Near temple, school...">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="post">Post Office</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-envelope" style="color:var(--accent)"></i></span>
                                <input type="text" class="form-control" id="post" name="post"
                                       value="{{ old('post', $pratihariAddress->post) }}"
                                       placeholder="Post office">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="police_station">Police Station</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-shield-alt" style="color:var(--accent)"></i></span>
                                <input type="text" class="form-control" id="police_station" name="police_station"
                                       value="{{ old('police_station', $pratihariAddress->police_station) }}"
                                       placeholder="Police station">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="pincode">Pincode</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-map-pin" style="color:var(--accent)"></i></span>
                                <input type="text" class="form-control" id="pincode" name="pincode"
                                       value="{{ old('pincode', $pratihariAddress->pincode) }}"
                                       inputmode="numeric" pattern="[0-9]{6}" maxlength="6" placeholder="6-digit pincode">
                            </div>
                            <div class="help">Enter a 6-digit pincode.</div>
                        </div>

                        <div class="col-md-3">
                            <label for="district">District</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-city" style="color:var(--accent)"></i></span>
                                <input type="text" class="form-control" id="district" name="district"
                                       value="{{ old('district', $pratihariAddress->district) }}"
                                       placeholder="District">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="state">State</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-map" style="color:var(--accent)"></i></span>
                                <input type="text" class="form-control" id="state" name="state"
                                       value="{{ old('state', $pratihariAddress->state) }}"
                                       placeholder="State">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="country">Country</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-globe" style="color:var(--accent)"></i></span>
                                <input type="text" class="form-control" id="country" name="country"
                                       value="{{ old('country', $pratihariAddress->country) }}"
                                       placeholder="Country">
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="address">Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-address-card" style="color:var(--accent)"></i></span>
                                <textarea class="form-control" id="address" name="address" rows="3" placeholder="House/Street/Area">{{ old('address', $pratihariAddress->address) }}</textarea>
                            </div>
                        </div>

                        <!-- Toggle for permanent address -->
                        <div class="col-12 pt-2">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="differentAsPermanent" name="differentAsPermanent"
                                       {{ old('differentAsPermanent') ? 'checked' : '' }}>
                                <label class="form-check-label" for="differentAsPermanent">
                                    This address is <strong>not</strong> the same as the permanent address
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Permanent Address -->
                    <div id="permanent-address-section" class="mt-4" style="display:none;">
                        <h5 class="mb-3"><i class="fa-solid fa-house me-2 text-info"></i>Permanent Address</h5>

                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="per_sahi">Permanent Sahi</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-map-marker-alt" style="color:var(--accent)"></i></span>
                                    <select class="form-select" id="per_sahi" name="per_sahi">
                                        <option value="">Select Sahi</option>
                                        @foreach ($sahiList as $sahi)
                                            <option value="{{ $sahi->id }}" {{ (old('per_sahi', $pratihariAddress->per_sahi) == $sahi->id) ? 'selected' : '' }}>
                                                {{ $sahi->sahi_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="per_landmark">Permanent Landmark</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-location-arrow" style="color:var(--accent)"></i></span>
                                    <input type="text" class="form-control" id="per_landmark" name="per_landmark"
                                           value="{{ old('per_landmark', $pratihariAddress->per_landmark) }}"
                                           placeholder="Landmark">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="per_post">Permanent Post Office</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-envelope" style="color:var(--accent)"></i></span>
                                    <input type="text" class="form-control" id="per_post" name="per_post"
                                           value="{{ old('per_post', $pratihariAddress->per_post) }}"
                                           placeholder="Post office">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="per_police_station">Permanent Police Station</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-shield-alt" style="color:var(--accent)"></i></span>
                                    <input type="text" class="form-control" id="per_police_station" name="per_police_station"
                                           value="{{ old('per_police_station', $pratihariAddress->per_police_station) }}"
                                           placeholder="Police station">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="per_pincode">Permanent Pincode</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-map-pin" style="color:var(--accent)"></i></span>
                                    <input type="text" class="form-control" id="per_pincode" name="per_pincode"
                                           value="{{ old('per_pincode', $pratihariAddress->per_pincode) }}"
                                           inputmode="numeric" pattern="[0-9]{6}" maxlength="6" placeholder="6-digit pincode">
                                </div>
                                <div class="help">Enter a 6-digit pincode.</div>
                            </div>

                            <div class="col-md-3">
                                <label for="per_district">Permanent District</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-city" style="color:var(--accent)"></i></span>
                                    <input type="text" class="form-control" id="per_district" name="per_district"
                                           value="{{ old('per_district', $pratihariAddress->per_district) }}"
                                           placeholder="District">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="per_state">Permanent State</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-map" style="color:var(--accent)"></i></span>
                                    <input type="text" class="form-control" id="per_state" name="per_state"
                                           value="{{ old('per_state', $pratihariAddress->per_state) }}"
                                           placeholder="State">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="per_country">Permanent Country</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-globe" style="color:var(--accent)"></i></span>
                                    <input type="text" class="form-control" id="per_country" name="per_country"
                                           value="{{ old('per_country', $pratihariAddress->per_country) }}"
                                           placeholder="Country">
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="per_address">Permanent Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-address-card" style="color:var(--accent)"></i></span>
                                    <textarea class="form-control" id="per_address" name="per_address" rows="3" placeholder="House/Street/Area">{{ old('per_address', $pratihariAddress->per_address) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-brand w-50">
                            <i class="fa fa-save me-1"></i> Update
                        </button>
                    </div>
                     

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- SweetAlert (optional flash) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
    <script>Swal.fire({icon:'success',title:'Success!',text:@json(session('success')),confirmButtonColor:'#0ea5e9'});</script>
    @endif
    @if (session('error'))
    <script>Swal.fire({icon:'error',title:'Error!',text:@json(session('error')),confirmButtonColor:'#ef4444'});</script>
    @endif

    <!-- Single Bootstrap bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Show/hide permanent address based on checkbox (preserve old() state)
        const toggle = document.getElementById('differentAsPermanent');
        const section = document.getElementById('permanent-address-section');
        function syncPermanentVisibility(){ section.style.display = toggle.checked ? 'block' : 'none'; }
        if (toggle && section){
            // initialize based on previous value
            syncPermanentVisibility();
            toggle.addEventListener('change', syncPermanentVisibility);
        }

        // Client-side pincode quick clean (optional)
        function clampDigits(el){
            el.value = el.value.replace(/\D/g,'').slice(0,6);
        }
        const pin1 = document.getElementById('pincode');
        const pin2 = document.getElementById('per_pincode');
        if (pin1) pin1.addEventListener('input', ()=>clampDigits(pin1));
        if (pin2) pin2.addEventListener('input', ()=>clampDigits(pin2));
    </script>
@endsection
