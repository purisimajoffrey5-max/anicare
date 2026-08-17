<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Order #{{ $order->id }} | Resident</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

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
            padding: 22px 14px 70px;
        }

        .page-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            margin-bottom: 18px;
        }

        .page-title {
            margin: 0;
            color: #198754;
            font-weight: 850;
            font-size: clamp(28px, 5vw, 40px);
            line-height: 1.05;
        }

        .page-subtitle {
            margin-top: 6px;
            color: #6c757d;
            font-size: 14px;
        }

        .back-btn {
            border-radius: 10px;
            white-space: nowrap;
        }

        .card-box {
            background: #fff;
            border: 1px solid #dfe5e2;
            border-radius: 16px;
            box-shadow: 0 5px 18px rgba(15,23,42,.05);
            padding: 22px;
            height: 100%;
        }

        .section-title {
            margin: 0 0 18px;
            font-size: 26px;
            font-weight: 850;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: 210px minmax(0, 1fr);
            gap: 14px 22px;
        }

        .summary-label {
            color: #5f6368;
            font-weight: 750;
        }

        .summary-value {
            overflow-wrap: anywhere;
        }

        .summary-total {
            color: #198754;
            font-weight: 850;
            font-size: 18px;
        }

        .tracking-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
        }

        .tracking-title {
            margin: 0;
            font-weight: 850;
            font-size: 26px;
        }

        .stage-badge {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 850;
            white-space: nowrap;
        }

        .stage-pending {
            background: #fff3cd;
            color: #755b00;
        }

        .stage-preparing,
        .stage-scheduled {
            background: #e8f1ff;
            color: #174ea6;
        }

        .stage-out {
            background: #dbeafe;
            color: #174ea6;
        }

        .stage-delivered,
        .stage-completed {
            background: #d1e7dd;
            color: #0f5132;
        }

        .stage-cancelled {
            background: #eceff1;
            color: #495057;
        }

        .tracking-note {
            color: #68717b;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .expected-card {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            padding: 14px;
            margin-bottom: 18px;
            border: 1px solid #cfe2ff;
            border-radius: 13px;
            background: #eef6ff;
            color: #174ea6;
        }

        .expected-card i {
            font-size: 22px;
        }

        .expected-label {
            font-size: 11px;
            font-weight: 700;
            opacity: .85;
        }

        .expected-value {
            margin-top: 2px;
            font-size: 17px;
            font-weight: 850;
        }

        .timeline {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 8px;
            margin: 18px 0;
        }

        .step {
            text-align: center;
            position: relative;
        }

        .step::before {
            content: "";
            position: absolute;
            top: 17px;
            left: -50%;
            width: 100%;
            height: 3px;
            background: #dfe5e2;
        }

        .step:first-child::before {
            display: none;
        }

        .step.done::before {
            background: #198754;
        }

        .step-dot {
            width: 36px;
            height: 36px;
            margin: 0 auto 6px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: #e5e9e7;
            color: #6c757d;
            position: relative;
            z-index: 1;
        }

        .step.done .step-dot {
            background: #198754;
            color: #fff;
        }

        .step-label {
            font-size: 10px;
            font-weight: 800;
            color: #68717b;
        }

        #trackMap {
            width: 100%;
            height: 390px;
            border-radius: 14px;
            border: 1px solid #dfe5e2;
        }

        .map-note {
            margin-top: 9px;
            color: #6c757d;
            font-size: 11px;
            line-height: 1.45;
        }

        .map-legend {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .map-legend span {
            padding: 6px 9px;
            border-radius: 999px;
            background: #f1f4f2;
            font-size: 11px;
            font-weight: 700;
        }

        .proof-box {
            margin-top: 18px;
            padding: 14px;
            border: 1px solid #d1e7dd;
            border-radius: 14px;
            background: #f2fbf6;
        }

        .proof-img {
            width: 100%;
            max-height: 380px;
            object-fit: contain;
            border-radius: 12px;
            border: 1px solid #dfe5e2;
            background: #fff;
            margin-top: 10px;
        }

        .received-form {
            margin-top: 18px;
        }

        .received-btn {
            width: 100%;
            min-height: 52px;
            border-radius: 12px;
            font-weight: 850;
        }

        @media (max-width: 767.98px) {
            .page {
                padding: 18px 12px 60px;
            }

            .page-head {
                display: grid;
                grid-template-columns: 1fr;
            }

            .back-btn {
                width: 100%;
            }

            .summary-grid {
                grid-template-columns: 1fr 1.15fr;
                gap: 12px;
            }

            .card-box {
                padding: 18px;
            }

            .timeline {
                overflow-x: auto;
                grid-template-columns: repeat(4, minmax(74px, 1fr));
            }

            #trackMap {
                height: 300px;
            }
        }
    </style>
