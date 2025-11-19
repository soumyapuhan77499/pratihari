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

        /* Section Nav (acts like tabs across modules) */
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

        /* Blocks */
        .section-title{ font-weight:800; color:#111827; display:flex; align-items:center; gap:.5rem; }
        .help{ font-size:.83rem; color:var(--muted); }

        /* Checkbox grid */
        .checkbox-list{
            display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
            gap:.6rem; padding:.75rem; background:#fff; border:1px dashed var(--border); border-radius:12px;
            max-height:280px; overflow:auto;
        }
        .form-check-input{ transform:scale(1.15); cursor:pointer; margin-top:.35rem; }
        .form-check-label{ cursor:pointer; }

        /* Grouped Beddhas */
        .beddha-group{ border:1px solid var(--border); border-radius:12px; padding:1rem; background:#fcfcfd; }
        .beddha-group strong{ color:#111827; }

        /* Buttons */
        .btn-brand{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            border:0; color:#fff; font-weight:800; border-radius:10px;
            box-shadow:0 12px 24px rgba(124,58,237,.22);
        }
        .btn-brand:hover{ opacity:.96 }
        .btn-amber{ background-color:var(--accent); color:#1f2937; border:0; }
        .btn-amber:hover{ filter:brightness(.95); }

        .chip{ display:inline-flex; align-items:center; gap:.45rem; font-weight:700;
            background:var(--soft); border:1px dashed var(--border); padding:.3rem .6rem; border-radius:999px; }

        @media (max-width: 768px){
            .tabbar{ overflow-x:auto; white-space:nowrap; }
        }
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-12 mt-3">
        <div class="card shadow-lg">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    {{-- Back button on the left --}}
                    <a href="{{ route('admin.viewProfile', ['pratihari_id' => $pratihari_id]) }}"
                        class="btn btn-light btn-sm d-inline-flex align-items-center">
                        <i class="fa-solid fa-arrow-left me-1"></i>
                        <span>Back to Profile</span>
                    </a>

                    {{-- Title on the right / center-ish --}}
                    <div class="text-uppercase fw-bold d-flex align-items-center">
                        <i class="fa-solid fa-location-dot me-2"></i>
                        <span>Seba Details</span>
                    </div>
                </div>

            <!-- Section Nav -->
            <div class="px-3 pt-3">
                <ul class="nav tabbar flex-nowrap" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.pratihariProfile') }}">
                            <i class="fas fa-user"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.pratihariFamily') }}"><i class="fas fa-users"></i> Family</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.pratihariIdcard') }}"><i class="fas fa-id-card"></i> ID Card</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.pratihariAddress') }}"><i class="fas fa-map-marker-alt"></i> Address</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.pratihariOccupation') }}"><i class="fas fa-briefcase"></i> Occupation</a></li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.pratihariSeba') }}">
                            <i class="fas fa-cogs"></i> Seba
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-share-alt"></i> Social Media</a></li>
                </ul>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.pratihari-seba.update', $pratihari_id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="pratihari_id" value="{{ $pratihari_id }}">

                    <!-- Seba List -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h5 class="section-title mb-0">
                                <i class="fa-solid fa-list-check text-warning"></i>
                                Available Sebas
                            </h5>
                            <span class="chip"><i class="fa-regular fa-lightbulb"></i> Choose one or more</span>
                        </div>
                        <div class="checkbox-list" id="seba_list" aria-label="Available Sebas">
                            @foreach ($sebas as $seba)
                                <div class="form-check d-flex gap-2 align-items-start">
                                    <input class="form-check-input seba-checkbox" type="checkbox"
                                           name="seba_id[]" value="{{ $seba->id }}"
                                           id="seba_{{ $seba->id }}"
                                           data-seba-id="{{ $seba->id }}"
                                           {{ in_array($seba->id, $assignedSebas) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="seba_{{ $seba->id }}">
                                        {{ $seba->seba_name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="help mt-1">Tick a Seba to reveal its Beddha list below.</div>
                    </div>

                    <!-- Beddha Groups -->
                    <div>
                        <h5 class="section-title mb-2">
                            <i class="fa-solid fa-scroll text-warning"></i>
                            Beddha List
                        </h5>
                        <div id="beddha_list" class="d-flex flex-column gap-3" aria-live="polite">
                            {{-- Pre-render already assigned groups --}}
                            @foreach ($assignedSebas as $sebaId)
                                <div class="beddha-group" id="beddha_group_{{ $sebaId }}">
                                    <strong>{{ $sebaNames[$sebaId] ?? 'Unknown Seba' }}</strong>
                                    <div class="row gy-2 mt-2">
                                        @foreach ($beddhas[$sebaId] ?? [] as $beddha)
                                            <div class="col-md-4 col-lg-3">
                                                <div class="form-check">
                                                    <input class="form-check-input"
                                                           type="checkbox"
                                                           name="beddha_id[{{ $sebaId }}][]"
                                                           value="{{ $beddha->id }}"
                                                           id="beddha_{{ $sebaId }}_{{ $beddha->id }}"
                                                           {{ isset($assignedBeddhas[$sebaId]) && in_array($beddha->id, $assignedBeddhas[$sebaId]) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="beddha_{{ $sebaId }}_{{ $beddha->id }}">
                                                        {{ $beddha->beddha_name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-brand w-50">
                            <i class="fa fa-save me-1"></i> Update
                        </button>
                    </div>
                </form>
            </div> <!-- /card-body -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- SweetAlert for flash -->
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
        // Handle Seba toggle → load/remove its Beddha list
        document.addEventListener('DOMContentLoaded', () => {
            const beddhaList = document.getElementById('beddha_list');

            function makeBeddhaGroup(sebaId, sebaName, items){
                const wrap = document.createElement('div');
                wrap.className = 'beddha-group';
                wrap.id = `beddha_group_${sebaId}`;

                let cols = items.map(b =>
                    `<div class="col-md-4 col-lg-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="beddha_id[${sebaId}][]"
                                   value="${b.id}"
                                   id="beddha_${sebaId}_${b.id}">
                            <label class="form-check-label" for="beddha_${sebaId}_${b.id}">${b.beddha_name}</label>
                        </div>
                    </div>`).join('');

                wrap.innerHTML = `
                    <strong>${sebaName}</strong>
                    <div class="row gy-2 mt-2">${cols || '<div class="text-muted">No Beddha found.</div>'}</div>
                `;
                return wrap;
            }

            async function loadBeddhaFor(sebaCheckbox){
                const sebaId = sebaCheckbox.dataset.sebaId;
                const groupId = `beddha_group_${sebaId}`;

                if (sebaCheckbox.checked) {
                    // if already present (rendered server-side or previously fetched), keep it
                    if (document.getElementById(groupId)) return;

                    try {
                        const res = await fetch(`/admin/get-beddha/${sebaId}`);
                        if (!res.ok) throw new Error('Network error');
                        const data = await res.json();
                        const name = sebaCheckbox.nextElementSibling?.innerText?.trim() || 'Seba';

                        beddhaList.appendChild(makeBeddhaGroup(sebaId, name, data));
                    } catch (err) {
                        console.error('Beddha fetch failed:', err);
                        Swal.fire({icon:'error', title:'Fetch Error', text:'Failed to load Beddha list.'});
                        // uncheck on failure to keep UI consistent
                        sebaCheckbox.checked = false;
                    }
                } else {
                    const group = document.getElementById(groupId);
                    if (group) group.remove();
                }
            }

            document.querySelectorAll('.seba-checkbox').forEach(cb => {
                cb.addEventListener('change', () => loadBeddhaFor(cb));
            });
        });
    </script>
@endsection
