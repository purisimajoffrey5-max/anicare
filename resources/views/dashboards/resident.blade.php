<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Resident Dashboard | ANI-CARE</title>

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
            --ani-green: #198754;
            --ani-green-dark: #146c43;
            --ani-green-soft: #eaf7f0;
            --ani-bg: #f4f7f6;
            --ani-card: #ffffff;
            --ani-text: #1f2933;
            --ani-muted: #6b7280;
            --ani-border: #e7ece9;
            --ani-warning: #ffc107;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", sans-serif;
            background: var(--ani-bg);
            color: var(--ani-text);
            overflow-x: hidden;
        }

        a {
            color: inherit;
        }

        /* =====================================================
           TOPBAR
        ====================================================== */

        .resident-topbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            background: var(--ani-green);
            color: #fff;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .12);
        }

        .resident-topbar-inner {
            width: min(1180px, 100%);
            margin: 0 auto;
            padding: 12px 16px;
        }

        .resident-topbar-main {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .resident-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .resident-brand-icon {
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            border-radius: 13px;
            background: rgba(255,255,255,.16);
            display: grid;
            place-items: center;
            font-size: 21px;
        }

        .resident-brand-text {
            min-width: 0;
        }

        .resident-brand-title {
            font-size: 20px;
            line-height: 1.1;
            font-weight: 800;
            white-space: nowrap;
        }

        .resident-brand-role {
            margin-top: 2px;
            font-size: 11px;
            color: rgba(255,255,255,.78);
        }

        .resident-top-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 0 0 auto;
        }

        .resident-logout {
            width: 42px;
            height: 42px;
            border: 0;
            border-radius: 12px;
            background: var(--ani-warning);
            color: #212529;
            display: inline-grid;
            place-items: center;
            font-size: 18px;
            transition: .2s ease;
        }

        .resident-logout:hover {
            background: #e0a800;
            transform: translateY(-1px);
        }

        /* =====================================================
           NAVIGATION
        ====================================================== */

        .resident-nav {
            margin-top: 11px;
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 2px;
            scrollbar-width: none;
        }

        .resident-nav::-webkit-scrollbar {
            display: none;
        }

        .resident-nav-link {
            flex: 0 0 auto;
            min-height: 38px;
            padding: 9px 14px;
            border-radius: 11px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            background: rgba(255,255,255,.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            white-space: nowrap;
            transition: .2s ease;
        }

        .resident-nav-link:hover,
        .resident-nav-link.active {
            background: #fff;
            color: var(--ani-green);
        }

        /* =====================================================
           PAGE
        ====================================================== */

        .resident-page {
            width: min(1180px, 100%);
            margin: 0 auto;
            padding: 22px 16px 60px;
        }

        /* =====================================================
           WELCOME
        ====================================================== */

        .welcome-panel {
            position: relative;
            overflow: hidden;
            padding: 24px;
            margin-bottom: 18px;
            border-radius: 20px;
            color: #fff;
            background:
                linear-gradient(
                    135deg,
                    #198754 0%,
                    #157347 60%,
                    #12613d 100%
                );
            box-shadow: 0 10px 28px rgba(25,135,84,.16);
        }

        .welcome-panel::after {
            content: "";
            position: absolute;
            right: -55px;
            top: -65px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(255,255,255,.08);
        }

        .welcome-kicker {
            position: relative;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 6px;
            font-size: 12px;
            font-weight: 700;
            color: rgba(255,255,255,.82);
        }

        .welcome-title {
            position: relative;
            z-index: 1;
            margin: 0;
            font-size: clamp(24px, 4vw, 34px);
            font-weight: 800;
            line-height: 1.15;
            overflow-wrap: anywhere;
        }

        .welcome-sub {
            position: relative;
            z-index: 1;
            max-width: 720px;
            margin-top: 8px;
            margin-bottom: 0;
            font-size: 14px;
            line-height: 1.55;
            color: rgba(255,255,255,.86);
        }

        /* =====================================================
           STATS
        ====================================================== */

        .resident-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }

        .resident-stat {
            min-width: 0;
            padding: 17px;
            border: 1px solid var(--ani-border);
            border-radius: 17px;
            background: var(--ani-card);
            box-shadow: 0 5px 18px rgba(15, 23, 42, .045);
        }

        .resident-stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 8px;
        }

        .resident-stat-label {
            min-width: 0;
            color: var(--ani-muted);
            font-size: 12px;
            font-weight: 650;
            line-height: 1.25;
        }

        .resident-stat-icon {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            background: var(--ani-green-soft);
            color: var(--ani-green);
            font-size: 15px;
        }

        .resident-stat-number {
            font-size: 27px;
            font-weight: 800;
            line-height: 1;
            color: var(--ani-green);
        }

        .resident-stat-foot {
            margin-top: 6px;
            color: #9298a0;
            font-size: 11px;
            line-height: 1.3;
        }

        /* =====================================================
           CONTENT GRID
        ====================================================== */

        .dashboard-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.55fr) minmax(300px, .85fr);
            gap: 16px;
            align-items: start;
        }

        .dashboard-stack {
            display: grid;
            gap: 16px;
        }

        .section-card {
            min-width: 0;
            padding: 18px;
            border: 1px solid var(--ani-border);
            border-radius: 18px;
            background: var(--ani-card);
            box-shadow: 0 5px 18px rgba(15,23,42,.045);
        }

        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }

        .section-heading-left {
            min-width: 0;
        }

        .section-title {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 17px;
            font-weight: 800;
            line-height: 1.25;
        }

        .section-title-icon {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            background: var(--ani-green-soft);
            color: var(--ani-green);
        }

        .section-note {
            margin-top: 3px;
            color: var(--ani-muted);
            font-size: 11px;
        }

        .section-action {
            flex: 0 0 auto;
            min-height: 34px;
            padding: 7px 11px;
            border: 1px solid #a9d9bd;
            border-radius: 9px;
            text-decoration: none;
            color: var(--ani-green);
            background: #fff;
            font-size: 11px;
            font-weight: 800;
        }

        /* =====================================================
           ANNOUNCEMENTS
        ====================================================== */

        .announcement-list {
            display: grid;
            gap: 10px;
        }

        .announcement-item {
            padding: 14px;
            border: 1px solid #edf1ef;
            border-radius: 14px;
            background: #fbfdfc;
        }

        .announcement-item-title {
            margin: 0 0 5px;
            font-size: 14px;
            font-weight: 800;
            color: var(--ani-green-dark);
        }

        .announcement-message {
            margin: 0 0 8px;
            color: #4b5563;
            font-size: 12.5px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .announcement-time {
            color: #9aa0a6;
            font-size: 10.5px;
        }

        .empty-state {
            padding: 26px 14px;
            text-align: center;
            color: var(--ani-muted);
        }

        .empty-state-icon {
            width: 52px;
            height: 52px;
            margin: 0 auto 10px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: var(--ani-green-soft);
            color: var(--ani-green);
            font-size: 24px;
        }

        .empty-state-title {
            font-weight: 800;
            color: #4b5563;
            font-size: 13px;
        }

        .empty-state-text {
            margin-top: 3px;
            font-size: 11px;
        }

        /* =====================================================
           MILLERS
        ====================================================== */

        .miller-list {
            display: grid;
            gap: 7px;
        }

        .miller-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            min-width: 0;
            padding: 10px 11px;
            border: 1px solid #edf1ef;
            border-radius: 12px;
            background: #fbfdfc;
        }

        .miller-person {
            display: flex;
            align-items: center;
            gap: 9px;
            min-width: 0;
        }

        .miller-avatar {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            border-radius: 10px;
            background: var(--ani-green-soft);
            color: var(--ani-green);
            display: grid;
            place-items: center;
        }

        .miller-info {
            min-width: 0;
        }

        .miller-name {
            font-size: 12.5px;
            font-weight: 800;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .miller-user {
            margin-top: 1px;
            color: var(--ani-muted);
            font-size: 10.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .status-pill {
            flex: 0 0 auto;
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 9.5px;
            font-weight: 800;
        }

        .status-open {
            color: #0f5132;
            background: #d1e7dd;
        }

        .status-closed {
            color: #495057;
            background: #e9ecef;
        }

        /* =====================================================
           PRODUCTS
        ====================================================== */

        .product-list {
            display: grid;
            gap: 10px;
        }

        .product-item {
            display: grid;
            grid-template-columns: 62px minmax(0, 1fr) auto;
            gap: 11px;
            align-items: center;
            padding: 10px;
            border: 1px solid #edf1ef;
            border-radius: 14px;
            background: #fbfdfc;
            text-decoration: none;
            transition: .18s ease;
        }

        .product-item:hover {
            border-color: #b9ddc7;
            transform: translateY(-1px);
        }

        .product-photo {
            width: 62px;
            height: 62px;
            border-radius: 12px;
            object-fit: cover;
            border: 1px solid #e7ece9;
            background: #f0f2f1;
        }

        .product-photo-empty {
            width: 62px;
            height: 62px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: var(--ani-green-soft);
            color: var(--ani-green);
            font-size: 22px;
        }

        .product-main {
            min-width: 0;
        }

        .product-name {
            font-size: 13px;
            font-weight: 800;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-meta {
            margin-top: 3px;
            color: var(--ani-muted);
            font-size: 10.5px;
            line-height: 1.4;
        }

        .product-price {
            padding-left: 6px;
            text-align: right;
        }

        .product-price-value {
            color: var(--ani-green);
            font-weight: 800;
            font-size: 14px;
            white-space: nowrap;
        }

        .product-price-unit {
            color: #979da4;
            font-size: 9.5px;
        }

        /* =====================================================
           RESPONSIVE
        ====================================================== */

        @media (max-width: 991.98px) {
            .dashboard-layout {
                grid-template-columns: 1fr;
            }

            .resident-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .right-dashboard-column {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 16px;
            }
        }

        @media (max-width: 767.98px) {
            .resident-topbar-inner {
                padding: 10px 12px;
            }

            .resident-brand-icon {
                width: 38px;
                height: 38px;
                flex-basis: 38px;
                font-size: 18px;
            }

            .resident-brand-title {
                font-size: 17px;
            }

            .resident-brand-role {
                display: none;
            }

            .resident-page {
                padding: 16px 11px 50px;
            }

            .welcome-panel {
                padding: 19px 17px;
                border-radius: 17px;
                margin-bottom: 14px;
            }

            .welcome-title {
                font-size: 23px;
            }

            .welcome-sub {
                font-size: 12.5px;
                line-height: 1.45;
            }

            .resident-stats {
                gap: 9px;
                margin-bottom: 14px;
            }

            .resident-stat {
                padding: 13px;
                border-radius: 14px;
            }

            .resident-stat-icon {
                width: 29px;
                height: 29px;
                flex-basis: 29px;
                font-size: 13px;
            }

            .resident-stat-label {
                font-size: 10.5px;
            }

            .resident-stat-number {
                font-size: 23px;
            }

            .resident-stat-foot {
                font-size: 9.5px;
            }

            .dashboard-layout,
            .dashboard-stack {
                gap: 12px;
            }

            .section-card {
                padding: 14px;
                border-radius: 16px;
            }

            .section-title {
                font-size: 15px;
            }

            .section-title-icon {
                width: 31px;
                height: 31px;
                flex-basis: 31px;
            }

            .right-dashboard-column {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .miller-item {
                padding: 9px 10px;
            }
        }

        @media (max-width: 430px) {
            .resident-topbar-main {
                gap: 8px;
            }

            .resident-brand-title {
                font-size: 16px;
            }

            .resident-nav {
                margin-top: 9px;
            }

            .resident-nav-link {
                padding: 8px 11px;
                font-size: 11.5px;
            }

            .resident-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .resident-stat {
                min-height: 108px;
            }

            .section-heading {
                align-items: flex-start;
            }

            .product-item {
                grid-template-columns: 54px minmax(0, 1fr);
            }

            .product-photo,
            .product-photo-empty {
                width: 54px;
                height: 54px;
            }

            .product-price {
                grid-column: 2;
                padding-left: 0;
                text-align: left;
                display: flex;
                align-items: baseline;
                gap: 5px;
                margin-top: -4px;
            }

            .product-price-value {
                font-size: 13px;
            }
        }

        @media (max-width: 350px) {
            .resident-brand-title {
                font-size: 14px;
            }

            .resident-brand-icon {
                display: none;
            }

            .resident-stat {
                padding: 11px;
            }

            .resident-stat-foot {
                display: none;
            }
        }
    </style>
</head>

<body>

@if(session()->pull('show_login_loader'))
    @include('components.loader')
@endif

@php
    $residentName =
        $user->fullname
        ?? $user->username
        ?? 'Resident';
@endphp

{{-- =========================================================
     TOPBAR
========================================================= --}}
<header class="resident-topbar">
    <div class="resident-topbar-inner">

        <div class="resident-topbar-main">

            <div class="resident-brand">
                <div class="resident-brand-icon">
                    <i class="bi bi-flower1"></i>
                </div>

                <div class="resident-brand-text">
                    <div class="resident-brand-title">
                        ANI-CARE
                    </div>

                    <div class="resident-brand-role">
                        Resident Portal
                    </div>
                </div>
            </div>

            <div class="resident-top-actions">

                {{-- GLOBAL NOTIFICATION BELL --}}
                @include('components.notification-bell')

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="m-0"
                >
                    @csrf

                    <button
                        type="submit"
                        class="resident-logout"
                        title="Logout"
                        aria-label="Logout"
                    >
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>

            </div>

        </div>

        {{-- MOBILE-FRIENDLY HORIZONTAL NAV --}}
        <nav class="resident-nav" aria-label="Resident navigation">

            <a
                href="{{ route('resident.marketplace') }}"
                class="resident-nav-link"
            >
                <i class="bi bi-shop"></i>
                Marketplace
            </a>

            <a
                href="{{ route('resident.orders.index') }}"
                class="resident-nav-link"
            >
                <i class="bi bi-bag-check"></i>
                My Orders
            </a>

            <a
                href="{{ route('resident.profile') }}"
                class="resident-nav-link"
            >
                <i class="bi bi-person-circle"></i>
                My Profile
            </a>

        </nav>

    </div>
</header>

{{-- =========================================================
     PAGE
========================================================= --}}
<main class="resident-page">

    {{-- SUCCESS / ERROR --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-4">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- WELCOME --}}
    <section class="welcome-panel">

        <div class="welcome-kicker">
            <i class="bi bi-house-heart-fill"></i>
            Resident Dashboard
        </div>

        <h1 class="welcome-title">
            Welcome, {{ $residentName }}!
        </h1>

        <p class="welcome-sub">
            Browse available rice and palay, monitor your orders,
            check miller availability, read announcements, and receive
            transaction updates from your notification bell.
        </p>

    </section>

    {{-- =====================================================
         STATS
    ====================================================== --}}
    <section class="resident-stats">

        <article class="resident-stat">
            <div class="resident-stat-top">
                <div class="resident-stat-label">
                    Open Millers
                </div>

                <div class="resident-stat-icon">
                    <i class="bi bi-gear-fill"></i>
                </div>
            </div>

            <div class="resident-stat-number">
                {{ $openMillersCount ?? 0 }}
            </div>

            <div class="resident-stat-foot">
                Available today
            </div>
        </article>

        <article class="resident-stat">
            <div class="resident-stat-top">
                <div class="resident-stat-label">
                    Marketplace
                </div>

                <div class="resident-stat-icon">
                    <i class="bi bi-shop"></i>
                </div>
            </div>

            <div class="resident-stat-number">
                {{ $marketplaceCount ?? 0 }}
            </div>

            <div class="resident-stat-foot">
                Active listings
            </div>
        </article>

        <article class="resident-stat">
            <div class="resident-stat-top">
                <div class="resident-stat-label">
                    My Orders
                </div>

                <div class="resident-stat-icon">
                    <i class="bi bi-bag-check-fill"></i>
                </div>
            </div>

            <div class="resident-stat-number">
                {{ $myOrdersCount ?? 0 }}
            </div>

            <div class="resident-stat-foot">
                Purchase transactions
            </div>
        </article>

        <article class="resident-stat">
            <div class="resident-stat-top">
                <div class="resident-stat-label">
                    Announcements
                </div>

                <div class="resident-stat-icon">
                    <i class="bi bi-megaphone-fill"></i>
                </div>
            </div>

            <div class="resident-stat-number">
                {{ isset($announcements) ? $announcements->count() : 0 }}
            </div>

            <div class="resident-stat-foot">
                Latest LGU updates
            </div>
        </article>

    </section>

    {{-- =====================================================
         MAIN DASHBOARD
    ====================================================== --}}
    <section class="dashboard-layout">

        {{-- LEFT --}}
        <div class="dashboard-stack">

            {{-- ANNOUNCEMENTS --}}
            <article class="section-card">

                <div class="section-heading">

                    <div class="section-heading-left">
                        <h2 class="section-title">
                            <span class="section-title-icon">
                                <i class="bi bi-megaphone-fill"></i>
                            </span>

                            Announcements
                        </h2>

                        <div class="section-note">
                            Latest LGU and ANI-CARE updates
                        </div>
                    </div>

                </div>

                @if(isset($announcements) && $announcements->count())

                    <div class="announcement-list">

                        @foreach($announcements as $a)
                            <div class="announcement-item">

                                <h3 class="announcement-item-title">
                                    {{ $a->title }}
                                </h3>

                                <p class="announcement-message">
                                    {{ $a->message }}
                                </p>

                                <div class="announcement-time">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $a->created_at?->format('M d, Y • h:i A') }}
                                </div>

                            </div>
                        @endforeach

                    </div>

                @else

                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="bi bi-megaphone"></i>
                        </div>

                        <div class="empty-state-title">
                            No announcements yet
                        </div>

                        <div class="empty-state-text">
                            New LGU updates will appear here.
                        </div>
                    </div>

                @endif

            </article>

            {{-- LATEST FARMER POSTS --}}
            <article class="section-card">

                <div class="section-heading">

                    <div class="section-heading-left">
                        <h2 class="section-title">
                            <span class="section-title-icon">
                                <i class="bi bi-basket2-fill"></i>
                            </span>

                            Latest Farmer Posts
                        </h2>

                        <div class="section-note">
                            New rice and palay marketplace listings
                        </div>
                    </div>

                    <a
                        href="{{ route('resident.marketplace') }}"
                        class="section-action"
                    >
                        Browse
                    </a>

                </div>

                <div class="product-list">

                    @forelse(($products ?? []) as $p)

                        @php
                            $productPhoto =
                                !empty($p->photo_path)
                                    ? asset('storage/'.$p->photo_path)
                                    : null;
                        @endphp

                        <a
                            href="{{ route('resident.product.show', $p->id) }}"
                            class="product-item"
                        >

                            @if($productPhoto)
                                <img
                                    src="{{ $productPhoto }}"
                                    class="product-photo"
                                    alt="{{ $p->name }}"
                                >
                            @else
                                <div class="product-photo-empty">
                                    <i class="bi bi-basket2"></i>
                                </div>
                            @endif

                            <div class="product-main">

                                <div class="product-name">
                                    {{ $p->name }}
                                </div>

                                <div class="product-meta">
                                    By
                                    {{ $p->user->fullname
                                        ?? $p->user->username
                                        ?? 'Unknown Farmer' }}
                                    <br>
                                    Stock:
                                    {{ number_format((float)($p->kilos_available ?? 0)) }} kg
                                </div>

                            </div>

                            <div class="product-price">

                                <div class="product-price-value">
                                    ₱{{ number_format((float)($p->price_per_kg ?? 0), 2) }}
                                </div>

                                <div class="product-price-unit">
                                    per kg
                                </div>

                            </div>

                        </a>

                    @empty

                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-basket2"></i>
                            </div>

                            <div class="empty-state-title">
                                No marketplace posts yet
                            </div>

                            <div class="empty-state-text">
                                Farmer listings will appear here.
                            </div>
                        </div>

                    @endforelse

                </div>

            </article>

        </div>

        {{-- RIGHT --}}
        <div class="right-dashboard-column">

            {{-- MILLERS --}}
            <article class="section-card">

                <div class="section-heading">

                    <div class="section-heading-left">
                        <h2 class="section-title">
                            <span class="section-title-icon">
                                <i class="bi bi-gear-wide-connected"></i>
                            </span>

                            Millers Status
                        </h2>

                        <div class="section-note">
                            Live milling availability
                        </div>
                    </div>

                </div>

                <div class="miller-list">

                    @forelse(($millers ?? []) as $m)

                        <div class="miller-item">

                            <div class="miller-person">

                                <div class="miller-avatar">
                                    <i class="bi bi-gear-fill"></i>
                                </div>

                                <div class="miller-info">

                                    <div class="miller-name">
                                        {{ $m->fullname ?? $m->username }}
                                    </div>

                                    <div class="miller-user">
                                        {{ '@'.$m->username }}
                                    </div>

                                </div>

                            </div>

                            @if($m->is_open)
                                <span class="status-pill status-open">
                                    OPEN
                                </span>
                            @else
                                <span class="status-pill status-closed">
                                    CLOSED
                                </span>
                            @endif

                        </div>

                    @empty

                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-gear"></i>
                            </div>

                            <div class="empty-state-title">
                                No millers available
                            </div>
                        </div>

                    @endforelse

                </div>

            </article>

        </div>

    </section>

</main>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>