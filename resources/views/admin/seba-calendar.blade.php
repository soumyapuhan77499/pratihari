@extends('layouts.app')

@section('styles')
    <!-- Bootstrap 5 (single source of truth) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

    <!-- Select2 -->
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />

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

        .page-header{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff;border-radius:1rem;padding:1rem 1.25rem;
            box-shadow:0 10px 24px rgba(6,182,212,.18);
        }
        .page-header .title{font-weight:800;letter-spacing:.3px;}

        .card.custom-card{
            border:1px solid var(--border);
            border-radius:14px;
            box-shadow:0 8px 22px rgba(2,6,23,.06);
        }
        .card-header{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff;font-weight:800;letter-spacing:.3px;
            border-radius:14px 14px 0 0;
            display:flex;align-items:center;gap:.55rem;
        }

        /* Filter cards */
        .filter-header--pratihari{ background:#f8c66d; color:#1f2937; }
        .filter-header--gochhikar{ background:#e96a01; color:#fff; }

        /* Calendar wrapper */
        #custom-calendar{
            max-width: 100%;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 12px;
            border:1px solid var(--border);
            box-shadow:0 6px 18px rgba(2,6,23,.06);
        }
        .fc .fc-toolbar-title{ font-weight:800; color:#f8c66d; }
        .fc .fc-button{ background-color:#f8c66d; border:none; }
        .fc .fc-button:hover{ filter:brightness(.95); }
        .fc-event{
            background-color:#e96a01 !important;
            border:none !important;
            border-radius:6px !important;
            padding:2px 6px;
            font-size:.76rem;
        }
        .fc-daygrid-day{ background-color:#f8f9fa; }

        /* Select2 harmonize with BS5 */
        .select2-container .select2-selection--single{
            height: 38px;
            padding:.35rem .5rem;
            border:1px solid #ced4da;
            border-radius:.375rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered{
            line-height: 28px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow{
            height: 36px;
            right: 6px;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Page header -->
    <div class="page-header mt-3 mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <div class="title h4 mb-1">
                    <i class="fa-solid fa-calendar-days me-2"></i>Seba Calendar
                </div>
                <div class="small opacity-75">
                    Filter by Pratihari / Gochhikar or only by date range to view assigned Seba dates.
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Seba Calendar</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Filters -->
    <div class="row g-3">
        <!-- Pratihari Filter -->
        <div class="col-lg-6">
            <div class="card custom-card">
                <div class="card-header filter-header--pratihari">
                    <i class="fa-solid fa-filter me-1"></i> Filter by Pratihari
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ url()->current() }}">
                        <div class="mb-2">
                            <label class="form-label fw-semibold">Pratihari Name</label>
                            <select class="form-control select2" name="pratihari_id" onchange="this.form.submit()">
                                <option value="">-- Select Pratihari --</option>
                                @foreach ($profile_name as $profile)
                                    <option value="{{ $profile->pratihari_id }}"
                                        {{ request('pratihari_id') == $profile->pratihari_id ? 'selected' : '' }}>
                                        {{ trim($profile->first_name.' '.$profile->middle_name.' '.$profile->last_name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- keep other query params on submit --}}
                        @if(request('gochhikar_id'))
                            <input type="hidden" name="gochhikar_id" value="{{ request('gochhikar_id') }}">
                        @endif
                        @if(request('from'))
                            <input type="hidden" name="from" value="{{ request('from') }}">
                        @endif
                        @if(request('to'))
                            <input type="hidden" name="to" value="{{ request('to') }}">
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Gochhikar Filter -->
        <div class="col-lg-6">
            <div class="card custom-card">
                <div class="card-header filter-header--gochhikar">
                    <i class="fa-solid fa-filter me-1"></i> Filter by Gochhikar
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ url()->current() }}">
                        <div class="mb-2">
                            <label class="form-label fw-semibold">Gochhikar Name</label>
                            <select class="form-control select2" name="gochhikar_id" onchange="this.form.submit()">
                                <option value="">-- Select Gochhikar --</option>
                                @foreach ($gochhikar_name as $gochhikar)
                                    <option value="{{ $gochhikar->pratihari_id }}"
                                        {{ request('gochhikar_id') == $gochhikar->pratihari_id ? 'selected' : '' }}>
                                        {{ trim($gochhikar->first_name.' '.$gochhikar->middle_name.' '.$gochhikar->last_name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- keep other query params on submit --}}
                        @if(request('pratihari_id'))
                            <input type="hidden" name="pratihari_id" value="{{ request('pratihari_id') }}">
                        @endif
                        @if(request('from'))
                            <input type="hidden" name="from" value="{{ request('from') }}">
                        @endif
                        @if(request('to'))
                            <input type="hidden" name="to" value="{{ request('to') }}">
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="row g-3 mt-0">
      <div class="col-lg-12">
        <div class="card custom-card">
          <div class="card-header" style="background:#0ea5e9;color:#fff;">
            <i class="fa-solid fa-calendar-range me-1"></i> Filter by Date Range
          </div>
          <div class="card-body">
            <form method="GET" action="{{ url()->current() }}" class="row g-3 align-items-end">
              <div class="col-md-3">
                <label class="form-label fw-semibold">From</label>
                <input type="date" name="from" class="form-control" value="{{ request('from') }}">
              </div>
              <div class="col-md-3">
                <label class="form-label fw-semibold">To</label>
                <input type="date" name="to" class="form-control" value="{{ request('to') }}">
              </div>

              <!-- Preserve identity filters -->
              @if(request('pratihari_id'))
                <input type="hidden" name="pratihari_id" value="{{ request('pratihari_id') }}">
              @endif
              @if(request('gochhikar_id'))
                <input type="hidden" name="gochhikar_id" value="{{ request('gochhikar_id') }}">
              @endif

              <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                  <i class="fa-solid fa-magnifying-glass me-1"></i> Apply
                </button>
              </div>
              <div class="col-md-3">
                <a href="{{ url()->current() }}?{{ http_build_query(collect(request()->except(['from','to']))->all()) }}"
                   class="btn btn-outline-secondary w-100">
                  Reset Dates
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Calendar -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card custom-card">
                <div class="card-header">
                    <i class="fa-regular fa-calendar-check me-1"></i> Calendar
                </div>
                <div class="card-body">
                    @if(!request('pratihari_id') && !request('gochhikar_id') && !request('from') && !request('to'))
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="fa-solid fa-circle-info me-2"></i>
                            Select a Pratihari / Gochhikar, or choose a date range, then the calendar will show Seba.
                        </div>
                    @endif
                    <div id="custom-calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Event details modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="eventModalLabel">
                        <i class="fa-solid fa-clipboard-list me-2"></i>Seba Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-1"><strong>Pratihari Name:</strong> <span id="modalPratihariName"></span></p>
                    <p class="mb-1"><strong>Seba Name:</strong> <span id="modalSebaName"></span></p>
                    <p class="mb-0"><strong>Beddha ID:</strong> <span id="modalBeddhaId"></span></p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
    <!-- jQuery + Bootstrap 5 bundle -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Select2 -->
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.js"></script>

    <script>
        $(function () {
            $('.select2').select2({
                placeholder: 'Select name',
                allowClear: true,
                width: '100%'
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl  = document.getElementById('custom-calendar');
            const urlParams   = new URLSearchParams(window.location.search);
            const pratihariId = urlParams.get('pratihari_id');
            const gochhikarId = urlParams.get('gochhikar_id');
            const fromDate    = urlParams.get('from'); // YYYY-MM-DD
            const toDate      = urlParams.get('to');   // YYYY-MM-DD

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 650,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: function (fetchInfo, success, failure) {
                    // Load events if ANY filter is present:
                    // pratihari / gochhikar / date range
                    if (!pratihariId && !gochhikarId && !fromDate && !toDate) {
                        success([]);
                        return;
                    }

                    const qs = new URLSearchParams();

                    if (pratihariId) qs.set('pratihari_id', pratihariId);
                    if (gochhikarId) qs.set('gochhikar_id', gochhikarId);
                    if (fromDate)    qs.set('from', fromDate);
                    if (toDate)      qs.set('to', toDate);

                    // Prevent caching
                    qs.set('_', Date.now());

                    fetch(`{{ route('admin.sebaDate') }}?` + qs.toString())
                        .then(r => r.json())
                        .then(data => success(Array.isArray(data) ? data : []))
                        .catch(err => {
                            console.error('Error loading events:', err);
                            failure(err);
                        });
                },
                eventClick: function (info) {
                    const ext = info.event.extendedProps || {};
                    document.getElementById('modalPratihariName').innerText = ext.pratihariName || '';
                    document.getElementById('modalSebaName').innerText      = ext.sebaName || '';
                    document.getElementById('modalBeddhaId').innerText      = ext.beddhaId || '';
                    new bootstrap.Modal(document.getElementById('eventModal')).show();
                }
            });

            calendar.render();
        });
    </script>
@endsection
