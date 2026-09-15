<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1"
    >

    <title>Miller Dashboard | ANI-CARE</title>

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
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            width: 100%;
            overflow-x: hidden;
        }

        :root {
            --primary: #198754;
            --primary-dark: #157347;
            --primary-soft: #eefaf3;
            --bg: #f4f6f8;
            --card: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --border: #e5e7eb;
            --shadow: 0 4px 14px rgba(15, 23, 42, .06);
            --radius: 16px;
        }

        body {
            width: 100%;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            color: var(--text);
            -webkit-text-size-adjust: 100%;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button,
        a,
        input,
        select {
            -webkit-tap-highlight-color: transparent;
        }

        img,
        video,
        iframe,
        svg,
        canvas {
            max-width: 100%;
            height: auto;
        }

        /* ========================================
           HEADER
        ======================================== */

        .top-header {
            position: relative;
            z-index: 100;
            width: 100%;
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .08);
        }

        .top-header-inner {
            width: 100%;
            max-width: 1120px;
            margin: 0 auto;
            padding: 11px 14px;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .brand {
            flex-shrink: 0;
            color: #fff;
            font-size: 18px;
            font-weight: 800;
            line-height: 1.2;
            white-space: nowrap;
        }

        .header-actions {
            min-width: 0;

            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 7px;
        }

        .notification-wrap {
            flex: 0 0 auto;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header-actions form {
            margin: 0;
            flex: 0 0 auto;
        }

        .header-chip,
        .status-btn,
        .logout-btn {
            min-height: 38px;
            border-radius: 7px;
            padding: 7px 12px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border: 0;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.1;
            white-space: nowrap;

            transition: .18s ease;
        }

        .header-chip {
            background: #fff;
            color: #111827;
        }

        .header-chip:hover {
            background: #f3f4f6;
            color: #111827;
        }

        .status-btn {
            background: #fff;
            color: var(--primary);
        }

        .status-btn:hover {
            background: #eefaf3;
        }

        .logout-btn {
            background: #facc15;
            color: #111827;
        }

        .logout-btn:hover {
            background: #eab308;
        }

        /* ========================================
           MAIN PAGE
        ======================================== */

        .page-wrap {
            width: 100%;
            max-width: 1120px;
            margin: 0 auto;
            padding: 16px 14px 40px;
        }

        /* ========================================
           HERO / WELCOME
        ======================================== */

        .hero-box {
            width: 100%;
            margin-bottom: 14px;
            padding: 22px 20px;

            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: var(--shadow);
        }

        .hero-title {
            margin-bottom: 7px;

            color: #111827;
            font-size: clamp(22px, 3vw, 28px);
            font-weight: 800;
            line-height: 1.25;

            overflow-wrap: anywhere;
        }

        .hero-sub {
            max-width: 760px;
            margin-bottom: 16px;

            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
        }

        .hero-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .hero-badge {
            padding: 7px 12px;

            border: 1px solid #cfead8;
            border-radius: 999px;
            background: var(--primary-soft);

            color: var(--primary);
            font-size: 12px;
            font-weight: 700;
            text-align: center;
        }

        /* ========================================
           STATS
        ======================================== */

        .stats-strip {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 14px;
        }

        .stat-box {
            min-width: 0;
            padding: 16px;

            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: var(--shadow);
        }

        .stat-label {
            margin-bottom: 6px;

            color: var(--muted);
            font-size: 12px;
            line-height: 1.3;
        }

        .stat-number {
            margin-bottom: 5px;

            color: #111827;
            font-size: 22px;
            font-weight: 800;
            line-height: 1.1;

            overflow-wrap: anywhere;
        }

        .stat-foot {
            color: #6b7280;
            font-size: 12px;
            line-height: 1.45;
        }

        /* ========================================
           MAIN FEATURE CARDS
        ======================================== */

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 14px;
        }

        .card-link {
            display: block;
            min-width: 0;
            height: 100%;
        }

        .dash-card {
            height: 100%;
            min-width: 0;
            padding: 18px;

            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow);

            transition:
                transform .18s ease,
                box-shadow .18s ease;
        }

        .dash-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(15, 23, 42, .08);
        }

        .card-icon {
            width: 50px;
            height: 50px;
            margin-bottom: 13px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 14px;
            background: var(--primary-soft);

            color: var(--primary);
            font-size: 23px;
        }

        .dash-card h4 {
            margin-bottom: 6px;

            color: var(--primary);
            font-size: 19px;
            font-weight: 800;
            line-height: 1.25;

            overflow-wrap: anywhere;
        }

        .dash-card p {
            margin-bottom: 14px;
            min-height: 63px;

            color: var(--muted);
            font-size: 13px;
            line-height: 1.55;
        }

        .card-btn,
        .card-btn-outline {
            min-height: 38px;
            padding: 9px 14px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border-radius: 8px;

            font-size: 13px;
            font-weight: 700;
        }

        .card-btn {
            background: var(--primary);
            color: #fff;
        }

        .card-btn-outline {
            background: #fff;
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        /* ========================================
           LOWER SECTIONS
        ======================================== */

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 14px;
        }

        .section-card {
            min-width: 0;
            height: 100%;
            padding: 16px;

            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow);
        }

        .section-head {
            margin-bottom: 12px;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .section-head h3,
        .section-head h4 {
            margin: 0;

            color: #111827;
            font-size: 18px;
            font-weight: 800;
            line-height: 1.25;
        }

        .section-note {
            margin-top: 2px;
            color: var(--muted);
            font-size: 12px;
        }

        .status-pill {
            padding: 6px 12px;

            display: inline-flex;
            align-items: center;
            gap: 6px;

            border-radius: 999px;

            font-size: 12px;
            font-weight: 800;
        }

        .status-open {
            background: #dcfce7;
            color: #166534;
        }

        .status-closed {
            background: #e5e7eb;
            color: #4b5563;
        }

        /* ========================================
           ANNOUNCEMENTS
        ======================================== */

        .announcement {
            margin-bottom: 10px;
            padding: 14px 16px;

            border: 1px solid #dbeafe;
            border-radius: 12px;
            background: #f8fbff;

            overflow-wrap: anywhere;
        }

        .announcement:last-child {
            margin-bottom: 0;
        }

        .announcement .title {
            margin-bottom: 5px;

            color: #111827;
            font-size: 14px;
            font-weight: 800;
        }

        .announcement .meta {
            margin-top: 6px;

            color: var(--muted);
            font-size: 11px;
        }

        .empty-state {
            padding: 22px 8px;

            color: var(--muted);
            font-size: 13px;
            text-align: center;
        }

        /* ========================================
           TABLET
        ======================================== */

        @media (max-width: 1100px) {
            .cards-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .stats-strip {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        /* ========================================
           MOBILE
        ======================================== */

        @media (max-width: 768px) {

            body {
                background: #f3f4f6;
            }

            .top-header-inner {
                padding: 10px 12px;

                flex-direction: column;
                align-items: stretch;
                gap: 9px;
            }

            .brand {
                width: 100%;
                font-size: 17px;
                white-space: normal;
            }

            /*
             * 4-column mobile toolbar:
             * bell | profile | status | logout
             */
            .header-actions {
                width: 100%;
                display: grid;

                grid-template-columns:
                    42px
                    minmax(0, 1fr)
                    minmax(0, 1fr)
                    minmax(0, .78fr);

                gap: 6px;
                align-items: stretch;
            }

            .notification-wrap {
                width: 42px;
                min-width: 42px;
                height: 40px;

                overflow: hidden;

                border-radius: 8px;
            }

            /*
             * Important:
             * Override Bootstrap/component button widths
             * inside notification bell only.
             */
            .notification-wrap .btn,
            .notification-wrap button,
            .notification-wrap a {
                width: 42px !important;
                min-width: 42px !important;
                max-width: 42px !important;
                height: 40px !important;

                padding: 0 !important;

                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }

            .header-actions form {
                width: 100%;
                min-width: 0;
            }

            .header-chip,
            .status-btn,
            .logout-btn {
                width: 100% !important;
                max-width: 100%;

                min-height: 40px;
                padding: 7px 5px;

                font-size: 11px;

                overflow: hidden;
                text-overflow: ellipsis;
            }

            .page-wrap {
                padding:
                    12px
                    11px
                    calc(32px + env(safe-area-inset-bottom));
            }

            .hero-box {
                margin-bottom: 11px;
                padding: 17px 15px;
                border-radius: 15px;
            }

            .hero-title {
                margin-bottom: 7px;
                font-size: 21px;
                line-height: 1.25;
            }

            .hero-sub {
                margin-bottom: 14px;
                font-size: 13px;
                line-height: 1.55;
            }

            .hero-badges {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 7px;
            }

            .hero-badge {
                width: 100%;
                padding: 7px 6px;
                font-size: 10px;

                display: flex;
                align-items: center;
                justify-content: center;

                white-space: normal;
            }

            /*
             * 2 x 2 instead of four huge vertical cards
             */
            .stats-strip {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 9px;
                margin-bottom: 11px;
            }

            .stat-box {
                min-height: 112px;
                padding: 13px 12px;
                border-radius: 13px;
            }

            .stat-label {
                margin-bottom: 5px;
                font-size: 10px;
            }

            .stat-number {
                margin-bottom: 4px;
                font-size: 18px;
            }

            .stat-foot {
                font-size: 10px;
                line-height: 1.35;
            }

            .cards-grid {
                grid-template-columns: 1fr;
                gap: 11px;
                margin-bottom: 11px;
            }

            .dash-card {
                padding: 15px;
                border-radius: 14px;
            }

            .card-icon {
                width: 44px;
                height: 44px;
                margin-bottom: 10px;

                border-radius: 12px;
                font-size: 20px;
            }

            .dash-card h4 {
                font-size: 17px;
            }

            .dash-card p {
                min-height: 0;
                margin-bottom: 12px;

                font-size: 12px;
                line-height: 1.5;
            }

            .card-btn,
            .card-btn-outline {
                min-height: 37px;
                padding: 8px 12px;
                font-size: 11px;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 11px;
            }

            .section-card {
                padding: 15px;
                border-radius: 14px;
            }

            .section-head h3,
            .section-head h4 {
                font-size: 16px;
            }

            .section-note {
                font-size: 10px;
            }

            .section-card .text-muted.small {
                font-size: 11px !important;
                line-height: 1.5;
            }

            .announcement {
                padding: 12px;
            }

            .announcement .title {
                font-size: 13px;
            }

            .announcement {
                font-size: 12px;
                line-height: 1.5;
            }
        }

        /* ========================================
           SMALL PHONES
        ======================================== */

        @media (max-width: 400px) {

            .top-header-inner {
                padding-left: 10px;
                padding-right: 10px;
            }

            .brand {
                font-size: 16px;
            }

            .header-actions {
                grid-template-columns:
                    40px
                    minmax(0, 1.05fr)
                    minmax(0, 1fr)
                    minmax(0, .75fr);

                gap: 5px;
            }

            .notification-wrap,
            .notification-wrap .btn,
            .notification-wrap button,
            .notification-wrap a {
                width: 40px !important;
                min-width: 40px !important;
                max-width: 40px !important;
            }

            .header-chip,
            .status-btn,
            .logout-btn {
                padding-left: 4px;
                padding-right: 4px;
                font-size: 10px;
            }

            .page-wrap {
                padding-left: 10px;
                padding-right: 10px;
            }

            .hero-box {
                padding: 16px 14px;
            }

            .hero-title {
                font-size: 20px;
            }

            .hero-badges {
                gap: 6px;
            }

            .hero-badge {
                font-size: 9.5px;
            }

            .stats-strip {
                gap: 8px;
            }

            .stat-box {
                min-height: 106px;
                padding: 12px 10px;
            }
        }

        /* Extremely narrow screen */
        @media (max-width: 330px) {

            .header-actions {
                grid-template-columns: 40px 1fr 1fr;
            }

            .logout-form {
                grid-column: 2 / 4;
            }

            .stats-strip {
                grid-template-columns: 1fr;
            }

            .hero-badges {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

@if(session()->pull('show_login_loader'))
    @include('components.loader')
@endif

<header class="top-header">
    <div class="top-header-inner">

        <div class="brand">
            ANI-CARE | Miller
        </div>

        <div class="header-actions">

            {{-- GLOBAL TRANSACTION NOTIFICATION BELL --}}
            <div class="notification-wrap">
                @include('components.notification-bell')
            </div>

            <a
                href="{{ route('miller.profile') }}"
                class="header-chip"
            >
                My Profile
            </a>

            <form
                method="POST"
                action="{{ route('miller.toggleOpen') }}"
            >
                @csrf

                <button
                    class="status-btn"
                    type="submit"
                >
                    {{ $user->is_open ? 'Set CLOSED' : 'Set OPEN' }}
                </button>
            </form>

            <form
                method="POST"
                action="{{ route('logout') }}"
                class="logout-form"
            >
                @csrf

                <button
                    class="logout-btn"
                    type="submit"
                >
                    Logout
                </button>
            </form>

        </div>
    </div>
</header>


<main class="page-wrap">

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-3">
            {{ session('success') }}
        </div>
    @endif


    {{-- ==========================================
         WELCOME
    =========================================== --}}
    <section class="hero-box">

        <div class="hero-title">
            Welcome,
            {{ $user->fullname ?? $user->username ?? 'Miller' }}
            👋
        </div>

        <div class="hero-sub">
            Access requests, manage schedules, review reports,
            and monitor announcements.
        </div>

        <div class="hero-badges">

            <span class="hero-badge">
                Milling Requests
            </span>

            <span class="hero-badge">
                Schedule Management
            </span>

            <span class="hero-badge">
                Reports
            </span>

            <span class="hero-badge">
                System Updates
            </span>

        </div>

    </section>


    {{-- ==========================================
         STATISTICS
    =========================================== --}}
    <section class="stats-strip">

        <div class="stat-box">

            <div class="stat-label">
                Miller Status
            </div>

            <div class="stat-number">
                {{ $user->is_open ? 'OPEN' : 'CLOSED' }}
            </div>

            <div class="stat-foot">
                Current service availability
            </div>

        </div>


        <div class="stat-box">

            <div class="stat-label">
                Requests Module
            </div>

            <div class="stat-number">
                Active
            </div>

            <div class="stat-foot">
                Manage incoming farmer requests
            </div>

        </div>


        <div class="stat-box">

            <div class="stat-label">
                Schedule Module
            </div>

            <div class="stat-number">
                Ready
            </div>

            <div class="stat-foot">
                Set approved milling schedules
            </div>

        </div>


        <div class="stat-box">

            <div class="stat-label">
                Announcements
            </div>

            <div class="stat-number">
                {{ isset($announcements) ? $announcements->count() : 0 }}
            </div>

            <div class="stat-foot">
                Latest updates from admin
            </div>

        </div>

    </section>


    {{-- ==========================================
         MAIN MODULES
    =========================================== --}}
    <section class="cards-grid">

        {{-- MILLING REQUESTS --}}
        <a
            class="card-link"
            href="{{ route('miller.requests') }}"
        >
            <div class="dash-card">

                <div class="card-icon">
                    📋
                </div>

                <h4>
                    Milling Requests
                </h4>

                <p>
                    View and manage farmer milling requests
                    and update their status.
                </p>

                <span class="card-btn">
                    View Requests
                </span>

            </div>
        </a>


        {{-- SCHEDULE --}}
        <a
            class="card-link"
            href="{{ route('miller.schedule') }}"
        >
            <div class="dash-card">

                <div class="card-icon">
                    🗓️
                </div>

                <h4>
                    Schedule Milling
                </h4>

                <p>
                    Set schedules for approved milling services
                    and track upcoming work.
                </p>

                <span class="card-btn">
                    Manage Schedule
                </span>

            </div>
        </a>


        {{-- REPORTS --}}
        <a
            class="card-link"
            href="{{ route('miller.reports') }}"
        >
            <div class="dash-card">

                <div class="card-icon">
                    📊
                </div>

                <h4>
                    Milling Reports
                </h4>

                <p>
                    Review completed milling records
                    and monitor finished transactions.
                </p>

                <span class="card-btn-outline">
                    View Reports
                </span>

            </div>
        </a>


        {{-- EARNINGS --}}
        <a
            class="card-link"
            href="{{ route('miller.earnings.index') }}"
        >
            <div class="dash-card">

                <div class="card-icon">
                    💰
                </div>

                <h4>
                    Earnings & Analytics
                </h4>

                <p>
                    Track paid milling revenue, receivables,
                    processed kilos, and monthly performance.
                </p>

                <span class="card-btn">
                    View Analytics
                </span>

            </div>
        </a>

    </section>


    {{-- ==========================================
         LOWER SECTIONS
    =========================================== --}}
    <section class="dashboard-grid">

        {{-- SERVICE STATUS --}}
        <div class="section-card">

            <div class="section-head">

                <div>

                    <h4>
                        Service Availability
                    </h4>

                    <div class="section-note">
                        Current miller account status
                    </div>

                </div>

            </div>


            @if($user->is_open)

                <span class="status-pill status-open">
                    ● OPEN
                </span>

            @else

                <span class="status-pill status-closed">
                    ● CLOSED
                </span>

            @endif


            <div class="mt-3 text-muted small">
                Use the button in the header to change
                your service availability anytime.
            </div>

        </div>


        {{-- ANNOUNCEMENTS --}}
        <div class="section-card">

            <div class="section-head">

                <div>

                    <h4>
                        Announcements
                    </h4>

                    <div class="section-note">
                        Latest updates from admin
                    </div>

                </div>

            </div>


            @if(isset($announcements) && $announcements->count())

                @foreach($announcements as $a)

                    <div class="announcement">

                        <div class="title">
                            {{ $a->title }}
                        </div>

                        <div>
                            {{ $a->message }}
                        </div>

                        <div class="meta">
                            Posted:
                            {{ $a->created_at?->format('Y-m-d H:i') }}
                        </div>

                    </div>

                @endforeach

            @else

                <div class="empty-state">
                    No announcements available.
                </div>

            @endif

        </div>

    </section>

</main>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>