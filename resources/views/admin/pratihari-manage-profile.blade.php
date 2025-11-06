@extends('layouts.app')

@section('styles')
    <!-- Bootstrap 5.3 + Font Awesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root{
            /* Brand palette (same across pages) */
            --brand-a:#7c3aed; /* violet */
            --brand-b:#06b6d4; /* cyan   */
            --brand-c:#22c55e; /* emerald */
            --ink:#0b1220;
            --muted:#64748b;
            --border:rgba(2,6,23,.10);
            --ring:rgba(6,182,212,.28);
            --amber:#f5c12e;
        }

        /* Page header */
        .page-header{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff;border-radius:1rem;padding:1.05rem 1.25rem;
            box-shadow:0 10px 24px rgba(6,182,212,.18);
        }
        .page-header .title{font-weight:800;letter-spacing:.3px;}

        /* Card */
        .card{border:1px solid var(--border);border-radius:1rem;}

        /* Table */
        .table-wrap{border:1px solid var(--border);border-radius:0.75rem;overflow:auto;}
        table.table{
            margin-bottom:0;
        }
        thead th{
            position:sticky; top:0; z-index:2;
            background:#f8fafc; color:var(--ink); font-weight:800;
            border-bottom:1px solid var(--border);
        }
        tbody tr:hover{background:#f9fbff;}
        td, th{vertical-align:middle;}

        /* Photo preview */
        .profile-photo{
            width:56px;height:56px;border-radius:10px;object-fit:cover;
            transition:transform .25s ease, box-shadow .25s ease;
        }
        .profile-photo:hover{
            transform:scale(2.6);
            box-shadow:0 8px 16px rgba(0,0,0,.25);
            z-index:5;
        }

        /* Badges for status */
        .badge-soft{
            font-weight:700;border:1px solid var(--border);background:#f8fafc;color:var(--muted);
        }
        .badge-approved{background:#ecfdf5;color:#065f46;border-color:#10b981;}
        .badge-rejected{background:#fef2f2;color:#991b1b;border-color:#ef4444;}
        .badge-pending{background:#fff7ed;color:#9a3412;border-color:#f59e0b;}

        /* Buttons */
        .btn-amber{
            background-color:var(--amber); color:#1f2937; border:0;
        }
        .btn-amber:hover{filter:brightness(.95);}
        .btn-brand{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            border:0;color:#fff;box-shadow:0 14px 30px rgba(124,58,237,.25);
        }
        .btn-brand:hover{opacity:.96;}
        .btn-slim{padding:.35rem .6rem;font-weight:700;border-radius:.5rem;}

        /* Little chips for section headings */
        .chip{
            display:inline-flex;align-items:center;justify-content:center;
            width:38px;height:38px;border-radius:10px;
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff;box-shadow:0 6px 16px rgba(2,6,23,.12);
        }

        /* Modal */
        .modal-header{background:#f8fafc;border-bottom:1px solid var(--border);}
        .modal-title{font-weight:800;color:var(--ink);}

        /* Accessibility focus */
        :focus-visible{outline:2px solid transparent;box-shadow:0 0 0 3px var(--ring) !important;border-radius:10px;}
    </style>
@endsection

@section('content')
<div class="container-fluid my-3">
    <!-- Header -->
    <div class="page-header mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="chip"><i class="fa-solid fa-user-gear"></i></span>
                <div>
                    <div class="title h4 mb-0">Pratihari • Manage Profiles</div>
                    <div class="small opacity-75">Browse, view details, and approve/reject submissions.</div>
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
                    <tbody>
                        @foreach ($profiles as $index => $profile)
                            <tr>
                                <td class="fw-bold">{{ $index + 1 }}</td>

                                <td>
                                    <a href="{{ route('admin.viewProfile', $profile->pratihari_id) }}" class="text-decoration-none">
                                        <img src="{{ asset($profile->profile_photo) }}" class="profile-photo" alt="Profile Photo">
                                    </a>
                                </td>

                                <td>
                                    <a href="{{ route('admin.viewProfile', $profile->pratihari_id) }}" class="btn btn-slim btn-amber">
                                        <i class="fa-regular fa-eye me-1"></i> View
                                    </a>
                                </td>

                                <td class="text-truncate" style="max-width:220px;">
                                    {{ $profile->first_name }} {{ $profile->middle_name }} {{ $profile->last_name }}
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
                                        <i class="fa-solid fa-map-location-dot me-1"></i> Details
                                    </button>
                                </td>

                                <td>{{ $profile->occupation->occupation_type ?? 'N/A' }}</td>
                                <td>{{ $profile->healthcard_no }}</td>

                                <td>
                                    @php
                                        $ps = $profile->pratihari_status;
                                    @endphp
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
                                            <button class="btn btn-success btn-slim approve-btn" data-id="{{ $profile->id }}">
                                                <i class="fa-solid fa-check me-1"></i> Approve
                                            </button>
                                            <button class="btn btn-outline-danger btn-slim reject-btn" data-id="{{ $profile->id }}">
                                                <i class="fa-solid fa-ban me-1"></i> Reject
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if($profiles->isEmpty())
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
    <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-address-card me-2"></i>Pratihari Address Details
                    </h5>
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
        Swal.fire({ icon:'success', title:'Success!', text:@json(session('success')), confirmButtonColor:'#0ea5e9' });
    </script>
    @endif
    @if (session('error'))
    <script>
        Swal.fire({ icon:'error', title:'Error!', text:@json(session('error')), confirmButtonColor:'#ef4444' });
    </script>
    @endif

    <script>
        // Address modal data binding
        document.querySelectorAll(".view-address").forEach(btn=>{
            btn.addEventListener("click", function(){
                document.getElementById("modal-address").textContent        = this.getAttribute("data-address");
                document.getElementById("modal-district").textContent       = this.getAttribute("data-district");
                document.getElementById("modal-sahi").textContent           = this.getAttribute("data-sahi");
                document.getElementById("modal-state").textContent          = this.getAttribute("data-state");
                document.getElementById("modal-country").textContent        = this.getAttribute("data-country");
                document.getElementById("modal-pincode").textContent        = this.getAttribute("data-pincode");
                document.getElementById("modal-landmark").textContent       = this.getAttribute("data-landmark");
                document.getElementById("modal-police-station").textContent = this.getAttribute("data-police-station");
            });
        });

        // Prevent double submits on Approve/Reject
        function lockButtons(row, locked=true){
            row.querySelectorAll('.approve-btn, .reject-btn').forEach(b=> b.disabled = locked);
        }

        $(function(){
            $('.approve-btn').on('click', function(){
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
                        lockButtons(row,true);
                        $.ajax({
                            url: '/admin/pratihari/approve/' + profileId,
                            method: 'POST',
                            data: { _token: "{{ csrf_token() }}" },
                            success: function(resp){
                                Swal.fire('Approved!', resp.message || 'Profile approved.', 'success')
                                    .then(()=> location.reload());
                            },
                            error: function(){
                                lockButtons(row,false);
                                Swal.fire('Error', 'Could not approve. Please try again.', 'error');
                            }
                        });
                    }
                });
            });

            $('.reject-btn').on('click', function(){
                const $btn = $(this);
                const profileId = $btn.data('id');
                const row = $btn.closest('tr')[0];

                Swal.fire({
                    title: 'Reject Profile',
                    input: 'textarea',
                    inputLabel: 'Reason for rejection',
                    inputPlaceholder: 'Type your reason here...',
                    inputAttributes: { 'aria-label': 'Reject reason' },
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
                        lockButtons(row,true);
                        $.ajax({
                            url: '/admin/pratihari/reject/' + profileId,
                            method: 'POST',
                            data: { _token: "{{ csrf_token() }}", reason: result.value },
                            success: function(resp){
                                Swal.fire('Rejected!', resp.message || 'Profile rejected.', 'success')
                                    .then(()=> location.reload());
                            },
                            error: function(){
                                lockButtons(row,false);
                                Swal.fire('Error', 'Could not reject. Please try again.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
