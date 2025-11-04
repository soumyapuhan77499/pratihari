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
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"/>
                </svg>
            </div>

            <ul class="side-menu">
                <!-- Category -->
                <li class="side-item side-item-category">Main</li>

                <!-- Dashboard -->
                <li class="slide">
                    <a @class([
                           'side-menu__item',
                           'is-active' => request()->routeIs('admin.dashboard')
                        ])
                       href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 icon icon-1"></i>
                        <span class="side-menu__label">Dashboard</span>
                        @if (request()->routeIs('admin.dashboard'))
                            <span class="active-dot"></span>
                        @endif
                    </a>
                </li>

                <!-- Category -->
                <li class="side-item side-item-category">Pratihari Nijoga</li>

                <!-- Add Pratihari Profile -->
                <li class="slide">
                    <a @class([
                           'side-menu__item',
                           'is-active' => request()->is('admin/pratihari-profile')
                        ])
                       href="{{ url('admin/pratihari-profile') }}">
                        <i class="bi bi-person-plus icon icon-2"></i>
                        <span class="side-menu__label">Add Pratihari Profile</span>
                    </a>
                </li>

                <!-- Manage Pratihari Profile -->
                <li class="slide">
                    <a @class([
                           'side-menu__item',
                           'is-active' => request()->is('admin/pratihari-manage-profile')
                        ])
                       href="{{ url('admin/pratihari-manage-profile') }}">
                        <i class="bi bi-people icon icon-3"></i>
                        <span class="side-menu__label">Manage Pratihari Profile</span>
                    </a>
                </li>

                @if (Auth::guard('super_admin')->check() && Auth::guard('super_admin')->user()->role === 'super_admin')
                    <!-- Pratihari Beddha Assign -->
                    <li class="slide">
                        <a @class([
                               'side-menu__item',
                               'is-active' => request()->is('admin/pratihari-seba-beddha')
                            ])
                           href="{{ url('admin/pratihari-seba-beddha') }}">
                            <i class="bi bi-diagram-3 icon icon-4"></i>
                            <span class="side-menu__label">Pratihari Beddha Assign</span>
                        </a>
                    </li>
                @endif

                <!-- Pratihari Seba Assign -->
                <li class="slide">
                    <a @class([
                           'side-menu__item',
                           'is-active' => request()->is('admin/assign-pratihari-seba')
                        ])
                       href="{{ url('admin/assign-pratihari-seba') }}">
                        <i class="bi bi-journal-check icon icon-5"></i>
                        <span class="side-menu__label">Pratihari Seba Assign</span>
                    </a>
                </li>

                <!-- Manage Notice -->
                <li class="slide">
                    <a @class([
                           'side-menu__item',
                           'is-active' => request()->is('admin/manage-notice')
                        ])
                       href="{{ url('admin/manage-notice') }}">
                        <i class="bi bi-bell icon icon-6"></i>
                        <span class="side-menu__label">Manage Notice</span>
                    </a>
                </li>

                <!-- Manage Committee -->
                <li class="slide">
                    <a @class([
                           'side-menu__item',
                           'is-active' => request()->is('admin/manage-designation')
                        ])
                       href="{{ url('admin/manage-designation') }}">
                        <i class="bi bi-building-gear icon icon-7"></i>
                        <span class="side-menu__label">Manage Committee</span>
                    </a>
                </li>

                <!-- Manage Application -->
                <li class="slide">
                    <a @class([
                           'side-menu__item',
                           'is-active' => request()->is('admin/manage-application')
                        ])
                       href="{{ url('admin/manage-application') }}">
                        <i class="bi bi-ui-checks-grid icon icon-8"></i>
                        <span class="side-menu__label">Manage Application</span>
                    </a>
                </li>

                <!-- Seba Calendar -->
                <li class="slide">
                    <a @class([
                           'side-menu__item',
                           'is-active' => request()->is('admin/seba-calendar')
                        ])
                       href="{{ url('admin/seba-calendar') }}">
                        <i class="bi bi-calendar3 icon icon-9"></i>
                        <span class="side-menu__label">Seba Calendar</span>
                    </a>
                </li>

                @if (Auth::guard('super_admin')->check() && Auth::guard('super_admin')->user()->role === 'super_admin')
                    <!-- Manage Admin -->
                    <li class="slide">
                        <a @class([
                               'side-menu__item',
                               'is-active' => request()->routeIs('superadmin.manageAdmin')
                            ])
                           href="{{ route('superadmin.manageAdmin') }}">
                            <i class="bi bi-shield-lock icon icon-10"></i>
                            <span class="side-menu__label">Manage Admin</span>
                        </a>
                    </li>
                @endif
            </ul>

            <div class="slide-right" id="slide-right" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/>
                </svg>
            </div>
        </div>
    </aside>
</div>
<!-- main-sidebar -->

