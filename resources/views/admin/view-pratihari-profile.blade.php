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
            --ink:#0b1220;
            --muted:#64748b;
            --border:rgba(2,6,23,.10);
            --ring:rgba(6,182,212,.28);
            --amber:#f5c12e;
            --surface:#ffffff;
            --soft:#f8fafc;
        }

        /* Page header */
        .page-header{
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff;border-radius:16px;padding:18px 20px;
            box-shadow:0 12px 28px rgba(6,182,212,.18);
        }
        .page-header .title{font-weight:800;letter-spacing:.2px;}
        .page-header .meta{opacity:.95}

        /* Profile block */
        .profile-hero{
            display:flex;align-items:center;gap:16px;
        }
        .profile-hero .avatar{
            width:84px;height:84px;border-radius:14px;overflow:hidden;
            border:3px solid rgba(255,255,255,.55);
            box-shadow:0 10px 22px rgba(2,6,23,.22);
            flex-shrink:0;
        }
        .profile-hero .avatar img{width:100%;height:100%;object-fit:cover;}

        /* Summary stats chips */
        .stat-chip{
            display:flex;align-items:center;gap:.45rem;
            background:rgba(255,255,255,.15);
            padding:.35rem .55rem;border-radius:999px;
            font-weight:600;backdrop-filter: blur(2px);
            white-space:nowrap;
        }

        /* Tabbar */
        .tabbar{background:#fff;border:1px solid var(--border);border-radius:14px;
            box-shadow:0 8px 22px rgba(2,6,23,.06);padding:.4rem;overflow:auto;scrollbar-width:thin;}
        .tabbar .nav{flex-wrap:nowrap;gap:.4rem;}
        .tabbar .nav-link{
            display:flex;align-items:center;gap:.5rem;border:1px solid transparent;
            background:var(--soft);color:var(--muted);border-radius:11px;
            padding:.6rem .9rem;font-weight:700;white-space:nowrap;
            transition:transform .12s ease, background .2s ease, color .2s ease, border-color .2s ease;
        }
        .tabbar .nav-link:hover{background:#eef2ff;color:var(--ink);transform:translateY(-1px);border-color:rgba(124,58,237,.25);}
        .tabbar .nav-link.active{color:#fff!important;background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            border-color:transparent;box-shadow:0 10px 18px rgba(124,58,237,.25);}
        .tabbar .nav-link i{font-size:.95rem;}

        /* Donut grid */
        .donut-card{
            border:1px solid var(--border);border-radius:14px;background:#fff;
            box-shadow:0 8px 20px rgba(2,6,23,.06);
        }
        .donut-grid{
            display:grid;gap:14px;
            grid-template-columns:repeat(auto-fit,minmax(130px,1fr));
        }
        .donut{
            display:flex;flex-direction:column;align-items:center;
            padding:14px 10px;border-radius:12px;background:var(--soft);
        }
        .donut canvas{width:92px!important;height:92px!important;}
        .donut .label{font-weight:700;margin-top:.4rem;font-size:.9rem;color:var(--ink);text-align:center;}
        .donut .pct{font-size:.85rem;color:var(--muted);}

        /* Content cards */
        .section-card{border:1px solid var(--border);border-radius:14px;background:var(--surface);box-shadow:0 6px 16px rgba(2,6,23,.05);}
        .section-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;}
        .section-title{margin:0;font-weight:800;color:var(--ink);font-size:1.05rem;}
        .section-hint{font-size:.9rem;color:var(--muted);}

        /* Profile item rows */
        .profile-section .profile-item{display:flex;gap:.75rem;padding:.5rem 0;align-items:flex-start;}
        .profile-item i{color:#475569;opacity:.85;margin-top:.25rem;min-width:18px;text-align:center;}
        .profile-item .key{display:block;font-size:.8rem;color:var(--muted);}
        .profile-item .val{font-weight:600;}

        /* Pills (Bheddha) */
        .beddha-pill{
            display:inline-flex;align-items:center;gap:.4rem;
            background:linear-gradient(90deg,var(--brand-a),var(--brand-b));
            color:#fff;font-weight:700;font-size:.83rem;
            padding:.35rem .65rem;border-radius:999px;
            box-shadow:0 6px 14px rgba(124,58,237,.22);
        }

        /* Buttons */
        .btn-amber{background-color:var(--amber);color:#1f2937;border:0;}
        .btn-amber:hover{filter:brightness(.95);}
        .btn-ghost{background:#fff;border:1px solid var(--border);color:var(--ink);}
        .btn-ghost:hover{background:#f8fafc;}

        /* Address helper */
        .address-grid .col{min-width:240px;}

        /* Misc */
        :focus-visible{outline:2px solid transparent;box-shadow:0 0 0 3px var(--ring)!important;border-radius:10px;}
        @media (max-width: 576px){
            .profile-hero{align-items:flex-start;}
        }
    </style>
@endsection

@section('content')
<div class="container-fluid my-3">
    <!-- Header -->
    <div class="page-header mb-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="profile-hero">
                <span class="avatar">
                    <img src="{{ asset($profile->profile_photo) }}" alt="Profile Photo">
                </span>
                <div>
                    <div class="title h4 mb-1">{{ $profile->first_name }} {{ $profile->last_name }}</div>
                    <div class="meta d-flex flex-wrap gap-2">
                        <span class="stat-chip"><i class="fa-solid fa-id-badge"></i><b>Nijoga:</b> {{ $profile->nijoga_id }}</span>
                        <span class="stat-chip"><i class="fa-solid fa-envelope"></i>{{ $profile->email }}</span>
                        <span class="stat-chip"><i class="fa-solid fa-phone"></i>{{ $profile->phone_no }}</span>
                        <span class="stat-chip"><i class="fa-brands fa-whatsapp"></i>{{ $profile->whatsapp_no }}</span>
                    </div>
                </div>
            </div>

            <!-- Donuts -->
            <div class="donut-card p-3">
                <div class="donut-grid">
                    <a class="donut text-decoration-none" href="{{ route('profile.update', ['pratihari_id'=>$profile->pratihari_id]) }}" title="Edit Personal">
                        <canvas id="profileChart"></canvas>
                        <div class="label">Personal</div>
                        <div class="pct">{{ round($profileCompletion) }}%</div>
                    </a>
                    <a class="donut text-decoration-none" href="{{ route('family.update', ['pratihari_id'=>$profile->pratihari_id]) }}" title="Edit Family">
                        <canvas id="familyChart"></canvas>
                        <div class="label">Family</div>
                        <div class="pct">{{ round($familyCompletion) }}%</div>
                    </a>
                    <a class="donut text-decoration-none" href="{{ route('idcard.update', ['pratihari_id'=>$profile->pratihari_id]) }}" title="Edit ID Card">
                        <canvas id="idcardChart"></canvas>
                        <div class="label">ID Card</div>
                        <div class="pct">{{ round($idcardCompletion) }}%</div>
                    </a>
                    <a class="donut text-decoration-none" href="{{ route('address.update', ['pratihari_id'=>$profile->pratihari_id]) }}" title="Edit Address">
                        <canvas id="addressChart"></canvas>
                        <div class="label">Address</div>
                        <div class="pct">{{ round($addressCompletion) }}%</div>
                    </a>
                    <a class="donut text-decoration-none" href="{{ route('occupation.update', ['pratihari_id'=>$profile->pratihari_id]) }}" title="Edit Occupation">
                        <canvas id="occupationChart"></canvas>
                        <div class="label">Occupation</div>
                        <div class="pct">{{ round($occupationCompletion) }}%</div>
                    </a>
                    <a class="donut text-decoration-none" href="{{ route('seba.update', ['pratihari_id'=>$profile->pratihari_id]) }}" title="Edit Seba">
                        <canvas id="sebaChart"></canvas>
                        <div class="label">Seba</div>
                        <div class="pct">{{ round($sebaCompletion) }}%</div>
                    </a>
                    <a class="donut text-decoration-none" href="{{ route('social.update', ['pratihari_id'=>$profile->pratihari_id]) }}" title="Edit Social">
                        <canvas id="socialmediaChart"></canvas>
                        <div class="label">Social</div>
                        <div class="pct">{{ round($socialmediaCompletion) }}%</div>
                    </a>
                </div>
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
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#social" type="button" role="tab"><i class="fa-solid fa-share-nodes"></i> Social</button></li>
        </ul>
    </div>

    <!-- Tab content -->
    <div class="tab-content">
        <!-- PERSONAL -->
        <div class="tab-pane fade show active" id="personal" role="tabpanel">
            <div class="section-card p-3 p-md-4">
                <div class="section-head">
                    <h6 class="section-title"><i class="fa-solid fa-user-circle me-2" style="color:var(--amber)"></i>Personal Details</h6>
                    <a href="{{ route('profile.update', ['pratihari_id'=>$profile->pratihari_id]) }}" class="btn btn-sm btn-amber"><i class="fa-regular fa-pen-to-square me-1"></i>Edit</a>
                </div>
                <div class="row">
                    <div class="col-lg-6 profile-section">
                        <div class="profile-item"><i class="fa-solid fa-user-tag"></i>
                            <div><span class="key">Alias Name</span><span class="val">{{ $profile->alias_name ?? 'Not Available' }}</span></div>
                        </div>
                        <div class="profile-item"><i class="fa-solid fa-id-card-clip"></i>
                            <div><span class="key">Health Card No</span><span class="val">{{ $profile->healthcard_no ?? 'Not Available' }}</span></div>
                        </div>
                        <div class="profile-item"><i class="fa-solid fa-cake-candles"></i>
                            <div><span class="key">Date of Birth</span><span class="val">{{ $profile->date_of_birth ?? 'Not Available' }}</span></div>
                        </div>
                    </div>
                    <div class="col-lg-6 profile-section">
                        <div class="profile-item"><i class="fa-solid fa-droplet"></i>
                            <div><span class="key">Blood Group</span><span class="val">{{ $profile->blood_group ?? 'Not Available' }}</span></div>
                        </div>
                        <div class="profile-item"><i class="fa-solid fa-calendar-check"></i>
                            <div><span class="key">Joining Date</span><span class="val">{{ $profile->joining_date ?? 'Not Available' }}</span></div>
                        </div>
                        @if (!empty($profile->health_card_photo))
                        <div class="profile-item"><i class="fa-solid fa-image"></i>
                            <div>
                                <span class="key">Health Card Photo</span>
                                <a href="{{ asset($profile->health_card_photo) }}" target="_blank" class="btn btn-sm btn-ghost mt-1">
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
            <div class="section-card p-3 p-md-4">
                <div class="section-head">
                    <h6 class="section-title"><i class="fa-solid fa-people-roof me-2" style="color:var(--amber)"></i>Family Details</h6>
                    <a href="{{ route('family.update', ['pratihari_id'=>$profile->pratihari_id]) }}" class="btn btn-sm btn-amber"><i class="fa-regular fa-pen-to-square me-1"></i>Edit</a>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="section-card p-3 h-100">
                            <div class="text-center">
                                <img class="rounded-circle mb-2" style="height:100px;width:100px;object-fit:cover;" src="{{ asset($family->father_photo ?? '') }}" alt="Father">
                                <div class="fw-semibold">{{ $family->father_name ?? 'Not Available' }}</div>
                                <div class="text-muted small">Father</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="section-card p-3 h-100">
                            <div class="text-center">
                                <img class="rounded-circle mb-2" style="height:100px;width:100px;object-fit:cover;" src="{{ asset($family->mother_photo ?? '') }}" alt="Mother">
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
                            <div class="section-card p-3 h-100 text-center">
                                <img class="rounded-circle mb-2" style="height:100px;width:100px;object-fit:cover;" src="{{ asset($family->spouse_photo ?? '') }}" alt="Spouse">
                                <div class="fw-semibold">{{ $family->spouse_name ?? 'Not Available' }}</div>
                                <div class="text-muted small">Spouse</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="section-card p-3 h-100 text-center">
                                <img class="rounded-circle mb-2" style="height:100px;width:100px;object-fit:cover;" src="{{ asset($family->spouse_father_photo ?? '') }}" alt="Spouse Father">
                                <div class="fw-semibold">{{ $family->spouse_father_name ?? 'Not Available' }}</div>
                                <div class="text-muted small">Spouse's Father</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="section-card p-3 h-100 text-center">
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
                            <div class="section-card p-3 h-100 text-center">
                                <img class="rounded-circle mb-2" style="height:120px;width:120px;object-fit:cover;" src="{{ asset($child->photo ?? '') }}" alt="Child">
                                <div class="fw-semibold">{{ $child->children_name }}</div>
                                <div class="text-muted small">{{ ucfirst($child->gender) }} • DOB: {{ date('d M Y', strtotime($child->date_of_birth)) }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center mb-0">No Children Details Available</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ID CARD -->
        <div class="tab-pane fade" id="idcard" role="tabpanel">
            <div class="section-card p-3 p-md-4">
                <div class="section-head">
                    <h6 class="section-title"><i class="fa-solid fa-id-card me-2" style="color:var(--amber)"></i>ID Card Details</h6>
                    <a href="{{ route('idcard.update', ['pratihari_id'=>$profile->pratihari_id]) }}" class="btn btn-sm btn-amber"><i class="fa-regular fa-pen-to-square me-1"></i>Edit</a>
                </div>

                <div class="row g-3">
                    @foreach ($idcard as $index => $card)
                        <div class="col-md-4">
                            <div class="section-card p-3 h-100">
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

        <!-- ADDRESS -->
        <div class="tab-pane fade" id="address" role="tabpanel">
            <div class="section-card p-3 p-md-4">
                <div class="section-head">
                    <h6 class="section-title"><i class="fa-solid fa-location-dot me-2" style="color:var(--amber)"></i>Address Details</h6>
                    <a href="{{ route('address.update', ['pratihari_id'=>$profile->pratihari_id]) }}" class="btn btn-sm btn-amber"><i class="fa-regular fa-pen-to-square me-1"></i>Edit</a>
                </div>

                <h6 class="fw-bold mt-1 mb-2"><i class="fa-solid fa-map-pin me-2 text-success"></i>Current Address</h6>
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

        <!-- OCCUPATION -->
        <div class="tab-pane fade" id="occupation" role="tabpanel">
            <div class="section-card p-3 p-md-4">
                <div class="section-head">
                    <h6 class="section-title"><i class="fa-solid fa-briefcase me-2" style="color:var(--amber)"></i>Occupation Details</h6>
                    <a href="{{ route('occupation.update', ['pratihari_id'=>$profile->pratihari_id]) }}" class="btn btn-sm btn-amber"><i class="fa-regular fa-pen-to-square me-1"></i>Edit</a>
                </div>

                @if ($occupation->isNotEmpty())
                    <div class="row">
                        <div class="col-lg-6 profile-section">
                            <div class="profile-item"><i class="fa-solid fa-user-tie"></i>
                                <div><span class="key">Occupation Type</span>
                                    <span class="val">{{ optional($occupation->first())->occupation_type ?? 'Not Available' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 profile-section">
                            <div class="profile-item"><i class="fa-solid fa-certificate"></i>
                                <div><span class="key">Extra Activities</span>
                                    <div class="val">
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
                        </div>
                    </div>
                @else
                    <p class="text-muted mb-0">No occupation details available.</p>
                @endif
            </div>
        </div>

        <!-- SEBA -->
        <div class="tab-pane fade" id="seba" role="tabpanel">
            <div class="section-card p-3 p-md-4">
                <div class="section-head">
                    <h6 class="section-title"><i class="fa-solid fa-gears me-2" style="color:var(--amber)"></i>Seba Details</h6>
                    <a href="{{ route('seba.update', ['pratihari_id'=>$profile->pratihari_id]) }}" class="btn btn-sm btn-amber"><i class="fa-regular fa-pen-to-square me-1"></i>Edit</a>
                </div>

                @forelse ($sebaDetails as $s)
                    <div class="profile-item">
                        <i class="fa-solid fa-hand-holding-heart"></i>
                        <div class="w-100">
                            <div><span class="key">Seba Name</span>
                                <span class="val">{{ $s->sebaMaster->seba_name ?? 'Not Available' }}</span>
                            </div>
                            <div class="mt-2">
                                <span class="key">Bheddha Assigned</span>
                                @php $beddhas = $s->beddhas(); @endphp
                                @if ($beddhas->isNotEmpty())
                                    <div class="mt-1">
                                        @foreach ($beddhas as $beddha)
                                            <span class="beddha-pill"><i class="fa-solid fa-user-tag"></i>{{ $beddha->beddha_name }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-muted">Not Assigned</div>
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

        <!-- SOCIAL -->
        <div class="tab-pane fade" id="social" role="tabpanel">
            <div class="section-card p-3 p-md-4">
                <div class="section-head">
                    <h6 class="section-title"><i class="fa-solid fa-share-nodes me-2" style="color:var(--amber)"></i>Social Media</h6>
                    <a href="{{ route('social.update', ['pratihari_id'=>$profile->pratihari_id]) }}" class="btn btn-sm btn-amber"><i class="fa-regular fa-pen-to-square me-1"></i>Edit</a>
                </div>

                <div class="row g-3">
                    @php
                        $socialLinks = [
                            ['fab fa-facebook-f','bg-primary','Facebook',$socialMedia->facebook_url ?? '#'],
                            ['fab fa-instagram','bg-danger','Instagram',$socialMedia->instagram_url ?? '#'],
                            ['fab fa-twitter','bg-info','Twitter',$socialMedia->twitter_url ?? '#'],
                            ['fab fa-linkedin-in','bg-success','LinkedIn',$socialMedia->linkedin_url ?? '#'],
                            ['fab fa-youtube','bg-danger','YouTube',$socialMedia->youtube_url ?? '#'],
                        ];
                    @endphp

                    @foreach ($socialLinks as $s)
                    <div class="col-md-6 col-lg-4">
                        <div class="section-card p-3 d-flex align-items-center gap-3 h-100">
                            <div class="rounded-circle text-white d-flex align-items-center justify-content-center {{ $s[1] }}" style="width:44px;height:44px;">
                                <i class="{{ $s[0] }}"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $s[2] }}</div>
                                <a href="{{ $s[3] ?: '#' }}" target="_blank" class="text-muted small text-decoration-none">
                                    {{ $s[3] ? (parse_url($s[3], PHP_URL_HOST) ?: $s[3]) : 'Not Available' }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

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
        // Donut chart data/colors (clamped 0..100)
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
            const v = Math.max(0, Math.min(100, Number(chartData[id])||0));
            new Chart(el.getContext('2d'), {
                type:'doughnut',
                data:{ datasets:[{ data:[v, 100-v], backgroundColor:[chartColors[id], '#e5e7eb'], borderWidth:0 }] },
                options:{ cutout:'72%', responsive:false, plugins:{ legend:{display:false}, tooltip:{enabled:false} } }
            });
        });
    </script>
@endsection
