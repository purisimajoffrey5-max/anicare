<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, viewport-fit=cover"
    >

    <title>Schedule Milling | Miller</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            width: 100%;
            min-height: 100%;
            overflow-x: hidden;
        }

        body {
            background: #f4f6f8;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: #1f2937;
        }

        :root {
            --primary: #198754;
            --primary-dark: #157347;
            --primary-soft: #eefaf3;
            --border: #e5e7eb;
            --muted: #6b7280;
            --shadow: 0 4px 14px rgba(15, 23, 42, .06);
        }

        /* ================================
           MAIN WRAPPER
        ================================= */

        .schedule-page {
            width: 100%;
            max-width: 1150px;
            margin: 0 auto;
            padding: 24px 16px 45px;
        }

        /* ================================
           PAGE HEADER
        ================================= */

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;

            margin-bottom: 20px;
        }

        .page-title-wrap {
            min-width: 0;
        }

        .page-title {
            margin: 0 0 5px;

            color: var(--primary);
            font-size: 30px;
            font-weight: 800;
            line-height: 1.2;
        }

        .page-subtitle {
            margin: 0;
            max-width: 520px;

            color: var(--muted);
            font-size: 15px;
            line-height: 1.5;
        }

        .back-btn {
            flex: 0 0 auto;

            min-width: 110px;
            padding: 9px 18px;

            border: 1px solid var(--primary);
            border-radius: 9px;

            color: var(--primary);
            background: #fff;

            font-weight: 600;
            text-align: center;

            transition: .18s ease;
        }

        .back-btn:hover {
            color: #fff;
            background: var(--primary);
        }

        /* ================================
           DESKTOP TABLE
        ================================= */

        .schedule-card {
            overflow: hidden;

            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow);
        }

        .desktop-table-wrapper {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .schedule-table {
            width: 100%;
            min-width: 1000px;
            margin: 0;
        }

        .schedule-table thead th {
            padding: 16px 14px;

            background: #f8fafc;
            border-bottom: 1px solid var(--border);

            color: #111827;
            font-size: 13px;
            font-weight: 800;

            white-space: nowrap;
            vertical-align: middle;
        }

        .schedule-table tbody td {
            padding: 14px;

            color: #374151;
            font-size: 13px;

            vertical-align: middle;
        }

        .schedule-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .schedule-table .schedule-form {
            min-width: 310px;

            display: flex;
            align-items: center;
            gap: 7px;
        }

        .schedule-table input[type="datetime-local"] {
            min-width: 215px;
        }

        /* ================================
           EMPTY STATE
        ================================= */

        .empty-state {
            padding: 45px 20px;

            color: var(--muted);
            text-align: center;
        }

        .empty-icon {
            margin-bottom: 8px;

            color: #9ca3af;
            font-size: 34px;
        }

        .empty-title {
            margin-bottom: 3px;

            color: #4b5563;
            font-size: 16px;
            font-weight: 700;
        }

        .empty-text {
            margin: 0;

            color: #6b7280;
            font-size: 13px;
        }

        /* ================================
           MOBILE REQUEST CARDS
        ================================= */

        .mobile-schedule-list {
            display: none;
        }

        .mobile-request-card {
            margin-bottom: 12px;
            padding: 16px;

            background: #fff;
            border: 1px solid var(--border);
            border-radius: 15px;
            box-shadow: var(--shadow);
        }

        .mobile-request-card:last-child {
            margin-bottom: 0;
        }

        .mobile-request-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;

            margin-bottom: 14px;
            padding-bottom: 12px;

            border-bottom: 1px solid #edf0f2;
        }

        .request-name {
            margin-bottom: 2px;

            color: #111827;
            font-size: 16px;
            font-weight: 800;

            overflow-wrap: anywhere;
        }

        .request-username {
            color: var(--muted);
            font-size: 11px;
        }

        .request-id {
            flex-shrink: 0;

            padding: 5px 9px;

            border-radius: 999px;
            background: var(--primary-soft);

            color: var(--primary);
            font-size: 11px;
            font-weight: 700;
        }

        .mobile-info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;

            margin-bottom: 14px;
        }

        .mobile-info {
            min-width: 0;
            padding: 10px 11px;

            background: #f8fafc;
            border-radius: 10px;
        }

        .mobile-info.full {
            grid-column: 1 / -1;
        }

        .mobile-label {
            display: block;
            margin-bottom: 3px;

            color: #8a94a3;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .mobile-value {
            color: #1f2937;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.4;

            overflow-wrap: anywhere;
        }

        /* ================================
           MOBILE FORM
        ================================= */

        .mobile-schedule-form {
            padding-top: 13px;

            border-top: 1px solid #edf0f2;
        }

        .mobile-schedule-form label {
            display: block;
            margin-bottom: 6px;

            color: #374151;
            font-size: 12px;
            font-weight: 700;
        }

        .mobile-schedule-form .form-control {
            width: 100%;
            min-height: 44px;
            margin-bottom: 8px;

            border-radius: 9px;

            font-size: 13px;
        }

        .mobile-schedule-form .save-btn {
            width: 100%;
            min-height: 43px;

            border: 0;
            border-radius: 9px;

            background: var(--primary);
            color: #fff;

            font-size: 13px;
            font-weight: 700;
        }

        .mobile-schedule-form .save-btn:hover {
            background: var(--primary-dark);
        }

        .secondary-action {
            width: 100%;
            margin-top: 8px;
        }

        .secondary-action form {
            width: 100%;
        }

        .complete-btn,
        .accept-btn {
            width: 100%;
            min-height: 42px;

            border-radius: 9px;

            font-size: 12px;
            font-weight: 700;
        }

        /* ================================
           PAGINATION
        ================================= */

        .pagination-wrap {
            margin-top: 18px;
        }

        .pagination-wrap nav {
            width: 100%;
        }

        /* ================================
           TABLET
        ================================= */

        @media (max-width: 991px) {

            .page-title {
                font-size: 27px;
            }

            .schedule-page {
                padding-left: 14px;
                padding-right: 14px;
            }
        }

        /* ================================
           MOBILE
        ================================= */

        @media (max-width: 767.98px) {

            .schedule-page {
                padding:
                    18px
                    12px
                    calc(35px + env(safe-area-inset-bottom));
            }

            .page-header {
                margin-bottom: 17px;

                flex-direction: column;
                align-items: stretch;
                gap: 13px;
            }

            .page-title {
                font-size: 25px;
            }

            .page-subtitle {
                max-width: 100%;

                font-size: 13px;
                line-height: 1.45;
            }

            /*
             * Back button is compact now.
             * No generic .btn { width:100% } rule.
             */
            .back-btn {
                width: auto;
                min-width: 0;
                align-self: flex-start;

                padding: 8px 16px;

                font-size: 13px;
            }

            /*
             * Hide desktop table completely
             * on phones.
             */
            .desktop-schedule {
                display: none;
            }

            /*
             * Show card version instead.
             */
            .mobile-schedule-list {
                display: block;
            }

            .alert {
                font-size: 12px;
            }

            .pagination-wrap {
                margin-top: 14px;
                overflow-x: auto;
            }

            .pagination {
                flex-wrap: wrap;
                gap: 3px;
            }
        }

        /* ================================
           SMALL PHONES
        ================================= */

        @media (max-width: 380px) {

            .schedule-page {
                padding-left: 10px;
                padding-right: 10px;
            }

            .page-title {
                font-size: 23px;
            }

            .mobile-request-card {
                padding: 14px;
            }

            .mobile-info-grid {
                gap: 8px;
            }

            .mobile-info {
                padding: 9px;
            }
        }

        /* ================================
           EXTRA SMALL
        ================================= */

        @media (max-width: 320px) {

            .mobile-info-grid {
                grid-template-columns: 1fr;
            }

            .mobile-info.full {
                grid-column: auto;
            }
        }
    </style>
