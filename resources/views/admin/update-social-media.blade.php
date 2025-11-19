@extends('layouts.app')

@section('styles')
    <!-- Single, modern Bootstrap + Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

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

        .card{ border:1px solid var(--border); border-radius:14px; box-shadow:0 8px 22px rgba(2,6,23,.06); }
        .card-header{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff; font-weight:800; letter-spacing:.3px; text-transform:uppercase;
            border-radius:14px 14px 0 0;
        }

        /* Section Nav (like app-wide tabs) */
        .tabbar{ background:#fff; border-radius:12px; padding:.4rem; box-shadow:0 6px 18px rgba(2,6,23,.06); }
        .tabbar .nav-link{
            border:1px solid transparent; background:var(--soft); color:var(--muted);
            border-radius:10px; font-weight:700; margin:.2rem; padding:.6rem .9rem;
            display:flex; align-items:center; gap:.5rem; white-space:nowrap; transition:all .18s ease;
        }
        .tabbar .nav-link:hover{ background:#eef2ff; color:var(--ink); transform:translateY(-1px); border-color:rgba(124,58,237,.25); }
        .tabbar .nav-link.active{
            color:#fff !important; background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            border-color:transparent; box-shadow:0 10px 18px rgba(124,58,237,.22);
        }

        .input-group-text{ background:#fff; border-right:0; }
        .input-group .form-control{ border-left:0; }
        .help{ font-size:.83rem; color:var(--muted); }
        .btn-brand{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            border:0; color:#fff; font-weight:800; border-radius:10px;
            box-shadow:0 12px 24px rgba(124,58,237,.22);
        }
        .btn-brand:hover{ opacity:.96 }

        /* Checkbox visual kit kept (if you add any later) */
        .checkbox-list{ border:1px solid var(--border); padding:15px; border-radius:10px; background:#f9f7f7; overflow-y:auto; }

        @media (max-width: 768px){
            .tabbar{ overflow-x:auto; white-space:nowrap; }
        }
    </style>
@endsection


@section('content')
<div class="row">
    <div class="col-12 mt-3">
        <div class="card shadow-lg">

            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    {{-- Back button on the left --}}
                    <a href="{{ route('admin.viewProfile', ['pratihari_id' => $socialMedia->pratihari_id]) }}"
                        class="btn btn-light btn-sm d-inline-flex align-items-center">
                        <i class="fa-solid fa-arrow-left me-1"></i>
                        <span>Back to Profile</span>
                    </a>

                    {{-- Title on the right / center-ish --}}
                    <div class="text-uppercase fw-bold d-flex align-items-center">
                        <i class="fa-solid fa-location-dot me-2"></i>
                        <span>Social Media Details</span>
                    </div>
                </div>

            <!-- App section nav -->
            <div class="px-3 pt-3">
                <ul class="nav tabbar flex-nowrap" role="tablist">
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.pratihariProfile') }}"><i class="fas fa-user"></i> Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.pratihariFamily') }}"><i class="fas fa-users"></i> Family</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.pratihariIdcard') }}"><i class="fas fa-id-card"></i> ID Card</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.pratihariAddress') }}"><i class="fas fa-map-marker-alt"></i> Address</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.pratihariOccupation') }}"><i class="fas fa-briefcase"></i> Occupation</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.pratihariSeba') }}"><i class="fas fa-cogs"></i> Seba</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.pratihariSocialMedia') }}"><i class="fas fa-share-alt"></i> Social Media</a></li>
                </ul>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.social-media.update', $pratihari_id) }}" method="POST" onsubmit="return validateForm()">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="pratihari_id" value="{{ $pratihari_id }}">

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="facebook_url" class="form-label">Facebook</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fab fa-facebook-f" style="color: var(--accent)"></i></span>
                                <input type="text" name="facebook_url" id="facebook_url" class="form-control"
                                       placeholder="https://www.facebook.com/username"
                                       value="{{ old('facebook_url', $socialMedia->facebook_url ?? '') }}">
                            </div>
                            <small id="facebook_url_error" class="text-danger"></small>
                        </div>

                        <div class="col-md-6">
                            <label for="twitter_url" class="form-label">Twitter / X</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fab fa-x-twitter" style="color: var(--accent)"></i></span>
                                <input type="text" name="twitter_url" id="twitter_url" class="form-control"
                                       placeholder="https://twitter.com/username"
                                       value="{{ old('twitter_url', $socialMedia->twitter_url ?? '') }}">
                            </div>
                            <small id="twitter_url_error" class="text-danger"></small>
                        </div>

                        <div class="col-md-6">
                            <label for="instagram_url" class="form-label">Instagram</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fab fa-instagram" style="color: var(--accent)"></i></span>
                                <input type="text" name="instagram_url" id="instagram_url" class="form-control"
                                       placeholder="https://instagram.com/username"
                                       value="{{ old('instagram_url', $socialMedia->instagram_url ?? '') }}">
                            </div>
                            <small id="instagram_url_error" class="text-danger"></small>
                        </div>

                        <div class="col-md-6">
                            <label for="linkedin_url" class="form-label">LinkedIn</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fab fa-linkedin-in" style="color: var(--accent)"></i></span>
                                <input type="text" name="linkedin_url" id="linkedin_url" class="form-control"
                                       placeholder="https://www.linkedin.com/in/username"
                                       value="{{ old('linkedin_url', $socialMedia->linkedin_url ?? '') }}">
                            </div>
                            <small id="linkedin_url_error" class="text-danger"></small>
                        </div>

                        <div class="col-md-6">
                            <label for="youtube_url" class="form-label">YouTube</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fab fa-youtube" style="color: var(--accent)"></i></span>
                                <input type="text" name="youtube_url" id="youtube_url" class="form-control"
                                       placeholder="https://www.youtube.com/@channel"
                                       value="{{ old('youtube_url', $socialMedia->youtube_url ?? '') }}">
                            </div>
                            <small id="youtube_url_error" class="text-danger"></small>
                        </div>

                        <div class="col-12">
                            <div class="help">
                                Tip: You can paste handles or full links — we’ll clean them up.
                            </div>
                        </div>

                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-brand w-50">
                                <i class="fa fa-save me-1"></i> Update
                            </button>
                        </div>
                    </div>
                </form>
            </div> <!-- /card-body -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
    <script>Swal.fire({icon:'success',title:'Success!',text:@json(session('success')),confirmButtonColor:'#0ea5e9'});</script>
    @endif
    @if(session('error'))
    <script>Swal.fire({icon:'error',title:'Error!',text:@json(session('error')),confirmButtonColor:'#ef4444'});</script>
    @endif

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Normalizes a social input into a full URL if a handle was provided.
        function normalizeUrl(id, base){
            const el = document.getElementById(id);
            let v = (el.value || '').trim();
            if(!v) return;

            // If they pasted a handle like @user or user
            if(!/^https?:\/\//i.test(v)){
                v = v.replace(/^@/,'');
                // special case for linkedin (may include /in/)
                if(id === 'linkedin_url' && !v.startsWith('in/')) v = 'in/' + v;
                el.value = base + v;
            }
        }

        function validateForm(){
            // Which fields to validate & their base URLs for normalization
            const fields = [
                { id: 'facebook_url',  base: 'https://www.facebook.com/' },
                { id: 'twitter_url',   base: 'https://twitter.com/' },
                { id: 'instagram_url', base: 'https://instagram.com/' },
                { id: 'linkedin_url',  base: 'https://www.linkedin.com/' },
                { id: 'youtube_url',   base: 'https://www.youtube.com/' },
            ];

            // Normalize before validate
            fields.forEach(f => normalizeUrl(f.id, f.base));

            // Basic URL pattern
            const urlPattern = /^(https?:\/\/)([\w.-]+)\.([a-z\.]{2,})(:\d+)?(\/\S*)?$/i;

            let ok = true;
            fields.forEach(f => {
                const input = document.getElementById(f.id);
                const err   = document.getElementById(f.id + '_error');
                if(input.value.trim() && !urlPattern.test(input.value.trim())){
                    err.innerText = 'Invalid URL format.';
                    ok = false;
                }else{
                    err.innerText = '';
                }
            });

            return ok;
        }
    </script>
@endsection
