@extends('layouts.app')

@section('styles')
    <!-- Bootstrap 5.3 + Font Awesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --brand-a: #7c3aed;
            --brand-b: #06b6d4;
            --ink: #0b1220;
            --muted: #64748b;
            --border: rgba(2, 6, 23, .10);
            --ring: rgba(6, 182, 212, .28);
            --amber: #f5c12e;
        }

        /* Page header */
        .page-header {
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            color: #fff;
            border-radius: 1rem;
            padding: 1.05rem 1.25rem;
            box-shadow: 0 10px 24px rgba(6, 182, 212, .18);
        }

        .page-header .title {
            font-weight: 800;
            letter-spacing: .3px;
        }

        /* Filters section (separate) */
        .filters-section {
            margin-bottom: 1rem;
        }

        .filters-card {
            border-radius: 1rem;
            border: 1px solid var(--border);
            padding: .75rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(2, 6, 23, .04);
        }

        .filters-nav .nav-link {
            border-radius: .6rem;
            margin-right: .5rem;
            padding: .5rem .9rem;
            font-weight: 800;
        }

        .filters-nav .nav-link.active {
            box-shadow: 0 8px 24px rgba(124, 58, 237, .08);
        }

        .filters-count {
            min-width: 2.2rem;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            font-weight: 800;
            border-radius: .5rem;
            padding: .25rem .45rem;
        }

        /* Table and cards */
        .card {
            border: 1px solid var(--border);
            border-radius: 1rem;
        }

        .table-wrap {
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            overflow: auto;
        }

        thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background: #f8fafc;
            color: var(--ink);
            font-weight: 800;
            border-bottom: 1px solid var(--border);
        }

        tbody tr:hover {
            background: #f9fbff;
        }

        /* Photo */
        .profile-photo {
            width: 56px;
            height: 56px;
            border-radius: 10px;
            object-fit: cover;
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .profile-photo:hover {
            transform: scale(2.6);
            box-shadow: 0 8px 16px rgba(0, 0, 0, .25);
            z-index: 5;
        }

        /* Badges */
        .badge-soft {
            font-weight: 700;
            border: 1px solid var(--border);
            background: #f8fafc;
            color: var(--muted);
            padding: .35rem .6rem;
            border-radius: .6rem;
        }

        .badge-approved {
            background: #ecfdf5;
            color: #065f46;
            border-color: #10b981;
        }

        .badge-rejected {
            background: #fef2f2;
            color: #991b1b;
            border-color: #ef4444;
        }

        .badge-pending {
            background: #fff7ed;
            color: #9a3412;
            border-color: #f59e0b;
        }

        /* Utilities */
        .btn-slim {
            padding: .35rem .6rem;
            font-weight: 700;
            border-radius: .5rem;
        }

        :focus-visible {
            outline: 2px solid transparent;
            box-shadow: 0 0 0 3px var(--ring) !important;
            border-radius: 10px;
        }

        @media (max-width:720px) {
            .filters-nav {
                width: 100%;
                overflow-x: auto;
                white-space: nowrap;
            }

            .filters-nav .nav-link {
                display: inline-flex;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid my-3">
        <!-- Header -->
        <div class="page-header mb-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span
                        style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;background:linear-gradient(90deg,var(--brand-a),var(--brand-b));color:#fff;"><i
                            class="fa-solid fa-user-gear"></i></span>
                    <div>
                        <div class="title h4 mb-0">Pratihari • Manage Profiles</div>
                        <div class="small opacity-75">Browse, view details, and approve/reject submissions.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- == Filters Section (separate) == -->
        <div class="filters-section">
            <div
                class="filters-card d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-2 filters-nav" role="tablist" aria-label="Profile status tabs">
                    <button class="nav-link active d-flex align-items-center" data-status="all" id="tab-all"
                        type="button" aria-pressed="true" aria-controls="profiles-tbody">
                        <i class="fa-solid fa-list-check me-2"></i> All
                        <span class="filters-count bg-white text-dark ms-2">{{ $profiles->count() }}</span>
                    </button>

                    <button class="nav-link d-flex align-items-center" data-status="pending" id="tab-pending" type="button"
                        aria-pressed="false" aria-controls="profiles-tbody">
                        <i class="fa-solid fa-clock me-2"></i> Pending
                        <span class="filters-count bg-warning text-dark ms-2">{{ $counts['pending'] }}</span>
                    </button>

                    <button class="nav-link d-flex align-items-center" data-status="approved" id="tab-approved"
                        type="button" aria-pressed="false" aria-controls="profiles-tbody">
                        <i class="fa-solid fa-check me-2"></i> Approved
                        <span class="filters-count bg-success text-white ms-2">{{ $counts['approved'] }}</span>
                    </button>

                    <button class="nav-link d-flex align-items-center" data-status="rejected" id="tab-rejected"
                        type="button" aria-pressed="false" aria-controls="profiles-tbody">
                        <i class="fa-solid fa-ban me-2"></i> Rejected
                        <span class="filters-count bg-danger text-white ms-2">{{ $counts['rejected'] }}</span>
                    </button>
                </div>

                <div class="d-flex align-items-center gap-2 ms-auto w-100 w-md-auto">
                    <div class="input-group" style="min-width:240px;">
                        <span class="input-group-text bg-white border-end-0"><i
                                class="fa-solid fa-magnifying-glass"></i></span>
                        <input id="profile-search" class="form-control form-control-sm"
                            placeholder="Search by name, phone or card..." aria-label="Search profiles">
                        <button id="clear-search" class="btn btn-outline-secondary btn-sm" type="button"
                            title="Clear search" aria-label="Clear search"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Listing -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-wrap">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width:56px;">#</th>
                                <th style="width:80px;">Photo</th>
                                <th style="width:120px;">View</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th style="min-width:120px;">Address</th>
                                <th>Occupation</th>
                                <th>Health Card No</th>
                                <th>Status</th>
                                <th style="min-width:160px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="profiles-tbody">
                            @foreach ($profiles as $index => $profile)
                                @php $ps = $profile->pratihari_status ?? 'pending'; @endphp
                                <tr data-status="{{ $ps }}"
                                    data-search="{{ strtolower($profile->first_name . ' ' . $profile->middle_name . ' ' . $profile->last_name . ' ' . $profile->phone_no . ' ' . $profile->healthcard_no) }}">
                                    <td class="fw-bold">{{ $index + 1 }}</td>

                                    <td>
                                        @php
                                            // Default image placed at: public/assets/img/1.jpeg
                                            $defaultPhoto = asset('assets/img/1.jpeg');

                                            // Pick the best available photo
                                            $photo =
                                                $profile->photo_url ??
                                                ($profile->profile_photo
                                                    ? (str_starts_with($profile->profile_photo, 'http')
                                                        ? $profile->profile_photo
                                                        : asset(ltrim($profile->profile_photo, '/')))
                                                    : $defaultPhoto);
                                        @endphp

                                        <a href="{{ route('admin.viewProfile', $profile->pratihari_id) }}"
                                            class="text-decoration-none">
                                            <img src="{{ $photo }}" class="profile-photo" alt="Profile Photo"
                                                onerror="this.onerror=null;this.src='{{ $defaultPhoto }}';">
                                        </a>
                                    </td>


                                    <td>
                                        <a href="{{ route('admin.viewProfile', $profile->pratihari_id) }}"
                                            class="btn btn-slim" style="background:var(--amber); color:#1f2937;">
                                            <i class="fa-regular fa-eye me-1"></i> View
                                        </a>
                                    </td>

                                    <td class="text-truncate" style="max-width:220px;">
                                        {{ trim($profile->first_name . ' ' . $profile->middle_name . ' ' . $profile->last_name) }}
                                    </td>

                                    <td>
                                        <a href="tel:{{ $profile->phone_no }}" class="text-decoration-none">
                                            <i class="fa-solid fa-phone me-1 text-success"></i>{{ $profile->phone_no }}
                                        </a>
                                    </td>

                                    <td>
                                        <button class="btn btn-slim btn-outline-secondary view-address"
                                            data-bs-toggle="modal" data-bs-target="#addressModal"
                                            data-address="{{ $profile->address->address ?? 'N/A' }}"
                                            data-district="{{ $profile->address->district ?? 'N/A' }}"
                                            data-sahi="{{ $profile->address->sahi ?? 'N/A' }}"
                                            data-state="{{ $profile->address->state ?? 'N/A' }}"
                                            data-country="{{ $profile->address->country ?? 'N/A' }}"
                                            data-pincode="{{ $profile->address->pincode ?? 'N/A' }}"
                                            data-landmark="{{ $profile->address->landmark ?? 'N/A' }}"
                                            data-police-station="{{ $profile->address->police_station ?? 'N/A' }}">
                                            <i class="fa-solid fa-map-location-dot me-1"></i>
                                        </button>
                                    </td>

                                    <td>{{ $profile->occupation->occupation_type ?? 'N/A' }}</td>
                                    <td>{{ $profile->healthcard_no }}</td>

                                    <td>
                                        @if ($ps === 'approved')
                                            <span class="badge badge-soft badge-approved">Approved</span>
                                        @elseif ($ps === 'rejected')
                                            <span class="badge badge-soft badge-rejected">Rejected</span>
                                        @else
                                            <span class="badge badge-soft badge-pending">Pending</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="d-flex gap-2">
                                            @if ($profile->pratihari_status === 'approved')
                                                <button class="btn btn-success btn-slim" disabled>
                                                    <i class="fa-solid fa-check me-1"></i> Approved
                                                </button>
                                            @elseif ($profile->pratihari_status === 'rejected')
                                                <button class="btn btn-danger btn-slim" disabled>
                                                    <i class="fa-solid fa-xmark me-1"></i> Rejected
                                                </button>
                                            @else
                                                <button class="btn btn-success btn-slim approve-btn"
                                                    data-id="{{ $profile->id }}">
                                                    <i class="fa-solid fa-check me-1"></i> Approve
                                                </button>
                                                <button class="btn btn-outline-danger btn-slim reject-btn"
                                                    data-id="{{ $profile->id }}">
                                                    <i class="fa-solid fa-ban me-1"></i> Reject
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @if ($profiles->isEmpty())
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">
                                        <i class="fa-regular fa-face-meh-blank me-1"></i>No profiles found.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div> <!-- /table-wrap -->
            </div>
        </div>

        <!-- Address Modal -->
        <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-address-card me-2"></i>Pratihari Address Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <th style="width:220px;"><i class="fas fa-map-marker-alt me-1"></i> Address</th>
                                    <td id="modal-address">N/A</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-city me-1"></i> Sahi</th>
                                    <td id="modal-sahi">N/A</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-map-pin me-1"></i> Pincode</th>
                                    <td id="modal-pincode">N/A</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-road me-1"></i> Landmark</th>
                                    <td id="modal-landmark">N/A</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-building-shield me-1"></i> Police Station</th>
                                    <td id="modal-police-station">N/A</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-city me-1"></i> District</th>
                                    <td id="modal-district">N/A</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-flag me-1"></i> State</th>
                                    <td id="modal-state">N/A</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-globe me-1"></i> Country</th>
                                    <td id="modal-country">N/A</td>
                                </tr>
                            </tbody>
                        </table>
                    </div> <!-- /modal-body -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery (needed for your existing $.ajax usage) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap 5.3 bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Flash toasts -->
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

    <script>
        // Address modal data binding
        document.querySelectorAll(".view-address").forEach(btn => {
            btn.addEventListener("click", function() {
                document.getElementById("modal-address").textContent = this.getAttribute("data-address");
                document.getElementById("modal-district").textContent = this.getAttribute("data-district");
                document.getElementById("modal-sahi").textContent = this.getAttribute("data-sahi");
                document.getElementById("modal-state").textContent = this.getAttribute("data-state");
                document.getElementById("modal-country").textContent = this.getAttribute("data-country");
                document.getElementById("modal-pincode").textContent = this.getAttribute("data-pincode");
                document.getElementById("modal-landmark").textContent = this.getAttribute("data-landmark");
                document.getElementById("modal-police-station").textContent = this.getAttribute(
                    "data-police-station");
            });
        });

        // Prevent double submits on Approve/Reject
        function lockButtons(row, locked = true) {
            $(row).find('.approve-btn, .reject-btn').prop('disabled', locked);
        }

        $(function() {
            // Tab filtering (client-side)
            function showTab(status) {
                $('#profiles-tbody tr').each(function() {
                    const rowStatus = $(this).data('status') || 'pending';
                    if (status === 'all' || rowStatus === status) $(this).show();
                    else $(this).hide();
                });

                // update visual active state + aria-pressed
                $('.filters-nav [data-status]').each(function() {
                    const s = $(this).data('status');
                    const isActive = (s === status || (status === 'all' && s === 'all'));
                    $(this).toggleClass('active', isActive);
                    $(this).attr('aria-pressed', isActive ? 'true' : 'false');
                });

                // focus first visible row for keyboard users
                const firstVisible = $('#profiles-tbody tr:visible').first();
                if (firstVisible.length) firstVisible.find('a,button').first().focus();
            }

            // initial show all
            showTab('all');

            // wire tab clicks
            $('.filters-nav [data-status]').on('click keypress', function(e) {
                if (e.type === 'click' || e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    const status = $(this).data('status');
                    showTab(status);
                }
            });

            // search box
            $('#profile-search').on('input', function() {
                const q = $(this).val().trim().toLowerCase();
                $('#profiles-tbody tr').each(function() {
                    const hay = $(this).data('search') || '';
                    if (!q) {
                        // honor active tab
                        const active = $('.filters-nav .nav-link.active').data('status') || 'all';
                        const rs = $(this).data('status') || 'pending';
                        if (active === 'all' || active === rs) $(this).show();
                        else $(this).hide();
                    } else {
                        if (hay.indexOf(q) !== -1) $(this).show();
                        else $(this).hide();
                    }
                });
            });

            $('#clear-search').on('click', function() {
                $('#profile-search').val('').trigger('input').focus();
            });

            // Approve button handler
            $(document).on('click', '.approve-btn', function() {
                const $btn = $(this);
                const profileId = $btn.data('id');
                const row = $btn.closest('tr')[0];

                Swal.fire({
                    title: 'Approve profile?',
                    text: 'Do you want to approve this profile?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#22c55e',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Approve'
                }).then((result) => {
                    if (result.isConfirmed) {
                        lockButtons(row, true);
                        $.ajax({
                            url: '/admin/pratihari/approve/' + profileId,
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(resp) {
                                Swal.fire('Approved!', resp.message ||
                                        'Profile approved.', 'success')
                                    .then(() => location.reload());
                            },
                            error: function(xhr) {
                                lockButtons(row, false);
                                Swal.fire('Error', (xhr.responseJSON && xhr.responseJSON
                                        .message) ? xhr.responseJSON.message :
                                    'Could not approve. Please try again.', 'error');
                            }
                        });
                    }
                });
            });

            // Reject button handler
            $(document).on('click', '.reject-btn', function() {
                const $btn = $(this);
                const profileId = $btn.data('id');
                const row = $btn.closest('tr')[0];

                Swal.fire({
                    title: 'Reject Profile',
                    input: 'textarea',
                    inputLabel: 'Reason for rejection',
                    inputPlaceholder: 'Type your reason here...',
                    inputAttributes: {
                        'aria-label': 'Reject reason'
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#0ea5e9',
                    confirmButtonText: 'Reject',
                    preConfirm: (reason) => {
                        if (!reason) {
                            Swal.showValidationMessage('Reject reason is required');
                        }
                        return reason;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        lockButtons(row, true);
                        $.ajax({
                            url: '/admin/pratihari/reject/' + profileId,
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                reason: result.value
                            },
                            success: function(resp) {
                                Swal.fire('Rejected!', resp.message ||
                                        'Profile rejected.', 'success')
                                    .then(() => location.reload());
                            },
                            error: function(xhr) {
                                lockButtons(row, false);
                                Swal.fire('Error', (xhr.responseJSON && xhr.responseJSON
                                        .message) ? xhr.responseJSON.message :
                                    'Could not reject. Please try again.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