</head>

<body>

@php
    /*
    |--------------------------------------------------------------------------
    | SELF-CONTAINED TRACKING VALUES
    |--------------------------------------------------------------------------
    |
    | These are calculated here so this Blade will not crash even if the
    | controller has not yet been updated to pass $expectedDelivery,
    | $deliveryStatus, $deliveryStage, etc.
    |
    */

    $orderStatus = strtolower((string) ($order->status ?? 'pending'));

    $deliveryStatus = strtolower(
        (string) ($order->delivery_status ?? 'pending')
    );

    $expectedDelivery = null;

    if (!empty($order->expected_delivery_at)) {
        try {
            $expectedDelivery = \Carbon\Carbon::parse(
                $order->expected_delivery_at,
                'UTC'
            )->timezone('Asia/Manila');
        } catch (\Throwable $e) {
            $expectedDelivery = null;
        }
    }

    $displayTotal = (float) (
        $order->grand_total
        ?? $order->total_price
        ?? 0
    );

    $deliveryStage = match (true) {
        $orderStatus === 'cancelled' => 'CANCELLED',
        $orderStatus === 'completed' => 'COMPLETED',
        $deliveryStatus === 'delivered' => 'DELIVERED',
        $deliveryStatus === 'out_for_delivery' => 'OUT FOR DELIVERY',
        $orderStatus === 'approved' && $expectedDelivery !== null => 'SCHEDULED',
        $orderStatus === 'approved' => 'PREPARING',
        default => strtoupper($orderStatus),
    };

    $deliveryNote = match (true) {
        $orderStatus === 'pending' =>
            'Your order is waiting for the farmer to approve it.',

        $orderStatus === 'cancelled' =>
            'This order was cancelled.',

        $orderStatus === 'completed' =>
            'Transaction completed. You confirmed that the order was received.',

        $deliveryStatus === 'delivered' =>
            'The seller marked this order as delivered. Confirm receipt only after you actually receive the order.',

        $deliveryStatus === 'out_for_delivery' =>
            'Your seller has dispatched this order. It is now OUT FOR DELIVERY.',

        $orderStatus === 'approved' && $expectedDelivery !== null =>
            'Your order is approved and is still being prepared. Expected delivery: ' .
            $expectedDelivery->format('F d, Y h:i A') . '.',

        $orderStatus === 'approved' =>
            'Your order is approved and is being prepared. The seller has not set an expected delivery schedule yet.',

        default =>
            'Track the current status of your order below.',
    };

    $stageClass = match ($deliveryStage) {
        'PENDING' => 'stage-pending',
        'PREPARING' => 'stage-preparing',
        'SCHEDULED' => 'stage-scheduled',
        'OUT FOR DELIVERY' => 'stage-out',
        'DELIVERED' => 'stage-delivered',
        'COMPLETED' => 'stage-completed',
        'CANCELLED' => 'stage-cancelled',
        default => 'stage-preparing',
    };

    $stageRank = match ($deliveryStage) {
        'PENDING' => 1,
        'PREPARING', 'SCHEDULED' => 2,
        'OUT FOR DELIVERY' => 3,
        'DELIVERED', 'COMPLETED' => 4,
        default => 1,
    };

    /*
    |--------------------------------------------------------------------------
    | SAFE MAP FALLBACKS
    |--------------------------------------------------------------------------
    */

    $safeFarmerLocation = $farmerLocation ?? null;
    $safeBuyerLocation = $buyerLocation ?? null;
    $safeCurrentLocation = $currentLocation ?? null;

    if (
        $safeBuyerLocation === null &&
        $order->delivery_latitude !== null &&
        $order->delivery_longitude !== null
    ) {
        $safeBuyerLocation = [
            'lat' => (float) $order->delivery_latitude,
            'lng' => (float) $order->delivery_longitude,
        ];
    }

    if (
        $safeFarmerLocation === null &&
        $order->farmer &&
        $order->farmer->latitude !== null &&
        $order->farmer->longitude !== null
    ) {
        $safeFarmerLocation = [
            'lat' => (float) $order->farmer->latitude,
            'lng' => (float) $order->farmer->longitude,
        ];
    }

    $safeMapCenterLat =
        $mapCenterLat
        ?? ($safeFarmerLocation['lat'] ?? null)
        ?? ($safeBuyerLocation['lat'] ?? null)
        ?? 18.2760;

    $safeMapCenterLng =
        $mapCenterLng
        ?? ($safeFarmerLocation['lng'] ?? null)
        ?? ($safeBuyerLocation['lng'] ?? null)
        ?? 121.6440;
