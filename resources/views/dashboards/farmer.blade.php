<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>Farmer Dashboard | ANI-CARE</title>

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, viewport-fit=cover"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <style>

        :root {
            --green: #198754;
            --green-dark: #146c43;
            --green-soft: #eaf7f0;
            --page-bg: #f4f7f6;
            --text-dark: #20252b;
            --text-muted: #697078;
            --card-radius: 18px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            width: 100%;
            overflow-x: hidden;
        }

        body {
            width: 100%;
            min-height: 100vh;
            overflow-x: hidden;
            font-family: 'Segoe UI', sans-serif;
            background: var(--page-bg);
            color: var(--text-dark);
        }

        a {
            text-decoration: none;
        }


        /* =====================================================
           TOPBAR
        ====================================================== */

        .topbar {
            position: sticky;
            top: 0;
            z-index: 1000;

            width: 100%;
            background: var(--green);
            color: #fff;

            box-shadow: 0 3px 14px rgba(0,0,0,.12);
        }

        .topbar-inner {
            max-width: 1180px;
            min-height: 68px;

            margin: 0 auto;
            padding: 10px 18px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;
        }

        .brand-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;

            min-width: 0;
        }

        .brand-icon {
            width: 40px;
            height: 40px;

            flex: 0 0 40px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 12px;

            background: rgba(255,255,255,.15);

            font-size: 21px;
        }

        .brand-text {
            min-width: 0;
        }

        .brand-title {
            font-size: 19px;
            line-height: 1.15;
            font-weight: 750;
            white-space: nowrap;
        }

        .brand-role {
            margin-top: 2px;

            color: rgba(255,255,255,.78);
            font-size: 12px;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 8px;

            flex-shrink: 0;
        }

        .top-action-btn {
            min-height: 40px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            gap: 6px;

            border-radius: 10px;

            padding: 8px 13px;

            font-size: 14px;
            font-weight: 600;
        }

        /* =====================================================
           NOTIFICATION BELL
        ====================================================== */

        .notification-dropdown {
            position: relative;
        }

        .notification-btn {
            position: relative;
            width: 42px;
            min-width: 42px;
            height: 42px;
            min-height: 42px;
            padding: 0;
            border: 0;
            border-radius: 12px;
            background: rgba(255,255,255,.16);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: .2s ease;
        }

        .notification-btn:hover,
        .notification-btn:focus {
            background: #fff;
            color: var(--green);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            min-width: 20px;
            height: 20px;
            padding: 0 5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--green);
            border-radius: 999px;
            background: #dc3545;
            color: #fff;
            font-size: 10px;
            line-height: 1;
            font-weight: 800;
        }

        .notification-menu {
            width: min(340px, calc(100vw - 24px));
            padding: 0;
            overflow: hidden;
            border: 0;
            border-radius: 16px;
            box-shadow: 0 14px 38px rgba(0,0,0,.18);
        }

        .notification-head {
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            border-bottom: 1px solid #edf0f2;
            background: #fff;
        }

        .notification-head strong {
            font-size: 15px;
        }

        .notification-count-text {
            color: #dc3545;
            font-size: 12px;
            font-weight: 700;
        }

        .notification-item {
            padding: 14px 16px;
            display: flex;
            gap: 11px;
            align-items: flex-start;
            background: #fff;
        }

        .notification-item + .notification-item {
            border-top: 1px solid #f0f2f4;
        }

        .notification-item.unread {
            background: #f1fbf5;
        }

        .notification-icon {
            width: 38px;
            height: 38px;
            flex: 0 0 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: var(--green-soft);
            color: var(--green);
            font-size: 18px;
        }

        .notification-title {
            margin-bottom: 3px;
            color: #20252b;
            font-size: 13px;
            font-weight: 750;
        }

        .notification-message {
            color: var(--text-muted);
            font-size: 12px;
            line-height: 1.4;
        }

        .notification-footer {
            display: block;
            padding: 11px 16px;
            border-top: 1px solid #edf0f2;
            background: #fff;
            color: var(--green);
            text-align: center;
            font-size: 13px;
            font-weight: 700;
        }

        .notification-footer:hover {
            background: var(--green-soft);
            color: var(--green-dark);
        }

        .bell-has-notification i {
            animation: bellRing 1.6s ease-in-out infinite;
            transform-origin: top center;
        }

        @keyframes bellRing {
            0%, 65%, 100% { transform: rotate(0); }
            70% { transform: rotate(12deg); }
            75% { transform: rotate(-10deg); }
            80% { transform: rotate(8deg); }
            85% { transform: rotate(-6deg); }
            90% { transform: rotate(0); }
        }

        .logout-form {
            margin: 0;
        }


        /* =====================================================
           MAIN PAGE
        ====================================================== */

        .page-wrap {
            width: 100%;
            max-width: 1180px;

            margin: 0 auto;

            padding:
                28px
                18px
                calc(45px + env(safe-area-inset-bottom));
        }


        /* =====================================================
           WELCOME AREA
        ====================================================== */

        .welcome-box {
            margin-bottom: 26px;
        }

        .welcome-title {
            margin-bottom: 7px;

            color: #20252b;

            font-size: 31px;
            line-height: 1.2;
            font-weight: 750;
        }

        .welcome-name {
            color: var(--green);
        }

        .welcome-subtitle {
            max-width: 720px;

            color: var(--text-muted);

            font-size: 15px;
            line-height: 1.6;
        }


        /* =====================================================
           QUICK ACTION GRID
        ====================================================== */

        .dashboard-grid {
            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 18px;
        }

        .dashboard-link {
            display: block;
            height: 100%;

            color: inherit;
        }

        .dashboard-card {
            position: relative;

            height: 100%;
            min-height: 210px;

            display: flex;
            flex-direction: column;

            padding: 22px;

            background: #fff;

            border: 1px solid rgba(0,0,0,.045);
            border-radius: var(--card-radius);

            box-shadow:
                0 5px 18px rgba(0,0,0,.055);

            transition:
                transform .2s ease,
                box-shadow .2s ease,
                border-color .2s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-3px);

            border-color: rgba(25,135,84,.25);

            box-shadow:
                0 11px 28px rgba(0,0,0,.09);
        }

        .card-icon {
            width: 48px;
            height: 48px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin-bottom: 16px;

            border-radius: 14px;

            background: var(--green-soft);
            color: var(--green);

            font-size: 23px;
        }

        .dashboard-card h3 {
            margin: 0 0 7px;

            color: var(--green);

            font-size: 19px;
            font-weight: 750;
        }

        .dashboard-card p {
            flex: 1;

            margin: 0 0 18px;

            color: var(--text-muted);

            font-size: 14px;
            line-height: 1.5;
        }

        .card-button {
            width: 100%;
            min-height: 42px;

            display: flex;
            align-items: center;
            justify-content: center;

            gap: 6px;

            padding: 9px 12px;

            border-radius: 10px;

            background: var(--green);
            color: #fff;

            font-size: 14px;
            font-weight: 650;

            transition: .2s ease;
        }

        .dashboard-link:hover .card-button {
            background: var(--green-dark);
        }

        .card-button.outline {
            background: #fff;
            color: var(--green);

            border: 1px solid var(--green);
        }

        .dashboard-link:hover .card-button.outline {
            background: var(--green-soft);
        }


        /* =====================================================
           ANNOUNCEMENTS
        ====================================================== */

        .announcement-section {
            margin-top: 36px;
        }

        .section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;

            margin-bottom: 14px;
        }

        .section-title {
            margin: 0;

            font-size: 20px;
            font-weight: 750;
        }

        .section-badge {
            padding: 5px 10px;

            border-radius: 50px;

            background: var(--green-soft);
            color: var(--green);

            font-size: 12px;
            font-weight: 650;
        }

        .announcement-card {
            margin-bottom: 12px;

            background: #fff;

            border: 1px solid rgba(0,0,0,.045);
            border-radius: 15px;

            box-shadow:
                0 4px 15px rgba(0,0,0,.04);
        }

        .announcement-card .card-body {
            padding: 18px;
        }

        .announcement-title {
            margin-bottom: 5px;

            color: var(--green);

            font-size: 16px;
            font-weight: 700;
        }

        .announcement-message {
            margin-bottom: 8px;

            color: #4f555b;

            font-size: 14px;
            line-height: 1.55;
        }


        /* =====================================================
           TABLET
        ====================================================== */

        @media (max-width: 991px) {

            .dashboard-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

        }


        /* =====================================================
           MOBILE
        ====================================================== */

        @media (max-width: 600px) {

            body {
                background: #f3f6f5;
            }

            /*
            --------------------------------------
            COMPACT TOPBAR
            --------------------------------------
            */

            .topbar-inner {
                min-height: 62px;

                padding:
                    9px
                    12px;
            }

            .brand-icon {
                width: 36px;
                height: 36px;

                flex-basis: 36px;

                border-radius: 10px;

                font-size: 19px;
            }

            .brand-title {
                font-size: 16px;
            }

            .brand-role {
                font-size: 11px;
            }

            .top-action-btn {
                width: 39px;
                min-width: 39px;
                height: 39px;
                min-height: 39px;

                padding: 0;

                border-radius: 10px;

                font-size: 17px;
            }

            /*
            Hide button labels on mobile
            */

            .top-action-label {
                display: none;
            }

            .notification-btn {
                width: 39px;
                min-width: 39px;
                height: 39px;
                min-height: 39px;
                border-radius: 10px;
                font-size: 18px;
            }

            .notification-menu {
                position: fixed !important;
                top: 66px !important;
                left: 12px !important;
                right: 12px !important;
                width: auto !important;
                transform: none !important;
                margin: 0 !important;
            }


            /*
            --------------------------------------
            MAIN
            --------------------------------------
            */

            .page-wrap {
                padding:
                    20px
                    13px
                    calc(30px + env(safe-area-inset-bottom));
            }


            /*
            --------------------------------------
            WELCOME
            --------------------------------------
            */

            .welcome-box {
                margin-bottom: 19px;
            }

            .welcome-title {
                margin-bottom: 5px;

                font-size: 24px;
                line-height: 1.25;
            }

            .welcome-subtitle {
                font-size: 13px;
                line-height: 1.5;
            }


            /*
            --------------------------------------
            TWO-COLUMN MOBILE GRID
            --------------------------------------
            */

            .dashboard-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));

                gap: 11px;
            }

            .dashboard-card {
                min-height: 175px;

                padding: 15px;

                border-radius: 15px;
            }

            .card-icon {
                width: 40px;
                height: 40px;

                margin-bottom: 11px;

                border-radius: 11px;

                font-size: 19px;
            }

            .dashboard-card h3 {
                margin-bottom: 5px;

                font-size: 15px;
                line-height: 1.25;
            }

            .dashboard-card p {
                margin-bottom: 13px;

                font-size: 12px;
                line-height: 1.4;
            }

            .card-button {
                min-height: 38px;

                padding: 8px 7px;

                border-radius: 9px;

                font-size: 12px;
            }


            /*
            --------------------------------------
            ANNOUNCEMENTS
            --------------------------------------
            */

            .announcement-section {
                margin-top: 28px;
            }

            .section-title {
                font-size: 18px;
            }

            .announcement-card .card-body {
                padding: 15px;
            }

            .announcement-title {
                font-size: 15px;
            }

            .announcement-message {
                font-size: 13px;
            }

        }


        /* =====================================================
           VERY SMALL PHONE
        ====================================================== */

        @media (max-width: 370px) {

            .dashboard-grid {
                gap: 9px;
            }

            .dashboard-card {
                min-height: 170px;
                padding: 13px;
            }

            .dashboard-card h3 {
                font-size: 14px;
            }

            .dashboard-card p {
                font-size: 11.5px;
            }

            .card-button {
                font-size: 11.5px;
            }

        }

    </style>

