<!-- ===== KPI GRID ===== -->
<div class="col-12 mt-1">
    <div class="row g-3">

        <!-- Today’s Registrations -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi violet h-100">
                <div class="meta">
                    <i class="bi bi-person-plus"></i>
                    Today’s Registrations
                    <a href="#"
                       class="ms-auto subtle text-decoration-none kpi-viewall"
                       data-title="Today’s Registrations"
                       data-type="profiles"
                       data-users='@json($arrTodayProfiles)'>
                        View all
                    </a>
                </div>
                <div class="value mt-2">{{ count($todayProfiles) }}</div>
                <div class="subtle">New profiles created</div>
                <div class="kpi-progress"><span style="--p: 38%;"></span></div>
            </div>
        </div>

        <!-- Pending Profiles -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi amber h-100">
                <div class="meta">
                    <i class="bi bi-hourglass-split"></i>
                    Pending Profiles
                    <a href="#"
                       class="ms-auto subtle text-decoration-none kpi-viewall"
                       data-title="Pending Profiles"
                       data-type="pending"
                       data-users='@json($arrPendingProfiles)'>
                        View all
                    </a>
                </div>
                <div class="value mt-2">{{ count($pendingProfile) }}</div>
                <div class="subtle">Awaiting review</div>
                <div class="kpi-progress"><span style="--p: 62%;"></span></div>
            </div>
        </div>

        <!-- Active Profiles -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi emerald h-100">
                <div class="meta">
                    <i class="bi bi-person-check"></i>
                    Active Profiles
                    <a href="#"
                       class="ms-auto subtle text-decoration-none kpi-viewall"
                       data-title="Active Profiles"
                       data-type="approved"
                       data-users='@json($arrActiveProfiles)'>
                        View all
                    </a>
                </div>
                <div class="value mt-2">{{ count($totalActiveUsers) }}</div>
                <div class="subtle">Approved & active</div>
                <div class="kpi-progress"><span style="--p: 74%;"></span></div>
            </div>
        </div>

        <!-- Incomplete Profiles -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi cyan h-100">
                <div class="meta">
                    <i class="bi bi-clipboard2-data"></i>
                    Incomplete Profiles
                    <a href="#"
                       class="ms-auto subtle text-decoration-none kpi-viewall"
                       data-title="Incomplete Profiles"
                       data-type="incomplete"
                       data-users='@json($arrIncompleteProfiles)'>
                        View all
                    </a>
                </div>
                <div class="value mt-2">{{ count($incompleteProfiles) }}</div>
                <div class="subtle">Need more info</div>
                <div class="kpi-progress"><span style="--p: 45%;"></span></div>
            </div>
        </div>

        <!-- Today Approved -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi blue h-100">
                <div class="meta">
                    <i class="bi bi-check2-circle"></i>
                    Today Approved
                    <a href="#"
                       class="ms-auto subtle text-decoration-none kpi-viewall"
                       data-title="Today Approved"
                       data-type="todayapproved"
                       data-users='@json($arrTodayApproved)'>
                        View all
                    </a>
                </div>
                <div class="value mt-2">{{ count($todayApprovedProfiles) }}</div>
                <div class="subtle">Approved today</div>
                <div class="kpi-progress"><span style="--p: 52%;"></span></div>
            </div>
        </div>

        <!-- Today Rejected -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi rose h-100">
                <div class="meta">
                    <i class="bi bi-x-circle"></i>
                    Today Rejected
                    <a href="#"
                       class="ms-auto subtle text-decoration-none kpi-viewall"
                       data-title="Today Rejected"
                       data-type="todayrejected"
                       data-users='@json($arrTodayRejected)'>
                        View all
                    </a>
                </div>
                <div class="value mt-2">{{ count($todayRejectedProfiles) }}</div>
                <div class="subtle">Rejected today</div>
                <div class="kpi-progress"><span style="--p: 30%;"></span></div>
            </div>
        </div>

        <!-- Updated Profiles -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi indigo h-100">
                <div class="meta">
                    <i class="bi bi-arrow-repeat"></i>
                    Updated Profiles
                    <a href="#"
                       class="ms-auto subtle text-decoration-none kpi-viewall"
                       data-title="Updated Profiles"
                       data-type="updated"
                       data-users='@json($arrUpdatedProfiles)'>
                        View all
                    </a>
                </div>
                <div class="value mt-2">{{ count($updatedProfiles) }}</div>
                <div class="subtle">Recently modified</div>
                <div class="kpi-progress"><span style="--p: 66%;"></span></div>
            </div>
        </div>

        <!-- Total Rejected -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi fuchsia h-100">
                <div class="meta">
                    <i class="bi bi-emoji-frown"></i>
                    Total Rejected
                    <a href="#"
                       class="ms-auto subtle text-decoration-none kpi-viewall"
                       data-title="Total Rejected"
                       data-type="rejected"
                       data-users='@json($arrRejectedAll)'>
                        View all
                    </a>
                </div>
                <div class="value mt-2">{{ count($rejectedProfiles) }}</div>
                <div class="subtle">All-time rejected</div>
                <div class="kpi-progress"><span style="--p: 22%;"></span></div>
            </div>
        </div>

        <!-- Applications: Today -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi cyan h-100">
                <div class="meta">
                    <i class="bi bi-calendar-check"></i>
                    Today’s Applications
                    <a href="#"
                       class="ms-auto subtle text-decoration-none kpi-viewall"
                       data-title="Today’s Applications"
                       data-type="apps_today"
                       data-users='@json($arrAppsToday)'>
                        View all
                    </a>
                </div>
                <div class="value mt-2">{{ count($todayApplications) }}</div>
                <div class="subtle">Submitted today</div>
                <div class="kpi-progress"><span style="--p: 48%;"></span></div>
            </div>
        </div>

        <!-- Applications: Approved -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi emerald h-100">
                <div class="meta">
                    <i class="bi bi-file-earmark-check"></i>
                    Approved Applications
                    <a href="#"
                       class="ms-auto subtle text-decoration-none kpi-viewall"
                       data-title="Approved Applications"
                       data-type="apps_approved"
                       data-users='@json($arrAppsApproved)'>
                        View all
                    </a>
                </div>
                <div class="value mt-2">{{ count($approvedApplication) }}</div>
                <div class="subtle">Accepted</div>
                <div class="kpi-progress"><span style="--p: 70%;"></span></div>
            </div>
        </div>

        <!-- Applications: Rejected -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi rose h-100">
                <div class="meta">
                    <i class="bi bi-file-earmark-x"></i>
                    Rejected Applications
                    <a href="#"
                       class="ms-auto subtle text-decoration-none kpi-viewall"
                       data-title="Rejected Applications"
                       data-type="apps_rejected"
                       data-users='@json($arrAppsRejected)'>
                        View all
                    </a>
                </div>
                <div class="value mt-2">{{ count($rejectedApplication) }}</div>
                <div class="subtle">Declined</div>
                <div class="kpi-progress"><span style="--p: 28%;"></span></div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-12">
            <div class="panel mb-3">
                <div class="panel-head d-flex align-items-center justify-content-between">
                    <div>
                        <div class="section-title">Quick Actions</div>
                        <div class="subtle">Frequently used filters & links.</div>
                    </div>
                </div>
                <div class="p-3 d-grid gap-2">
                    <a class="btn btn-outline-primary d-flex align-items-center justify-content-between"
                       href="{{ route('admin.pratihari.filterUsers', 'today') }}">
                        <span><i class="bi bi-funnel me-2"></i> Filter Today</span>
                        <i class="bi bi-arrow-right-short fs-5"></i>
                    </a>
                    <a class="btn btn-outline-success d-flex align-items-center justify-content-between"
                       href="{{ route('admin.pratihari.filterUsers', 'approved') }}">
                        <span><i class="bi bi-check2-circle me-2"></i> View Approved</span>
                        <i class="bi bi-arrow-right-short fs-5"></i>
                    </a>
                    <a class="btn btn-outline-warning d-flex align-items-center justify-content-between"
                       href="{{ route('admin.pratihari.filterUsers', 'pending') }}">
                        <span><i class="bi bi-hourglass-split me-2"></i> View Pending</span>
                        <i class="bi bi-arrow-right-short fs-5"></i>
                    </a>
                </div>
            </div>
        </div>

    </div><!-- /row -->
</div>
