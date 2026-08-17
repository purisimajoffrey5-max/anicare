<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resident Orders | Farmer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            min-height: 100%;
            font-family: "Segoe UI", sans-serif;
        }

        body {
            background: #f4f7f6;
            color: #20252b;
        }

        .topbar {
            background: #198754;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 3px 14px rgba(0,0,0,.10);
        }

        .topbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            min-height: 72px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(255,255,255,.16);
            display: grid;
            place-items: center;
            font-size: 22px;
            flex: 0 0 auto;
        }

        .brand-title {
            font-size: 22px;
            line-height: 1.1;
            font-weight: 800;
        }

        .brand-subtitle {
            font-size: 12px;
            opacity: .82;
            margin-top: 2px;
        }

        .top-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            flex: 0 0 auto;
        }

        .top-actions .btn {
            border-radius: 10px;
            white-space: nowrap;
        }

        .page-wrap {
            width: min(1200px, 100%);
            margin: 0 auto;
            padding: 24px 16px 70px;
        }

        .page-heading {
            margin-bottom: 18px;
        }

        .page-heading h1 {
            color: #198754;
            font-weight: 800;
            font-size: clamp(25px, 4vw, 34px);
            margin: 0;
        }

        .page-heading p {
            color: #6c757d;
            margin: 4px 0 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }

        .stat-card {
            background: #fff;
            border: 1px solid #e8ecea;
            border-radius: 18px;
            padding: 18px;
            box-shadow: 0 6px 20px rgba(15,23,42,.04);
            min-width: 0;
        }

        .stat-label {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 30px;
            line-height: 1;
            font-weight: 800;
        }

        .orders-shell {
            background: #fff;
            border: 1px solid #e8ecea;
            border-radius: 18px;
            box-shadow: 0 6px 24px rgba(15,23,42,.05);
            overflow: hidden;
        }

        .desktop-orders {
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .desktop-orders table {
            min-width: 1080px;
            margin: 0;
        }

        .desktop-orders thead th {
            background: #f8faf9;
            border-bottom: 1px solid #e9ecef;
            padding: 15px 13px;
            font-size: 13px;
            color: #495057;
            white-space: nowrap;
        }

        .desktop-orders tbody td {
            padding: 15px 13px;
            vertical-align: middle;
        }

        .product-row {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 220px;
        }

        .product-img {
            width: 68px;
            height: 56px;
            object-fit: cover;
            border-radius: 12px;
            background: #eef2f0;
            flex: 0 0 auto;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .2px;
            white-space: nowrap;
        }

        .status-pending {
            background: #fff3cd;
            color: #755b00;
        }

        .status-approved {
            background: #dbeafe;
            color: #174ea6;
        }

        .status-completed {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-cancelled {
            background: #eceff1;
            color: #495057;
        }

        .action-stack {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 6px;
        }

        .action-stack .btn {
            border-radius: 10px;
            white-space: nowrap;
        }

        .approved-note {
            display: inline-flex;
            gap: 6px;
            align-items: center;
            color: #0d6efd;
            font-size: 12px;
            font-weight: 600;
            padding-top: 4px;
        }

        /* MOBILE ORDER CARDS */
        .mobile-orders {
            display: none;
        }

        .mobile-order-card {
            background: #fff;
            border: 1px solid #e7ece9;
            border-radius: 18px;
            padding: 15px;
            margin-bottom: 12px;
            box-shadow: 0 4px 16px rgba(15,23,42,.04);
        }

        .mobile-order-top {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .mobile-product-img {
            width: 74px;
            height: 68px;
            object-fit: cover;
            border-radius: 13px;
            background: #eef2f0;
            flex: 0 0 auto;
        }

        .mobile-order-main {
            flex: 1;
            min-width: 0;
        }

        .mobile-order-id {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 2px;
        }

        .mobile-product-name {
            font-weight: 800;
            font-size: 17px;
            line-height: 1.2;
            overflow-wrap: anywhere;
        }

        .mobile-product-type {
            color: #6c757d;
            font-size: 12px;
            margin-top: 2px;
            text-transform: uppercase;
        }

        .mobile-status-row {
            margin-top: 8px;
        }

        .resident-block {
            margin-top: 14px;
            padding: 12px;
            border-radius: 13px;
            background: #f8faf9;
        }

        .resident-name {
            font-weight: 750;
            overflow-wrap: anywhere;
        }

        .resident-meta {
            color: #6c757d;
            font-size: 12px;
            overflow-wrap: anywhere;
        }

        .mobile-info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            margin-top: 12px;
        }

        .mobile-info {
            background: #fbfcfc;
            border: 1px solid #edf0ef;
            border-radius: 12px;
            padding: 10px;
            min-width: 0;
        }

        .mobile-info-label {
            color: #6c757d;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .mobile-info-value {
            font-weight: 750;
            font-size: 14px;
            overflow-wrap: anywhere;
        }

        .mobile-action-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
            margin-top: 14px;
        }

        .mobile-action-grid .btn,
        .mobile-action-grid form,
        .mobile-action-grid form .btn {
            width: 100%;
        }

        .mobile-action-grid .btn {
            min-height: 44px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }


        .workflow-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 8px;
        }

        .workflow-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
        }

        .payment-paid {
            background: #d1e7dd;
            color: #0f5132;
        }

        .payment-unpaid {
            background: #fff3cd;
            color: #755b00;
        }

        .delivery-preparing {
            background: #eef2f5;
            color: #495057;
        }

        .delivery-out {
            background: #dbeafe;
            color: #174ea6;
        }

        .delivery-done {
            background: #d1e7dd;
            color: #0f5132;
        }

        .delivery-workflow-box {
            margin-top: 12px;
            padding: 12px;
            border: 1px solid #dfe8e3;
            border-radius: 14px;
            background: #fbfdfc;
        }

        .delivery-workflow-title {
            font-weight: 800;
            margin-bottom: 8px;
        }

        .proof-form {
            margin-top: 10px;
            padding: 11px;
            background: #f4f8f6;
            border: 1px dashed #9fcab1;
            border-radius: 12px;
        }

        .proof-form input[type="file"] {
            font-size: 12px;
        }

        .proof-photo {
            width: 100%;
            max-height: 240px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #e2e8e5;
            margin-top: 10px;
        }

        .full-row {
            grid-column: 1 / -1;
        }

        .buyer-confirm-box {
            margin-top: 12px;
            background: #eef6ff;
            color: #174ea6;
            border: 1px solid #dbeafe;
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 12px;
            line-height: 1.45;
        }


        .expected-delivery-box {
            margin-top: 10px;
            padding: 10px 12px;
            border: 1px solid #cfe2ff;
            border-radius: 12px;
            background: #eef6ff;
            color: #174ea6;
            font-size: 12px;
            line-height: 1.45;
        }

        .expected-delivery-box strong {
            display: block;
            margin-top: 2px;
            font-size: 13px;
        }

        .expected-delivery-badge {
            background: #e7f1ff;
            color: #0d6efd;
        }

        .schedule-form-card {
            border: 1px solid #dfe8e3;
            border-radius: 14px;
            background: #fbfdfc;
            padding: 14px;
        }

        .schedule-help {
            color: #6c757d;
            font-size: 12px;
            line-height: 1.45;
        }

        .pagination-wrap {
            padding: 14px 16px;
            border-top: 1px solid #eef1ef;
        }

        .modal-info-box {
            border: 1px solid #e8ecea;
            background: #fafcfb;
            border-radius: 14px;
            padding: 14px;
            height: 100%;
        }

        .meta-label {
            color: #6c757d;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .meta-value {
            font-weight: 650;
            overflow-wrap: anywhere;
        }

        @media (max-width: 900px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .topbar-inner {
                min-height: auto;
                padding: 10px 12px;
                align-items: center;
            }

            .brand-title {
                font-size: 18px;
            }

            .brand-subtitle {
                display: none;
            }

            .brand-icon {
                width: 38px;
                height: 38px;
            }

            .top-actions .btn {
                padding: 8px 10px;
                font-size: 0;
                width: 40px;
                height: 40px;
            }

            .top-actions .btn i {
                font-size: 17px;
            }

            .page-wrap {
                padding: 18px 12px 70px;
            }

            .page-heading {
                margin-bottom: 14px;
            }

            .page-heading h1 {
                font-size: 27px;
            }

            .stats-grid {
                gap: 10px;
            }

            .stat-card {
                padding: 14px;
                border-radius: 15px;
            }

            .stat-label {
                font-size: 12px;
            }

            .stat-value {
                font-size: 25px;
            }

            .orders-shell {
                background: transparent;
                border: 0;
                border-radius: 0;
                box-shadow: none;
                overflow: visible;
            }

            .desktop-orders {
                display: none;
            }

            .mobile-orders {
                display: block;
            }

            .pagination-wrap {
                background: #fff;
                border: 1px solid #e8ecea;
                border-radius: 14px;
                margin-top: 12px;
            }
        }

        @media (max-width: 767.98px) {
            .modal-dialog {
                margin: 12px;
            }

            .modal-content {
                border-radius: 18px;
                max-height: calc(100dvh - 24px);
            }

            .modal-header,
            .modal-body,
            .modal-footer {
                padding-left: 16px;
                padding-right: 16px;
            }

            .modal-footer {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }

            .modal-footer form,
            .modal-footer form .btn,
            .modal-footer > .btn {
                width: 100%;
                margin: 0;
            }

            .modal-footer > .btn:last-child {
                grid-column: 1 / -1;
            }
        }


        @media (max-width: 380px) {
            .mobile-info-grid,
            .mobile-action-grid {
                grid-template-columns: 1fr;
            }

            .mobile-product-img {
                width: 64px;
                height: 62px;
            }
        }
    </style>
