@extends('layouts.app')

@section('styles')
    <!-- Bootstrap + Select2 (single versions) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <!-- Font Awesome 6 (needed for all fa-solid / fa-regular icons below) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root{
            --brand-a:#7c3aed; /* violet  */
            --brand-b:#06b6d4; /* cyan    */
            --accent:#f5c12e;  /* amber   */
            --ink:#0b1220;
            --muted:#64748b;
            --border:rgba(2,6,23,.10);
            --soft:#f8fafc;
        }

        .card{ border:1px solid var(--border); border-radius:14px; box-shadow:0 8px 22px rgba(2,6,23,.06); }
        .card-header{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff; font-weight:800; letter-spacing:.3px; text-transform:uppercase;
            border-radius:14px 14px 0 0; display:flex; align-items:center; gap:.6rem;
        }

        /* Filter row */
        .filter-bar{
            background:#fff; border:1px solid var(--border); border-radius:12px; padding:.8rem;
            box-shadow:0 6px 18px rgba(2,6,23,.05);
        }
        .filter-label{ font-weight:700; color:var(--ink); }

        /* Select2 tweaks to look like Bootstrap 5 */
        .select2-container .select2-selection--single{
            height: 38px; padding:.35rem .5rem; border:1px solid #ced4da; border-radius:.375rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered{ line-height: 28px; }
        .select2-container--default .select2-selection--single .select2-selection__arrow{ height: 36px; right: 6px; }

        /* Sections */
        .beddha-section{ margin-top: 20px; padding: 15px; background:#fff; border-radius:12px; border:1px solid var(--border); }
        .beddha-group-row{ padding:14px 0; border-bottom:1px dashed #e2e8f0; }
        .beddha-group-row:last-child{ border-bottom:0; }

        .beddha-header{
            display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;
        }
        .seba-chip{
            display:inline-flex; align-items:center; gap:.5rem; font-weight:800; color:#fff;
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            padding:.4rem .7rem; border-radius:999px; box-shadow:0 8px 16px rgba(124,58,237,.22);
        }
        .group-actions .btn{
            --bs-btn-padding-y:.25rem; --bs-btn-padding-x:.5rem; --bs-btn-font-size:.8rem;
        }

        .beddha-items{ display:flex; flex-wrap:wrap; gap:12px; margin-top:10px; }
        .beddha-items .form-check{ display:flex; align-items:center; gap:8px; background:var(--soft); border:1px solid var(--border); padding:.45rem .6rem; border-radius:10px; }
        .beddha-items .form-check-input{ width:22px; height:22px; cursor:pointer; }
        .beddha-items .form-check-label{ margin-left: 2px; font-weight:600; color:var(--ink); }

        .custom-gradient-btn{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            border:0; color:#fff; font-weight:800; border-radius:10px;
            box-shadow:0 12px 24px rgba(124,58,237,.22);
        }
        .custom-gradient-btn:hover{ opacity:.96 }

        /* Small helpers */
        .input-icon{ color:var(--accent); width: 38px; display:inline-flex; align-items:center; justify-content:center; background:#fff; border:1px solid #ced4da; border-right:0; border-radius:.375rem 0 0 .375rem; }
        .input-w-icon .form-control{ border-left:0; }

        /* Alerts auto-hide fade */
        .fade-out{ animation: fadeout 0.6s ease-in-out forwards; }
        @keyframes fadeout{ to{ opacity:0; height:0; margin:0; padding:0; } }

        @media (max-width: 576px){
            .custom-gradient-btn{ width:100%; }
        }
    </style>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2" id="successMessage">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2" id="errorMessage">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-12 mt-2">
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-hand-holding-heart"></i>
                    Pratihari Seba Assign
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.savePratihariAssignSeba') }}" id="assignSebaForm">
                        @csrf

                        <!-- Filters -->
                        <div class="filter-bar mb-3">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-6">
                                    <label for="pratihari_id" class="filter-label">
                                        <i class="fa-solid fa-user-tag me-1 text-primary"></i> Select Pratihari
                                    </label>
                                    <div class="input-w-icon d-flex">
                                        <span class="input-icon"><i class="fa-regular fa-id-badge"></i></span>
                                        <select name="pratihari_id" id="pratihari_id" class="form-control select2" style="width:100%;">
                                            <option value="">-- Select Pratihari --</option>
                                            @foreach ($pratiharis as $pratihari_id_option => $name)
                                                <option value="{{ $pratihari_id_option }}" {{ request('pratihari_id') == $pratihari_id_option ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="year" class="filter-label">
                                        <i class="fa-solid fa-calendar-days me-1 text-primary"></i> Year
                                    </label>
                                    <div class="input-w-icon d-flex">
                                        <span class="input-icon"><i class="fa-regular fa-calendar-check"></i></span>
                                        <select class="form-select @error('year') is-invalid @enderror" id="year" name="year" required>
                                            <option value="">Select Year</option>
                                            @for ($y = date('Y'); $y >= 2000; $y--)
                                                @php $display = $y . '-' . ($y + 1); @endphp
                                                <option value="{{ $display }}" {{ request('year') == $display ? 'selected' : '' }}>{{ $display }}</option>
                                            @endfor
                                        </select>
                                        @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (request('pratihari_id') && request('year'))
                            <input type="hidden" name="pratihari_id" value="{{ request('pratihari_id') }}">
                            <input type="hidden" name="year" value="{{ request('year') }}">

                            <!-- BEDDHA ASSIGN -->
                            <div class="beddha-section">
                                <label class="h6 fw-bold d-flex align-items-center gap-2 mb-2">
                                    <i class="fa-solid fa-scroll text-warning"></i> Assign Beddha to Seba
                                </label>

                                <div class="checkbox-list" id="beddha_list">
                                    @foreach ($sebas as $seba)
                                        <input type="hidden" name="seba_id[]" value="{{ $seba->id }}">

                                        <div class="beddha-group-row" id="beddha_group_{{ $seba->id }}">
                                            <div class="beddha-header">
                                                <span class="seba-chip">
                                                    <i class="fa-solid fa-hands-praying"></i>
                                                    {{ $seba->seba_name }}
                                                </span>

                                                <div class="group-actions">
                                                    <button type="button"
                                                            class="btn btn-outline-secondary btn-sm select-all"
                                                            data-target="group-{{ $seba->id }}">
                                                        <i class="fa-regular fa-square-check me-1"></i> Select all
                                                    </button>
                                                    <button type="button"
                                                            class="btn btn-outline-secondary btn-sm clear-all"
                                                            data-target="group-{{ $seba->id }}">
                                                        <i class="fa-regular fa-square me-1"></i> Clear
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="beddha-items mt-2 group-{{ $seba->id }}">
                                                @foreach ($beddhas[$seba->id] ?? [] as $beddha)
                                                    <div class="form-check me-2">
                                                        <input class="form-check-input"
                                                               type="checkbox"
                                                               name="beddha_id[{{ $seba->id }}][]"
                                                               value="{{ $beddha->id }}"
                                                               id="beddha_{{ $seba->id }}_{{ $beddha->id }}"
                                                               {{ in_array($beddha->id, $assignedBeddhas[$seba->id] ?? []) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="beddha_{{ $seba->id }}_{{ $beddha->id }}">
                                                            <i class="fa-solid fa-user-tag me-1 text-primary"></i>{{ $beddha->beddha_name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                                @if (empty($beddhas[$seba->id]) || count($beddhas[$seba->id]) === 0)
                                                    <div class="text-muted small">No Beddhas available for this Seba.</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-lg mt-4 w-50 custom-gradient-btn">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Submit
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery, Bootstrap, Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(function () {
            // Flash message auto-hide
            setTimeout(() => { $('#successMessage').addClass('fade-out'); }, 3500);
            setTimeout(() => { $('#errorMessage').addClass('fade-out');   }, 3500);

            // Select2 init
            $('#pratihari_id').select2({
                placeholder: '-- Select Pratihari --',
                allowClear: true,
                width: '100%'
            });

            // Redirect on filter change (needs both)
            $('#pratihari_id, #year').on('change', function(){
                const pratihari_id = $('#pratihari_id').val();
                const year = $('#year').val();
                if(pratihari_id && year){
                    window.location.href = `{{ route('admin.PratihariSebaAssign') }}?pratihari_id=${pratihari_id}&year=${year}`;
                }
            });

            // Group Select All / Clear
            $(document).on('click','.select-all', function(){
                const groupClass = $(this).data('target');
                $(`.${groupClass} input[type="checkbox"]`).prop('checked', true);
            });
            $(document).on('click','.clear-all', function(){
                const groupClass = $(this).data('target');
                $(`.${groupClass} input[type="checkbox"]`).prop('checked', false);
            });
        });
    </script>
@endsection
