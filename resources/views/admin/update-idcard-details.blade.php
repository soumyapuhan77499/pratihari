@extends('layouts.app')

@section('styles')
    <!-- Single Bootstrap + Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root{
            --brand-a:#7c3aed; /* violet   */
            --brand-b:#06b6d4; /* cyan     */
            --accent:#f5c12e;  /* amber    */
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

        /* Tabs */
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
        .input-group .form-control{
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

        /* Rows spacing */
        .id-entry{ background:#fff; border:1px dashed var(--border); border-radius:12px; padding:12px; }
        .section-gap > [class^="col-"]{ margin-bottom:14px; }

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
                    <a href="{{ route('admin.viewProfile', ['pratihari_id' => $pratihariId]) }}"
                        class="btn btn-light btn-sm d-inline-flex align-items-center">
                        <i class="fa-solid fa-arrow-left me-1"></i>
                        <span>Back to Profile</span>
                    </a>

                    {{-- Title on the right / center-ish --}}
                    <div class="text-uppercase fw-bold d-flex align-items-center">
                        <i class="fa-solid fa-location-dot me-2"></i>
                        <span>Id Card Details</span>
                    </div>
                </div>

            <!-- Top tabs (use routes you had) -->
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
                        <a class="nav-link active" href="{{ route('admin.pratihariIdcard') }}">
                            <i class="fas fa-id-card"></i> ID Card
                        </a>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-map-marker-alt"></i> Address</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-briefcase"></i> Occupation</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-cogs"></i> Seba</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-share-alt"></i> Social Media</a></li>
                </ul>
            </div>

            <div class="card-body pt-2">
                <form action="{{ route('idcard.update', $pratihariId) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="pratihari_id" value="{{ $pratihariId }}">

                    <div id="id-section" class="mt-2">
                        @forelse ($idCards as $idCard)
                            @php $idx = $loop->index; @endphp
                            <div class="row id-entry align-items-end g-3 mb-3">
                                <div class="col-md-4">
                                    <label>ID Type</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-id-card" style="color:var(--accent)"></i></span>
                                        <select class="form-control" name="id_type[]" required>
                                            <option value="" disabled>Select ID Type</option>
                                            @foreach (['Aadhar Card', 'Voter ID', 'Driving License', 'Passport', 'PAN Card'] as $option)
                                                <option value="{{ $option }}" {{ $idCard->id_type == $option ? 'selected' : '' }}>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- <div class="col-md-3">
                                    <label>ID Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-hashtag" style="color:var(--accent)"></i></span>
                                        <input type="text" class="form-control" name="id_number[]" required value="{{ $idCard->id_number }}">
                                    </div>
                                </div> --}}

                                <div class="col-md-4">
                                    <label>ID Photo Upload</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-camera" style="color:var(--accent)"></i></span>
                                        <input type="file" class="form-control" name="id_photo[]">
                                    </div>

                                    @if (!empty($idCard->id_photo))
                                        <button type="button"
                                                class="btn btn-outline-secondary btn-sm mt-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#imageModal-{{ $idx }}">
                                            <i class="fa-regular fa-image me-1"></i> View
                                        </button>

                                        <!-- Unique modal per row -->
                                        <div class="modal fade" id="imageModal-{{ $idx }}" tabindex="-1" aria-labelledby="imageModalLabel-{{ $idx }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="imageModalLabel-{{ $idx }}">ID Photo</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="{{ $idCard->id_photo }}" class="img-fluid rounded" alt="ID Photo">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-3">
                                    <button type="button" class="btn btn-danger w-100 remove-btn">
                                        <i class="fas fa-minus me-1"></i> Remove
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No ID cards yet. Use “Add ID” to create one.</p>
                        @endforelse
                    </div>

                    <div class="text-center">
                        <button type="button" class="btn btn-amber mt-2" id="add-id-btn">
                            <i class="fas fa-plus me-1"></i> Add ID
                        </button>
                        <button type="submit" class="btn btn-brand mt-2 w-50">
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
        // Add new ID row
        const addBtn = document.getElementById('add-id-btn');
        const idSection = document.getElementById('id-section');

        function newIdRowTemplate() {
            return `
            <div class="row id-entry align-items-end g-3 mb-3">
                <div class="col-md-3">
                    <label>ID Type</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-id-card" style="color:var(--accent)"></i></span>
                        <select class="form-control" name="id_type[]" required>
                            <option value="" disabled selected>Select ID Type</option>
                            <option value="Aadhar Card">Aadhar Card</option>
                            <option value="Voter ID">Voter ID</option>
                            <option value="Driving License">Driving License</option>
                            <option value="Passport">Passport</option>
                            <option value="PAN Card">PAN Card</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <label>ID Number</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-hashtag" style="color:var(--accent)"></i></span>
                        <input type="text" class="form-control" name="id_number[]" required placeholder="Enter ID Number">
                    </div>
                </div>

                <div class="col-md-3">
                    <label>ID Photo Upload</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-camera" style="color:var(--accent)"></i></span>
                        <input type="file" class="form-control" name="id_photo[]">
                    </div>
                </div>

                <div class="col-md-3">
                    <button type="button" class="btn btn-danger w-100 remove-btn">
                        <i class="fas fa-minus me-1"></i> Remove
                    </button>
                </div>
            </div>`;
        }

        if (addBtn && idSection){
            addBtn.addEventListener('click', () => {
                idSection.insertAdjacentHTML('beforeend', newIdRowTemplate());
            });

            // Event delegation for removing rows (works for existing + new)
            idSection.addEventListener('click', (e) => {
                const removeBtn = e.target.closest('.remove-btn');
                if (removeBtn){
                    const row = removeBtn.closest('.id-entry');
                    if (row) row.remove();
                }
            });
        }
    </script>
@endsection
