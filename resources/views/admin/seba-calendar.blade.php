@extends('layouts.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        #custom-calendar {
            max-width: 100%;
            margin: 0 auto;
            height: 900px !important;
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .fc .fc-toolbar-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #f8c66d;
        }

        .fc-button {
            background-color: #f8c66d !important;
            border: none !important;
            border-radius: 6px !important;
        }

        .fc-button-primary:not(:disabled):hover {
            background-color: #f8c66d !important;
        }

        .fc-event {
            background-color: #e96a01 !important;
            border: none !important;
            border-radius: 4px !important;
            padding: 2px 4px;
            font-size: 0.685rem;
        }

        .fc-daygrid-day-number {
            font-weight: 600;
            font-size: 14px;
        }

        .fc-daygrid-day {
            background-color: #f8f9fa;
        }

        .card-header {
            background: linear-gradient(90deg, #007bff 0%, #6a11cb 100%);
            color: rgb(233, 234, 237);
            font-size: 25px;
            font-weight: bold;
            text-align: center;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            letter-spacing: 1px;
            text-transform: uppercase;
        }
    </style>
@endsection

@section('content')
    <div class="row">

        <div class="col-12 mt-2">
            <div class="card">
                <div class="col-lg-12 mt-4">
                    <div class="card custom-card overflow-hidden">
                        <div class="card-header">Seba Calendar</div>
                    </div>
                </div>
                <div class="row">
                    <!-- Filter by Pratihari Name -->
                    <div class="col-lg-4 mb-4 mx-auto d-flex mt-4">
                        <div class="card custom-card w-100">
                            <div class="card-header text-white" style="background-color: #f8c66d">
                                <i class="bi bi-filter me-2"></i>Filter by Pratihari Name
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ url()->current() }}" id="searchForm">
                                    <div class="mb-3 d-flex align-items-center">
                                        <select class="form-select me-2" name="pratihari_id" id="pratihariSelect">
                                            <option value="">-- Select Pratihari Name --</option>
                                            @foreach ($profile_name as $profile)
                                                <option value="{{ $profile->pratihari_id }}"
                                                    {{ request('pratihari_id') == $profile->pratihari_id ? 'selected' : '' }}>
                                                    {{ $profile->first_name }} {{ $profile->middle_name }}
                                                    {{ $profile->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-primary"
                                            onclick="submitSearch()">Search</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-4 mx-auto d-flex mt-4">
                        <div class="card custom-card w-100">
                            <div class="card-header text-white" style="background-color: #f8c66d">
                                <i class="bi bi-filter me-2"></i>Filter by Gochhikar Name
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ url()->current() }}">
                                    <div class="mb-3">
                                        <select class="form-select" name="pratihari_id" onchange="this.form.submit()">
                                            <option value="">-- Select Gochhikar Name --</option>
                                            @foreach ($gochhikar_name as $gochhikar)
                                                <option value="{{ $gochhikar->pratihari_id }}"
                                                    {{ request('pratihari_id') == $profile->pratihari_id ? 'selected' : '' }}>
                                                    {{ $gochhikar->first_name }} {{ $gochhikar->middle_name }}
                                                    {{ $gochhikar->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Calendar -->
                    <div class="col-lg-12 mb-4">
                        <div class="card custom-card">
                            <div class="card-header text-white" style="background-color: #f8c66d">
                                <i class="bi bi-calendar-event me-2"></i>Custom Calendar
                            </div>
                            <div class="card-body">
                                <div id="custom-calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="eventModalLabel">Seba Details</h5>
                                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Seba Name:</strong> <span id="modalSebaName"></span></p>
                                <p><strong>Beddha ID:</strong> <span id="modalBeddhaId"></span></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('custom-calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 500,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    const urlParams = new URLSearchParams(window.location.search);
                    const pratihariId = urlParams.get('pratihari_id');

                    if (pratihariId) {
                        fetch(
                                `{{ route('admin.sebaDate') }}?pratihari_id=${encodeURIComponent(pratihariId)}&_=no_cache`
                            )
                            .then(response => {
                                if (!response.ok) throw new Error("Failed to load events");
                                return response.json();
                            })
                            .then(data => successCallback(data))
                            .catch(error => {
                                console.error("Error loading events:", error);
                                failureCallback(error);
                            });
                    } else {
                        successCallback([]);
                    }
                },
                eventClick: function(info) {
                    const sebaName = info.event.extendedProps.sebaName;
                    const beddhaId = info.event.extendedProps.beddhaId;

                    document.getElementById('modalSebaName').innerText = sebaName;
                    document.getElementById('modalBeddhaId').innerText = beddhaId;

                    const modal = new bootstrap.Modal(document.getElementById('eventModal'));
                    modal.show();
                }

            });

            calendar.render();
        });
    </script>
@endsection
