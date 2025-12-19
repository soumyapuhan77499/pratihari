@extends('layouts.app')

@section('styles')
    <!-- Bootstrap 5.3 + Font Awesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Optional: keep your component CSS -->
    <link href="{{ asset('assets/css/profile.css') }}" rel="stylesheet">

    <style>
        :root {
            --brand-a: #7c3aed;
            /* violet */
            --brand-b: #06b6d4;
            /* cyan   */
            --ink: #0b1220;
            --muted: #64748b;
            --border: rgba(2, 6, 23, .10);
            --ring: rgba(6, 182, 212, .28);
            --amber: #f5c12e;
            --surface: #ffffff;
            --soft: #f8fafc;
        }

        /* Page header */
        .page-header {
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            color: #fff;
            border-radius: 16px;
            padding: 18px 20px;
            box-shadow: 0 12px 28px rgba(6, 182, 212, .18);
        }

        .page-header .title {
            font-weight: 800;
            letter-spacing: .2px
        }

        .page-header .meta {
            opacity: .95
        }

        /* Profile block */
        .profile-hero {
            display: flex;
            align-items: center;
            gap: 16px
        }

        .profile-hero .avatar {
            width: 84px;
            height: 84px;
            border-radius: 14px;
            overflow: hidden;
            border: 3px solid rgba(255, 255, 255, .55);
            box-shadow: 0 10px 22px rgba(2, 6, 23, .22);
            flex-shrink: 0
        }

        .profile-hero .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        /* Chips */
        .stat-chip {
            display: flex;
            align-items: center;
            gap: .45rem;
            background: rgba(255, 255, 255, .15);
            padding: .35rem .55rem;
            border-radius: 999px;
            font-weight: 600;
            white-space: nowrap;
            backdrop-filter: blur(2px)
        }

        /* Donut rail */
        .donut-card {
            border: 1px solid rgba(255, 255, 255, .25);
            background: rgba(255, 255, 255, .08);
            border-radius: 14px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .15), 0 8px 20px rgba(2, 6, 23, .15);
            backdrop-filter: blur(3px)
        }

        .donut-rail {
            display: flex;
            flex-wrap: nowrap;
            gap: 12px;
            overflow-x: auto;
            padding: 6px;
            scroll-snap-type: x mandatory
        }

        .donut-rail::-webkit-scrollbar {
            height: 8px
        }

        .donut-rail::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .25);
            border-radius: 8px
        }

        .donut {
            flex: 0 0 132px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 12px 10px;
            border-radius: 12px;
            background: var(--soft);
            scroll-snap-align: start;
            border: 1px solid var(--border)
        }

        .donut canvas {
            width: 92px !important;
            height: 92px !important
        }

        .donut .label {
            font-weight: 700;
            margin-top: .4rem;
            font-size: .9rem;
            color: var(--ink);
            text-align: center
        }

        .donut .pct {
            font-size: .85rem;
            color: var(--muted)
        }

        /* Tabbar */
        .tabbar {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 8px 22px rgba(2, 6, 23, .06);
            padding: .4rem;
            overflow: auto;
            scrollbar-width: thin
        }

        .tabbar .nav {
            flex-wrap: nowrap;
            gap: .4rem
        }

        .tabbar .nav-link {
            display: flex;
            align-items: center;
            gap: .5rem;
            border: 1px solid transparent;
            background: var(--soft);
            color: var(--muted);
            border-radius: 11px;
            padding: .6rem .9rem;
            font-weight: 700;
            white-space: nowrap;
            transition: transform .12s, background .2s, color .2s, border-color .2s
        }

        .tabbar .nav-link:hover {
            background: #eef2ff;
            color: var(--ink);
            transform: translateY(-1px);
            border-color: rgba(124, 58, 237, .25)
        }

        .tabbar .nav-link.active {
            color: #fff !important;
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            border-color: transparent;
            box-shadow: 0 10px 18px rgba(124, 58, 237, .25)
        }

        .tabbar .nav-link i {
            font-size: .95rem
        }

        /* Generic cards */
        .section-card {
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--surface);
            box-shadow: 0 6px 16px rgba(2, 6, 23, .05)
        }

        .section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px
        }

        .section-title {
            margin: 0;
            font-weight: 800;
            color: var(--ink);
            font-size: 1.05rem
        }

        .profile-section .profile-item {
            display: flex;
            gap: .75rem;
            padding: .5rem 0;
            align-items: flex-start
        }

        .profile-item i {
            color: #475569;
            opacity: .85;
            margin-top: .25rem;
            min-width: 18px;
            text-align: center
        }

        .profile-item .key {
            display: block;
            font-size: .8rem;
            color: var(--muted)
        }

        .profile-item .val {
            font-weight: 600
        }

        /* Bheddha pill */
        .beddha-pill {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            color: #fff;
            font-weight: 700;
            font-size: .83rem;
            padding: .35rem .65rem;
            border-radius: 999px;
            box-shadow: 0 6px 14px rgba(124, 58, 237, .22)
        }

        /* Buttons */
        .btn-amber {
            background-color: var(--amber);
            color: #1f2937;
            border: 0
        }

        .btn-amber:hover {
            filter: brightness(.95)
        }

        .btn-ghost {
            background: #fff;
            border: 1px solid var(--border);
            color: var(--ink)
        }

        .btn-ghost:hover {
            background: #f8fafc
        }

        /* Address (colorful duo+) */
        .address-duo .addr-card {
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid var(--border);
            background: var(--surface);
            box-shadow: 0 16px 38px rgba(2, 6, 23, .08)
        }

        .addr-top {
            position: relative;
            padding: 16px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: .7rem;
            background: linear-gradient(120deg, #0ea5e9 0%, #22c55e 100%);
        }

        .addr-top.perm {
            background: linear-gradient(120deg, #7c3aed 0%, #ef4444 100%)
        }

        .addr-top .title {
            font-weight: 900;
            letter-spacing: .2px;
            flex: 1
        }

        .addr-top .badge-soft {
            background: rgba(255, 255, 255, .2);
            border: 1px solid rgba(255, 255, 255, .35);
            padding: .18rem .5rem;
            border-radius: 999px;
            font-weight: 800
        }

        .addr-top:after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(1200px 200px at -10% 0%, rgba(255, 255, 255, .18), transparent 50%);
            pointer-events: none;
            mix-blend: overlay;
        }

        .addr-body {
            padding: 14px 16px
        }

        .addr-row {
            display: grid;
            grid-template-columns: 28px 1fr;
            gap: .7rem;
            padding: .6rem .2rem;
            border-radius: 10px;
            position: relative;
        }

        .addr-row+.addr-row {
            margin-top: .25rem
        }

        .addr-row:nth-child(odd) {
            background: linear-gradient(180deg, #f8fafc, #ffffff);
            border: 1px dashed rgba(2, 6, 23, .06)
        }

        .addr-row:nth-child(even) {
            background: linear-gradient(180deg, #ffffff, #f8fafc);
            border: 1px dashed rgba(2, 6, 23, .06)
        }

        .addr-row i {
            color: #334155;
            opacity: .95;
            margin-top: .2rem
        }

        .addr-row .k {
            display: block;
            font-size: .78rem;
            color: var(--muted)
        }

        .addr-row .v {
            font-weight: 800;
            color: var(--ink);
            word-break: break-word
        }

        .tagline {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: #eef2ff;
            border: 1px solid rgba(124, 58, 237, .25);
            color: #312e81;
            font-weight: 700;
            border-radius: 999px;
            padding: .2rem .55rem;
            font-size: .75rem
        }

        .addr-foot {
            display: flex;
            justify-content: flex-end;
            gap: .5rem;
            border-top: 1px solid var(--border);
            padding: 10px 16px;
            background: linear-gradient(180deg, rgba(2, 6, 23, .02), rgba(2, 6, 23, .04))
        }

        .btn-round {
            border-radius: 999px;
            padding: .38rem .75rem
        }

        /* Copy tooltip */
        .copy-tip {
            position: relative
        }

        .copy-tip[data-copied="1"]::after {
            content: "Copied!";
            position: absolute;
            top: -28px;
            right: 0;
            background: #10b981;
            color: #fff;
            font-weight: 800;
            padding: .15rem .45rem;
            border-radius: 8px;
            font-size: .75rem;
            box-shadow: 0 6px 12px rgba(16, 185, 129, .35)
        }

        /* SEBA cards (unchanged from your last setup) */
        .seba-card {
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            background: var(--surface);
            box-shadow: 0 10px 28px rgba(2, 6, 23, .06);
            height: 100%
        }

        .seba-card .seba-head {
            color: #fff;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: .6rem;
            font-weight: 800
        }

        .seba-card .seba-title {
            flex: 1
        }

        .seba-card .seba-body {
            padding: 14px 16px
        }

        .seba-card .row-line {
            display: flex;
            gap: .6rem;
            padding: .45rem 0;
            border-bottom: 1px dashed rgba(2, 6, 23, .08)
        }

        .seba-card .row-line:last-child {
            border-bottom: 0
        }

        .seba-card .row-line i {
            width: 22px;
            text-align: center;
            color: #334155;
            opacity: .9;
            margin-top: .2rem
        }

        .seba-card .k {
            font-size: .78rem;
            color: var(--muted);
            display: block
        }

        .seba-card .v {
            font-weight: 700;
            color: var(--ink)
        }

        .seba-chip {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .3rem .55rem;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            color: #fff;
            font-weight: 700;
            font-size: .8rem;
            box-shadow: 0 6px 14px rgba(124, 58, 237, .18)
        }

        .grad-1 {
            background: linear-gradient(120deg, #0ea5e9, #22c55e)
        }

        .grad-2 {
            background: linear-gradient(120deg, #7c3aed, #f43f5e)
        }

        .grad-3 {
            background: linear-gradient(120deg, #06b6d4, #3b82f6)
        }

        .grad-4 {
            background: linear-gradient(120deg, #f59e0b, #ef4444)
        }

        .grad-5 {
            background: linear-gradient(120deg, #10b981, #14b8a6)
        }

        .grad-6 {
            background: linear-gradient(120deg, #ec4899, #8b5cf6)
        }

        /* Focus */
        :focus-visible {
            outline: 2px solid transparent;
            box-shadow: 0 0 0 3px var(--ring) !important;
            border-radius: 10px
        }

        @media (max-width:576px) {
            .profile-hero {
                align-items: flex-start
            }
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
                            <span class="stat-chip"><i class="fa-solid fa-id-badge"></i><b>Nijoga:</b>
                                {{ $profile->nijoga_id }}</span>
                            <span class="stat-chip"><i class="fa-solid fa-envelope"></i>{{ $profile->email }}</span>
                            <span class="stat-chip"><i class="fa-solid fa-phone"></i>{{ $profile->phone_no }}</span>
                            <span class="stat-chip"><i class="fa-brands fa-whatsapp"></i>{{ $profile->whatsapp_no }}</span>
                        </div>
                    </div>
                </div>

                <!-- Donuts -->
                <div class="donut-card p-2 p-sm-3">
                    <div class="donut-rail">
                        <a class="donut text-decoration-none"
                            href="{{ route('profile.update', ['pratihari_id' => $profile->pratihari_id]) }}"
                            title="Edit Personal">
                            <canvas id="profileChart"></canvas>
                            <div class="label">Personal</div>
                            <div class="pct">{{ round($profileCompletion) }}%</div>
                        </a>
                        <a class="donut text-decoration-none"
                            href="{{ route('family.update', ['pratihari_id' => $profile->pratihari_id]) }}"
                            title="Edit Family">
                            <canvas id="familyChart"></canvas>
                            <div class="label">Family</div>
                            <div class="pct">{{ round($familyCompletion) }}%</div>
                        </a>
                        <a class="donut text-decoration-none"
                            href="{{ route('idcard.update', ['pratihari_id' => $profile->pratihari_id]) }}"
                            title="Edit ID Card">
                            <canvas id="idcardChart"></canvas>
                            <div class="label">ID Card</div>
                            <div class="pct">{{ round($idcardCompletion) }}%</div>
                        </a>
                        <a class="donut text-decoration-none"
                            href="{{ route('address.update', ['pratihari_id' => $profile->pratihari_id]) }}"
                            title="Edit Address">
                            <canvas id="addressChart"></canvas>
                            <div class="label">Address</div>
                            <div class="pct">{{ round($addressCompletion) }}%</div>
                        </a>
                        <a class="donut text-decoration-none"
                            href="{{ route('occupation.update', ['pratihari_id' => $profile->pratihari_id]) }}"
                            title="Edit Occupation">
                            <canvas id="occupationChart"></canvas>
                            <div class="label">Occupation</div>
                            <div class="pct">{{ round($occupationCompletion) }}%</div>
                        </a>
                        <a class="donut text-decoration-none"
                            href="{{ route('seba.update', ['pratihari_id' => $profile->pratihari_id]) }}"
                            title="Edit Seba">
                            <canvas id="sebaChart"></canvas>
                            <div class="label">Seba</div>
                            <div class="pct">{{ round($sebaCompletion) }}%</div>
                        </a>
                        <a class="donut text-decoration-none"
                            href="{{ route('social.update', ['pratihari_id' => $profile->pratihari_id]) }}"
                            title="Edit Social">
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
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#personal"
                        type="button" role="tab"><i class="fa-solid fa-user"></i> Personal</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#family" type="button"
                        role="tab"><i class="fa-solid fa-users"></i> Family</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#idcard" type="button"
                        role="tab"><i class="fa-solid fa-id-card"></i> ID Card</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#address" type="button"
                        role="tab"><i class="fa-solid fa-location-dot"></i> Address</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#occupation"
                        type="button" role="tab"><i class="fa-solid fa-briefcase"></i> Occupation</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#seba"
                        type="button" role="tab"><i class="fa-solid fa-gears"></i> Seba</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#social"
                        type="button" role="tab"><i class="fa-solid fa-share-nodes"></i> Social</button></li>
            </ul>
        </div>

        <!-- Tab content -->
        <div class="tab-content">
            <!-- PERSONAL -->
            <div class="tab-pane fade show active" id="personal" role="tabpanel">
                <div class="section-card p-3 p-md-4">
                    <div class="section-head">
                        <h6 class="section-title">
                            <i class="fa-solid fa-user-circle me-2" style="color:var(--amber)"></i>Personal Details
                        </h6>
                        <a href="{{ route('profile.update', ['pratihari_id' => $profile->pratihari_id]) }}"
                            class="btn btn-sm btn-amber">
                            <i class="fa-regular fa-pen-to-square me-1"></i>Edit
                        </a>
                    </div>

                    <div class="row g-3">
                        <div class="col-lg-6 profile-section">
                            <div class="profile-item">
                                <i class="fa-solid fa-user-tag"></i>
                                <div>
                                    <span class="key">Alias Name</span>
                                    <span class="val">{{ $profile->alias_name ?? 'Not Available' }}</span>
                                </div>
                            </div>

                            <div class="profile-item">
                                <i class="fa-solid fa-user-check"></i>
                                <div>
                                    <span class="key">Bhagari</span>
                                    <span class="val">
                                        @if (($profile->bhagari ?? 0) == 1)
                                            <span class="beddha-pill"><i class="fa-solid fa-check"></i>Yes</span>
                                        @else
                                            <span class="badge text-bg-secondary">No</span>
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="profile-item">
                                <i class="fa-solid fa-user-tie"></i>
                                <div>
                                    <span class="key">Baristha Bhai Pua</span>
                                    <span class="val">
                                        @if (($profile->baristha_bhai_pua ?? 0) == 1)
                                            <span class="beddha-pill"><i class="fa-solid fa-check"></i>Yes</span>
                                        @else
                                            <span class="badge text-bg-secondary">No</span>
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="profile-item">
                                <i class="fa-solid fa-id-card-clip"></i>
                                <div>
                                    <span class="key">Health Card No</span>
                                    <span class="val">{{ $profile->healthcard_no ?? 'Not Available' }}</span>
                                </div>
                            </div>

                            <div class="profile-item">
                                <i class="fa-solid fa-cake-candles"></i>
                                <div>
                                    <span class="key">Date of Birth</span>
                                    <span class="val">{{ $profile->date_of_birth ?? 'Not Available' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 profile-section">
                            <div class="profile-item">
                                <i class="fa-solid fa-droplet"></i>
                                <div>
                                    <span class="key">Blood Group</span>
                                    <span class="val">{{ $profile->blood_group ?? 'Not Available' }}</span>
                                </div>
                            </div>

                            <div class="profile-item">
                                <i class="fa-solid fa-calendar-check"></i>
                                <div>
                                    <span class="key">Joining Date</span>
                                    <span
                                        class="val">{{ $joiningDateText ?? ($profile->joining_date ?? 'Not Available') }}</span>
                                </div>
                            </div>

                            <div class="profile-item">
                                <i class="fa-solid fa-calendar-days"></i>
                                <div>
                                    <span class="key">Joining Year</span>
                                    <span
                                        class="val">{{ $joiningYearText ?? ($profile->joining_year ?? 'Not Available') }}</span>
                                </div>
                            </div>

                            {{-- ✅ FIX: use healthcard_photo (your controller stores healthcard_photo) --}}
                            @if (!empty($profile->healthcard_photo))
                                <div class="profile-item">
                                    <i class="fa-solid fa-image"></i>
                                    <div>
                                        <span class="key">Health Card Photo</span>
                                        <a href="{{ asset($profile->healthcard_photo) }}" target="_blank"
                                            class="btn btn-sm btn-ghost mt-1">
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
                        <h6 class="section-title"><i class="fa-solid fa-people-roof me-2"
                                style="color:var(--amber)"></i>Family Details</h6>
                        <a href="{{ route('family.update', ['pratihari_id' => $profile->pratihari_id]) }}"
                            class="btn btn-sm btn-amber"><i class="fa-regular fa-pen-to-square me-1"></i>Edit</a>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="section-card p-3 h-100">
                                <div class="text-center">
                                    <img class="rounded-circle mb-2" style="height:100px;width:100px;object-fit:cover;"
                                        src="{{ asset($family->father_photo ?? '') }}" alt="Father">
                                    <div class="fw-semibold">{{ $family->father_name ?? 'Not Available' }}</div>
                                    <div class="text-muted small">Father</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="section-card p-3 h-100">
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
                        <h6 class="fw-bold mb-2"><i class="fa-solid fa-heart me-2" style="color:var(--amber)"></i>Spouse
                            & In-Laws</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="section-card p-3 h-100 text-center">
                                    <img class="rounded-circle mb-2" style="height:100px;width:100px;object-fit:cover;"
                                        src="{{ asset($family->spouse_photo ?? '') }}" alt="Spouse">
                                    <div class="fw-semibold">{{ $family->spouse_name ?? 'Not Available' }}</div>
                                    <div class="text-muted small">Spouse</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="section-card p-3 h-100 text-center">
                                    <img class="rounded-circle mb-2" style="height:100px;width:100px;object-fit:cover;"
                                        src="{{ asset($family->spouse_father_photo ?? '') }}" alt="Spouse Father">
                                    <div class="fw-semibold">{{ $family->spouse_father_name ?? 'Not Available' }}</div>
                                    <div class="text-muted small">Spouse's Father</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="section-card p-3 h-100 text-center">
                                    <img class="rounded-circle mb-2" style="height:100px;width:100px;object-fit:cover;"
                                        src="{{ asset($family->spouse_mother_photo ?? '') }}" alt="Spouse Mother">
                                    <div class="fw-semibold">{{ $family->spouse_mother_name ?? 'Not Available' }}</div>
                                    <div class="text-muted small">Spouse's Mother</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <hr class="my-4">
                    <h6 class="fw-bold mb-2"><i class="fa-solid fa-child-reaching me-2"
                            style="color:var(--amber)"></i>Children</h6>
                    <div class="row g-3">
                        @forelse ($children as $child)
                            <div class="col-md-4">
                                <div class="section-card p-3 h-100 text-center">
                                    <img class="rounded-circle mb-2" style="height:120px;width:120px;object-fit:cover;"
                                        src="{{ asset($child->photo ?? '') }}" alt="Child">
                                    <div class="fw-semibold">{{ $child->children_name }}</div>
                                    <div class="text-muted small">{{ ucfirst($child->gender) }} • DOB:
                                        {{ date('d M Y', strtotime($child->date_of_birth)) }}</div>
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
                        <h6 class="section-title"><i class="fa-solid fa-id-card me-2" style="color:var(--amber)"></i>ID
                            Card Details</h6>
                        <a href="{{ route('idcard.update', ['pratihari_id' => $profile->pratihari_id]) }}"
                            class="btn btn-sm btn-amber"><i class="fa-regular fa-pen-to-square me-1"></i>Edit</a>
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
                                            <img src="{{ $card->id_photo }}" alt="ID Photo"
                                                class="img-fluid rounded mb-2" style="height:160px;object-fit:cover;">
                                        </a>
                                    </div>
                                    <div class="text-center small">
                                        <div class="mb-1"><span class="text-muted">ID Type:</span> <span
                                                class="fw-semibold">{{ $card->id_type ?? 'Not Available' }}</span></div>
                                        <div><span class="text-muted">ID Number:</span> <span
                                                class="fw-semibold">{{ $card->id_number ?? 'Not Available' }}</span></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if ($idcard->isEmpty())
                            <div class="col-12 text-center text-muted">No ID cards added.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ADDRESS (colorful duo) -->
            <div class="tab-pane fade" id="address" role="tabpanel">
                <div class="section-card p-3 p-md-4">
                    <div class="section-head">
                        <h6 class="section-title"><i class="fa-solid fa-location-dot me-2"
                                style="color:var(--amber)"></i>Address Details</h6>
                        <a href="{{ route('address.update', ['pratihari_id' => $profile->pratihari_id]) }}"
                            class="btn btn-sm btn-amber">
                            <i class="fa-regular fa-pen-to-square me-1"></i>Edit
                        </a>
                    </div>

                    @php
                        $addr = $profile->address ?? null;

                        $current = [
                            ['icon' => 'fa-map-marked-alt', 'label' => 'Address', 'value' => $addr->address ?? null],
                            ['icon' => 'fa-map-signs', 'label' => 'Sahi', 'value' => $addr->sahi ?? null],
                            ['icon' => 'fa-thumbtack', 'label' => 'Landmark', 'value' => $addr->landmark ?? null],
                            ['icon' => 'fa-envelope', 'label' => 'Pincode', 'value' => $addr->pincode ?? null],
                            ['icon' => 'fa-mail-bulk', 'label' => 'Post', 'value' => $addr->post ?? null],
                            [
                                'icon' => 'fa-user-shield',
                                'label' => 'Police Station',
                                'value' => $addr->police_station ?? null,
                            ],
                            ['icon' => 'fa-city', 'label' => 'District', 'value' => $addr->district ?? null],
                            ['icon' => 'fa-map', 'label' => 'State', 'value' => $addr->state ?? null],
                            ['icon' => 'fa-flag', 'label' => 'Country', 'value' => $addr->country ?? null],
                        ];

                        $permanent = [
                            ['icon' => 'fa-map-marked', 'label' => 'Address', 'value' => $addr->per_address ?? null],
                            ['icon' => 'fa-map-signs', 'label' => 'Sahi', 'value' => $addr->per_sahi ?? null],
                            ['icon' => 'fa-thumbtack', 'label' => 'Landmark', 'value' => $addr->per_landmark ?? null],
                            ['icon' => 'fa-envelope', 'label' => 'Pincode', 'value' => $addr->per_pincode ?? null],
                            ['icon' => 'fa-mail-bulk', 'label' => 'Post', 'value' => $addr->per_post ?? null],
                            [
                                'icon' => 'fa-user-shield',
                                'label' => 'Police Station',
                                'value' => $addr->per_police_station ?? null,
                            ],
                            ['icon' => 'fa-city', 'label' => 'District', 'value' => $addr->per_district ?? null],
                            ['icon' => 'fa-map', 'label' => 'State', 'value' => $addr->per_state ?? null],
                            ['icon' => 'fa-flag', 'label' => 'Country', 'value' => $addr->per_country ?? null],
                        ];

                        $isEmptyList = function ($list) {
                            foreach ($list as $x) {
                                if (!empty($x['value'])) {
                                    return false;
                                }
                            }
                            return true;
                        };
                    @endphp

                    <div class="row address-duo g-3 g-lg-4">
                        <!-- Current Address Card -->
                        <div class="col-lg-6">
                            <div class="addr-card h-100">
                                <div class="addr-top">
                                    <i class="fa-solid fa-map-pin"></i>
                                    <div class="title">Current Address</div>
                                    <span class="badge-soft">Now</span>
                                </div>
                                <div class="addr-body">
                                    @if ($isEmptyList($current))
                                        <div class="text-muted text-center py-3">Not Available</div>
                                    @else
                                        <!-- Tagline -->
                                        <div class="mb-2">
                                            <span class="tagline"><i class="fa-solid fa-location-crosshairs"></i> Where
                                                you stay now</span>
                                        </div>
                                        @foreach ($current as $row)
                                            <div class="addr-row">
                                                <i class="fa-solid {{ $row['icon'] }}"></i>
                                                <div>
                                                    <span class="k">{{ $row['label'] }}</span>
                                                    <span class="v">{{ $row['value'] ?? 'Not Available' }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="addr-foot">
                                    <button type="button" class="btn btn-ghost btn-sm btn-round copy-tip"
                                        data-clip="{{ trim(($addr->address ?? '') . ' ' . ($addr->sahi ?? '') . ' ' . ($addr->district ?? '') . ' ' . ($addr->state ?? '') . ' ' . ($addr->pincode ?? '')) }}">
                                        <i class="fa-regular fa-copy me-1"></i>Copy
                                    </button>
                                    <a href="https://www.google.com/maps/search/{{ urlencode(trim(($addr->address ?? '') . ' ' . ($addr->district ?? '') . ' ' . ($addr->state ?? '') . ' ' . ($addr->pincode ?? ''))) }}"
                                        target="_blank" class="btn btn-ghost btn-sm btn-round">
                                        <i class="fa-solid fa-location-arrow me-1"></i>Open in Maps
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Permanent Address Card -->
                        <div class="col-lg-6">
                            <div class="addr-card h-100">
                                <div class="addr-top perm">
                                    <i class="fa-solid fa-house-chimney"></i>
                                    <div class="title">Permanent Address</div>
                                    <span class="badge-soft">Home</span>
                                </div>
                                <div class="addr-body">
                                    @if ($isEmptyList($permanent))
                                        <div class="text-muted text-center py-3">Not Available</div>
                                    @else
                                        <div class="mb-2">
                                            <span class="tagline"><i class="fa-solid fa-house"></i> Family / native
                                                place</span>
                                        </div>
                                        @foreach ($permanent as $row)
                                            <div class="addr-row">
                                                <i class="fa-solid {{ $row['icon'] }}"></i>
                                                <div>
                                                    <span class="k">{{ $row['label'] }}</span>
                                                    <span class="v">{{ $row['value'] ?? 'Not Available' }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="addr-foot">
                                    <button type="button" class="btn btn-ghost btn-sm btn-round copy-tip"
                                        data-clip="{{ trim(($addr->per_address ?? '') . ' ' . ($addr->per_sahi ?? '') . ' ' . ($addr->per_district ?? '') . ' ' . ($addr->per_state ?? '') . ' ' . ($addr->per_pincode ?? '')) }}">
                                        <i class="fa-regular fa-copy me-1"></i>Copy
                                    </button>
                                    <a href="https://www.google.com/maps/search/{{ urlencode(trim(($addr->per_address ?? '') . ' ' . ($addr->per_district ?? '') . ' ' . ($addr->per_state ?? '') . ' ' . ($addr->per_pincode ?? ''))) }}"
                                        target="_blank" class="btn btn-ghost btn-sm btn-round">
                                        <i class="fa-solid fa-location-arrow me-1"></i>Open in Maps
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div><!-- /row -->
                </div>
            </div>

            <!-- OCCUPATION (unchanged) -->
            <div class="tab-pane fade" id="occupation" role="tabpanel">
                <div class="section-card p-3 p-md-4">
                    <div class="section-head">
                        <h6 class="section-title"><i class="fa-solid fa-briefcase me-2"
                                style="color:var(--amber)"></i>Occupation Details</h6>
                        <a href="{{ route('occupation.update', ['pratihari_id' => $profile->pratihari_id]) }}"
                            class="btn btn-sm btn-amber"><i class="fa-regular fa-pen-to-square me-1"></i>Edit</a>
                    </div>

                    @if ($occupation->isNotEmpty())
                        <div class="row">
                            <div class="col-lg-6 profile-section">
                                <div class="profile-item"><i class="fa-solid fa-user-tie"></i>
                                    <div><span class="key">Occupation Type</span>
                                        <span
                                            class="val">{{ optional($occupation->first())->occupation_type ?? 'Not Available' }}</span>
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

            <!-- SEBA (kept from your latest colorful grid) -->
            <div class="tab-pane fade" id="seba" role="tabpanel">
                <div class="section-card p-3 p-md-4">
                    <div class="section-head">
                        <h6 class="section-title"><i class="fa-solid fa-gears me-2" style="color:var(--amber)"></i>Seba
                            Details</h6>
                        {{-- <a href="{{ route('seba.update', ['pratihari_id' => $profile->pratihari_id]) }}" class="btn btn-sm btn-amber">
                        <i class="fa-regular fa-pen-to-square me-1"></i>Edit
                    </a> --}}
                    </div>

                    @php $grads = ['grad-1','grad-2','grad-3','grad-4','grad-5','grad-6']; @endphp

                    @forelse ($sebaDetails as $idx => $s)
                        @php
                            $beddhas = $s->beddhas();
                            $grad = $grads[$idx % count($grads)];
                            $sebaName = $s->sebaMaster->seba_name ?? 'Not Available';
                        @endphp

                        @if ($idx % 3 === 0)
                            <div class="row g-3 g-lg-4">
                        @endif

                        <div class="col-md-6 col-lg-4">
                            <div class="seba-card">
                                <div class="seba-head {{ $grad }}">
                                    <i class="fa-solid fa-hand-holding-heart"></i>
                                    <div class="seba-title">{{ $sebaName }}</div>
                                    <span class="badge-soft">Seba</span>
                                </div>
                                <div class="seba-body">
                                    <div class="row-line">
                                        <i class="fa-solid fa-tags"></i>
                                        <div>
                                            <span class="k">Seba Name</span>
                                            <span class="v">{{ $sebaName }}</span>
                                        </div>
                                    </div>
                                    <div class="row-line">
                                        <i class="fa-solid fa-user-group"></i>
                                        <div class="w-100">
                                            <span class="k">Bheddha Assigned</span>
                                            @if ($beddhas->isNotEmpty())
                                                <div class="mt-1 d-flex flex-wrap gap-1">
                                                    @foreach ($beddhas as $beddha)
                                                        <span class="seba-chip"><i
                                                                class="fa-solid fa-user-tag"></i>{{ $beddha->beddha_name }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-muted">Not Assigned</div>
                                            @endif
                                        </div>
                                    </div>
                                    @if (!empty($s->remarks))
                                        <div class="row-line">
                                            <i class="fa-solid fa-note-sticky"></i>
                                            <div>
                                                <span class="k">Remarks</span>
                                                <span class="v">{{ $s->remarks }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if ($idx % 3 === 2 || $loop->last)
                </div>
                @endif
            @empty
                <p class="text-muted mb-0">No seba details available.</p>
                @endforelse
            </div>
        </div>

        <!-- SOCIAL -->
        <div class="tab-pane fade" id="social" role="tabpanel">
            <div class="section-card p-3 p-md-4">
                <div class="section-head">
                    <h6 class="section-title"><i class="fa-solid fa-share-nodes me-2"
                            style="color:var(--amber)"></i>Social Media</h6>
                    <a href="{{ route('social.update', ['pratihari_id' => $profile->pratihari_id]) }}"
                        class="btn btn-sm btn-amber"><i class="fa-regular fa-pen-to-square me-1"></i>Edit</a>
                </div>

                <div class="row g-3">
                    @php
                        $socialLinks = [
                            ['fab fa-facebook-f', 'bg-primary', 'Facebook', $socialMedia->facebook_url ?? '#'],
                            ['fab fa-instagram', 'bg-danger', 'Instagram', $socialMedia->instagram_url ?? '#'],
                            ['fab fa-twitter', 'bg-info', 'Twitter', $socialMedia->twitter_url ?? '#'],
                            ['fab fa-linkedin-in', 'bg-success', 'LinkedIn', $socialMedia->linkedin_url ?? '#'],
                            ['fab fa-youtube', 'bg-danger', 'YouTube', $socialMedia->youtube_url ?? '#'],
                        ];
                    @endphp

                    @foreach ($socialLinks as $s)
                        <div class="col-md-6 col-lg-4">
                            <div class="section-card p-3 d-flex align-items-center gap-3 h-100">
                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center {{ $s[1] }}"
                                    style="width:44px;height:44px;">
                                    <i class="{{ $s[0] }}"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $s[2] }}</div>
                                    <a href="{{ $s[3] ?: '#' }}" target="_blank"
                                        class="text-muted small text-decoration-none">
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

    <script>
        // Donut chart init
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
            profileChart: '#4CAF50',
            familyChart: '#FF9800',
            idcardChart: '#2196F3',
            addressChart: '#673AB7',
            occupationChart: '#009688',
            sebaChart: '#FF5722',
            socialmediaChart: '#E91E63',
        };
        Object.keys(chartData).forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            const v = Math.max(0, Math.min(100, Number(chartData[id]) || 0));
            new Chart(el.getContext('2d'), {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [v, 100 - v],
                        backgroundColor: [chartColors[id], '#e5e7eb'],
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '72%',
                    responsive: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: false
                        }
                    }
                }
            });
        });

        // Copy buttons in Address cards
        document.querySelectorAll('.copy-tip').forEach(btn => {
            btn.addEventListener('click', async () => {
                try {
                    const text = btn.getAttribute('data-clip') || '';
                    await navigator.clipboard.writeText(text.trim());
                    btn.setAttribute('data-copied', '1');
                    setTimeout(() => btn.removeAttribute('data-copied'), 1200);
                } catch (e) {}
            });
        });
    </script>
@endsection
