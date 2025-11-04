<!-- main-sidebar -->
<div class="sticky">
    <aside class="app-sidebar">
        <!-- Header / Brand -->
        <div class="main-sidebar-header active">
            <a class="header-logo active d-flex align-items-center gap-2 text-decoration-none" href="{{ url('/dashboard') }}">
                <div class="brand-chip d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-stars"></i>
                </div>
                <div class="d-flex flex-column">
                    <span class="brand-title">PRATIHARI</span>
                    <span class="brand-sub">Nijoga Suite</span>
                </div>
            </a>
        </div>

        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg>
            </div>

            <ul class="side-menu">
                <!-- Category -->
                <li class="side-item side-item-category">Main</li>

                <!-- Dashboard -->
                <li class="slide">
                    <a class="side-menu__item {{ Route::is('admin.dashboard') ? 'is-active' : '' }}"
                       href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 icon gradient-1"></i>
                        <span class="side-menu__label">Dashboard</span>
                        @if (Route::is('admin.dashboard'))
                            <span class="active-dot"></span>
                        @endif
                    </a>
                </li>

                <!-- Category -->
                <li class="side-item side-item-category">Pratihari Nijoga</li>

                <!-- Add Pratihari Profile -->
                <li class="slide">
                    <a class="side-menu__item {{ request()->is('admin/pratihari-profile') ? 'is-active' : '' }}"
                       href="{{ url('admin/pratihari-profile') }}">
                        <i class="bi bi-person-plus icon gradient-2"></i>
                        <span class="side-menu__label">Add Profile</span>
                    </a>
                </li>

                <!-- Manage Pratihari Profile -->
                <li class="slide">
                    <a class="side-menu__item {{ request()->is('admin/pratihari-manage-profile') ? 'is-active' : '' }}"
                       href="{{ url('admin/pratihari-manage-profile') }}">
                        <i class="bi bi-people icon gradient-3"></i>
                        <span class="side-menu__label">Manage Profile</span>
                    </a>
                </li>

                @if (Auth::guard('super_admin')->check() && Auth::guard('super_admin')->user()->role === 'super_admin')
                    <!-- Pratihari Beddha Assign -->
                    <li class="slide">
                        <a class="side-menu__item {{ request()->is('admin/pratihari-seba-beddha') ? 'is-active' : '' }}"
                           href="{{ url('admin/pratihari-seba-beddha') }}">
                            <i class="bi bi-diagram-3 icon gradient-4"></i>
                            <span class="side-menu__label">Beddha Assign</span>
                        </a>
                    </li>
                @endif

                <!-- Pratihari Seba Assign -->
                <li class="slide">
                    <a class="side-menu__item {{ request()->is('admin/assign-pratihari-seba') ? 'is-active' : '' }}"
                       href="{{ url('admin/assign-pratihari-seba') }}">
                        <i class="bi bi-journal-check icon gradient-5"></i>
                        <span class="side-menu__label">Seba Assign</span>
                    </a>
                </li>

                <!-- Manage Notice -->
                <li class="slide">
                    <a class="side-menu__item {{ request()->is('admin/manage-notice') ? 'is-active' : '' }}"
                       href="{{ url('admin/manage-notice') }}">
                        <i class="bi bi-bell icon gradient-6"></i>
                        <span class="side-menu__label">Mng Notice</span>
                    </a>
                </li>

                <!-- Manage Committee -->
                <li class="slide">
                    <a class="side-menu__item {{ request()->is('admin/manage-designation') ? 'is-active' : '' }}"
                       href="{{ url('admin/manage-designation') }}">
                        <i class="bi bi-building-gear icon gradient-7"></i>
                        <span class="side-menu__label">Mng Committee</span>
                    </a>
                </li>

                <!-- Manage Application -->
                <li class="slide">
                    <a class="side-menu__item {{ request()->is('admin/manage-application') ? 'is-active' : '' }}"
                       href="{{ url('admin/manage-application') }}">
                        <i class="bi bi-ui-checks-grid icon gradient-8"></i>
                        <span class="side-menu__label">Mng Application</span>
                    </a>
                </li>

                <!-- Seba Calendar -->
                <li class="slide">
                    <a class="side-menu__item {{ request()->is('admin/seba-calendar') ? 'is-active' : '' }}"
                       href="{{ url('admin/seba-calendar') }}">
                        <i class="bi bi-calendar3 icon gradient-9"></i>
                        <span class="side-menu__label">Seba Calendar</span>
                    </a>
                </li>

                @if (Auth::guard('super_admin')->check() && Auth::guard('super_admin')->user()->role === 'super_admin')
                    <!-- Manage Admin -->
                    <li class="slide">
                        <a class="side-menu__item {{ Route::is('superadmin.manageAdmin') ? 'is-active' : '' }}"
                           href="{{ route('superadmin.manageAdmin') }}">
                            <i class="bi bi-shield-lock icon gradient-10"></i>
                            <span class="side-menu__label">Mng Admin</span>
                        </a>
                    </li>
                @endif
            </ul>

            <div class="slide-right" id="slide-right" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg>
            </div>
        </div>
    </aside>
</div>
<!-- main-sidebar -->

