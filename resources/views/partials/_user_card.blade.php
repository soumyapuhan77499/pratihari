@if ($user)
<div class="seba-user-card position-relative">
    <span class="online-indicator" title="Active"></span>

    <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}" class="text-decoration-none">
        <img
            src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/brand/monk.png') }}"
            alt="Profile photo"
            class="user-avatar">
    </a>

    <a href="{{ route('admin.viewProfile', $user->pratihari_id) }}" class="name d-block text-decoration-none">
        {{ $user->first_name }} {{ $user->last_name }}
    </a>

    <div class="meta">{{ $user->phone_no ?? '—' }}</div>

    <div class="seba-actions">
        @if (!empty($user->phone_no))
            <a class="btn btn-sm btn-outline-primary" href="tel:{{ $user->phone_no }}" title="Call">
                <i class="bi bi-telephone"></i>
            </a>
            <a class="btn btn-sm btn-outline-success" href="https://wa.me/{{ preg_replace('/\D/','',$user->phone_no) }}" target="_blank" rel="noopener" title="WhatsApp">
                <i class="bi bi-whatsapp"></i>
            </a>
        @endif
        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.viewProfile', $user->pratihari_id) }}" title="Open profile">
            <i class="bi bi-box-arrow-up-right"></i>
        </a>
    </div>
</div>
@endif
