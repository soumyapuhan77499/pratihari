@extends('layouts.app')

@section('styles')
    <!-- Bootstrap 5 + Font Awesome 6 (match other pages) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            /* Brand palette (same across pages) */
            --brand-a: #7c3aed;
            /* violet */
            --brand-b: #06b6d4;
            /* cyan   */
            --brand-c: #22c55e;
            /* emerald */
            --ink: #0b1220;
            --muted: #64748b;
            --border: rgba(2, 6, 23, .10);
            --ring: rgba(6, 182, 212, .28);
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

        /* Tabbar */
        .tabbar {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 8px 22px rgba(2, 6, 23, .06);
            padding: .35rem;
            overflow: auto;
            scrollbar-width: thin;
        }

        .tabbar .nav {
            flex-wrap: nowrap;
            gap: .35rem;
        }

        .tabbar .nav-link {
            display: flex;
            align-items: center;
            gap: .55rem;
            border: 1px solid transparent;
            background: #f8fafc;
            color: var(--muted);
            border-radius: 11px;
            padding: .55rem .9rem;
            font-weight: 700;
            white-space: nowrap;
            transition: transform .12s ease, background .2s ease, color .2s ease, border-color .2s ease;
        }

        .tabbar .nav-link:hover {
            background: #eef2ff;
            color: var(--ink);
            transform: translateY(-1px);
            border-color: rgba(124, 58, 237, .25);
        }

        .tabbar .nav-link.active {
            color: #fff !important;
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            border-color: transparent;
            box-shadow: 0 10px 18px rgba(124, 58, 237, .25);
        }

        .tabbar .nav-link i {
            font-size: .95rem;
        }

        .tabbar::-webkit-scrollbar {
            height: 8px;
        }

        .tabbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 8px;
        }

        .tabbar::-webkit-scrollbar-track {
            background: transparent;
        }

        /* Card & sections */
        .card {
            border: 1px solid var(--border);
            border-radius: 1rem;
        }

        .section-title {
            font-weight: 800;
            color: var(--ink);
        }

        .section-hint {
            color: var(--muted);
            font-size: .9rem;
        }

        /* Underline inputs */
        .underline-group {
            display: flex;
            align-items: center;
            gap: .6rem;
            border-bottom: 2px solid var(--border);
            padding-bottom: .25rem;
            background: transparent;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .underline-group:focus-within {
            border-bottom-color: var(--brand-b);
            box-shadow: 0 6px 0 -5px var(--ring);
        }

        .form-label {
            font-weight: 600;
            margin-bottom: .35rem;
        }

        .form-control {
            border: 0 !important;
            border-radius: 0 !important;
            background: transparent !important;
            padding: .45rem 0 .25rem 0;
            height: auto;
            box-shadow: none !important;
            color: var(--ink);
        }

        .form-control::placeholder {
            color: #9aa4b2;
        }

        .form-control:focus {
            outline: none;
        }

        /* Icon chips */
        .chips {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            min-width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            color: #fff;
            flex: 0 0 40px;
            box-shadow: 0 6px 16px rgba(2, 6, 23, .12);
        }

        /* Buttons */
        .btn-brand {
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            border: 0;
            color: #fff !important;
            box-shadow: 0 14px 30px rgba(124, 58, 237, .25);
        }

        .btn-brand:hover {
            opacity: .96;
        }

        .btn-brand:disabled {
            opacity: .6;
            box-shadow: none;
            cursor: not-allowed;
        }

        /* Accessibility focus */
        :focus-visible {
            outline: 2px solid transparent;
            box-shadow: 0 0 0 3px var(--ring) !important;
            border-radius: 10px;
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition: none !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid my-3">
        <!-- Header -->
        <div class="page-header mb-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <div class="title h4 mb-0">Pratihari • Social Media</div>
                    <div class="small opacity-75">Add your public profile links with the same look & feel as other pages.
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs (Social active) -->
        <div class="tabbar mb-3">
            <ul class="nav" id="profileTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link" id="tab-profile" data-bs-toggle="tab" data-bs-target="#pane-profile"
                        type="button" role="tab" aria-controls="pane-profile" aria-selected="false">
                        <i class="fa-solid fa-user"></i> Profile
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-family" data-bs-toggle="tab" data-bs-target="#pane-family"
                        type="button" role="tab" aria-controls="pane-family" aria-selected="false">
                        <i class="fa-solid fa-users"></i> Family
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-id" data-bs-toggle="tab" data-bs-target="#pane-id" type="button"
                        role="tab" aria-controls="pane-id" aria-selected="false">
                        <i class="fa-solid fa-id-card"></i> ID Card
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-address" data-bs-toggle="tab" data-bs-target="#pane-address"
                        type="button" role="tab" aria-controls="pane-address" aria-selected="false">
                        <i class="fa-solid fa-location-dot"></i> Address
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-occupation" data-bs-toggle="tab" data-bs-target="#pane-occupation"
                        type="button" role="tab" aria-controls="pane-occupation" aria-selected="false">
                        <i class="fa-solid fa-briefcase"></i> Occupation
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-seba" data-bs-toggle="tab" data-bs-target="#pane-seba" type="button"
                        role="tab" aria-controls="pane-seba" aria-selected="false">
                        <i class="fa-solid fa-gears"></i> Seba
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link active" id="tab-social" data-bs-toggle="tab" data-bs-target="#pane-social"
                        type="button" role="tab" aria-controls="pane-social" aria-selected="true">
                        <i class="fa-solid fa-share-nodes"></i> Social Media
                    </button>
                </li>
            </ul>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.social-media.store') }}" method="POST" onsubmit="return validateForm()"
                    novalidate>
                    @csrf
                    <input type="hidden" name="pratihari_id" value="{{ request('pratihari_id') }}">

                    <div class="tab-content" id="tabsContent">
                        <!-- placeholder panes to keep structure consistent -->
                        <div class="tab-pane fade" id="pane-profile" role="tabpanel" aria-labelledby="tab-profile">
                            <div class="text-muted">Profile section is managed on the Profile tab.</div>
                        </div>
                        <div class="tab-pane fade" id="pane-family" role="tabpanel" aria-labelledby="tab-family">
                            <div class="text-muted">Family section is managed on the Family tab.</div>
                        </div>
                        <div class="tab-pane fade" id="pane-id" role="tabpanel" aria-labelledby="tab-id">
                            <div class="text-muted">ID Card section is managed on the ID Card tab.</div>
                        </div>
                        <div class="tab-pane fade" id="pane-address" role="tabpanel" aria-labelledby="tab-address">
                            <div class="text-muted">Address section is managed on the Address tab.</div>
                        </div>
                        <div class="tab-pane fade" id="pane-occupation" role="tabpanel"
                            aria-labelledby="tab-occupation">
                            <div class="text-muted">Occupation section is managed on the Occupation tab.</div>
                        </div>
                        <div class="tab-pane fade" id="pane-seba" role="tabpanel" aria-labelledby="tab-seba">
                            <div class="text-muted">Seba section is managed on the Seba tab.</div>
                        </div>

                        <!-- SOCIAL (active) -->
                        <div class="tab-pane fade show active" id="pane-social" role="tabpanel"
                            aria-labelledby="tab-social">
                    
                            <div class="row g-3">
                                <!-- Facebook -->
                                <div class="col-md-6">
                                    <label class="form-label" for="facebook">Facebook</label>
                                    <div class="underline-group">
                                        <span class="chips"><i class="fab fa-facebook-f"></i></span>
                                        <input type="text" name="facebook" id="facebook" class="form-control"
                                            placeholder="https://facebook.com/..." value="{{ old('facebook') }}">
                                    </div>
                                    <small id="facebook_error" class="text-danger"></small>
                                </div>

                                <!-- X (Twitter) -->
                                <div class="col-md-6">
                                    <label class="form-label" for="twitter">Twitter / X</label>
                                    <div class="underline-group">
                                        <span class="chips"><i class="fab fa-x-twitter"></i></span>
                                        <input type="text" name="twitter" id="twitter" class="form-control"
                                            placeholder="https://x.com/... or https://twitter.com/..."
                                            value="{{ old('twitter') }}">
                                    </div>
                                    <small id="twitter_error" class="text-danger"></small>
                                </div>

                                <!-- Instagram -->
                                <div class="col-md-6">
                                    <label class="form-label" for="instagram">Instagram</label>
                                    <div class="underline-group">
                                        <span class="chips"><i class="fab fa-instagram"></i></span>
                                        <input type="text" name="instagram" id="instagram" class="form-control"
                                            placeholder="https://instagram.com/..." value="{{ old('instagram') }}">
                                    </div>
                                    <small id="instagram_error" class="text-danger"></small>
                                </div>

                                <!-- LinkedIn -->
                                <div class="col-md-6">
                                    <label class="form-label" for="linkedin">LinkedIn</label>
                                    <div class="underline-group">
                                        <span class="chips"><i class="fab fa-linkedin-in"></i></span>
                                        <input type="text" name="linkedin" id="linkedin" class="form-control"
                                            placeholder="https://linkedin.com/in/... or /company/..."
                                            value="{{ old('linkedin') }}">
                                    </div>
                                    <small id="linkedin_error" class="text-danger"></small>
                                </div>

                                <!-- YouTube -->
                                <div class="col-md-6">
                                    <label class="form-label" for="youtube">YouTube</label>
                                    <div class="underline-group">
                                        <span class="chips"><i class="fab fa-youtube"></i></span>
                                        <input type="text" name="youtube" id="youtube" class="form-control"
                                            placeholder="https://youtube.com/@handle or /channel/..."
                                            value="{{ old('youtube') }}">
                                    </div>
                                    <small id="youtube_error" class="text-danger"></small>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-lg px-5 btn-brand">
                                    <i class="fa-regular fa-floppy-disk me-2"></i>Submit
                                </button>
                            </div>
                        </div>
                    </div><!-- /tab-content -->
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- SweetAlert (flash) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    <!-- Bootstrap 5.3 bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Simple URL validation
        function validateForm() {
            let isValid = true;
            const urlPattern = /^(https?:\/\/)([\w-]+\.)+[\w-]{2,}(\/\S*)?$/i;

            const fields = ["facebook", "twitter", "instagram", "linkedin", "youtube"];
            fields.forEach((field) => {
                const el = document.getElementById(field);
                const err = document.getElementById(field + "_error");
                const val = (el?.value || "").trim();
                if (val && !urlPattern.test(val)) {
                    err.textContent = "Invalid URL format. Please include https://";
                    isValid = false;
                } else {
                    err.textContent = "";
                }
            });

            return isValid;
        }
    </script>
@endsection
