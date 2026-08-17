<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Milling Requests | ANI-CARE Miller</title>
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

        /* =========================================================
           TOPBAR
        ========================================================= */

        .topbar{
            background:#198754;
            color:#fff;
            box-shadow:0 2px 8px rgba(0,0,0,.08);
        }

        .topbar-inner{
            max-width:1180px;
            margin:0 auto;
            min-height:64px;
            padding:10px 16px;

            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
        }

        .brand{
            font-size:18px;
            font-weight:850;
            display:flex;
            align-items:center;
            gap:8px;
        }

        /* =========================================================
           PAGE
        ========================================================= */

        .page{
            max-width:1180px;
            margin:0 auto;
            padding:24px 14px 60px;
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
            font-size:14px;
        }

        /* =========================================================
           FILTER
        ========================================================= */

        .soft-card{
            background:#fff;
            border:1px solid #e0e7e3;
            border-radius:17px;
            box-shadow:0 7px 20px rgba(15,23,42,.05);
        }

        .filter-card{
            padding:14px;
            margin-bottom:15px;
        }

        .form-control,
        .form-select{
            border-radius:10px;
            min-height:44px;
        }

        /* =========================================================
           STATUS
        ========================================================= */

        .status-pill,
        .payment-pill,
        .transport-pill{
            display:inline-flex;
            align-items:center;
            gap:4px;
            border-radius:999px;
            padding:5px 9px;
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

        .payment-paid{
            background:#d1e7dd;
            color:#0f5132;
        }

        .payment-unpaid{
            background:#fff3cd;
            color:#705700;
        }

        .transport-delivery{
            background:#e8f3ff;
            color:#174ea6;
        }

        .transport-pickup{
            background:#f3e8ff;
            color:#6f2da8;
        }

        /* =========================================================
           DESKTOP TABLE
        ========================================================= */

        .desktop-table{
            overflow-x:auto;
        }

        .desktop-table table{
            min-width:1080px;
            margin:0;
        }

        .desktop-table th{
            font-size:11px;
            color:#4b5563;
            background:#f8faf9;
            white-space:nowrap;
            border-bottom:1px solid #dfe5e2;
        }

        .desktop-table td{
            font-size:12px;
            vertical-align:middle;
            border-color:#edf1ef;
        }

        .requester-name{
            font-weight:800;
            font-size:12px;
        }

        .requester-role{
            color:#6b7280;
            font-size:10px;
        }

        .product-name{
            font-weight:800;
        }

        .muted-small{
            color:#6b7280;
            font-size:10px;
        }

        .action-stack{
            display:flex;
            flex-wrap:wrap;
            justify-content:flex-end;
            gap:5px;
            min-width:155px;
        }

        .action-stack .btn{
            border-radius:8px;
            font-size:10px;
            padding:5px 8px;
            white-space:nowrap;
        }

        /* =========================================================
           MOBILE CARDS
        ========================================================= */

        .mobile-list{
            display:none;
        }

        .request-card{
            background:#fff;
            border:1px solid #e0e7e3;
            border-radius:16px;
            padding:14px;
            margin-bottom:11px;
            box-shadow:0 6px 17px rgba(15,23,42,.04);
        }

        .request-card-head{
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            gap:10px;
        }

        .request-number{
            color:#6b7280;
            font-size:10px;
        }

        .mobile-requester{
            font-size:15px;
            font-weight:850;
        }

        .info-grid{
            display:grid;
            grid-template-columns:repeat(2,minmax(0,1fr));
            gap:8px;
            margin-top:12px;
        }

        .info-box{
            background:#f8faf9;
            border:1px solid #edf1ef;
            border-radius:10px;
            padding:9px;
            min-width:0;
        }

        .info-label{
            color:#6b7280;
            font-size:9px;
        }

        .info-value{
            font-size:11px;
            font-weight:750;
            margin-top:2px;
            overflow-wrap:anywhere;
        }

        .mobile-actions{
            display:grid;
            grid-template-columns:repeat(2,minmax(0,1fr));
            gap:7px;
            margin-top:12px;
        }

        .mobile-actions form{
            margin:0;
        }

        .mobile-actions .btn,
        .mobile-actions form .btn{
            width:100%;
            min-height:40px;
            border-radius:9px;
            font-size:10px;
            font-weight:750;
        }

        .full-action{
            grid-column:1/-1;
        }

        .waiting-box{
            margin-top:10px;
            background:#eef6ff;
            border:1px solid #cfe2ff;
            border-radius:10px;
            padding:10px;
            color:#174ea6;
            font-size:10px;
        }

        .completed-box{
            margin-top:10px;
            background:#effaf4;
            border:1px solid #ccebd8;
            border-radius:10px;
            padding:10px;
            color:#146c43;
            font-size:10px;
        }

        .payment-warning{
            margin-top:10px;
            background:#fff8e1;
            border:1px solid #ffe08a;
            border-radius:10px;
            padding:10px;
            color:#6b5800;
            font-size:10px;
        }

        .btn-disabled-note{
            font-size:9px;
            color:#856404;
            width:100%;
            text-align:right;
            margin-top:2px;
        }

        /* =========================================================
           MODALS
        ========================================================= */

        .modal-content{
            border:0;
            border-radius:17px;
            overflow:hidden;
        }

        .modal-header{
            background:#198754;
            color:#fff;
            border:0;
        }

        .modal-header .btn-close{
            filter:brightness(0) invert(1);
        }

        .modal-title{
            font-size:16px;
            font-weight:850;
        }

        .proof-preview{
            max-width:100%;
            max-height:280px;
            object-fit:contain;
            border-radius:10px;
            border:1px solid #e0e7e3;
            background:#fafafa;
        }


        /* =========================================================
           REQUEST INFO MODAL
        ========================================================= */

        .request-info-grid{
            display:grid;
            grid-template-columns:repeat(2,minmax(0,1fr));
            gap:10px;
        }

        .request-info-box{
            background:#f8faf9;
            border:1px solid #e5ebe8;
            border-radius:12px;
            padding:11px;
            min-width:0;
        }

        .request-info-box.full{
            grid-column:1/-1;
        }

        .request-info-label{
            color:#6b7280;
            font-size:10px;
            margin-bottom:3px;
        }

        .request-info-value{
            color:#1f2937;
            font-size:13px;
            font-weight:750;
            overflow-wrap:anywhere;
        }

        .request-info-proof{
            width:100%;
            max-height:320px;
            object-fit:contain;
            border-radius:12px;
            border:1px solid #dfe7e3;
            background:#f8faf9;
        }

        @media(max-width:480px){
            .request-info-grid{
                grid-template-columns:1fr;
            }

            .request-info-box.full{
                grid-column:auto;
            }
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media(max-width:767.98px){

            .page{
                padding:16px 10px 45px;
            }

            .page-title{
                font-size:25px;
            }

            .brand{
                font-size:15px;
            }

            .desktop-wrap{
                display:none;
            }

            .mobile-list{
                display:block;
            }

            .filter-card .row > div{
                width:100%;
            }

            .filter-card .btn{
                width:100%;
            }
        }

        @media(max-width:390px){

            .mobile-actions{
                grid-template-columns:1fr;
            }

            .full-action{
                grid-column:auto;
            }
        }
    </style>
</head>

<body>

@php
    /*
    |--------------------------------------------------------------------------
    | REQUESTER DISPLAY RESOLVER
    |--------------------------------------------------------------------------
    |
    | Priority:
    | 1. transaction snapshot
    | 2. current users profile
    | 3. requester/farmer relation
    | 4. User #ID fallback
    |
    */

    $resolveRequesterDisplay = function ($requestRow) use ($requesterProfiles) {

        $requesterId =
            $requestRow->requester_id
            ?? $requestRow->farmer_id
            ?? $requestRow->user_id
            ?? null;

        $profile =
            $requesterId
                ? ($requesterProfiles[(int)$requesterId] ?? [])
                : [];

        $relation =
            $requestRow->requester
            ?? $requestRow->farmer
            ?? null;

        $name = trim((string) (
            $requestRow->requester_name_snapshot
            ?? $profile['name']
            ?? $relation?->fullname
            ?? $relation?->username
            ?? ($requesterId ? 'User #'.$requesterId : 'Unknown Requester')
        ));

        $username = trim((string) (
            $requestRow->requester_username_snapshot
            ?? $profile['username']
            ?? $relation?->username
            ?? ''
        ));

        $role = strtolower(trim((string) (
            $requestRow->requester_role
            ?? $profile['role']
            ?? $relation?->role
            ?? ''
        )));

        if (!in_array($role, ['admin','farmer'], true)) {
            $role =
                !empty($requestRow->farmer_id)
                    ? 'farmer'
                    : (strtolower((string) ($profile['role'] ?? '')) ?: 'requester');
        }

        $contact = trim((string) (
            $requestRow->requester_contact_snapshot
            ?? $profile['contact']
            ?? $relation?->mobile_number
            ?? $relation?->contact_number
            ?? $relation?->phone
            ?? ''
        ));

        $address = trim((string) (
            $requestRow->requester_address_snapshot
            ?? $requestRow->requester_address
            ?? $profile['address']
            ?? $relation?->address
            ?? ''
        ));

        if ($address === '' && !empty($relation?->barangay)) {
            $address = trim((string) $relation->barangay).', Allacapan, Cagayan';
        }

        return [
            'id' => $requesterId,
            'name' => $name !== '' ? $name : ($requesterId ? 'User #'.$requesterId : 'Unknown Requester'),
            'username' => $username,
            'role' => $role,
            'contact' => $contact !== '' ? $contact : '-',
            'address' => $address !== '' ? $address : '-',
        ];
    };
@endphp

<header class="topbar">
    <div class="topbar-inner">

        <div class="brand">
            <i class="bi bi-gear-wide-connected"></i>
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
        Milling Requests
    </h1>

    <div class="subtitle">
        Accept requests, set the milling fee and schedule, start the job, finish with proof, then record payment.
    </div>


    @if(session('success'))
        <div class="alert alert-success rounded-4">
            {{ session('success') }}
        </div>
    @endif


    @if($errors->any())
        <div class="alert alert-danger rounded-4">
            <strong>Please check:</strong>

            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- =========================================================
         FILTER
    ========================================================== --}}
    <section class="soft-card filter-card">

        <form
            method="GET"
            action="{{ route('miller.requests') }}"
            class="row g-2 align-items-center"
        >

            <div class="col-md-4">

                <select
                    name="status"
                    class="form-select"
                >
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>
                        All Requests
                    </option>

                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>
                        Pending
                    </option>

                    <option value="accepted" {{ $status === 'accepted' ? 'selected' : '' }}>
                        Accepted
                    </option>

                    <option value="scheduled" {{ $status === 'scheduled' ? 'selected' : '' }}>
                        Scheduled
                    </option>

                    <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>
                        In Progress
                    </option>

                    <option value="finished" {{ $status === 'finished' ? 'selected' : '' }}>
                        Finished
                    </option>

                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>
                        Completed
                    </option>

                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>
                        Rejected
                    </option>

                    <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>
                        Cancelled
                    </option>
                </select>

            </div>

            <div class="col-md-2 d-grid">
                <button class="btn btn-success">
                    <i class="bi bi-funnel me-1"></i>
                    Filter
                </button>
            </div>

        </form>

    </section>


    {{-- =========================================================
         DESKTOP TABLE
    ========================================================== --}}
    <section class="soft-card desktop-wrap">

        <div class="desktop-table">

            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Requester</th>
                        <th>Product</th>
                        <th>Kilos</th>
                        <th>Transport</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Schedule</th>
                        <th>Requested</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($requests as $r)

                    @php
                        $requestStatus =
                            strtolower((string) ($r->status ?? 'pending'));

                        $paymentStatus =
                            strtolower((string) ($r->payment_status ?? 'unpaid'));

                        $requesterDisplay =


                            $resolveRequesterDisplay($r);



                        $requesterId =


                            $requesterDisplay['id'];



                        $requesterName =


                            $requesterDisplay['name'];



                        $requesterRole =


                            $requesterDisplay['role'];

                        $productName =
                            $r->product_type
                            ?? $r->product
                            ?? $r->rice_type
                            ?? $r->crop_type
                            ?? $r->palay_type
                            ?? optional($r->inventoryItem)->name
                            ?? 'Milling Request';

                        $quantity =
                            $r->quantity_kilos
                            ?? $r->quantity_kg
                            ?? $r->kilos
                            ?? $r->quantity
                            ?? $r->weight_kg
                            ?? 0;

                        $transport =
                            strtolower((string) ($r->transport_type ?? ''));

                        $statusClass = match($requestStatus) {
                            'accepted' => 'st-accepted',
                            'scheduled' => 'st-scheduled',
                            'in_progress' => 'st-progress',
                            'finished' => 'st-finished',
                            'completed' => 'st-completed',
                            'rejected' => 'st-rejected',
                            'cancelled' => 'st-cancelled',
                            default => 'st-pending',
                        };

                        $scheduledValue =
                            $r->scheduled_at
                                ? $r->scheduled_at
                                    ->copy()
                                    ->timezone('Asia/Manila')
                                    ->format('M d, Y h:i A')
                                : '-';
                    @endphp

                    <tr>

                        <td>
                            <strong>#{{ $r->id }}</strong>
                        </td>


                        <td>
                            <div class="requester-name">
                                {{ $requesterName }}
                            </div>

                            <div class="requester-role text-capitalize">
                                {{ $requesterRole }}
                            </div>
                        </td>


                        <td>
                            <div class="product-name">
                                {{ $productName }}
                            </div>
                        </td>


                        <td>
                            <strong>
                                {{ number_format((float)$quantity,2) }} kg
                            </strong>
                        </td>


                        <td>
                            @if($transport === 'pickup')
                                <span class="transport-pill transport-pickup">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    PICKUP
                                </span>

                                @if(!empty($r->pickup_address))
                                    <div class="muted-small mt-1">
                                        {{ $r->pickup_address }}
                                    </div>
                                @endif

                            @elseif($transport === 'delivery')
                                <span class="transport-pill transport-delivery">
                                    <i class="bi bi-truck"></i>
                                    FARMER DELIVERY
                                </span>

                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>


                        <td>
                            <span class="status-pill {{ $statusClass }}">
                                {{ strtoupper(str_replace('_',' ', $requestStatus)) }}
                            </span>
                        </td>


                        <td>
                            <span class="payment-pill {{ $paymentStatus === 'paid' ? 'payment-paid' : 'payment-unpaid' }}">
                                {{ strtoupper($paymentStatus) }}
                            </span>

                            @if(!empty($r->total_amount))
                                <div class="muted-small mt-1">
                                    ₱{{ number_format((float)$r->total_amount,2) }}
                                </div>
                            @endif
                        </td>


                        <td>
                            <div style="min-width:125px">
                                {{ $scheduledValue }}
                            </div>
                        </td>


                        <td>
                            <div style="min-width:100px">
                                {{ $r->created_at
                                    ? $r->created_at
                                        ->copy()
                                        ->timezone('Asia/Manila')
                                        ->format('M d, h:i A')
                                    : '-' }}
                            </div>
                        </td>


                        <td>
                            <div class="action-stack">

                                <button
                                    type="button"
                                    class="btn btn-outline-dark"
                                    data-bs-toggle="modal"
                                    data-bs-target="#requestInfoModal{{ $r->id }}"
                                    title="View complete request information"
                                >
                                    <i class="bi bi-eye"></i>
                                    View Info
                                </button>

                                @if(in_array($requestStatus, ['pending','assigned'], true))

                                    <form
                                        method="POST"
                                        action="{{ route('miller.requests.accept', $r->id) }}"
                                    >
                                        @csrf

                                        <button
                                            class="btn btn-success"
                                            onclick="return confirm('Accept this milling request?')"
                                        >
                                            <i class="bi bi-check-circle"></i>
                                            Accept
                                        </button>
                                    </form>


                                    <form
                                        method="POST"
                                        action="{{ route('miller.requests.reject', $r->id) }}"
                                    >
                                        @csrf

                                        <button
                                            class="btn btn-outline-danger"
                                            onclick="return confirm('Reject this milling request?')"
                                        >
                                            <i class="bi bi-x-circle"></i>
                                            Reject
                                        </button>
                                    </form>

                                @elseif(in_array($requestStatus, ['accepted','scheduled'], true))

                                    <button
                                        type="button"
                                        class="btn btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#scheduleModal{{ $r->id }}"
                                    >
                                        <i class="bi bi-calendar-event"></i>
                                        {{ $requestStatus === 'scheduled' ? 'Change Schedule + Fee' : 'Set Schedule + Fee' }}
                                    </button>


                                    @if($requestStatus === 'scheduled')

                                        {{-- PAYMENT DOES NOT BLOCK MILLING ANYMORE --}}
                                        <form
                                            method="POST"
                                            action="{{ route('miller.requests.startMilling', $r->id) }}"
                                        >
                                            @csrf

                                            <button
                                                class="btn btn-primary"
                                                onclick="return confirm('Start milling now? The requester will be notified that milling is IN PROGRESS.')"
                                            >
                                                <i class="bi bi-gear-wide-connected"></i>
                                                Start Milling
                                            </button>
                                        </form>

                                    @endif


                                @elseif($requestStatus === 'in_progress')

                                    {{-- FINISH IS AVAILABLE EVEN WHILE PAYMENT IS UNPAID --}}
                                    <button
                                        type="button"
                                        class="btn btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#finishModal{{ $r->id }}"
                                    >
                                        <i class="bi bi-camera-fill"></i>
                                        Finish Milling + Proof
                                    </button>


                                @elseif($requestStatus === 'finished')

                                    @if($paymentStatus !== 'paid')

                                        {{-- MARK PAID APPEARS ONLY AFTER FINISH MILLING --}}
                                        <form
                                            method="POST"
                                            action="{{ route('miller.requests.paid', $r->id) }}"
                                        >
                                            @csrf

                                            <button
                                                class="btn btn-success"
                                                onclick="return confirm('The milling job is finished. Confirm that payment was actually received?')"
                                            >
                                                <i class="bi bi-cash-coin"></i>
                                                Mark Paid
                                            </button>
                                        </form>

                                    @else

                                        <span class="text-success small fw-semibold">
                                            <i class="bi bi-cash-coin"></i>
                                            Payment received
                                        </span>

                                    @endif

                                    <span class="text-primary small fw-semibold">
                                        <i class="bi bi-hourglass-split"></i>
                                        Waiting for requester confirmation
                                    </span>


                                @elseif($requestStatus === 'completed')

                                    @if($paymentStatus !== 'paid')

                                        {{-- SAFETY FALLBACK: requester confirmed before payment was recorded --}}
                                        <form
                                            method="POST"
                                            action="{{ route('miller.requests.paid', $r->id) }}"
                                        >
                                            @csrf

                                            <button
                                                class="btn btn-success"
                                                onclick="return confirm('This milling job is already completed. Confirm that payment was actually received?')"
                                            >
                                                <i class="bi bi-cash-coin"></i>
                                                Mark Paid
                                            </button>
                                        </form>

                                        <span class="text-warning small fw-semibold">
                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                            Completed but payment is still UNPAID
                                        </span>

                                    @else

                                        <span class="text-success small fw-semibold">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Transaction completed
                                        </span>

                                    @endif

                                @else

                                    <span class="text-muted">—</span>

                                @endif

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="10"
                            class="text-center text-muted py-5"
                        >
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            No milling requests found.
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

        @forelse($requests as $r)

            @php
                $requestStatus =
                    strtolower((string) ($r->status ?? 'pending'));

                $paymentStatus =
                    strtolower((string) ($r->payment_status ?? 'unpaid'));

                $requesterDisplay =


                    $resolveRequesterDisplay($r);



                $requesterId =


                    $requesterDisplay['id'];



                $requesterName =


                    $requesterDisplay['name'];



                $requesterRole =


                    $requesterDisplay['role'];

                $productName =
                    $r->product_type
                    ?? $r->product
                    ?? $r->rice_type
                    ?? $r->crop_type
                    ?? $r->palay_type
                    ?? optional($r->inventoryItem)->name
                    ?? 'Milling Request';

                $quantity =
                    $r->quantity_kilos
                    ?? $r->quantity_kg
                    ?? $r->kilos
                    ?? $r->quantity
                    ?? $r->weight_kg
                    ?? 0;

                $transport =
                    strtolower((string) ($r->transport_type ?? ''));

                $statusClass = match($requestStatus) {
                    'accepted' => 'st-accepted',
                    'scheduled' => 'st-scheduled',
                    'in_progress' => 'st-progress',
                    'finished' => 'st-finished',
                    'completed' => 'st-completed',
                    'rejected' => 'st-rejected',
                    'cancelled' => 'st-cancelled',
                    default => 'st-pending',
                };
            @endphp


            <article class="request-card">

                <div class="request-card-head">

                    <div>
                        <div class="request-number">
                            Milling Request #{{ $r->id }}
                        </div>

                        <div class="mobile-requester">
                            {{ $requesterName }}
                        </div>

                        <div class="requester-role text-capitalize">
                            {{ $requesterRole }}
                        </div>
                    </div>


                    <span class="status-pill {{ $statusClass }}">
                        {{ strtoupper(str_replace('_',' ', $requestStatus)) }}
                    </span>

                </div>


                <div class="info-grid">

                    <div class="info-box">
                        <div class="info-label">Product</div>
                        <div class="info-value">{{ $productName }}</div>
                    </div>

                    <div class="info-box">
                        <div class="info-label">Quantity</div>
                        <div class="info-value">
                            {{ number_format((float)$quantity,2) }} kg
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
                        <div class="info-label">Transport</div>

                        <div class="info-value">
                            @if($transport === 'pickup')
                                Pickup by Miller
                            @elseif($transport === 'delivery')
                                Farmer Delivery
                            @else
                                —
                            @endif
                        </div>
                    </div>

                    @if($transport === 'pickup' && !empty($r->pickup_address))
                        <div class="info-box" style="grid-column:1/-1">
                            <div class="info-label">Pickup Address</div>
                            <div class="info-value">
                                {{ $r->pickup_address }}
                            </div>
                        </div>
                    @endif

                    <div class="info-box">
                        <div class="info-label">Schedule</div>
                        <div class="info-value">
                            {{ $r->scheduled_at
                                ? $r->scheduled_at
                                    ->copy()
                                    ->timezone('Asia/Manila')
                                    ->format('M d, Y h:i A')
                                : 'Not set' }}
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-label">Total Milling Fee</div>
                        <div class="info-value text-success">
                            {{ !empty($r->total_amount)
                                ? '₱'.number_format((float)$r->total_amount,2)
                                : 'Not set' }}
                        </div>
                    </div>

                </div>


                <div class="mobile-actions">

                    <button
                        type="button"
                        class="btn btn-outline-dark full-action"
                        data-bs-toggle="modal"
                        data-bs-target="#requestInfoModal{{ $r->id }}"
                    >
                        <i class="bi bi-eye"></i>
                        View Request Info
                    </button>

                    @if(in_array($requestStatus, ['pending','assigned'], true))

                        <form
                            method="POST"
                            action="{{ route('miller.requests.accept', $r->id) }}"
                        >
                            @csrf

                            <button
                                class="btn btn-success"
                                onclick="return confirm('Accept this milling request?')"
                            >
                                <i class="bi bi-check-circle"></i>
                                Accept
                            </button>
                        </form>


                        <form
                            method="POST"
                            action="{{ route('miller.requests.reject', $r->id) }}"
                        >
                            @csrf

                            <button
                                class="btn btn-outline-danger"
                                onclick="return confirm('Reject this milling request?')"
                            >
                                <i class="bi bi-x-circle"></i>
                                Reject
                            </button>
                        </form>

                    @elseif(in_array($requestStatus, ['accepted','scheduled'], true))

                        <button
                            type="button"
                            class="btn btn-outline-primary full-action"
                            data-bs-toggle="modal"
                            data-bs-target="#scheduleModal{{ $r->id }}"
                        >
                            <i class="bi bi-calendar-event"></i>
                            {{ $requestStatus === 'scheduled' ? 'Change Schedule + Fee' : 'Set Schedule + Fee' }}
                        </button>


                        @if($requestStatus === 'scheduled')

                            <form
                                method="POST"
                                action="{{ route('miller.requests.startMilling', $r->id) }}"
                                class="full-action"
                            >
                                @csrf

                                <button
                                    class="btn btn-primary"
                                    onclick="return confirm('Start milling now? The requester will be notified that milling is IN PROGRESS.')"
                                >
                                    <i class="bi bi-gear-wide-connected"></i>
                                    Start Milling
                                </button>
                            </form>

                        @endif


                    @elseif($requestStatus === 'in_progress')

                        <button
                            type="button"
                            class="btn btn-success full-action"
                            data-bs-toggle="modal"
                            data-bs-target="#finishModal{{ $r->id }}"
                        >
                            <i class="bi bi-camera-fill"></i>
                            Finish Milling + Upload Proof
                        </button>


                    @elseif($requestStatus === 'finished')

                        @if($paymentStatus !== 'paid')

                            <form
                                method="POST"
                                action="{{ route('miller.requests.paid', $r->id) }}"
                                class="full-action"
                            >
                                @csrf

                                <button
                                    class="btn btn-success"
                                    onclick="return confirm('The milling job is finished. Confirm that payment was actually received?')"
                                >
                                    <i class="bi bi-cash-coin"></i>
                                    Mark Paid
                                </button>
                            </form>

                        @endif


                    @elseif($requestStatus === 'completed')

                        @if($paymentStatus !== 'paid')

                            <form
                                method="POST"
                                action="{{ route('miller.requests.paid', $r->id) }}"
                                class="full-action"
                            >
                                @csrf

                                <button
                                    class="btn btn-success"
                                    onclick="return confirm('This milling job is already completed. Confirm that payment was actually received?')"
                                >
                                    <i class="bi bi-cash-coin"></i>
                                    Mark Paid
                                </button>
                            </form>

                        @endif

                    @endif

                </div>


                @if(
                    in_array($requestStatus, ['scheduled','in_progress','finished','completed'], true) &&
                    $paymentStatus !== 'paid'
                )
                    <div class="payment-warning">
                        <i class="bi bi-info-circle-fill me-1"></i>

                        @if($requestStatus === 'scheduled')
                            <strong>PAYMENT PENDING:</strong>
                            You may start the milling job now. Payment will be recorded after the job is finished.

                        @elseif($requestStatus === 'in_progress')
                            <strong>MILLING IN PROGRESS:</strong>
                            Finish the milling job and upload proof first. Mark Paid will appear after finishing.

                        @elseif($requestStatus === 'finished')
                            <strong>MILLING FINISHED:</strong>
                            Proof has been submitted. Mark Paid is now available after actual payment is received.

                        @elseif($requestStatus === 'completed')
                            <strong>PAYMENT PENDING:</strong>
                            The requester already confirmed completion. Record payment only after it is actually received.
                        @endif
                    </div>
                @endif


                @if($requestStatus === 'finished')
                    <div class="waiting-box">
                        <i class="bi bi-hourglass-split me-1"></i>
                        Milling is finished. Waiting for
                        <strong>{{ ucfirst($requesterRole) }}</strong>
                        to confirm completion.
                    </div>
                @endif


                @if($requestStatus === 'completed')
                    <div class="completed-box">
                        <i class="bi bi-check-circle-fill me-1"></i>
                        Requester confirmed the milling transaction as completed.
                    </div>
                @endif

            </article>

        @empty

            <div class="soft-card text-center text-muted p-5">
                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                No milling requests found.
            </div>

        @endforelse

    </section>


    {{-- PAGINATION --}}
    @if($requests->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $requests->links() }}
        </div>
    @endif