</head>


<body>

<main class="schedule-page">

    {{-- ======================================
         HEADER
    ======================================= --}}
    <div class="page-header">

        <div class="page-title-wrap">

            <h1 class="page-title">
                Schedule Milling
            </h1>

            <p class="page-subtitle">
                Live schedule view for assigned and approved requests.
            </p>

        </div>

        <a
            href="{{ route('miller.dashboard') }}"
            class="back-btn"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

    </div>


    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm">
            {{ session('success') }}
        </div>

    @endif


    {{-- ======================================
         DESKTOP / TABLET TABLE
    ======================================= --}}
    <div class="desktop-schedule">

        <div class="schedule-card">

            <div class="desktop-table-wrapper">

                <table class="table table-hover align-middle schedule-table">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Requester</th>
                            <th>Product</th>
                            <th>Kilos</th>
                            <th>Status</th>
                            <th>Current Schedule</th>
                            <th>Set New Schedule</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($approved as $r)

                        <tr>

                            <td>
                                {{ $r->id }}
                            </td>

                            <td>
                                <strong>
                                    {{ optional($r->user)->fullname ?? 'User #' . $r->user_id }}
                                </strong>

                                <br>

                                <span class="small text-muted">
                                    {{ $r->user ? $r->user->username : 'Farmer' }}
                                </span>
                            </td>

                            <td>
                                {{ optional($r->inventoryItem)->name ?? 'Milling request' }}
                            </td>

                            <td>
                                {{ number_format($r->kilos, 2) }}
                            </td>

                            <td>
                                <span
                                    class="badge bg-{{ $r->status === 'assigned' ? 'warning text-dark' : 'primary' }} text-uppercase"
                                >
                                    {{ $r->status }}
                                </span>
                            </td>

                            <td>
                                {{ $r->scheduled_at
                                    ? $r->scheduled_at->format('Y-m-d H:i')
                                    : '-'
                                }}
                            </td>

                            <td>

                                <form
                                    class="schedule-form"
                                    method="POST"
                                    action="{{ route('miller.schedule.set', $r->id) }}"
                                >
                                    @csrf

                                    <input
                                        type="datetime-local"
                                        name="scheduled_at"
                                        class="form-control form-control-sm"
                                        required
                                    >

                                    <button
                                        type="submit"
                                        class="btn btn-success btn-sm"
                                    >
                                        Save
                                    </button>

                                </form>


                                @if($r->status === 'approved')

                                    <form
                                        class="mt-2"
                                        method="POST"
                                        action="{{ route('miller.requests.complete', $r->id) }}"
                                    >
                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn btn-primary btn-sm"
                                            onclick="return confirm('Mark this milling as completed?')"
                                        >
                                            Complete
                                        </button>
                                    </form>

                                @elseif($r->status === 'assigned')

                                    <form
                                        class="mt-2"
                                        method="POST"
                                        action="{{ route('miller.requests.accept', $r->id) }}"
                                    >
                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn btn-outline-primary btn-sm"
                                            onclick="return confirm('Accept this assigned request?')"
                                        >
                                            Accept
                                        </button>
                                    </form>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        <i class="bi bi-calendar2-x"></i>
                                    </div>

                                    <div class="empty-title">
                                        No schedules yet
                                    </div>

                                    <p class="empty-text">
                                        No approved or assigned requests yet.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ======================================
         MOBILE VERSION
    ======================================= --}}
    <div class="mobile-schedule-list">

        @forelse($approved as $r)

            <article class="mobile-request-card">

                {{-- REQUEST HEADER --}}
                <div class="mobile-request-head">

                    <div>

                        <div class="request-name">
                            {{ optional($r->user)->fullname ?? 'User #' . $r->user_id }}
                        </div>

                        <div class="request-username">
                            {{ $r->user ? $r->user->username : 'Farmer' }}
                        </div>

                    </div>

                    <div class="request-id">
                        #{{ $r->id }}
                    </div>

                </div>


                {{-- REQUEST INFO --}}
                <div class="mobile-info-grid">

                    <div class="mobile-info">

                        <span class="mobile-label">
                            Product
                        </span>

                        <div class="mobile-value">
                            {{ optional($r->inventoryItem)->name ?? 'Milling request' }}
                        </div>

                    </div>


                    <div class="mobile-info">

                        <span class="mobile-label">
                            Kilos
                        </span>

                        <div class="mobile-value">
                            {{ number_format($r->kilos, 2) }} kg
                        </div>

                    </div>


                    <div class="mobile-info">

                        <span class="mobile-label">
                            Status
                        </span>

                        <div class="mobile-value">

                            <span
                                class="badge bg-{{ $r->status === 'assigned' ? 'warning text-dark' : 'primary' }} text-uppercase"
                            >
                                {{ $r->status }}
                            </span>

                        </div>

                    </div>


                    <div class="mobile-info">

                        <span class="mobile-label">
                            Current Schedule
                        </span>

                        <div class="mobile-value">

                            {{ $r->scheduled_at
                                ? $r->scheduled_at->format('M d, Y h:i A')
                                : 'Not scheduled'
                            }}

                        </div>

                    </div>

                </div>


                {{-- SET SCHEDULE --}}
                <div class="mobile-schedule-form">

                    <form
                        method="POST"
                        action="{{ route('miller.schedule.set', $r->id) }}"
                    >
                        @csrf

                        <label>
                            <i class="bi bi-calendar-event me-1"></i>
                            Set New Schedule
                        </label>

                        <input
                            type="datetime-local"
                            name="scheduled_at"
                            class="form-control"
                            required
                        >

                        <button
                            type="submit"
                            class="save-btn"
                        >
                            <i class="bi bi-check-circle me-1"></i>
                            Save Schedule
                        </button>

                    </form>


                    {{-- COMPLETE --}}
                    @if($r->status === 'approved')

                        <div class="secondary-action">

                            <form
                                method="POST"
                                action="{{ route('miller.requests.complete', $r->id) }}"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-primary complete-btn"
                                    onclick="return confirm('Mark this milling as completed?')"
                                >
                                    <i class="bi bi-check2-all me-1"></i>
                                    Mark as Completed
                                </button>

                            </form>

                        </div>

                    {{-- ACCEPT --}}
                    @elseif($r->status === 'assigned')

                        <div class="secondary-action">

                            <form
                                method="POST"
                                action="{{ route('miller.requests.accept', $r->id) }}"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-outline-primary accept-btn"
                                    onclick="return confirm('Accept this assigned request?')"
                                >
                                    <i class="bi bi-check-circle me-1"></i>
                                    Accept Request
                                </button>

                            </form>

                        </div>

                    @endif

                </div>

            </article>

        @empty

            <div class="mobile-request-card">

                <div class="empty-state">

                    <div class="empty-icon">
                        <i class="bi bi-calendar2-x"></i>
                    </div>

                    <div class="empty-title">
                        No schedules yet
                    </div>

                    <p class="empty-text">
                        No approved or assigned requests yet.
                    </p>

                </div>

            </div>

        @endforelse

    </div>


    {{-- ======================================
         PAGINATION
    ======================================= --}}
    @if(method_exists($approved, 'links'))

        <div class="pagination-wrap">
            {{ $approved->links() }}
        </div>

    @endif

</main>


<script>
    /*
     * Auto refresh every 30 seconds.
     * Do not refresh while user is entering
     * a schedule date/time.
     */
    let userIsEditing = false;

    document.querySelectorAll(
        'input[type="datetime-local"]'
    ).forEach(function(input) {

        input.addEventListener('focus', function() {
            userIsEditing = true;
        });

        input.addEventListener('change', function() {
            userIsEditing = true;
        });

        input.addEventListener('blur', function() {
            setTimeout(function() {
                userIsEditing = false;
            }, 3000);
        });

    });

    setInterval(function() {

        if (!userIsEditing) {
            window.location.reload();
        }

    }, 30000);
</script>

</body>
</html>