</head>


<body>


{{-- =========================================================
    LOGIN LOADER
========================================================= --}}

@if(session()->pull('show_login_loader'))

    @include('components.loader')

@endif



{{-- =========================================================
    TOPBAR
========================================================= --}}

<header class="topbar">

    <div class="topbar-inner">


        {{-- BRAND --}}

        <div class="brand-wrapper">

            <div class="brand-icon">

                <i class="bi bi-flower1"></i>

            </div>

            <div class="brand-text">

                <div class="brand-title">
                    ANI-CARE
                </div>

                <div class="brand-role">
                    Farmer Portal
                </div>

            </div>

        </div>



        {{-- ACTIONS --}}

        <div class="top-actions">

            {{-- GLOBAL TRANSACTION NOTIFICATION BELL --}}
            @include('components.notification-bell')




            {{-- ORDERS --}}

            <a
                href="{{ route('farmer.orders.index') }}"
                class="btn btn-light top-action-btn"
                title="Orders"
            >

                <i class="bi bi-bag-check"></i>

                <span class="top-action-label">
                    Orders
                </span>

            </a>



            {{-- LOGOUT --}}

            <form
                method="POST"
                action="{{ route('logout') }}"
                class="logout-form"
            >

                @csrf

                <button
                    type="submit"
                    class="btn btn-warning top-action-btn"
                    title="Logout"
                >

                    <i class="bi bi-box-arrow-right"></i>

                    <span class="top-action-label">
                        Logout
                    </span>

                </button>

            </form>

        </div>

    </div>