</main>


{{-- =============================================================
     ACTION MODALS
============================================================= --}}

@foreach($requests as $r)

    @php
        $requestStatus =
            strtolower((string) ($r->status ?? 'pending'));

        $paymentStatus =
            strtolower((string) ($r->payment_status ?? 'unpaid'));

        $requesterDisplay =
            $resolveRequesterDisplay($r);

        $requesterId =
            $requesterDisplay['id'];

        $requesterName =
            $requesterDisplay['name'];

        $requesterRole =
            $requesterDisplay['role'];

        $requesterContact =
            $requesterDisplay['contact'];

        $requesterAddress =
            $requesterDisplay['address'];

        $productName =
            $r->product_type
            ?? $r->product
            ?? $r->rice_type
            ?? $r->crop_type
            ?? $r->palay_type
            ?? 'Palay / Milling Request';

        $quantity =
            $r->quantity_kilos
            ?? $r->quantity_kg
            ?? $r->kilos
            ?? $r->quantity
            ?? $r->weight_kg
            ?? 0;

        $transport =
            strtolower((string) ($r->transport_type ?? ''));

        $transportLabel =
            $transport === 'pickup'
                ? 'Pickup by Miller'
                : (
                    $transport === 'delivery'
                        ? (
                            $requesterRole === 'admin'
                                ? 'Admin Delivery'
                                : 'Farmer Delivery'
                        )
                        : 'Not specified'
                );

        $requesterAddressLabel =
            $transport === 'pickup'
                ? 'Pickup Address'
                : (
                    $requesterRole === 'admin'
                        ? 'Admin Registered Address'
                        : (
                            $requesterRole === 'farmer'
                                ? 'Farmer Registered Address'
                                : 'Requester Registered Address'
                        )
                );

        $millingRate =
            (float) ($r->milling_fee_per_kg ?? 0);

        $millingTotal =
            (float) ($r->total_amount ?? ($millingRate * (float)$quantity));

        $shippingFee =
            (float) ($r->shipping_fee ?? 0);

        $grandTotal =
            (float) ($r->grand_total ?? ($millingTotal + $shippingFee));

        $distanceKm =
            $r->shipping_distance_km
            ?? $r->distance_km
            ?? null;

        $notes =
            $r->notes
            ?? $r->remarks
            ?? $r->message
            ?? $r->details
            ?? '-';

        $proofPath =
            $r->proof_photo_path
            ?? $r->proof_path
            ?? $r->proof_of_milling_path
            ?? $r->milling_proof_path
            ?? null;

        $requestedAt =
            $r->created_at
                ? \Carbon\Carbon::parse($r->created_at)
                    ->timezone('Asia/Manila')
                    ->format('M d, Y h:i A')
                : '-';

        $scheduledAt =
            $r->scheduled_at
                ? \Carbon\Carbon::parse($r->scheduled_at)
                    ->timezone('Asia/Manila')
                    ->format('M d, Y h:i A')
                : 'Not set';

        $startedAt =
            !empty($r->started_at)
                ? \Carbon\Carbon::parse($r->started_at)
                    ->timezone('Asia/Manila')
                    ->format('M d, Y h:i A')
                : '-';

        $finishedAt =
            !empty($r->finished_at)
                ? \Carbon\Carbon::parse($r->finished_at)
                    ->timezone('Asia/Manila')
                    ->format('M d, Y h:i A')
                : '-';
    @endphp


    {{-- =========================================================
         VIEW COMPLETE REQUEST INFO MODAL
    ========================================================== --}}
    <div
        class="modal fade"
        id="requestInfoModal{{ $r->id }}"
        tabindex="-1"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

            <div class="modal-content">

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title">
                            <i class="bi bi-info-circle me-1"></i>
                            Milling Request #{{ $r->id }}
                        </h5>

                        <div class="small opacity-75">
                            Complete requester and transaction information
                        </div>
                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>
                </div>


                <div class="modal-body">

                    <div class="request-info-grid">

                        <div class="request-info-box">
                            <div class="request-info-label">
                                Requester
                            </div>
                            <div class="request-info-value">
                                {{ $requesterName }}
                            </div>
                        </div>

                        <div class="request-info-box">
                            <div class="request-info-label">
                                Requester Role
                            </div>
                            <div class="request-info-value text-capitalize">
                                {{ $requesterRole }}
                            </div>
                        </div>

                        <div class="request-info-box">
                            <div class="request-info-label">
                                Contact Number
                            </div>
                            <div class="request-info-value">
                                {{ $requesterContact }}
                            </div>
                        </div>

                        <div class="request-info-box">
                            <div class="request-info-label">
                                Product
                            </div>
                            <div class="request-info-value">
                                {{ $productName }}
                            </div>
                        </div>

                        <div class="request-info-box">
                            <div class="request-info-label">
                                Quantity
                            </div>
                            <div class="request-info-value">
                                {{ number_format((float)$quantity,2) }} kg
                            </div>
                        </div>

                        <div class="request-info-box">
                            <div class="request-info-label">
                                Transport
                            </div>
                            <div class="request-info-value">
                                {{ $transportLabel }}
                            </div>
                        </div>

                        <div class="request-info-box full">
                            <div class="request-info-label">
                                {{ $requesterAddressLabel }}
                            </div>
                            <div class="request-info-value">
                                {{ $requesterAddress }}
                            </div>
                        </div>

                        <div class="request-info-box">
                            <div class="request-info-label">
                                Status
                            </div>
                            <div class="request-info-value">
                                {{ strtoupper(str_replace('_',' ', $requestStatus)) }}
                            </div>
                        </div>

                        <div class="request-info-box">
                            <div class="request-info-label">
                                Payment Status
                            </div>
                            <div class="request-info-value">
                                <span class="payment-pill {{ $paymentStatus === 'paid' ? 'payment-paid' : 'payment-unpaid' }}">
                                    {{ strtoupper($paymentStatus) }}
                                </span>
                            </div>
                        </div>

                        <div class="request-info-box">
                            <div class="request-info-label">
                                Milling Fee / kg
                            </div>
                            <div class="request-info-value">
                                ₱{{ number_format($millingRate,2) }}
                            </div>
                        </div>

                        <div class="request-info-box">
                            <div class="request-info-label">
                                Milling Fee
                            </div>
                            <div class="request-info-value text-success">
                                ₱{{ number_format($millingTotal,2) }}
                            </div>
                        </div>

                        <div class="request-info-box">
                            <div class="request-info-label">
                                Shipping Fee
                            </div>
                            <div class="request-info-value">
                                ₱{{ number_format($shippingFee,2) }}
                            </div>
                        </div>

                        <div class="request-info-box">
                            <div class="request-info-label">
                                Grand Total
                            </div>
                            <div class="request-info-value text-success">
                                ₱{{ number_format($grandTotal,2) }}
                            </div>
                        </div>

                        <div class="request-info-box">
                            <div class="request-info-label">
                                Transport Distance
                            </div>
                            <div class="request-info-value">
                                {{ $distanceKm !== null
                                    ? number_format((float)$distanceKm,2).' km'
                                    : '-' }}
                            </div>
                        </div>

                        <div class="request-info-box">
                            <div class="request-info-label">
                                Requested At
                            </div>
                            <div class="request-info-value">
                                {{ $requestedAt }}
                            </div>
                        </div>

                        <div class="request-info-box">
                            <div class="request-info-label">
                                Schedule
                            </div>
                            <div class="request-info-value">
                                {{ $scheduledAt }}
                            </div>
                        </div>

                        <div class="request-info-box">
                            <div class="request-info-label">
                                Milling Started
                            </div>
                            <div class="request-info-value">
                                {{ $startedAt }}
                            </div>
                        </div>

                        <div class="request-info-box">
                            <div class="request-info-label">
                                Milling Finished
                            </div>
                            <div class="request-info-value">
                                {{ $finishedAt }}
                            </div>
                        </div>

                        <div class="request-info-box full">
                            <div class="request-info-label">
                                Notes / Instructions
                            </div>
                            <div class="request-info-value">
                                {{ $notes }}
                            </div>
                        </div>

                        @if($proofPath)
                            <div class="request-info-box full">
                                <div class="request-info-label mb-2">
                                    Proof of Milling
                                </div>

                                <img
                                    src="{{ asset('storage/'.$proofPath) }}"
                                    class="request-info-proof"
                                    alt="Proof of Milling"
                                >
                            </div>
                        @endif

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Close
                    </button>

                </div>

            </div>

        </div>
    </div>


    {{-- =========================================================
         SCHEDULE + FEE MODAL
    ========================================================== --}}
    @if(in_array($requestStatus, ['accepted','scheduled'], true))

        <div
            class="modal fade"
            id="scheduleModal{{ $r->id }}"
            tabindex="-1"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    <form
                        method="POST"
                        action="{{ route('miller.requests.schedule', $r->id) }}"
                    >
                        @csrf

                        <div class="modal-header">

                            <div>
                                <h5 class="modal-title">
                                    Set Milling Schedule & Fee
                                </h5>

                                <div class="small opacity-75">
                                    Request #{{ $r->id }}
                                    • {{ number_format((float)$quantity,2) }} kg
                                </div>
                            </div>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                            ></button>

                        </div>


                        <div class="modal-body">

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Milling Fee / kg *
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">₱</span>

                                    <input
                                        type="number"
                                        name="milling_fee_per_kg"
                                        class="form-control milling-fee-input"
                                        min="0"
                                        step="0.01"
                                        value="{{ old(
                                            'milling_fee_per_kg',
                                            $r->milling_fee_per_kg
                                        ) }}"
                                        data-kilos="{{ (float)$quantity }}"
                                        data-total-target="feeTotal{{ $r->id }}"
                                        required
                                    >
                                </div>

                            </div>


                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Milling Date & Time *
                                </label>

                                <input
                                    type="datetime-local"
                                    name="scheduled_at"
                                    class="form-control"
                                    min="{{ now('Asia/Manila')->format('Y-m-d\TH:i') }}"
                                    value="{{ $r->scheduled_at
                                        ? $r->scheduled_at
                                            ->copy()
                                            ->timezone('Asia/Manila')
                                            ->format('Y-m-d\TH:i')
                                        : '' }}"
                                    required
                                >

                            </div>


                            <div class="alert alert-light border mb-0">

                                Estimated Total Milling Fee:

                                <strong
                                    class="text-success"
                                    id="feeTotal{{ $r->id }}"
                                >
                                    ₱{{ number_format(
                                        (float)(
                                            $r->total_amount
                                            ?? (
                                                ((float)($r->milling_fee_per_kg ?? 0))
                                                *
                                                ((float)$quantity)
                                            )
                                        ),
                                        2
                                    ) }}
                                </strong>

                            </div>

                        </div>


                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                data-bs-dismiss="modal"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="btn btn-success"
                            >
                                <i class="bi bi-check-circle me-1"></i>
                                Save Schedule & Fee
                            </button>

                        </div>

                    </form>

                </div>

            </div>
        </div>

    @endif


    {{-- =========================================================
         FINISH + PROOF MODAL
    ========================================================== --}}
    @if($requestStatus === 'in_progress')

        <div
            class="modal fade"
            id="finishModal{{ $r->id }}"
            tabindex="-1"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    <form
                        method="POST"
                        action="{{ route('miller.requests.finish', $r->id) }}"
                        enctype="multipart/form-data"
                    >
                        @csrf

                        <div class="modal-header">

                            <div>
                                <h5 class="modal-title">
                                    Finish Milling
                                </h5>

                                <div class="small opacity-75">
                                    Request #{{ $r->id }}
                                </div>
                            </div>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                            ></button>

                        </div>


                        <div class="modal-body">

                            <div class="alert alert-info small">
                                Upload a clear photo as proof that the milling job is finished.
                                The requester will be notified after you finish and upload proof. Mark Paid will become available after this step.
                            </div>


                            <label class="form-label fw-semibold">
                                Proof of Milling *
                            </label>

                            <input
                                type="file"
                                name="proof_photo"
                                class="form-control proof-input"
                                accept="image/*"
                                capture="environment"
                                data-preview="proofPreview{{ $r->id }}"
                                required
                            >

                            <div class="form-text">
                                JPG, JPEG, PNG, or WEBP. Maximum 5MB.
                            </div>


                            <img
                                id="proofPreview{{ $r->id }}"
                                class="proof-preview mt-3 d-none"
                                alt="Proof Preview"
                            >

                        </div>


                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                data-bs-dismiss="modal"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="btn btn-success"
                                onclick="return confirm('Mark this milling request as FINISHED?')"
                            >
                                <i class="bi bi-check-circle-fill me-1"></i>
                                Finish Milling
                            </button>

                        </div>

                    </form>

                </div>

            </div>
        </div>

    @endif

