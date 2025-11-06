@extends('layouts.app')

@section('styles')
    <!-- Bootstrap 5 + Font Awesome 6 (match other pages) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root{
            /* Brand palette (same across pages) */
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

        /* Card & sections */
        .card{border:1px solid var(--border);border-radius:1rem;}
        .section-title{font-weight:800;color:var(--ink);}
        .section-hint{color:var(--muted);font-size:.9rem;}
        .divider{height:1px;background:var(--border);margin:1rem 0;}

        /* Underline inputs (avoid Bootstrap .input-group) */
        .underline-group{
            display:flex;align-items:center;gap:.6rem;border-bottom:2px solid var(--border);
            padding-bottom:.25rem;background:transparent;transition:border-color .2s ease,box-shadow .2s ease;
        }
        .underline-group:focus-within{border-bottom-color:var(--brand-b);box-shadow:0 6px 0 -5px var(--ring);}
        .form-label{font-weight:600;margin-bottom:.35rem;}
        .form-control,.form-select{
            border:0!important;border-radius:0!important;background:transparent!important;
            padding:.45rem 0 .25rem 0;height:auto;box-shadow:none!important;color:var(--ink);
        }
        .form-control::placeholder{color:#9aa4b2;}
        .form-select{padding-right:1.6rem;background-clip:padding-box;}
        .form-control:focus,.form-select:focus{outline:none;}

        /* Brand chips */
        .chip{
            display:inline-flex;align-items:center;justify-content:center;
            width:40px;min-width:40px;height:40px;border-radius:10px;
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff;flex:0 0 40px;
            box-shadow:0 6px 16px rgba(2,6,23,.12);
        }
        .chip i{font-size:1rem;line-height:1;color:#fff !important;}

        /* Buttons */
        .btn-brand{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            border:0;color:#fff;box-shadow:0 14px 30px rgba(124,58,237,.25);
        }
        .btn-brand:hover{opacity:.96;}
        .btn-brand:disabled{opacity:.6;box-shadow:none;cursor:not-allowed;}

        /* Layout */
        .row.g-3{--bs-gutter-x:1rem;--bs-gutter-y:1rem;}
        .tab-pane{padding:1rem .25rem;}

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
<div class="container-fluid my-3">
    <!-- Header -->
    <div class="page-header mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <div class="title h4 mb-0">Pratihari • Occupation</div>
                <div class="small opacity-75">Same design and colors as the other sections.</div>
            </div>
        </div>
    </div>

    <!-- Tabs (button tabs; Occupation active) -->
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
                <button class="nav-link" id="tab-address" data-bs-toggle="tab" data-bs-target="#pane-address" type="button" role="tab" aria-controls="pane-address" aria-selected="false">
                    <i class="fa-solid fa-location-dot"></i> Address
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link active" id="tab-occupation" data-bs-toggle="tab" data-bs-target="#pane-occupation" type="button" role="tab" aria-controls="pane-occupation" aria-selected="true">
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
            <form action="{{ route('admin.pratihari-occupation.store') }}" method="POST" novalidate>
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
                        <div class="text-muted">ID Card section is managed on the ID tab.</div>
                    </div>

                    <!-- ADDRESS (placeholder) -->
                    <div class="tab-pane fade" id="pane-address" role="tabpanel" aria-labelledby="tab-address">
                        <div class="text-muted">Address section is managed on the Address tab.</div>
                    </div>

                    <!-- OCCUPATION (active) -->
                    <div class="tab-pane fade show active" id="pane-occupation" role="tabpanel" aria-labelledby="tab-occupation">
                        <div class="mb-2">
                            <div class="section-title">Occupation & Activities</div>
                            <div class="section-hint">Add the member’s occupation and any extra curriculum activities.</div>
                        </div>

                        <div class="row g-3">
                            <!-- Occupation -->
                            <div class="col-md-6">
                                <label class="form-label" for="occupation">Occupation</label>
                                <div class="underline-group">
                                    <span class="chip" aria-hidden="true"><i class="fa-solid fa-briefcase"></i></span>
                                    <input type="text" name="occupation" id="occupation" class="form-control" placeholder="e.g., Teacher, Carpenter, Student" autocomplete="off">
                                </div>
                            </div>

                            <!-- Extra Curriculum Activity (dynamic list) -->
                            <div class="col-md-6">
                                <label class="form-label">Extra Curriculum Activity</label>
                                <div id="extraActivityContainer">
                                    <div class="underline-group mb-2">
                                        <span class="chip" aria-hidden="true"><i class="fa-solid fa-person-running"></i></span>
                                        <input type="text" name="extra_activity[]" class="form-control" placeholder="e.g., Sports coaching" autocomplete="off">
                                        <button type="button" class="btn btn-brand ms-2 addMore">
                                            <i class="fa fa-plus me-1"></i>
                                        </button>
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

                    <!-- Other placeholders to keep tab structure uniform -->
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
    // Dynamic add/remove for Extra Curriculum Activities (no jQuery)
    (function(){
        const container = document.getElementById('extraActivityContainer');

        container.addEventListener('click', function(e){
            if(e.target.closest('.addMore')){
                const row = document.createElement('div');
                row.className = 'underline-group mb-2';
                row.innerHTML = `
                    <span class="chip" aria-hidden="true"><i class="fa-solid fa-person-running"></i></span>
                    <input type="text" name="extra_activity[]" class="form-control" placeholder="e.g., Event volunteering" autocomplete="off">
                    <button type="button" class="btn btn-outline-danger ms-2 remove">
                        <i class="fa fa-trash"></i>
                    </button>
                `;
                container.appendChild(row);
            }

            if(e.target.closest('.remove')){
                e.target.closest('.underline-group').remove();
            }
        });
    })();
    </script>
@endsection
