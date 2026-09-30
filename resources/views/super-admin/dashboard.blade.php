<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        Super Admin Control Center | ANI-CARE
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <style>

        .manager-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 22px;
        }

        .security-event {
            transition: 0.2s;
        }

        .security-event:hover {
            background: #f8f9fa;
        }

    </style>

</head>


<body class="bg-light">


{{-- =====================================================
     NAVBAR
====================================================== --}}

<nav class="navbar navbar-dark bg-dark">

    <div class="container">

        <span class="navbar-brand fw-bold">

            <i class="bi bi-shield-lock-fill me-2"></i>

            ANI-CARE Super Admin

        </span>


        <div class="d-flex align-items-center gap-2">

            <span class="text-white small">

                {{ Auth::user()->fullname }}

            </span>


            <form
                method="POST"
                action="{{ route('logout') }}"
                class="m-0"
            >

                @csrf

                <button class="btn btn-warning btn-sm">

                    Logout

                </button>

            </form>

        </div>

    </div>

</nav>



<main class="container py-4">


{{-- =====================================================
     SUCCESS MESSAGE
====================================================== --}}

@if(session('success'))

    <div class="alert alert-success">

        <i class="bi bi-check-circle-fill me-2"></i>

        {{ session('success') }}

    </div>

@endif



{{-- =====================================================
     HEADER
====================================================== --}}

<div class="mb-4">

    <h2 class="fw-bold">

        System Control Center

    </h2>

    <p class="text-muted mb-0">

        Super Admin security, recovery, backup,
        monitoring and system management.

    </p>

</div>



{{-- =====================================================
     USER STATISTICS
====================================================== --}}

<div class="row g-3 mb-4">

@foreach([

    ['Users', $users, 'bi-people-fill'],

    ['Regular Admins', $admins, 'bi-person-gear'],

    ['Super Admins', $superAdmins, 'bi-shield-lock-fill'],

    ['Farmers', $farmers, 'bi-person-fill'],

    ['Millers', $millers, 'bi-person-workspace'],

    ['Residents', $residents, 'bi-house-fill'],

] as [$label, $value, $icon])


<div class="col-6 col-md-4 col-xl-2">

    <div class="card border-0 shadow-sm h-100">

        <div class="card-body text-center">

            <i
                class="bi {{ $icon }} fs-3 text-success"
            ></i>

            <div class="small text-muted mt-2">

                {{ $label }}

            </div>

            <div class="fs-3 fw-bold">

                {{ $value }}

            </div>

        </div>

    </div>

</div>


@endforeach

</div>



{{-- =====================================================
     SECURITY & RISK MONITOR
====================================================== --}}

