@extends('layouts.app')

@section('styles')
    <!-- One Bootstrap + One Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --brand-a: #7c3aed; /* violet */
            --brand-b: #06b6d4; /* cyan   */
            --accent: #f5c12e; /* amber  */
            --ink: #0b1220;
            --muted: #64748b;
            --border: rgba(2, 6, 23, .10);
            --soft: #f8fafc;
        }

        .card {
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 8px 22px rgba(2, 6, 23, .06);
        }

        .card-header {
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            color: #fff;
            font-weight: 800;
            letter-spacing: .3px;
            border-radius: 14px 14px 0 0;
            text-transform: uppercase;
        }

        /* Tabs */
        .nav-tabs {
            border: 0;
            background: #fff;
            border-radius: 12px;
            padding: .4rem;
            box-shadow: 0 6px 18px rgba(2, 6, 23, .06);
        }

        .nav-tabs .nav-link {
            border: 1px solid transparent;
            background: var(--soft);
            color: var(--muted);
            border-radius: 10px;
            font-weight: 700;
            margin: .2rem;
            padding: .6rem .9rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            white-space: nowrap;
            transition: all .18s ease;
        }

        .nav-tabs .nav-link:hover {
            background: #eef2ff;
            color: var(--ink);
            transform: translateY(-1px);
            border-color: rgba(124, 58, 237, .25);
        }

        .nav-tabs .nav-link.active {
            color: #fff !important;
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            border-color: transparent;
            box-shadow: 0 10px 18px rgba(124, 58, 237, .22);
        }

        /* Inputs with icons */
        label {
            font-weight: 600;
            color: #1f2937
        }

        .input-group-text {
            background: #fff;
            border-right: 0;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .input-group .form-control {
            border-left: 0;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        /* Buttons */
        .btn-brand {
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            border: 0;
            color: #fff;
            font-weight: 800;
            border-radius: 10px;
            box-shadow: 0 12px 24px rgba(124, 58, 237, .22);
        }

        .btn-brand:hover {
            opacity: .96
        }

        /* Section spacing */
        .section-gap > [class^="col-"] {
            margin-bottom: 14px;
        }

        @media (max-width: 768px) {
            .nav-tabs {
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mt-3">
            <div class="card shadow-lg">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    {{-- Back button on the left --}}
                    @php
                        $pratihariId = $family->pratihari_id ?? (request('pratihari_id') ?? old('pratihari_id'));
                    @endphp

                    <a href="{{ $pratihariId ? route('admin.viewProfile', $pratihariId) : route('admin.pratihariProfile') }}"
                       class="btn btn-light btn-sm d-inline-flex align-items-center">
                        <i class="fa-solid fa-arrow-left me-1"></i>
                        <span>Back to Profile</span>
                    </a>

                    {{-- Title on the right / center-ish --}}
                    <div class="text-uppercase fw-bold d-flex align-items-center">
                        <i class="fa-solid fa-location-dot me-2"></i>
                        <span>Family Details</span>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="px-3 pt-3">
                    <ul class="nav nav-tabs flex-nowrap" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" href="{{ route('admin.pratihariProfile') }}">
                                <i class="fas fa-user"></i> Profile
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" id="family-tab" href="{{ route('admin.pratihariFamily') }}">
                                <i class="fas fa-users"></i> Family
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-id-card"></i> ID Card</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-map-marker-alt"></i> Address</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-briefcase"></i> Occupation</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-cogs"></i> Seba</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-share-alt"></i> Social Media</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body pt-2">
                    <form
                        action="{{ isset($family) ? route('admin.pratihari-family.update', $family->pratihari_id) : route('admin.pratihari-family.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($family))
                            @method('PUT')
                        @endif

                        <div class="row section-gap">
                            <input type="hidden" name="pratihari_id"
                                   value="{{ old('pratihari_id', $family->pratihari_id ?? '') }}">

                            <!-- Father Name -->
                            <div class="col-md-6">
                                <label for="father_name">Father Name</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fa fa-user" style="color:var(--accent)"></i>
                                    </span>
                                    <input type="text" class="form-control" id="father_name" name="father_name" required
                                           placeholder="Enter Father's Name"
                                           value="{{ old('father_name', $family->father_name ?? '') }}">
                                </div>
                            </div>

                            <!-- Father Photo -->
                            <div class="col-md-6">
                                <label for="father_photo">Father Photo</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fa fa-camera" style="color:var(--accent)"></i>
                                    </span>
                                    <input type="file" class="form-control" id="father_photo" name="father_photo">
                                </div>
                                @if (isset($family) && $family->father_photo)
                                    <button type="button" class="btn btn-outline-secondary btn-sm mt-2"
                                            data-bs-toggle="modal" data-bs-target="#fatherPhotoModal">
                                        <i class="fa-solid fa-image me-1"></i> View Image
                                    </button>
                                @endif
                            </div>

                            <!-- Father Photo Modal -->
                            <div class="modal fade" id="fatherPhotoModal" tabindex="-1"
                                 aria-labelledby="fatherPhotoModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="fatherPhotoModalLabel">Father Photo</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            @if (!empty($family) && !empty($family->father_photo))
                                                <img src="{{ asset($family->father_photo) }}" class="img-fluid rounded"
                                                     alt="Father Photo">
                                            @else
                                                <p class="text-muted mb-0">No father photo available.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mother Name -->
                            <div class="col-md-6">
                                <label for="mother_name">Mother Name</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fa fa-user" style="color:var(--accent)"></i>
                                    </span>
                                    <input type="text" class="form-control" id="mother_name" name="mother_name"
                                           required placeholder="Enter Mother's Name"
                                           value="{{ old('mother_name', $family->mother_name ?? '') }}">
                                </div>
                            </div>

                            <!-- Mother Photo -->
                            <div class="col-md-6">
                                <label for="mother_photo">Mother Photo</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fa fa-camera" style="color:var(--accent)"></i>
                                    </span>
                                    <input type="file" class="form-control" id="mother_photo" name="mother_photo">
                                </div>
                                @if (isset($family) && $family->mother_photo)
                                    <button type="button" class="btn btn-outline-secondary btn-sm mt-2"
                                            data-bs-toggle="modal" data-bs-target="#motherPhotoModal">
                                        <i class="fa-solid fa-image me-1"></i> View Full Image
                                    </button>
                                @endif
                            </div>

                            <!-- Mother Photo Modal -->
                            <div class="modal fade" id="motherPhotoModal" tabindex="-1"
                                 aria-labelledby="motherPhotoModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="motherPhotoModalLabel">Mother Photo</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            @if (!empty($family) && !empty($family->mother_photo))
                                                <img src="{{ asset($family->mother_photo) }}" class="img-fluid rounded"
                                                     alt="Mother Photo">
                                            @else
                                                <p class="text-muted mb-0">No mother photo available.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Marital Status -->
                            <div class="col-md-6">
                                <label class="form-label d-block">
                                    <i class="fa fa-heart me-1" style="color:var(--accent)"></i>
                                    Marital Status
                                </label>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="marital_status"
                                               id="married" value="married"
                                               {{ old('marital_status', $family->marital_status ?? '') == 'married' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="married">Married</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="marital_status"
                                               id="unmarried" value="unmarried"
                                               {{ old('marital_status', $family->marital_status ?? '') == 'unmarried' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="unmarried">Unmarried</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Spouse Details -->
                            <div class="row mt-2" id="spouseDetails" style="display:none;">
                                <div class="col-md-6">
                                    <label for="spouse_name">Spouse Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fa fa-user" style="color:var(--accent)"></i>
                                        </span>
                                        <input type="text" class="form-control" id="spouse_name" name="spouse_name"
                                               placeholder="Enter Spouse's Name"
                                               value="{{ old('spouse_name', $family->spouse_name ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="spouse_photo">Spouse Photo</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fa fa-camera" style="color:var(--accent)"></i>
                                        </span>
                                        <input type="file" class="form-control" id="spouse_photo" name="spouse_photo">
                                    </div>
                                    @if (isset($family) && $family->spouse_photo)
                                        <button type="button" class="btn btn-outline-secondary btn-sm mt-2"
                                                data-bs-toggle="modal" data-bs-target="#spousePhotoModal">
                                            <i class="fa-solid fa-image me-1"></i> View Full Image
                                        </button>
                                    @endif
                                </div>

                                <!-- Spouse Photo Modal -->
                                <div class="modal fade" id="spousePhotoModal" tabindex="-1"
                                     aria-labelledby="spousePhotoModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="spousePhotoModalLabel">Spouse Photo</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                @if (!empty($family) && !empty($family->spouse_photo))
                                                    <img src="{{ asset($family->spouse_photo) }}" class="img-fluid rounded"
                                                         alt="Spouse Photo">
                                                @else
                                                    <p class="text-muted mb-0">No spouse photo available.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Children -->
                            <div class="col-12 mt-3" id="childrenSection">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0">
                                        <i class="fas fa-child me-2" style="color:var(--accent)"></i>
                                        Children Details
                                    </h5>
                                    <button type="button" id="addChild" class="btn btn-sm btn-brand">
                                        <i class="fa fa-plus-circle me-1"></i> Add Child
                                    </button>
                                </div>

                                <div id="childrenContainer" class="mt-2">
                                    @php
                                        $childrenList =
                                            isset($family) && isset($family->children) ? $family->children : collect();
                                    @endphp

                                    @if ($childrenList->isNotEmpty())
                                        @foreach ($childrenList as $index => $child)
                                            <div class="row child-row mt-3 border p-3 rounded bg-light-subtle">
                                                <input type="hidden" name="children[{{ $index }}][id]"
                                                       value="{{ $child->id }}">
                                                <div class="col-md-4">
                                                    <label class="form-label">
                                                        <i class="fa fa-user me-1"></i> Child Name
                                                    </label>
                                                    <input type="text" class="form-control"
                                                           name="children[{{ $index }}][name]"
                                                           value="{{ old('children.' . $index . '.name', $child->children_name) }}"
                                                           placeholder="Enter Child's Name">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">
                                                        <i class="fa fa-calendar me-1"></i> Date of Birth
                                                    </label>
                                                    <input type="date" class="form-control"
                                                           name="children[{{ $index }}][dob]"
                                                           value="{{ old('children.' . $index . '.dob', $child->date_of_birth) }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">
                                                        <i class="fa fa-venus-mars me-1"></i> Gender
                                                    </label>
                                                    <select class="form-control"
                                                            name="children[{{ $index }}][gender]">
                                                        <option value="male"
                                                            {{ old('children.' . $index . '.gender', $child->gender) == 'male' ? 'selected' : '' }}>
                                                            Male
                                                        </option>
                                                        <option value="female"
                                                            {{ old('children.' . $index . '.gender', $child->gender) == 'female' ? 'selected' : '' }}>
                                                            Female
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">
                                                        <i class="fa fa-camera me-1"></i> Photo
                                                    </label>
                                                    <input type="file" class="form-control"
                                                           name="children[{{ $index }}][photo]">
                                                    @if ($child->photo)
                                                        <button type="button"
                                                                class="btn btn-outline-secondary btn-sm mt-2"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#childPhotoModal{{ $index }}">
                                                            View Full Image
                                                        </button>
                                                    @endif
                                                </div>
                                                <div class="col-md-1 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger removeChild">
                                                        <i class="fa fa-trash-alt"></i>
                                                    </button>
                                                </div>

                                                @if ($child->photo)
                                                    <div class="modal fade" id="childPhotoModal{{ $index }}"
                                                         tabindex="-1"
                                                         aria-labelledby="childPhotoModalLabel{{ $index }}"
                                                         aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="childPhotoModalLabel{{ $index }}">
                                                                        Child Photo
                                                                    </h5>
                                                                    <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-center">
                                                                    <img src="{{ asset($child->photo) }}"
                                                                         class="img-fluid rounded" alt="Child Photo">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted mt-2 mb-0">No children details added yet.</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="col-12 text-center mt-3">
                                <button type="submit" class="btn btn-lg w-50 btn-brand">
                                    <i class="fa fa-save me-1"></i>
                                    {{ isset($family) ? 'Update' : 'Submit' }}
                                </button>
                            </div>
                        </div> <!-- /row -->
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- SweetAlert for flash messages -->
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

    <!-- Bootstrap 5 bundle (single include) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ----- Marital status toggle (spouse + children) -----
        const marriedRadio    = document.getElementById('married');
        const unmarriedRadio  = document.getElementById('unmarried');
        const spouseDetails   = document.getElementById('spouseDetails');
        const childrenSection = document.getElementById('childrenSection');

        function updateMaritalSections() {
            if (!spouseDetails || !childrenSection) return;

            if (marriedRadio && marriedRadio.checked) {
                // Show spouse + children when married
                spouseDetails.style.display   = 'flex';
                childrenSection.style.display = 'block';
            } else {
                // Hide both when unmarried (or neither selected)
                spouseDetails.style.display   = 'none';
                childrenSection.style.display = 'none';
            }
        }

        if (marriedRadio && unmarriedRadio) {
            marriedRadio.addEventListener('change', updateMaritalSections);
            unmarriedRadio.addEventListener('change', updateMaritalSections);
            // Initialize on load (uses old()/DB value)
            updateMaritalSections();
        }

        // ----- Children dynamic rows (vanilla JS) -----
        const addChildBtn = document.getElementById('addChild');
        const container   = document.getElementById('childrenContainer');

        function childRowTemplate(idx) {
            return `
            <div class="row child-row mt-3 border p-3 rounded bg-light-subtle">
                <div class="col-md-4">
                    <label class="form-label"><i class="fa fa-user me-1"></i> Child Name</label>
                    <input type="text" class="form-control" name="children[${idx}][name]" placeholder="Enter Child's Name">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><i class="fa fa-calendar me-1"></i> Date of Birth</label>
                    <input type="date" class="form-control" name="children[${idx}][dob]">
                </div>
                <div class="col-md-2">
                    <label class="form-label"><i class="fa fa-venus-mars me-1"></i> Gender</label>
                    <select class="form-control" name="children[${idx}][gender]">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label"><i class="fa fa-camera me-1"></i> Photo</label>
                    <input type="file" class="form-control" name="children[${idx}][photo]">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger removeChild"><i class="fa fa-trash-alt"></i></button>
                </div>
            </div>`;
        }

        if (addChildBtn && container) {
            addChildBtn.addEventListener('click', () => {
                const idx = container.querySelectorAll('.child-row').length;
                container.insertAdjacentHTML('beforeend', childRowTemplate(idx));
            });

            container.addEventListener('click', (e) => {
                if (e.target.closest('.removeChild')) {
                    e.target.closest('.child-row').remove();
                }
            });
        }
    </script>
@endsection
