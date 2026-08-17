<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Users Management | ANI-CARE</title>

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    >

    <style>

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: #f4f6f8;
            color: #212529;
        }

        /* ========================================
           MAIN CONTAINER
        ======================================== */

        .page-container {
            width: 100%;
            max-width: 1600px;
            margin: 0 auto;
            padding: 28px 22px 50px;
        }

        /* ========================================
           HEADER
        ======================================== */

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
        }

        .page-title {
            margin: 0;
            color: #198754;
            font-weight: 750;
            font-size: 30px;
        }

        .page-subtitle {
            color: #6c757d;
            font-size: 14px;
            margin-top: 3px;
        }

        .back-btn {
            white-space: nowrap;
        }

        /* ========================================
           STAT CARDS
        ======================================== */

        .stat-card {
            height: 100%;
            border: 0;
            border-radius: 13px;
            box-shadow: 0 3px 12px rgba(0,0,0,.06);
            transition: .2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 18px rgba(0,0,0,.09);
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 20px;

            background: #edf8f2;
            color: #198754;
        }

        .stat-label {
            color: #6c757d;
            font-size: 13px;
            margin-bottom: 2px;
        }

        .stat-number {
            font-size: 25px;
            font-weight: 750;
            line-height: 1.1;
        }

        /* ========================================
           FILTER CARD
        ======================================== */

        .filter-card {
            border: 0;
            border-radius: 13px;
            box-shadow: 0 3px 12px rgba(0,0,0,.06);
        }

        .form-control,
        .form-select {
            min-height: 44px;
            border-radius: 8px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #198754;
            box-shadow: 0 0 0 .2rem rgba(25,135,84,.12);
        }

        /* ========================================
           TABLE CARD
        ======================================== */

        .users-card {
            border: 0;
            border-radius: 13px;
            box-shadow: 0 3px 12px rgba(0,0,0,.06);
            overflow: hidden;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .users-table {
            min-width: 1350px;
            margin-bottom: 0;
        }

        .users-table thead th {
            padding: 14px 12px;
            white-space: nowrap;

            background: #f8f9fa;

            color: #343a40;

            border-bottom: 2px solid #e9ecef;

            font-size: 13px;
            font-weight: 700;
        }

        .users-table tbody td {
            padding: 13px 12px;
            vertical-align: middle;
            font-size: 13px;
        }

        .users-table tbody tr:hover {
            background: #f8fcfa;
        }

        .user-name {
            font-weight: 700;
            color: #212529;
        }

        .business-name {
            margin-top: 2px;
            color: #198754;
            font-size: 12px;
        }

        /* ========================================
           ROLE BADGES
        ======================================== */

        .role-badge {
            padding: 6px 9px;
            border-radius: 7px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .2px;
        }

        .role-admin {
            background: #dc3545;
            color: #fff;
        }

        .role-resident {
            background: #0d6efd;
            color: #fff;
        }

        .role-farmer {
            background: #198754;
            color: #fff;
        }

        .role-miller {
            background: #6c757d;
            color: #fff;
        }

        /* ========================================
           STATUS
        ======================================== */

        .status-badge {
            padding: 6px 9px;
            border-radius: 7px;
            font-size: 11px;
            font-weight: 700;
        }

        /* ========================================
           ACTION BUTTONS
        ======================================== */

        .action-wrapper {
            display: flex;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 5px;
        }

        .action-wrapper form {
            margin: 0;
        }

        .action-wrapper .btn {
            min-width: 65px;
        }

        /* ========================================
           RESULT INFO
        ======================================== */

        .result-info {
            border-top: 1px solid #eee;
            padding: 14px 15px;
            color: #6c757d;
            font-size: 13px;
        }

        /* ========================================
           MOBILE
        ======================================== */

        @media (max-width: 991px) {

            .page-container {
                padding: 22px 15px 40px;
            }

            .page-header {
                align-items: flex-start;
            }

        }

        @media (max-width: 768px) {

            .page-container {
                padding: 18px 12px 35px;
            }

            .page-header {
                flex-direction: column;
                gap: 15px;
            }

            .page-title {
                font-size: 26px;
            }

            .back-btn {
                width: 100%;
                min-height: 44px;
            }

            .filter-buttons {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }

            .filter-buttons .btn {
                width: 100%;
            }

            .users-card .card-body {
                padding: 14px !important;
            }

        }

        @media (max-width: 576px) {

            .page-title {
                font-size: 24px;
            }

            .stat-number {
                font-size: 22px;
            }

            .stat-card .card-body {
                padding: 14px;
            }

            .filter-card .card-body {
                padding: 14px;
            }

        }

    </style>

</head>


<body>

<div class="page-container">


    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="page-header">

        <div>

            <h2 class="page-title">

                <i class="bi bi-people-fill"></i>

                Users Management

            </h2>

            <div class="page-subtitle">

                Manage all Admin, Resident, Farmer and Miller accounts in ANI-CARE.

            </div>

        </div>


        <a
            href="{{ route('admin.dashboard') }}"
            class="btn btn-outline-success back-btn"
        >

            <i class="bi bi-arrow-left"></i>

            Back to Dashboard

        </a>

    </div>



    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle-fill me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif



    {{-- =========================================================
        DELETE ERROR
    ========================================================== --}}

    @if($errors->has('delete'))

        <div class="alert alert-danger">

            <i class="bi bi-exclamation-triangle-fill me-1"></i>

            {{ $errors->first('delete') }}

        </div>

    @endif



    {{-- =========================================================
        GENERAL ERRORS
    ========================================================== --}}

    @if($errors->any() && !$errors->has('delete'))

        <div class="alert alert-danger">

            <i class="bi bi-exclamation-triangle-fill me-1"></i>

            {{ $errors->first() }}

        </div>

    @endif



    {{-- =========================================================
        STATISTICS
    ========================================================== --}}

    <div class="row g-3 mb-4">


        {{-- TOTAL USERS --}}

        <div class="col-xl-2 col-lg-4 col-md-4 col-6">

            <div class="card stat-card">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-3">

                        <div class="stat-icon">

                            <i class="bi bi-people"></i>

                        </div>

                        <div>

                            <div class="stat-label">
                                Total Users
                            </div>

                            <div class="stat-number">
                                {{ $totalUsers ?? 0 }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- ADMIN --}}

        <div class="col-xl-2 col-lg-4 col-md-4 col-6">

            <div class="card stat-card">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-3">

                        <div class="stat-icon">

                            <i class="bi bi-shield-lock"></i>

                        </div>

                        <div>

                            <div class="stat-label">
                                Admin
                            </div>

                            <div class="stat-number">
                                {{ $totalAdmins ?? 0 }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- RESIDENTS --}}

        <div class="col-xl-2 col-lg-4 col-md-4 col-6">

            <div class="card stat-card">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-3">

                        <div class="stat-icon">

                            <i class="bi bi-house-door"></i>

                        </div>

                        <div>

                            <div class="stat-label">
                                Residents
                            </div>

                            <div class="stat-number">
                                {{ $totalResidents ?? 0 }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- FARMERS --}}

        <div class="col-xl-2 col-lg-4 col-md-4 col-6">

            <div class="card stat-card">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-3">

                        <div class="stat-icon">

                            <i class="bi bi-flower1"></i>

                        </div>

                        <div>

                            <div class="stat-label">
                                Farmers
                            </div>

                            <div class="stat-number">
                                {{ $totalFarmers ?? 0 }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- MILLERS --}}

        <div class="col-xl-2 col-lg-4 col-md-4 col-6">

            <div class="card stat-card">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-3">

                        <div class="stat-icon">

                            <i class="bi bi-gear"></i>

                        </div>

                        <div>

                            <div class="stat-label">
                                Millers
                            </div>

                            <div class="stat-number">
                                {{ $totalMillers ?? 0 }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- PENDING --}}

        <div class="col-xl-2 col-lg-4 col-md-4 col-6">

            <div class="card stat-card">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-3">

                        <div class="stat-icon">

                            <i class="bi bi-hourglass-split"></i>

                        </div>

                        <div>

                            <div class="stat-label">
                                Pending
                            </div>

                            <div class="stat-number text-warning">

                                {{ $pendingCount ?? $pendingApproval ?? 0 }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        FILTERS
    ========================================================== --}}

    <div class="card filter-card mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.farmers_millers') }}"
                class="row g-2 align-items-center"
            >


                {{-- SEARCH --}}

                <div class="col-lg-4 col-md-12">

                    <div class="input-group">

                        <span class="input-group-text">

                            <i class="bi bi-search"></i>

                        </span>

                        <input
                            type="text"
                            name="q"
                            class="form-control"
                            placeholder="Search name, username, email, mobile, business..."
                            value="{{ $search ?? '' }}"
                        >

                    </div>

                </div>



                {{-- ROLE --}}

                <div class="col-lg-3 col-md-4">

                    <select
                        name="role"
                        class="form-select"
                    >

                        <option
                            value="all"
                            {{ ($role ?? 'all') === 'all' ? 'selected' : '' }}
                        >
                            All Roles
                        </option>

                        <option
                            value="admin"
                            {{ ($role ?? '') === 'admin' ? 'selected' : '' }}
                        >
                            Admin
                        </option>

                        <option
                            value="resident"
                            {{ ($role ?? '') === 'resident' ? 'selected' : '' }}
                        >
                            Resident
                        </option>

                        <option
                            value="farmer"
                            {{ ($role ?? '') === 'farmer' ? 'selected' : '' }}
                        >
                            Farmer
                        </option>

                        <option
                            value="miller"
                            {{ ($role ?? '') === 'miller' ? 'selected' : '' }}
                        >
                            Miller
                        </option>

                    </select>

                </div>



                {{-- STATUS --}}

                <div class="col-lg-2 col-md-4">

                    <select
                        name="status"
                        class="form-select"
                    >

                        <option
                            value="all"
                            {{ ($status ?? 'all') === 'all' ? 'selected' : '' }}
                        >
                            All Status
                        </option>

                        <option
                            value="pending"
                            {{ ($status ?? '') === 'pending' ? 'selected' : '' }}
                        >
                            Pending
                        </option>

                        <option
                            value="approved"
                            {{ ($status ?? '') === 'approved' ? 'selected' : '' }}
                        >
                            Approved
                        </option>

                    </select>

                </div>



                {{-- BUTTONS --}}

                <div class="col-lg-3 col-md-4">

                    <div class="filter-buttons d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-success flex-fill"
                        >

                            <i class="bi bi-funnel"></i>

                            Apply

                        </button>


                        <a
                            href="{{ route('admin.farmers_millers') }}"
                            class="btn btn-outline-secondary flex-fill"
                        >

                            <i class="bi bi-arrow-counterclockwise"></i>

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>



    {{-- =========================================================
        USERS TABLE
    ========================================================== --}}

    <div class="card users-card">

        <div class="card-body p-3">


            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>

                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-list-ul text-success"></i>
                        User Accounts
                    </h5>

                    <div class="small text-muted">

                        All matching ANI-CARE accounts

                    </div>

                </div>


                <span class="badge bg-success">

                    {{ $users->count() }}
                    Result{{ $users->count() === 1 ? '' : 's' }}

                </span>

            </div>


            <div class="table-responsive">

                <table class="table table-hover users-table align-middle">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>User / Business</th>

                            <th>Username</th>

                            <th>Email</th>

                            <th>Mobile Number</th>

                            <th>Barangay</th>

                            <th>Role</th>

                            <th>Status</th>

                            <th>Approved At</th>

                            <th>Registered</th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($users as $u)

                        @php

                            $roleClass = match($u->role) {

                                'admin' =>
                                    'role-admin',

                                'resident' =>
                                    'role-resident',

                                'farmer' =>
                                    'role-farmer',

                                'miller' =>
                                    'role-miller',

                                default =>
                                    'role-miller'
                            };

                        @endphp


                        <tr>


                            {{-- ID --}}

                            <td>

                                {{ $u->id }}

                            </td>



                            {{-- FULLNAME / BUSINESS --}}

                            <td>

                                <div class="user-name">

                                    {{ $u->fullname ?? 'No Name' }}

                                </div>


                                @if(!empty($u->business_name))

                                    <div class="business-name">

                                        <i class="bi bi-building"></i>

                                        {{ $u->business_name }}

                                    </div>

                                @endif

                            </td>



                            {{-- USERNAME --}}

                            <td>

                                {{ $u->username ?? '-' }}

                            </td>



                            {{-- EMAIL --}}

                            <td>

                                @if(!empty($u->email))

                                    {{ $u->email }}

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>



                            {{-- MOBILE --}}

                            <td>

                                @if(!empty($u->mobile_number))

                                    <i class="bi bi-phone text-success"></i>

                                    {{ $u->mobile_number }}

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>



                            {{-- BARANGAY --}}

                            <td>

                                @if(!empty($u->barangay))

                                    <i class="bi bi-geo-alt text-success"></i>

                                    {{ $u->barangay }}

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>



                            {{-- ROLE --}}

                            <td>

                                <span class="role-badge {{ $roleClass }}">

                                    {{ strtoupper($u->role ?? 'UNKNOWN') }}

                                </span>

                            </td>



                            {{-- STATUS --}}

                            <td>

                                @if($u->role === 'admin')

                                    <span class="badge bg-dark status-badge">

                                        SYSTEM ADMIN

                                    </span>

                                @elseif($u->is_approved)

                                    <span class="badge bg-success status-badge">

                                        <i class="bi bi-check-circle-fill"></i>

                                        APPROVED

                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark status-badge">

                                        <i class="bi bi-hourglass-split"></i>

                                        PENDING

                                    </span>

                                @endif

                            </td>



                            {{-- APPROVED AT --}}

                            <td>

                                @if($u->approved_at)

                                    {{ \Carbon\Carbon::parse($u->approved_at)->format('Y-m-d H:i') }}

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>



                            {{-- REGISTERED --}}

                            <td>

                                @if($u->created_at)

                                    {{ \Carbon\Carbon::parse($u->created_at)->format('Y-m-d') }}

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>



                            {{-- ACTIONS --}}

                            <td>

                                <div class="action-wrapper">


                                    {{-- VIEW --}}

                                    <a
                                        href="{{ route('admin.farmers_millers.show', $u->id) }}"
                                        class="btn btn-outline-success btn-sm"
                                    >

                                        <i class="bi bi-eye"></i>

                                        View

                                    </a>



                                    {{-- DO NOT MODIFY ADMIN --}}

                                    @if($u->role !== 'admin')


                                        {{-- APPROVE --}}

                                        @if(!$u->is_approved)

                                            <form
                                                method="POST"
                                                action="{{ route('admin.approvals.approve', $u->id) }}"
                                                onsubmit="return confirm('Approve {{ addslashes($u->fullname) }}?');"
                                            >

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="btn btn-success btn-sm"
                                                >

                                                    <i class="bi bi-check-circle"></i>

                                                    Approve

                                                </button>

                                            </form>


                                        {{-- REVOKE --}}

                                        @else

                                            <form
                                                method="POST"
                                                action="{{ route('admin.approvals.revoke', $u->id) }}"
                                                onsubmit="return confirm('Revoke approval for {{ addslashes($u->fullname) }}?');"
                                            >

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="btn btn-outline-warning btn-sm"
                                                >

                                                    <i class="bi bi-x-circle"></i>

                                                    Revoke

                                                </button>

                                            </form>

                                        @endif



                                        {{-- DELETE --}}

                                        <form
                                            method="POST"
                                            action="{{ route('admin.farmers_millers.delete', $u->id) }}"
                                            onsubmit="return confirm('Delete {{ addslashes($u->fullname) }}? This action cannot be undone.');"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn btn-outline-danger btn-sm"
                                            >

                                                <i class="bi bi-trash"></i>

                                                Delete

                                            </button>

                                        </form>


                                    @else

                                        <span class="badge bg-light text-dark border">

                                            <i class="bi bi-shield-lock"></i>

                                            Protected

                                        </span>

                                    @endif


                                </div>

                            </td>


                        </tr>


                    @empty


                        <tr>

                            <td
                                colspan="11"
                                class="text-center py-5"
                            >

                                <i class="bi bi-search fs-1 text-muted"></i>

                                <div class="fw-bold mt-2">

                                    No users found

                                </div>

                                <div class="text-muted small">

                                    Try changing your search or filters.

                                </div>

                            </td>

                        </tr>


                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>



        {{-- RESULT COUNT --}}

        <div class="result-info">

            <i class="bi bi-info-circle"></i>

            Showing all

            <strong>
                {{ $users->count() }}
            </strong>

            matching user(s).

            @if(
                ($role ?? 'all') !== 'all' ||
                ($status ?? 'all') !== 'all' ||
                !empty($search)
            )

                <span class="ms-1">

                    Filters are currently applied.

                </span>

            @endif

        </div>

    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
</script>


</body>
</html>