</head>
<body>

<header class="topbar">
    <div class="topbar-inner">
        <div class="brand">
            <div class="brand-icon">
                <i class="bi bi-flower1"></i>
            </div>

            <div>
                <div class="brand-title">ANI-CARE | Farmer</div>
                <div class="brand-subtitle">Resident Order Management</div>
            </div>
        </div>

        <div class="top-actions">
            <a href="{{ route('farmer.dashboard') }}"
               class="btn btn-light btn-sm"
               title="Back to Dashboard">
                <i class="bi bi-arrow-left"></i>
                <span class="d-none d-md-inline ms-1">Back</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit"
                        class="btn btn-warning btn-sm"
                        title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="d-none d-md-inline ms-1">Logout</span>
                </button>
            </form>
        </div>
    </div>
</header>

<main class="page-wrap">

    <section class="page-heading">
        <h1>Orders from Residents</h1>
        <p>Review and approve orders placed on your rice and palay products.</p>
    </section>

    @if(session('success'))
        <div class="alert alert-success rounded-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger rounded-4">
            {{ $errors->first() }}
        </div>
    @endif

    @php
        $currentOrders = $orders->getCollection();
        $pendingCount = $currentOrders->where('status', 'pending')->count();
        $approvedCount = $currentOrders->where('status', 'approved')->count();
        $completedCount = $currentOrders->where('status', 'completed')->count();
    @endphp

    <section class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Orders</div>
            <div class="stat-value">{{ $orders->total() }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Pending</div>
            <div class="stat-value text-warning">{{ $pendingCount }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Approved</div>
            <div class="stat-value text-primary">{{ $approvedCount }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Completed</div>
            <div class="stat-value text-success">{{ $completedCount }}</div>
        </div>
    </section>

    <section class="orders-shell">

        {{-- DESKTOP / TABLET TABLE --}}
        <div class="desktop-orders">
            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Resident</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>When</th>
                    <th class="text-end">Action</th>
                </tr>
                </thead>

                <tbody>
                @forelse($orders as $o)
                    @php
                        $img = !empty($o->product?->photo_path)
                            ? asset('storage/'.$o->product->photo_path)
                            : null;

                        $st = strtolower($o->status ?? 'pending');
                        $deliverySt = strtolower($o->delivery_status ?? 'pending');
                        $paymentSt = strtolower($o->payment_status ?? 'unpaid');

                        $expectedDelivery = !empty($o->expected_delivery_at)
                            ? \Carbon\Carbon::parse($o->expected_delivery_at, 'UTC')->timezone('Asia/Manila')
                            : null;

                        $displayTotal = (float) (
                            $o->grand_total
                            ?? $o->total_price
                            ?? 0
                        );
                    @endphp

                    <tr>
                        <td class="fw-bold">#{{ $o->id }}</td>

                        <td>
                            <div class="product-row">
                                @if($img)
                                    <img src="{{ $img }}"
                                         class="product-img"
                                         alt="{{ $o->product?->name ?? 'Product' }}">
                                @else
                                    <div class="product-img d-grid place-items-center text-muted small">
                                        No photo
                                    </div>
                                @endif

                                <div>
                                    <div class="fw-bold">
                                        {{ $o->product?->name ?? '-' }}
                                    </div>
                                    <div class="small text-muted text-uppercase">
                                        {{ $o->product?->type ?? '' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="fw-semibold">
                                {{ $o->resident?->fullname ?? $o->resident?->username ?? '-' }}
                            </div>
                            <div class="small text-muted">
                                {{ $o->resident?->email ?? '' }}
                            </div>
                        </td>

                        <td class="fw-semibold">
                            {{ number_format((float)$o->quantity_kilos, 2) }} kg
                        </td>

                        <td>
                            ₱{{ number_format((float)$o->unit_price, 2) }}/kg
                        </td>

                        <td class="fw-bold text-success">
                            ₱{{ number_format($displayTotal, 2) }}
                        </td>

                        <td>
                            @if($st === 'pending')
                                <span class="status-pill status-pending">PENDING</span>
                            @elseif($st === 'approved')
                                <span class="status-pill status-approved">APPROVED</span>

                                <div class="workflow-badges">
                                    <span class="workflow-badge {{ $paymentSt === 'paid' ? 'payment-paid' : 'payment-unpaid' }}">
                                        <i class="bi {{ $paymentSt === 'paid' ? 'bi-cash-coin' : 'bi-hourglass' }}"></i>
                                        {{ strtoupper($paymentSt) }}
                                    </span>

                                    @if($expectedDelivery && !in_array($deliverySt, ['out_for_delivery', 'delivered'], true))
                                        <span class="workflow-badge expected-delivery-badge">
                                            <i class="bi bi-calendar-event"></i>
                                            {{ $expectedDelivery->format('M d, h:i A') }}
                                        </span>
                                    @endif

                                    @if($deliverySt === 'out_for_delivery')
                                        <span class="workflow-badge delivery-out">
                                            <i class="bi bi-truck"></i> OUT FOR DELIVERY
                                        </span>
                                    @elseif($deliverySt === 'delivered')
                                        <span class="workflow-badge delivery-done">
                                            <i class="bi bi-box-seam-fill"></i> DELIVERED
                                        </span>
                                    @else
                                        <span class="workflow-badge delivery-preparing">
                                            <i class="bi bi-box2"></i> PREPARING
                                        </span>
                                    @endif
                                </div>
                            @elseif($st === 'completed')
                                <span class="status-pill status-completed">COMPLETED</span>
                            @elseif($st === 'cancelled')
                                <span class="status-pill status-cancelled">CANCELLED</span>
                            @else
                                <span class="badge bg-dark">{{ strtoupper($st) }}</span>
                            @endif
                        </td>

                        <td class="small text-muted">
                            {{ $o->created_at?->format('M d, Y h:i A') ?? '-' }}
                        </td>

                        <td>
                            <div class="action-stack">
                                <button type="button"
                                        class="btn btn-sm btn-outline-dark"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewOrderModal{{ $o->id }}">
                                    <i class="bi bi-eye"></i>
                                    View
                                </button>

                                @if($st === 'pending')
                                    <form method="POST"
                                          action="{{ route('farmer.orders.approve', $o->id) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-primary"
                                                onclick="return confirm('Approve this order?')">
                                            <i class="bi bi-check-circle"></i>
                                            Approve
                                        </button>
                                    </form>
                                @endif

                                @if($st === 'approved' && $paymentSt !== 'paid')
                                    <form method="POST"
                                          action="{{ route('farmer.orders.paid', $o->id) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-success"
                                                onclick="return confirm('Confirm that payment was actually received?')">
                                            <i class="bi bi-cash-coin"></i>
                                            Mark Paid
                                        </button>
                                    </form>
                                @endif

                                @if($st === 'approved' && !in_array($deliverySt, ['out_for_delivery', 'delivered'], true))
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#scheduleDeliveryModal{{ $o->id }}">
                                        <i class="bi bi-calendar-event"></i>
                                        {{ $expectedDelivery ? 'Change Schedule' : 'Set Expected Delivery' }}
                                    </button>

                                    @if($expectedDelivery)
                                        <form method="POST"
                                              action="{{ route('farmer.orders.startDelivery', $o->id) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-primary"
                                                    onclick="return confirm('Dispatch this order now and mark it OUT FOR DELIVERY?')">
                                                <i class="bi bi-truck"></i>
                                                Dispatch Now
                                            </button>
                                        </form>
                                    @endif
                                @endif

                                @if(!in_array($st, ['completed', 'cancelled'], true) && !in_array($deliverySt, ['out_for_delivery', 'delivered'], true))
                                    <form method="POST"
                                          action="{{ route('farmer.orders.cancel', $o->id) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Cancel this order and return stock?')">
                                            <i class="bi bi-x-circle"></i>
                                            Cancel
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            No orders yet.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE CARDS --}}
        <div class="mobile-orders">

            @forelse($orders as $o)
                @php
                    $img = !empty($o->product?->photo_path)
                        ? asset('storage/'.$o->product->photo_path)
                        : null;

                    $st = strtolower($o->status ?? 'pending');
                    $deliverySt = strtolower($o->delivery_status ?? 'pending');
                    $paymentSt = strtolower($o->payment_status ?? 'unpaid');

                    $expectedDelivery = !empty($o->expected_delivery_at)
                        ? \Carbon\Carbon::parse($o->expected_delivery_at, 'UTC')->timezone('Asia/Manila')
                        : null;

                    $displayTotal = (float) (
                        $o->grand_total
                        ?? $o->total_price
                        ?? 0
                    );
                @endphp

                <article class="mobile-order-card">

                    <div class="mobile-order-top">
                        @if($img)
                            <img src="{{ $img }}"
                                 class="mobile-product-img"
                                 alt="{{ $o->product?->name ?? 'Product' }}">
                        @else
                            <div class="mobile-product-img d-grid place-items-center text-muted small">
                                No photo
                            </div>
                        @endif

                        <div class="mobile-order-main">
                            <div class="mobile-order-id">
                                Order #{{ $o->id }}
                            </div>

                            <div class="mobile-product-name">
                                {{ $o->product?->name ?? '-' }}
                            </div>

                            <div class="mobile-product-type">
                                {{ $o->product?->type ?? '' }}
                            </div>

                            <div class="mobile-status-row">
                                @if($st === 'pending')
                                    <span class="status-pill status-pending">PENDING</span>
                                @elseif($st === 'approved')
                                    <span class="status-pill status-approved">APPROVED</span>
                                @elseif($st === 'completed')
                                    <span class="status-pill status-completed">COMPLETED</span>
                                @elseif($st === 'cancelled')
                                    <span class="status-pill status-cancelled">CANCELLED</span>
                                @endif
                            </div>

                            @if($st === 'approved')
                                <div class="workflow-badges">
                                    <span class="workflow-badge {{ $paymentSt === 'paid' ? 'payment-paid' : 'payment-unpaid' }}">
                                        <i class="bi {{ $paymentSt === 'paid' ? 'bi-cash-coin' : 'bi-hourglass' }}"></i>
                                        {{ strtoupper($paymentSt) }}
                                    </span>

                                    @if($expectedDelivery && !in_array($deliverySt, ['out_for_delivery', 'delivered'], true))
                                        <span class="workflow-badge expected-delivery-badge">
                                            <i class="bi bi-calendar-event"></i>
                                            {{ $expectedDelivery->format('M d, h:i A') }}
                                        </span>
                                    @endif

                                    @if($deliverySt === 'out_for_delivery')
                                        <span class="workflow-badge delivery-out">
                                            <i class="bi bi-truck"></i>
                                            OUT FOR DELIVERY
                                        </span>
                                    @elseif($deliverySt === 'delivered')
                                        <span class="workflow-badge delivery-done">
                                            <i class="bi bi-check-circle-fill"></i>
                                            DELIVERED
                                        </span>
                                    @else
                                        <span class="workflow-badge delivery-preparing">
                                            <i class="bi bi-box2"></i>
                                            PREPARING
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="resident-block">
                        <div class="small text-muted">Resident / Buyer</div>
                        <div class="resident-name">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ $o->resident?->fullname ?? $o->resident?->username ?? '-' }}
                        </div>
                        <div class="resident-meta">
                            {{ $o->resident?->email ?? '' }}
                        </div>
                        @if(!empty($o->resident?->barangay))
                            <div class="resident-meta mt-1">
                                <i class="bi bi-geo-alt"></i>
                                {{ $o->resident?->barangay }}
                            </div>
                        @endif
                    </div>

                    <div class="mobile-info-grid">
                        <div class="mobile-info">
                            <div class="mobile-info-label">Quantity</div>
                            <div class="mobile-info-value">
                                {{ number_format((float)$o->quantity_kilos, 2) }} kg
                            </div>
                        </div>

                        <div class="mobile-info">
                            <div class="mobile-info-label">Unit Price</div>
                            <div class="mobile-info-value">
                                ₱{{ number_format((float)$o->unit_price, 2) }}/kg
                            </div>
                        </div>

                        <div class="mobile-info">
                            <div class="mobile-info-label">Order Total</div>
                            <div class="mobile-info-value text-success">
                                ₱{{ number_format($displayTotal, 2) }}
                            </div>
                        </div>

                        <div class="mobile-info">
                            <div class="mobile-info-label">Ordered</div>
                            <div class="mobile-info-value">
                                {{ $o->created_at?->format('M d, h:i A') ?? '-' }}
                            </div>
                        </div>

                        @if($expectedDelivery)
                            <div class="mobile-info full-row">
                                <div class="mobile-info-label">Expected Delivery</div>
                                <div class="mobile-info-value text-primary">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ $expectedDelivery->format('M d, Y h:i A') }}
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($st === 'approved' && $deliverySt === 'delivered')
                        <div class="buyer-confirm-box">
                            <i class="bi bi-bell-fill me-1"></i>
                            Delivered. The buyer has been notified and must now confirm
                            <strong>I Received My Order</strong>.
                        </div>

                        @if(!empty($o->proof_of_delivery_path))
                            <img
                                src="{{ asset('storage/'.$o->proof_of_delivery_path) }}"
                                class="proof-photo"
                                alt="Proof of delivery">
                        @endif

                    @elseif($st === 'approved' && $deliverySt === 'out_for_delivery')
                        <div class="buyer-confirm-box">
                            <i class="bi bi-truck me-1"></i>
                            Order is currently <strong>OUT FOR DELIVERY</strong>.
                            Upload a proof photo after handing over the order.
                        </div>

                    @elseif($st === 'approved')
                        <div class="buyer-confirm-box">
                            <i class="bi bi-box2 me-1"></i>
                            Order approved. Set the expected delivery date and time first.
                            The buyer will be notified of the schedule.
                        </div>

                    @elseif($st === 'completed')
                        <div class="buyer-confirm-box"
                             style="background:#edf9f2;color:#146c43;border-color:#d1e7dd;">
                            <i class="bi bi-check-circle-fill me-1"></i>
                            Buyer confirmed that this order was received.
                        </div>
                    @endif

                    <div class="mobile-action-grid">
                        <button type="button"
                                class="btn btn-outline-dark"
                                data-bs-toggle="modal"
                                data-bs-target="#viewOrderModal{{ $o->id }}">
                            <i class="bi bi-eye"></i>
                            View Details
                        </button>

                        @if($st === 'pending')
                            <form method="POST"
                                  action="{{ route('farmer.orders.approve', $o->id) }}">
                                @csrf
                                <button class="btn btn-primary"
                                        onclick="return confirm('Approve this order?')">
                                    <i class="bi bi-check-circle"></i>
                                    Approve
                                </button>
                            </form>
                        @endif

                        @if($st === 'approved' && $paymentSt !== 'paid')
                            <form method="POST"
                                  action="{{ route('farmer.orders.paid', $o->id) }}">
                                @csrf
                                <button class="btn btn-success"
                                        onclick="return confirm('Confirm that payment was actually received?')">
                                    <i class="bi bi-cash-coin"></i>
                                    Mark Paid
                                </button>
                            </form>
                        @endif

                        @if($st === 'approved' && !in_array($deliverySt, ['out_for_delivery', 'delivered'], true))
                            <button type="button"
                                    class="btn btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#scheduleDeliveryModal{{ $o->id }}">
                                <i class="bi bi-calendar-event"></i>
                                {{ $expectedDelivery ? 'Change Expected Delivery' : 'Set Expected Delivery' }}
                            </button>

                            @if($expectedDelivery)
                                <form method="POST"
                                      action="{{ route('farmer.orders.startDelivery', $o->id) }}">
                                    @csrf
                                    <button class="btn btn-primary"
                                            onclick="return confirm('Dispatch this order now and mark it OUT FOR DELIVERY?')">
                                        <i class="bi bi-truck"></i>
                                        Dispatch Now
                                    </button>
                                </form>
                            @endif
                        @endif

                        @if(!in_array($st, ['completed', 'cancelled'], true) && !in_array($deliverySt, ['out_for_delivery', 'delivered'], true))
                            <form method="POST"
                                  action="{{ route('farmer.orders.cancel', $o->id) }}">
                                @csrf
                                <button class="btn btn-outline-danger"
                                        onclick="return confirm('Cancel this order and return stock?')">
                                    <i class="bi bi-x-circle"></i>
                                    Cancel
                                </button>
                            </form>
                        @endif
                    </div>

                    @if($st === 'approved' && $deliverySt === 'out_for_delivery')
                        <div class="delivery-workflow-box">
                            <div class="delivery-workflow-title">
                                <i class="bi bi-camera-fill me-1"></i>
                                Proof of Delivery
                            </div>

                            <div class="small text-muted">
                                Take a clear photo after handing the order to the buyer.
                            </div>

                            <form method="POST"
                                  action="{{ route('farmer.orders.delivered', $o->id) }}"
                                  enctype="multipart/form-data"
                                  class="proof-form">
                                @csrf

                                <input type="file"
                                       name="proof_photo"
                                       class="form-control"
                                       accept="image/*"
                                       capture="environment"
                                       required>

                                <button type="submit"
                                        class="btn btn-success w-100 mt-2"
                                        onclick="return confirm('Upload this photo and mark the order as DELIVERED?')">
                                    <i class="bi bi-camera-fill"></i>
                                    Upload Proof & Mark Delivered
                                </button>
                            </form>
                        </div>
                    @endif

                </article>
            @empty
                <div class="mobile-order-card text-center text-muted py-5">
                    <i class="bi bi-bag-x fs-1"></i>
                    <div class="mt-2">No orders yet.</div>
                </div>
            @endforelse

        </div>

        <div class="pagination-wrap">
            {{ $orders->links() }}
        </div>

    </section>

