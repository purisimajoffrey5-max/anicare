<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders | ANI-CARE Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

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
            min-height: 100%;
            font-family: "Segoe UI", sans-serif;
        }

        body {
            background: #f4f7f6;
            color: #1f2937;
        }

        .topbar {
            background: #198754;
            color: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
        }

        .topbar-inner {
            min-height: 62px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 16px;
        }

        .brand {
            font-weight: 800;
            font-size: 19px;
        }

        .top-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .page-wrap {
            max-width: 1050px;
            margin: 0 auto;
            padding: 24px 14px 60px;
        }

        .page-title {
            margin: 0;
            color: #198754;
            font-weight: 850;
            font-size: 30px;
        }

        .page-subtitle {
            color: #6c757d;
            margin-top: 4px;
            margin-bottom: 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 18px;
        }

        .stat-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 15px;
            box-shadow: 0 5px 15px rgba(15,23,42,.04);
        }

        .stat-label {
            color: #6b7280;
            font-size: 12px;
        }

        .stat-value {
            margin-top: 4px;
            font-size: 25px;
            font-weight: 850;
        }

        .order-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 5px 16px rgba(15,23,42,.04);
        }

        .order-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 14px;
        }

        .product-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .product-img {
            width: 68px;
            height: 68px;
            border-radius: 12px;
            object-fit: cover;
            background: #eef1ef;
            flex: 0 0 auto;
        }

        .product-name {
            font-size: 17px;
            font-weight: 850;
            overflow-wrap: anywhere;
        }

        .seller-name {
            margin-top: 2px;
            color: #6b7280;
            font-size: 12px;
        }

        .status-stack {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 850;
        }

        .pending {
            background: #fff3cd;
            color: #7a5d00;
        }

        .approved,
        .scheduled,
        .preparing,
        .out-for-delivery {
            background: #e7f1ff;
            color: #174ea6;
        }

        .delivered,
        .completed {
            background: #d1e7dd;
            color: #0f5132;
        }

        .cancelled {
            background: #eceff1;
            color: #495057;
        }

        .unpaid {
            background: #fff3cd;
            color: #7a5d00;
        }

        .paid {
            background: #d1e7dd;
            color: #0f5132;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 8px;
        }

        .info-box {
            background: #f8faf9;
            border: 1px solid #eef1ef;
            border-radius: 11px;
            padding: 10px;
            min-width: 0;
        }

        .info-label {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 3px;
        }

        .info-value {
            font-size: 13px;
            font-weight: 750;
            overflow-wrap: anywhere;
        }

        .expected-box {
            margin-top: 10px;
            padding: 11px 12px;
            border: 1px solid #cfe2ff;
            border-radius: 12px;
            background: #eef6ff;
            color: #174ea6;
            font-size: 12px;
        }

        .empty-state {
            padding: 50px 20px;
            text-align: center;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            color: #6b7280;
        }


        .workflow-note {
            margin-top: 10px;
            padding: 10px 12px;
            border-radius: 11px;
            font-size: 12px;
            line-height: 1.45;
        }

        .note-pending {
            background: #fff8df;
            border: 1px solid #ffe8a3;
            color: #705a00;
        }

        .note-active {
            background: #eef6ff;
            border: 1px solid #cfe2ff;
            color: #174ea6;
        }

        .note-success {
            background: #eaf8ef;
            border: 1px solid #ccebd8;
            color: #146c43;
        }

        .order-actions {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
            margin-top: 12px;
        }

        .order-actions .btn,
        .order-actions form,
        .order-actions form .btn {
            width: 100%;
        }

        .order-actions .btn {
            min-height: 42px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
        }

        .receive-wrap {
            grid-column: 1 / -1;
        }

        @media (max-width: 767.98px) {
            .topbar-inner {
                padding: 9px 10px;
            }

            .brand {
                font-size: 16px;
            }

            .top-actions .btn {
                font-size: 11px;
            }

            .page-wrap {
                padding: 17px 10px 50px;
            }

            .page-title {
                font-size: 25px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 8px;
            }

            .order-head {
                display: block;
            }

            .status-stack {
                justify-content: flex-start;
                margin-top: 10px;
            }

            .info-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .order-card {
                padding: 12px;
            }

            .order-actions {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .receive-wrap {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 380px) {
            .brand {
                font-size: 14px;
            }

            .top-actions .btn span {
                display: none;
            }

            .product-img {
                width: 58px;
                height: 58px;
            }

            .order-actions {
                grid-template-columns: 1fr;
            }

            .receive-wrap {
                grid-column: auto;
            }
        }
    </style>
</head>

<body>

<header class="topbar">
    <div class="topbar-inner">

        <div class="brand">
            ANI-CARE | Admin
        </div>

        <div class="top-actions">

            <a
                href="{{ route('admin.market') }}"
                class="btn btn-outline-light btn-sm"
            >
                <i class="bi bi-arrow-left"></i>
                <span>Marketplace</span>
            </a>

            <form
                method="POST"
                action="{{ route('logout') }}"
                class="m-0"
            >
                @csrf

                <button class="btn btn-warning btn-sm">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>

        </div>

    </div>
</header>


<main class="page-wrap">

    <h1 class="page-title">
        <i class="bi bi-bag-check me-1"></i>
        My Orders
    </h1>

    <div class="page-subtitle">
        Orders you placed from farmer marketplace products.
    </div>


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

        $pendingCount =
            $currentOrders
                ->where('status', 'pending')
                ->count();

        $activeCount =
            $currentOrders
                ->where('status', 'approved')
                ->count();

        $completedCount =
            $currentOrders
                ->where('status', 'completed')
                ->count();
    @endphp


    <section class="stats-grid">

        <div class="stat-card">
            <div class="stat-label">Total Orders</div>
            <div class="stat-value">
                {{ $orders->total() }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Pending</div>
            <div class="stat-value text-warning">
                {{ $pendingCount }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Active</div>
            <div class="stat-value text-primary">
                {{ $activeCount }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Completed</div>
            <div class="stat-value text-success">
                {{ $completedCount }}
            </div>
        </div>

    </section>


    @forelse($orders as $o)

        @php
            $status =
                strtolower(
                    (string) ($o->status ?? 'pending')
                );

            $deliveryStatus =
                strtolower(
                    (string) ($o->delivery_status ?? 'pending')
                );

            $paymentStatus =
                strtolower(
                    (string) ($o->payment_status ?? 'unpaid')
                );

            $expectedDelivery = null;

            if (!empty($o->expected_delivery_at)) {
                try {
                    $expectedDelivery =
                        \Carbon\Carbon::parse(
                            $o->expected_delivery_at,
                            'UTC'
                        )->timezone('Asia/Manila');
                } catch (\Throwable $e) {
                    $expectedDelivery = null;
                }
            }

            $displayStage = match (true) {
                $status === 'cancelled' =>
                    'CANCELLED',

                $status === 'completed' =>
                    'COMPLETED',

                $deliveryStatus === 'delivered' =>
                    'DELIVERED',

                $deliveryStatus === 'out_for_delivery' =>
                    'OUT FOR DELIVERY',

                $status === 'approved' && $expectedDelivery =>
                    'SCHEDULED',

                $status === 'approved' =>
                    'PREPARING',

                default =>
                    strtoupper($status),
            };

            $stageClass = match ($displayStage) {
                'PENDING' => 'pending',
                'PREPARING' => 'preparing',
                'SCHEDULED' => 'scheduled',
                'OUT FOR DELIVERY' => 'out-for-delivery',
                'DELIVERED' => 'delivered',
                'COMPLETED' => 'completed',
                'CANCELLED' => 'cancelled',
                default => 'pending',
            };

            $displayTotal =
                (float) (
                    $o->grand_total
                    ?? $o->total_price
                    ?? 0
                );

            $productPhoto =
                !empty($o->product?->photo_path)
                    ? asset(
                        'storage/' .
                        $o->product->photo_path
                    )
                    : null;
        @endphp


        <article class="order-card">

            <div class="order-head">

                <div class="product-wrap">

                    @if($productPhoto)
                        <img
                            src="{{ $productPhoto }}"
                            class="product-img"
                            alt="{{ $o->product?->name ?? 'Product' }}"
                        >
                    @else
                        <div
                            class="product-img d-grid place-items-center"
                            style="display:grid;place-items:center;"
                        >
                            🌾
                        </div>
                    @endif

                    <div>
                        <div class="small text-muted">
                            Order #{{ $o->id }}
                        </div>

                        <div class="product-name">
                            {{ $o->product?->name ?? '-' }}
                        </div>

                        <div class="seller-name">
                            Seller:
                            {{ $o->farmer?->fullname
                                ?? $o->farmer?->username
                                ?? '-' }}
                        </div>
                    </div>

                </div>


                <div class="status-stack">

                    <span class="status-pill {{ $stageClass }}">
                        {{ $displayStage }}
                    </span>

                    <span class="status-pill {{ $paymentStatus === 'paid' ? 'paid' : 'unpaid' }}">
                        {{ strtoupper($paymentStatus) }}
                    </span>

                </div>

            </div>


            <div class="info-grid">

                <div class="info-box">
                    <div class="info-label">
                        Quantity
                    </div>

                    <div class="info-value">
                        {{ number_format((float) $o->quantity_kilos, 2) }} kg
                    </div>
                </div>


                <div class="info-box">
                    <div class="info-label">
                        Order Total
                    </div>

                    <div class="info-value text-success">
                        ₱{{ number_format($displayTotal, 2) }}
                    </div>
                </div>


                <div class="info-box">
                    <div class="info-label">
                        Fulfillment
                    </div>

                    <div class="info-value">
                        {{ ucfirst($o->fulfillment_type ?? '-') }}
                    </div>
                </div>


                <div class="info-box">
                    <div class="info-label">
                        Ordered
                    </div>

                    <div class="info-value">
                        {{ $o->created_at
                            ? $o->created_at
                                ->copy()
                                ->timezone('Asia/Manila')
                                ->format('M d, h:i A')
                            : '-' }}
                    </div>
                </div>

            </div>


            @if($expectedDelivery)
                <div class="expected-box">
                    <i class="bi bi-calendar-event me-1"></i>
                    Expected Delivery:
                    <strong>
                        {{ $expectedDelivery->format('M d, Y h:i A') }}
                    </strong>
                </div>
            @endif


            {{-- ORDER WORKFLOW MESSAGE --}}
            @if($status === 'pending')

                <div class="workflow-note note-pending">
                    <i class="bi bi-hourglass-split me-1"></i>
                    Waiting for the farmer to approve your order.
                </div>

            @elseif($status === 'approved' && $deliveryStatus === 'delivered')

                <div class="workflow-note note-success">
                    <i class="bi bi-house-check-fill me-1"></i>
                    The seller marked this order as delivered.
                    Review the proof before confirming receipt.
                </div>

            @elseif($status === 'approved' && $deliveryStatus === 'out_for_delivery')

                <div class="workflow-note note-active">
                    <i class="bi bi-truck me-1"></i>
                    Your order is now <strong>OUT FOR DELIVERY</strong>.
                </div>

            @elseif($status === 'approved' && $expectedDelivery)

                <div class="workflow-note note-active">
                    <i class="bi bi-calendar-check me-1"></i>
                    Your order is being prepared. Expected delivery:
                    <strong>{{ $expectedDelivery->format('M d, Y h:i A') }}</strong>.
                </div>

            @elseif($status === 'approved')

                <div class="workflow-note note-active">
                    <i class="bi bi-box2 me-1"></i>
                    The farmer approved your order and is preparing it.
                </div>

            @elseif($status === 'completed')

                <div class="workflow-note note-success">
                    <i class="bi bi-check-circle-fill me-1"></i>
                    You confirmed that this order was received.
                </div>

            @endif


            {{-- ACTIONS --}}
            <div class="order-actions">

                <a
                    href="{{ route('admin.orders.show', $o->id) }}"
                    class="btn btn-outline-primary"
                >
                    <i class="bi bi-eye"></i>
                    View / Track
                </a>

                <a
                    href="{{ route('admin.orders.invoice.show', $o->id) }}"
                    class="btn btn-outline-success"
                >
                    <i class="bi bi-receipt"></i>
                    View Invoice
                </a>

                <a
                    href="{{ route('admin.orders.invoice.download', $o->id) }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-file-earmark-pdf"></i>
                    PDF
                </a>


                @if(
                    $status === 'approved' &&
                    $deliveryStatus === 'delivered' &&
                    !empty($o->proof_of_delivery_path)
                )

                    <form
                        method="POST"
                        action="{{ route('admin.orders.received', $o->id) }}"
                        class="receive-wrap"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="btn btn-success"
                            onclick="return confirm('Confirm that you have actually received this order? This will complete the transaction.')"
                        >
                            <i class="bi bi-check-circle-fill"></i>
                            I Received My Order
                        </button>
                    </form>

                @endif

            </div>

        </article>

    @empty

        <div class="empty-state">
            <i class="bi bi-bag-x fs-1 d-block mb-2"></i>

            You have not placed any marketplace orders yet.

            <div class="mt-3">
                <a
                    href="{{ route('admin.market') }}"
                    class="btn btn-success"
                >
                    Browse Marketplace
                </a>
            </div>
        </div>

    @endforelse


    @if($orders->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    @endif

</main>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>