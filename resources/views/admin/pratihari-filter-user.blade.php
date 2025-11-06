@extends('layouts.app')

@section('styles')
    <!-- One Bootstrap & One Font Awesome (consistent across app) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

    <style>
        :root{
            --brand-a:#7c3aed; /* violet  */
            --brand-b:#06b6d4; /* cyan    */
            --ink:#0b1220;
            --muted:#64748b;
            --border:rgba(2,6,23,.10);
            --soft:#f8fafc;
        }

        .card{ border:1px solid var(--border); border-radius:14px; box-shadow:0 8px 22px rgba(2,6,23,.06); }

        /* NEW: unified colorful header (same as other pages) */
        .card-header{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff; font-weight:800; letter-spacing:.3px; text-transform:uppercase;
            border-radius:14px 14px 0 0; display:flex; align-items:center; justify-content:space-between;
            padding:.9rem 1rem; text-shadow:0 1px 2px rgba(0,0,0,.25);
        }
        .card-header .title{display:flex; align-items:center; gap:.6rem;}
        .card-header .title i{opacity:.95;}
        .subhint{font-size:.85rem; opacity:.9; font-weight:600;}

        .table thead th{ white-space:nowrap; }
        .table td, .table th{ vertical-align:middle; }
        .table-hover>tbody>tr:hover{ background:rgba(2,6,23,.03); }

        .profile-photo{
            width:60px; height:60px; border-radius:50%;
            object-fit:cover; transition: transform .18s ease, box-shadow .18s ease;
            cursor: zoom-in;
        }
        .profile-photo:hover{ transform: scale(1.12); box-shadow:0 6px 18px rgba(2,6,23,.18); }

        .btn-icon{ display:inline-flex; align-items:center; gap:.35rem; }

        .badge-status{ font-size:.78rem; padding:.45rem .6rem; border-radius:999px; }

        /* Address modal table */
        #addressModal table th{ width:220px; }

        /* Tiny header legend */
        .legend{ display:flex; gap:.4rem; flex-wrap:wrap; }
        .legend .badge{ font-weight:700; }
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-12 mt-2">
        <div class="card">
            <div class="card-header">
                <div class="title">
                    <i class="fas fa-user-circle"></i>
                    <span>Pratihari • Manage Profile</span>
                </div>
                <div class="legend">
                    <span class="badge bg-success badge-status"><i class="fa-solid fa-check-circle me-1"></i>Approved</span>
                    <span class="badge bg-warning text-dark badge-status"><i class="fa-solid fa-pen-to-square me-1"></i>Updated</span>
                    <span class="badge bg-secondary badge-status"><i class="fa-regular fa-hourglass-half me-1"></i>Pending</span>
                    <span class="badge bg-danger badge-status"><i class="fa-solid fa-xmark-circle me-1"></i>Rejected</span>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="file-datatable" class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>View</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Occupation</th>
                                <th>Health Card No</th>
                                <th>Status</th>
                                <th style="min-width:220px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($profiles as $index => $profile)
                                @php
                                    $fullName = trim(($profile->first_name ?? '').' '.($profile->middle_name ?? '').' '.($profile->last_name ?? ''));
                                    $photoSrc = $profile->profile_photo ? asset($profile->profile_photo) : asset('assets/images/placeholder-avatar.png');
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        <a href="{{ route('admin.viewProfile', $profile->pratihari_id) }}" class="text-decoration-none" data-bs-toggle="tooltip" title="Open full profile">
                                            <img src="{{ $photoSrc }}" class="profile-photo" alt="Profile Photo">
                                        </a>
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.viewProfile', $profile->pratihari_id) }}"
                                           class="btn btn-sm btn-dark btn-icon">
                                            <i class="fa-solid fa-eye"></i> View Profile
                                        </a>
                                    </td>

                                    <td class="fw-semibold">{{ $fullName ?: 'N/A' }}</td>
                                    <td>{{ $profile->phone_no ?: 'N/A' }}</td>

                                    <td>
                                        <button class="btn btn-info btn-sm btn-icon view-address"
                                                data-bs-toggle="modal" data-bs-target="#addressModal"
                                                data-address="{{ $profile->address->address ?? 'N/A' }}"
                                                data-district="{{ $profile->address->district ?? 'N/A' }}"
                                                data-sahi="{{ $profile->address->sahi ?? 'N/A' }}"
                                                data-state="{{ $profile->address->state ?? 'N/A' }}"
                                                data-country="{{ $profile->address->country ?? 'N/A' }}"
                                                data-pincode="{{ $profile->address->pincode ?? 'N/A' }}"
                                                data-landmark="{{ $profile->address->landmark ?? 'N/A' }}"
                                                data-police-station="{{ $profile->address->police_station ?? 'N/A' }}">
                                            <i class="fas fa-map-marker-alt"></i> View
                                        </button>
                                    </td>

                                    <td>{{ $profile->occupation->occupation_type ?? 'N/A' }}</td>
                                    <td>{{ $profile->healthcard_no ?: 'N/A' }}</td>

                                    <td>
                                        @if ($profile->pratihari_status === 'approved')
                                            <span class="badge bg-success badge-status"><i class="fa-solid fa-check-circle me-1"></i>Approved</span>
                                        @elseif ($profile->pratihari_status === 'rejected')
                                            <span class="badge bg-danger badge-status"><i class="fa-solid fa-xmark-circle me-1"></i>Rejected</span>
                                        @elseif ($profile->pratihari_status === 'updated')
                                            <span class="badge bg-warning text-dark badge-status"><i class="fa-solid fa-pen-to-square me-1"></i>Updated</span>
                                        @else
                                            <span class="badge bg-secondary badge-status"><i class="fa-regular fa-hourglass-half me-1"></i>Pending</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($profile->pratihari_status === 'updated')
                                            <button class="btn btn-success btn-sm btn-icon approve-btn" data-id="{{ $profile->id }}">
                                                <i class="fa-solid fa-thumbs-up"></i> Approve
                                            </button>
                                            <button class="btn btn-danger btn-sm btn-icon reject-btn" data-id="{{ $profile->id }}">
                                                <i class="fa-solid fa-thumbs-down"></i> Reject
                                            </button>
                                        @else
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Quick actions">
                                                <a class="btn btn-outline-primary btn-icon" href="{{ route('admin.viewProfile', $profile->pratihari_id) }}">
                                                    <i class="fa-regular fa-id-card"></i> Details
                                                </a>
                                                @if ($profile->pratihari_status === 'rejected' && !empty($profile->reject_reason))
                                                    <button type="button" class="btn btn-outline-danger btn-icon show-reject-reason"
                                                            data-bs-toggle="modal" data-bs-target="#rejectReasonModal"
                                                            data-reason="{{ $profile->reject_reason }}">
                                                        <i class="fa-solid fa-circle-info"></i> Reason
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> <!-- /table-responsive -->
            </div>
        </div>
    </div>

    <!-- Address Modal -->
    <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-address-card me-2"></i>Pratihari Address Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr><th><i class="fas fa-map-marker-alt me-1"></i> Address</th><td id="modal-address">N/A</td></tr>
                            <tr><th><i class="fas fa-city me-1"></i> Sahi</th><td id="modal-sahi">N/A</td></tr>
                            <tr><th><i class="fas fa-map-pin me-1"></i> Pincode</th><td id="modal-pincode">N/A</td></tr>
                            <tr><th><i class="fas fa-road me-1"></i> Landmark</th><td id="modal-landmark">N/A</td></tr>
                            <tr><th><i class="fas fa-building me-1"></i> Police Station</th><td id="modal-police-station">N/A</td></tr>
                            <tr><th><i class="fas fa-city me-1"></i> District</th><td id="modal-district">N/A</td></tr>
                            <tr><th><i class="fas fa-flag me-1"></i> State</th><td id="modal-state">N/A</td></tr>
                            <tr><th><i class="fas fa-globe me-1"></i> Country</th><td id="modal-country">N/A</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Reason Modal (single instance) -->
    <div class="modal fade" id="rejectReasonModal" tabindex="-1" aria-labelledby="rejectReasonModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectReasonModalLabel">
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
        // Tooltips
        document.addEventListener('DOMContentLoaded', () => {
            const tt = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tt.map(el => new bootstrap.Tooltip(el));
        });

        // Address modal fill
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".view-address").forEach(button => {
                button.addEventListener("click", function() {
                    const get = (k) => this.getAttribute(k) || 'N/A';
                    document.getElementById("modal-address").textContent        = get("data-address");
                    document.getElementById("modal-district").textContent       = get("data-district");
                    document.getElementById("modal-sahi").textContent           = get("data-sahi");
                    document.getElementById("modal-state").textContent          = get("data-state");
                    document.getElementById("modal-country").textContent        = get("data-country");
                    document.getElementById("modal-pincode").textContent        = get("data-pincode");
                    document.getElementById("modal-landmark").textContent       = get("data-landmark");
                    document.getElementById("modal-police-station").textContent = get("data-police-station");
                });
            });
        });

        // Reject reason modal fill
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.show-reject-reason').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    document.getElementById('reject-reason-text').textContent = this.getAttribute('data-reason') || 'No reason provided.';
                });
            });
        });

        // Session flash toasts (optional)
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('success'))
                Swal.fire({ icon:'success', title:'Success!', text:"{{ session('success') }}", confirmButtonColor:'#3085d6' });
            @endif
            @if (session('error'))
                Swal.fire({ icon:'error', title:'Error!', text:"{{ session('error') }}", confirmButtonColor:'#d33' });
            @endif
        });

        // AJAX Approve / Reject with CSRF
        $(function () {
            const csrf = "{{ csrf_token() }}";

            $('.approve-btn').on('click', function() {
                const profileId = $(this).data('id');

                Swal.fire({
                    title: 'Approve this profile?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('/admin/pratihari/approve/' + profileId, { _token: csrf })
                         .done(resp => Swal.fire('Approved!', resp.message || 'Profile approved.', 'success').then(()=>location.reload()))
                         .fail(()  => Swal.fire('Error', 'Approval failed. Try again.', 'error'));
                    }
                });
            });

            $('.reject-btn').on('click', function() {
                const profileId = $(this).data('id');

                Swal.fire({
                    title: 'Reject Profile',
                    input: 'textarea',
                    inputLabel: 'Reason for rejection',
                    inputPlaceholder: 'Type your reason here...',
                    inputAttributes: { 'aria-label': 'Type your reason here' },
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Reject',
                    preConfirm: reason => {
                        if (!reason) { Swal.showValidationMessage('Reject reason is required'); }
                        return reason;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('/admin/pratihari/reject/' + profileId, { _token: csrf, reason: result.value })
                         .done(resp => Swal.fire('Rejected!', resp.message || 'Profile rejected.', 'success').then(()=>location.reload()))
                         .fail(()  => Swal.fire('Error', 'Rejection failed. Try again.', 'error'));
                    }
                });
            });
        });
    </script>
@endsection
