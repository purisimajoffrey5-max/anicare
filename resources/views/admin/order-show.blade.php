<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order #{{ $order->id }} | ANI-CARE Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        *{
            box-sizing:border-box;
        }

        html,
        body{
            margin:0;
            min-height:100%;
            font-family:"Segoe UI",sans-serif;
        }

        body{
            background:#f4f7f6;
            color:#20252b;
        }

        .topbar{
            background:#198754;
            color:#fff;
        }

        .topbar-inner{
            min-height:60px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
            padding:9px 14px;
        }

        .brand{
            font-size:18px;
            font-weight:850;
        }

        .page{
            max-width:850px;
            margin:auto;
            padding:24px 14px 60px;
        }

        .title{
            color:#198754;
            font-size:30px;
            font-weight:850;
            margin:0;
        }

        .subtitle{
            color:#6c757d;
            margin-top:4px;
            margin-bottom:18px;
        }

        .card-box{
            background:#fff;
            border:1px solid #dfe5e2;
            border-radius:16px;
            box-shadow:0 5px 18px rgba(15,23,42,.05);
            padding:22px;
        }

        .section-title{
            font-size:24px;
            font-weight:850;
            margin:0 0 18px;
        }

        .summary{
            display:grid;
            grid-template-columns:210px minmax(0,1fr);
            gap:14px 20px;
        }

        .label{
            color:#6b7280;
            font-weight:700;
        }

        .value{
            overflow-wrap:anywhere;
        }

        .total{
            color:#198754;
            font-weight:850;
            font-size:18px;
        }

        .status-pill{
            display:inline-flex;
            align-items:center;
            padding:6px 10px;
            border-radius:999px;
            font-size:11px;
            font-weight:850;
        }

        .status-pending{
            background:#fff3cd;
            color:#755b00;
        }

        .status-active{
            background:#e8f1ff;
            color:#174ea6;
        }

        .status-success{
            background:#d1e7dd;
            color:#0f5132;
        }

        .status-cancelled{
            background:#eceff1;
            color:#495057;
        }

        .expected-box{
            margin-top:18px;
            padding:13px 14px;
            border:1px solid #cfe2ff;
            border-radius:12px;
            background:#eef6ff;
            color:#174ea6;
        }

        .proof{
            margin-top:18px;
            padding:14px;
            border:1px solid #ccebd8;
            border-radius:12px;
            background:#f1fbf5;
        }

        .proof-title{
            font-weight:800;
            color:#146c43;
        }

        .proof img{
            width:100%;
            max-height:420px;
            object-fit:contain;
            border-radius:10px;
            margin-top:10px;
            background:#fff;
            border:1px solid #dfe5e2;
        }

        .actions{
            display:grid;
            grid-template-columns:repeat(2,minmax(0,1fr));
            gap:9px;
            margin-top:18px;
        }

        .actions .btn,
        .actions form,
        .actions form .btn{
            width:100%;
        }

        .actions .btn{
            min-height:44px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:5px;
            border-radius:10px;
            font-weight:700;
        }

        .receive-wrap{
            grid-column:1/-1;
        }

        @media(max-width:767px){
            .page{
                padding:18px 10px 50px;
            }

            .brand{
                font-size:15px;
            }

            .title{
                font-size:25px;
            }

            .card-box{
                padding:16px;
            }

            .summary{
                grid-template-columns:1fr 1.2fr;
                gap:11px 12px;
            }
        }

        @media(max-width:390px){
            .topbar .btn span{
                display:none;
            }

            .actions{
                grid-template-columns:1fr;
            }

            .receive-wrap{
                grid-column:auto;
            }
        }
    </style>
</head>

<body>

@php
    $displayTotal = (float) (
        $order->grand_total
        ?? $order->total_price
        ?? 0
    );

    $paymentStatus = strtolower(
        (string) ($order->payment_status ?? 'unpaid')
    );

    $orderStatus = strtolower(
        (string) ($orderStatus ?? $order->status ?? 'pending')
    );

    $deliveryStatus = strtolower(
        (string) ($deliveryStatus ?? $order->delivery_status ?? 'pending')
    );

    $displayStage = match (true) {
        $orderStatus === 'cancelled' => 'CANCELLED',
        $orderStatus === 'completed' => 'COMPLETED',
        $deliveryStatus === 'delivered' => 'DELIVERED',
        $deliveryStatus === 'out_for_delivery' => 'OUT FOR DELIVERY',
        $orderStatus === 'approved' && !empty($expectedDelivery) => 'SCHEDULED',
        $orderStatus === 'approved' => 'PREPARING',
        default => strtoupper($orderStatus),
    };

    $stageClass = match ($displayStage) {
        'PENDING' => 'status-pending',
        'PREPARING', 'SCHEDULED', 'OUT FOR DELIVERY' => 'status-active',
        'DELIVERED', 'COMPLETED' => 'status-success',
        'CANCELLED' => 'status-cancelled',
        default => 'status-active',
    };
