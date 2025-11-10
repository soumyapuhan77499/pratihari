@extends('layouts.app')

@section('styles')
    <!-- Bootstrap 5 + Font Awesome (icons will now render) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- (Optional) Select2 – keep if you use it site-wide -->
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

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

        .page-header {
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            color: #fff;
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            box-shadow: 0 10px 24px rgba(6, 182, 212, .18);
        }

        .page-header .title {
            font-weight: 800;
            letter-spacing: .3px;
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
            text-transform: uppercase;
            border-radius: 14px 14px 0 0;
            display: flex;
            align-items: center;
            gap: .6rem;
            justify-content: center;
        }

        .input-group-text {
            background: #fff;
        }

        .form-label {
            font-weight: 700;
            color: var(--ink);
        }

        .text-hint {
            color: var(--muted);
            font-size: .9rem;
        }

        .custom-gradient-btn {
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            border: 0;
            color: #fff;
            font-weight: 800;
            border-radius: 10px;
            box-shadow: 0 12px 24px rgba(124, 58, 237, .22);
        }

        .custom-gradient-btn:hover {
            opacity: .96;
        }

        /* Flash fade */
        .fade-out {
            animation: fadeout .5s ease-in-out forwards;
        }

        @keyframes fadeout {
            to {
                opacity: 0;
                height: 0;
                margin: 0;
                padding: 0;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-header mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <div class="title h4 mb-0"><i class="fa-solid fa-bullhorn me-2"></i>Add Notice</div>
                <div class="text-hint">Create a notice with dates and a description. Dates must be valid.</div>
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
                            <div class="col-md-4">
                                <label for="notice_name" class="form-label">Notice Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-pen"></i></span>
                                    <input type="text" class="form-control @error('notice_name') is-invalid @enderror"
                                        id="notice_name" name="notice_name" required maxlength="150"
                                        value="{{ old('notice_name') }}" placeholder="Enter Notice Name">
                                    @error('notice_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="from_date" class="form-label">From Date</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                                    <input type="date" class="form-control @error('from_date') is-invalid @enderror"
                                        id="from_date" name="from_date" value="{{ old('from_date') }}" required>
                                    @error('from_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="to_date" class="form-label">To Date</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-regular fa-calendar-days"></i></span>
                                    <input type="date" class="form-control @error('to_date') is-invalid @enderror"
                                        id="to_date" name="to_date" value="{{ old('to_date') }}" required>
                                    @error('to_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- NEW: Notice Photo -->
                            <div class="col-md-6">
                                <label for="notice_photo" class="form-label">Notice Photo <span
                                        class="text-hint">(optional)</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-regular fa-image"></i></span>
                                    <input type="file" class="form-control @error('notice_photo') is-invalid @enderror"
                                        id="notice_photo" name="notice_photo" accept="image/*">
                                    @error('notice_photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Accepted: JPG, PNG, WebP; max 2MB.</small>
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label">Description <span
                                        class="text-hint">(optional)</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-align-left"></i></span>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="4" placeholder="Enter Description">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-lg mt-2 px-5 custom-gradient-btn">
                                    <i class="fa-regular fa-floppy-disk me-2"></i>Submit
                                </button>
                            </div>
                        </div><!-- /row -->
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery (if needed elsewhere) + Bootstrap 5 bundle -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Select2 (optional, kept once to avoid duplicates) -->
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script>
        // Flash messages auto-hide
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(() => document.getElementById("successMessage")?.classList.add("fade-out"), 3000);
            setTimeout(() => document.getElementById("errorMessage")?.classList.add("fade-out"), 3000);
        });

        // Date guard: to_date cannot be before from_date
        (function() {
            const from = document.getElementById('from_date');
            const to = document.getElementById('to_date');

            function syncMin() {
                if (!from.value) {
                    to.min = '';
                    return;
                }
                to.min = from.value;
                if (to.value && to.value < from.value) {
                    to.value = from.value;
                }
            }
            from.addEventListener('change', syncMin);
            document.addEventListener('DOMContentLoaded', syncMin);
        })();
    </script>
@endsection