{{-- Wrap CSS so Blade doesn't parse @media / ::after --}}
@verbatim
<style>
    /* Brand */
    .brand-chip{
        width:42px;height:42px;border-radius:12px;
        background:linear-gradient(135deg,#6a11cb,#2575fc);
        color:#fff;font-size:20px;box-shadow:0 6px 16px rgba(37,117,252,.25);
    }
    .brand-title{font-weight:800;letter-spacing:.5px;color:var(--bs-body-color);line-height:1;}
    .brand-sub{font-size:12px;color:var(--bs-secondary-color);margin-top:2px;}

    /* Menu base */
    .app-sidebar{background:var(--surface,#fff);border-right:1px solid rgba(0,0,0,.06);}
    .main-sidemenu{padding:.5rem .75rem 1rem;}

    .side-item-category{
        font-size:.72rem;text-transform:uppercase;letter-spacing:.12rem;
        color:var(--bs-secondary-color);margin:1rem .5rem .5rem;font-weight:700;
    }

    .side-menu{list-style:none;padding:0;margin:0;}
    .slide{margin:.125rem 0;}

    /* Item */
    .side-menu__item{
        display:flex;align-items:center;gap:.75rem;
        padding:.625rem .75rem;border-radius:.8rem;text-decoration:none;
        color:var(--bs-body-color);position:relative;
        transition:transform .12s ease,background .2s ease,box-shadow .2s ease;outline:none;
    }
    .side-menu__item:hover{
        background:rgba(99,102,241,.06);transform:translateX(2px);
        box-shadow:0 4px 12px rgba(16,24,40,.06);
    }
    .side-menu__item.is-active{
        background:linear-gradient(180deg,rgba(99,102,241,.12),rgba(99,102,241,.08));
        box-shadow:inset 0 0 0 1px rgba(99,102,241,.25),0 6px 16px rgba(16,24,40,.08);
    }
    .side-menu__item:focus-visible{box-shadow:0 0 0 3px rgba(99,102,241,.25);}
    .side-menu__label{font-weight:600;letter-spacing:.2px;}

    /* ROUND icon pills with soft ring */
    .icon{
        width:40px;height:40px;border-radius:50%;
        display:inline-flex;align-items:center;justify-content:center;
        font-size:18px;background:var(--icon-bg,#eef2ff);color:var(--icon-fg,#4f46e5);
        position:relative;box-shadow:inset 0 0 0 1px rgba(0,0,0,.04);
        transition:transform .15s ease,box-shadow .2s ease,background .2s ease;
    }
    .side-menu__item:hover .icon{transform:scale(1.04);}
    .icon::after{
        content:"";position:absolute;inset:-2px;border-radius:50%;
        box-shadow:0 4px 14px rgba(0,0,0,.06),0 0 0 6px var(--icon-ring,rgba(0,0,0,0));
        pointer-events:none;
    }
    .side-menu__item.is-active .icon{box-shadow:inset 0 0 0 2px rgba(255,255,255,.6);}
    .side-menu__item.is-active .icon::after{--icon-ring:rgba(99,102,241,.12);}

    /* Color presets */
    .icon-1{--icon-bg:#eef2ff;--icon-fg:#4338ca;--icon-ring:rgba(67,56,202,.12);}
    .icon-2{--icon-bg:#fff7ed;--icon-fg:#c2410c;--icon-ring:rgba(194,65,12,.12);}
    .icon-3{--icon-bg:#ecfdf5;--icon-fg:#047857;--icon-ring:rgba(4,120,87,.12);}
    .icon-4{--icon-bg:#e0f2fe;--icon-fg:#0369a1;--icon-ring:rgba(3,105,161,.12);}
    .icon-5{--icon-bg:#faf5ff;--icon-fg:#7e22ce;--icon-ring:rgba(126,34,206,.12);}
    .icon-6{--icon-bg:#fef2f2;--icon-fg:#b91c1c;--icon-ring:rgba(185,28,28,.12);}
    .icon-7{--icon-bg:#ecfeff;--icon-fg:#0e7490;--icon-ring:rgba(14,116,144,.12);}
    .icon-8{--icon-bg:#f0fdf4;--icon-fg:#16a34a;--icon-ring:rgba(22,163,74,.12);}
    .icon-9{--icon-bg:#eff6ff;--icon-fg:#1d4ed8;--icon-ring:rgba(29,78,216,.12);}
    .icon-10{--icon-bg:#fdf4ff;--icon-fg:#a21caf;--icon-ring:rgba(162,28,175,.12);}

    /* Active indicator */
    .active-dot{
        position:absolute;right:.6rem;width:8px;height:8px;border-radius:50%;
        background:#22c55e;box-shadow:0 0 0 6px rgba(34,197,94,.15);
    }

    /* Reduced-motion */
    @media (prefers-reduced-motion: no-preference){
        .side-menu__item{transition:transform .15s cubic-bezier(.2,.8,.2,1),background .2s ease,box-shadow .2s ease;}
    }

    /* Dark mode */
    body.theme-dark .app-sidebar{background:#0f172a;border-right-color:rgba(255,255,255,.06);}
    body.theme-dark .side-item-category{color:rgba(255,255,255,.55);}
    body.theme-dark .side-menu__item{color:rgba(255,255,255,.85);}
    body.theme-dark .side-menu__item:hover{background:rgba(99,102,241,.18);}
    body.theme-dark .side-menu__item.is-active{
        background:linear-gradient(180deg,rgba(99,102,241,.22),rgba(99,102,241,.14));
        box-shadow:inset 0 0 0 1px rgba(99,102,241,.35);
    }
</style>
@endverbatim
