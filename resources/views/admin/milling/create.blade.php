<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Milling | ANI-CARE Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <style>
        *{box-sizing:border-box}
        html,body{margin:0;min-height:100%;font-family:"Segoe UI",sans-serif}
        body{background:#f4f7f6;color:#1f2937}

        .topbar{background:#198754;color:#fff;box-shadow:0 2px 8px rgba(0,0,0,.08)}
        .topbar-inner{max-width:1000px;margin:auto;min-height:62px;padding:9px 14px;display:flex;align-items:center;justify-content:space-between;gap:10px}
        .brand{font-size:17px;font-weight:850}

        .page{max-width:920px;margin:auto;padding:22px 14px 60px}
        .page-title{margin:0;color:#198754;font-size:30px;font-weight:850}
        .subtitle{color:#6b7280;margin-top:4px;margin-bottom:18px}

        .card-box{background:#fff;border:1px solid #e1e7e4;border-radius:17px;box-shadow:0 6px 20px rgba(15,23,42,.05);padding:20px}
        .section-title{font-size:16px;font-weight:850;margin:20px 0 12px}
        .section-title:first-child{margin-top:0}

        .form-label{font-size:13px;font-weight:700}
        .form-control,.form-select{min-height:46px;border-radius:10px}
        textarea.form-control{min-height:100px}

        .selected-box{background:#f8faf9;border:1px solid #e1e7e4;border-radius:12px;padding:12px;display:flex;align-items:center;justify-content:space-between;gap:10px}
        .selected-name{font-size:14px;font-weight:850}
        .selected-meta{margin-top:2px;color:#6b7280;font-size:10px}

        .quantity-grid{display:grid;grid-template-columns:155px 1fr;gap:10px;align-items:end}
        .helper-box{margin-top:8px;border:1px solid #cfe2ff;background:#eef6ff;color:#174ea6;border-radius:10px;padding:9px 11px;font-size:11px}

        .transport-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}
        .transport-option{position:relative}
        .transport-option input{position:absolute;opacity:0;pointer-events:none}
        .transport-card{height:100%;border:1.5px solid #dfe5e2;border-radius:13px;background:#fff;padding:13px;cursor:pointer;display:flex;gap:10px;align-items:flex-start;transition:.15s}
        .transport-option input:checked + .transport-card{border-color:#198754;background:#effaf4;box-shadow:0 0 0 2px rgba(25,135,84,.08)}
        .transport-icon{width:38px;height:38px;border-radius:10px;background:#eaf7ef;color:#198754;display:grid;place-items:center;flex:0 0 auto;font-size:18px}
        .transport-title{font-weight:850;font-size:13px}
        .transport-desc{color:#6b7280;font-size:10px;line-height:1.4;margin-top:3px}

        .address-box{border:1px solid #e1e7e4;border-radius:11px;background:#f8faf9;padding:11px;font-size:11px}
        .cost-card{background:#f8faf9;border:1px solid #e1e7e4;border-radius:13px;padding:14px}
        .cost-row{display:flex;justify-content:space-between;gap:12px;padding:7px 0;font-size:12px}
        .cost-row + .cost-row{border-top:1px dashed #dfe5e2}
        .cost-grand{font-size:18px;font-weight:850;color:#198754}
        .cost-note{font-size:10px;color:#6b7280;margin-top:7px}

        .warning-box{display:none;margin-top:9px;border-radius:10px;padding:9px 11px;font-size:11px}
        .warning-box.show{display:block}

        .submit-btn{min-height:49px;border-radius:11px;font-weight:800}

        .miller-modal .modal-content{border:0;border-radius:17px;overflow:hidden}
        .miller-modal .modal-header{background:#198754;color:#fff;border:0}
        .miller-modal .btn-close{filter:brightness(0) invert(1)}
        .search-wrap{position:relative;margin-bottom:12px}
        .search-wrap i{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#6b7280}
        .search-wrap input{padding-left:38px}
        .miller-list{display:grid;gap:8px;max-height:430px;overflow-y:auto}
        .miller-item{background:#fff;border:1px solid #e1e7e4;border-radius:12px;padding:11px;display:flex;align-items:center;justify-content:space-between;gap:10px}
        .miller-item.hide{display:none}
        .miller-name{font-size:13px;font-weight:850}
        .miller-user{font-size:10px;color:#6b7280}
        .open-pill{display:inline-flex;padding:4px 8px;border-radius:999px;background:#198754;color:#fff;font-size:9px;font-weight:850}

        /* Leaflet exists and works, but the map is intentionally invisible. */
        #hiddenShippingMap{
            position:absolute !important;
            left:-10000px !important;
            top:-10000px !important;
            width:2px !important;
            height:2px !important;
            opacity:0 !important;
            pointer-events:none !important;
        }

        @media(max-width:767px){
            .page{padding:16px 10px 50px}
            .page-title{font-size:25px}
            .card-box{padding:15px}
            .brand{font-size:14px}
            .topbar .btn span{display:none}
            .selected-box{align-items:flex-start;flex-direction:column}
            .selected-box .btn{width:100%}
            .quantity-grid,.transport-grid{grid-template-columns:1fr}
        }
    </style>
</head>

<body>

@php
    $adminAddress = trim((string) (
        $adminAddress
        ?? $user->address
        ?? $user->complete_address
        ?? ''
    ));

    if ($adminAddress === '') {
        $parts = array_values(array_filter([
            trim((string) ($user->barangay ?? '')),
            trim((string) ($user->municipality ?? 'Allacapan')),
            trim((string) ($user->province ?? 'Cagayan')),
        ]));

        $adminAddress = implode(', ', array_unique($parts));
    }

    $millerJsData = [];

    foreach (($millers ?? collect()) as $miller) {

        $barangay =
            trim(
                (string) (
                    $miller->barangay
                    ?? ''
                )
            );

        $municipality =
            trim(
                (string) (
                    $miller->municipality
                    ?? 'Allacapan'
                )
            );

        if ($municipality === '') {
            $municipality = 'Allacapan';
        }

        $province =
            trim(
                (string) (
                    $miller->province
                    ?? 'Cagayan'
                )
            );

        if ($province === '') {
            $province = 'Cagayan';
        }

        $millerLocation =
            $barangay !== ''
                ? implode(', ', [
                    $barangay,
                    $municipality,
                    $province,
                    'Philippines',
                ])
                : '';

        $millerJsData[] = [
            'id' => (int) $miller->id,
            'fullname' => $miller->fullname ?? $miller->username ?? 'Miller',
            'username' => $miller->username ?? '',
            'barangay' => $barangay,
            'latitude' => null,
            'longitude' => null,
            'address' => $millerLocation,
        ];
    }
@endphp

<header class="topbar">
    <div class="topbar-inner">
        <div class="brand">
            <i class="bi bi-shield-check me-1"></i>
            ANI-CARE | Admin
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.milling.index') }}" class="btn btn-outline-light btn-sm">
                <i class="bi bi-clipboard-check"></i>
                <span>My Requests</span>
            </a>

            <a href="{{ route('admin.dashboard') }}" class="btn btn-warning btn-sm">
                <i class="bi bi-arrow-left"></i>
                <span>Back</span>
            </a>
        </div>
    </div>
</header>

<main class="page">

    <h1 class="page-title">
        <i class="bi bi-gear-wide-connected me-1"></i>
        Request Milling
    </h1>

    <div class="subtitle">
        Palay only • KG or Sack/Cavan • Delivery or Miller Pickup • Automatic cost estimate and invoice.
    </div>

    @if($errors->any())
        <div class="alert alert-danger rounded-4">
            <strong>Please check the form:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.milling.store') }}" id="adminMillingForm">
        @csrf

        <input type="hidden" name="quantity_kilos" id="quantity_kilos" value="{{ old('quantity_kilos') }}">
        <input type="hidden" name="quantity_value" id="quantity_value" value="{{ old('quantity_value') }}">

        <section class="card-box">

            <div class="section-title">
                <i class="bi bi-shop text-success me-1"></i>
                Selected Miller
            </div>

            <input type="hidden" name="miller_id" id="miller_id" value="{{ old('miller_id') }}" required>

            <div class="selected-box">
                <div>
                    <div class="selected-name" id="selectedMillerName">No Miller selected</div>
                    <div class="selected-meta" id="selectedMillerMeta">
                        Choose an OPEN Miller who will receive the milling request.
                    </div>
                </div>

                <button
                    type="button"
                    class="btn btn-success btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#selectMillerModal"
                >
                    <i class="bi bi-shop me-1"></i>
                    <span id="millerButtonText">Select a Miller</span>
                </button>
            </div>


            <div class="section-title">
                <i class="bi bi-flower1 text-success me-1"></i>
                Palay Quantity
            </div>

            <div class="alert alert-success py-2">
                <strong>Palay Milling Request</strong>
                <div class="small">Only Palay is accepted for this milling transaction.</div>
            </div>

            <div class="quantity-grid">

                <div>
                    <label class="form-label">Unit *</label>

                    <select name="quantity_unit" id="quantity_unit" class="form-select" required>
                        <option value="kg" {{ old('quantity_unit', 'kg') === 'kg' ? 'selected' : '' }}>
                            Kilogram (kg)
                        </option>

                        <option value="sack" {{ old('quantity_unit') === 'sack' ? 'selected' : '' }}>
                            Sack / Cavan
                        </option>
                    </select>
                </div>

                <div>
                    <label class="form-label" id="quantityInputLabel">
                        Quantity in kg *
                    </label>

                    <input
                        type="number"
                        id="quantity_input"
                        class="form-control"
                        step="0.01"
                        min="0.01"
                        placeholder="Example: 500"
                        required
                    >
                </div>

            </div>

            <div class="helper-box" id="quantityConversion">
                Enter the Palay quantity.
            </div>


            <div class="section-title">
                <i class="bi bi-truck text-success me-1"></i>
                Transport Option
            </div>

            <div class="transport-grid">

                <label class="transport-option">
                    <input
                        type="radio"
                        name="transport_type"
                        id="transportDelivery"
                        value="delivery"
                        {{ old('transport_type', 'delivery') === 'delivery' ? 'checked' : '' }}
                        required
                    >

                    <div class="transport-card">
                        <div class="transport-icon">
                            <i class="bi bi-box-arrow-right"></i>
                        </div>

                        <div>
                            <div class="transport-title">Admin Will Deliver to Miller</div>
                            <div class="transport-desc">
                                Admin brings the Palay to the selected Miller. System shipping fee: FREE.
                            </div>
                        </div>
                    </div>
                </label>


                <label class="transport-option">
                    <input
                        type="radio"
                        name="transport_type"
                        id="transportPickup"
                        value="pickup"
                        {{ old('transport_type') === 'pickup' ? 'checked' : '' }}
                        required
                    >

                    <div class="transport-card">
                        <div class="transport-icon">
                            <i class="bi bi-truck-front-fill"></i>
                        </div>

                        <div>
                            <div class="transport-title">Pickup by Miller</div>
                            <div class="transport-desc">
                                Miller picks up the Palay from Admin's registered address. The address is converted to map coordinates automatically.
                            </div>
                        </div>
                    </div>
                </label>

            </div>

            <div class="address-box mt-2" id="transportAddressBox">
                <strong>Admin Registered Address:</strong><br>
                {{ $adminAddress !== '' ? $adminAddress : 'No registered address found.' }}
            </div>

            <div class="form-text mt-1">
                <i class="bi bi-geo-alt"></i>
                Shipping uses this registered address. The address is geocoded automatically; Admin latitude/longitude does not need to be stored in the users table.
            </div>

            <div class="alert alert-danger warning-box" id="shippingWarning"></div>


            <div class="section-title">
                <i class="bi bi-receipt text-success me-1"></i>
                Cost Estimate
            </div>

            <div class="cost-card">

                <div class="cost-row">
                    <span>Milling rate</span>
                    <strong>₱2.50 / kg</strong>
                </div>

                <div class="cost-row">
                    <span>Milling fee</span>
                    <strong id="millingFeeDisplay">₱0.00</strong>
                </div>

                <div class="cost-row">
                    <span>Transport distance</span>
                    <strong id="distanceDisplay">—</strong>
                </div>

                <div class="cost-row">
                    <span>Shipping fee</span>
                    <strong id="shippingFeeDisplay">FREE</strong>
                </div>

                <div class="cost-row cost-grand">
                    <span>Estimated Grand Total</span>
                    <span id="grandTotalDisplay">₱0.00</span>
                </div>

                <div class="cost-note">
                    Pickup formula: ₱50 base + ₱10/km. Admin address is geocoded first, then the hidden Leaflet distance calculation runs.
                    The server recalculates the same distance when the request is submitted.
                    The Miller can update the final milling rate when setting the schedule.
                </div>

            </div>


            <div class="section-title">
                <i class="bi bi-calendar3 text-success me-1"></i>
                Additional Details
            </div>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Preferred Date</label>

                    <input
                        type="date"
                        name="preferred_date"
                        class="form-control"
                        min="{{ now('Asia/Manila')->format('Y-m-d') }}"
                        value="{{ old('preferred_date') }}"
                    >

                    <div class="form-text">
                        Optional. Miller still sets the final date and time.
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Notes</label>

                    <textarea
                        name="notes"
                        class="form-control"
                        maxlength="1000"
                        placeholder="Optional instructions..."
                    >{{ old('notes') }}</textarea>
                </div>

            </div>


            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-success submit-btn">
                    <i class="bi bi-send-check me-1"></i>
                    Submit Request & Generate Invoice
                </button>
            </div>

        </section>
    </form>

</main>


{{-- Hidden Leaflet map. Not visible to the user, but initialized and available. --}}
<div id="hiddenShippingMap" aria-hidden="true"></div>


<div class="modal fade miller-modal" id="selectMillerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold">Select an OPEN Miller</h5>
                    <div class="small opacity-75">
                        Select a Miller. Location is used for pickup shipping calculation.
                    </div>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="search-wrap">
                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        class="form-control"
                        id="millerSearch"
                        placeholder="Search Miller..."
                    >
                </div>

                <div class="miller-list">

                    @forelse(($millers ?? collect()) as $miller)

                        <div
                            class="miller-item"
                            data-search="{{ strtolower(($miller->fullname ?? '').' '.($miller->username ?? '')) }}"
                        >
                            <div>
                                <div class="miller-name">
                                    {{ $miller->fullname ?? $miller->username ?? 'Miller' }}
                                </div>

                                <div class="miller-user">
                                    {{ !empty($miller->username) ? '@'.$miller->username : '' }}
                                    • Barangay:
                                    <strong>{{ $miller->barangay ?: 'Not set' }}</strong>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <span class="open-pill">OPEN</span>

                                <button
                                    type="button"
                                    class="btn btn-outline-success btn-sm"
                                    onclick="selectMillerById({{ $miller->id }})"
                                >
                                    Select
                                </button>
                            </div>
                        </div>

                    @empty
                        <div class="text-center text-muted py-4">
                            No OPEN Millers available.
                        </div>
                    @endforelse

                </div>

            </div>

        </div>

    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const SACK_KILOS = 60;
    const MILLING_RATE = 2.50;
    const SHIPPING_BASE = 50;
    const SHIPPING_PER_KM = 10;

    const ADMIN_LAT =
        @json(
            isset($adminCoordinates['lat'])
                ? (float) $adminCoordinates['lat']
                : (
                    is_numeric($user->latitude ?? null)
                        ? (float) $user->latitude
                        : null
                )
        );

    const ADMIN_LNG =
        @json(
            isset($adminCoordinates['lng'])
                ? (float) $adminCoordinates['lng']
                : (
                    is_numeric($user->longitude ?? null)
                        ? (float) $user->longitude
                        : null
                )
        );

    const ADMIN_ADDRESS =
        @json($adminAddress);

    const millers =
        @json($millerJsData);

    /*
     * The selected Miller Barangay is geocoded by Laravel.
     * __ID__ is replaced with the actual Miller ID in JavaScript.
     */
    const MILLER_LOCATION_URL_TEMPLATE =
        @json(route('admin.milling.millerLocation', ['id' => '__ID__']));


    /*
    |--------------------------------------------------------------------------
    | HIDDEN LEAFLET
    |--------------------------------------------------------------------------
    */

    const hiddenMap =
        L.map(
            'hiddenShippingMap',
            {
                zoomControl:false,
                attributionControl:false,
                dragging:false,
                scrollWheelZoom:false,
                doubleClickZoom:false,
                boxZoom:false,
                keyboard:false,
                touchZoom:false
            }
        ).setView(
            [
                ADMIN_LAT ?? 18.2760,
                ADMIN_LNG ?? 121.6440
            ],
            13
        );


    let selectedMiller = null;

    const quantityUnit = document.getElementById('quantity_unit');
    const quantityInput = document.getElementById('quantity_input');
    const quantityKilos = document.getElementById('quantity_kilos');
    const quantityValue = document.getElementById('quantity_value');

    const transportDelivery = document.getElementById('transportDelivery');
    const transportPickup = document.getElementById('transportPickup');

    const shippingWarning = document.getElementById('shippingWarning');


    function money(value) {
        return '₱' + Number(value || 0).toLocaleString(
            'en-PH',
            {
                minimumFractionDigits:2,
                maximumFractionDigits:2
            }
        );
    }


    function currentKilos() {

        const raw =
            Number(
                quantityInput.value
                || 0
            );

        if (quantityUnit.value === 'sack') {
            return raw * SACK_KILOS;
        }

        return raw;
    }


    function updateQuantity() {

        const raw =
            Number(
                quantityInput.value
                || 0
            );

        const kilos =
            currentKilos();

        quantityValue.value =
            raw > 0
                ? raw.toFixed(2)
                : '';

        quantityKilos.value =
            kilos > 0
                ? kilos.toFixed(2)
                : '';

        const label =
            document.getElementById(
                'quantityInputLabel'
            );

        const conversion =
            document.getElementById(
                'quantityConversion'
            );

        if (quantityUnit.value === 'sack') {

            label.textContent =
                'Number of Sacks / Cavans *';

            quantityInput.placeholder =
                'Example: 5';

            conversion.innerHTML =
                '<strong>' +
                (raw || 0).toLocaleString('en-PH', {
                    maximumFractionDigits:2
                }) +
                ' sack(s)</strong> × 60 kg = <strong>' +
                kilos.toLocaleString('en-PH', {
                    minimumFractionDigits:2,
                    maximumFractionDigits:2
                }) +
                ' kg</strong>';

        } else {

            label.textContent =
                'Quantity in kg *';

            quantityInput.placeholder =
                'Example: 500';

            conversion.innerHTML =
                '<strong>' +
                kilos.toLocaleString('en-PH', {
                    minimumFractionDigits:2,
                    maximumFractionDigits:2
                }) +
                ' kg</strong> of Palay';
        }

        updateCost();
    }


    /*
    |--------------------------------------------------------------------------
    | RESOLVE SELECTED MILLER BARANGAY
    |--------------------------------------------------------------------------
    |
    | We do NOT require Miller latitude/longitude in the users table.
    | Laravel geocodes:
    |
    |     Miller Barangay + Allacapan + Cagayan + Philippines
    |
    */

    async function resolveMillerBarangay(miller) {

        if (!miller) {
            return false;
        }

        if (
            miller.latitude !== null &&
            miller.longitude !== null
        ) {
            return true;
        }

        if (!miller.barangay) {

            shippingWarning.textContent =
                'The selected Miller has no Barangay saved.';

            shippingWarning.classList.add('show');

            updateCost();

            return false;
        }


        shippingWarning.textContent =
            'Locating Miller Barangay: ' +
            miller.barangay +
            ', Allacapan, Cagayan...';

        shippingWarning.classList.add('show');


        try {

            const url =
                MILLER_LOCATION_URL_TEMPLATE.replace(
                    '__ID__',
                    encodeURIComponent(miller.id)
                );

            const response =
                await fetch(
                    url,
                    {
                        headers:{
                            'Accept':'application/json'
                        },
                        credentials:'same-origin'
                    }
                );

            const data =
                await response.json();


            if (
                !response.ok ||
                !data.ok
            ) {
                throw new Error(
                    data.message ||
                    'Could not locate Miller Barangay.'
                );
            }


            miller.latitude =
                Number(data.latitude);

            miller.longitude =
                Number(data.longitude);

            miller.address =
                data.location_text;


            document
                .getElementById('selectedMillerMeta')
                .innerHTML =
                '<span class="text-success fw-bold">OPEN</span>' +
                (miller.username ? ' • @' + miller.username : '') +
                '<br><strong>Barangay:</strong> ' +
                (data.barangay || '') +
                '<br><span class="text-muted">' +
                data.location_text +
                '</span>';


            shippingWarning.textContent = '';
            shippingWarning.classList.remove('show');

            updateCost();

            return true;

        } catch (error) {

            shippingWarning.textContent =
                error.message ||
                'Could not locate the selected Miller Barangay.';

            shippingWarning.classList.add('show');

            updateCost();

            return false;
        }
    }


    function getDistanceKm() {

        if (
            ADMIN_LAT === null ||
            ADMIN_LNG === null ||
            !selectedMiller ||
            selectedMiller.latitude === null ||
            selectedMiller.longitude === null
        ) {
            return null;
        }

        /*
         * Leaflet distanceTo() returns meters.
         */
        const from =
            L.latLng(
                Number(ADMIN_LAT),
                Number(ADMIN_LNG)
            );

        const to =
            L.latLng(
                Number(selectedMiller.latitude),
                Number(selectedMiller.longitude)
            );

        return (
            from.distanceTo(to) /
            1000
        );
    }


    function updateCost() {

        const kilos =
            currentKilos();

        const millingFee =
            kilos *
            MILLING_RATE;

        let shippingFee = 0;
        let distanceKm = null;
        let warning = '';


        if (transportPickup.checked) {

            distanceKm =
                getDistanceKm();

            if (!ADMIN_ADDRESS) {

                warning =
                    'Pickup requires the Admin registered address.';

            } else if (
                ADMIN_LAT === null ||
                ADMIN_LNG === null
            ) {

                warning =
                    'The Admin registered address could not be located on the map. Check the address spelling/details.';

            } else if (!selectedMiller) {

                warning =
                    'Select a Miller first so the pickup shipping fee can be calculated.';

            } else if (
                selectedMiller.latitude === null ||
                selectedMiller.longitude === null
            ) {

                warning =
                    'The selected Miller Barangay has not been located yet.';

            } else {

                shippingFee =
                    SHIPPING_BASE +
                    (
                        distanceKm *
                        SHIPPING_PER_KM
                    );
            }
        }


        const grandTotal =
            millingFee +
            shippingFee;


        document
            .getElementById('millingFeeDisplay')
            .textContent =
            money(millingFee);


        document
            .getElementById('distanceDisplay')
            .textContent =
            transportDelivery.checked
                ? 'Admin delivery'
                : (
                    distanceKm !== null
                        ? distanceKm.toFixed(2) + ' km'
                        : '—'
                );


        document
            .getElementById('shippingFeeDisplay')
            .textContent =
            transportDelivery.checked
                ? 'FREE'
                : (
                    warning
                        ? '—'
                        : money(shippingFee)
                );


        document
            .getElementById('grandTotalDisplay')
            .textContent =
            warning
                ? money(millingFee)
                : money(grandTotal);


        shippingWarning.textContent =
            warning;

        shippingWarning.classList.toggle(
            'show',
            warning !== ''
        );
    }


    window.selectMillerById =
        async function (id) {

            const miller =
                millers.find(
                    function (item) {
                        return Number(item.id) === Number(id);
                    }
                );

            if (!miller) {
                alert('Unable to find the selected Miller.');
                return;
            }

            selectedMiller =
                miller;

            document
                .getElementById('miller_id')
                .value =
                miller.id;

            document
                .getElementById('selectedMillerName')
                .textContent =
                miller.fullname;

            document
                .getElementById('selectedMillerMeta')
                .innerHTML =
                '<span class="text-success fw-bold">OPEN</span>' +
                (miller.username ? ' • @' + miller.username : '') +
                '<br><strong>Barangay:</strong> ' +
                (miller.barangay || 'Not set');

            document
                .getElementById('millerButtonText')
                .textContent =
                'Change Miller';

            const modalElement =
                document.getElementById(
                    'selectMillerModal'
                );

            const modal =
                bootstrap.Modal.getInstance(
                    modalElement
                );

            if (modal) {
                modal.hide();
            }

            /*
             * Resolve the selected Miller shipping location from BARANGAY.
             */
            await resolveMillerBarangay(
                miller
            );

            updateCost();
        };


    quantityUnit.addEventListener(
        'change',
        function () {
            quantityInput.value = '';
            updateQuantity();
            quantityInput.focus();
        }
    );

    quantityInput.addEventListener(
        'input',
        updateQuantity
    );

    transportDelivery.addEventListener(
        'change',
        updateCost
    );

    transportPickup.addEventListener(
        'change',
        updateCost
    );


    const search =
        document.getElementById(
            'millerSearch'
        );

    search?.addEventListener(
        'input',
        function () {

            const query =
                this.value
                    .trim()
                    .toLowerCase();

            document
                .querySelectorAll(
                    '.miller-item'
                )
                .forEach(
                    function (item) {

                        const haystack =
                            (
                                item.dataset.search
                                || ''
                            )
                            .toLowerCase();

                        item.classList.toggle(
                            'hide',
                            !haystack.includes(query)
                        );
                    }
                );
        }
    );


    /*
    |--------------------------------------------------------------------------
    | RESTORE OLD INPUT
    |--------------------------------------------------------------------------
    */

    const oldQuantityKilos =
        Number(
            @json((float) old('quantity_kilos', 0))
        );

    const oldQuantityValue =
        Number(
            @json((float) old('quantity_value', 0))
        );

    const oldQuantityUnit =
        @json(old('quantity_unit', 'kg'));

    if (oldQuantityKilos > 0) {

        if (oldQuantityValue > 0) {
            quantityInput.value =
                oldQuantityValue;
        } else if (oldQuantityUnit === 'sack') {
            quantityInput.value =
                oldQuantityKilos /
                SACK_KILOS;
        } else {
            quantityInput.value =
                oldQuantityKilos;
        }
    }

    updateQuantity();


    const oldMillerId =
        document
            .getElementById('miller_id')
            .value;

    if (oldMillerId) {
        selectMillerById(
            oldMillerId
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE BEFORE SUBMIT
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('adminMillingForm')
        .addEventListener(
            'submit',
            function (event) {

                updateQuantity();
                updateCost();

                const kilos =
                    Number(
                        quantityKilos.value
                        || 0
                    );

                if (kilos <= 0) {
                    event.preventDefault();
                    alert('Please enter a valid Palay quantity.');
                    return;
                }

                if (
                    !document
                        .getElementById('miller_id')
                        .value
                ) {
                    event.preventDefault();
                    alert('Please select an OPEN Miller.');
                    return;
                }

                if (
                    transportPickup.checked &&
                    shippingWarning.classList.contains('show')
                ) {
                    event.preventDefault();
                    alert(shippingWarning.textContent);
                }
            }
        );
});
</script>

</body>
</html>