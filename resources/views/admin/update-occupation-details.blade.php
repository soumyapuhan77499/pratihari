@extends('layouts.app')

@section('styles')
    <!-- Single Bootstrap + Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --brand-a: #7c3aed;
            /* violet */
            --brand-b: #06b6d4;
            /* cyan   */
            --accent: #f5c12e;
            /* amber  */
            --ink: #0b1220;
            --muted: #64748b;
            --border: rgba(2, 6, 23, .10);
            --soft: #f8fafc;
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
        }

        /* Section Nav (acts like tabs across modules) */
        .tabbar {
            background: #fff;
            border-radius: 12px;
            padding: .4rem;
            box-shadow: 0 6px 18px rgba(2, 6, 23, .06);
        }

        .tabbar .nav-link {
            border: 1px solid transparent;
            background: var(--soft);
            color: var(--muted);
            border-radius: 10px;
            font-weight: 700;
            margin: .2rem;
            padding: .6rem .9rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            white-space: nowrap;
            transition: all .18s ease;
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
            box-shadow: 0 10px 18px rgba(124, 58, 237, .22);
        }

        label {
            font-weight: 600;
            color: #1f2937
        }

        .input-group-text {
            background: #fff;
            border-right: 0;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .input-group .form-control {
            border-left: 0;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .help {
            font-size: .825rem;
            color: var(--muted);
        }

        .btn-brand {
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            border: 0;
            color: #fff;
            font-weight: 800;
            border-radius: 10px;
            box-shadow: 0 12px 24px rgba(124, 58, 237, .22);
        }

        .btn-brand:hover {
            opacity: .96
        }

        .btn-amber {
            background-color: var(--accent);
            color: #1f2937;
            border: 0;
        }

        .btn-amber:hover {
            filter: brightness(.95);
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            font-weight: 700;
            background: var(--soft);
            border: 1px dashed var(--border);
            padding: .35rem .6rem;
            border-radius: 999px;
        }

        @media (max-width: 768px) {
            .tabbar {
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mt-3">
            <div class="card shadow-lg">
              <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    {{-- Back button on the left --}}
                    <a href="{{ route('admin.viewProfile', ['pratihari_id' => $pratihariId]) }}"
                        class="btn btn-light btn-sm d-inline-flex align-items-center">
                        <i class="fa-solid fa-arrow-left me-1"></i>
                        <span>Back to Profile</span>
                    </a>

                    {{-- Title on the right / center-ish --}}
                    <div class="text-uppercase fw-bold d-flex align-items-center">
                        <i class="fa-solid fa-location-dot me-2"></i>
                        <span>Occupation Details</span>
                    </div>
                </div>
                <!-- Section Nav (Address/Family/etc.) -->
                <div class="px-3 pt-3">
                    <ul class="nav tabbar flex-nowrap" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.pratihariProfile') }}">
                                <i class="fas fa-user"></i> Profile
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.pratihariFamily') }}">
                                <i class="fas fa-users"></i> Family
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.pratihariIdcard') }}">
                                <i class="fas fa-id-card"></i> ID Card
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.pratihariAddress') }}">
                                <i class="fas fa-map-marker-alt"></i> Address
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('admin.pratihariOccupation') }}">
                                <i class="fas fa-briefcase"></i> Occupation
                            </a>
                        </li>

                        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-cogs"></i> Seba</a></li>
                        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-share-alt"></i> Social
                                Media</a></li>
                    </ul>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.pratihari-occupation.update', $pratihariId) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="pratihari_id" value="{{ $pratihariId }}">

                        <div class="row g-3">
                            <!-- Occupation -->
                            <div class="col-md-6">
                                <label for="occupation">Occupation</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="width:38px">
                                        <i class="fas fa-briefcase" style="color:var(--accent)"></i>
                                    </span>
                                    <input type="text" name="occupation" id="occupation" class="form-control"
                                        value="{{ old('occupation', $occupation->occupation_type ?? '') }}"
                                        placeholder="e.g., Teacher, Electrician, Shop Owner">
                                </div>
                                <div class="help">Primary occupation or trade.</div>
                            </div>

                            <!-- Extra Curriculum Activity Repeater -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="mb-0" for="extra_activity_0">Extra Curriculum Activity</label>
                                    <span class="chip"><i class="fa-regular fa-lightbulb"></i> Optional</span>
                                </div>

                                @php
                                    $activities =
                                        isset($occupation) && $occupation->extra_activity
                                            ? array_filter(array_map('trim', explode(',', $occupation->extra_activity)))
                                            : [];
                                @endphp

                                <div id="extraActivityContainer" class="pt-1">
                                    @if (count($activities))
                                        @foreach ($activities as $idx => $activity)
                                            <div class="input-group mb-2">
                                                <span class="input-group-text"><i class="fas fa-star"
                                                        style="color:var(--accent)"></i></span>
                                                <input type="text" name="extra_activity[]" class="form-control"
                                                    value="{{ $activity }}"
                                                    placeholder="e.g., First Aid, Driving, Carpentry">
                                                @if ($idx === 0)
                                                    <button type="button" class="btn btn-amber addMore"><i
                                                            class="fas fa-plus"></i></button>
                                                @else
                                                    <button type="button" class="btn btn-danger remove"><i
                                                            class="fas fa-trash"></i></button>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-star"
                                                    style="color:var(--accent)"></i></span>
                                            <input type="text" name="extra_activity[]" class="form-control"
                                                placeholder="e.g., First Aid, Driving, Carpentry">
                                            <button type="button" class="btn btn-amber addMore"><i
                                                    class="fas fa-plus"></i></button>
                                        </div>
                                    @endif
                                </div>
                                <div class="help">Add multiple skills/activities if applicable.</div>
                            </div>

                            <!-- Submit -->
                            <div class="col-12 text-center mt-2">
                                <button type="submit" class="btn btn-brand w-50" style="color: white">
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

    <!-- Single Bootstrap bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Extra Activity repeater (vanilla JS)
        (function() {
            const container = document.getElementById('extraActivityContainer');

            function makeRow(value = '') {
                const wrap = document.createElement('div');
                wrap.className = 'input-group mb-2';
                wrap.innerHTML = `
                    <span class="input-group-text"><i class="fas fa-star" style="color:var(--accent)"></i></span>
                    <input type="text" name="extra_activity[]" class="form-control" placeholder="e.g., First Aid, Driving, Carpentry" value="${value.replace(/"/g,'&quot;')}">
                    <button type="button" class="btn btn-danger remove"><i class="fas fa-trash"></i></button>
                `;
                return wrap;
            }

            container.addEventListener('click', function(e) {
                const btn = e.target.closest('button');
                if (!btn) return;

                if (btn.classList.contains('addMore')) {
                    container.appendChild(makeRow(''));
                }
                if (btn.classList.contains('remove')) {
                    const row = btn.closest('.input-group');
                    if (row) row.remove();
                }
            });
        })();
    </script>
@endsection