@endforeach


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | MILLING FEE LIVE TOTAL
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.milling-fee-input')
        .forEach(function (input) {

            function calculate() {

                const kilos =
                    Number(
                        input.dataset.kilos
                        || 0
                    );

                const fee =
                    Number(
                        input.value
                        || 0
                    );

                const total =
                    kilos * fee;

                const target =
                    document.getElementById(
                        input.dataset.totalTarget
                    );

                if (target) {

                    target.textContent =
                        '₱' +
                        total.toLocaleString(
                            'en-PH',
                            {
                                minimumFractionDigits:2,
                                maximumFractionDigits:2
                            }
                        );
                }
            }

            input.addEventListener(
                'input',
                calculate
            );

            calculate();
        });


    /*
    |--------------------------------------------------------------------------
    | PROOF PHOTO PREVIEW
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.proof-input')
        .forEach(function (input) {

            input.addEventListener(
                'change',
                function () {

                    const preview =
                        document.getElementById(
                            input.dataset.preview
                        );

                    const file =
                        input.files &&
                        input.files[0];

                    if (
                        !preview ||
                        !file
                    ) {
                        return;
                    }

                    const reader =
                        new FileReader();

                    reader.onload =
                        function (event) {

                            preview.src =
                                event.target.result;

                            preview.classList.remove(
                                'd-none'
                            );
                        };

                    reader.readAsDataURL(
                        file
                    );
                }
            );
        });

});
</script>

</body>
</html>