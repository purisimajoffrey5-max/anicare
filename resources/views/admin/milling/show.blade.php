<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Milling Request #{{ $millingRequest->id }} | ANI-CARE Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        *{box-sizing:border-box}
        html,body{margin:0;min-height:100%;font-family:"Segoe UI",sans-serif}
        body{background:#f4f7f6;color:#1f2937}

        .topbar{background:#198754;color:#fff;box-shadow:0 2px 8px rgba(0,0,0,.08)}
        .topbar-inner{
            min-height:60px;display:flex;align-items:center;justify-content:space-between;
            gap:10px;padding:9px 14px
        }
        .brand{font-size:18px;font-weight:850}

        .page{max-width:880px;margin:0 auto;padding:22px 14px 60px}
        .title{margin:0;color:#198754;font-size:30px;font-weight:850}
        .subtitle{color:#6c757d;margin-top:4px;margin-bottom:18px}

        .card-box{
            background:#fff;border:1px solid #e3e8e5;border-radius:17px;
            box-shadow:0 5px 18px rgba(15,23,42,.05);padding:22px
        }

        .summary{
            display:grid;grid-template-columns:210px minmax(0,1fr);
            gap:14px 20px
        }
        .label{color:#6b7280;font-weight:700}
        .value{overflow-wrap:anywhere}
        .money{color:#198754;font-weight:850}

        .pill{
            display:inline-flex;align-items:center;padding:6px 10px;border-radius:999px;
            font-size:10px;font-weight:850
        }
        .pending{background:#fff3cd;color:#755b00}
        .active{background:#e8f1ff;color:#174ea6}
        .success{background:#d1e7dd;color:#0f5132}
        .cancelled{background:#eceff1;color:#495057}
        .paid{background:#d1e7dd;color:#0f5132}
        .unpaid{background:#fff3cd;color:#755b00}

        .schedule-box{
            margin-top:18px;padding:13px 14px;border:1px solid #cfe2ff;
            border-radius:12px;background:#eef6ff;color:#174ea6
        }

        .progress-wrap{
            margin-top:18px;padding:14px;border:1px solid #e4e8e6;
            border-radius:13px;background:#fafcfb
        }
        .progress-title{font-weight:850;margin-bottom:10px}
        .steps{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:7px}
        .step{text-align:center;font-size:10px;color:#6b7280}
        .dot{
            width:30px;height:30px;margin:0 auto 5px;border-radius:50%;
            display:grid;place-items:center;background:#e5e9e7;color:#6b7280
        }
        .step.done .dot{background:#198754;color:#fff}
        .step.done{color:#198754;font-weight:800}

        .proof{
            margin-top:18px;padding:14px;border:1px solid #ccebd8;
            border-radius:12px;background:#f1fbf5
        }
        .proof-title{font-weight:850;color:#146c43}
        .proof img{
            width:100%;max-height:420px;object-fit:contain;border-radius:10px;
            border:1px solid #dfe5e2;background:#fff;margin-top:10px
        }

        .notes-box{
            margin-top:18px;padding:13px 14px;border-radius:12px;
            background:#f8faf9;border:1px solid #edf1ef
        }

        .actions{
            display:grid;grid-template-columns:repeat(2,minmax(0,1fr));
            gap:8px;margin-top:18px
        }
        .actions .btn,.actions form,.actions form .btn{width:100%}
        .actions .btn{
            min-height:44px;border-radius:10px;display:inline-flex;
            align-items:center;justify-content:center;gap:5px;font-weight:750
        }
        .full{grid-column:1/-1}

        @media(max-width:767px){
            .page{padding:16px 10px 50px}
            .title{font-size:25px}
            .brand{font-size:15px}
            .card-box{padding:16px}
            .summary{grid-template-columns:1fr 1.2fr;gap:11px 12px}
            .steps{grid-template-columns:repeat(5,minmax(68px,1fr));overflow-x:auto;padding-bottom:4px}
            .topbar .btn span{display:none}
        }

        @media(max-width:390px){
            .actions{grid-template-columns:1fr}
            .full{grid-column:auto}
        }
    </style>
</head>

<body>

@php
    $status = strtolower((string) ($millingRequest->status ?? 'pending'));
    $paymentStatus = strtolower((string) ($millingRequest->payment_status ?? 'unpaid'));

    $statusClass = match($status) {
        'finished','completed' => 'success',
        'cancelled','rejected' => 'cancelled',
        'accepted','scheduled','in_progress' => 'active',
        default => 'pending',
    };

    $productType =
        $millingRequest->product_type
        ?? $millingRequest->product
        ?? $millingRequest->rice_type
        ?? $millingRequest->crop_type
        ?? $millingRequest->palay_type
        ?? '-';

    $quantity =
        $millingRequest->quantity_kilos
        ?? $millingRequest->quantity_kg
        ?? $millingRequest->kilos
        ?? $millingRequest->quantity
        ?? $millingRequest->weight_kg
        ?? 0;

    $notes =
        $millingRequest->notes
        ?? $millingRequest->remarks
        ?? $millingRequest->message
        ?? $millingRequest->details
        ?? null;

    $feePerKg = (float) ($millingRequest->milling_fee_per_kg ?? 0);
    $totalAmount = (float) ($millingRequest->total_amount ?? 0);

    $rank = match($status) {
        'accepted' => 2,
        'scheduled' => 3,
        'in_progress' => 4,
        'finished','completed' => 5,
        default => 1,
    };
@endphp

<header class="topbar">
    <div class="topbar-inner">
        <div class="brand">ANI-CARE | Admin</div>

        <a href="{{ route('admin.milling.index') }}" class="btn btn-outline-light btn-sm">
            <i class="bi bi-arrow-left"></i>
            <span>My Requests</span>
        </a>
    </div>
</header>

<main class="page">

    <h1 class="title">Milling Request #{{ $millingRequest->id }}</h1>
    <div class="subtitle">View request information, status, schedule, payment, and proof of milling.</div>

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

    <section class="card-box">

        <div class="d-flex justify-content-between align-items-start gap-2 mb-4 flex-wrap">
            <div>
                <div class="small text-muted">Selected Miller</div>
                <div class="fs-5 fw-bold">
                    {{ $millingRequest->miller?->fullname
                        ?? $millingRequest->miller?->username
                        ?? 'Miller' }}
                </div>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <span class="pill {{ $statusClass }}">
                    {{ strtoupper(str_replace('_',' ', $status)) }}
                </span>

                <span class="pill {{ $paymentStatus === 'paid' ? 'paid' : 'unpaid' }}">
                    {{ strtoupper($paymentStatus) }}
                </span>
            </div>
        </div>

        <div class="summary">

            <div class="label">Product / Crop</div>
            <div class="value">{{ $productType }}</div>

            <div class="label">Quantity</div>
            <div class="value">{{ number_format((float)$quantity,2) }} kg</div>

            <div class="label">Preferred Date</div>
            <div class="value">
                {{ !empty($millingRequest->preferred_date)
                    ? \Carbon\Carbon::parse($millingRequest->preferred_date)->format('M d, Y')
                    : 'Not specified' }}
            </div>

            <div class="label">Milling Fee / kg</div>
            <div class="value">
                {{ $feePerKg > 0 ? '₱'.number_format($feePerKg,2) : 'Not set yet' }}
            </div>

            <div class="label">Total Amount</div>
            <div class="value money">
                {{ $totalAmount > 0 ? '₱'.number_format($totalAmount,2) : 'Not calculated yet' }}
            </div>

            <div class="label">Submitted</div>
            <div class="value">
                {{ $millingRequest->created_at
                    ? $millingRequest->created_at->copy()->timezone('Asia/Manila')->format('M d, Y h:i A')
                    : '-' }}
            </div>

        </div>

        @if(!empty($millingRequest->scheduled_at))
            <div class="schedule-box">
                <i class="bi bi-calendar-check-fill me-1"></i>
                Milling Schedule:
                <strong>
                    {{ $millingRequest->scheduled_at
                        ->copy()
                        ->timezone('Asia/Manila')
                        ->format('F d, Y h:i A') }}
                </strong>
            </div>
        @endif

        @if(!in_array($status, ['cancelled','rejected'], true))
            <div class="progress-wrap">
                <div class="progress-title">Milling Progress</div>

                <div class="steps">
                    <div class="step {{ $rank >= 1 ? 'done' : '' }}">
                        <div class="dot"><i class="bi bi-send-check"></i></div>
                        PENDING
                    </div>

                    <div class="step {{ $rank >= 2 ? 'done' : '' }}">
                        <div class="dot"><i class="bi bi-check2-circle"></i></div>
                        ACCEPTED
                    </div>

                    <div class="step {{ $rank >= 3 ? 'done' : '' }}">
                        <div class="dot"><i class="bi bi-calendar-event"></i></div>
                        SCHEDULED
                    </div>

                    <div class="step {{ $rank >= 4 ? 'done' : '' }}">
                        <div class="dot"><i class="bi bi-gear-wide-connected"></i></div>
                        MILLING
                    </div>

                    <div class="step {{ $rank >= 5 ? 'done' : '' }}">
                        <div class="dot"><i class="bi bi-check-lg"></i></div>
                        FINISHED
                    </div>
                </div>
            </div>
        @endif

        @if(!empty($notes))
            <div class="notes-box">
                <div class="small text-muted mb-1">Notes</div>
                {{ $notes }}
            </div>
        @endif

        @if(
            in_array($status, ['finished','completed'], true) &&
            !empty($millingRequest->proof_of_milling_path)
        )
            <div class="proof">
                <div class="proof-title">
                    <i class="bi bi-camera-fill me-1"></i>
                    Proof of Milling
                </div>

                <img
                    src="{{ asset('storage/'.$millingRequest->proof_of_milling_path) }}"
                    alt="Proof of Milling"
                >
            </div>
        @endif

        <div class="actions">

            <a
                href="{{ route('admin.milling.invoice.show', $millingRequest->id) }}"
                class="btn btn-outline-success"
            >
                <i class="bi bi-receipt"></i>
                View Invoice
            </a>

            <a
                href="{{ route('admin.milling.invoice.download', $millingRequest->id) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-file-earmark-pdf"></i>
                Download PDF
            </a>

            @if(in_array($status, ['pending','accepted','scheduled'], true))
                <form
                    method="POST"
                    action="{{ route('admin.milling.cancel', $millingRequest->id) }}"
                >
                    @csrf

                    <button
                        class="btn btn-outline-danger"
                        onclick="return confirm('Cancel this milling request?')"
                    >
                        <i class="bi bi-x-circle"></i>
                        Cancel Request
                    </button>
                </form>
            @endif

            @if(
                $status === 'finished' &&
                !empty($millingRequest->proof_of_milling_path)
            )

                @if($paymentStatus === 'paid')

                    <form
                        method="POST"
                        action="{{ route('admin.milling.confirmCompleted', $millingRequest->id) }}"
                        class="full"
                    >
                        @csrf

                        <button
                            class="btn btn-success"
                            onclick="return confirm('Confirm that the milling service is completed, payment is confirmed, and the proof has been verified?')"
                        >
                            <i class="bi bi-check-circle-fill"></i>
                            I Confirm Milling Completed
                        </button>
                    </form>

                @else

                    <div
                        class="alert alert-warning full mb-0"
                        style="border-radius:10px;"
                    >
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        <strong>Payment is still UNPAID.</strong>
                        The Miller must confirm payment before you can finalize this milling transaction.
                    </div>

                    <button
                        type="button"
                        class="btn btn-secondary full"
                        disabled
                    >
                        <i class="bi bi-lock-fill"></i>
                        Completion Locked Until PAID
                    </button>

                @endif

            @endif

        </div>

    </section>

</main>

</body>
</html>