<!-- Sidebar styles -->
<style>
    /* ================= Brand ================= */
    .brand-chip{
        width:42px;height:42px;border-radius:12px;
        background:linear-gradient(135deg,#6a11cb,#2575fc);
        color:#fff;font-size:20px;box-shadow:0 6px 16px rgba(37,117,252,.25)
    }
    .brand-title{font-weight:800;letter-spacing:.5px;color:var(--bs-body-color);line-height:1}
    .brand-sub{font-size:12px;color:var(--bs-secondary-color);margin-top:2px}

    /* ================ Container ================ */
    .app-sidebar{background:var(--surface,#fff);border-right:1px solid rgba(0,0,0,.06)}
    .main-sidemenu{padding:.5rem .75rem 1rem}
    .side-item-category{
        font-size:.72rem;text-transform:uppercase;letter-spacing:.12rem;
        color:var(--bs-secondary-color);margin:1rem .5rem .5rem;font-weight:700
    }
    .side-menu{list-style:none;padding:0;margin:0}
    .slide{margin:.125rem 0}

    /* ================ Items ================ */
    .side-menu__item{
        display:flex;align-items:center;gap:.75rem;
        padding:.625rem .75rem;border-radius:.8rem;text-decoration:none;
        color:var(--bs-body-color);position:relative;
        transition:transform .12s ease,background .2s ease,box-shadow .2s ease
    }
    .side-menu__item:hover{
        background:rgba(99,102,241,.06);transform:translateX(2px);
        box-shadow:0 4px 12px rgba(16,24,40,.06)
    }
    .side-menu__item.is-active{
        background:linear-gradient(180deg,rgba(99,102,241,.12),rgba(99,102,241,.08));
        box-shadow:inset 0 0 0 1px rgba(99,102,241,.25),0 6px 16px rgba(16,24,40,.08)
    }
    .side-menu__label{font-weight:600;letter-spacing:.2px}

    /* ============== Icon pills (perfect circles) ============== */
    :root{ --icon-size:40px; --icon-font:20px; } /* tweak sizes here */

    .icon{
        width:var(--icon-size);height:var(--icon-size);
        flex:0 0 var(--icon-size);               /* prevent stretch */
        border-radius:50%;                        /* true circle */
        display:grid;place-items:center;          /* perfect center */
        font-size:var(--icon-font);line-height:1; /* crisp baseline */
        background:#f1f3f5;                       /* fallback */
        box-shadow:inset 0 0 0 1px rgba(0,0,0,.05);
        overflow:hidden;                          /* clip edges cleanly */
    }

    /* Gradient themes (fill circle neatly) */
    .gradient-1  {background:linear-gradient(135deg,#6366F1,#22D3EE); color:#fff; background-size:100% 100%;}
    .gradient-2  {background:linear-gradient(135deg,#F59E0B,#F97316); color:#fff; background-size:100% 100%;}
    .gradient-3  {background:linear-gradient(135deg,#22C55E,#10B981); color:#fff; background-size:100% 100%;}
    .gradient-4  {background:linear-gradient(135deg,#0EA5E9,#6366F1); color:#fff; background-size:100% 100%;}
    .gradient-5  {background:linear-gradient(135deg,#8B5CF6,#EC4899); color:#fff; background-size:100% 100%;}
    .gradient-6  {background:linear-gradient(135deg,#FB7185,#F43F5E); color:#fff; background-size:100% 100%;}
    .gradient-7  {background:linear-gradient(135deg,#06B6D4,#0EA5E9); color:#fff; background-size:100% 100%;}
    .gradient-8  {background:linear-gradient(135deg,#4ADE80,#22C55E); color:#fff; background-size:100% 100%;}
    .gradient-9  {background:linear-gradient(135deg,#60A5FA,#34D399); color:#fff; background-size:100% 100%;}
    .gradient-10 {background:linear-gradient(135deg,#F472B6,#F59E0B); color:#fff; background-size:100% 100%;}

    /* Optional: larger on very wide screens */
    @media (min-width:1400px){
        :root{ --icon-size:44px; --icon-font:22px; }
    }

    /* ============== Active indicator ============== */
    .active-dot{
        position:absolute;right:.6rem;width:8px;height:8px;border-radius:50%;
        background:#22c55e;box-shadow:0 0 0 6px rgba(34,197,94,.15)
    }

    /* Motion nicety */
    @media (prefers-reduced-motion:no-preference){
        .side-menu__item{transition:transform .15s cubic-bezier(.2,.8,.2,1),background .2s ease,box-shadow .2s ease}
    }

    /* ============== Dark mode (if body.theme-dark) ============== */
    body.theme-dark .app-sidebar{background:#0f172a;border-right-color:rgba(255,255,255,.06)}
    body.theme-dark .side-item-category{color:rgba(255,255,255,.55)}
    body.theme-dark .side-menu__item{color:rgba(255,255,255,.85)}
    body.theme-dark .side-menu__item:hover{background:rgba(99,102,241,.18)}
    body.theme-dark .side-menu__item.is-active{
        background:linear-gradient(180deg,rgba(99,102,241,.22),rgba(99,102,241,.14));
        box-shadow:inset 0 0 0 1px rgba(99,102,241,.35)
    }
</style>
