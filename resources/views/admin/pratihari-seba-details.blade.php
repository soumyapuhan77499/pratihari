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

        /* Checkbox grid */
        .checkbox-grid{
            display:grid;
            grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
            gap:.6rem;
            background:#f8fafc;
            border:1px solid var(--border);
            border-radius:12px;
            padding:.8rem;
        }
        .form-check .form-check-input{cursor:pointer;transform:scale(1.1);margin-right:.4rem;}
        .form-check-label{cursor:pointer;}

        /* Bheddha group */
        .beddha-group{
            border:1px solid var(--border);
            background:#fff;
            border-radius:12px;
            padding: .85rem;
        }
        .beddha-group .title{
            font-weight:700;color:var(--ink);
        }
        .beddha-pills{
            display:flex;flex-wrap:wrap;gap:.6rem;margin-top:.6rem;
        }
        .beddha-pill{
            display:flex;align-items:center;gap:.35rem;
            border:1px solid var(--border);border-radius:999px;padding:.25rem .6rem;background:#f9fafb;
        }
        .beddha-disabled{opacity:.55;}
        .beddha-disabled .form-check-input{cursor:not-allowed;}

        /* Buttons */
        .btn-brand{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            border:0;color:#fff;box-shadow:0 14px 30px rgba(124,58,237,.25);
        }
        .btn-brand:hover{opacity:.96;}
        .btn-brand:disabled{opacity:.6;box-shadow:none;cursor:not-allowed;}

        /* Accessibility focus */
        :focus-visible{outline:2px solid transparent;box-shadow:0 0 0 3px var(--ring) !important;border-radius:10px;}

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
                <div class="title h4 mb-0">Pratihari • Seba</div>
                <div class="small opacity-75">Choose Seba types; Bheddha options appear per selection.</div>
            </div>
        </div>
    </div>

    <!-- Tabs (Seba active) -->
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
                <button class="nav-link" id="tab-occupation" data-bs-toggle="tab" data-bs-target="#pane-occupation" type="button" role="tab" aria-controls="pane-occupation" aria-selected="false">
                    <i class="fa-solid fa-briefcase"></i> Occupation
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link active" id="tab-seba" data-bs-toggle="tab" data-bs-target="#pane-seba" type="button" role="tab" aria-controls="pane-seba" aria-selected="true">
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
            <form action="{{ route('admin.pratihari-seba.store') }}" method="POST" novalidate>
                @csrf
                <input type="hidden" name="pratihari_id" value="{{ request('pratihari_id') }}">

                <div class="tab-content" id="tabsContent">
                    <!-- PLACEHOLDERS to keep tab structure uniform -->
                    <div class="tab-pane fade" id="pane-profile" role="tabpanel" aria-labelledby="tab-profile">
                        <div class="text-muted">Profile section is managed on the Profile tab.</div>
                    </div>
                    <div class="tab-pane fade" id="pane-family" role="tabpanel" aria-labelledby="tab-family">
                        <div class="text-muted">Family section is managed on the Family tab.</div>
                    </div>
                    <div class="tab-pane fade" id="pane-id" role="tabpanel" aria-labelledby="tab-id">
                        <div class="text-muted">ID Card section is managed on the ID Card tab.</div>
                    </div>
                    <div class="tab-pane fade" id="pane-address" role="tabpanel" aria-labelledby="tab-address">
                        <div class="text-muted">Address section is managed on the Address tab.</div>
                    </div>
                    <div class="tab-pane fade" id="pane-occupation" role="tabpanel" aria-labelledby="tab-occupation">
                        <div class="text-muted">Occupation section is managed on the Occupation tab.</div>
                    </div>
                    <div class="tab-pane fade" id="pane-social" role="tabpanel" aria-labelledby="tab-social">
                        <div class="text-muted">Social section is available on the Social tab.</div>
                    </div>

                    <!-- SEBA (active) -->
                    <div class="tab-pane fade show active" id="pane-seba" role="tabpanel" aria-labelledby="tab-seba">
                        <div class="mb-2">
                            <div class="section-title">Seba Type</div>
                            <div class="section-hint">Select one or more Seba categories. Related Bheddha options will appear below.</div>
                        </div>

                        <!-- Seba checkbox grid -->
                        <div id="seba_list" class="checkbox-grid">
                            @foreach ($sebas as $seba)
                                <div class="form-check d-flex align-items-center">
                                    <input class="form-check-input seba-checkbox" type="checkbox"
                                           name="seba_id[]" value="{{ $seba->id }}"
                                           id="seba_{{ $seba->id }}" data-seba-id="{{ $seba->id }}">
                                    <label class="form-check-label ms-2" for="seba_{{ $seba->id }}">
                                        {{ $seba->seba_name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Bheddha Section -->
                        <div class="mt-4" id="beddha_section" style="display:none;">
                            <div class="section-title mb-2">Bheddha List</div>
                            <div id="beddha_list" class="d-flex flex-column gap-2"></div>
                        </div>

                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-lg px-5 btn-brand">
                                <i class="fa-regular fa-floppy-disk me-2"></i>Submit
                            </button>
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
        const beddhaListWrap = document.getElementById('beddha_list');
        const beddhaSection  = document.getElementById('beddha_section');

        function updateBeddhaSectionVisibility(){
            const anyChecked = Array.from(document.querySelectorAll('.seba-checkbox')).some(cb => cb.checked);
            beddhaSection.style.display = anyChecked ? 'block' : 'none';
            if(!anyChecked){ beddhaListWrap.innerHTML = ''; }
        }

        // Render a single Bheddha group for one Seba
        function renderBeddhaGroup(sebaId, sebaName, items){
            const groupId = `beddha_group_${sebaId}`;
            // If already exists, replace it
            const existing = document.getElementById(groupId);
            if(existing) existing.remove();

            const group = document.createElement('div');
            group.className = 'beddha-group';
            group.id = groupId;

            const inner = document.createElement('div');
            inner.innerHTML = `<div class="title">${sebaName}</div>`;
            const pills = document.createElement('div');
            pills.className = 'beddha-pills';

            items.forEach(b => {
                const disabled = Number(b.beddha_status) === 0;
                const pill = document.createElement('label');
                pill.className = 'beddha-pill form-check-label' + (disabled ? ' beddha-disabled' : '');
                pill.setAttribute('title', disabled ? 'Admin assigned this Bheddha ID' : '');

                const input = document.createElement('input');
                input.type = 'checkbox';
                input.className = 'form-check-input';
                input.name = `beddha_id[${sebaId}][]`;
                input.value = b.id;
                input.id = `beddha_${sebaId}_${b.id}`;
                if(disabled) input.disabled = true;

                const text = document.createElement('span');
                text.textContent = b.beddha_name;

                pill.htmlFor = input.id;
                pill.prepend(input);
                pill.appendChild(text);
                pills.appendChild(pill);
            });

            inner.appendChild(pills);
            group.appendChild(inner);
            beddhaListWrap.appendChild(group);
        }

        // Remove Bheddha group for a Seba
        function removeBeddhaGroup(sebaId){
            const node = document.getElementById(`beddha_group_${sebaId}`);
            if(node) node.remove();
        }

        // Attach listeners to Seba checkboxes
        document.querySelectorAll('.seba-checkbox').forEach(cb => {
            cb.addEventListener('change', function(){
                const sebaId   = this.dataset.sebaId;
                const sebaName = this.nextElementSibling?.textContent?.trim() || 'Seba';
                if(this.checked){
                    // Fetch Bheddha for this Seba
                    fetch(`/admin/get-beddha/${sebaId}`)
                        .then(r => r.json())
                        .then(list => {
                            renderBeddhaGroup(sebaId, sebaName, list || []);
                            updateBeddhaSectionVisibility();
                        })
                        .catch(() => {
                            Swal.fire({icon:'error', title:'Could not load Bheddha', text:'Please try again.'});
                            this.checked = false;
                            updateBeddhaSectionVisibility();
                        });
                }else{
                    removeBeddhaGroup(sebaId);
                    updateBeddhaSectionVisibility();
                }
            });
        });

        // Init visibility (in case of old input)
        updateBeddhaSectionVisibility();
    })();
    </script>
@endsection
