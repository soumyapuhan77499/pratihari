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
        <style>
      .profile-image img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #ddd;
    }

    .prof-details h4 {
        margin-bottom: 5px;
        font-weight: bold;
    }

    .prof-details p {
        margin-bottom: 3px;
        color: #555;
    }

    .progress-container-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .progress-card-link {
        text-decoration: none;
        flex: 1 1 160px;
        max-width: 180px;
    }

    .progress-card {
        background: #f5f5f5;
        border-radius: 12px;
        padding: 12px;
        text-align: center;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease;
        height: 120px;
    }

    .progress-card:hover {
        transform: translateY(-3px);
    }

    .progress-card label {
        font-size: 14px;
        font-weight: 600;
        color: #333;
        display: block;
        margin-bottom: 8px;
    }

    .chart-container {
        height: 40px;
    }

    @media (max-width: 576px) {
        .progress-container-wrapper {
            flex-direction: column;
            align-items: stretch;
        }

        .progress-card-link {
            max-width: 100%;
        }
    }
</style>

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

                <div class="ms-md-auto progress-container-wrapper">
                    @foreach ([
                        ['Personal', $profileCompletion, 'profileChart', '#4CAF50', 'profile.update'],
                        ['Family', $familyCompletion, 'familyChart', '#FF9800', 'family.update'],
                        ['ID Card', $idcardCompletion, 'idcardChart', '#2196F3', 'idcard.update'],
                        ['Address', $addressCompletion, 'addressChart', '#673AB7', 'address.update'],
                        ['Occupation', $occupationCompletion, 'occupationChart', '#009688', 'occupation.update'],
                        ['Seba', $sebaCompletion, 'sebaChart', '#FF5722', 'seba.update'],
                        ['Social Media', $socialmediaCompletion, 'socialmediaChart', '#E91E63', 'social.update'],
                    ] as $data)
                        <a href="{{ route($data[4], ['pratihari_id' => $profile->pratihari_id]) }}" class="progress-card-link">
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

            <div class="card-footer py-3">
                <nav class="nav main-nav-line profile-nav-line">
                    <a style="color: white" class="nav-link active" data-bs-toggle="tab" href="#personal">Personal</a>
                    <a style="color: white" class="nav-link" data-bs-toggle="tab" href="#family">Family</a>
                    <a style="color: white" class="nav-link" data-bs-toggle="tab" href="#idcard">Id Card</a>
                    <a style="color: white" class="nav-link" data-bs-toggle="tab" href="#address">Address</a>
                    <a style="color: white" class="nav-link" data-bs-toggle="tab" href="#occupation">Occupation</a>
                    <a style="color: white" class="nav-link" data-bs-toggle="tab" href="#seba">Seba</a>
                    <a style="color: white" class="nav-link" data-bs-toggle="tab" href="#social">Social Media</a>
                </nav>
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

                                    @if (!empty($profile->health_card_photo))
                                        <div class="personal-details-item d-flex align-items-center">
                                            <i class="fas fa-image me-2"></i>
                                            <span class="personal-details-text me-2">Health Card Photo:</span>
                                            <a href="{{ asset($profile->health_card_photo) }}" target="_blank"
                                                rel="noopener noreferrer">
                                                <img src="{{ asset($profile->health_card_photo) }}" alt="Health Card Photo"
                                                    class="img-thumbnail" style="max-width: 100px; cursor: pointer;" />
                                            </a>
                                        </div>
                                    @endif

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
                                <h3 class="fw-bold text-center mb-4" style="color: rgb(1, 1, 66)">Family Details</h3>

                                <!-- Parent Details Section -->
                                <div class="family-section mb-5">
                                    <h4 class="fw-bold mb-4" style="color:rgb(1, 1, 66)">
                                        <i class="fas fa-users me-2" style="color:rgb(85, 1, 15)"></i> Parents
                                    </h4>
                                    <div class="row g-4 text-center">
                                        <!-- Father -->
                                        <div class="col-md-4">
                                            <div class="card border shadow-sm p-3 h-100">
                                                <div class="card-body">
                                                    <img class="profile-imgs rounded-circle mb-3"
                                                        style="height: 100px; width: 100px; object-fit: cover;"
                                                        src="{{ asset($family->father_photo ?? '') }}" alt="Father">
                                                    <h5 class="fw-semibold text-dark">
                                                        {{ $family->father_name ?? 'Not Available' }}</h5>
                                                    <span class="text-muted">Father</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Mother -->
                                        <div class="col-md-4">
                                            <div class="card border shadow-sm p-3 h-100">
                                                <div class="card-body">
                                                    <img class="profile-imgs rounded-circle mb-3"
                                                        style="height: 100px; width: 100px; object-fit: cover;"
                                                        src="{{ asset($family->mother_photo ?? '') }}" alt="Mother">
                                                    <h5 class="fw-semibold text-dark">
                                                        {{ $family->mother_name ?? 'Not Available' }}</h5>
                                                    <span class="text-muted">Mother</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Spouse Details Section -->
                                @if ($family && $family->maritial_status == 'married')
                                    <div class="family-section mb-5">
                                        <h4 class="fw-bold mb-4" style="color:rgb(1, 1, 66)">
                                            <i class="fas fa-heart me-2" style="color:rgb(85, 1, 15)"></i> Spouse &
                                            In-Laws
                                        </h4>
                                        <div class="row g-4 text-center">
                                            <!-- Spouse -->
                                            <div class="col-md-4">
                                                <div class="card border shadow-sm p-3 h-100">
                                                    <div class="card-body">
                                                        <img class="profile-imgs rounded-circle mb-3"
                                                            style="height: 100px; width: 100px; object-fit: cover;"
                                                            src="{{ asset($family->spouse_photo ?? '') }}"
                                                            alt="Spouse">
                                                        <h5 class="fw-semibold text-dark">
                                                            {{ $family->spouse_name ?? 'Not Available' }}</h5>
                                                        <span class="text-muted">Spouse</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Spouse's Father -->
                                            <div class="col-md-4">
                                                <div class="card border shadow-sm p-3 h-100">
                                                    <div class="card-body">
                                                        <img class="profile-imgs rounded-circle mb-3"
                                                            style="height: 100px; width: 100px; object-fit: cover;"
                                                            src="{{ asset($family->spouse_father_photo ?? '') }}"
                                                            alt="Spouse Father">
                                                        <h5 class="fw-semibold text-dark">
                                                            {{ $family->spouse_father_name ?? 'Not Available' }}</h5>
                                                        <span class="text-muted">Spouse's Father</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Spouse's Mother -->
                                            <div class="col-md-4">
                                                <div class="card border shadow-sm p-3 h-100">
                                                    <div class="card-body">
                                                        <img class="profile-imgs rounded-circle mb-3"
                                                            style="height: 100px; width: 100px; object-fit: cover;"
                                                            src="{{ asset($family->spouse_mother_photo ?? '') }}"
                                                            alt="Spouse Mother">
                                                        <h5 class="fw-semibold text-dark">
                                                            {{ $family->spouse_mother_name ?? 'Not Available' }}</h5>
                                                        <span class="text-muted">Spouse's Mother</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Children Details Section -->
                                <div class="family-section">
                                    <h4 class="fw-bold mb-4" style="color:rgb(1, 1, 66)">
                                        <i class="fas fa-child me-2" style="color:rgb(85, 1, 15)"></i> Children
                                    </h4>
                                    <div class="row g-4">
                                        @forelse ($children as $child)
                                            <div class="col-md-4">
                                                <div class="card border shadow-sm p-3 text-center h-100">
                                                    <div class="card-body">
                                                        <img alt="Child" class="profile-imgs rounded-circle mb-3"
                                                            style="width: 120px; height: 120px; object-fit: cover;"
                                                            src="{{ asset($child->photo ?? '') }}">
                                                        <h5 class="fw-semibold text-dark">{{ $child->children_name }}</h5>
                                                        <span class="text-muted">{{ $child->gender }} | DOB:
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

                        <!-- ID Card Section -->
                        <div class="tab-pane fade" id="idcard">
                            <div class="card profile-section">
                                <div class="card-body">
                                    <h4 class="fw-bold mb-4" style="color:rgb(1, 1, 66)">
                                        <i class="fas fa-id-card"></i> ID Card Details
                                    </h4>

                                    <div class="row g-4">
                                        @foreach ($idcard as $index => $card)
                                            <div class="col-md-4">
                                                <div class="card shadow-sm p-3 border rounded id-card-box h-100">
                                                    <div class="text-center border-bottom pb-2 mb-2">
                                                        <h5 class="text-uppercase fw-bold mb-0">
                                                            {{ $card->id_type ?? 'ID CARD' }}
                                                        </h5>
                                                    </div>
                                                    <div class="text-center">
                                                        <a href="{{ $card->id_photo }}" target="_blank">
                                                            <img src="{{ $card->id_photo }}" alt="ID Photo"
                                                                class="img-fluid rounded mb-3"
                                                                style="height: 160px; object-fit: cover; cursor: pointer;">
                                                        </a>
                                                    </div>
                                                    <div class="text-center">
                                                        <p class="mb-1"><strong>ID Type:</strong>
                                                            {{ $card->id_type ?? 'Not Available' }}</p>
                                                        <p class="mb-0"><strong>ID Number:</strong>
                                                            {{ $card->id_number ?? 'Not Available' }}</p>
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
                            <div class="card profile-section shadow-lg border-0">
                                <div class="card-body">
                                    <h4 class="fw-bold text-center mb-4" style="color:#001a33">
                                        <i class="fas fa-map-marker-alt me-2 text-primary"></i> Address Details
                                    </h4>

                                    <!-- Current Address -->
                                    <h5 class="fw-semibold mb-3 text-dark">
                                        <i class="fas fa-map-pin me-2 text-success"></i>Current Address
                                    </h5>
                                    <div class="row g-3 mb-4">
                                        <x-address-item icon="fa-map-marked-alt" color="primary" label="Current Address"
                                            :value="$profile->address->address ?? 'Not Available'" />
                                        <x-address-item icon="fa-map-signs" color="success" label="Sahi"
                                            :value="$profile->address->sahi ?? 'Not Available'" />
                                        <x-address-item icon="fa-thumbtack" color="danger" label="Landmark"
                                            :value="$profile->address->landmark ?? 'Not Available'" />
                                        <x-address-item icon="fa-envelope" color="info" label="Pincode"
                                            :value="$profile->address->pincode ?? 'Not Available'" />
                                        <x-address-item icon="fa-mail-bulk" color="primary" label="Post"
                                            :value="$profile->address->post ?? 'Not Available'" />
                                        <x-address-item icon="fa-user-shield" color="warning" label="Police Station"
                                            :value="$profile->address->police_station ?? 'Not Available'" />
                                        <x-address-item icon="fa-city" color="secondary" label="District"
                                            :value="$profile->address->district ?? 'Not Available'" />
                                        <x-address-item icon="fa-map" color="success" label="State"
                                            :value="$profile->address->state ?? 'Not Available'" />
                                        <x-address-item icon="fa-flag" color="danger" label="Country"
                                            :value="$profile->address->country ?? 'Not Available'" />
                                    </div>

                                    <hr class="my-4">

                                    <!-- Permanent Address -->
                                    <h5 class="fw-semibold mb-3 text-dark">
                                        <i class="fas fa-home me-2 text-info"></i>Permanent Address
                                    </h5>
                                    <div class="row g-3">
                                        <x-address-item icon="fa-map-marked" color="primary" label="Permanent Address"
                                            :value="$profile->address->per_address ?? 'Not Available'" />
                                        <x-address-item icon="fa-map-signs" color="success" label="Sahi"
                                            :value="$profile->address->per_sahi ?? 'Not Available'" />
                                        <x-address-item icon="fa-thumbtack" color="danger" label="Landmark"
                                            :value="$profile->address->per_landmark ?? 'Not Available'" />
                                        <x-address-item icon="fa-envelope" color="info" label="Pincode"
                                            :value="$profile->address->per_pincode ?? 'Not Available'" />
                                        <x-address-item icon="fa-mail-bulk" color="primary" label="Post"
                                            :value="$profile->address->per_post ?? 'Not Available'" />
                                        <x-address-item icon="fa-user-shield" color="warning" label="Police Station"
                                            :value="$profile->address->per_police_station ?? 'Not Available'" />
                                        <x-address-item icon="fa-city" color="secondary" label="District"
                                            :value="$profile->address->per_district ?? 'Not Available'" />
                                        <x-address-item icon="fa-map" color="success" label="State"
                                            :value="$profile->address->per_state ?? 'Not Available'" />
                                        <x-address-item icon="fa-flag" color="danger" label="Country"
                                            :value="$profile->address->per_country ?? 'Not Available'" />
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
                                                    @php
                                                        $beddhas = $seba->beddhas();
                                                    @endphp

                                                    @if ($beddhas->isNotEmpty())
                                                        @foreach ($beddhas as $beddha)
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