</header>



{{-- =========================================================
    CONTENT
========================================================= --}}

<main class="page-wrap">


    {{-- =====================================================
        WELCOME
    ====================================================== --}}

    <section class="welcome-box">

        <h1 class="welcome-title">

            Welcome,
            <span class="welcome-name">
                {{ Auth::user()->fullname ?? 'Farmer' }}
            </span>

            👨‍🌾

        </h1>

        <p class="welcome-subtitle">

            Manage your farm profile, milling requests,
            rice products, orders, and updates from one place.

        </p>

    </section>



    {{-- =====================================================
        DASHBOARD CARDS
    ====================================================== --}}

    <section class="dashboard-grid">


        {{-- FARM PROFILE --}}

        <a
            href="{{ route('farmer.profile') }}"
            class="dashboard-link"
        >

            <article class="dashboard-card">

                <div class="card-icon">

                    <i class="bi bi-person-vcard"></i>

                </div>

                <h3>
                    Farm Profile
                </h3>

                <p>
                    View and update your farm information.
                </p>

                <div class="card-button">

                    <i class="bi bi-arrow-right-circle"></i>

                    View Profile

                </div>

            </article>

        </a>



        {{-- REQUEST MILLING --}}

        <a
            href="{{ route('farmer.milling.create') }}"
            class="dashboard-link"
        >

            <article class="dashboard-card">

                <div class="card-icon">

                    <i class="bi bi-gear-wide-connected"></i>

                </div>

                <h3>
                    Request Milling
                </h3>

                <p>
                    Submit a new milling service request.
                </p>

                <div class="card-button">

                    <i class="bi bi-plus-circle"></i>

                    Request Now

                </div>

            </article>

        </a>



        {{-- MY REQUESTS --}}

        <a
            href="{{ route('farmer.milling.index') }}"
            class="dashboard-link"
        >

            <article class="dashboard-card">

                <div class="card-icon">

                    <i class="bi bi-clipboard-check"></i>

                </div>

                <h3>
                    My Requests
                </h3>

                <p>
                    Track submitted milling requests.
                </p>

                <div class="card-button">

                    <i class="bi bi-eye"></i>

                    View Requests

                </div>

            </article>

        </a>



        {{-- POST PRODUCT --}}

        <a
            href="{{ route('farmer.products.create') }}"
            class="dashboard-link"
        >

            <article class="dashboard-card">

                <div class="card-icon">

                    <i class="bi bi-bag-plus"></i>

                </div>

                <h3>
                    Post Rice
                </h3>

                <p>
                    Add rice or palay products by sack.
                </p>

                <div class="card-button">

                    <i class="bi bi-upload"></i>

                    Post Product

                </div>

            </article>

        </a>



        {{-- MY PRODUCTS --}}

        <a
            href="{{ route('farmer.products.index') }}"
            class="dashboard-link"
        >

            <article class="dashboard-card">

                <div class="card-icon">

                    <i class="bi bi-basket2"></i>

                </div>

                <h3>
                    My Products
                </h3>

                <p>
                    Manage your posted rice products.
                </p>

                <div class="card-button outline">

                    <i class="bi bi-box-seam"></i>

                    View Products

                </div>

            </article>

        </a>



        {{-- ORDERS --}}

        <a
            href="{{ route('farmer.orders.index') }}"
            class="dashboard-link"
        >

            <article class="dashboard-card">

                <div class="card-icon">

                    <i class="bi bi-cart-check"></i>

                </div>

                <h3>
                    Orders
                </h3>

                <p>
                    Review and manage customer orders.
                </p>

                <div class="card-button">

                    <i class="bi bi-bag-check"></i>

                    Manage Orders

                </div>

            </article>

        </a>


        {{-- EARNINGS & ANALYTICS --}}

        <a
            href="{{ route('farmer.earnings.index') }}"
            class="dashboard-link"
        >

            <article class="dashboard-card">

                <div class="card-icon">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>

                <h3>
                    Earnings & Analytics
                </h3>

                <p>
                    Monitor sales revenue, receivables,
                    milling expenses, and product performance.
                </p>

                <div class="card-button">
                    <i class="bi bi-bar-chart-line"></i>
                    View Analytics
                </div>

            </article>

        </a>


    </section>



    {{-- =====================================================
        ANNOUNCEMENTS
    ====================================================== --}}

    @if(isset($announcements) && $announcements->count())

        <section class="announcement-section">


            <div class="section-heading">

                <h2 class="section-title">

                    <i class="bi bi-megaphone-fill text-success"></i>

                    Announcements

                </h2>


                <span class="section-badge">

                    {{ $announcements->count() }}

                    Update{{ $announcements->count() === 1 ? '' : 's' }}

                </span>

            </div>


            @foreach($announcements as $a)

                <article class="announcement-card">

                    <div class="card-body">

                        <div class="announcement-title">

                            {{ $a->title }}

                        </div>


                        <p class="announcement-message">

                            {{ $a->message }}

                        </p>


                        <small class="text-muted">

                            <i class="bi bi-clock"></i>

                            {{ $a->created_at?->format('F d, Y • h:i A') }}

                        </small>

                    </div>

                </article>

            @endforeach

        </section>

    @endif


</main>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
</script>


</body>
</html>