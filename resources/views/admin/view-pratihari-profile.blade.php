@extends('layouts.app')
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/profile.css') }}" rel="stylesheet">
@endsection

@section('content')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <span class="main-content-title mg-b-0 mg-b-lg-1">PROFILE DETAILS</span>
        </div>
        <div class="justify-content-center mt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Pages</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile Details</li>
            </ol>
        </div>
    </div>
    <!-- /breadcrumb -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="card custom-card">
                    <div class="card-body d-md-flex align-items-center">
                        <div class="me-4">
                            <span class="profile-image">
                                <img class="br-5" src="{{ asset($profile->profile_photo) }}" alt="Profile Photo">
                                <span class="profile-online"></span>
                            </span>
                        </div>

                        <div class="my-md-auto mt-4 prof-details">
                            <h4>{{ $profile->first_name }} {{ $profile->last_name }}</h4>
                            <p><i class="fa fa-user me-2"></i> <b>Nijoga Id:</b> {{ $profile->nijoga_id }}</p>
                            <p><i class="fa fa-envelope me-2"></i> <b>Email:</b> {{ $profile->email }}</p>
                            <p><i class="fa fa-phone me-2"></i> <b>Phone:</b> {{ $profile->phone_no }}</p>
                            <p><i class="fa fa-globe me-2"></i> <b>Whatsapp:</b> {{ $profile->whatsapp_no }}</p>
                        </div>
                    </div>

                    <div class="card-footer py-3">
                        <nav class="nav main-nav-line profile-nav-line">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personal">Personal</a>
                            <a class="nav-link" data-bs-toggle="tab" href="#family">Family</a>
                            <a class="nav-link" data-bs-toggle="tab" href="#idcard">Id Card</a>
                            <a class="nav-link" data-bs-toggle="tab" href="#address">Address</a>
                            <a class="nav-link" data-bs-toggle="tab" href="#occupation">Occupation</a>
                            <a class="nav-link" data-bs-toggle="tab" href="#seba">Seba</a>
                            <a class="nav-link" data-bs-toggle="tab" href="#social">Social Media</a>
                        </nav>
                    </div>

                    <div class="progress-container-wrapper">
                        @foreach ([['Personal', $profileCompletion, 'profileChart', '#4CAF50', 'profile.update'], ['Family', $familyCompletion, 'familyChart', '#FF9800', 'family.update'], ['ID Card', $idcardCompletion, 'idcardChart', '#2196F3', 'idcard.update'], ['Address', $addressCompletion, 'addressChart', '#673AB7', 'address.update'], ['Occupation', $occupationCompletion, 'occupationChart', '#009688', 'occupation.update'], ['Seba', $sebaCompletion, 'sebaChart', '#FF5722', 'seba.update'], ['Social Media', $socialmediaCompletion, 'socialmediaChart', '#E91E63', 'social.update']] as $data)
                            <a href="{{ route($data[4], ['pratihari_id' => $profile->pratihari_id]) }}"
                                class="progress-card-link">
                                <div class="progress-card">
                                    <label><strong>{{ $data[0] }}:</strong> {{ round($data[1]) }}%</label>
                                    <div class="chart-container">
                                        <canvas id="{{ $data[2] }}"></canvas>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>

        <!-- Row -->
        <div class="row row-sm">
            <div class="col-lg-12 col-md-12">
                <div class="main-content-body-profile">
                    <div class="tab-content">
                        <!-- Personal Information -->

                        <div class="main-content-body tab-pane active" id="personal">
                            <div class="card personal-details-card">
                                <div class="card-body">
                                    <h4 class="fw-bold" style="color: rgb(6, 6, 6)"><i class="fas fa-user-circle"
                                            style="color:rgb(61, 33, 218)"></i> Personal Details</h4>

                                    <div class="personal-details-item">
                                        <i class="fas fa-id-card"></i>
                                        <div>
                                            <span class="personal-details-text">Health Card No:</span>
                                            <span
                                                class="personal-details-value">{{ $profile->healthcard_no ?? 'Not Available' }}</span>
                                        </div>
                                    </div>

                                    <div class="personal-details-item">
                                        <i class="fas fa-birthday-cake"></i>
                                        <div>
                                            <span class="personal-details-text">Date of Birth:</span>
                                            <span
                                                class="personal-details-value">{{ $profile->date_of_birth ?? 'Not Available' }}</span>
                                        </div>
                                    </div>

                                    <div class="personal-details-item">
                                        <i class="fas fa-tint"></i>
                                        <div>
                                            <span class="personal-details-text">Blood Group:</span>
                                            <span
                                                class="personal-details-value">{{ $profile->blood_group ?? 'Not Available' }}</span>
                                        </div>
                                    </div>

                                    <div class="personal-details-item">
                                        <i class="fas fa-calendar-check"></i>
                                        <div>
                                            <span class="personal-details-text">Joining Date:</span>
                                            <span
                                                class="personal-details-value">{{ $profile->joining_date ?? 'Not Available' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Family Information -->
                        <div class="main-content-body tab-pane border-top-0" id="family">
                            <div class="card p-4 shadow-lg">
                                <h4 class="fw-bold text-center mb-4" style="color: rgb(1, 1, 66)">Family Details</h4>

                                <!-- Parent Details Section -->
                                <div class="family-section">
                                    <h4 class="fw-bold" style="color:rgb(1, 1, 66)"><i class="fas fa-users"
                                            style="color:rgb(85, 1, 15)"></i> Parents</h4>
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <div class="card custom-card border shadow-sm p-3">
                                                <div class="card-body">
                                                    <div class="family-photo-container">
                                                        <img alt="Father" class="profile-imgs"
                                                            style="height: 100px;width:100px"
                                                            src="{{ $family->father_photo }}">
                                                    </div>
                                                    <h5 class="mt-3 text-dark fw-semibold">
                                                        {{ $family->father_name ?? 'Not Available' }}</h5>
                                                    <span class="text-muted">Father</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card custom-card border shadow-sm p-3">
                                                <div class="card-body">
                                                    <div class="family-photo-container">
                                                        <img alt="Mother" class="profile-imgs"
                                                            style="height: 100px;width:100px"
                                                            src="{{ $family->mother_photo }}">
                                                    </div>
                                                    <h5 class="mt-3 text-dark fw-semibold">
                                                        {{ $family->mother_name ?? 'Not Available' }}</h5>
                                                    <span class="text-muted">Mother</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Spouse Details Section -->
                                @if ($family->maritial_status == 'married')
                                    <div class="family-section">
                                        <h4 class="fw-bold" style="color:rgb(1, 1, 66)"><i class="fas fa-heart "
                                                style="color:rgb(85, 1, 15)"></i> Spouse</h4>
                                        <div class="text-center">
                                            <div class="card custom-card border shadow-sm p-3 d-inline-block">
                                                <div class="card-body">
                                                    <div class="family-photo-container">
                                                        <img alt="Spouse" class="profile-imgs"
                                                            style="height: 100px;width: 100px"
                                                            src="{{ $family->spouse_photo }}">
                                                    </div>
                                                    <h5 class="mt-3 text-dark fw-semibold">
                                                        {{ $family->spouse_name ?? 'Not Available' }}</h5>
                                                    <span class="text-muted">Spouse</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Children Details Section -->
                                <div class="family-section">
                                    <h4 class="fw-bold" style="color:rgb(1, 1, 66)"><i class="fas fa-child"
                                            style="color:rgb(85, 1, 15)"></i> Children</h4>
                                    <div class="row">
                                        @forelse ($children as $child)
                                            <div class="col-md-4">
                                                <div class="card custom-card border shadow-sm p-3 text-center">
                                                    <div class="card-body">
                                                        <div class="family-photo-container">
                                                            <img alt="Child" class="profile-imgs"
                                                                style="width: 150px;height: 150px"
                                                                src="{{ $child->photo }}">
                                                        </div>
                                                        <h5 class="mt-3 text-dark fw-semibold">{{ $child->children_name }}
                                                        </h5>
                                                        <span class="text-muted">{{ $child->gender }} - DOB:
                                                            {{ date('d M Y', strtotime($child->date_of_birth)) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-center text-muted">No Children Details Available</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ID Card Details -->
                        <div class="tab-pane fade" id="idcard">
                            <div class="card profile-section">
                                <div class="card-body">
                                    <h4 class="fw-bold" style="color:rgb(1, 1, 66)"><i class="fas fa-id-card"></i> ID
                                        Card
                                        Details</h4>

                                    <div class="row">
                                        @foreach ($idcard as $card)
                                            <div class="col-md-4"> <!-- 3 ID cards per row -->
                                                <div class="id-card {{ strtolower($card->id_type ?? '') }}">
                                                    <div class="id-card-header">
                                                        <h5>{{ strtoupper($card->id_type ?? 'ID CARD') }}</h5>
                                                    </div>
                                                    <div class="id-card-body">
                                                        <div class="id-photo text-center">
                                                            <img src="{{ $card->id_photo }}" alt="ID Photo">
                                                        </div>
                                                        <div class="id-details text-center">
                                                            <p><strong>ID Type:</strong>
                                                                {{ $card->id_type ?? 'Not Available' }}</p>
                                                            <p><strong>ID Number:</strong>
                                                                {{ $card->id_number ?? 'Not Available' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Address Details -->
                        <div class="tab-pane fade" id="address">
                            <div class="card profile-section">
                                <div class="card-body">
                                    <h4 class="fw-bold" style="color:rgb(1, 1, 66)"><i class="fas fa-map-marker-alt"></i>
                                        Address Details
                                    </h4>

                                    <!-- Current Address -->
                                    <div class="profile-item">
                                        <i class="fas fa-map-marked-alt text-primary"></i>
                                        <div>
                                            <span class="profile-text">Current Address:</span>
                                            <span
                                                class="profile-value">{{ $profile->address->address ?? 'Not Available' }}</span>
                                        </div>
                                    </div>

                                    <div class="profile-item">
                                        <i class="fas fa-map-signs text-success"></i>
                                        <div>
                                            <span class="profile-text">Sahi:</span>
                                            <span
                                                class="profile-value">{{ $profile->address->sahiDetail->sahi_name ?? 'Not Available' }}</span>

                                        </div>
                                    </div>

                                    <div class="profile-item">
                                        <i class="fas fa-thumbtack text-danger"></i>
                                        <div>
                                            <span class="profile-text">Landmark:</span>
                                            <span
                                                class="profile-value">{{ $profile->address->landmark ?? 'Not Available' }}</span>
                                        </div>
                                    </div>

                                    <div class="profile-item">
                                        <i class="fas fa-envelope text-info"></i>
                                        <div>
                                            <span class="profile-text">Pincode:</span>
                                            <span
                                                class="profile-value">{{ $profile->address->pincode ?? 'Not Available' }}</span>
                                        </div>
                                    </div>

                                    <div class="profile-item">
                                        <i class="fas fa-mail-bulk text-primary"></i>
                                        <div>
                                            <span class="profile-text">Post:</span>
                                            <span
                                                class="profile-value">{{ $profile->address->post ?? 'Not Available' }}</span>
                                        </div>
                                    </div>

                                    <div class="profile-item">
                                        <i class="fas fa-user-shield text-warning"></i>
                                        <div>
                                            <span class="profile-text">Police Station:</span>
                                            <span
                                                class="profile-value">{{ $profile->address->police_station ?? 'Not Available' }}</span>
                                        </div>
                                    </div>

                                    <div class="profile-item">
                                        <i class="fas fa-city text-secondary"></i>
                                        <div>
                                            <span class="profile-text">District:</span>
                                            <span
                                                class="profile-value">{{ $profile->address->district ?? 'Not Available' }}</span>
                                        </div>
                                    </div>

                                    <div class="profile-item">
                                        <i class="fas fa-map text-success"></i>
                                        <div>
                                            <span class="profile-text">State:</span>
                                            <span
                                                class="profile-value">{{ $profile->address->state ?? 'Not Available' }}</span>
                                        </div>
                                    </div>

                                    <div class="profile-item">
                                        <i class="fas fa-flag text-danger"></i>
                                        <div>
                                            <span class="profile-text">Country:</span>
                                            <span
                                                class="profile-value">{{ $profile->address->country ?? 'Not Available' }}</span>
                                        </div>
                                    </div>

                                    <hr class="my-3">

                                    <h5 class="fw-bold text-info"><i class="fas fa-home"></i> Permanent Address</h5>

                                    <div class="profile-item">
                                        <i class="fas fa-map-marked text-primary"></i>
                                        <div>
                                            <span class="profile-text">Permanent Address:</span>
                                            <span
                                                class="profile-value">{{ $profile->address->per_address ?? 'Not Available' }}</span>
                                        </div>
                                    </div>

                                    <div class="profile-item">
                                        <i class="fas fa-map-signs text-success"></i>
                                        <div>
                                            <span class="profile-text">Sahi:</span>
                                            <span
                                                class="profile-value">{{ $profile->address->per_sahi ?? 'Not Available' }}</span>
                                        </div>
                                    </div>

                                    <div class="profile-item">
                                        <i class="fas fa-thumbtack text-danger"></i>
                                        <div>
                                            <span class="profile-text">Landmark:</span>
                                            <span
                                                class="profile-value">{{ $profile->address->per_landmark ?? 'Not Available' }}</span>
                                        </div>
                                    </div>

                                    <div class="profile-item">
                                        <i class="fas fa-envelope text-info"></i>
                                        <div>
                                            <span class="profile-text">Pincode:</span>
                                            <span
                                                class="profile-value">{{ $profile->address->per_pincode ?? 'Not Available' }}</span>
                                        </div>
                                    </div>

                                    <div class="profile-item">
                                        <i class="fas fa-mail-bulk text-primary"></i>
                                        <div>
                                            <span class="profile-text">Post:</span>
                                            <span
                                                class="profile-value">{{ $profile->address->per_post ?? 'Not Available' }}</span>
                                        </div>
                                    </div>

                                    <div class="profile-item">
                                        <i class="fas fa-user-shield text-warning"></i>
                                        <div>
                                            <span class="profile-text">Police Station:</span>
                                            <span
                                                class="profile-value">{{ $profile->address->per_police_station ?? 'Not Available' }}</span>
                                        </div>
                                    </div>

                                    <div class="profile-item">
                                        <i class="fas fa-city text-secondary"></i>
                                        <div>
                                            <span class="profile-text">District:</span>
                                            <span
                                                class="profile-value">{{ $profile->address->per_district ?? 'Not Available' }}</span>
                                        </div>
                                    </div>

                                    <div class="profile-item">
                                        <i class="fas fa-map text-success"></i>
                                        <div>
                                            <span class="profile-text">State:</span>
                                            <span
                                                class="profile-value">{{ $profile->address->per_state ?? 'Not Available' }}</span>
                                        </div>
                                    </div>

                                    <div class="profile-item">
                                        <i class="fas fa-flag text-danger"></i>
                                        <div>
                                            <span class="profile-text">Country:</span>
                                            <span
                                                class="profile-value">{{ $profile->address->per_country ?? 'Not Available' }}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Occupation Details -->
                        <div class="tab-pane fade" id="occupation">
                            <div class="card profile-section">
                                <div class="card-body">
                                    <h4 class="fw-bold text-primary"><i class="fas fa-briefcase"></i> Occupation Details
                                    </h4>

                                    @if ($occupation->isNotEmpty())
                                        <div class="profile-item">
                                            <i class="fas fa-user-tie"></i>
                                            <div>
                                                <span class="profile-text">Occupation Type:</span>
                                                <span
                                                    class="profile-value">{{ optional($occupation->first())->occupation_type ?? 'Not Available' }}</span>
                                            </div>
                                        </div>

                                        <div class="profile-item">
                                            <i class="fas fa-certificate"></i>
                                            <div>
                                                <span class="profile-text">Extra Activities:</span>
                                                <div class="profile-value">
                                                    @if (!empty(optional($occupation->first())->extra_activity))
                                                        @foreach (explode(',', optional($occupation->first())->extra_activity) as $activity)
                                                            <span
                                                                class="badge bg-success me-1">{{ trim($activity) }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">Not Available</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted">No occupation details available.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Seba Details -->
                        <div class="tab-pane fade" id="seba">
                            <div class="card profile-section">
                                <div class="card-body">
                                    <h4 class="fw-bold text-primary"><i class="fas fa-hands-helping"></i> Seba Details
                                    </h4>

                                    @foreach ($sebaDetails as $seba)
                                        <!-- Nijoga Section -->
                                        <div class="profile-item d-flex align-items-center">
                                            <i class="fas fa-user-shield text-primary me-2"></i>
                                            <div>
                                                <span class="profile-text fw-bold">Nijoga:</span>
                                                <span
                                                    class="profile-value">{{ $seba->nijogaMaster->nijoga_name ?? 'Not Available' }}</span>
                                            </div>
                                        </div>

                                        <!-- Seba Name Section -->
                                        <div class="profile-item d-flex align-items-center">
                                            <i class="fas fa-praying-hands text-success me-2"></i>
                                            <div>
                                                <span class="profile-text fw-bold">Seba Name:</span>
                                                <span
                                                    class="profile-value">{{ $seba->sebaMaster->seba_name ?? 'Not Available' }}</span>
                                            </div>
                                        </div>

                                        <!-- Beddha Assigned Section -->
                                        <div class="profile-item d-flex align-items-center">
                                            <i class="fas fa-link text-danger me-2"></i>
                                            <div>
                                                <span class="profile-text fw-bold">Beddha Assigned:</span>
                                                <div class="profile-value mt-1">
                                                    @if ($seba->beddhaMaster->isNotEmpty())
                                                        @foreach ($seba->beddhaMaster as $beddha)
                                                            <span
                                                                class="badge bg-success me-1">{{ $beddha->beddha_name }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">Not Assigned</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="my-3"> <!-- Stylish separator -->
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="social">
                            <div class="card">
                                <div class="p-4">
                                    <label class="main-content-label tx-13 mg-b-20 fw-bold text-primary">
                                        <i class="fas fa-share-alt"></i> Connect with Us
                                    </label>
                                    <div class="d-lg-flex flex-wrap">
                                        @php
                                            $socialLinks = [
                                                [
                                                    'icon' => 'fab fa-facebook-f',
                                                    'color' => 'bg-primary',
                                                    'text' => 'Facebook',
                                                    'url' => $socialMedia->facebook_url ?? '#',
                                                ],
                                                [
                                                    'icon' => 'fab fa-instagram',
                                                    'color' => 'bg-danger',
                                                    'text' => 'Instagram',
                                                    'url' => $socialMedia->instagram_url ?? '#',
                                                ],
                                                [
                                                    'icon' => 'fab fa-twitter',
                                                    'color' => 'bg-info',
                                                    'text' => 'Twitter',
                                                    'url' => $socialMedia->twitter_url ?? '#',
                                                ],
                                                [
                                                    'icon' => 'fab fa-linkedin-in',
                                                    'color' => 'bg-success',
                                                    'text' => 'LinkedIn',
                                                    'url' => $socialMedia->linkedin_url ?? '#',
                                                ],
                                                [
                                                    'icon' => 'fab fa-youtube',
                                                    'color' => 'bg-danger',
                                                    'text' => 'YouTube',
                                                    'url' => $socialMedia->youtube_url ?? '#',
                                                ],
                                            ];
                                        @endphp

                                        @foreach ($socialLinks as $social)
                                            <div class="mg-md-r-20 mg-b-10">
                                                <div class="main-profile-social-list">
                                                    <div class="media d-flex align-items-center">
                                                        <div class="media-icon {{ $social['color'] }} text-white rounded-circle d-flex align-items-center justify-content-center"
                                                            style="width: 40px; height: 40px;">
                                                            <i class="{{ $social['icon'] }} fa-lg"></i>
                                                        </div>
                                                        <div class="media-body ms-3">
                                                            <span class="fw-bold">{{ $social['text'] }}</span>
                                                            <a href="{{ $social['url'] }}" target="_blank"
                                                                class="d-block text-muted">{{ parse_url($social['url'], PHP_URL_HOST) ?: 'Not Available' }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const charts = [{
                    id: 'profileChart',
                    data: {{ $profileCompletion }},
                    color: '#4CAF50'
                },
                {
                    id: 'familyChart',
                    data: {{ $familyCompletion }},
                    color: '#FF9800'
                },
                {
                    id: 'idcardChart',
                    data: {{ $idcardCompletion }},
                    color: '#2196F3'
                },
                {
                    id: 'addressChart',
                    data: {{ $addressCompletion }},
                    color: '#673AB7'
                },
                {
                    id: 'occupationChart',
                    data: {{ $occupationCompletion }},
                    color: '#009688'
                },
                {
                    id: 'sebaChart',
                    data: {{ $sebaCompletion }},
                    color: '#FF5722'
                },
                {
                    id: 'socialmediaChart',
                    data: {{ $socialmediaCompletion }},
                    color: '#E91E63'
                }
            ];

            charts.forEach(chart => {
                const ctx = document.getElementById(chart.id).getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Completed', 'Remaining'],
                        datasets: [{
                            data: [chart.data, 100 - chart.data],
                            backgroundColor: [chart.color, '#E8E8E8']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>
    <!-- Include SweetAlert Library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@endsection
