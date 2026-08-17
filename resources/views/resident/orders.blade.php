<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders | Resident</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            min-height: 100%;
            font-family: "Segoe UI", sans-serif;
        }

        body {
            background: #f4f7f6;
            color: #20252b;
        }

        .page {
            width: min(1100px, 100%);
            margin: 0 auto;
            padding: 24px 16px 70px;
        }

        .page-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            margin-bottom: 18px;
        }

        .page-head h1 {
            margin: 0;
            color: #198754;
            font-size: 30px;
            font-weight: 800;
        }

        .page-head p {
            margin: 3px 0 0;
            color: #6c757d;
            font-size: 13px;
        }

        .page-head .btn {
            border-radius: 10px;
            white-space: nowrap;
        }

        .desktop-table {
            background: #fff;
            border: 1px solid #e8ecea;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 6px 24px rgba(15,23,42,.05);
        }

        .desktop-table .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .desktop-table table {
            min-width: 980px;
            margin: 0;
        }

        .desktop-table th {
            background: #f8faf9;
            white-space: nowrap;
            padding: 14px;
        }

        .desktop-table td {
            padding: 14px;
            vertical-align: middle;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
        }

        .pending { background:#fff3cd; color:#755b00; }
        .approved { background:#dbeafe; color:#174ea6; }
        .completed { background:#d1e7dd; color:#0f5132; }
        .cancelled { background:#eceff1; color:#495057; }

        .mobile-orders {
            display: none;
        }

        .order-card {
            background: #fff;
            border: 1px solid #e8ecea;
            border-radius: 18px;
            padding: 15px;
            margin-bottom: 12px;
            box-shadow: 0 5px 18px rgba(15,23,42,.04);
        }

        .order-card-head {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            align-items: flex-start;
        }

        .order-id {
            color: #6c757d;
            font-size: 11px;
        }

        .product-name {
            font-weight: 800;
            font-size: 17px;
            line-height: 1.2;
            margin-top: 2px;
        }

        .farmer {
            color: #6c757d;
            font-size: 12px;
            margin-top: 4px;
        }

        .info-grid {
            margin-top: 13px;
            display: grid;
            grid-template-columns: repeat(2, minmax(0,1fr));
            gap: 9px;
        }

        .info-box {
            background: #f8faf9;
            border-radius: 11px;
            padding: 10px;
            min-width: 0;
        }

        .info-label {
            color: #6c757d;
            font-size: 10px;
            margin-bottom: 3px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 750;
            overflow-wrap: anywhere;
        }

        .workflow-note {
            margin-top: 12px;
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 12px;
            line-height: 1.45;
        }

        .note-pending {
            background: #fff8df;
            color: #765c00;
        }

        .note-approved {
            background: #eef6ff;
            color: #174ea6;
        }

        .note-completed {
            background: #edf9f2;
            color: #146c43;
        }

        .actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0,1fr));
            gap: 8px;
            margin-top: 13px;
        }

        .actions .btn,
        .actions form,
        .actions form .btn {
            width: 100%;
        }

        .actions .btn {
            min-height: 43px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .receive-btn {
            grid-column: 1 / -1;
            min-height: 50px !important;
            font-weight: 800;
        }

        .pagination-wrap {
            margin-top: 14px;
        }

        @media (max-width: 767.98px) {
            .page {
                padding: 18px 12px 70px;
            }

            .page-head {
                align-items: flex-start;
            }

            .page-head h1 {
                font-size: 27px;
            }

            .page-head .btn {
                width: 44px;
                height: 42px;
                padding: 0;
                display: grid;
                place-items: center;
                font-size: 0;
            }

            .page-head .btn i {
                font-size: 18px;
            }

            .desktop-table {
                display: none;
            }

            .mobile-orders {
                display: block;
            }
        }

        @media (max-width: 390px) {
            .info-grid,
            .actions {
                grid-template-columns: 1fr;
            }

            .receive-btn {
                grid-column: auto;
            }
        }
    </style>
</head>
<body>

<main class="page">

    <div class="page-head">
        <div>
            <h1>My Orders</h1>
            <p>Track your purchases and confirm when you receive your order.</p>
        </div>

        <a href="{{ route('resident.marketplace') }}"
           class="btn btn-outline-success btn-sm"
           title="Back to Marketplace">
            <i class="bi bi-arrow-left"></i>
            <span class="d-none d-md-inline">Back to Marketplace</span>
        </a>
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

    {{-- DESKTOP --}}
    <div class="desktop-table">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Farmer</th>
                    <th>Kilos</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Ordered At</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                @forelse($orders as $o)
                    @php
                        $st = strtolower($o->status ?? 'pending');
                        $deliverySt = strtolower($o->delivery_status ?? 'pending');

                        $expectedDelivery = !empty($o->expected_delivery_at)
                            ? \Carbon\Carbon::parse($o->expected_delivery_at, 'UTC')
                                ->timezone('Asia/Manila')
                            : null;

                        $displayStage = match (true) {
                            $st === 'cancelled' => 'CANCELLED',
                            $st === 'completed' => 'COMPLETED',
                            $deliverySt === 'delivered' => 'DELIVERED',
                            $deliverySt === 'out_for_delivery' => 'OUT FOR DELIVERY',
                            $st === 'approved' && $expectedDelivery !== null => 'SCHEDULED',
                            $st === 'approved' => 'PREPARING',
                            default => strtoupper($st),
                        };

                        $displayTotal = (float) (
                            $o->grand_total
                            ?? $o->total_price
                            ?? 0
                        );
                    @endphp

                    <tr>
                        <td class="fw-bold">#{{ $o->id }}</td>

                        <td class="fw-semibold">
                            {{ $o->product->name ?? '-' }}
                        </td>

                        <td>
                            {{ $o->farmer->fullname ?? $o->farmer->username ?? '-' }}
                        </td>

                        <td>
                            {{ number_format((float)$o->quantity_kilos, 2) }} kg
                        </td>

                        <td class="fw-bold text-success">
                            ₱{{ number_format($displayTotal, 2) }}
                        </td>

                        <td>
                            <span class="status-pill {{ $st }}">
                                {{ $displayStage }}
                            </span>

                            @if($expectedDelivery && !in_array($deliverySt, ['out_for_delivery', 'delivered'], true))
                                <div class="small text-primary mt-1">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ $expectedDelivery->format('M d, h:i A') }}
                                </div>
                            @endif
                        </td>

                        <td>
                            {{ $o->created_at?->format('M d, Y h:i A') ?? '-' }}
                        </td>

                        <td>
                            <div class="d-flex gap-2 flex-wrap">

                                <a href="{{ route('resident.orders.show', $o->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-geo-alt"></i>
                                    Track
                                </a>

                                <a href="{{ route('resident.orders.invoice.show', $o->id) }}"
                                   class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-receipt"></i>
                                    Invoice
                                </a>

                                @if(
                                    $st === 'approved' &&
                                    $deliverySt === 'delivered' &&
                                    !empty($o->proof_of_delivery_path)
                                )
                                    <form method="POST"
                                          action="{{ route('resident.orders.received', $o->id) }}">
                                        @csrf

                                        <button type="submit"
                                                class="btn btn-sm btn-success"
                                                onclick="return confirm('Confirm that you already received this order?')">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Order Received
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            No orders yet.
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>
    </div>

    {{-- MOBILE --}}
    <section class="mobile-orders">

        @forelse($orders as $o)
            @php
                $st = strtolower($o->status ?? 'pending');
                $deliverySt = strtolower($o->delivery_status ?? 'pending');

                $expectedDelivery = !empty($o->expected_delivery_at)
                    ? \Carbon\Carbon::parse($o->expected_delivery_at, 'UTC')
                        ->timezone('Asia/Manila')
                    : null;

                $displayStage = match (true) {
                    $st === 'cancelled' => 'CANCELLED',
                    $st === 'completed' => 'COMPLETED',
                    $deliverySt === 'delivered' => 'DELIVERED',
                    $deliverySt === 'out_for_delivery' => 'OUT FOR DELIVERY',
                    $st === 'approved' && $expectedDelivery !== null => 'SCHEDULED',
                    $st === 'approved' => 'PREPARING',
                    default => strtoupper($st),
                };

                $displayTotal = (float) (
                    $o->grand_total
                    ?? $o->total_price
                    ?? 0
                );
            @endphp

            <article class="order-card">

                <div class="order-card-head">
                    <div>
                        <div class="order-id">Order #{{ $o->id }}</div>

                        <div class="product-name">
                            {{ $o->product->name ?? '-' }}
                        </div>

                        <div class="farmer">
                            <i class="bi bi-person"></i>
                            Farmer:
                            {{ $o->farmer->fullname ?? $o->farmer->username ?? '-' }}
                        </div>
                    </div>

                    <span class="status-pill {{ $st }}">
                        {{ $displayStage }}
                    </span>
                </div>

                <div class="info-grid">
                    <div class="info-box">
                        <div class="info-label">Quantity</div>
                        <div class="info-value">
                            {{ number_format((float)$o->quantity_kilos, 2) }} kg
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-label">Order Type</div>
                        <div class="info-value">
                            {{ ucfirst($o->fulfillment_type ?? '-') }}
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-label">Total</div>
                        <div class="info-value text-success">
                            ₱{{ number_format($displayTotal, 2) }}
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-label">Ordered</div>
                        <div class="info-value">
                            {{ $o->created_at?->format('M d, h:i A') ?? '-' }}
                        </div>
                    </div>

                    @if($expectedDelivery)
                        <div class="info-box" style="grid-column:1/-1;">
                            <div class="info-label">Expected Delivery</div>
                            <div class="info-value text-primary">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ $expectedDelivery->format('M d, Y h:i A') }}
                            </div>
                        </div>
                    @endif
                </div>

                @if($st === 'pending')
                    <div class="workflow-note note-pending">
                        <i class="bi bi-hourglass-split me-1"></i>
                        Waiting for the farmer to approve your order.
                    </div>

                @elseif($st === 'approved' && $deliverySt === 'delivered')
                    <div class="workflow-note note-completed">
                        <i class="bi bi-house-check-fill me-1"></i>
                        The seller marked this order as delivered.
                        Confirm receipt only after you actually receive it.
                    </div>

                @elseif($st === 'approved' && $deliverySt === 'out_for_delivery')
                    <div class="workflow-note note-approved">
                        <i class="bi bi-truck me-1"></i>
                        Your order is now <strong>OUT FOR DELIVERY</strong>.
                    </div>

                @elseif($st === 'approved' && $expectedDelivery)
                    <div class="workflow-note note-approved">
                        <i class="bi bi-calendar-event me-1"></i>
                        Your order is approved and being prepared.
                        Expected delivery:
                        <strong>{{ $expectedDelivery->format('M d, Y h:i A') }}</strong>.
                    </div>

                @elseif($st === 'approved')
                    <div class="workflow-note note-approved">
                        <i class="bi bi-box2 me-1"></i>
                        Your order is approved and being prepared.
                        The seller has not set the expected delivery schedule yet.
                    </div>

                @elseif($st === 'completed')
                    <div class="workflow-note note-completed">
                        <i class="bi bi-check-circle-fill me-1"></i>
                        You confirmed that this order was received.
                    </div>
                @endif

                <div class="actions">

                    <a href="{{ route('resident.orders.show', $o->id) }}"
                       class="btn btn-outline-primary">
                        <i class="bi bi-geo-alt"></i>
                        Track
                    </a>

                    <a href="{{ route('resident.orders.invoice.show', $o->id) }}"
                       class="btn btn-outline-success">
                        <i class="bi bi-receipt"></i>
                        View Invoice
                    </a>

                    <a href="{{ route('resident.orders.invoice.download', $o->id) }}"
                       class="btn btn-outline-secondary">
                        <i class="bi bi-file-earmark-pdf"></i>
                        PDF
                    </a>

                    @if(
                        $st === 'approved' &&
                        $deliverySt === 'delivered' &&
                        !empty($o->proof_of_delivery_path)
                    )
                        <form method="POST"
                              action="{{ route('resident.orders.received', $o->id) }}"
                              style="grid-column:1/-1;">
                            @csrf

                            <button type="submit"
                                    class="btn btn-success receive-btn"
                                    onclick="return confirm('Confirm that you already received this order? This will mark the order as COMPLETED.')">
                                <i class="bi bi-check-circle-fill"></i>
                                I Received My Order
                            </button>
                        </form>
                    @endif

                </div>

            </article>

        @empty
            <div class="order-card text-center text-muted py-5">
                <i class="bi bi-bag-x fs-1"></i>
                <div class="mt-2">No orders yet.</div>
            </div>
        @endforelse

    </section>

    <div class="pagination-wrap">
        {{ $orders->links() }}
    </div>

</main>

</body>
</html>