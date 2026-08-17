<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Milling | ANI-CARE Farmer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    >

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    >

    <style>
        *{
            box-sizing:border-box;
        }

        html,
        body{
            margin:0;
            min-height:100%;
            font-family:"Segoe UI",Tahoma,Geneva,Verdana,sans-serif;
        }

        body{
            background:#f4f7f6;
            color:#1f2937;
            overflow-x:hidden;
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
            gap:12px;
        }

        .brand{
            font-size:18px;
            font-weight:850;
            display:flex;
            align-items:center;
            gap:8px;
        }

        .top-actions{
            display:flex;
            align-items:center;
            gap:8px;
        }

        /* =========================================================
           PAGE
        ========================================================= */

        .page-wrap{
            max-width:1180px;
            margin:0 auto;
            padding:24px 14px 60px;
        }

        .page-head{
            margin-bottom:18px;
        }

        .page-title{
            color:#198754;
            font-size:30px;
            font-weight:850;
            margin:0;
        }

        .page-subtitle{
            color:#6b7280;
            font-size:14px;
            margin-top:4px;
        }

        /* =========================================================
           CARDS
        ========================================================= */

        .soft-card{
            background:#fff;
            border:1px solid #e1e7e4;
            border-radius:18px;
            box-shadow:0 8px 22px rgba(15,23,42,.05);
        }

        .card-inner{
            padding:20px;
        }

        .section-title{
            font-size:17px;
            font-weight:850;
            margin:0;
        }

        .section-subtitle{
            color:#6b7280;
            font-size:12px;
            margin-top:2px;
        }

        /* =========================================================
           FORM
        ========================================================= */

        .form-label{
            font-size:13px;
            font-weight:700;
            margin-bottom:6px;
        }

        .form-control,
        .form-select{
            min-height:46px;
            border-radius:11px;
            border:1px solid #d7dedb;
        }

        textarea.form-control{
            min-height:105px;
            resize:vertical;
        }

        .form-control:focus,
        .form-select:focus{
            border-color:#198754;
            box-shadow:0 0 0 .2rem rgba(25,135,84,.12);
        }

        .helper{
            color:#6b7280;
            font-size:11px;
            margin-top:5px;
        }

        .selected-box{
            background:#f7faf8;
            border:1px solid #e4ebe7;
            border-radius:12px;
            padding:12px 13px;
        }

        .selected-name{
            font-weight:850;
            font-size:14px;
        }

        .selected-meta{
            color:#6b7280;
            font-size:11px;
            margin-top:2px;
        }

        #selectMillerButton{
            border-radius:10px;
            font-size:11px;
            font-weight:750;
            white-space:nowrap;
        }

        .nearest-box{
            border:1px solid #b8d8ff;
            background:#eef6ff;
            color:#174ea6;
            border-radius:12px;
            padding:12px;
            font-size:12px;
            line-height:1.45;
        }


        .transport-grid{
            display:grid;
            grid-template-columns:repeat(2,minmax(0,1fr));
            gap:9px;
        }

        .transport-option{
            position:relative;
        }

        .transport-option input{
            position:absolute;
            opacity:0;
            pointer-events:none;
        }

        .transport-card{
            border:1.5px solid #dfe5e2;
            border-radius:13px;
            padding:12px;
            background:#fff;
            cursor:pointer;
            min-height:94px;
            transition:.15s ease;
            display:flex;
            gap:10px;
            align-items:flex-start;
        }

        .transport-option input:checked + .transport-card{
            border-color:#198754;
            background:#effaf4;
            box-shadow:0 0 0 2px rgba(25,135,84,.08);
        }

        .transport-icon{
            width:36px;
            height:36px;
            border-radius:10px;
            background:#eaf7ef;
            color:#198754;
            display:grid;
            place-items:center;
            flex:0 0 auto;
            font-size:18px;
        }

        .transport-title{
            font-size:13px;
            font-weight:850;
        }

        .transport-desc{
            color:#6b7280;
            font-size:10px;
            line-height:1.35;
            margin-top:3px;
        }

        .pickup-info{
            display:none;
            margin-top:10px;
            padding:11px 12px;
            border-radius:11px;
            border:1px solid #cfe2ff;
            background:#eef6ff;
            color:#174ea6;
            font-size:11px;
            line-height:1.45;
        }

        .pickup-info.show{
            display:block;
        }

        .submit-btn{
            min-height:48px;
            border-radius:11px;
            font-weight:800;
        }

        /* =========================================================
           MAP
        ========================================================= */

        #map{
            width:100%;
            height:350px;
            border-radius:14px;
            border:1px solid #dfe5e2;
            overflow:hidden;
        }

        .map-legend{
            display:flex;
            gap:7px;
            flex-wrap:wrap;
        }

        .pill{
            border-radius:999px;
            padding:5px 9px;
            font-size:9px;
            font-weight:850;
            display:inline-flex;
            align-items:center;
            gap:4px;
        }

        /* =========================================================
           MILLER LIST
        ========================================================= */

        .miller-list{
            display:grid;
            grid-template-columns:repeat(2,minmax(0,1fr));
            gap:9px;
            margin-top:12px;
        }

        .miller-row{
            border:1px solid #e2e7e4;
            border-radius:13px;
            padding:11px;

            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;

            background:#fff;
            min-width:0;
        }

        .miller-info{
            min-width:0;
        }

        .miller-name{
            font-weight:800;
            font-size:13px;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
        }

        .miller-user,
        .miller-location{
            color:#6b7280;
            font-size:10px;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
        }

        .miller-actions{
            flex:0 0 auto;
            text-align:right;
        }

        .select-btn{
            border-radius:9px;
            font-size:10px;
            padding:5px 9px;
        }

        /* =========================================================
           VIEW ALL
        ========================================================= */

        .miller-extra{
            display:none;
        }

        .miller-extra.show{
            display:flex;
        }

        .view-all-wrap{
            display:flex;
            justify-content:center;
            margin-top:14px;
        }

        .view-all-btn{
            min-width:200px;
            border-radius:10px;
            font-size:12px;
            font-weight:750;
        }

        /* =========================================================
           SELECT MILLER MODAL
        ========================================================= */

        .miller-modal .modal-content{
            border:0;
            border-radius:18px;
            overflow:hidden;
            box-shadow:0 18px 60px rgba(0,0,0,.18);
        }

        .miller-modal .modal-header{
            background:#198754;
            color:#fff;
            border-bottom:0;
            padding:15px 18px;
        }

        .miller-modal .modal-title{
            font-size:17px;
            font-weight:850;
        }

        .miller-modal .btn-close{
            filter:brightness(0) invert(1);
        }

        .miller-modal .modal-body{
            padding:16px;
            background:#f7faf8;
        }

        .modal-search{
            position:relative;
            margin-bottom:12px;
        }

        .modal-search i{
            position:absolute;
            left:13px;
            top:50%;
            transform:translateY(-50%);
            color:#6b7280;
        }

        .modal-search input{
            padding-left:38px;
            border-radius:11px;
            min-height:44px;
        }

        .modal-miller-list{
            display:grid;
            gap:8px;
            max-height:430px;
            overflow-y:auto;
            padding-right:3px;
        }

        .modal-miller-item{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            padding:11px 12px;
            border:1px solid #dde6e1;
            border-radius:12px;
            background:#fff;
        }

        .modal-miller-item.hidden-by-search{
            display:none;
        }

        .modal-miller-main{
            min-width:0;
            flex:1 1 auto;
        }

        .modal-miller-name{
            font-size:13px;
            font-weight:850;
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
        }

        .modal-miller-meta{
            margin-top:2px;
            color:#6b7280;
            font-size:10px;
        }

        .modal-select-btn{
            flex:0 0 auto;
            border-radius:9px;
            font-size:10px;
            font-weight:750;
        }

        .modal-empty{
            display:none;
            text-align:center;
            color:#6b7280;
            padding:24px 10px;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media(max-width:991.98px){

            .page-wrap{
                padding-top:18px;
            }

            #map{
                height:300px;
            }

            .miller-list{
                grid-template-columns:1fr;
            }

            .transport-grid{
                grid-template-columns:1fr;
            }
        }

        @media(max-width:767.98px){

            .topbar-inner{
                padding:9px 10px;
            }

            .brand{
                font-size:15px;
            }

            .top-actions .btn{
                font-size:11px;
            }

            .page-wrap{
                padding:16px 10px 45px;
            }

            .page-title{
                font-size:25px;
            }

            .card-inner{
                padding:15px;
            }

            #map{
                height:260px;
            }

            .soft-card{
                border-radius:14px;
            }
        }

        @media(max-width:420px){

            .brand{
                font-size:14px;
            }

            .top-actions .btn span{
                display:none;
            }

            .page-title{
                font-size:23px;
            }

            .miller-row{
                align-items:flex-start;
            }

            .miller-name{
                max-width:190px;
            }

            .view-all-btn{
                width:100%;
            }
        }
    </style>
