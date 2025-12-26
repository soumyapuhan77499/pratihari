@extends('layouts.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --brand-a: #7c3aed;
            /* violet */
            --brand-b: #06b6d4;
            /* cyan   */
            --accent: #f5c12e;
            /* amber  */
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
        .section-gap>[class^="col-"] {
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
                    <a href="{{ route('admin.viewProfile', $family->pratihari_id) }}"
                        class="btn btn-light btn-sm d-inline-flex align-items-center">
                        <i class="fa-solid fa-arrow-left me-1"></i>
                        <span>Back to Profile</span>
                    </a>

                    <div class="fw-bold d-flex align-items-center">
                        <i class="fa-solid fa-users me-2"></i>
                        <span>Update Family Details</span>
                    </div>
                </div>

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

                    <form action="{{ route('admin.pratihari-family.update', $family->pratihari_id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="pratihari_id" value="{{ $family->pratihari_id }}">

                        <div class="row g-3">

                            {{-- Father --}}
                            <div class="col-md-6">
                                <label class="form-label">Father Name</label>
                                <input type="text" class="form-control" name="father_name"
                                    value="{{ old('father_name', $family->father_name) }}"
                                    placeholder="Enter Father's Name">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Father Photo</label>
                                <input type="file" class="form-control" name="father_photo" accept="image/*">

                                @if ($family->father_photo)
                                    <button type="button" class="btn btn-outline-secondary btn-sm mt-2"
                                        data-bs-toggle="modal" data-bs-target="#fatherPhotoModal">
                                        <i class="fa-solid fa-image me-1"></i> View Image
                                    </button>
                                @endif
                            </div>

                            @if ($family->father_photo)
                                <div class="modal fade" id="fatherPhotoModal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Father Photo</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ asset($family->father_photo) }}" class="img-fluid rounded"
                                                    alt="Father Photo">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Mother --}}
                            <div class="col-md-6">
                                <label class="form-label">Mother Name</label>
                                <input type="text" class="form-control" name="mother_name"
                                    value="{{ old('mother_name', $family->mother_name) }}"
                                    placeholder="Enter Mother's Name">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Mother Photo</label>
                                <input type="file" class="form-control" name="mother_photo" accept="image/*">

                                @if ($family->mother_photo)
                                    <button type="button" class="btn btn-outline-secondary btn-sm mt-2"
                                        data-bs-toggle="modal" data-bs-target="#motherPhotoModal">
                                        <i class="fa-solid fa-image me-1"></i> View Image
                                    </button>
                                @endif
                            </div>

                            @if ($family->mother_photo)
                                <div class="modal fade" id="motherPhotoModal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Mother Photo</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ asset($family->mother_photo) }}" class="img-fluid rounded"
                                                    alt="Mother Photo">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Marital Status --}}
                            @php
                                // DB column is maritial_status (your schema)
                                $ms = old('marital_status', $family->maritial_status);
                            @endphp
                            <div class="col-md-6">
                                <label class="form-label d-block">Marital Status</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="marital_status" id="married"
                                            value="married" {{ $ms === 'married' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="married">Married</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="marital_status"
                                            id="unmarried" value="unmarried" {{ $ms === 'unmarried' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="unmarried">Unmarried</label>
                                    </div>
                                </div>
                            </div>

                            {{-- Spouse (Select/Manual + Photo) --}}
                            @php
                                // Try to preselect spouse id by matching name (best-effort)
                                $spouseSelectedId = '';
                                foreach ($pratiharis as $p) {
                                    $full = trim($p->first_name . ' ' . $p->middle_name . ' ' . $p->last_name);
                                    if ($family->spouse_name && $full === $family->spouse_name) {
                                        $spouseSelectedId = $p->id;
                                        break;
                                    }
                                }

                                $oldSpouseSelect = old('spouse_select', $spouseSelectedId ?: '');
                                $oldSpouseManual = old(
                                    'spouse_name_manual',
                                    $spouseSelectedId ? '' : $family->spouse_name ?? '',
                                );
                            @endphp

                            <div class="row g-3 mt-1" id="spouseDetails" style="display:none;">
                                <div class="col-md-6">
                                    <label class="form-label">Spouse Name</label>
                                    <select class="form-select" name="spouse_select" id="spouse_select">
                                        <option value="">Select Spouse</option>
                                        @foreach ($pratiharis as $p)
                                            @php $full = trim($p->first_name.' '.$p->middle_name.' '.$p->last_name); @endphp
                                            <option value="{{ $p->id }}"
                                                {{ (string) $oldSpouseSelect === (string) $p->id ? 'selected' : '' }}>
                                                {{ $full }}
                                            </option>
                                        @endforeach
                                        <option value="manual" {{ $oldSpouseSelect === 'manual' ? 'selected' : '' }}
                                            class="text-danger">
                                            Not in list (Manual)
                                        </option>
                                    </select>

                                    <div class="mt-2" id="spouse_manual_div" style="display:none;">
                                        <label class="form-label">Spouse Name (Manual)</label>
                                        <input type="text" class="form-control" name="spouse_name_manual"
                                            id="spouse_name_manual" value="{{ $oldSpouseManual }}"
                                            placeholder="Enter spouse name">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Spouse Photo</label>
                                    <input type="file" class="form-control" name="spouse_photo" accept="image/*">

                                    @if ($family->spouse_photo)
                                        <button type="button" class="btn btn-outline-secondary btn-sm mt-2"
                                            data-bs-toggle="modal" data-bs-target="#spousePhotoModal">
                                            <i class="fa-solid fa-image me-1"></i> View Image
                                        </button>
                                    @endif
                                </div>

                                @if ($family->spouse_photo)
                                    <div class="modal fade" id="spousePhotoModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Spouse Photo</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="{{ asset($family->spouse_photo) }}"
                                                        class="img-fluid rounded" alt="Spouse Photo">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Children --}}
                            <div class="col-12 mt-3" id="childrenSection" style="display:none;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0"><i class="fa-solid fa-child me-2"></i> Children Details</h5>
                                    <button type="button" id="addChild" class="btn btn-sm btn-primary">
                                        <i class="fa fa-plus-circle me-1"></i> Add Child
                                    </button>
                                </div>

                                <div id="childrenContainer" class="mt-3">
                                    @foreach ($family->children as $index => $child)
                                        @php
                                            // best-effort preselect child spouse id by matching name
                                            $childSpouseId = '';
                                            foreach ($pratiharis as $p) {
                                                $full = trim(
                                                    $p->first_name . ' ' . $p->middle_name . ' ' . $p->last_name,
                                                );
                                                if ($child->spouse_name && $full === $child->spouse_name) {
                                                    $childSpouseId = $p->id;
                                                    break;
                                                }
                                            }

                                            $oldChildSpouseSelect = old(
                                                "children.$index.spouse_select",
                                                $childSpouseId ?: '',
                                            );
                                            $oldChildSpouseManual = old(
                                                "children.$index.spouse_name_manual",
                                                $childSpouseId ? '' : $child->spouse_name ?? '',
                                            );
                                            $oldChildMarital = old(
                                                "children.$index.marital_status",
                                                $child->marital_status,
                                            );
                                        @endphp

                                        <div class="row child-row border rounded p-3 mb-3"
                                            data-index="{{ $index }}">
                                            <input type="hidden" name="children[{{ $index }}][id]"
                                                value="{{ $child->id }}">

                                            <div class="col-md-3">
                                                <label class="form-label">Child Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control"
                                                    name="children[{{ $index }}][name]"
                                                    value="{{ old("children.$index.name", $child->children_name) }}"
                                                    placeholder="Enter Child Name">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">DOB <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control"
                                                    name="children[{{ $index }}][dob]"
                                                    value="{{ old("children.$index.dob", $child->date_of_birth) }}">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Gender <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select" name="children[{{ $index }}][gender]">
                                                    <option value="">Select</option>
                                                    <option value="male"
                                                        {{ old("children.$index.gender", $child->gender) === 'male' ? 'selected' : '' }}>
                                                        Male</option>
                                                    <option value="female"
                                                        {{ old("children.$index.gender", $child->gender) === 'female' ? 'selected' : '' }}>
                                                        Female</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Child Marital</label>
                                                <select class="form-select child-marital"
                                                    name="children[{{ $index }}][marital_status]">
                                                    <option value="">Select</option>
                                                    <option value="single"
                                                        {{ $oldChildMarital === 'single' ? 'selected' : '' }}>Single
                                                    </option>
                                                    <option value="married"
                                                        {{ $oldChildMarital === 'married' ? 'selected' : '' }}>Married
                                                    </option>
                                                    <option value="divorced"
                                                        {{ $oldChildMarital === 'divorced' ? 'selected' : '' }}>Divorced
                                                    </option>
                                                    <option value="widowed"
                                                        {{ $oldChildMarital === 'widowed' ? 'selected' : '' }}>Widowed
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Child Photo
                                                    {{ $child->photo ? '' : ' *' }}</label>
                                                <input type="file" class="form-control"
                                                    name="children[{{ $index }}][photo]" accept="image/*">

                                                @if ($child->photo)
                                                    <button type="button" class="btn btn-outline-secondary btn-sm mt-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#childPhotoModal{{ $index }}">
                                                        View Image
                                                    </button>
                                                @endif
                                            </div>

                                            {{-- Child spouse block --}}
                                            <div class="col-12 mt-3 child-spouse-wrap" style="display:none;">
                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Spouse (Select)</label>
                                                        <select class="form-select child-spouse-select"
                                                            name="children[{{ $index }}][spouse_select]">
                                                            <option value="">Select spouse</option>
                                                            @foreach ($pratiharis as $p)
                                                                @php $full = trim($p->first_name.' '.$p->middle_name.' '.$p->last_name); @endphp
                                                                <option value="{{ $p->id }}"
                                                                    {{ (string) $oldChildSpouseSelect === (string) $p->id ? 'selected' : '' }}>
                                                                    {{ $full }}
                                                                </option>
                                                            @endforeach
                                                            <option value="manual"
                                                                {{ $oldChildSpouseSelect === 'manual' ? 'selected' : '' }}
                                                                class="text-danger">
                                                                Not in list (Manual)
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4 child-spouse-manual-wrap" style="display:none;">
                                                        <label class="form-label">Spouse Name (Manual)</label>
                                                        <input type="text"
                                                            class="form-control child-spouse-manual-input"
                                                            name="children[{{ $index }}][spouse_name_manual]"
                                                            value="{{ $oldChildSpouseManual }}"
                                                            placeholder="Enter spouse name">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-1 d-flex align-items-end mt-3 mt-md-0">
                                                <button type="button" class="btn btn-danger removeChild w-100">
                                                    <i class="fa fa-trash-alt"></i>
                                                </button>
                                            </div>

                                            @if ($child->photo)
                                                <div class="modal fade" id="childPhotoModal{{ $index }}"
                                                    tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Child Photo</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
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
                                </div>
                            </div>

                            <div class="col-12 text-center mt-4">
                                <button type="submit" class="btn btn-lg btn-success px-5"  style="color: white">
                                    <i class="fa fa-save me-1"></i> Update
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        (function() {
            const marriedRadio = document.getElementById('married');
            const unmarriedRadio = document.getElementById('unmarried');
            const spouseDetails = document.getElementById('spouseDetails');
            const childrenSection = document.getElementById('childrenSection');

            const spouseSelectMain = document.getElementById('spouse_select');
            const spouseManualDiv = document.getElementById('spouse_manual_div');
            const spouseManualInp = document.getElementById('spouse_name_manual');

            const addChildBtn = document.getElementById('addChild');
            const container = document.getElementById('childrenContainer');

            // Build spouse options html from server list for dynamic child rows
            const PRATIHARIS = @json($pratiharis ?? []);

            function esc(s) {
                return String(s ?? '').replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;').replaceAll("'", "&#039;");
            }

            function fullName(p) {
                return [p.first_name, p.middle_name, p.last_name].filter(Boolean).join(' ').trim();
            }

            function spouseOptionsHtml() {
                let h = `<option value="">Select spouse</option>`;
                PRATIHARIS.forEach(p => {
                    h += `<option value="${p.id}">${esc(fullName(p))}</option>`;
                });
                h += `<option value="manual" class="text-danger">Not in list (Manual)</option>`;
                return h;
            }
            const spouseOpt = spouseOptionsHtml();

            function refreshMainSpouseManual() {
                if (!spouseSelectMain || !spouseManualDiv) return;

                if (spouseSelectMain.value === 'manual') {
                    spouseManualDiv.style.display = 'block';
                    if (spouseManualInp) spouseManualInp.required = true;
                } else {
                    spouseManualDiv.style.display = 'none';
                    if (spouseManualInp) {
                        spouseManualInp.required = false;
                    }
                }
            }

            function refreshMaritalUI() {
                const isMarried = marriedRadio && marriedRadio.checked;

                spouseDetails.style.display = isMarried ? 'flex' : 'none';
                spouseDetails.style.flexWrap = isMarried ? 'wrap' : '';
                childrenSection.style.display = isMarried ? 'block' : 'none';

                if (!isMarried) {
                    if (spouseSelectMain) spouseSelectMain.value = '';
                    if (spouseManualInp) spouseManualInp.value = '';
                }
                refreshMainSpouseManual();

                // Apply child spouse visibility per row
                container.querySelectorAll('.child-row').forEach(row => toggleChildSpouse(row));
            }

            function toggleChildSpouse(row) {
                const ms = row.querySelector('.child-marital');
                const spouseWrap = row.querySelector('.child-spouse-wrap');
                const spouseSel = row.querySelector('.child-spouse-select');
                const manualWrap = row.querySelector('.child-spouse-manual-wrap');
                const manualInp = row.querySelector('.child-spouse-manual-input');

                if (!ms || !spouseWrap) return;

                const isChildMarried = (ms.value === 'married');
                spouseWrap.style.display = isChildMarried ? 'block' : 'none';

                if (!isChildMarried) {
                    if (spouseSel) spouseSel.value = '';
                    if (manualInp) {
                        manualInp.value = '';
                        manualInp.required = false;
                    }
                    if (manualWrap) manualWrap.style.display = 'none';
                    return;
                }

                // Child married: spouse_select required
                if (spouseSel) spouseSel.required = true;

                if (spouseSel && spouseSel.value === 'manual') {
                    if (manualWrap) manualWrap.style.display = 'block';
                    if (manualInp) manualInp.required = true;
                } else {
                    if (manualWrap) manualWrap.style.display = 'none';
                    if (manualInp) {
                        manualInp.value = '';
                        manualInp.required = false;
                    }
                }
            }

            // Existing rows events
            container.querySelectorAll('.child-row').forEach(row => {
                row.querySelector('.child-marital')?.addEventListener('change', () => toggleChildSpouse(row));
                row.querySelector('.child-spouse-select')?.addEventListener('change', () => toggleChildSpouse(
                    row));
                row.querySelector('.removeChild')?.addEventListener('click', () => row.remove());
            });

            function childRowTemplate(idx) {
                return `
        <div class="row child-row border rounded p-3 mb-3" data-index="${idx}">
            <div class="col-md-3">
                <label class="form-label">Child Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="children[${idx}][name]" placeholder="Enter Child Name">
            </div>

            <div class="col-md-2">
                <label class="form-label">DOB <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="children[${idx}][dob]">
            </div>

            <div class="col-md-2">
                <label class="form-label">Gender <span class="text-danger">*</span></label>
                <select class="form-select" name="children[${idx}][gender]">
                    <option value="">Select</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Child Marital</label>
                <select class="form-select child-marital" name="children[${idx}][marital_status]">
                    <option value="">Select</option>
                    <option value="single">Single</option>
                    <option value="married">Married</option>
                    <option value="divorced">Divorced</option>
                    <option value="widowed">Widowed</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Child Photo <span class="text-danger">*</span></label>
                <input type="file" class="form-control" name="children[${idx}][photo]" accept="image/*">
            </div>

            <div class="col-12 mt-3 child-spouse-wrap" style="display:none;">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Spouse (Select)</label>
                        <select class="form-select child-spouse-select" name="children[${idx}][spouse_select]">
                            ${spouseOpt}
                        </select>
                    </div>

                    <div class="col-md-4 child-spouse-manual-wrap" style="display:none;">
                        <label class="form-label">Spouse Name (Manual)</label>
                        <input type="text" class="form-control child-spouse-manual-input"
                               name="children[${idx}][spouse_name_manual]" placeholder="Enter spouse name">
                    </div>
                </div>
            </div>

            <div class="col-md-1 d-flex align-items-end mt-3 mt-md-0">
                <button type="button" class="btn btn-danger removeChild w-100">
                    <i class="fa fa-trash-alt"></i>
                </button>
            </div>
        </div>`;
            }

            if (addChildBtn) {
                addChildBtn.addEventListener('click', () => {
                    const idx = container.querySelectorAll('.child-row').length;
                    container.insertAdjacentHTML('beforeend', childRowTemplate(idx));

                    const row = container.querySelector('.child-row:last-child');
                    row.querySelector('.child-marital')?.addEventListener('change', () => toggleChildSpouse(
                        row));
                    row.querySelector('.child-spouse-select')?.addEventListener('change', () =>
                        toggleChildSpouse(row));
                    row.querySelector('.removeChild')?.addEventListener('click', () => row.remove());

                    refreshMaritalUI();
                });
            }

            if (spouseSelectMain) {
                spouseSelectMain.addEventListener('change', refreshMainSpouseManual);
            }

            if (marriedRadio) marriedRadio.addEventListener('change', refreshMaritalUI);
            if (unmarriedRadio) unmarriedRadio.addEventListener('change', refreshMaritalUI);

            // Initial load
            refreshMaritalUI();

            // Ensure spouse manual visibility on load (especially when old input chooses manual)
            refreshMainSpouseManual();

            // Ensure existing child spouse blocks are correct on load
            container.querySelectorAll('.child-row').forEach(row => toggleChildSpouse(row));
        })();
    </script>
@endsection
