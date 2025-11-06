@extends('layouts.app')

@section('styles')
    <!-- Bootstrap 5 + Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Select2 -->
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <style>
        :root{
            --brand-a:#7c3aed; /* violet */
            --brand-b:#06b6d4; /* cyan   */
            --ink:#0b1220;
            --muted:#64748b;
            --border:rgba(2,6,23,.10);
            --ring:rgba(6,182,212,.28);
        }
        /* Page header */
        .page-header{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff;border-radius:1rem;padding:1rem 1.25rem;
            box-shadow:0 10px 24px rgba(6,182,212,.18);
        }
        .page-header .title{font-weight:800;letter-spacing:.3px;}
        .card{ border:1px solid var(--border); border-radius:14px; box-shadow:0 8px 22px rgba(2,6,23,.06); }
        .card-header{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff; font-weight:800; letter-spacing:.3px; text-transform:uppercase;
            border-radius:14px 14px 0 0; display:flex; align-items:center; gap:.6rem;
        }

        /* Input groups */
        .input-icon{ width:44px; display:inline-flex; align-items:center; justify-content:center; }
        .form-text.muted{ color:var(--muted); }

        /* Select2 → Bootstrap look */
        .select2-container .select2-selection--single{
            height: 44px; padding:.5rem .75rem; border:1px solid #ced4da; border-radius:.375rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered{ line-height: 28px; }
        .select2-container--default .select2-selection--single .select2-selection__arrow{ height: 42px; right: 6px; }

        /* Buttons */
        .btn-brand{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            border:0;color:#fff;font-weight:800;border-radius:10px;
            box-shadow:0 12px 24px rgba(124,58,237,.22);
        }
        .btn-brand:hover{ opacity:.96; }

        /* Focus ring */
        :focus-visible{ outline:2px solid transparent; box-shadow:0 0 0 3px var(--ring) !important; border-radius:10px; }
    </style>
@endsection

@section('content')
<div class="container my-4">
    <!-- Header -->
    <div class="page-header mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <div class="title h4 mb-0"><i class="fa-solid fa-user-tie me-2"></i>Add Designation</div>
                <div class="small opacity-75">Assign a designation to a Pratihari for a specific academic year.</div>
            </div>
            <a href="{{ url('admin/manage-designation') }}" class="btn btn-light">
                <i class="fa-solid fa-list-ul me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-regular fa-circle-check me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-regular fa-triangle-exclamation me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Card --}}
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-pen-to-square"></i> Designation Details
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.designation.store') }}" method="POST" novalidate>
                        @csrf

                        {{-- Year --}}
                        <div class="mb-3">
                            <label for="year" class="form-label fw-bold">Year</label>
                            <div class="input-group">
                                <span class="input-group-text input-icon"><i class="fa-regular fa-calendar-check text-primary"></i></span>
                                <select class="form-select @error('year') is-invalid @enderror" id="year" name="year" required>
                                    <option value="">Select Year</option>
                                    @for ($y = date('Y'); $y >= 2000; $y--)
                                        @php $display = $y . '-' . ($y + 1); @endphp
                                        <option value="{{ $display }}" {{ old('year') == $display ? 'selected' : '' }}>
                                            {{ $display }}
                                        </option>
                                    @endfor
                                </select>
                                @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-text muted">Example: 2025-2026</div>
                        </div>

                        {{-- Pratihari --}}
                        <div class="mb-3">
                            <label for="pratihari_id" class="form-label fw-bold">Pratihari</label>
                            <div class="input-group">
                                <span class="input-group-text input-icon"><i class="fa-regular fa-id-badge text-primary"></i></span>
                                <select class="form-select select2 @error('pratihari_id') is-invalid @enderror" id="pratihari_id" name="pratihari_id" required style="width:100%;">
                                    <option value="">Select Pratihari</option>
                                    @foreach ($profiles as $pratihari)
                                        <option value="{{ $pratihari->pratihari_id }}" {{ old('pratihari_id') == $pratihari->pratihari_id ? 'selected' : '' }}>
                                            {{ trim($pratihari->first_name.' '.($pratihari->middle_name ?? '').' '.$pratihari->last_name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pratihari_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-text muted">Type to search by name.</div>
                        </div>

                        {{-- Designation --}}
                        <div class="mb-3">
                            <label for="designation" class="form-label fw-bold">Designation</label>
                            <div class="input-group">
                                <span class="input-group-text input-icon"><i class="fa-solid fa-briefcase text-success"></i></span>
                                <input type="text"
                                       class="form-control @error('designation') is-invalid @enderror"
                                       id="designation" name="designation"
                                       value="{{ old('designation') }}"
                                       placeholder="Enter Designation" required>
                                @error('designation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-brand btn-lg">
                                <i class="fa-regular fa-floppy-disk me-2"></i>Add Designation
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-3 small text-muted">
                Need a new Pratihari profile? <a href="{{ url('admin/pratihari/create') }}">Create here</a>.
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- jQuery (before Select2), Bootstrap bundle, Select2 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        $(function () {
            // Select2 init
            $('#pratihari_id').select2({
                placeholder: 'Select Pratihari',
                allowClear: true,
                width: '100%'
            });

            // Auto-hide alerts
            setTimeout(() => $('.alert').alert('close'), 3500);
        });
    </script>
@endsection