@endphp

<main class="page">

    <div class="page-head">

        <div>
            <h1 class="page-title">
                Track Order #{{ $order->id }}
            </h1>

            <div class="page-subtitle">
                Delivery status and order progress
            </div>
        </div>

        <a
            href="{{ route('resident.orders.index') }}"
            class="btn btn-outline-success back-btn"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Orders
        </a>

    </div>


    <div class="row g-3">

        {{-- =====================================================
             ORDER SUMMARY
        ====================================================== --}}
        <div class="col-lg-6">

            <section class="card-box">

                <h2 class="section-title">
                    Order Summary
                </h2>

                <div class="summary-grid">

                    <div class="summary-label">Product</div>
                    <div class="summary-value">
                        {{ $order->product?->name ?? '-' }}
                    </div>

                    <div class="summary-label">Farmer</div>
                    <div class="summary-value">
                        {{ $order->farmer?->fullname
                            ?? $order->farmer?->username
                            ?? '-' }}
                    </div>

                    <div class="summary-label">Quantity</div>
                    <div class="summary-value">
                        {{ number_format((float) $order->quantity_kilos, 2) }} kg
                    </div>

                    <div class="summary-label">Total</div>
                    <div class="summary-value summary-total">
                        ₱{{ number_format($displayTotal, 2) }}
                    </div>

                    <div class="summary-label">Fulfillment</div>
                    <div class="summary-value">
                        {{ ucfirst($order->fulfillment_type ?? '-') }}
                    </div>

                    <div class="summary-label">Order Status</div>
                    <div class="summary-value">
                        {{ strtoupper($orderStatus) }}
                    </div>

                    <div class="summary-label">Delivery Status</div>
                    <div class="summary-value">
                        <strong>{{ $deliveryStage }}</strong>
                    </div>

                    @if(($order->fulfillment_type ?? '') === 'delivery')
                        <div class="summary-label">Delivery Address</div>
                        <div class="summary-value">
                            {{ $order->delivery_address ?? '-' }}
                        </div>
                    @else
                        <div class="summary-label">Pickup Address</div>
                        <div class="summary-value">
                            {{ $order->pickup_address ?? '-' }}
                        </div>
                    @endif

                    <div class="summary-label">Order Created</div>
                    <div class="summary-value">
                        {{ $order->created_at
                            ? $order->created_at
                                ->copy()
                                ->timezone('Asia/Manila')
                                ->format('M d, Y h:i A')
                            : '-' }}
                    </div>

                </div>

            </section>

        </div>


        {{-- =====================================================
             DELIVERY TRACKING
        ====================================================== --}}
        <div class="col-lg-6">

            <section class="card-box">

                <div class="tracking-head">

                    <h2 class="tracking-title">
                        Delivery Tracking
                    </h2>

                    <span class="stage-badge {{ $stageClass }}">
                        {{ $deliveryStage }}
                    </span>

                </div>

                <div class="tracking-note">
                    {{ $deliveryNote }}
                </div>


                {{-- EXPECTED DELIVERY --}}
                @if($expectedDelivery)

                    <div class="expected-card">

                        <i class="bi bi-calendar-event-fill"></i>

                        <div>
                            <div class="expected-label">
                                SELLER'S EXPECTED DELIVERY
                            </div>

                            <div class="expected-value">
                                {{ $expectedDelivery->format('F d, Y h:i A') }}
                            </div>
                        </div>

                    </div>

                @endif


                {{-- PROGRESS --}}
                @if($deliveryStage !== 'CANCELLED')

                    <div class="timeline">

                        <div class="step {{ $stageRank >= 1 ? 'done' : '' }}">
                            <div class="step-dot">
                                <i class="bi bi-bag-check"></i>
                            </div>
                            <div class="step-label">
                                ORDERED
                            </div>
                        </div>

                        <div class="step {{ $stageRank >= 2 ? 'done' : '' }}">
                            <div class="step-dot">
                                <i class="bi bi-box2"></i>
                            </div>
                            <div class="step-label">
                                PREPARING
                            </div>
                        </div>

                        <div class="step {{ $stageRank >= 3 ? 'done' : '' }}">
                            <div class="step-dot">
                                <i class="bi bi-truck"></i>
                            </div>
                            <div class="step-label">
                                OUT FOR DELIVERY
                            </div>
                        </div>

                        <div class="step {{ $stageRank >= 4 ? 'done' : '' }}">
                            <div class="step-dot">
                                <i class="bi bi-house-check"></i>
                            </div>
                            <div class="step-label">
                                DELIVERED
                            </div>
                        </div>

                    </div>

                @endif


                {{-- MAP --}}
                @if(($order->fulfillment_type ?? '') === 'delivery')

                    <div id="trackMap"></div>

                    <div class="map-legend">
                        <span>🌾 Seller / Farmer</span>
                        <span>🏠 Delivery Destination</span>
                    </div>

                    <div class="map-note">
                        The map shows the seller location and delivery destination.
                        It is not a live rider GPS tracker.
                    </div>

                @endif


                {{-- PROOF OF DELIVERY --}}
                @if(
                    $deliveryStatus === 'delivered' &&
                    !empty($order->proof_of_delivery_path)
                )

                    <div class="proof-box">

                        <div class="fw-bold text-success">
                            <i class="bi bi-camera-fill me-1"></i>
                            Proof of Delivery
                        </div>

                        <img
                            src="{{ asset('storage/' . $order->proof_of_delivery_path) }}"
                            class="proof-img"
                            alt="Proof of Delivery"
                        >

                    </div>

                @endif


                {{-- BUYER CONFIRMATION --}}
                @if(
                    $orderStatus === 'approved' &&
                    $deliveryStatus === 'delivered' &&
                    !empty($order->proof_of_delivery_path)
                )

                    <form
                        method="POST"
                        action="{{ route('resident.orders.received', $order->id) }}"
                        class="received-form"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="btn btn-success received-btn"
                            onclick="return confirm('Confirm that you have actually received this order? This will complete the transaction.')"
                        >
                            <i class="bi bi-check-circle-fill me-1"></i>
                            I Received My Order
                        </button>

                    </form>

                @endif

            </section>

        </div>

    </div>

