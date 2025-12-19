@extends('layouts.app')

@section('styles')
    <!-- Bootstrap 5 + Font Awesome 6 (match profile page) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root{
            --brand-a:#7c3aed;
            --brand-b:#06b6d4;
            --brand-c:#22c55e;
            --ink:#0b1220;
            --muted:#64748b;
            --border:rgba(2,6,23,.10);
            --ring:rgba(6,182,212,.28);
        }

        .page-header{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff;border-radius:1rem;padding:1.05rem 1.25rem;
            box-shadow:0 10px 24px rgba(6,182,212,.18);
        }
        .page-header .title{font-weight:800;letter-spacing:.3px;}

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

        .card{border:1px solid var(--border);border-radius:1rem;}
        .section-title{font-weight:800;color:var(--ink);}
        .section-hint{color:var(--muted);font-size:.9rem;}
        .divider{height:1px;background:var(--border);margin:1rem 0;}

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

        .chip{
            display:inline-flex;align-items:center;justify-content:center;
            width:40px;min-width:40px;height:40px;border-radius:10px;
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff;flex:0 0 40px;
            box-shadow:0 6px 16px rgba(2,6,23,.12);
        }
        .chip i{font-size:1rem;line-height:1;color:#fff !important;}

        .row.g-3{--bs-gutter-x:1rem;--bs-gutter-y:1rem;}
        .tab-pane{padding:1rem .25rem;}

        .btn-brand{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            border:0;color:#fff;box-shadow:0 14px 30px rgba(124,58,237,.25);
        }
        .btn-brand:hover{opacity:.96;}
        .btn-brand:disabled{opacity:.6;box-shadow:none;cursor:not-allowed;}

        .preview-image{transition:transform .25s ease, box-shadow .25s ease;border-radius:.5rem;}
        .preview-image:hover{transform:scale(2);box-shadow:0 8px 16px rgba(0,0,0,.3);}

        :focus-visible{outline:2px solid transparent;box-shadow:0 0 0 3px var(--ring) !important;border-radius:10px;}

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
    <div class="page-header mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <div class="title h4 mb-0">Pratihari • Family Details</div>
                <div class="small opacity-75">Same look & feel as your profile form. Add parents, spouse, and children.</div>
            </div>
        </div>
    </div>

    <div class="tabbar mb-3">
        <ul class="nav" id="profileTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link" id="tab-profile" data-bs-toggle="tab" data-bs-target="#pane-profile" type="button" role="tab">
                    <i class="fa-solid fa-user"></i> Profile
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link active" id="tab-family" data-bs-toggle="tab" data-bs-target="#pane-family" type="button" role="tab">
                    <i class="fa-solid fa-users"></i> Family
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-id" data-bs-toggle="tab" data-bs-target="#pane-id" type="button" role="tab">
                    <i class="fa-solid fa-id-card"></i> ID Card
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-address" data-bs-toggle="tab" data-bs-target="#pane-address" type="button" role="tab">
                    <i class="fa-solid fa-location-dot"></i> Address
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-occupation" data-bs-toggle="tab" data-bs-target="#pane-occupation" type="button" role="tab">
                    <i class="fa-solid fa-briefcase"></i> Occupation
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-seba" data-bs-toggle="tab" data-bs-target="#pane-seba" type="button" role="tab">
                    <i class="fa-solid fa-gears"></i> Seba
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-social" data-bs-toggle="tab" data-bs-target="#pane-social" type="button" role="tab">
                    <i class="fa-solid fa-share-nodes"></i> Social Media
                </button>
            </li>
        </ul>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <div class="fw-semibold mb-1">Please fix the following:</div>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="familyForm" action="{{ route('admin.pratihari-family.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <input type="hidden" name="pratihari_id" value="{{ request('pratihari_id') }}">

                <div class="tab-content" id="tabsContent">
                    <div class="tab-pane fade" id="pane-profile" role="tabpanel" aria-labelledby="tab-profile">
                        <div class="text-muted">Profile section is managed on the Profile page.</div>
                    </div>

                    <div class="tab-pane fade show active" id="pane-family" role="tabpanel" aria-labelledby="tab-family">
                        <div class="mb-2">
                            <div class="section-title">Parents</div>
                            <div class="section-hint">Choose existing records or add new ones with photos.</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-lg-6">
                                <div class="mb-2 fw-semibold text-uppercase small text-muted">Father Details</div>

                                <label class="form-label" for="father_name_select">Father Name</label>
                                <div class="underline-group">
                                    <span class="chip" aria-hidden="true"><i class="fa-solid fa-user"></i></span>
                                    <select class="form-select" id="father_name_select" name="father_id">
                                        <option value="">Select Father's Name</option>
                                        <option value="other" class="text-danger">If not listed, choose this</option>
                                        @foreach ($familyDetails as $family)
                                            <option value="{{ $family->id }}" data-photo="{{ asset($family->father_photo) }}">
                                                {{ $family->father_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mt-3" id="father_name_input_div" style="display:none;">
                                    <label class="form-label" for="father_name">Enter Father's Name</label>
                                    <div class="underline-group">
                                        <span class="chip" aria-hidden="true"><i class="fa-solid fa-pen-to-square"></i></span>
                                        <input type="text" class="form-control" id="father_name" name="father_name" placeholder="Enter Father's Name" autocomplete="off">
                                    </div>
                                </div>

                                <div class="mt-3" id="father_photo_preview_div" style="display:none;">
                                    <label class="form-label">Father Photo</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <img id="father_photo_preview" class="preview-image border" src="" alt="Father Photo" style="height: 90px; width: 90px; object-fit: cover;">
                                        <a id="father_photo_link" href="#" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <i class="fa-solid fa-up-right-from-square me-1"></i>Open
                                        </a>
                                    </div>
                                </div>

                                <div class="mt-3" id="father_photo_upload_div" style="display:none;">
                                    <label class="form-label">Upload Father Photo</label>
                                    <div class="underline-group">
                                        <span class="chip" aria-hidden="true"><i class="fa-solid fa-camera"></i></span>
                                        <input type="file" class="form-control" name="father_photo" accept="image/*">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-6">
                                <div class="mb-2 fw-semibold text-uppercase small text-muted">Mother Details</div>

                                <label class="form-label" for="mother_name_select">Mother Name</label>
                                <div class="underline-group">
                                    <span class="chip" aria-hidden="true"><i class="fa-solid fa-person-dress"></i></span>
                                    <select class="form-select" id="mother_name_select" name="mother_id">
                                        <option value="">Select Mother's Name</option>
                                        <option value="other" class="text-danger">If not listed, choose this</option>
                                        @foreach ($familyDetails as $family)
                                            <option value="{{ $family->id }}" data-photo="{{ asset($family->mother_photo) }}">
                                                {{ $family->mother_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mt-3" id="mother_name_input_div" style="display:none;">
                                    <label class="form-label" for="mother_name">Enter Mother's Name</label>
                                    <div class="underline-group">
                                        <span class="chip" aria-hidden="true"><i class="fa-solid fa-pen-to-square"></i></span>
                                        <input type="text" class="form-control" id="mother_name" name="mother_name" placeholder="Enter Mother's Name" autocomplete="off">
                                    </div>
                                </div>

                                <div class="mt-3" id="mother_photo_preview_div" style="display:none;">
                                    <label class="form-label">Mother Photo</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <img id="mother_photo_preview" class="preview-image border" src="" alt="Mother Photo" style="height: 90px; width: 90px; object-fit: cover;">
                                        <a id="mother_photo_link" href="#" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <i class="fa-solid fa-up-right-from-square me-1"></i>Open
                                        </a>
                                    </div>
                                </div>

                                <div class="mt-3" id="mother_photo_upload_div" style="display:none;">
                                    <label class="form-label">Upload Mother Photo</label>
                                    <div class="underline-group">
                                        <span class="chip" aria-hidden="true"><i class="fa-solid fa-camera"></i></span>
                                        <input type="file" class="form-control" name="mother_photo" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="mt-1 mb-2">
                            <div class="section-title">Marital Status & Spouse</div>
                            <div class="section-hint">Reveal spouse & children fields only when needed.</div>
                        </div>

                        <div class="row g-3 align-items-center">
                            <div class="col-12 col-md-6">
                                <div class="underline-group" role="group" aria-label="Marital Status">
                                    <span class="chip" aria-hidden="true"><i class="fa-regular fa-heart"></i></span>
                                    <div class="d-flex gap-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="marital_status" id="married" value="married">
                                            <label class="form-check-label" for="married">Married</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="marital_status" id="unmarried" value="unmarried">
                                            <label class="form-check-label" for="unmarried">Unmarried</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SPOUSE DETAILS (UPDATED: full name dropdown + manual) --}}
                        <div class="row g-3 mt-1" id="spouseDetails" style="display:none;">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="spouse_select">Spouse Name</label>
                                <div class="underline-group">
                                    <span class="chip" aria-hidden="true"><i class="fa-solid fa-user"></i></span>
                                    <select class="form-select" id="spouse_select" name="spouse_select">
                                        <option value="">Select Spouse Name</option>
                                        @foreach($pratiharis as $p)
                                            <option value="{{ $p->id }}">{{ $p->full_name }}</option>
                                        @endforeach
                                        <option value="manual" class="text-danger">Not in list (Manual)</option>
                                    </select>
                                </div>

                                <div class="mt-3" id="spouse_manual_div" style="display:none;">
                                    <label class="form-label" for="spouse_name_manual">Spouse Name (Manual)</label>
                                    <div class="underline-group">
                                        <span class="chip" aria-hidden="true"><i class="fa-solid fa-pen-to-square"></i></span>
                                        <input type="text" class="form-control" id="spouse_name_manual" name="spouse_name_manual" placeholder="Enter Spouse Name" autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="spouse_photo">Spouse Photo</label>
                                <div class="underline-group">
                                    <span class="chip" aria-hidden="true"><i class="fa-solid fa-camera"></i></span>
                                    <input type="file" class="form-control" id="spouse_photo" name="spouse_photo" accept="image/*">
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="spouse_father_name">Spouse Father's Name</label>
                                <div class="underline-group">
                                    <span class="chip" aria-hidden="true"><i class="fa-solid fa-user-tie"></i></span>
                                    <input type="text" class="form-control" id="spouse_father_name" name="spouse_father_name" placeholder="Enter Father's Name" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="spouse_mother_name">Spouse Mother's Name</label>
                                <div class="underline-group">
                                    <span class="chip" aria-hidden="true"><i class="fa-solid fa-person-dress"></i></span>
                                    <input type="text" class="form-control" id="spouse_mother_name" name="spouse_mother_name" placeholder="Enter Mother's Name" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Upload Spouse Father Photo</label>
                                <div class="underline-group">
                                    <span class="chip" aria-hidden="true"><i class="fa-solid fa-camera"></i></span>
                                    <input type="file" class="form-control" name="spouse_father_photo" accept="image/*">
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Upload Spouse Mother Photo</label>
                                <div class="underline-group">
                                    <span class="chip" aria-hidden="true"><i class="fa-solid fa-camera"></i></span>
                                    <input type="file" class="form-control" name="spouse_mother_photo" accept="image/*">
                                </div>
                            </div>
                        </div>

                        {{-- CHILDREN --}}
                        <div class="col-12 mt-3" id="childrenBlock" style="display:none;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="h6 mb-0">
                                    <i class="fas fa-child me-2" style="color:var(--brand-c)"></i>Children Details
                                </div>
                                <button type="button" class="btn btn-sm btn-success" id="addChild">
                                    <i class="fa fa-plus-circle me-1"></i>Add Child
                                </button>
                            </div>
                            <div id="childrenContainer" class="mt-2"></div>
                        </div>

                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-lg px-5 btn-brand">
                                <i class="fa-regular fa-floppy-disk me-2"></i>Submit
                            </button>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pane-id" role="tabpanel" aria-labelledby="tab-id">
                        <div class="text-muted">ID Card section is available on the ID Card page.</div>
                    </div>
                    <div class="tab-pane fade" id="pane-address" role="tabpanel" aria-labelledby="tab-address">
                        <div class="text-muted">Address section is available on the Address page.</div>
                    </div>
                    <div class="tab-pane fade" id="pane-occupation" role="tabpanel" aria-labelledby="tab-occupation">
                        <div class="text-muted">Occupation section is available on the Occupation page.</div>
                    </div>
                    <div class="tab-pane fade" id="pane-seba" role="tabpanel" aria-labelledby="tab-seba">
                        <div class="text-muted">Seba section is available on the Seba page.</div>
                    </div>
                    <div class="tab-pane fade" id="pane-social" role="tabpanel" aria-labelledby="tab-social">
                        <div class="text-muted">Social Media section is available on the Social page.</div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    (function(){
        // ---------- helper ----------
        function escapeHtml(str){
            return String(str ?? '')
                .replaceAll('&','&amp;')
                .replaceAll('<','&lt;')
                .replaceAll('>','&gt;')
                .replaceAll('"','&quot;')
                .replaceAll("'",'&#039;');
        }

        // Pratihari list for spouse dropdowns (main + child)
        // Controller should send: select('id','first_name','middle_name','last_name')
        const PRATIHARIS = @json($pratiharis ?? []);

        function buildFullName(p){
            return [p.first_name, p.middle_name, p.last_name]
                .filter(Boolean)
                .join(' ')
                .trim();
        }

        function buildSpouseOptionsHtml(){
            let h = `<option value="">Select Spouse Name</option>`;
            PRATIHARIS.forEach(p => {
                h += `<option value="${p.id}">${escapeHtml(buildFullName(p))}</option>`;
            });
            h += `<option value="manual" class="text-danger">Not in list (Manual)</option>`;
            return h;
        }
        const spouseOptionsHtml = buildSpouseOptionsHtml();

        // ---------- Father/Mother select handling ----------
        function handleFamilySelection(type){
            const select = document.getElementById(`${type}_name_select`);
            if(!select) return;

            const selected = select.value;
            const option   = select.options[select.selectedIndex];
            const photoUrl = option?.getAttribute('data-photo') || '';

            const inputDiv   = document.getElementById(`${type}_name_input_div`);
            const uploadDiv  = document.getElementById(`${type}_photo_upload_div`);
            const previewDiv = document.getElementById(`${type}_photo_preview_div`);
            const previewImg = document.getElementById(`${type}_photo_preview`);
            const previewLnk = document.getElementById(`${type}_photo_link`);

            if(selected === 'other'){
                inputDiv.style.display   = 'block';
                uploadDiv.style.display  = 'block';
                previewDiv.style.display = 'none';
                if(previewImg){ previewImg.src = ''; }
                if(previewLnk){ previewLnk.href = '#'; }
            }else if(selected){
                inputDiv.style.display   = 'none';
                uploadDiv.style.display  = 'none';
                previewDiv.style.display = 'block';
                if(previewImg){ previewImg.src = photoUrl; }
                if(previewLnk){ previewLnk.href = photoUrl || '#'; }
            }else{
                inputDiv.style.display   = 'none';
                uploadDiv.style.display  = 'none';
                previewDiv.style.display = 'none';
                if(previewImg){ previewImg.src = ''; }
                if(previewLnk){ previewLnk.href = '#'; }
            }
        }

        ['father','mother'].forEach(t=>{
            const el = document.getElementById(`${t}_name_select`);
            if(el){ el.addEventListener('change', ()=>handleFamilySelection(t)); }
        });

        const form      = document.getElementById('familyForm');
        const married   = document.getElementById('married');
        const unmarried = document.getElementById('unmarried');
        const spouse    = document.getElementById('spouseDetails');
        const kids      = document.getElementById('childrenBlock');

        const spouseSelectMain = document.getElementById('spouse_select');
        const spouseManualDiv  = document.getElementById('spouse_manual_div');
        const spouseManualInp  = document.getElementById('spouse_name_manual');

        const addBtn     = document.getElementById('addChild');
        const container  = document.getElementById('childrenContainer');

        function setChildrenRequired(isRequired){
            container.querySelectorAll('input[name$="[dob]"]').forEach(el => el.required = isRequired);
            container.querySelectorAll('input[name$="[photo]"]').forEach(el => el.required = isRequired);
        }

        function refreshMainSpouseManual(){
            if(!spouseSelectMain || !spouseManualDiv) return;
            if(spouseSelectMain.value === 'manual'){
                spouseManualDiv.style.display = 'block';
                spouseManualInp && (spouseManualInp.required = true);
            }else{
                spouseManualDiv.style.display = 'none';
                if(spouseManualInp){ spouseManualInp.value = ''; spouseManualInp.required = false; }
            }
        }

        if(spouseSelectMain){
            spouseSelectMain.addEventListener('change', refreshMainSpouseManual);
        }

        function refreshMaritalUI(){
            const isMarried = !!married?.checked;

            spouse.style.display = isMarried ? 'flex' : 'none';
            spouse.style.flexWrap = isMarried ? 'wrap' : '';
            kids.style.display   = isMarried ? 'block' : 'none';

            setChildrenRequired(isMarried);

            if(!isMarried){
                if(spouseSelectMain) spouseSelectMain.value = '';
                refreshMainSpouseManual();
            }else{
                refreshMainSpouseManual();
            }

            container.querySelectorAll('.child-row').forEach(row => toggleChildSpouse(row));
        }

        if(married)   married.addEventListener('change', refreshMaritalUI);
        if(unmarried) unmarried.addEventListener('change', refreshMaritalUI);

        function toggleChildSpouse(row){
            const ms = row.querySelector('.child-marital');
            const spouseWrap = row.querySelector('.child-spouse-wrap');
            const spouseSel  = row.querySelector('.child-spouse-select');
            const manualWrap = row.querySelector('.child-spouse-manual-wrap');
            const manualInp  = row.querySelector('.child-spouse-manual-input');

            if(!ms || !spouseWrap) return;

            const isChildMarried = (ms.value === 'married');
            spouseWrap.style.display = isChildMarried ? '' : 'none';

            if(!isChildMarried){
                if(spouseSel) spouseSel.value = '';
                if(manualInp){ manualInp.value = ''; manualInp.required = false; }
                if(manualWrap) manualWrap.style.display = 'none';
            }else{
                if(spouseSel) spouseSel.required = true;

                if(spouseSel && spouseSel.value === 'manual'){
                    if(manualWrap) manualWrap.style.display = '';
                    if(manualInp) manualInp.required = true;
                }else{
                    if(manualWrap) manualWrap.style.display = 'none';
                    if(manualInp){ manualInp.value=''; manualInp.required = false; }
                }
            }
        }

        function addChildRow(){
            const idx = container.querySelectorAll('.child-row').length + 1;

            const node = document.createElement('div');
            node.className = 'row g-3 child-row mt-2 p-3 rounded border';

            node.innerHTML = `
                <div class="col-12 col-md-4">
                    <label class="form-label">
                        <i class="fa fa-user me-1" style="color:var(--brand-a)"></i>Child Name
                    </label>
                    <div class="underline-group">
                        <span class="chip" aria-hidden="true"><i class="fa-solid fa-child"></i></span>
                        <input type="text" class="form-control" name="children[${idx}][name]" placeholder="Enter Child's Name" autocomplete="off">
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label">
                        <i class="fa fa-calendar me-1" style="color:var(--brand-a)"></i>Date of Birth <span class="text-danger">*</span>
                    </label>
                    <div class="underline-group">
                        <span class="chip" aria-hidden="true"><i class="fa-regular fa-calendar"></i></span>
                        <input type="date" class="form-control" name="children[${idx}][dob]" required>
                    </div>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label">
                        <i class="fa fa-venus-mars me-1" style="color:var(--brand-a)"></i>Gender
                    </label>
                    <div class="underline-group">
                        <span class="chip" aria-hidden="true"><i class="fa-solid fa-venus-mars"></i></span>
                        <select class="form-select" name="children[${idx}][gender]">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label">
                        <i class="fa-solid fa-heart me-1" style="color:var(--brand-a)"></i>Marital Status
                    </label>
                    <div class="underline-group">
                        <span class="chip" aria-hidden="true"><i class="fa-regular fa-heart"></i></span>
                        <select class="form-select child-marital" name="children[${idx}][marital_status]">
                            <option value="">Select</option>
                            <option value="single">Single</option>
                            <option value="married">Married</option>
                            <option value="divorced">Divorced</option>
                            <option value="widowed">Widowed</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-3 child-spouse-wrap" style="display:none;">
                    <label class="form-label">
                        <i class="fa-solid fa-user me-1" style="color:var(--brand-a)"></i>Spouse (Select)
                    </label>
                    <div class="underline-group">
                        <span class="chip" aria-hidden="true"><i class="fa-solid fa-user"></i></span>
                        <select class="form-select child-spouse-select" name="children[${idx}][spouse_select]">
                            ${spouseOptionsHtml}
                        </select>
                    </div>

                    <div class="mt-3 child-spouse-manual-wrap" style="display:none;">
                        <label class="form-label">Spouse Name (Manual)</label>
                        <div class="underline-group">
                            <span class="chip" aria-hidden="true"><i class="fa-solid fa-pen-to-square"></i></span>
                            <input type="text" class="form-control child-spouse-manual-input" name="children[${idx}][spouse_name_manual]" placeholder="Enter spouse name">
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label">
                        <i class="fa fa-camera me-1" style="color:var(--brand-a)"></i>Photo <span class="text-danger">*</span>
                    </label>
                    <div class="underline-group">
                        <span class="chip" aria-hidden="true"><i class="fa-solid fa-camera"></i></span>
                        <input type="file" class="form-control" name="children[${idx}][photo]" accept="image/*" required>
                    </div>
                </div>

                <div class="col-12 col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger w-100 removeChild">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            `;

            container.appendChild(node);

            const ms = node.querySelector('.child-marital');
            const spSel = node.querySelector('.child-spouse-select');

            ms && ms.addEventListener('change', () => toggleChildSpouse(node));
            spSel && spSel.addEventListener('change', () => toggleChildSpouse(node));

            node.querySelector('.removeChild')?.addEventListener('click', ()=>{
                node.remove();
                refreshMaritalUI();
            });

            refreshMaritalUI();
        }

        if(addBtn){ addBtn.addEventListener('click', addChildRow); }

        if(form){
            form.addEventListener('submit', function(e){
                const isMarried = !!married?.checked;
                if(!isMarried) return;

                const rows = container.querySelectorAll('.child-row');
                let invalid = false;

                rows.forEach((row) => {
                    const dobInput   = row.querySelector('input[name$="[dob]"]');
                    const photoInput = row.querySelector('input[name$="[photo]"]');

                    const dobOk   = dobInput && dobInput.value;
                    const photoOk = photoInput && photoInput.files && photoInput.files.length > 0;

                    if(!dobOk || !photoOk){
                        invalid = true;
                    }
                });

                if(invalid){
                    e.preventDefault();
                    Swal.fire({
                        icon:'error',
                        title:'Required Fields Missing',
                        text:'For each child, Date of Birth and Photo are required.',
                        confirmButtonColor:'#ef4444'
                    });
                }
            });
        }

        refreshMaritalUI();
    })();
    </script>
@endsection
