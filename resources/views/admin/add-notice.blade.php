@extends('layouts.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <style>
        :root{
            --brand-a:#7c3aed;
            --brand-b:#06b6d4;
            --ink:#0b1220;
            --muted:#64748b;
            --border:rgba(2,6,23,.10);
            --panel:rgba(2,6,23,.03);
        }
        .page-header{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff;
            border-radius:1rem;
            padding:1rem 1.25rem;
            box-shadow:0 10px 24px rgba(6,182,212,.18);
        }
        .page-header .title{ font-weight:800; letter-spacing:.3px; }
        .card{
            border:1px solid var(--border);
            border-radius:14px;
            box-shadow:0 8px 22px rgba(2,6,23,.06);
        }
        .card-header{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff;
            font-weight:800;
            letter-spacing:.3px;
            text-transform:uppercase;
            border-radius:14px 14px 0 0;
            display:flex;
            align-items:center;
            gap:.6rem;
            justify-content:center;
        }
        .input-group-text{ background:#fff; }
        .form-label{ font-weight:700; color:var(--ink); }
        .text-hint{ color:var(--muted); font-size:.9rem; }
        .custom-gradient-btn{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            border:0;
            color:#fff;
            font-weight:800;
            border-radius:10px;
            box-shadow:0 12px 24px rgba(124,58,237,.22);
        }
        .custom-gradient-btn:hover{ opacity:.96; }

        .fade-out{ animation:fadeout .5s ease-in-out forwards; }
        @keyframes fadeout{ to{ opacity:0; height:0; margin:0; padding:0; } }

        /* Notification panel */
        .section-title{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:.75rem;
            margin:0;
        }
        .notify-panel{
            border:1px solid var(--border);
            background:var(--panel);
            border-radius:14px;
            padding:14px;
        }
        .notify-panel .badge{
            font-weight:800;
            letter-spacing:.2px;
        }

        /* Select2 (better height alignment with Bootstrap) */
        .select2-container .select2-selection--multiple{
            min-height: 40px;
            border: 1px solid #dee2e6;
            border-radius: .375rem;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple{
            border-color: #86b7fe;
            box-shadow: 0 0 0 .25rem rgba(13,110,253,.25);
        }
    </style>
@endsection

@section('content')
    <div class="page-header mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <div class="title h4 mb-0">
                    <i class="fa-solid fa-bullhorn me-2"></i>Add Notice
                </div>
                <div class="text-hint">Create a notice and optionally send notification (Pratihari / Gochhikar / category-wise).</div>
            </div>
        </div>
    </div>

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
                    <i class="fa-solid fa-clipboard-list"></i> Notice
                </div>

                <div class="card-body">
                    <form action="{{ route('saveNotice') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf

                        <div class="row g-3">

                            {{-- Notice fields --}}
                            <div class="col-md-3">
                                <label for="notice_name" class="form-label">Notice Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-pen"></i></span>
                                    <input type="text"
                                           class="form-control @error('notice_name') is-invalid @enderror"
                                           id="notice_name" name="notice_name"
                                           value="{{ old('notice_name') }}"
                                           placeholder="Enter Notice Name"
                                           required maxlength="150">
                                    @error('notice_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="from_date" class="form-label">From Date</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                                    <input type="date"
                                           class="form-control @error('from_date') is-invalid @enderror"
                                           id="from_date" name="from_date"
                                           value="{{ old('from_date') }}"
                                           min="{{ now()->format('Y-m-d') }}"
                                           required>
                                    @error('from_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="to_date" class="form-label">To Date</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-regular fa-calendar-days"></i></span>
                                    <input type="date"
                                           class="form-control @error('to_date') is-invalid @enderror"
                                           id="to_date" name="to_date"
                                           value="{{ old('to_date') }}"
                                           min="{{ now()->format('Y-m-d') }}"
                                           required>
                                    @error('to_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="notice_photo" class="form-label">Notice Photo <span class="text-hint">(optional)</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-regular fa-image"></i></span>
                                    <input type="file"
                                           class="form-control @error('notice_photo') is-invalid @enderror"
                                           id="notice_photo" name="notice_photo" accept="image/*">
                                    @error('notice_photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <small class="text-muted">Accepted: JPG, PNG, WebP; max 2MB.</small>
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label">Description <span class="text-hint">(optional)</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-align-left"></i></span>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description"
                                              rows="4" placeholder="Enter Description">{{ old('description') }}</textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Notification Panel --}}
                            <div class="col-12">
                                <div class="notify-panel mt-2">
                                    <div class="section-title">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge text-bg-dark">
                                                <i class="fa-solid fa-bell me-1"></i> Notification
                                            </span>
                                            <span class="text-hint">Send notice to recipients (device-wise) after saving.</span>
                                        </div>

                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input" type="checkbox" value="1" id="send_notification"
                                                   name="send_notification" {{ old('send_notification', 1) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="send_notification">Enable</label>
                                        </div>
                                    </div>

                                    <div class="row g-3 mt-1">
                                        <div class="col-md-4">
                                            <label class="form-label mb-1">Recipient Group</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa-solid fa-users"></i></span>
                                                <select class="form-control" id="recipient_group" name="recipient_group">
                                                    <option value="all" {{ old('recipient_group', 'all') === 'all' ? 'selected' : '' }}>
                                                        All (Pratihari + Gochhikar)
                                                    </option>
                                                    <option value="pratihari" {{ old('recipient_group') === 'pratihari' ? 'selected' : '' }}>
                                                        Only Pratihari
                                                    </option>
                                                    <option value="gochhikar" {{ old('recipient_group') === 'gochhikar' ? 'selected' : '' }}>
                                                        Only Gochhikar
                                                    </option>
                                                    <option value="selected" {{ old('recipient_group') === 'selected' ? 'selected' : '' }}>
                                                        Selected Individuals
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="text-hint mt-1">Choose who should receive this notice notification.</div>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label mb-1">Category Filter</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa-solid fa-filter"></i></span>
                                                <select class="form-control" name="category_filter" id="category_filter">
                                                    @foreach($categories as $key => $label)
                                                        <option value="{{ $key }}" {{ old('category_filter', 'all') === $key ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="text-hint mt-1">Optional filter by category A / B / C.</div>
                                        </div>

                                        <div class="col-md-5">
                                            <label class="form-label mb-1">
                                                Select Individuals
                                                <span class="text-hint">(only when Recipient Group = Selected)</span>
                                            </label>

                                            <select class="form-control select2" id="pratihari_ids" name="pratihari_ids[]" multiple>
                                                <optgroup label="Pratihari">
                                                    @foreach($pratihari_name as $p)
                                                        <option value="{{ $p->pratihari_id }}"
                                                            {{ collect(old('pratihari_ids', []))->contains($p->pratihari_id) ? 'selected' : '' }}>
                                                            {{ $p->full_name }} ({{ strtoupper($p->category ?? '-') }}) - {{ $p->pratihari_id }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>

                                                <optgroup label="Gochhikar">
                                                    @foreach($gochhikar_name as $p)
                                                        <option value="{{ $p->pratihari_id }}"
                                                            {{ collect(old('pratihari_ids', []))->contains($p->pratihari_id) ? 'selected' : '' }}>
                                                            {{ $p->full_name }} ({{ strtoupper($p->category ?? '-') }}) - {{ $p->pratihari_id }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            </select>

                                            @error('pratihari_ids') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                            @error('pratihari_ids.*') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

                                            <div class="text-hint mt-1">
                                                Individuals list is segregated by Seba type (Pratihari / Gochhikar).
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-lg mt-2 px-5 custom-gradient-btn">
                                    <i class="fa-regular fa-floppy-disk me-2"></i>Submit
                                </button>
                            </div>

                        </div>
                    </form>

                </div>{{-- card-body --}}
            </div>{{-- card --}}
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // Alerts auto fade
            setTimeout(() => document.getElementById("successMessage")?.classList.add("fade-out"), 3000);
            setTimeout(() => document.getElementById("errorMessage")?.classList.add("fade-out"), 3000);

            // Select2 init
            $('.select2').select2({ width: '100%' });

            // Date guard (from/to)
            const from = document.getElementById('from_date');
            const to   = document.getElementById('to_date');
            const today = new Date().toISOString().split('T')[0];

            if (from && to) {
                from.min = today;
                to.min   = today;

                if (!from.value || from.value < today) from.value = today;
                if (!to.value || to.value < from.value) to.value = from.value;

                function syncToMin() {
                    const fromVal = from.value || today;
                    to.min = fromVal < today ? today : fromVal;
                    if (to.value < to.min) to.value = to.min;
                }

                from.addEventListener('change', syncToMin);
                syncToMin();
            }

            // Enable/Disable Individuals selector
            const sendNotification = document.getElementById('send_notification');
            const group = document.getElementById('recipient_group');
            const select = document.getElementById('pratihari_ids');

            function toggleIndividuals() {
                const enabled = sendNotification.checked && group.value === 'selected';
                $(select).prop('disabled', !enabled).trigger('change.select2');
            }

            sendNotification.addEventListener('change', toggleIndividuals);
            group.addEventListener('change', toggleIndividuals);
            toggleIndividuals();
        });
    </script>
@endsection
