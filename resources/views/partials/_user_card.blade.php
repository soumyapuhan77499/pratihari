@if ($user)
    <div class="seba-user-card position-relative text-center">
        <span class="online-indicator" title="Active"></span>
        <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}" class="text-decoration-none text-dark">
            <img src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}" style="width: 60px" class="user-avatar">
        </a>
        <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}" class="text-decoration-none text-dark">
            <div class="fw-semibold">{{ $user->first_name }} {{ $user->last_name }}</div>
        </a>
        <div class="text-muted small">{{ $user->phone_no ?? '' }}</div>
    </div>
@endif