</head>

<body>

@php
    /*
    |--------------------------------------------------------------------------
    | FARMER REGISTERED / PERSONAL ADDRESS
    |--------------------------------------------------------------------------
    |
    | Pickup uses the Farmer's own registered address, NOT the farm-map
    | latitude/longitude. Priority:
    | 1. users.address
    | 2. users.complete_address
    | 3. barangay + municipality + province
    |
    */

    $farmerUser = auth()->user();

    $farmerPickupAddress = trim((string) (
        $farmerUser->address
        ?? $farmerUser->complete_address
        ?? ''
    ));

    if ($farmerPickupAddress === '') {

        $addressParts = array_values(array_filter([
            trim((string) ($farmerUser->barangay ?? '')),
            trim((string) ($farmerUser->municipality ?? 'Allacapan')),
            trim((string) ($farmerUser->province ?? 'Cagayan')),
        ]));

        $farmerPickupAddress =
            implode(', ', array_unique($addressParts));
    }
@endphp

<header class="topbar">
    <div class="topbar-inner">

        <div class="brand">
            <i class="bi bi-flower1"></i>
            ANI-CARE | Farmer
        </div>

        <div class="top-actions">

            <a
                href="{{ route('farmer.milling.index') }}"
                class="btn btn-outline-light btn-sm"
            >
                <i class="bi bi-clipboard-check"></i>
                <span>My Requests</span>
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