<div class="card border-0 shadow-sm mb-4">


    <div class="card-header bg-dark text-white py-3">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h5 class="mb-0 fw-bold">

                    <i class="bi bi-shield-check me-2"></i>

                    Security & Risk Monitor

                </h5>

                <small class="text-white-50">

                    Application security monitoring

                </small>

            </div>


            <span
                class="badge bg-{{ $securityRiskClass }} px-3 py-2"
            >

                <i class="bi bi-shield-fill-check me-1"></i>

                {{ $securityRisk }}

                RISK

            </span>

        </div>

    </div>



    <div class="card-body">


        {{-- SECURITY CARDS --}}

        <div class="row g-3 mb-4">


            {{-- OVERALL RISK --}}

            <div class="col-md-6 col-lg-3">

                <div class="card border-{{ $securityRiskClass }} h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <small class="text-muted">

                                    Overall Risk

                                </small>

                                <h3
                                    class="fw-bold text-{{ $securityRiskClass }} mb-0"
                                >

                                    {{ $securityRisk }}

                                </h3>

                            </div>


                            <div
                                class="manager-icon bg-{{ $securityRiskClass }}-subtle text-{{ $securityRiskClass }}"
                            >

                                <i class="bi bi-shield-check"></i>

                            </div>

                        </div>


                        <small class="text-muted">

                            Based on security events
                            recorded within 24 hours.

                        </small>

                    </div>

                </div>

            </div>



            {{-- FAILED LOGIN --}}

            <div class="col-md-6 col-lg-3">

                <div class="card border-warning h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <small class="text-muted">

                                    Failed Logins

                                </small>

                                <h3 class="fw-bold text-warning mb-0">

                                    {{ $securityStats['failed_logins'] }}

                                </h3>

                            </div>


                            <div
                                class="manager-icon bg-warning-subtle text-warning"
                            >

                                <i class="bi bi-key-fill"></i>

                            </div>

                        </div>


                        <small class="text-muted">

                            Last 24 hours

                        </small>

                    </div>

                </div>

            </div>



            {{-- SECURITY EVENTS --}}

            <div class="col-md-6 col-lg-3">

                <div class="card border-danger h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <small class="text-muted">

                                    Security Events

                                </small>

                                <h3 class="fw-bold text-danger mb-0">

                                    {{ $securityStats['security_events'] }}

                                </h3>

                            </div>


                            <div
                                class="manager-icon bg-danger-subtle text-danger"
                            >

                                <i class="bi bi-exclamation-triangle-fill"></i>

                            </div>

                        </div>


                        <small class="text-muted">

                            Last 24 hours

                        </small>

                    </div>

                </div>

            </div>



            {{-- FILE INTEGRITY --}}

            <div class="col-md-6 col-lg-3">

                <div class="card border-info h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <small class="text-muted">

                                    File Integrity

                                </small>

                                <h3 class="fw-bold text-info mb-0">

                                    CHECKED

                                </h3>

                            </div>


                            <div
                                class="manager-icon bg-info-subtle text-info"
                            >

                                <i class="bi bi-file-earmark-check-fill"></i>

                            </div>

                        </div>


                        <small class="text-muted">

                            Application-level monitoring

                        </small>

                    </div>

                </div>

            </div>


        </div>



        {{-- SECURITY TOOLS --}}

        <div class="row g-3">


            {{-- LOGIN SECURITY --}}

            <div class="col-md-6 col-lg-4">

                <div class="card h-100">

                    <div class="card-body">

                        <div
                            class="manager-icon bg-primary-subtle text-primary mb-3"
                        >

                            <i class="bi bi-person-lock"></i>

                        </div>


                        <h6 class="fw-bold">

                            Login Security

                        </h6>


                        <p class="small text-muted">

                            Monitors failed login attempts
                            and possible unauthorized account access.

                        </p>


                        <div class="alert alert-light border small">

                            <strong>

                                Failed Attempts:

                            </strong>

                            {{ $securityStats['failed_logins'] }}

                        </div>


                        <span class="badge bg-primary">

                            Monitoring Active

                        </span>

                    </div>

                </div>

            </div>



            {{-- REQUEST MONITOR --}}

            <div class="col-md-6 col-lg-4">

                <div class="card h-100">

                    <div class="card-body">

                        <div
                            class="manager-icon bg-warning-subtle text-warning mb-3"
                        >

                            <i class="bi bi-globe2"></i>

                        </div>


                        <h6 class="fw-bold">

                            IP & Request Monitor

                        </h6>


                        <p class="small text-muted">

                            Detects suspicious request paths
                            and unauthorized access attempts.

                        </p>


                        <div class="alert alert-light border small">

                            <strong>

                                Suspicious Requests:

                            </strong>

                            {{ $securityStats['suspicious_requests'] }}

                        </div>


                        <span class="badge bg-warning text-dark">

                            Monitoring Active

                        </span>

                    </div>

                </div>

            </div>



            {{-- FILE MONITOR --}}

            <div class="col-md-6 col-lg-4">

                <div class="card h-100">

                    <div class="card-body">

                        <div
                            class="manager-icon bg-danger-subtle text-danger mb-3"
                        >

                            <i class="bi bi-file-earmark-binary"></i>

                        </div>


                        <h6 class="fw-bold">

                            File Integrity

                        </h6>


                        <p class="small text-muted">

                            Application-level monitoring for
                            unexpected file activity.

                        </p>


                        <div class="alert alert-light border small">

                            <strong>

                                Suspicious Files:

                            </strong>

                            {{ $securityStats['suspicious_files'] }}

                        </div>


                        <span class="badge bg-danger">

                            Scanner Ready

                        </span>

                    </div>

                </div>

            </div>


        </div>


        {{-- SECURITY NOTICE --}}

        <div class="alert alert-info mt-4 mb-0">

            <i class="bi bi-info-circle-fill me-2"></i>

            <strong>Security Monitoring:</strong>

            ANI-CARE records application-level security
            events such as unauthorized requests and
            suspicious request patterns.

            This does not replace a dedicated
            antivirus or server security product.

        </div>


    </div>

</div>



{{-- =====================================================
     RECOVERY CENTER
====================================================== --}}

