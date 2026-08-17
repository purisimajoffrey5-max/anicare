<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Milling Requests | ANI-CARE Farmer</title>
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
        *{box-sizing:border-box}

        html,body{
            margin:0;
            min-height:100%;
            font-family:"Segoe UI",Tahoma,Geneva,Verdana,sans-serif;
        }

        body{
            background:#f4f7f6;
            color:#1f2937;
        }

        .topbar{
            background:#198754;
            color:#fff;
            box-shadow:0 2px 8px rgba(0,0,0,.08);
        }

        .topbar-inner{
            max-width:1100px;
            margin:0 auto;
            min-height:62px;
            padding:9px 14px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
        }

        .brand{
            font-size:17px;
            font-weight:850;
        }

        .top-actions{
            display:flex;
            gap:7px;
        }

        .page{
            max-width:1100px;
            margin:0 auto;
            padding:22px 14px 60px;
        }

        .page-title{
            color:#198754;
            font-size:30px;
            font-weight:850;
            margin:0;
        }

        .subtitle{
            color:#6b7280;
            margin-top:4px;
            margin-bottom:18px;
        }

        .sync-note{
            display:flex;
            align-items:center;
            gap:6px;
            color:#6b7280;
            font-size:11px;
            margin-bottom:12px;
        }

        .sync-dot{
            width:8px;
            height:8px;
            border-radius:50%;
            background:#198754;
        }

        .request-card{
            background:#fff;
            border:1px solid #e0e7e3;
            border-radius:16px;
            padding:16px;
            margin-bottom:12px;
            box-shadow:0 6px 18px rgba(15,23,42,.045);
        }

        .card-head{
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap:12px;
        }

        .request-id{
            color:#6b7280;
            font-size:10px;
        }

        .miller-name{
            font-size:17px;
            font-weight:850;
            margin-top:2px;
        }

        .miller-label{
            color:#6b7280;
            font-size:10px;
        }

        .status-pill,
        .payment-pill{
            display:inline-flex;
            align-items:center;
            gap:4px;
            padding:6px 10px;
            border-radius:999px;
            font-size:9px;
            font-weight:850;
            white-space:nowrap;
        }

        .st-pending{
            background:#fff3cd;
            color:#705700;
        }

        .st-accepted{
            background:#dbeafe;
            color:#174ea6;
        }

        .st-scheduled{
            background:#e0e7ff;
            color:#3730a3;
        }

        .st-progress{
            background:#cff4fc;
            color:#055160;
        }

        .st-finished{
            background:#d1e7dd;
            color:#0f5132;
        }

        .st-completed{
            background:#ccebd8;
            color:#146c43;
        }

        .st-rejected,
        .st-cancelled{
            background:#eceff1;
            color:#495057;
        }

        .pay-paid{
            background:#d1e7dd;
            color:#0f5132;
        }

        .pay-unpaid{
            background:#fff3cd;
            color:#705700;
        }

        .info-grid{
            display:grid;
            grid-template-columns:repeat(4,minmax(0,1fr));
            gap:8px;
            margin-top:14px;
        }

        .info{
            background:#f8faf9;
            border:1px solid #edf1ef;
            border-radius:11px;
            padding:10px;
            min-width:0;
        }

        .label{
            color:#6b7280;
            font-size:9px;
        }

        .value{
            margin-top:2px;
            font-size:12px;
            font-weight:750;
            overflow-wrap:anywhere;
        }

        .schedule-box{
            margin-top:11px;
            padding:11px 12px;
            border:1px solid #cfe2ff;
            border-radius:11px;
            background:#eef6ff;
            color:#174ea6;
            font-size:11px;
        }

        .status-note{
            margin-top:10px;
            padding:10px 11px;
            border-radius:10px;
            font-size:10px;
        }

        .note-pending{
            background:#fff9e6;
            border:1px solid #ffe69c;
            color:#6f5700;
        }

        .note-active{
            background:#eef6ff;
            border:1px solid #cfe2ff;
            color:#174ea6;
        }

        .note-success{
            background:#effaf4;
            border:1px solid #ccebd8;
            color:#146c43;
        }

        .note-muted{
            background:#f3f4f6;
            border:1px solid #e5e7eb;
            color:#4b5563;
        }

        .progress-line{
            display:grid;
            grid-template-columns:repeat(6,minmax(70px,1fr));
            gap:6px;
            margin-top:13px;
            overflow-x:auto;
            padding-bottom:3px;
        }

        .step{
            text-align:center;
            color:#9ca3af;
            font-size:9px;
            min-width:70px;
        }

        .step-dot{
            width:28px;
            height:28px;
            margin:0 auto 4px;
            border-radius:50%;
            background:#e5e7eb;
            color:#6b7280;
            display:grid;
            place-items:center;
        }

        .step.done{
            color:#198754;
            font-weight:800;
        }

        .step.done .step-dot{
            background:#198754;
            color:#fff;
        }

        .empty{
            background:#fff;
            border:1px solid #e0e7e3;
            border-radius:16px;
            padding:45px 20px;
            text-align:center;
            color:#6b7280;
        }

        .payment-warning{
            margin-top:10px;
            padding:10px 12px;
            border-radius:11px;
            background:#fff3cd;
            border:1px solid #ffe69c;
            color:#705700;
            font-size:11px;
        }

        .payment-ok{
            margin-top:10px;
            padding:10px 12px;
            border-radius:11px;
            background:#d1e7dd;
            border:1px solid #badbcc;
            color:#0f5132;
            font-size:11px;
        }

        .proof-box{
            margin-top:10px;
            padding:11px;
            border-radius:12px;
            background:#effaf4;
            border:1px solid #ccebd8;
        }

        .proof-title{
            color:#146c43;
            font-size:12px;
            font-weight:850;
            margin-bottom:8px;
        }

        .proof-img{
            display:block;
            width:100%;
            max-height:320px;
            object-fit:contain;
            border-radius:10px;
            background:#fff;
            border:1px solid #dfe8e2;
        }

        .confirm-area{
            margin-top:10px;
        }

        .confirm-area .btn{
            width:100%;
            min-height:44px;
            border-radius:10px;
            font-weight:800;
        }

        @media(max-width:767.98px){
            .page{
                padding:16px 10px 50px;
            }

            .page-title{
                font-size:25px;
            }

            .brand{
                font-size:14px;
            }

            .top-actions .btn span{
                display:none;
            }

            .info-grid{
                grid-template-columns:repeat(2,minmax(0,1fr));
            }

            .request-card{
                padding:13px;
            }
        }

        @media(max-width:390px){
            .info-grid{
                grid-template-columns:1fr;
            }
        }
    </style>