<main class="page-wrap">

    <div class="page-head">

        <h1 class="page-title">
            <i class="bi bi-gear-wide-connected me-1"></i>
            Request Milling
        </h1>

        <div class="page-subtitle">
            Select an OPEN Miller and submit the details of the palay you want processed.
        </div>

    </div>


    @if(session('success'))
        <div class="alert alert-success rounded-4">
            {{ session('success') }}
        </div>
    @endif


    @if($errors->any())
        <div class="alert alert-danger rounded-4">
            <strong>Please check the form:</strong>

            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="row g-3">

        {{-- =====================================================
             LEFT: FORM
        ====================================================== --}}
        <div class="col-12 col-lg-5">

            <section class="soft-card">
                <div class="card-inner">

                    <div class="mb-3">

                        <h2 class="section-title">
                            <i class="bi bi-card-checklist text-success me-1"></i>
                            Request Details
                        </h2>

                        <div class="section-subtitle">
                            Enter kilos, select a Miller, then submit your request.
                        </div>

                    </div>


                    <form
                        method="POST"
                        action="{{ route('farmer.milling.store') }}"
                        id="millingForm"
                    >
                        @csrf


                        <div class="mb-3">

                            <label class="form-label">
                                Kilos to Mill *
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="1"
                                name="kilos"
                                class="form-control"
                                value="{{ old('kilos') }}"
                                placeholder="Example: 50 or 120.5"
                                required
                            >

                            <div class="helper">
                                Enter the total palay quantity in kilograms.
                            </div>

                        </div>


                        {{-- TRANSPORT / HANDOVER OPTION --}}
                        <div class="mb-3">

                            <label class="form-label">
                                How will the palay reach the Miller? *
                            </label>

                            <div class="transport-grid">

                                <label class="transport-option">

                                    <input
                                        type="radio"
                                        name="transport_type"
                                        value="delivery"
                                        id="transportDelivery"
                                        {{ old('transport_type', 'delivery') === 'delivery' ? 'checked' : '' }}
                                        required
                                    >

                                    <div class="transport-card">

                                        <div class="transport-icon">
                                            <i class="bi bi-truck"></i>
                                        </div>

                                        <div>
                                            <div class="transport-title">
                                                I Will Deliver to Miller
                                            </div>

                                            <div class="transport-desc">
                                                You will bring the palay directly to the selected Miller.
                                            </div>
                                        </div>

                                    </div>

                                </label>


                                <label class="transport-option">

                                    <input
                                        type="radio"
                                        name="transport_type"
                                        value="pickup"
                                        id="transportPickup"
                                        {{ old('transport_type') === 'pickup' ? 'checked' : '' }}
                                        required
                                    >

                                    <div class="transport-card">

                                        <div class="transport-icon">
                                            <i class="bi bi-geo-alt-fill"></i>
                                        </div>

                                        <div>
                                            <div class="transport-title">
                                                Pickup by Miller
                                            </div>

                                            <div class="transport-desc">
                                                The selected Miller will pick up the palay from your registered address.
                                            </div>
                                        </div>

                                    </div>

                                </label>

                            </div>


                            <div
                                class="pickup-info"
                                id="pickupInfo"
                            >
                                <div class="fw-bold mb-2">
                                    <i class="bi bi-house-door-fill me-1"></i>
                                    Farmer Pickup Address
                                </div>

                                <input
                                    type="text"
                                    name="pickup_address"
                                    id="pickupAddress"
                                    class="form-control"
                                    value="{{ old('pickup_address', $farmerPickupAddress) }}"
                                    readonly
                                >

                                @if($farmerPickupAddress !== '')
                                    <div class="mt-2 text-success fw-bold">
                                        <i class="bi bi-check-circle-fill"></i>
                                        The Miller will pick up the palay at your registered address.
                                    </div>
                                @else
                                    <div class="mt-2 text-danger fw-bold">
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                        No registered address found. Please update your profile address first.
                                    </div>
                                @endif
                            </div>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Notes
                            </label>

                            <textarea
                                name="notes"
                                class="form-control"
                                rows="3"
                                maxlength="1000"
                                placeholder="Optional schedule preference, rice type, or other instructions..."
                            >{{ old('notes') }}</textarea>

                        </div>


                        {{-- SELECTED MILLER --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Selected Miller *
                            </label>

                            <input
                                type="hidden"
                                name="miller_id"
                                id="miller_id"
                                value="{{ old('miller_id') }}"
                                required
                            >

                            <div class="selected-box">

                                <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">

                                    <div class="flex-grow-1">

                                        <div
                                            class="selected-name"
                                            id="selectedMillerName"
                                        >
                                            {{ old('miller_id')
                                                ? 'Selected from previous input'
                                                : 'No Miller selected' }}
                                        </div>

                                        <div
                                            class="selected-meta"
                                            id="selectedMillerMeta"
                                        >
                                            Choose an OPEN Miller for this milling request.
                                        </div>

                                    </div>

                                    <button
                                        type="button"
                                        class="btn btn-success btn-sm"
                                        id="selectMillerButton"
                                        data-bs-toggle="modal"
                                        data-bs-target="#selectMillerModal"
                                    >
                                        <i class="bi bi-shop me-1"></i>
                                        <span id="selectMillerButtonText">
                                            Select a Miller
                                        </span>
                                    </button>

                                </div>

                            </div>

                        </div>


                        {{-- NEAREST --}}
                        <div class="nearest-box mb-3">

                            <div class="fw-bold mb-1">
                                <i class="bi bi-geo-alt-fill"></i>
                                Nearest OPEN Miller
                            </div>

                            <div id="nearestBox">
                                Calculating...
                            </div>

                        </div>


                        <button
                            type="submit"
                            class="btn btn-success submit-btn w-100 mb-2"
                        >
                            <i class="bi bi-send-check me-1"></i>
                            Submit Milling Request
                        </button>


                        <a
                            href="{{ route('farmer.milling.index') }}"
                            class="btn btn-outline-secondary submit-btn w-100"
                        >
                            <i class="bi bi-list-check me-1"></i>
                            View My Requests
                        </a>

                    </form>

                </div>
            </section>

        </div>


        {{-- =====================================================
             RIGHT: MAP + MILLERS
        ====================================================== --}}
        <div class="col-12 col-lg-7">

            {{-- MAP --}}
            <section class="soft-card mb-3">
                <div class="card-inner">

                    <div class="d-flex justify-content-between align-items-start gap-2 mb-3 flex-wrap">

                        <div>
                            <h2 class="section-title">
                                <i class="bi bi-map text-success me-1"></i>
                                Millers Map
                            </h2>

                            <div class="section-subtitle">
                                Click an OPEN Miller marker to select it.
                            </div>
                        </div>

                        <div class="map-legend">
                            <span class="pill bg-success text-white">
                                OPEN
                            </span>

                            <span class="pill bg-secondary text-white">
                                CLOSED
                            </span>
                        </div>

                    </div>

                    <div id="map"></div>

                </div>
            </section>


            {{-- MILLERS LIST --}}
            <section class="soft-card" id="millerSelectionSection">
                <div class="card-inner">

                    @php
                        $millerList = collect($millers ?? []);
                        $previewLimit = 6;
                    @endphp

                    <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">

                        <div>
                            <h2 class="section-title">
                                <i class="bi bi-shop text-success me-1"></i>
                                Registered Millers
                            </h2>

                            <div class="section-subtitle">
                                Showing {{ min($previewLimit, $millerList->count()) }}
                                of {{ $millerList->count() }} Miller(s).
                            </div>
                        </div>

                    </div>


                    <div class="miller-list" id="millerList">

                        @forelse($millerList as $m)

                            @php
                                $isExtra = $loop->index >= $previewLimit;
                            @endphp

                            <div class="miller-row {{ $isExtra ? 'miller-extra' : '' }}">

                                <div class="miller-info">

                                    <div
                                        class="miller-name"
                                        title="{{ $m->fullname ?? $m->username }}"
                                    >
                                        {{ $m->fullname ?? $m->username }}
                                    </div>

                                    <div class="miller-user">
                                        {{ !empty($m->username) ? '@'.$m->username : '' }}
                                    </div>

                                    <div class="miller-location">

                                        @if($m->latitude && $m->longitude)

                                            <span class="text-success">
                                                <i class="bi bi-geo-alt-fill"></i>
                                                Location available
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                <i class="bi bi-geo-alt"></i>
                                                Location not set
                                            </span>

                                        @endif

                                    </div>

                                </div>


                                <div class="miller-actions">

                                    @if($m->is_open)

                                        <div class="pill bg-success text-white mb-2">
                                            OPEN
                                        </div>

                                    @else

                                        <div class="pill bg-secondary text-white mb-2">
                                            CLOSED
                                        </div>

                                    @endif


                                    <button
                                        type="button"
                                        class="btn btn-sm select-btn {{ $m->is_open ? 'btn-outline-success' : 'btn-outline-secondary' }}"
                                        {{ !$m->is_open ? 'disabled' : '' }}

                                        onclick="selectMillerById({{ $m->id }})"
                                    >
                                        <i class="bi bi-check2-circle"></i>
                                        Select
                                    </button>

                                </div>

                            </div>

                        @empty

                            <div class="text-muted py-3">
                                No Millers found.
                            </div>

                        @endforelse

                    </div>


                    @if($millerList->count() > $previewLimit)

                        <div class="view-all-wrap">

                            <button
                                type="button"
                                class="btn btn-outline-success view-all-btn"
                                id="toggleMillersBtn"
                            >
                                <i class="bi bi-people-fill me-1"></i>
                                <span id="toggleMillersText">
                                    View All Millers
                                </span>
                            </button>

                        </div>

                    @endif

                </div>
            </section>

        </div>

    </div>

</main>


{{-- =========================================================
     SELECT MILLER MODAL
========================================================= --}}
<div
    class="modal fade miller-modal"
    id="selectMillerModal"
    tabindex="-1"
    aria-labelledby="selectMillerModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5
                        class="modal-title"
                        id="selectMillerModalLabel"
                    >
                        <i class="bi bi-shop me-1"></i>
                        Select an OPEN Miller
                    </h5>

                    <div class="small opacity-75 mt-1">
                        Choose the Miller who will receive this request.
                    </div>
                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>

            <div class="modal-body">

                <div class="modal-search">
                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        class="form-control"
                        id="millerSearchInput"
                        placeholder="Search Miller name or username..."
                        autocomplete="off"
                    >
                </div>

                <div class="modal-miller-list" id="modalMillerList">

                    @php
                        $openMillers = collect($millers ?? [])
                            ->filter(fn($m) => (bool) $m->is_open);
                    @endphp

                    @forelse($openMillers as $m)

                        <div
                            class="modal-miller-item"
                            data-miller-search="{{ strtolower(($m->fullname ?? '').' '.($m->username ?? '')) }}"
                        >
                            <div class="modal-miller-main">

                                <div class="modal-miller-name">
                                    {{ $m->fullname ?? $m->username ?? 'Miller' }}
                                </div>

                                <div class="modal-miller-meta">
                                    {{ !empty($m->username) ? '@'.$m->username : '' }}

                                    @if($m->latitude && $m->longitude)
                                        • Location available
                                    @endif
                                </div>

                            </div>

                            <button
                                type="button"
                                class="btn btn-success btn-sm modal-select-btn"
                                onclick="selectMillerById({{ $m->id }})"
                            >
                                <i class="bi bi-check2-circle"></i>
                                Select
                            </button>
                        </div>

                    @empty

                        <div class="text-center text-muted py-4">
                            No OPEN Millers are currently available.
                        </div>

                    @endforelse

                </div>

                <div
                    class="modal-empty"
                    id="modalSearchEmpty"
                >
                    <i class="bi bi-search fs-3 d-block mb-2"></i>
                    No matching OPEN Miller found.
                </div>

            </div>

        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>


<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | FARMER LOCATION
    |--------------------------------------------------------------------------
    */

    const farmerLat =
        @json(
            auth()->user()->latitude !== null
                ? (float) auth()->user()->latitude
                : null
        );

    const farmerLng =
        @json(
            auth()->user()->longitude !== null
                ? (float) auth()->user()->longitude
                : null
        );


    /*
    |--------------------------------------------------------------------------
    | FARMER REGISTERED ADDRESS FOR PICKUP
    |--------------------------------------------------------------------------
    |
    | This is separate from the farm-map coordinates.
    |
    */

    const FARMER_PICKUP_ADDRESS =
        @json($farmerPickupAddress);


    /*
    |--------------------------------------------------------------------------
    | MAP
    |--------------------------------------------------------------------------
    */

    const center =
        [18.2760, 121.6440];

    const map =
        L.map('map')
            .setView(
                center,
                13
            );


    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom:19,
            attribution:'&copy; OpenStreetMap contributors'
        }
    ).addTo(map);


    /*
    |--------------------------------------------------------------------------
    | ICONS
    |--------------------------------------------------------------------------
    */

    const openIcon =
        new L.Icon({
            iconUrl:
                'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',

            shadowUrl:
                'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',

            iconSize:[25,41],
            iconAnchor:[12,41],
            popupAnchor:[1,-34],
            shadowSize:[41,41]
        });


    const closedIcon =
        new L.Icon({
            iconUrl:
                'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-grey.png',

            shadowUrl:
                'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',

            iconSize:[25,41],
            iconAnchor:[12,41],
            popupAnchor:[1,-34],
            shadowSize:[41,41]
        });


    /*
    |--------------------------------------------------------------------------
    | FARMER MARKER
    |--------------------------------------------------------------------------
    */

    if (
        farmerLat !== null &&
        farmerLng !== null
    ) {

        L.circleMarker(
            [farmerLat, farmerLng],
            {
                radius:8,
                weight:3,
                color:'#0d6efd',
                fillColor:'#0d6efd',
                fillOpacity:.9
            }
        )
        .addTo(map)
        .bindPopup(
            '<strong>Your Farm Location</strong>'
        );


        map.setView(
            [farmerLat, farmerLng],
            13
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MILLER DATA
    |--------------------------------------------------------------------------
    */

    const millersRaw =
        @json($millers ?? []);


    const millers =
        (Array.isArray(millersRaw)
            ? millersRaw
            : []
        ).map(function (m) {

            return {
                id:m.id,

                fullname:
                    m.fullname
                    ?? m.username
                    ?? 'Miller',

                username:
                    m.username
                    ?? '',

                is_open:
                    !!m.is_open,

                latitude:
                    m.latitude,

                longitude:
                    m.longitude
            };
        });


    /*
    |--------------------------------------------------------------------------
    | SELECT MILLER BY ID
    |--------------------------------------------------------------------------
    |
    | Buttons now pass only the numeric ID. This prevents broken inline
    | JavaScript when a Miller name contains spaces, quotes, or apostrophes.
    |
    */

    window.selectMillerById =
        function (id) {

            const selected =
                millers.find(
                    function (m) {
                        return Number(m.id) === Number(id);
                    }
                );

            if (!selected) {

                alert(
                    'Unable to find the selected Miller. Please refresh the page and try again.'
                );

                return;
            }

            window.selectMiller(
                selected.id,
                selected.fullname,
                selected.username,
                selected.is_open
            );
        };


    /*
    |--------------------------------------------------------------------------
    | SELECT MILLER
    |--------------------------------------------------------------------------
    */

    window.selectMiller =
        function (
            id,
            fullname,
            username,
            isOpen
        ) {

            if (!isOpen) {
                alert(
                    'This Miller is currently CLOSED. Please select an OPEN Miller.'
                );

                return;
            }


            document
                .getElementById('miller_id')
                .value =
                id;


            document
                .getElementById('selectedMillerName')
                .textContent =
                fullname;


            document
                .getElementById('selectedMillerMeta')
                .innerHTML =
                '<span class="text-success fw-bold">OPEN</span>' +
                ' • @' +
                username;


            const selectButtonText =
                document.getElementById(
                    'selectMillerButtonText'
                );

            if (selectButtonText) {
                selectButtonText.textContent =
                    'Change Miller';
            }


            /*
             * Close the selection modal immediately after choosing.
             */
            const modalElement =
                document.getElementById(
                    'selectMillerModal'
                );

            if (modalElement) {

                const modalInstance =
                    bootstrap.Modal.getInstance(
                        modalElement
                    );

                if (modalInstance) {
                    modalInstance.hide();
                }
            }


            /*
             * Give visual confirmation that the selected Miller changed.
             */
            const selectedBox =
                document
                    .getElementById(
                        'selectedMillerName'
                    )
                    ?.closest(
                        '.selected-box'
                    );

            if (selectedBox) {

                selectedBox.style.borderColor =
                    '#198754';

                selectedBox.style.background =
                    '#effaf4';

                setTimeout(
                    function () {
                        selectedBox.style.borderColor = '';
                        selectedBox.style.background = '';
                    },
                    1200
                );
            }
        };


    /*
    |--------------------------------------------------------------------------
    | HAVERSINE
    |--------------------------------------------------------------------------
    */

    function haversine(
        lat1,
        lon1,
        lat2,
        lon2
    ) {

        const R =
            6371;

        const dLat =
            (lat2 - lat1) *
            Math.PI /
            180;

        const dLon =
            (lon2 - lon1) *
            Math.PI /
            180;

        const a =
            Math.sin(dLat / 2) *
            Math.sin(dLat / 2) +

            Math.cos(
                lat1 *
                Math.PI /
                180
            ) *

            Math.cos(
                lat2 *
                Math.PI /
                180
            ) *

            Math.sin(dLon / 2) *
            Math.sin(dLon / 2);


        return (
            R *
            2 *
            Math.atan2(
                Math.sqrt(a),
                Math.sqrt(1 - a)
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MILLER MARKERS + NEAREST OPEN
    |--------------------------------------------------------------------------
    */

    let nearest =
        null;

    const markerPoints =
        [];


    millers.forEach(function (m) {

        const lat =
            parseFloat(
                m.latitude
            );

        const lng =
            parseFloat(
                m.longitude
            );


        if (
            Number.isNaN(lat) ||
            Number.isNaN(lng)
        ) {
            return;
        }


        const icon =
            m.is_open
                ? openIcon
                : closedIcon;


        const popupHtml =
            `
            <div style="min-width:190px">

                <strong>
                    ${m.fullname}
                </strong>

                <br>

                @${m.username}

                <br>

                Status:
                <strong>
                    ${m.is_open ? 'OPEN' : 'CLOSED'}
                </strong>

                ${
                    m.is_open
                        ? `
                            <br>

                            <button
                                type="button"
                                class="btn btn-sm btn-success mt-2"
                                onclick="selectMillerById(${m.id})"
                            >
                                Select Miller
                            </button>
                        `
                        : ''
                }

            </div>
            `;


        L.marker(
            [lat,lng],
            {icon:icon}
        )
        .addTo(map)
        .bindPopup(
            popupHtml
        );


        markerPoints.push(
            [lat,lng]
        );


        if (
            farmerLat !== null &&
            farmerLng !== null &&
            m.is_open
        ) {

            const km =
                haversine(
                    Number(farmerLat),
                    Number(farmerLng),
                    lat,
                    lng
                );


            if (
                !nearest ||
                km < nearest.km
            ) {

                nearest =
                    {
                        ...m,
                        km:km
                    };
            }
        }
    });


    /*
    |--------------------------------------------------------------------------
    | FIT MAP
    |--------------------------------------------------------------------------
    */

    const allPoints =
        [...markerPoints];

    if (
        farmerLat !== null &&
        farmerLng !== null
    ) {

        allPoints.push(
            [
                Number(farmerLat),
                Number(farmerLng)
            ]
        );
    }


    if (allPoints.length > 1) {

        map.fitBounds(
            L.latLngBounds(
                allPoints
            ),
            {
                padding:[25,25]
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | NEAREST MILLER
    |--------------------------------------------------------------------------
    */

    const nearestBox =
        document.getElementById(
            'nearestBox'
        );


    if (
        farmerLat === null ||
        farmerLng === null
    ) {

        nearestBox.innerHTML =
            '<span class="text-danger fw-bold">No farm location saved.</span>' +
            '<br>Please set your farm location in Farm Profile.';

    } else if (!nearest) {

        nearestBox.innerHTML =
            '<span class="text-danger fw-bold">No OPEN Miller with a saved location was found.</span>';

    } else {

        nearestBox.innerHTML =
            `
            <strong>
                ${nearest.fullname}
            </strong>

            (@${nearest.username})

            <br>

            Distance:
            <strong>
                ${nearest.km.toFixed(2)} km
            </strong>

            <br>

            <button
                type="button"
                class="btn btn-sm btn-success mt-2"
                onclick="selectMillerById(${nearest.id})"
            >
                Select Nearest Miller
            </button>
            `;
    }


    /*
    |--------------------------------------------------------------------------
    | VIEW ALL MILLERS
    |--------------------------------------------------------------------------
    */

    const toggleButton =
        document.getElementById(
            'toggleMillersBtn'
        );

    if (toggleButton) {

        const text =
            document.getElementById(
                'toggleMillersText'
            );

        const extras =
            document.querySelectorAll(
                '.miller-extra'
            );

        let expanded =
            false;


        toggleButton
            .addEventListener(
                'click',
                function () {

                    expanded =
                        !expanded;


                    extras.forEach(
                        function (item) {

                            item.classList.toggle(
                                'show',
                                expanded
                            );
                        }
                    );


                    text.textContent =
                        expanded
                            ? 'Show Less Millers'
                            : 'View All Millers';


                    const icon =
                        toggleButton
                            .querySelector(
                                'i'
                            );

                    if (icon) {

                        icon.className =
                            expanded
                                ? 'bi bi-chevron-up me-1'
                                : 'bi bi-people-fill me-1';
                    }
                }
            );
    }


    /*
    |--------------------------------------------------------------------------
    | MILLER MODAL SEARCH
    |--------------------------------------------------------------------------
    */

    const millerSearchInput =
        document.getElementById(
            'millerSearchInput'
        );

    const modalSearchEmpty =
        document.getElementById(
            'modalSearchEmpty'
        );


    if (millerSearchInput) {

        millerSearchInput.addEventListener(
            'input',
            function () {

                const query =
                    this.value
                        .trim()
                        .toLowerCase();

                const modalItems =
                    document.querySelectorAll(
                        '.modal-miller-item'
                    );

                let visibleCount =
                    0;


                modalItems.forEach(
                    function (item) {

                        const searchable =
                            (
                                item.dataset.millerSearch
                                || ''
                            ).toLowerCase();

                        const matches =
                            searchable.includes(
                                query
                            );

                        item.classList.toggle(
                            'hidden-by-search',
                            !matches
                        );

                        if (matches) {
                            visibleCount++;
                        }
                    }
                );


                if (modalSearchEmpty) {

                    modalSearchEmpty.style.display =
                        visibleCount === 0
                            ? 'block'
                            : 'none';
                }
            }
        );
    }


    const selectMillerModalElement =
        document.getElementById(
            'selectMillerModal'
        );

    if (selectMillerModalElement) {

        selectMillerModalElement.addEventListener(
            'shown.bs.modal',
            function () {

                if (millerSearchInput) {

                    millerSearchInput.value = '';

                    millerSearchInput.dispatchEvent(
                        new Event('input')
                    );

                    millerSearchInput.focus();
                }
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TRANSPORT OPTION
    |--------------------------------------------------------------------------
    */

    const transportDelivery =
        document.getElementById(
            'transportDelivery'
        );

    const transportPickup =
        document.getElementById(
            'transportPickup'
        );

    const pickupInfo =
        document.getElementById(
            'pickupInfo'
        );


    function updateTransportOption() {

        if (!pickupInfo) {
            return;
        }

        pickupInfo.classList.toggle(
            'show',
            !!transportPickup?.checked
        );
    }


    transportDelivery?.addEventListener(
        'change',
        updateTransportOption
    );

    transportPickup?.addEventListener(
        'change',
        updateTransportOption
    );

    updateTransportOption();


    /*
    |--------------------------------------------------------------------------
    | FORM VALIDATION
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('millingForm')
        .addEventListener(
            'submit',
            function (e) {

                const millerId =
                    document
                        .getElementById(
                            'miller_id'
                        )
                        .value;


                /*
                 * Pickup uses the Farmer's registered PERSONAL/USER address.
                 * It does NOT require farm latitude/longitude.
                 */
                if (
                    transportPickup?.checked &&
                    (
                        !FARMER_PICKUP_ADDRESS ||
                        FARMER_PICKUP_ADDRESS.trim() === ''
                    )
                ) {

                    e.preventDefault();

                    alert(
                        'Pickup requires your registered address. Please update your profile address first.'
                    );

                    return;
                }


                if (!millerId) {

                    e.preventDefault();

                    alert(
                        'Please select an OPEN Miller before submitting your request.'
                    );
                }
            }
        );


    setTimeout(
        function () {
            map.invalidateSize();
        },
        150
    );
});
</script>

</body>
</html>