<div class="row g-3 mb-4">


    <div class="col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <h5 class="fw-bold">

                    <i
                        class="bi bi-database-check text-success me-2"
                    ></i>

                    Recovery Center

                </h5>


                <p class="text-muted">

                    Create a recovery archive containing
                    ANI-CARE database data and application
                    upload/storage files.

                </p>


                <a
                    href="{{ route('super-admin.backup') }}"
                    class="btn btn-success"
                >

                    <i class="bi bi-download me-1"></i>

                    Create & Download Backup

                </a>


                <a
                    href="{{ route('super-admin.restore') }}"
                    class="btn btn-outline-danger ms-2"
                >

                    <i class="bi bi-arrow-counterclockwise me-1"></i>

                    Recovery / Restore

                </a>

            </div>

        </div>

    </div>



    <div class="col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <h5 class="fw-bold">

                    <i
                        class="bi bi-grid-1x2-fill text-success me-2"
                    ></i>

                    Regular Admin

                </h5>


                <p class="text-muted">

                    Super Admin can use the existing
                    Admin modules and system operations.

                </p>


                <a
                    href="{{ route('admin.dashboard') }}"
                    class="btn btn-outline-success"
                >

                    Open Admin Dashboard

                </a>

            </div>

        </div>

    </div>

</div>



{{-- =====================================================
     SECURITY EVENTS
====================================================== --}}

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white">

        <div class="d-flex justify-content-between align-items-center">

            <strong>

                <i class="bi bi-exclamation-diamond me-2"></i>

                Recent Security Events

            </strong>

            <span class="badge bg-secondary">

                Last 15 Events

            </span>

        </div>

    </div>


    <div class="table-responsive">

        <table class="table table-sm table-hover mb-0">

            <thead>

                <tr>

                    <th>Date</th>

                    <th>Event</th>

                    <th>Risk</th>

                    <th>IP Address</th>

                    <th>Route</th>

                    <th>User</th>

                    <th>Description</th>

                </tr>

            </thead>


            <tbody>


            @forelse($securityEvents as $event)


                <tr class="security-event">

                    <td class="small">

                        {{ $event->created_at?->format('M d, Y h:i A') }}

                    </td>


                    <td>

                        <span class="badge bg-secondary">

                            {{ $event->event_type }}

                        </span>

                    </td>


                    <td>

                        @if($event->risk_level === 'high')

                            <span class="badge bg-danger">

                                HIGH

                            </span>

                        @elseif($event->risk_level === 'medium')

                            <span class="badge bg-warning text-dark">

                                MEDIUM

                            </span>

                        @else

                            <span class="badge bg-success">

                                LOW

                            </span>

                        @endif

                    </td>


                    <td class="small">

                        {{ $event->ip_address ?? 'Unknown' }}

                    </td>


                    <td class="small">

                        {{ $event->route ?? '-' }}

                    </td>


                    <td class="small">

                        {{ $event->user?->fullname ?? 'Guest' }}

                    </td>


                    <td class="small">

                        {{ $event->description }}

                    </td>

                </tr>


            @empty


                <tr>

                    <td
                        colspan="7"
                        class="text-center text-muted py-4"
                    >

                        <i
                            class="bi bi-shield-check fs-2 text-success d-block mb-2"
                        ></i>

                        No security events recorded.

                    </td>

                </tr>


            @endforelse


            </tbody>

        </table>

    </div>

</div>



{{-- =====================================================
     SUPER ADMIN AUDIT LOG
====================================================== --}}

<div class="card border-0 shadow-sm">

    <div class="card-header bg-white fw-bold">

        Super Admin Audit Log

    </div>


    <div class="table-responsive">

        <table class="table table-sm table-hover mb-0">

            <thead>

                <tr>

                    <th>Date</th>

                    <th>Action</th>

                    <th>User</th>

                    <th>Details</th>

                </tr>

            </thead>


            <tbody>


            @forelse($logs as $log)


                <tr>

                    <td>

                        {{ $log->created_at?->format('M d, Y h:i A') }}

                    </td>


                    <td>

                        <span class="badge text-bg-secondary">

                            {{ $log->action }}

                        </span>

                    </td>


                    <td>

                        {{ $log->user?->fullname ?? 'System' }}

                    </td>


                    <td class="small">

                        {{ $log->details }}

                    </td>

                </tr>


            @empty


                <tr>

                    <td
                        colspan="4"
                        class="text-center text-muted py-4"
                    >

                        No recovery actions recorded.

                    </td>

                </tr>


            @endforelse


            </tbody>

        </table>

    </div>

</div>


</main>


</body>

</html>