</main>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

@if(($order->fulfillment_type ?? '') === 'delivery')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const farmerLocation = @json($safeFarmerLocation);
    const buyerLocation = @json($safeBuyerLocation);

    const map = L.map('trackMap').setView(
        [
            Number(@json($safeMapCenterLat)),
            Number(@json($safeMapCenterLng))
        ],
        13
    );

    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }
    ).addTo(map);

    const points = [];

    if (farmerLocation) {

        const farmerPoint = [
            Number(farmerLocation.lat),
            Number(farmerLocation.lng)
        ];

        L.marker(farmerPoint)
            .addTo(map)
            .bindPopup(
                '<strong>Seller / Farmer</strong><br>Dispatch location'
            );

        points.push(farmerPoint);
    }

    if (buyerLocation) {

        const buyerPoint = [
            Number(buyerLocation.lat),
            Number(buyerLocation.lng)
        ];

        L.marker(buyerPoint)
            .addTo(map)
            .bindPopup(
                '<strong>Buyer</strong><br>Delivery destination'
            );

        points.push(buyerPoint);
    }

    if (farmerLocation && buyerLocation) {

        L.polyline(
            [
                [
                    Number(farmerLocation.lat),
                    Number(farmerLocation.lng)
                ],
                [
                    Number(buyerLocation.lat),
                    Number(buyerLocation.lng)
                ]
            ],
            {
                weight: 4,
                opacity: .65
            }
        ).addTo(map);
    }

    if (points.length > 1) {

        map.fitBounds(
            L.latLngBounds(points),
            {
                padding: [35, 35]
            }
        );

    } else if (points.length === 1) {

        map.setView(points[0], 15);
    }

    setTimeout(function () {
        map.invalidateSize();
    }, 150);
});
</script>
@endif

</body>
</html>