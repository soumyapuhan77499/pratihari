@extends('layouts.app')
@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    /* profile css */
    .custom-card {
            background: linear-gradient(135deg, #ffcc70, #ff8e53);
            border: none;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            padding: 20px;
        }
        /* Profile Image */
        .profile-image {
            position: relative;
            display: inline-block;
        }

        .profile-image img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.8);
        }

        .profile-online {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 15px;
            height: 15px;
            background: #28a745;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        /* Profile Info */
        .prof-details h4 {
            font-size: 22px;
            font-weight: bold;
            color: #3314de;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
        }

        .prof-details p {
            font-size: 14px;
            font-weight: 500;
            color: #090909;
        }

        /* Tab Menu */
        .profile-nav-line {
            display: flex;
            justify-content: space-around;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 10px;
        }

        .profile-nav-line .nav-link {
            color: #ffeb3b;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 25px;
            transition: all 0.3s ease-in-out;
        }

        .profile-nav-line .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        .profile-nav-line .active {
            background: #ffeb3b;
            color: #3e1c78;
        }
        /* personal css   */
        .personal-details-card {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background: #f8f9fa;
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .personal-details-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .personal-details-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        .personal-details-item i {
            font-size: 20px;
            color: #007bff;
            margin-right: 15px;
        }

        .personal-details-item:last-child {
            border-bottom: none;
        }

        .personal-details-text {
            font-size: 13px;
            font-weight: 500;
            color: #0d0d0d;
        }

        .personal-details-value {
            font-size: 15px;
            color: #666;
        }

        /* family css */

        .profile-imgs {
            border-radius: 50%;
            transition: transform 0.3s ease;
            display: block;
            margin: 0 auto;
            object-fit: cover;
        }

        .profile-imgs:hover {
            transform: scale(1.5);
        }

        .custom-card {
            border-radius: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .custom-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .family-section {
            padding: 20px;
            border-radius: 10px;
            background-color: #f8f9fa;
            margin-bottom: 20px;
        }

        .family-section h4 {
            text-align: center;
            margin-bottom: 15px;
        }

        /* idcard css */
        .id-card {
            border: 2px solid #ddd;
            border-radius: 10px;
            background: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
            transition: 0.3s ease-in-out;
        }

        .id-card:hover {
            box-shadow: 4px 4px 12px rgba(0, 0, 0, 0.2);
        }

        .id-card-header {
            background: #007bff;
            color: white;
            text-align: center;
            padding: 10px;
            font-weight: bold;
            border-radius: 5px 5px 0 0;
        }

        .id-photo img {
            width: 100px;
            height: 100px;
            border-radius: 5px;
            border: 2px solid #ddd;
            object-fit: cover;
        }

        .id-details p {
            font-size: 14px;
            margin: 5px 0;
            color: #333;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .col-md-4 {
            display: flex;
            justify-content: center;
        }

        .profile-section {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background: #f8f9fa;
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .profile-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .profile-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        .profile-item i {
            font-size: 20px;
            color: #007bff;
            margin-right: 15px;
        }

        .profile-item:last-child {
            border-bottom: none;
        }

        .profile-text {
            font-size: 16px;
            font-weight: 500;
            color: #333;
        }

        .profile-value {
            font-size: 15px;
            color: #666;
        }
    </style>
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
                                <img class="br-5" src="{{ asset('storage/' . $profile->profile_photo) }}" alt="Profile Photo">
                                <span class="profile-online"></span>
                            </span>
                        </div>
                        <div class="my-md-auto mt-4 prof-details">
                            <h4>{{ $profile->first_name }} {{ $profile->last_name }}</h4>
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
                                                        src="{{ $family->father_photo ? asset('storage/' . $family->father_photo) : asset('assets/img/default-profile.png') }}">
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
                                                        src="{{ $family->mother_photo ? asset('storage/' . $family->mother_photo) : asset('assets/img/default-profile.png') }}">
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
                                                        src="{{ $family->spouse_photo ? asset('storage/' . $family->spouse_photo) : asset('assets/img/default-profile.png') }}">
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
                                                            src="{{ $child->photo ? asset('storage/' . $child->photo) : asset('assets/img/default-profile.png') }}">
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
                                <h4 class="fw-bold" style="color:rgb(1, 1, 66)"><i class="fas fa-id-card"></i> ID Card
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
                                                        <img src="{{ asset('storage/' . $card->id_photo ?? 'default.png') }}"
                                                            alt="ID Photo">
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
                                            class="profile-value">{{ $profile->address->sahi ?? 'Not Available' }}</span>
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
                                <h4 class="fw-bold text-primary"><i class="fas fa-briefcase"></i> Occupation Details</h4>

                                @foreach ($occupation as $job)
                                    <div class="profile-item">
                                        <i class="fas fa-user-tie"></i>
                                        <div>
                                            <span class="profile-text">Occupation Type:</span>
                                            <span
                                                class="profile-value">{{ $job->occupation_type ?? 'Not Available' }}</span>
                                        </div>
                                    </div>

                                    <div class="profile-item">
                                        <i class="fas fa-certificate"></i>
                                        <div>
                                            <span class="profile-text">Extra Activities:</span>
                                            <div class="profile-value">
                                                @if (!empty($job->extra_activity))
                                                    @foreach (explode(',', $job->extra_activity) as $activity)
                                                        <span class="badge bg-success me-1">{{ trim($activity) }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">Not Available</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <hr> <!-- Separator for better UI -->
                                @endforeach

                            </div>
                        </div>
                    </div>

                    <!-- Seba Details -->
                    <div class="tab-pane fade" id="seba">
                        <div class="card profile-section">
                            <div class="card-body">
                                <h4 class="fw-bold text-primary"><i class="fas fa-hands-helping"></i> Seba Details</h4>
                    
                                @foreach ($sebaDetails as $seba)
                                    <!-- Nijoga Section -->
                                    <div class="profile-item d-flex align-items-center">
                                        <i class="fas fa-user-shield text-primary me-2"></i>
                                        <div>
                                            <span class="profile-text fw-bold">Nijoga:</span>
                                            <span class="profile-value">{{ $seba->nijogaMaster->nijoga_name ?? 'Not Available' }}</span>
                                        </div>
                                    </div>
                    
                                    <!-- Seba Name Section -->
                                    <div class="profile-item d-flex align-items-center">
                                        <i class="fas fa-praying-hands text-success me-2"></i>
                                        <div>
                                            <span class="profile-text fw-bold">Seba Name:</span>
                                            <span class="profile-value">{{ $seba->sebaMaster->seba_name ?? 'Not Available' }}</span>
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
                                                        <span class="badge bg-success me-1">{{ $beddha->beddha_name }}</span>
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
                                            ['icon' => 'fab fa-facebook-f', 'color' => 'bg-primary', 'text' => 'Facebook', 'url' => $socialMedia->facebook_url ?? '#'],
                                            ['icon' => 'fab fa-instagram', 'color' => 'bg-danger', 'text' => 'Instagram', 'url' => $socialMedia->instagram_url ?? '#'],
                                            ['icon' => 'fab fa-twitter', 'color' => 'bg-info', 'text' => 'Twitter', 'url' => $socialMedia->twitter_url ?? '#'],
                                            ['icon' => 'fab fa-linkedin-in', 'color' => 'bg-success', 'text' => 'LinkedIn', 'url' => $socialMedia->linkedin_url ?? '#'],
                                            ['icon' => 'fab fa-youtube', 'color' => 'bg-danger', 'text' => 'YouTube', 'url' => $socialMedia->youtube_url ?? '#'],
                                        ];
                                    @endphp
                    
                                    @foreach ($socialLinks as $social)
                                        <div class="mg-md-r-20 mg-b-10">
                                            <div class="main-profile-social-list">
                                                <div class="media d-flex align-items-center">
                                                    <div class="media-icon {{ $social['color'] }} text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="{{ $social['icon'] }} fa-lg"></i>
                                                    </div>
                                                    <div class="media-body ms-3">
                                                        <span class="fw-bold">{{ $social['text'] }}</span> 
                                                        <a href="{{ $social['url'] }}" target="_blank" class="d-block text-muted">{{ parse_url($social['url'], PHP_URL_HOST) ?: 'Not Available' }}</a>
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

@endsection