@endphp

<header class="topbar">
    <div class="topbar-inner">

        <div class="brand">
            ANI-CARE | Admin
        </div>

        <a
            href="{{ route('admin.orders.index') }}"
            class="btn btn-outline-light btn-sm"
        >
            <i class="bi bi-arrow-left"></i>
            <span>My Orders</span>
        </a>

    </div>
</header>


<main class="page">

    <h1 class="title">
        Order #{{ $order->id }}
    </h1>

    <div class="subtitle">
        View order information, invoice, delivery status, and proof.
    </div>


    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif


    <section class="card-box">

        <h2 class="section-title">
            Order Summary
        </h2>


        <div class="summary">

            <div class="label">
                Product
            </div>

            <div class="value">
                {{ $order->product?->name ?? '-' }}
            </div>


            <div class="label">
                Farmer
            </div>

            <div class="value">
                {{ $order->farmer?->fullname
                    ?? $order->farmer?->username
                    ?? '-' }}
            </div>


            <div class="label">
                Quantity
            </div>

            <div class="value">
                {{ number_format((float)$order->quantity_kilos,2) }} kg
            </div>


            <div class="label">
                Total
            </div>

            <div class="value total">
                ₱{{ number_format($displayTotal,2) }}
            </div>


            <div class="label">
                Payment
            </div>

            <div class="value">
                {{ strtoupper($paymentStatus) }}
            </div>


            <div class="label">
                Fulfillment
            </div>

            <div class="value">
                {{ ucfirst($order->fulfillment_type ?? '-') }}
            </div>


            <div class="label">
                Order Status
            </div>

            <div class="value">
                {{ strtoupper($orderStatus) }}
            </div>


            <div class="label">
                Delivery Status
            </div>

            <div class="value">
                <span class="status-pill {{ $stageClass }}">
                    {{ $displayStage }}
                </span>
            </div>


            @if(($order->fulfillment_type ?? '') === 'delivery')

                <div class="label">
                    Delivery Address
                </div>

                <div class="value">
                    {{ $order->delivery_address ?? '-' }}
                </div>

            @else

                <div class="label">
                    Pickup Address
                </div>

                <div class="value">
                    {{ $order->pickup_address ?? '-' }}
                </div>

            @endif

        </div>


        {{-- EXPECTED DELIVERY --}}
        @if(!empty($expectedDelivery))

            <div class="expected-box">

                <i class="bi bi-calendar-event-fill me-1"></i>

                Expected Delivery:

                <strong>
                    {{ $expectedDelivery->format('F d, Y h:i A') }}
                </strong>

            </div>

        @endif


        {{-- PROOF OF DELIVERY --}}
        @if(
            $deliveryStatus === 'delivered' &&
            !empty($order->proof_of_delivery_path)
        )

            <div class="proof">

                <div class="proof-title">
                    <i class="bi bi-camera-fill me-1"></i>
                    Proof of Delivery
                </div>

                <img
                    src="{{ asset('storage/'.$order->proof_of_delivery_path) }}"
                    alt="Proof of Delivery"
                >

            </div>

        @endif


        {{-- ACTIONS --}}
        <div class="actions">

            <a
                href="{{ route('admin.orders.invoice.show', $order->id) }}"
                class="btn btn-outline-success"
            >
                <i class="bi bi-receipt"></i>
                View Invoice
            </a>


            <a
                href="{{ route('admin.orders.invoice.download', $order->id) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-file-earmark-pdf"></i>
                Download PDF
            </a>


            @if(
                $orderStatus === 'approved' &&
                $deliveryStatus === 'delivered' &&
                !empty($order->proof_of_delivery_path)
            )

                <form
                    method="POST"
                    action="{{ route('admin.orders.received', $order->id) }}"
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

    </section>

</main>

</body>
</html>