</head>

<body>

<header class="topbar">
    <div class="topbar-inner">

        <div class="brand">
            ANI-CARE | Farmer
        </div>

        <div class="top-actions">

            <a
                href="{{ route('farmer.milling.create') }}"
                class="btn btn-light btn-sm"
            >
                <i class="bi bi-plus-circle"></i>
                <span>New Request</span>
            </a>

            <a
                href="{{ route('farmer.dashboard') }}"
                class="btn btn-warning btn-sm"
            >
                <i class="bi bi-arrow-left"></i>
                <span>Back</span>
            </a>

        </div>

    </div>
</header>


<main class="page">

    <h1 class="page-title">
        My Milling Requests
    </h1>

    <div class="subtitle">
        Track your milling request, payment, schedule, and processing progress.
    </div>

    <div class="sync-note">
        <span class="sync-dot"></span>
        This page automatically refreshes every 8 seconds to show Miller updates.
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


    @forelse($requests as $r)

        @php
            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            |
            | The status shown here comes DIRECTLY from milling_requests.status.
            | Setting a schedule must show SCHEDULED, not COMPLETED.
            |
            */

            $status =
                strtolower(
                    trim(
                        (string) ($r->status ?? 'pending')
                    )
                );

            $paymentStatus =
                strtolower(
                    (string) ($r->payment_status ?? 'unpaid')
                );

            $miller =
                $r->miller ?? null;

            $millerName =
                $miller?->fullname
                ?? $miller?->username
                ?? 'Selected Miller';

            $product =
                $r->product_type
                ?? $r->product
                ?? $r->rice_type
                ?? $r->crop_type
                ?? $r->palay_type
                ?? optional($r->inventoryItem)->name
                ?? 'Palay / Milling Request';

            $kilos =
                $r->quantity_kilos
                ?? $r->quantity_kg
                ?? $r->kilos
                ?? $r->quantity
                ?? $r->weight_kg
                ?? 0;

            $transport =
                strtolower(
                    (string) ($r->transport_type ?? '')
                );

            $statusClass = match($status) {
                'accepted' => 'st-accepted',
                'scheduled' => 'st-scheduled',
                'in_progress' => 'st-progress',
                'finished' => 'st-finished',
                'completed' => 'st-completed',
                'rejected' => 'st-rejected',
                'cancelled' => 'st-cancelled',
                default => 'st-pending',
            };

            $rank = match($status) {
                'pending', 'assigned' => 1,
                'accepted' => 2,
                'scheduled' => 3,
                'in_progress' => 4,
                'finished' => 5,
                'completed' => 6,
                default => 1,
            };

            $statusMessage = match($status) {
                'pending', 'assigned' =>
                    'Your request is waiting for the Miller to accept it.',

                'accepted' =>
                    'The Miller accepted your request. Waiting for the milling schedule and fee.',

                'scheduled' =>
                    'Your milling date and time have been scheduled by the Miller.',

                'in_progress' =>
                    'Your palay is currently being milled.',

                'finished' =>
                    'The Miller finished the milling service. Review the proof before confirming completion.',

                'completed' =>
                    'The milling transaction has been confirmed as completed.',

                'rejected' =>
                    'The Miller rejected this milling request.',

                'cancelled' =>
                    'This milling request was cancelled.',

                default =>
                    'Track the latest milling transaction status here.',
            };

            $noteClass = match($status) {
                'completed', 'finished' => 'note-success',
                'accepted', 'scheduled', 'in_progress' => 'note-active',
                'rejected', 'cancelled' => 'note-muted',
                default => 'note-pending',
            };
        @endphp


        <article class="request-card">

            <div class="card-head">

                <div>
                    <div class="request-id">
                        Milling Request #{{ $r->id }}
                    </div>

                    <div class="miller-name">
                        {{ $millerName }}
                    </div>

                    <div class="miller-label">
                        Miller
                    </div>
                </div>

                <span class="status-pill {{ $statusClass }}">
                    {{ strtoupper(str_replace('_',' ', $status)) }}
                </span>

            </div>


            <div class="info-grid">

                <div class="info">
                    <div class="label">Product</div>
                    <div class="value">{{ $product }}</div>
                </div>

                <div class="info">
                    <div class="label">Quantity</div>
                    <div class="value">
                        {{ number_format((float)$kilos,2) }} kg
                    </div>
                </div>

                <div class="info">
                    <div class="label">Payment</div>
                    <div class="value">
                        <span class="payment-pill {{ $paymentStatus === 'paid' ? 'pay-paid' : 'pay-unpaid' }}">
                            {{ strtoupper($paymentStatus) }}
                        </span>
                    </div>
                </div>

                <div class="info">
                    <div class="label">Milling Fee</div>
                    <div class="value text-success">
                        {{ !empty($r->total_amount)
                            ? '₱'.number_format((float)$r->total_amount,2)
                            : 'Not set yet' }}
                    </div>
                </div>

                <div class="info">
                    <div class="label">Transport</div>
                    <div class="value">
                        @if($transport === 'pickup')
                            Pickup by Miller
                        @elseif($transport === 'delivery')
                            Farmer Delivery
                        @else
                            Not specified
                        @endif
                    </div>
                </div>

                <div class="info">
                    <div class="label">Requested At</div>
                    <div class="value">
                        {{ $r->created_at
                            ? $r->created_at
                                ->copy()
                                ->timezone('Asia/Manila')
                                ->format('M d, Y h:i A')
                            : '-' }}
                    </div>
                </div>

            </div>


            @if(!empty($r->scheduled_at))

                <div class="schedule-box">
                    <i class="bi bi-calendar-event-fill me-1"></i>

                    Milling Schedule:

                    <strong>
                        {{ $r->scheduled_at
                            ->copy()
                            ->timezone('Asia/Manila')
                            ->format('F d, Y h:i A') }}
                    </strong>
                </div>

            @endif


            <div class="progress-line">

                <div class="step {{ $rank >= 1 ? 'done' : '' }}">
                    <div class="step-dot">
                        <i class="bi bi-send-check"></i>
                    </div>
                    PENDING
                </div>

                <div class="step {{ $rank >= 2 ? 'done' : '' }}">
                    <div class="step-dot">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    ACCEPTED
                </div>

                <div class="step {{ $rank >= 3 ? 'done' : '' }}">
                    <div class="step-dot">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    SCHEDULED
                </div>

                <div class="step {{ $rank >= 4 ? 'done' : '' }}">
                    <div class="step-dot">
                        <i class="bi bi-gear-wide-connected"></i>
                    </div>
                    MILLING
                </div>

                <div class="step {{ $rank >= 5 ? 'done' : '' }}">
                    <div class="step-dot">
                        <i class="bi bi-camera"></i>
                    </div>
                    FINISHED
                </div>

                <div class="step {{ $rank >= 6 ? 'done' : '' }}">
                    <div class="step-dot">
                        <i class="bi bi-check2-all"></i>
                    </div>
                    COMPLETED
                </div>

            </div>


            <div class="status-note {{ $noteClass }}">
                <i class="bi bi-info-circle-fill me-1"></i>
                {{ $statusMessage }}
            </div>


            {{-- =====================================================
                 PAYMENT GATE - SAME RULE AS ADMIN <-> MILLER
            ====================================================== --}}

            @if(
                in_array(
                    $status,
                    ['scheduled','in_progress','finished','completed'],
                    true
                ) &&
                $paymentStatus !== 'paid'
            )
                <div class="payment-warning">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>

                    <strong>UNPAID:</strong>

                    @if($status === 'completed')
                        This is an older completed transaction whose payment
                        has not yet been confirmed. The Miller must use
                        <strong>Mark Paid</strong> only after actual payment
                        is received.
                    @elseif($status === 'finished')
                        Milling is finished, but you cannot confirm completion
                        until the Miller confirms that payment was received.
                    @else
                        Milling cannot proceed until the Miller confirms the
                        actual payment as <strong>PAID</strong>.
                    @endif
                </div>
            @elseif(
                in_array(
                    $status,
                    ['scheduled','in_progress','finished','completed'],
                    true
                ) &&
                $paymentStatus === 'paid'
            )
                <div class="payment-ok">
                    <i class="bi bi-cash-coin me-1"></i>
                    Payment has been confirmed by the Miller.
                </div>
            @endif


            {{-- =====================================================
                 PROOF OF MILLING
            ====================================================== --}}

            @if(
                !empty($r->proof_of_milling_path) &&
                in_array(
                    $status,
                    ['finished','completed'],
                    true
                )
            )
                <div class="proof-box">

                    <div class="proof-title">
                        <i class="bi bi-camera-fill me-1"></i>
                        Proof of Milling
                    </div>

                    <img
                        src="{{ asset('storage/'.$r->proof_of_milling_path) }}"
                        alt="Proof of Milling"
                        class="proof-img"
                    >

                </div>
            @endif


            {{-- =====================================================
                 FARMER CONFIRMS ONLY AFTER FINISHED + PAID + PROOF
            ====================================================== --}}

            @if(
                $status === 'finished' &&
                $paymentStatus === 'paid' &&
                !empty($r->proof_of_milling_path)
            )
                <div class="confirm-area">

                    <form
                        method="POST"
                        action="{{ route('farmer.milling.confirmCompleted', $r->id) }}"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="btn btn-success"
                            onclick="return confirm('Confirm that the milling service is finished and you received/reviewed the proof?')"
                        >
                            <i class="bi bi-check2-circle me-1"></i>
                            I Confirm Milling Completed
                        </button>

                    </form>

                </div>
            @endif

        </article>

    @empty

        <div class="empty">

            <i class="bi bi-gear-wide-connected fs-1 d-block mb-2"></i>

            You have no milling requests yet.

            <div class="mt-3">
                <a
                    href="{{ route('farmer.milling.create') }}"
                    class="btn btn-success"
                >
                    Create Milling Request
                </a>
            </div>

        </div>

    @endforelse


    @if(method_exists($requests, 'hasPages') && $requests->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $requests->links() }}
        </div>
    @endif

</main>


<script>
(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTO REFRESH
    |--------------------------------------------------------------------------
    |
    | Miller and Farmer often have the two pages open at the same time.
    | Refresh this tracking page automatically so SCHEDULED / IN PROGRESS /
    | FINISHED changes appear without manually pressing F5.
    |
    */

    const AUTO_REFRESH_MS = 8000;

    setInterval(function () {

        if (
            document.visibilityState === 'visible' &&
            !document.querySelector('.modal.show')
        ) {
            window.location.reload();
        }

    }, AUTO_REFRESH_MS);

})();
</script>

</body>
</html>
