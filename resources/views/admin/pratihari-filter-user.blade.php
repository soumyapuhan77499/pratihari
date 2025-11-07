@extends('layouts.app')

@section('styles')
    <!-- Bootstrap & Font Awesome (one of each, no custom CSS) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
        :root {
            --brand-a: #7c3aed;
            /* violet  */
            --brand-b: #06b6d4;
            /* cyan    */
            --ink: #0b1220;
            --muted: #64748b;
            --border: rgba(2, 6, 23, .10);
        }

        .card-header {
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            color: #fff;
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            box-shadow: 0 10px 24px rgba(6, 182, 212, .18);
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-2">

                <div class="card shadow-sm border-0">
                    <!-- Header -->
                    <div class="card-header text-white">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <div class="d-flex align-items-center gap-2 fw-semibold">
                                <i class="fa-solid fa-user-circle"></i>
                                <span>Pratihari • Manage Profile</span>
                            </div>

                            <div class="ms-auto d-flex flex-wrap gap-2">
                                <span class="badge text-bg-success"><i
                                        class="fa-solid fa-check-circle me-1"></i>Approved</span>
                                <span class="badge text-bg-warning text-dark"><i
                                        class="fa-solid fa-pen-to-square me-1"></i>Updated</span>
                                <span class="badge text-bg-secondary"><i
                                        class="fa-regular fa-hourglass-half me-1"></i>Pending</span>
                                <span class="badge text-bg-danger"><i
                                        class="fa-solid fa-xmark-circle me-1"></i>Rejected</span>
                            </div>
                        </div>
                    </div>

                    <!-- Toolbar -->
                    <div class="card-body border-bottom">
                        <div class="row g-2 align-items-center">
                            <div class="col-12 col-md-7">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                                    <input id="searchBox" type="search" class="form-control"
                                        placeholder="Search name or phone…" aria-label="Search">
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <select id="statusFilter" class="form-select">
                                    <option value="">Filter: All statuses</option>
                                    <option value="approved">Approved</option>
                                    <option value="updated">Updated</option>
                                    <option value="pending">Pending</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2 text-md-end small text-muted">
                                <i class="fa-regular fa-circle-question me-1"></i>Click photo to open full profile
                            </div>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-body">
                        @if ($profiles->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="fa-regular fa-folder-open display-6 d-block mb-2"></i>
                                <div class="fw-semibold">No profiles found</div>
                                <div class="small">Try changing the status filter or your search.</div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table id="profiles-table" class="table table-striped table-hover align-middle">
                                    <thead class="table-light">
                                        <tr class="text-nowrap">
                                            <th>#</th>
                                            <th>Photo</th>
                                            <th>View</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>Occupation</th>
                                            <th>Health Card No</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($profiles as $index => $profile)
                                            @php
                                                $fullName = trim(
                                                    ($profile->first_name ?? '') .
                                                        ' ' .
                                                        ($profile->middle_name ?? '') .
                                                        ' ' .
                                                        ($profile->last_name ?? ''),
                                                );
                                                $photoSrc = $profile->profile_photo
                                                    ? asset($profile->profile_photo)
                                                    : asset('assets/images/placeholder-avatar.png');
                                                $status = strtolower($profile->pratihari_status ?? 'pending');
                                            @endphp

                                            <tr data-status="{{ $status }}">
                                                <td class="text-nowrap">{{ $index + 1 }}</td>

                                                <td>
                                                    <a href="{{ route('admin.viewProfile', $profile->pratihari_id) }}"
                                                        data-bs-toggle="tooltip" title="Open full profile">
                                                        <img src="{{ $photoSrc }}" alt="Profile Photo"
                                                            class="rounded-circle border"
                                                            style="width:56px;height:56px;object-fit:cover;">
                                                    </a>
                                                </td>

                                                <td>
                                                    <a href="{{ route('admin.viewProfile', $profile->pratihari_id) }}"
                                                        class="btn btn-sm btn-dark">
                                                        <i class="fa-solid fa-eye me-1"></i>View
                                                    </a>
                                                </td>

                                                <td class="fw-semibold">{{ $fullName ?: 'N/A' }}</td>
                                                <td class="text-nowrap">{{ $profile->phone_no ?: 'N/A' }}</td>

                                                <td>
                                                    <button class="btn btn-outline-info btn-sm view-address"
                                                        data-bs-toggle="modal" data-bs-target="#addressModal"
                                                        data-address="{{ optional($profile->address)->address ?? 'N/A' }}"
                                                        data-district="{{ optional($profile->address)->district ?? 'N/A' }}"
                                                        data-sahi="{{ optional($profile->address)->sahi ?? 'N/A' }}"
                                                        data-state="{{ optional($profile->address)->state ?? 'N/A' }}"
                                                        data-country="{{ optional($profile->address)->country ?? 'N/A' }}"
                                                        data-pincode="{{ optional($profile->address)->pincode ?? 'N/A' }}"
                                                        data-landmark="{{ optional($profile->address)->landmark ?? 'N/A' }}"
                                                        data-police-station="{{ optional($profile->address)->police_station ?? 'N/A' }}">
                                                        <i class="fa-solid fa-map-location-dot me-1"></i>View
                                                    </button>
                                                </td>

                                                <td>{{ optional($profile->occupation)->occupation_type ?? 'N/A' }}</td>
                                                <td class="text-nowrap">{{ $profile->healthcard_no ?: 'N/A' }}</td>

                                                <td class="text-nowrap">
                                                    @switch($status)
                                                        @case('approved')
                                                            <span class="badge text-bg-success"><i
                                                                    class="fa-solid fa-check-circle me-1"></i>Approved</span>
                                                        @break

                                                        @case('rejected')
                                                            <span class="badge text-bg-danger"><i
                                                                    class="fa-solid fa-xmark-circle me-1"></i>Rejected</span>
                                                        @break

                                                        @case('updated')
                                                            <span class="badge text-bg-warning text-dark"><i
                                                                    class="fa-solid fa-pen-to-square me-1"></i>Updated</span>
                                                        @break

                                                        @default
                                                            <span class="badge text-bg-secondary"><i
                                                                    class="fa-regular fa-hourglass-half me-1"></i>Pending</span>
                                                    @endswitch
                                                </td>

                                                <td class="text-nowrap">
                                                    @if ($status === 'updated')
                                                        <div class="d-flex gap-2 flex-wrap">
                                                            <button class="btn btn-success btn-sm approve-btn"
                                                                data-id="{{ $profile->id }}">
                                                                <i class="fa-solid fa-thumbs-up me-1"></i>Approve
                                                            </button>
                                                            <button class="btn btn-danger btn-sm reject-btn"
                                                                data-id="{{ $profile->id }}">
                                                                <i class="fa-solid fa-thumbs-down me-1"></i>Reject
                                                            </button>
                                                        </div>
                                                    @else
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <a class="btn btn-outline-primary"
                                                                href="{{ route('admin.viewProfile', $profile->pratihari_id) }}">
                                                                <i class="fa-regular fa-id-card me-1"></i>Details
                                                            </a>

                                                            @if ($status === 'rejected' && !empty($profile->reject_reason))
                                                                <button type="button"
                                                                    class="btn btn-outline-danger show-reject-reason"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#rejectReasonModal"
                                                                    data-reason="{{ $profile->reject_reason }}">
                                                                    <i class="fa-solid fa-circle-info me-1"></i>Reason
                                                                </button>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <!-- Address Modal -->
        <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-address-card me-2"></i>Pratihari Address Details
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <th class="w-25"><i class="fas fa-map-marker-alt me-1"></i> Address</th>
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
                                        <th><i class="fas fa-building me-1"></i> Police Station</th>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reject Reason Modal -->
        <div class="modal fade" id="rejectReasonModal" tabindex="-1" aria-labelledby="rejectReasonModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle me-2"></i>Reject Reason
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="reject-reason-text"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery + Bootstrap + SweetAlert2 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Enable tooltips
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

        // Fill Address modal
        document.querySelectorAll(".view-address").forEach(button => {
            button.addEventListener("click", function() {
                const get = (k) => this.getAttribute(k) || 'N/A';
                document.getElementById("modal-address").textContent = get("data-address");
                document.getElementById("modal-district").textContent = get("data-district");
                document.getElementById("modal-sahi").textContent = get("data-sahi");
                document.getElementById("modal-state").textContent = get("data-state");
                document.getElementById("modal-country").textContent = get("data-country");
                document.getElementById("modal-pincode").textContent = get("data-pincode");
                document.getElementById("modal-landmark").textContent = get("data-landmark");
                document.getElementById("modal-police-station").textContent = get("data-police-station");
            });
        });

        // Fill Reject Reason modal
        document.querySelectorAll('.show-reject-reason').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('reject-reason-text').textContent = this.getAttribute(
                    'data-reason') || 'No reason provided.';
            });
        });

        // Flash toast from session
        (function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}"
                });
            @endif
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "{{ session('error') }}"
                });
            @endif
        })();

        // Client-side search & status filter
        (function() {
            const tableBody = document.querySelector('#profiles-table tbody');
            if (!tableBody) return;

            const rows = Array.from(tableBody.querySelectorAll('tr'));
            const searchBox = document.getElementById('searchBox');
            const statusInput = document.getElementById('statusFilter');

            const norm = (v) => (v || '').toString().toLowerCase().trim();

            function apply() {
                const q = norm(searchBox.value);
                const st = norm(statusInput.value);

                rows.forEach(tr => {
                    const tds = tr.querySelectorAll('td');
                    const name = norm(tds[3]?.textContent);
                    const phone = norm(tds[4]?.textContent);
                    const rowSt = norm(tr.getAttribute('data-status'));

                    const okText = !q || name.includes(q) || phone.includes(q);
                    const okSt = !st || rowSt === st;

                    tr.style.display = (okText && okSt) ? '' : 'none';
                });
            }

            searchBox.addEventListener('input', apply);
            statusInput.addEventListener('change', apply);
        })();

        // Ajax approve / reject
        $(function() {
            const csrf = "{{ csrf_token() }}";

            $('.approve-btn').on('click', function() {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Approve this profile?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, approve'
                }).then((r) => {
                    if (r.isConfirmed) {
                        Swal.showLoading();
                        $.post('/admin/pratihari/approve/' + id, {
                                _token: csrf
                            })
                            .done(resp => Swal.fire('Approved!', resp.message ||
                                'Profile approved.', 'success').then(() => location.reload()))
                            .fail(() => Swal.fire('Error', 'Approval failed. Try again.', 'error'));
                    }
                });
            });

            $('.reject-btn').on('click', function() {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Reject Profile',
                    input: 'textarea',
                    inputLabel: 'Reason for rejection',
                    inputPlaceholder: 'Type your reason here…',
                    showCancelButton: true,
                    confirmButtonText: 'Reject',
                    preConfirm: (reason) => {
                        if (!reason) {
                            Swal.showValidationMessage('Reject reason is required');
                        }
                        return reason;
                    }
                }).then((r) => {
                    if (r.isConfirmed) {
                        Swal.showLoading();
                        $.post('/admin/pratihari/reject/' + id, {
                                _token: csrf,
                                reason: r.value
                            })
                            .done(resp => Swal.fire('Rejected!', resp.message ||
                                'Profile rejected.', 'success').then(() => location.reload()))
                            .fail(() => Swal.fire('Error', 'Rejection failed. Try again.',
                            'error'));
                    }
                });
            });
        });
    </script>
@endsection
