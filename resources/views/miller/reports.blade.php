<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Milling Reports | Miller</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <style>
        *{
            box-sizing:border-box;
        }

        html,body{
            min-height:100%;
            margin:0;
            font-family:'Segoe UI',sans-serif;
        }

        body{
            background:#f5f7fb;
            color:#1f2937;
        }

        .topbar{
            background:#198754;
            color:#fff;
            box-shadow:0 3px 12px rgba(0,0,0,.1);
        }

        .topbar-inner{
            max-width:1150px;
            min-height:62px;
            margin:0 auto;
            padding:10px 14px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
        }

        .brand{
            font-weight:850;
            font-size:17px;
        }

        .page{
            max-width:1150px;
            margin:0 auto;
            padding:22px 12px 60px;
        }

        .page-title{
            margin:0;
            color:#198754;
            font-size:29px;
            font-weight:850;
        }

        .subtitle{
            color:#6b7280;
            margin-top:3px;
            margin-bottom:18px;
        }

        .soft-card{
            background:#fff;
            border:1px solid #e1e7e4;
            border-radius:16px;
            box-shadow:0 5px 18px rgba(15,23,42,.05);
        }

        .table-wrap{
            overflow-x:auto;
            -webkit-overflow-scrolling:touch;
        }

        table{
            min-width:920px;
        }

        .requester-name{
            font-weight:750;
        }

        .requester-role{
            margin-top:2px;
            color:#6b7280;
            font-size:11px;
            text-transform:capitalize;
        }

        .status-pill,
        .payment-pill{
            display:inline-flex;
            align-items:center;
            border-radius:999px;
            padding:5px 9px;
            font-size:10px;
            font-weight:800;
        }

        .status-completed{
            background:#d1e7dd;
            color:#0f5132;
        }

        .payment-paid{
            background:#d1e7dd;
            color:#0f5132;
        }

        .payment-unpaid{
            background:#fff3cd;
            color:#664d03;
        }

        .amount{
            color:#198754;
            font-weight:800;
        }

        .mobile-list{
            display:none;
        }

        .mobile-card{
            background:#fff;
            border:1px solid #e1e7e4;
            border-radius:15px;
            padding:14px;
            margin-bottom:11px;
            box-shadow:0 4px 14px rgba(15,23,42,.04);
        }

        .mobile-head{
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            gap:10px;
            margin-bottom:12px;
        }

        .mobile-id{
            color:#6b7280;
            font-size:11px;
        }

        .mobile-grid{
            display:grid;
            grid-template-columns:repeat(2,minmax(0,1fr));
            gap:8px;
        }

        .info-box{
            background:#f8faf9;
            border:1px solid #edf1ef;
            border-radius:10px;
            padding:9px;
        }

        .info-label{
            color:#6b7280;
            font-size:10px;
        }

        .info-value{
            margin-top:2px;
            font-size:12px;
            font-weight:700;
            overflow-wrap:anywhere;
        }

        @media(max-width:767px){
            .desktop-table{
                display:none;
            }

            .mobile-list{
                display:block;
            }

            .page{
                padding:17px 10px 50px;
            }

            .page-title{
                font-size:25px;
            }

            .brand{
                font-size:14px;
            }
        }

        @media(max-width:390px){
            .mobile-grid{
                grid-template-columns:1fr;
            }
        }
    </style>
</head>

<body>

<header class="topbar">
    <div class="topbar-inner">

        <div class="brand">
            <i class="bi bi-gear-wide-connected me-1"></i>
            ANI-CARE | Miller
        </div>

        <a
            href="{{ route('miller.dashboard') }}"
            class="btn btn-warning btn-sm"
        >
            <i class="bi bi-arrow-left"></i>
            Back
        </a>

    </div>
</header>


