    <!-- main-sidebar -->
    <div class="sticky">
        <aside class="app-sidebar">
            <div class="main-sidebar-header active">
                <a class="header-logo active" href="{{ url('index') }}">
                    <img src="{{ asset('assets/img/brand/nijoga-logo.jpg') }}" class=""
                        style="height: 70px;width: 150px;margin-left: 25px;margin-top: -15px;" alt="logo">
                    <img src="{{ asset('assets/img/brand/logo-white.png') }}" class="main-logo  desktop-dark"
                        alt="logo">
                    <img src="{{ asset('assets/img/brand/favicon.png') }}" class="main-logo  mobile-logo"
                        alt="logo">
                    <img src="{{ asset('assets/img/brand/favicon-white.png') }}" class="main-logo  mobile-dark"
                        alt="logo">
                </a>
            </div>

            <div class="main-sidemenu">
                <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                        width="24" height="24" viewBox="0 0 24 24">
                        <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                    </svg>
				</div>
                <ul class="side-menu">
                    <li class="side-item side-item-category">Main</li>
                    <li class="slide">
                        <a class="side-menu__item" data-bs-toggle="slide" href="{{ route('admin.dashboard') }}"><span   class="side-menu__label"><img src="{{ asset('assets/img/brand/dashboard.png') }}"   style="height: 20px;width: 20px" alt="logo"><span class="side-menu__label"  style="margin-left: 10px">Dashboards</span></a>
                    </li>
                    <li class="side-item side-item-category">PRATIHARI NIJOGA</li>
                    <li class="slide">
                        <a class="side-menu__item" href="{{ url('admin/pratihari-profile') }}"><span
                                class="side-menu__label"><img src="{{ asset('assets/img/brand/monk.png') }}"
                                    style="height: 20px;width: 20px" alt="logo"><span class="side-menu__label"
                                    style="margin-left: 10px">Pratihari Profile</span></a>
                    </li>

                    <li class="slide">
                        <a class="side-menu__item" href="{{ url('admin/pratihari-manage-profile') }}"><span
                                class="side-menu__label"><img src="{{ asset('assets/img/brand/resume.png') }}"
                                    style="height: 20px;width: 20px" alt="logo"><span class="side-menu__label"
                                    style="margin-left: 10px">Pratihari Manage Profile</span></a>
                    </li>

                    <li class="slide">
                        <a class="side-menu__item" href="{{ url('admin/pratihari-nijoga-seba') }}"><span
                                class="side-menu__label"><img src="{{ asset('assets/img/brand/pratihari.png') }}"
                                    style="height: 20px;width: 20px" alt="logo"><span class="side-menu__label"
                                    style="margin-left: 10px">Pratihari Nijoga Assign</span></a>
                    </li>

                    <li class="slide">
                        <a class="side-menu__item" href="{{ url('admin/pratihari-seba-beddha') }}"><span
                                class="side-menu__label"><img src="{{ asset('assets/img/brand/manage.png') }}"
                                    style="height: 20px;width: 20px" alt="logo"><span class="side-menu__label"
                                    style="margin-left: 10px">Pratihari Beddha Assign</span></a>
                    </li>

                    <li class="slide">
                        <a class="side-menu__item" href="{{ url('admin/manage-notice') }}"><span
                                class="side-menu__label"><img src="{{ asset('assets/img/brand/notification.png') }}"
                                    style="height: 20px;width: 20px" alt="logo"><span class="side-menu__label"
                                    style="margin-left: 10px">Manage Notice</span></a>
                    </li>

                    <li class="slide">
                        <a class="side-menu__item" href="{{ url('admin/manage-designation') }}"><span
                                class="side-menu__label"><img src="{{ asset('assets/img/brand/sketch.png') }}"
                                    style="height: 20px;width: 20px" alt="logo"><span class="side-menu__label"
                                    style="margin-left: 10px">Manage Designation</span></a>
                    </li>

                    <li class="slide">
                        <a class="side-menu__item" href="{{ url('admin/manage-application') }}"><span
                                class="side-menu__label"><img src="{{ asset('assets/img/brand/gallery.png') }}"
                                    style="height: 20px;width: 20px" alt="logo"><span class="side-menu__label"
                                    style="margin-left: 10px">Manage Application</span></a>
                    </li>

                    @if (Auth::guard('super_admin')->check() && Auth::guard('super_admin')->user()->role === 'super_admin')
                        <li class="slide">
                            <a class="side-menu__item" href="{{ route('superadmin.manageAdmin') }}">
                                <span class="side-menu__label">
                                    <img src="{{ asset('assets/img/brand/monk.png') }}"
                                        style="height: 20px;width: 20px" alt="logo">
                                    <span class="side-menu__label" style="margin-left: 10px">Manage Admin</span>
                                </span>
                            </a>
                        </li>
                    @endif

                </ul>
                <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                        width="24" height="24" viewBox="0 0 24 24">
                        <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                    </svg></div>
            </div>
        </aside>
    </div>
    <!-- main-sidebar -->