</main>


{{-- =========================================================
     ORDER DETAIL MODALS
     IMPORTANT: These are intentionally OUTSIDE .desktop-orders.
     .desktop-orders is display:none on mobile, so a modal placed
     inside it would also be invisible even though the backdrop opens.
========================================================= --}}
@foreach($orders as $o)
    @php
        $modalImg = !empty($o->product?->photo_path)
            ? asset('storage/'.$o->product->photo_path)
            : null;

        $modalStatus = strtolower($o->status ?? 'pending');
        $modalDelivery = strtolower($o->delivery_status ?? 'pending');
        $modalPayment = strtolower($o->payment_status ?? 'unpaid');

        $modalExpectedDelivery = !empty($o->expected_delivery_at)
            ? \Carbon\Carbon::parse($o->expected_delivery_at, 'UTC')->timezone('Asia/Manila')
            : null;

        $modalTotal = (float) (
            $o->grand_total
            ?? $o->total_price
            ?? 0
        );
    @endphp

    <div class="modal fade"
         id="viewOrderModal{{ $o->id }}"
         tabindex="-1"
         aria-labelledby="viewOrderModalLabel{{ $o->id }}"
         aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">

                <div class="modal-header">
                    <div>
                        <div class="small text-muted">Resident Order</div>
                        <h5 class="modal-title fw-bold mb-0"
                            id="viewOrderModalLabel{{ $o->id }}">
                            Order Details #{{ $o->id }}
                        </h5>
                    </div>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-12 col-md-6">
                            <div class="modal-info-box">

                                <div class="d-flex gap-3 align-items-center mb-3">

                                    @if($modalImg)
                                        <img src="{{ $modalImg }}"
                                             class="product-img"
                                             alt="{{ $o->product?->name ?? 'Product' }}">
                                    @else
                                        <div class="product-img d-grid place-items-center text-muted small">
                                            No photo
                                        </div>
                                    @endif

                                    <div class="min-w-0">
                                        <div class="fw-bold fs-5">
                                            {{ $o->product?->name ?? '-' }}
                                        </div>

                                        <div class="small text-muted text-uppercase">
                                            {{ $o->product?->type ?? '' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3">

                                    <div class="col-6">
                                        <div class="meta-label">Quantity</div>
                                        <div class="meta-value">
                                            {{ number_format((float)$o->quantity_kilos, 2) }} kg
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="meta-label">Unit Price</div>
                                        <div class="meta-value">
                                            ₱{{ number_format((float)$o->unit_price, 2) }}/kg
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="meta-label">Order Total</div>
                                        <div class="meta-value text-success">
                                            ₱{{ number_format($modalTotal, 2) }}
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="meta-label">Status</div>
                                        <div class="meta-value">
                                            {{ strtoupper($modalStatus) }}
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="meta-label">Order Type</div>
                                        <div class="meta-value">
                                            {{ ucfirst($o->fulfillment_type ?? '-') }}
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="meta-label">Expected Delivery</div>
                                        <div class="meta-value text-primary">
                                            @if($modalExpectedDelivery)
                                                <i class="bi bi-calendar-event me-1"></i>
                                                {{ $modalExpectedDelivery->format('F d, Y h:i A') }}
                                            @else
                                                Not scheduled yet
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="meta-label">Payment</div>
                                        <div class="meta-value">
                                            {{ strtoupper($o->payment_method ?? '-') }}
                                            /
                                            {{ strtoupper($modalPayment) }}
                                        </div>
                                    </div>

                                    @if(($o->fulfillment_type ?? '') === 'delivery')
                                        <div class="col-6">
                                            <div class="meta-label">Distance</div>
                                            <div class="meta-value">
                                                {{ number_format((float)($o->distance_km ?? 0), 2) }} km
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="meta-label">Shipping Fee</div>
                                            <div class="meta-value">
                                                ₱{{ number_format((float)($o->shipping_fee ?? 0), 2) }}
                                            </div>
                                        </div>
                                    @endif

                                </div>

                                @if($modalStatus === 'approved' && $modalDelivery === 'delivered')
                                    <div class="buyer-confirm-box">
                                        <i class="bi bi-bell-fill me-1"></i>
                                        Delivery completed by farmer. Buyer notification is active
                                        until the buyer confirms receipt.
                                    </div>

                                    @if(!empty($o->proof_of_delivery_path))
                                        <img
                                            src="{{ asset('storage/'.$o->proof_of_delivery_path) }}"
                                            class="proof-photo"
                                            alt="Proof of delivery">
                                    @endif

                                @elseif($modalStatus === 'approved' && $modalDelivery === 'out_for_delivery')
                                    <div class="buyer-confirm-box">
                                        <i class="bi bi-truck me-1"></i>
                                        This order is OUT FOR DELIVERY.
                                    </div>

                                    <form method="POST"
                                          action="{{ route('farmer.orders.delivered', $o->id) }}"
                                          enctype="multipart/form-data"
                                          class="proof-form">
                                        @csrf

                                        <label class="form-label fw-semibold">
                                            Take / Upload Proof Photo
                                        </label>

                                        <input type="file"
                                               name="proof_photo"
                                               class="form-control"
                                               accept="image/*"
                                               capture="environment"
                                               required>

                                        <button type="submit"
                                                class="btn btn-success w-100 mt-2"
                                                onclick="return confirm('Upload proof and mark this order DELIVERED?')">
                                            <i class="bi bi-camera-fill"></i>
                                            Upload Proof & Mark Delivered
                                        </button>
                                    </form>

                                @elseif($modalStatus === 'approved')
                                    <div class="buyer-confirm-box">
                                        <i class="bi bi-box2 me-1"></i>
                                        Approved and preparing for delivery.
                                    </div>

                                @elseif($modalStatus === 'completed')
                                    <div class="buyer-confirm-box"
                                         style="background:#edf9f2;color:#146c43;border-color:#d1e7dd;">
                                        <i class="bi bi-check-circle-fill me-1"></i>
                                        Buyer confirmed that this order was received.
                                    </div>
                                @endif

                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="modal-info-box">

                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-person-circle me-1"></i>
                                    Resident Information
                                </h6>

                                <div class="mb-2">
                                    <div class="meta-label">Full Name</div>
                                    <div class="meta-value">
                                        {{ $o->resident?->fullname ?? $o->resident?->username ?? '-' }}
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <div class="meta-label">Email</div>
                                    <div class="meta-value">
                                        {{ $o->resident?->email ?? '-' }}
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <div class="meta-label">Barangay</div>
                                    <div class="meta-value">
                                        {{ $o->resident?->barangay ?? '-' }}
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <div class="meta-label">
                                        {{ ($o->fulfillment_type ?? '') === 'pickup'
                                            ? 'Pickup Address'
                                            : 'Delivery Address' }}
                                    </div>

                                    <div class="meta-value">
                                        {{ ($o->fulfillment_type ?? '') === 'pickup'
                                            ? ($o->pickup_address ?? '-')
                                            : ($o->delivery_address ?? '-') }}
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <div class="meta-label">Contact Number</div>
                                    <div class="meta-value">
                                        {{ $o->contact_number ?? '-' }}
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <div class="meta-label">Notes</div>
                                    <div class="meta-value">
                                        {{ $o->notes ?? 'No notes provided.' }}
                                    </div>
                                </div>

                                <div class="mb-0">
                                    <div class="meta-label">Ordered At</div>
                                    <div class="meta-value">
                                        {{ $o->created_at?->format('F d, Y h:i A') ?? '-' }}
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">

                    @if($modalStatus === 'pending')
                        <form method="POST"
                              action="{{ route('farmer.orders.approve', $o->id) }}">
                            @csrf

                            <button class="btn btn-primary"
                                    onclick="return confirm('Approve this order?')">
                                <i class="bi bi-check-circle"></i>
                                Approve
                            </button>
                        </form>
                    @endif

                    @if($modalStatus === 'approved' && $modalPayment !== 'paid')
                        <form method="POST"
                              action="{{ route('farmer.orders.paid', $o->id) }}">
                            @csrf

                            <button class="btn btn-success"
                                    onclick="return confirm('Confirm that payment was actually received?')">
                                <i class="bi bi-cash-coin"></i>
                                Mark Paid
                            </button>
                        </form>
                    @endif

                    @if($modalStatus === 'approved' && !in_array($modalDelivery, ['out_for_delivery', 'delivered'], true))
                        <button type="button"
                                class="btn btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#scheduleDeliveryModal{{ $o->id }}"
                                data-bs-dismiss="modal">
                            <i class="bi bi-calendar-event"></i>
                            {{ $modalExpectedDelivery ? 'Change Expected Delivery' : 'Set Expected Delivery' }}
                        </button>

                        @if($modalExpectedDelivery)
                            <form method="POST"
                                  action="{{ route('farmer.orders.startDelivery', $o->id) }}">
                                @csrf

                                <button class="btn btn-primary"
                                        onclick="return confirm('Dispatch this order now and mark it OUT FOR DELIVERY?')">
                                    <i class="bi bi-truck"></i>
                                    Dispatch Now
                                </button>
                            </form>
                        @endif
                    @endif

                    @if(!in_array($modalStatus, ['completed', 'cancelled'], true) && !in_array($modalDelivery, ['out_for_delivery', 'delivered'], true))
                        <form method="POST"
                              action="{{ route('farmer.orders.cancel', $o->id) }}">
                            @csrf

                            <button class="btn btn-outline-danger"
                                    onclick="return confirm('Cancel this order and return stock?')">
                                <i class="bi bi-x-circle"></i>
                                Cancel
                            </button>
                        </form>
                    @endif

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Close
                    </button>

                </div>

            </div>
        </div>
    </div>
@endforeach


{{-- =========================================================
     EXPECTED DELIVERY SCHEDULE MODALS
========================================================= --}}
@foreach($orders as $o)
    @php
        $scheduleStatus = strtolower($o->status ?? 'pending');
        $scheduleDeliveryStatus = strtolower($o->delivery_status ?? 'pending');

        $scheduleExpected = !empty($o->expected_delivery_at)
            ? \Carbon\Carbon::parse($o->expected_delivery_at, 'UTC')->timezone('Asia/Manila')
            : null;
    @endphp

    @if(
        $scheduleStatus === 'approved' &&
        !in_array($scheduleDeliveryStatus, ['out_for_delivery', 'delivered'], true)
    )
        <div class="modal fade"
             id="scheduleDeliveryModal{{ $o->id }}"
             tabindex="-1"
             aria-labelledby="scheduleDeliveryModalLabel{{ $o->id }}"
             aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">

                    <div class="modal-header">
                        <div>
                            <div class="small text-muted">Order #{{ $o->id }}</div>
                            <h5 class="modal-title fw-bold mb-0"
                                id="scheduleDeliveryModalLabel{{ $o->id }}">
                                Expected Delivery Schedule
                            </h5>
                        </div>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>

                    <form method="POST"
                          action="{{ route('farmer.orders.expectedDelivery', $o->id) }}">
                        @csrf

                        <div class="modal-body">

                            <div class="schedule-form-card">

                                <label class="form-label fw-bold">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    Expected Delivery Date & Time
                                </label>

                                <input type="datetime-local"
                                       name="expected_delivery_at"
                                       class="form-control form-control-lg"
                                       min="{{ now('Asia/Manila')->format('Y-m-d\TH:i') }}"
                                       value="{{ $scheduleExpected?->format('Y-m-d\TH:i') }}"
                                       required>

                                <div class="schedule-help mt-2">
                                    Set the date and time you expect to deliver this order.
                                    The buyer will receive a notification after you save it.
                                </div>

                                @if($scheduleExpected)
                                    <div class="expected-delivery-box mt-3">
                                        Current expected delivery:
                                        <strong>
                                            {{ $scheduleExpected->format('F d, Y h:i A') }}
                                        </strong>
                                    </div>
                                @endif

                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="bi bi-calendar-check me-1"></i>
                                Save Expected Delivery
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    @endif
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>