<main class="page">

    <h1 class="page-title">
        Milling Reports
    </h1>

    <div class="subtitle">
        Completed milling transactions handled by your Miller account.
    </div>


    {{-- =========================================================
         DESKTOP TABLE
    ========================================================== --}}
    <section class="soft-card desktop-table">

        <div class="table-wrap">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Requester</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th>Scheduled</th>
                        <th>Completed</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($reports as $r)

                    @php
                        /*
                        |--------------------------------------------------------------------------
                        | REQUESTER
                        |--------------------------------------------------------------------------
                        |
                        | New requests:
                        | $r->requester
                        |
                        | Old Farmer requests:
                        | $r->farmer
                        |
                        */
                        $requester =
                            $r->requester
                            ?? $r->farmer
                            ?? null;

                        $requesterId =
                            $r->requester_id
                            ?? $r->farmer_id
                            ?? $r->user_id
                            ?? null;

                        $requesterName =
                            $requester?->fullname
                            ?? $requester?->username
                            ?? (
                                $requesterId
                                    ? 'User #'.$requesterId
                                    : 'Unknown Requester'
                            );

                        $requesterRole =
                            strtolower(
                                trim(
                                    (string) (
                                        $r->requester_role
                                        ?? ''
                                    )
                                )
                            );

                        if ($requesterRole === '') {
                            $requesterRole =
                                !empty($r->farmer_id)
                                    ? 'farmer'
                                    : 'requester';
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | PRODUCT + QUANTITY
                        |--------------------------------------------------------------------------
                        */

                        $product =
                            $r->product_type
                            ?? $r->product
                            ?? $r->rice_type
                            ?? $r->crop_type
                            ?? $r->palay_type
                            ?? 'Palay / Milling Request';

                        $kilos =
                            $r->quantity_kilos
                            ?? $r->quantity_kg
                            ?? $r->kilos
                            ?? $r->quantity
                            ?? $r->weight_kg
                            ?? 0;


                        /*
                        |--------------------------------------------------------------------------
                        | PAYMENT / TOTAL
                        |--------------------------------------------------------------------------
                        */

                        $paymentStatus =
                            strtolower(
                                (string) (
                                    $r->payment_status
                                    ?? 'unpaid'
                                )
                            );

                        $grandTotal =
                            (float) (
                                $r->grand_total
                                ?? $r->total_amount
                                ?? 0
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | DATE/TIME
                        |--------------------------------------------------------------------------
                        */

                        $scheduled =
                            !empty($r->scheduled_at)
                                ? \Carbon\Carbon::parse($r->scheduled_at)
                                    ->timezone('Asia/Manila')
                                    ->format('M d, Y h:i A')
                                : '-';

                        $completedValue =
                            $r->completed_at
                            ?? $r->requester_confirmed_at
                            ?? $r->updated_at
                            ?? null;

                        $completed =
                            $completedValue
                                ? \Carbon\Carbon::parse($completedValue)
                                    ->timezone('Asia/Manila')
                                    ->format('M d, Y h:i A')
                                : '-';
                    @endphp

                    <tr>
                        <td>
                            #{{ $r->id }}
                        </td>

                        <td>
                            <div class="requester-name">
                                {{ $requesterName }}
                            </div>

                            <div class="requester-role">
                                {{ $requesterRole }}
                            </div>
                        </td>

                        <td>
                            {{ $product }}
                        </td>

                        <td>
                            {{ number_format((float)$kilos,2) }} kg
                        </td>

                        <td>
                            <span class="payment-pill {{ $paymentStatus === 'paid' ? 'payment-paid' : 'payment-unpaid' }}">
                                {{ strtoupper($paymentStatus) }}
                            </span>
                        </td>

                        <td class="amount">
                            ₱{{ number_format($grandTotal,2) }}
                        </td>

                        <td>
                            {{ $scheduled }}
                        </td>

                        <td>
                            {{ $completed }}
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="8"
                            class="text-center text-muted py-5"
                        >
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            No completed milling reports yet.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>
        </div>

    </section>


    {{-- =========================================================
         MOBILE CARDS
    ========================================================== --}}
    <section class="mobile-list">

        @forelse($reports as $r)

            @php
                $requester =
                    $r->requester
                    ?? $r->farmer
                    ?? null;

                $requesterId =
                    $r->requester_id
                    ?? $r->farmer_id
                    ?? $r->user_id
                    ?? null;

                $requesterName =
                    $requester?->fullname
                    ?? $requester?->username
                    ?? (
                        $requesterId
                            ? 'User #'.$requesterId
                            : 'Unknown Requester'
                    );

                $requesterRole =
                    strtolower(
                        trim(
                            (string) (
                                $r->requester_role
                                ?? ''
                            )
                        )
                    );

                if ($requesterRole === '') {
                    $requesterRole =
                        !empty($r->farmer_id)
                            ? 'farmer'
                            : 'requester';
                }

                $product =
                    $r->product_type
                    ?? $r->product
                    ?? $r->rice_type
                    ?? $r->crop_type
                    ?? $r->palay_type
                    ?? 'Palay / Milling Request';

                $kilos =
                    $r->quantity_kilos
                    ?? $r->quantity_kg
                    ?? $r->kilos
                    ?? $r->quantity
                    ?? $r->weight_kg
                    ?? 0;

                $paymentStatus =
                    strtolower(
                        (string) (
                            $r->payment_status
                            ?? 'unpaid'
                        )
                    );

                $grandTotal =
                    (float) (
                        $r->grand_total
                        ?? $r->total_amount
                        ?? 0
                    );

                $scheduled =
                    !empty($r->scheduled_at)
                        ? \Carbon\Carbon::parse($r->scheduled_at)
                            ->timezone('Asia/Manila')
                            ->format('M d, Y h:i A')
                        : '-';

                $completedValue =
                    $r->completed_at
                    ?? $r->requester_confirmed_at
                    ?? $r->updated_at
                    ?? null;

                $completed =
                    $completedValue
                        ? \Carbon\Carbon::parse($completedValue)
                            ->timezone('Asia/Manila')
                            ->format('M d, Y h:i A')
                        : '-';
            @endphp

            <article class="mobile-card">

                <div class="mobile-head">

                    <div>
                        <div class="mobile-id">
                            Milling Request #{{ $r->id }}
                        </div>

                        <div class="requester-name">
                            {{ $requesterName }}
                        </div>

                        <div class="requester-role">
                            {{ $requesterRole }}
                        </div>
                    </div>

                    <span class="status-pill status-completed">
                        COMPLETED
                    </span>

                </div>


                <div class="mobile-grid">

                    <div class="info-box">
                        <div class="info-label">Product</div>
                        <div class="info-value">
                            {{ $product }}
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-label">Quantity</div>
                        <div class="info-value">
                            {{ number_format((float)$kilos,2) }} kg
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-label">Payment</div>
                        <div class="info-value">
                            <span class="payment-pill {{ $paymentStatus === 'paid' ? 'payment-paid' : 'payment-unpaid' }}">
                                {{ strtoupper($paymentStatus) }}
                            </span>
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-label">Total</div>
                        <div class="info-value amount">
                            ₱{{ number_format($grandTotal,2) }}
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-label">Scheduled</div>
                        <div class="info-value">
                            {{ $scheduled }}
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-label">Completed</div>
                        <div class="info-value">
                            {{ $completed }}
                        </div>
                    </div>

                </div>

            </article>

        @empty

            <div class="soft-card text-center text-muted p-5">
                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                No completed milling reports yet.
            </div>

        @endforelse

    </section>


    @if($reports->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $reports->links() }}
        </div>
    @endif

</main>

</body>
</html>