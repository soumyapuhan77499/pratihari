@extends('layouts.app')

@section('styles')
    <!-- Bootstrap 5.3 + Font Awesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Optional: keep your component CSS -->
    <link href="{{ asset('assets/css/profile.css') }}" rel="stylesheet">

    <style>
        :root{
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

        /* Profile header block */
        .profile-head .profile-image{
            width:92px;height:92px;border-radius:18px;overflow:hidden;
            display:inline-block;border:3px solid rgba(255,255,255,.6);
            box-shadow:0 10px 22px rgba(2,6,23,.18);
        }
        .profile-head .profile-image img{ width:100%;height:100%;object-fit:cover; }

        /* Clickable donut progress list */
        .progress-circle-wrapper{ display:flex;flex-wrap:wrap;gap:1rem;align-items:center; }
        .progress-circle{
            display:flex;flex-direction:column;align-items:center;text-decoration:none;width:92px;
        }
        .progress-circle canvas{ width:92px!important;height:92px!important; }
        .progress-label{ margin-top:.35rem;text-align:center;font-weight:700;font-size:.82rem;color:var(--ink); }

        /* Tabbar */
        .tabbar{background:#fff;border:1px solid var(--border);border-radius:14px;
            box-shadow:0 8px 22px rgba(2,6,23,.06);padding:.35rem;overflow:auto;scrollbar-width:thin;}
        .tabbar .nav{flex-wrap:nowrap;gap:.35rem;}
        .tabbar .nav-link{
            display:flex;align-items:center;gap:.55rem;border:1px solid transparent;
            background:#f8fafc;color:var(--muted);border-radius:11px;
            padding:.55rem .9rem;font-weight:700;white-space:nowrap;
            transition:transform .12s ease, background .2s ease, color .2s ease, border-color .2s ease;
        }
        .tabbar .nav-link:hover{background:#eef2ff;color:var(--ink);transform:translateY(-1px);border-color:rgba(124,58,237,.25);}
        .tabbar .nav-link.active{color:#fff!important;background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            border-color:transparent;box-shadow:0 10px 18px rgba(124,58,237,.25);}
        .tabbar .nav-link i{font-size:.95rem;}
        .tabbar::-webkit-scrollbar{ height:8px; }
        .tabbar::-webkit-scrollbar-thumb{ background:#e2e8f0;border-radius:8px; }

        /* Section cards */
        .card{border:1px solid var(--border);border-radius:1rem;}
        .profile-section .profile-item{ display:flex;gap:.75rem;padding:.4rem 0;align-items:flex-start; }
        .profile-item i{color:#475569;opacity:.8;margin-top:.15rem;}

        /* Pills */
        .beddha-pill{
            display:inline-flex;align-items:center;gap:.35rem;
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff;font-weight:700;font-size:.82rem;
            padding:.35rem .65rem;border-radius:999px;
            box-shadow:0 6px 14px rgba(124,58,237,.22);
        }

        /* Address grid helper */
        .address-grid .col{min-width:220px;}

        /* Buttons */
        .btn-amber{background-color:var(--amber);color:#1f2937;border:0;}
        .btn-amber:hover{filter:brightness(.95);}
        .btn-brand{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            border:0;color:#fff;box-shadow:0 14px 30px rgba(124,58,237,.25);
        }
        .btn-brand:hover{opacity:.96;}

        /* Accessibility */
        :focus-visible{outline:2px solid transparent;box-shadow:0 0 0 3px var(--ring)!important;border-radius:10px;}

        @media (max-width: 576px){
            .profile-head .prof-details{width:100%!important;}
            .progress-circle-wrapper{gap:.75rem;}
        }
    </style>
@endsection

@section('content')
<div class="container-fluid my-3">
    <!-- Header -->
    <div class="page-header mb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3 profile-head">
                <span class="profile-image">
                    <img src="{{ asset($profile->profile_photo) }}" alt="Profile Photo">
                </span>
                <div class="prof-details" style="width: 320px;">
                    <div class="title h4 mb-1">{{ $profile->first_name }} {{ $profile->last_name }}</div>
                    <div class="small">
                        <span class="me-3"><i class="fa-solid fa-id-badge me-1"></i><b>Nijoga ID:</b> {{ $profile->nijoga_id }}</span>
                        <span class="me-3"><i class="fa-solid fa-envelope me-1"></i>{{ $profile->email }}</span>
                        <span class="me-3"><i class="fa-solid fa-phone me-1"></i>{{ $profile->phone_no }}</span>
                        <span><i class="fa-brands fa-whatsapp me-1"></i>{{ $profile->whatsapp_no }}</span>
                    </div>
                </div>
            </div>

            <!-- Progress donuts (explicit items to avoid Blade array parsing issues) -->
            <div class="progress-circle-wrapper">
                <a href="{{ route('profile.update', ['pratihari_id'=>$profile->pratihari_id]) }}" class="progress-circle" title="Edit Personal">
                    <canvas id="profileChart"></canvas>
                    <div class="progress-label">Personal<br>{{ round($profileCompletion) }}%</div>
                </a>
                <a href="{{ route('family.update', ['pratihari_id'=>$profile->pratihari_id]) }}" class="progress-circle" title="Edit Family">
                    <canvas id="familyChart"></canvas>
                    <div class="progress-label">Family<br>{{ round($familyCompletion) }}%</div>
                </a>
                <a href="{{ route('idcard.update', ['pratihari_id'=>$profile->pratihari_id]) }}" class="progress-circle" title="Edit ID Card">
                    <canvas id="idcardChart"></canvas>
                    <div class="progress-label">ID Card<br>{{ round($idcardCompletion) }}%</div>
                </a>
                <a href="{{ route('address.update', ['pratihari_id'=>$profile->pratihari_id]) }}" class="progress-circle" title="Edit Address">
                    <canvas id="addressChart"></canvas>
                    <div class="progress-label">Address<br>{{ round($addressCompletion) }}%</div>
                </a>
                <a href="{{ route('occupation.update', ['pratihari_id'=>$profile->pratihari_id]) }}" class="progress-circle" title="Edit Occupation">
                    <canvas id="occupationChart"></canvas>
                    <div class="progress-label">Occupation<br>{{ round($occupationCompletion) }}%</div>
                </a>
                <a href="{{ route('seba.update', ['pratihari_id'=>$profile->pratihari_id]) }}" class="progress-circle" title="Edit Seba">
                    <canvas id="sebaChart"></canvas>
                    <div class="progress-label">Seba<br>{{ round($sebaCompletion) }}%</div>
                </a>
                <a href="{{ route('social.update', ['pratihari_id'=>$profile->pratihari_id]) }}" class="progress-circle" title="Edit Social">
                    <canvas id="socialmediaChart"></canvas>
                    <div class="progress-label">Social<br>{{ round($socialmediaCompletion) }}%</div>
                </a>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tabbar mb-3">
        <ul class="nav" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab"><i class="fa-solid fa-user"></i> Personal</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#family" type="button" role="tab"><i class="fa-solid fa-users"></i> Family</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#idcard" type="button" role="tab"><i class="fa-solid fa-id-card"></i> ID Card</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#address" type="button" role="tab"><i class="fa-solid fa-location-dot"></i> Address</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#occupation" type="button" role="tab"><i class="fa-solid fa-briefcase"></i> Occupation</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#seba" type="button" role="tab"><i class="fa-solid fa-gears"></i> Seba</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#social" type="button" role="tab"><i class="fa-solid fa-share-nodes"></i> Social Media</button></li>
        </ul>
    </div>

    <!-- Tab content -->
    <div class="row">
        <div class="col-12">
            <div class="tab-content">
                <!-- PERSONAL -->
                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                    <div class="card personal-details-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0"><i class="fa-solid fa-user-circle me-2" style="color:var(--amber)"></i>Personal Details</h5>
                                <a href="{{ route('profile.update', ['pratihari_id'=>$profile->pratihari_id]) }}" class="btn btn-sm btn-amber"><i class="fa-regular fa-pen-to-square me-1"></i>Edit</a>
                            </div>

                            <div class="profile-section">
                                <div class="profile-item"><i class="fa-solid fa-user-tag"></i>
                                    <div><span class="text-muted small d-block">Alias Name</span><span class="fw-semibold">{{ $profile->alias_name ?? 'Not Available' }}</span></div>
                                </div>
                                <div class="profile-item"><i class="fa-solid fa-id-card-clip"></i>
                                    <div><span class="text-muted small d-block">Health Card No</span><span class="fw-semibold">{{ $profile->healthcard_no ?? 'Not Available' }}</span></div>
                                </div>
                                <div class="profile-item"><i class="fa-solid fa-cake-candles"></i>
                                    <div><span class="text-muted small d-block">Date of Birth</span><span class="fw-semibold">{{ $profile->date_of_birth ?? 'Not Available' }}</span></div>
                                </div>
                                <div class="profile-item"><i class="fa-solid fa-droplet"></i>
                                    <div><span class="text-muted small d-block">Blood Group</span><span class="fw-semibold">{{ $profile->blood_group ?? 'Not Available' }}</span></div>
                                </div>
                                <div class="profile-item"><i class="fa-solid fa-calendar-check"></i>
                                    <div><span class="text-muted small d-block">Joining Date</span><span class="fw-semibold">{{ $profile->joining_date ?? 'Not Available' }}</span></div>
                                </div>

                                @if (!empty($profile->health_card_photo))
                                    <div class="profile-item"><i class="fa-solid fa-image"></i>
                                        <div>
                                            <span class="text-muted small d-block">Health Card Photo</span>
                                            <a href="{{ asset($profile->health_card_photo) }}" target="_blank" class="btn btn-sm btn-outline-secondary mt-1">
                                                <i class="fa-solid fa-up-right-from-square me-1"></i>View Photo
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAMILY -->
                <div class="tab-pane fade" id="family" role="tabpanel">
                    <div class="card p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0"><i class="fa-solid fa-people-roof me-2" style="color:var(--amber)"></i>Family Details</h5>
                            <a href="{{ route('family.update', ['pratihari_id'=>$profile->pratihari_id]) }}" class="btn btn-sm btn-amber"><i class="fa-regular fa-pen-to-square me-1"></i>Edit</a>
                        </div>

                        <!-- Parents -->
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <div class="card h-100 p-3">
                                    <div class="text-center">
                                        <img class="rounded-circle mb-2" style="height:100px;width:100px;object-fit:cover;"
                                             src="{{ asset($family->father_photo ?? '') }}" alt="Father">
                                        <div class="fw-semibold">{{ $family->father_name ?? 'Not Available' }}</div>
                                        <div class="text-muted small">Father</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 p-3">
                                    <div class="text-center">
                                        <img class="rounded-circle mb-2" style="height:100px;width:100px;object-fit:cover;"
                                             src="{{ asset($family->mother_photo ?? '') }}" alt="Mother">
                                        <div class="fw-semibold">{{ $family->mother_name ?? 'Not Available' }}</div>
                                        <div class="text-muted small">Mother</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($family && $family->maritial_status == 'married')
                            <hr class="my-4">
                            <h6 class="fw-bold mb-2"><i class="fa-solid fa-heart me-2" style="color:var(--amber)"></i>Spouse & In-Laws</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="card h-100 p-3 text-center">
                                        <img class="rounded-circle mb-2" style="height:100px;width:100px;object-fit:cover;" src="{{ asset($family->spouse_photo ?? '') }}" alt="Spouse">
                                        <div class="fw-semibold">{{ $family->spouse_name ?? 'Not Available' }}</div>
                                        <div class="text-muted small">Spouse</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card h-100 p-3 text-center">
                                        <img class="rounded-circle mb-2" style="height:100px;width:100px;object-fit:cover;" src="{{ asset($family->spouse_father_photo ?? '') }}" alt="Spouse Father">
                                        <div class="fw-semibold">{{ $family->spouse_father_name ?? 'Not Available' }}</div>
                                        <div class="text-muted small">Spouse's Father</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card h-100 p-3 text-center">
                                        <img class="rounded-circle mb-2" style="height:100px;width:100px;object-fit:cover;" src="{{ asset($family->spouse_mother_photo ?? '') }}" alt="Spouse Mother">
                                        <div class="fw-semibold">{{ $family->spouse_mother_name ?? 'Not Available' }}</div>
                                        <div class="text-muted small">Spouse's Mother</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <hr class="my-4">
                        <h6 class="fw-bold mb-2"><i class="fa-solid fa-child-reaching me-2" style="color:var(--amber)"></i>Children</h6>
                        <div class="row g-3">
                            @forelse ($children as $child)
                                <div class="col-md-4">
                                    <div class="card h-100 p-3 text-center">
                                        <img class="rounded-circle mb-2" style="height:120px;width:120px;object-fit:cover;" src="{{ asset($child->photo ?? '') }}" alt="Child">
                                        <div class="fw-semibold">{{ $child->children_name }}</div>
                                        <div class="text-muted small">{{ ucfirst($child->gender) }} • DOB: {{ date('d M Y', strtotime($child->date_of_birth)) }}</div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted text-center">No Children Details Available</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- ID CARD -->
                <div class="tab-pane fade" id="idcard" role="tabpanel">
                    <div class="card profile-section">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0"><i class="fa-solid fa-id-card me-2" style="color:var(--amber)"></i>ID Card Details</h5>
                                <a href="{{ route('idcard.update', ['pratihari_id'=>$profile->pratihari_id]) }}" class="btn btn-sm btn-amber"><i class="fa-regular fa-pen-to-square me-1"></i>Edit</a>
                            </div>

                            <div class="row g-3 mt-1">
                                @foreach ($idcard as $index => $card)
                                    <div class="col-md-4">
                                        <div class="card h-100 p-3">
                                            <div class="text-center border-bottom pb-2 mb-2">
                                                <div class="fw-bold text-uppercase">{{ $card->id_type ?? 'ID CARD' }}</div>
                                            </div>
                                            <div class="text-center">
                                                <a href="{{ $card->id_photo }}" target="_blank">
                                                    <img src="{{ $card->id_photo }}" alt="ID Photo" class="img-fluid rounded mb-2" style="height:160px;object-fit:cover;">
                                                </a>
                                            </div>
                                            <div class="text-center small">
                                                <div class="mb-1"><span class="text-muted">ID Type:</span> <span class="fw-semibold">{{ $card->id_type ?? 'Not Available' }}</span></div>
                                                <div><span class="text-muted">ID Number:</span> <span class="fw-semibold">{{ $card->id_number ?? 'Not Available' }}</span></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @if($idcard->isEmpty())
                                    <div class="col-12 text-center text-muted">No ID cards added.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ADDRESS -->
                <div class="tab-pane fade" id="address" role="tabpanel">
                    <div class="card profile-section">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0"><i class="fa-solid fa-location-dot me-2" style="color:var(--amber)"></i>Address Details</h5>
                                <a href="{{ route('address.update', ['pratihari_id'=>$profile->pratihari_id]) }}" class="btn btn-sm btn-amber"><i class="fa-regular fa-pen-to-square me-1"></i>Edit</a>
                            </div>

                            <h6 class="fw-bold mt-2 mb-2"><i class="fa-solid fa-map-pin me-2 text-success"></i>Current Address</h6>
                            <div class="row row-cols-auto g-2 address-grid">
                                <x-address-item icon="fa-map-marked-alt" color="primary" label="Address" :value="$profile->address->address ?? 'Not Available'" />
                                <x-address-item icon="fa-map-signs" color="success" label="Sahi" :value="$profile->address->sahi ?? 'Not Available'" />
                                <x-address-item icon="fa-thumbtack" color="danger" label="Landmark" :value="$profile->address->landmark ?? 'Not Available'" />
                                <x-address-item icon="fa-envelope" color="info" label="Pincode" :value="$profile->address->pincode ?? 'Not Available'" />
                                <x-address-item icon="fa-mail-bulk" color="primary" label="Post" :value="$profile->address->post ?? 'Not Available'" />
                                <x-address-item icon="fa-user-shield" color="warning" label="Police Station" :value="$profile->address->police_station ?? 'Not Available'" />
                                <x-address-item icon="fa-city" color="secondary" label="District" :value="$profile->address->district ?? 'Not Available'" />
                                <x-address-item icon="fa-map" color="success" label="State" :value="$profile->address->state ?? 'Not Available'" />
                                <x-address-item icon="fa-flag" color="danger" label="Country" :value="$profile->address->country ?? 'Not Available'" />
                            </div>

                            <hr class="my-4">

                            <h6 class="fw-bold mb-2"><i class="fa-solid fa-house me-2 text-info"></i>Permanent Address</h6>
                            <div class="row row-cols-auto g-2 address-grid">
                                <x-address-item icon="fa-map-marked" color="primary" label="Address" :value="$profile->address->per_address ?? 'Not Available'" />
                                <x-address-item icon="fa-map-signs" color="success" label="Sahi" :value="$profile->address->per_sahi ?? 'Not Available'" />
                                <x-address-item icon="fa-thumbtack" color="danger" label="Landmark" :value="$profile->address->per_landmark ?? 'Not Available'" />
                                <x-address-item icon="fa-envelope" color="info" label="Pincode" :value="$profile->address->per_pincode ?? 'Not Available'" />
                                <x-address-item icon="fa-mail-bulk" color="primary" label="Post" :value="$profile->address->per_post ?? 'Not Available'" />
                                <x-address-item icon="fa-user-shield" color="warning" label="Police Station" :value="$profile->address->per_police_station ?? 'Not Available'" />
                                <x-address-item icon="fa-city" color="secondary" label="District" :value="$profile->address->per_district ?? 'Not Available'" />
                                <x-address-item icon="fa-map" color="success" label="State" :value="$profile->address->per_state ?? 'Not Available'" />
                                <x-address-item icon="fa-flag" color="danger" label="Country" :value="$profile->address->per_country ?? 'Not Available'" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- OCCUPATION -->
                <div class="tab-pane fade" id="occupation" role="tabpanel">
                    <div class="card profile-section">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0"><i class="fa-solid fa-briefcase me-2" style="color:var(--amber)"></i>Occupation Details</h5>
                                <a href="{{ route('occupation.update', ['pratihari_id'=>$profile->pratihari_id]) }}" class="btn btn-sm btn-amber"><i class="fa-regular fa-pen-to-square me-1"></i>Edit</a>
                            </div>

                            @if ($occupation->isNotEmpty())
                                <div class="profile-item"><i class="fa-solid fa-user-tie"></i>
                                    <div><span class="text-muted small d-block">Occupation Type</span>
                                        <span class="fw-semibold">{{ optional($occupation->first())->occupation_type ?? 'Not Available' }}</span>
                                    </div>
                                </div>
                                <div class="profile-item"><i class="fa-solid fa-certificate"></i>
                                    <div><span class="text-muted small d-block">Extra Activities</span>
                                        <div>
                                            @if (!empty(optional($occupation->first())->extra_activity))
                                                @foreach (explode(',', optional($occupation->first())->extra_activity) as $activity)
                                                    <span class="badge text-bg-success me-1">{{ trim($activity) }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Not Available</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted mb-0">No occupation details available.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- SEBA -->
                <div class="tab-pane fade" id="seba" role="tabpanel">
                    <div class="card profile-section">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0"><i class="fa-solid fa-gears me-2" style="color:var(--amber)"></i>Seba Details</h5>
                                <a href="{{ route('seba.update', ['pratihari_id'=>$profile->pratihari_id]) }}" class="btn btn-sm btn-amber"><i class="fa-regular fa-pen-to-square me-1"></i>Edit</a>
                            </div>

                            @forelse ($sebaDetails as $s)
                                <div class="profile-item">
                                    <i class="fa-solid fa-hand-holding-heart"></i>
                                    <div>
                                        <div><span class="text-muted small d-block">Seba Name</span>
                                            <span class="fw-bold">{{ $s->sebaMaster->seba_name ?? 'Not Available' }}</span>
                                        </div>
                                        <div class="mt-2">
                                            <span class="text-muted small d-block">Bheddha Assigned</span>
                                            @php $beddhas = $s->beddhas(); @endphp
                                            @if ($beddhas->isNotEmpty())
                                                <div class="mt-1">
                                                    @foreach ($beddhas as $beddha)
                                                        <span class="beddha-pill"><i class="fa-solid fa-user-tag"></i>{{ $beddha->beddha_name }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">Not Assigned</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-3">
                            @empty
                                <p class="text-muted mb-0">No seba details available.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- SOCIAL -->
                <div class="tab-pane fade" id="social" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0"><i class="fa-solid fa-share-nodes me-2" style="color:var(--amber)"></i>Social Media Links</h5>
                                <a href="{{ route('social.update', ['pratihari_id'=>$profile->pratihari_id]) }}" class="btn btn-sm btn-amber"><i class="fa-regular fa-pen-to-square me-1"></i>Edit</a>
                            </div>

                            <div class="d-flex flex-wrap gap-3 mt-3">
                                @php
                                    $socialLinks = [
                                        ['fab fa-facebook-f','bg-primary','Facebook',$socialMedia->facebook_url ?? '#'],
                                        ['fab fa-instagram','bg-danger','Instagram',$socialMedia->instagram_url ?? '#'],
                                        ['fab fa-twitter','bg-info','Twitter',$socialMedia->twitter_url ?? '#'],
                                        ['fab fa-linkedin-in','bg-success','LinkedIn',$socialMedia->linkedin_url ?? '#'],
                                        ['fab fa-youtube','bg-danger','YouTube',$socialMedia->youtube_url ?? '#'],
                                    ];
                                @endphp
                                @foreach ($socialLinks as $social)
                                    <div class="d-flex align-items-center me-3">
                                        <div class="rounded-circle text-white d-flex align-items-center justify-content-center {{ $social[1] }}"
                                             style="width:40px;height:40px;"><i class="{{ $social[0] }}"></i></div>
                                        <div class="ms-2">
                                            <div class="fw-semibold">{{ $social[2] }}</div>
                                            <a href="{{ $social[3] ?: '#' }}" target="_blank" class="text-muted small text-decoration-none">
                                                {{ $social[3] ? (parse_url($social[3], PHP_URL_HOST) ?: $social[3]) : 'Not Available' }}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
                <!-- /SOCIAL -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- Bootstrap bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>Swal.fire({icon:'success',title:'Success!',text:@json(session('success')),confirmButtonColor:'#0ea5e9'});</script>
    @endif
    @if (session('error'))
        <script>Swal.fire({icon:'error',title:'Error!',text:@json(session('error')),confirmButtonColor:'#ef4444'});</script>
    @endif

    <script>
        // Donut chart data/colors
        const chartData = {
            profileChart: {{ round($profileCompletion) }},
            familyChart: {{ round($familyCompletion) }},
            idcardChart: {{ round($idcardCompletion) }},
            addressChart: {{ round($addressCompletion) }},
            occupationChart: {{ round($occupationCompletion) }},
            sebaChart: {{ round($sebaCompletion) }},
            socialmediaChart: {{ round($socialmediaCompletion) }},
        };
        const chartColors = {
            profileChart:'#4CAF50',
            familyChart:'#FF9800',
            idcardChart:'#2196F3',
            addressChart:'#673AB7',
            occupationChart:'#009688',
            sebaChart:'#FF5722',
            socialmediaChart:'#E91E63',
        };

        Object.keys(chartData).forEach(id=>{
            const el = document.getElementById(id);
            if(!el) return;
            const val = Math.max(0, Math.min(100, Number(chartData[id])||0));
            new Chart(el.getContext('2d'), {
                type:'doughnut',
                data:{ datasets:[{ data:[val, 100-val], backgroundColor:[chartColors[id], '#e5e7eb'], borderWidth:0 }] },
                options:{ cutout:'72%', responsive:false, plugins:{ legend:{display:false}, tooltip:{enabled:false} } }
            });
        });
    </script>
@endsection
