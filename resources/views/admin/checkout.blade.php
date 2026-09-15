<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Checkout | ANI-CARE Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Leaflet Map -->
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin="">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #f4f6f8;
            font-family: 'Segoe UI', sans-serif;
            color: #212529;
        }

        .checkout-container {
            max-width: 1000px;
            margin: auto;
            padding: 30px 18px 50px;
        }

        .checkout-title {
            color: #198754;
            font-weight: 700;
        }

        .checkout-card {
            border: 0;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .08);
        }

        .product-info {
            background: #f8fff9;
            border: 1px solid #d7f0df;
            border-radius: 10px;
            padding: 18px;
            margin-bottom: 24px;
        }

        .product-name {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .product-info p {
            margin-bottom: 5px;
        }

        .form-label {
            font-weight: 500;
        }

        .form-control,
        .form-select {
            min-height: 46px;
            border-radius: 8px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #198754;
            box-shadow: 0 0 0 .2rem rgba(25, 135, 84, .15);
        }

        .vat-summary-row {
            background: #f8faf9;
            border-radius: 6px;
            padding-left: 8px;
            padding-right: 8px;
        }

        .readonly-field {
            background: #f8f9fa !important;
        }

        /* ORDER TYPE */

        .order-type-option {
            position: relative;
            width: 100%;
        }

        .order-type-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .order-type-card {
            width: 100%;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 16px;
            cursor: pointer;
            transition: .2s ease;
            background: #fff;
        }

        .order-type-card:hover {
            border-color: #198754;
        }

        .order-type-option input:checked + .order-type-card {
            border-color: #198754;
            background: #edf9f2;
        }

        .order-type-icon {
            font-size: 28px;
            color: #198754;
        }

        /* PAYMENT */

        .payment-box {
            background: #fff8e1;
            border: 1px solid #ffe082;
            border-radius: 8px;
            padding: 14px;
        }

        /* SUMMARY */

        .order-summary {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 25px;
        }

        .summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 10px;
        }

        .summary-row.total {
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
            margin-top: 15px;
            font-size: 20px;
            font-weight: 700;
            color: #198754;
        }

        .shipping-free {
            color: #198754;
            font-weight: 700;
        }

        .shipping-error {
            color: #dc3545;
            font-weight: 600;
        }

        .distance-text {
            font-size: 13px;
            margin-top: 7px;
            color: #6c757d;
        }

        .shipping-loading {
            display: none;
            margin-top: 7px;
            font-size: 13px;
            color: #198754;
        }

        .btn-place-order {
            min-height: 50px;
            font-size: 17px;
            font-weight: 600;
            border-radius: 9px;
            padding-left: 28px;
            padding-right: 28px;
        }

        .route-map-wrap {
            margin-top: 12px;
            padding: 12px;
            background: #fff;
            border: 1px solid #dfe6e2;
            border-radius: 12px;
        }

        #routeMap {
            width: 100%;
            height: 320px;
            border-radius: 10px;
            overflow: hidden;
        }

        .map-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 10px;
            font-size: 13px;
            color: #6c757d;
        }

        .map-status {
            margin-top: 8px;
            font-size: 13px;
            color: #198754;
        }

        @media (max-width: 768px) {
            .checkout-container {
                padding: 20px 12px 40px;
            }

            .checkout-card .card-body {
                padding: 18px !important;
            }

            .checkout-title {
                font-size: 25px;
            }

            .product-name {
                font-size: 21px;
            }

            .btn-place-order {
                width: 100%;
            }


            #routeMap {
                height: 250px;
            }
        }

        .navbar .navbar-brand {
            font-size: 16px;
        }

        @media (max-width: 576px) {
            .navbar .container-fluid {
                display: flex;
                flex-wrap: nowrap;
                gap: 8px;
            }

            .navbar .navbar-brand {
                width: auto !important;
                flex: 1 1 auto;
                font-size: 14px;
                white-space: nowrap;
            }

            .navbar .btn {
                width: auto !important;
                flex: 0 0 auto;
                font-size: 11px;
            }

            .summary-row {
                align-items: flex-start;
            }

            .summary-row > :last-child {
                text-align: right;
            }
        }

    </style>
</head>

<body>

@php

    /*
    |--------------------------------------------------------------------------
    | PRODUCT
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | PRODUCT PRICE / CAVAN CONVERSION
    |--------------------------------------------------------------------------
    | Product price is stored PER KILOGRAM.
    | 1 sack = 1 cavan = 60 kilograms.
    */

    $kgPerSack = 60;

    $pricePerKg = (float) (
        $product->price_per_kg
        ?? $product->price
        ?? 0
    );

    /*
     * Whole sack/cavan pricing.
     * With ₱41.67/kg × 60 kg, rounded whole-cavan price becomes ₱2,500.
     * If you already save a sack/cavan price in the product, that value wins.
     */
    $pricePerSack = (float) (
        $product->price_per_sack
        ?? $product->price_per_cavan
        ?? round($pricePerKg * $kgPerSack)
    );

    $availableKilos = $product->kilos_available
        ?? $product->stock_kg
        ?? null;

    if ($availableKilos !== null) {
        $availableKilos = (float) $availableKilos;
        $availableSacks = (int) floor($availableKilos / $kgPerSack);
    } else {
        $availableSacks = $product->stock_sacks
            ?? $product->quantity_sacks
            ?? null;

        if ($availableSacks !== null) {
            $availableSacks = (int) $availableSacks;
            $availableKilos = $availableSacks * $kgPerSack;
        }
    }



    /*
    |--------------------------------------------------------------------------
    | BUYER / ADMIN
    |--------------------------------------------------------------------------
    */

    $buyer = $user;

    $buyerName =
        $buyer->fullname
        ?? $buyer->username
        ?? 'Admin';

    $buyerContact =
        $buyer->mobile_number
        ?? $buyer->contact_number
        ?? $buyer->phone
        ?? '';


    /*
    |--------------------------------------------------------------------------
    | BUYER ADDRESS
    |--------------------------------------------------------------------------
    |
    | Priority:
    | 1. users.address / complete_address
    | 2. users.barangay + municipality + province
    | 3. users.barangay + Allacapan, Cagayan
    |
    */

    $buyerAddress = trim((string) (
        $buyer->address
        ?? $buyer->complete_address
        ?? ''
    ));

    if ($buyerAddress === '') {

        $buyerBarangay = trim((string) ($buyer->barangay ?? ''));
        $buyerMunicipality = trim((string) ($buyer->municipality ?? ''));
        $buyerProvince = trim((string) ($buyer->province ?? ''));

        if ($buyerBarangay !== '') {

            $buyerAddressParts = array_values(array_filter([
                $buyerBarangay,
                $buyerMunicipality ?: 'Allacapan',
                $buyerProvince ?: 'Cagayan',
            ]));

            $buyerAddress = implode(', ', array_unique($buyerAddressParts));
        }
    }


    /*
    |--------------------------------------------------------------------------
    | BUYER COORDINATES
    |--------------------------------------------------------------------------
    */

    $buyerLatitude =
        $buyer->latitude ?? null;

    $buyerLongitude =
        $buyer->longitude ?? null;


    /*
    |--------------------------------------------------------------------------
    | FARMER / SELLER
    |--------------------------------------------------------------------------
    */

    $farmer = $product->user;


    /*
    |--------------------------------------------------------------------------
    | FARMER NAME
    |--------------------------------------------------------------------------
    */

    $farmerName =
        $farmer->fullname
        ?? $farmer->username
        ?? 'Unknown Farmer';


    /*
    |--------------------------------------------------------------------------
    | FARMER PROFILE
    |--------------------------------------------------------------------------
    |
    | Try known relationships if they exist.
    |
    */

    $farmerProfile = null;

    if ($farmer) {

        /*
         * Different ANI-CARE versions may use a different relationship name.
         * Check the common names instead of assuming only farmerProfile().
         */
        $profileRelations = [
            'farmerProfile',
            'farmProfile',
            'profile',
            'farm',
        ];

        foreach ($profileRelations as $relationName) {

            if (method_exists($farmer, $relationName)) {
                $candidate = $farmer->{$relationName};

                if ($candidate) {
                    $farmerProfile = $candidate;
                    break;
                }
            }
        }

        /*
         * TEMPORARY SAFETY FALLBACK:
         * If the User model does not expose a profile relationship, look for
         * the common farmer-profile tables by user_id/farmer_id. This makes
         * checkout still read the address saved on the My Farm Profile page.
         * For long-term cleanup, move this lookup to the controller/model.
         */
        if (!$farmerProfile) {

            $possibleProfileTables = [
                'farmer_profiles',
                'farm_profiles',
                'farmer_profile',
            ];

            foreach ($possibleProfileTables as $profileTable) {

                if (!\Illuminate\Support\Facades\Schema::hasTable($profileTable)) {
                    continue;
                }

                $possibleForeignKeys = ['user_id', 'farmer_id'];

                foreach ($possibleForeignKeys as $foreignKey) {

                    if (!\Illuminate\Support\Facades\Schema::hasColumn($profileTable, $foreignKey)) {
                        continue;
                    }

                    $candidate = \Illuminate\Support\Facades\DB::table($profileTable)
                        ->where($foreignKey, $farmer->id)
                        ->first();

                    if ($candidate) {
                        $farmerProfile = $candidate;
                        break 2;
                    }
                }
            }
        }
    }



    /*
    |--------------------------------------------------------------------------
    | FARMER / SELLER ADDRESS
    |--------------------------------------------------------------------------
    |
    | Priority:
    | 1. Farmer profile full address fields
    | 2. Farmer profile barangay/municipality/province
    | 3. users.address / users.complete_address
    | 4. users.barangay + municipality/province
    |
    | The old code skipped users.address completely. If the farmer's saved
    | address is in users.address, the checkout incorrectly showed
    | "Farmer address not available".
    |
    */

    $farmerAddress = '';

    if ($farmerProfile) {
        $farmerAddress = trim((string) (
            $farmerProfile->address
            ?? $farmerProfile->farm_address
            ?? $farmerProfile->complete_address
            ?? $farmerProfile->home_address
            ?? ''
        ));
    }

    if ($farmerAddress === '' && $farmerProfile) {

        $profileBarangay = trim((string) ($farmerProfile->barangay ?? ''));
        $profileMunicipality = trim((string) ($farmerProfile->municipality ?? ''));
        $profileProvince = trim((string) ($farmerProfile->province ?? ''));

        $addressParts = array_values(array_filter([
            $profileBarangay,
            $profileMunicipality,
            $profileProvince,
        ]));

        if (!empty($addressParts)) {
            $farmerAddress = implode(', ', array_unique($addressParts));
        }
    }

    /* IMPORTANT FALLBACK: farmer address saved directly in users table */
    if ($farmerAddress === '' && $farmer) {
        $farmerAddress = trim((string) (
            $farmer->address
            ?? $farmer->complete_address
            ?? $farmer->farm_address
            ?? $farmer->home_address
            ?? ''
        ));
    }

    if ($farmerAddress === '' && $farmer) {

        $farmerBarangay = trim((string) ($farmer->barangay ?? ''));
        $farmerMunicipality = trim((string) ($farmer->municipality ?? ''));
        $farmerProvince = trim((string) ($farmer->province ?? ''));

        if ($farmerBarangay !== '') {

            $farmerAddressParts = array_values(array_filter([
                $farmerBarangay,
                $farmerMunicipality ?: 'Allacapan',
                $farmerProvince ?: 'Cagayan',
            ]));

            $farmerAddress = implode(', ', array_unique($farmerAddressParts));
        }
    }

    if ($farmerAddress === '') {
        $farmerAddress = 'Farmer address not available';
    }


    /*
    |--------------------------------------------------------------------------
    | FARMER COORDINATES
    |--------------------------------------------------------------------------
    */

    $farmerLatitude =
        $farmerProfile?->latitude
        ?? $farmer?->latitude
        ?? null;

    $farmerLongitude =
        $farmerProfile?->longitude
        ?? $farmer?->longitude
        ?? null;

@endphp


<nav class="navbar navbar-dark" style="background:#198754;">
    <div class="container-fluid px-3">
        <span class="navbar-brand fw-bold m-0">
            <i class="bi bi-shield-check me-1"></i>
            ANI-CARE | Admin
        </span>

        <a href="{{ route('admin.market') }}"
           class="btn btn-outline-light btn-sm">
            <i class="bi bi-arrow-left"></i>
            Back to Marketplace
        </a>
    </div>
</nav>

<div class="checkout-container">

    {{-- HEADER --}}
    <div class="mb-4">
        <h2 class="checkout-title m-0">
            <i class="bi bi-cart-check"></i>
            Admin Checkout
        </h2>

        <div class="text-muted mt-1">
            Buy directly from a farmer using the same checkout workflow as Resident.
        </div>
    </div>


    {{-- ERRORS --}}
    @if($errors->any())
        <div class="alert alert-danger">

            <strong>
                <i class="bi bi-exclamation-triangle"></i>
                Unable to place order.
            </strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif


    <div class="card checkout-card">
        <div class="card-body p-4">

            {{-- PRODUCT --}}
            <div class="product-info">

                <div class="product-name">
                    {{ $product->name }}
                </div>

                <p class="text-muted">
                    <i class="bi bi-person"></i>
                    Seller:
                    <strong>{{ $farmerName }}</strong>
                </p>

                <p class="text-muted">
                    <i class="bi bi-geo-alt"></i>
                    Farmer Address:
                    <strong>{{ $farmerAddress }}</strong>
                </p>

                <p class="mb-0">
                    <i class="bi bi-tag"></i>
                    Per Kilo:
                    <strong class="text-success">
                        ₱{{ number_format($pricePerKg, 2) }} / kg
                    </strong>
                </p>

                <p class="mt-1 mb-0">
                    <i class="bi bi-box-seam"></i>
                    Per Sack / Cavan:
                    <strong class="text-success">
                        ₱{{ number_format($pricePerSack, 2) }}
                    </strong>
                    <span class="text-muted">({{ $kgPerSack }} kg)</span>
                </p>

                @if($availableSacks !== null)
                    <p class="mt-2 mb-0 text-muted">
                        <i class="bi bi-box-seam"></i>

                        Available:
                        <strong>
                            {{ number_format($availableSacks) }} sacks
                        </strong>
                    </p>
                @endif

            </div>


            <form method="POST"
                  action="{{ route('admin.checkout.place', $product->id) }}"
                  id="checkoutForm">

                @csrf


                {{-- COMPUTED VALUES --}}
                <input type="hidden"
                       name="shipping_fee"
                       id="shippingFeeInput"
                       value="0">

                <input type="hidden"
                       name="distance_km"
                       id="distanceInput"
                       value="0">

                <input type="hidden"
                       name="delivery_latitude"
                       id="deliveryLatitude">

                <input type="hidden"
                       name="delivery_longitude"
                       id="deliveryLongitude">

                <input type="hidden"
                       name="payment_method"
                       id="paymentMethodInput"
                       value="cash">

                <input type="hidden"
                       name="total_kilos"
                       id="totalKilosInput"
                       value="{{ $kgPerSack }}">

                <input type="hidden"
                       name="quantity_sacks"
                       id="quantitySacksInput"
                       value="1">

                <input type="hidden"
                       name="quantity_kilos"
                       id="quantityKilosInput"
                       value="{{ $kgPerSack }}">


                <div class="row g-3">

                    {{-- BUYER NAME --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Buyer Name
                        </label>

                        <input type="text"
                               name="buyer_name"
                               class="form-control"
                               value="{{ old('buyer_name', $buyerName) }}"
                               required>
                    </div>


                    {{-- CONTACT --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Contact Number
                        </label>

                        <input type="text"
                               name="contact_number"
                               class="form-control"
                               value="{{ old('contact_number', $buyerContact) }}"
                               required>
                    </div>


                    {{-- PURCHASE UNIT --}}
                    <div class="col-12">

                        <label class="form-label">
                            Buy Product By
                        </label>

                        <select name="purchase_unit"
                                id="purchaseUnit"
                                class="form-select"
                                required>

                            <option value="sack"
                                {{ old('purchase_unit', 'sack') === 'sack' ? 'selected' : '' }}>
                                Sack / Cavan ({{ $kgPerSack }} kg) — ₱{{ number_format($pricePerSack, 2) }} each
                            </option>

                            <option value="kilo"
                                {{ old('purchase_unit') === 'kilo' ? 'selected' : '' }}>
                                Per Kilo — ₱{{ number_format($pricePerKg, 2) }} / kg
                            </option>

                        </select>

                        <div class="form-text">
                            Choose a full <strong>Sack / Cavan</strong> or buy the exact amount <strong>Per Kilo</strong>.
                        </div>
                    </div>


                    {{-- DYNAMIC QUANTITY --}}
                    <div class="col-md-6">

                        <label class="form-label"
                               id="quantityLabel">
                            Quantity (Sacks / Cavan)
                        </label>

                        <div class="input-group">

                            <input type="number"
                                   name="order_quantity"
                                   id="orderQuantity"
                                   class="form-control"
                                   min="1"
                                   step="1"
                                   value="{{ old('order_quantity', 1) }}"
                                   required>

                            <span class="input-group-text"
                                  id="quantityUnitText">
                                Sack
                            </span>
                        </div>

                        <div class="form-text"
                             id="quantityHelpText"></div>
                    </div>


                    {{-- DYNAMIC PRICE --}}
                    <div class="col-md-6">

                        <label class="form-label"
                               id="priceFieldLabel">
                            Price Per Sack / Cavan
                        </label>

                        <input type="text"
                               id="priceDisplayInput"
                               class="form-control readonly-field"
                               value="₱{{ number_format($pricePerSack, 2) }} / sack"
                               readonly>
                    </div>


                    {{-- ORDER TYPE --}}
                    <div class="col-12">

                        <label class="form-label mb-2">
                            Order Type
                        </label>

                        <div class="row g-3">

                            {{-- DELIVERY --}}
                            <div class="col-md-6">

                                <label class="order-type-option">

                                    <input type="radio"
                                           name="fulfillment_type"
                                           value="delivery"
                                           id="deliveryOption"
                                           {{ old('fulfillment_type', 'delivery') === 'delivery' ? 'checked' : '' }}>

                                    <div class="order-type-card">

                                        <div class="d-flex align-items-center gap-3">

                                            <i class="bi bi-truck order-type-icon"></i>

                                            <div>
                                                <strong>
                                                    Delivery
                                                </strong>

                                                <div class="small text-muted">
                                                    Automatic buyer address
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </label>
                            </div>


                            {{-- PICKUP --}}
                            <div class="col-md-6">

                                <label class="order-type-option">

                                    <input type="radio"
                                           name="fulfillment_type"
                                           value="pickup"
                                           id="pickupOption"
                                           {{ old('fulfillment_type') === 'pickup' ? 'checked' : '' }}>

                                    <div class="order-type-card">

                                        <div class="d-flex align-items-center gap-3">

                                            <i class="bi bi-shop order-type-icon"></i>

                                            <div>
                                                <strong>
                                                    Pickup
                                                </strong>

                                                <div class="small text-success">
                                                    FREE SHIPPING
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </label>
                            </div>

                        </div>
                    </div>


                    {{-- DELIVERY ADDRESS --}}
                    <div class="col-12"
                         id="deliverySection">

                        <label class="form-label">
                            Delivery Address <span class="text-muted fw-normal">(Buyer)</span>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-geo-alt-fill text-success"></i>
                            </span>

                            <input type="text"
                                   name="delivery_address"
                                   id="deliveryAddress"
                                   class="form-control"
                                   value="{{ old('delivery_address', $buyerAddress) }}"
                                   placeholder="Enter Admin delivery address"
                                   required>

                        </div>

                        <div class="form-text">
                            <i class="bi bi-geo-alt"></i>
                            The shipping fee is calculated from the farmer location to the delivery address above.
                        </div>

                        <div id="shippingLoading"
                             class="shipping-loading">

                            <span class="spinner-border spinner-border-sm"></span>
                            Calculating distance and shipping fee...
                        </div>

                        <div id="distanceText"
                             class="distance-text">
                        </div>

                    </div>


{{-- PICKUP ADDRESS --}}
<div
    class="col-12"
    id="pickupSection"
    style="display:none;"
>

    <label class="form-label">
        Pickup Address <span class="text-muted fw-normal">(Farmer / Seller)</span>
    </label>

    <div class="input-group">

        <span class="input-group-text">
            <i class="bi bi-shop text-success"></i>
        </span>

        <input
            type="text"
            name="pickup_address"
            id="pickupAddress"
            class="form-control"
            value="{{ $farmerAddress }}"
            readonly
        >

    </div>


    @if($farmerAddress !== 'Farmer address not available')

        <div class="form-text text-success">

            <i class="bi bi-check-circle-fill"></i>

            Pickup location:
            <strong>{{ $farmerAddress }}</strong>

            • FREE SHIPPING

        </div>

    @else

        <div class="form-text text-danger">

            <i class="bi bi-exclamation-circle"></i>

            Farmer pickup address is not available.

        </div>

    @endif

</div>


                    {{-- LEAFLET DISTANCE MAP --}}
                    <div class="col-12" id="routeMapSection">

                        <label class="form-label">
                            <i class="bi bi-map"></i>
                            Admin Buyer ↔ Farmer Distance Map
                        </label>

                        <div class="route-map-wrap">
                            <div id="routeMap"></div>

                            <div class="map-legend">
                                <span>🔵 Admin / Delivery Location</span>
                                <span>🟢 Farmer / Seller Location</span>
                            </div>

                            <div id="mapStatus" class="map-status">
                                Locating buyer and farmer...
                            </div>
                        </div>
                    </div>


                    {{-- NOTES --}}
                    <div class="col-12">

                        <label class="form-label">
                            Notes
                            <span class="text-muted fw-normal">
                                (Optional)
                            </span>
                        </label>

                        <textarea name="notes"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Additional instructions for the farmer...">{{ old('notes') }}</textarea>
                    </div>

                </div>


                {{-- PAYMENT --}}
                <div class="mt-4">

                    <label class="form-label">
                        Payment Method
                    </label>

                    <div class="payment-box">

                        <div class="d-flex align-items-center gap-3">

                            <i id="paymentIcon"
                               class="bi bi-cash-stack fs-3 text-success">
                            </i>

                            <div>

                                <strong id="paymentMethodDisplay">
                                    Cash
                                </strong>

                                <div class="small text-muted"
                                     id="paymentRuleText">

                                    Orders below ₱100,000 are paid through Cash.

                                </div>

                            </div>
                        </div>

                    </div>
                </div>


                {{-- ORDER SUMMARY --}}
                <div class="order-summary">

                    <h5 class="fw-bold mb-3">
                        Order Summary
                    </h5>


                    <div class="summary-row">
                        <span id="summaryPriceLabel">
                            Price per Sack / Cavan
                        </span>

                        <strong id="summaryUnitPrice">
                            ₱{{ number_format($pricePerSack, 2) }} / sack
                        </strong>
                    </div>


                    <div class="summary-row">
                        <span>Quantity</span>

                        <strong id="summaryQuantity">
                            1 sack / cavan
                        </strong>
                    </div>

                    <div class="summary-row"
                         id="sackConversionRow">
                        <span>Kg per Sack / Cavan</span>
                        <strong>{{ $kgPerSack }} kg</strong>
                    </div>

                    <div class="summary-row">
                        <span>Total Weight</span>
                        <strong id="summaryWeight">{{ $kgPerSack }} kg</strong>
                    </div>


                    <div class="summary-row">
                        <span>Subtotal</span>

                        <strong id="subtotalDisplay">
                            ₱0.00
                        </strong>
                    </div>


                    <div class="summary-row">
                        <span>Shipping Fee</span>

                        <strong id="shippingDisplay">
                            Calculating...
                        </strong>
                    </div>

                    @if($vatEnabled)
    <div class="summary-row vat-summary-row">
        <span>VATable Sales</span>
        <strong id="vatableSalesDisplay">₱0.00</strong>
    </div>

    <div class="summary-row vat-summary-row">
        <span>VAT ({{ number_format((float)$vatRate, 2) }}%)</span>
        <strong id="vatAmountDisplay">₱0.00</strong>
    </div>
@endif


                    <div class="summary-row">
                        <span>Distance</span>

                        <strong id="distanceDisplay">
                            -
                        </strong>
                    </div>


                    <div class="summary-row total">
                        <span>Grand Total</span>

                        <span id="grandTotalDisplay">
                            ₱0.00
                        </span>
                    </div>

                </div>


                <div class="mt-4 d-flex justify-content-end">

                    <button type="submit"
                            class="btn btn-success btn-place-order"
                            id="placeOrderButton">

                        <i class="bi bi-bag-check"></i>
                        Place Order

                    </button>

                </div>

            </form>
        </div>
    </div>
</div>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | DATA FROM LARAVEL
    |--------------------------------------------------------------------------
    */

    const PRICE_PER_KG = Number(@json($pricePerKg));
    const PRICE_PER_SACK = Number(@json($pricePerSack));
    const KG_PER_SACK = Number(@json($kgPerSack));
    const AVAILABLE_KILOS = @json($availableKilos !== null ? (float) $availableKilos : null);
    const AVAILABLE_SACKS = @json($availableSacks !== null ? (int) $availableSacks : null);
    const VAT_ENABLED = @json($vatEnabled);
    const VAT_RATE = @json((float) $vatRate) / 100;

    const INITIAL_BUYER_ADDRESS = @json($buyerAddress);
    let BUYER_ADDRESS = (document.getElementById('deliveryAddress')?.value || INITIAL_BUYER_ADDRESS || '').trim();

    const FARMER_ADDRESS = @json($farmerAddress);

    let buyerLatitude = @json(
        $buyerLatitude !== null
            ? (float) $buyerLatitude
            : null
    );

    let buyerLongitude = @json(
        $buyerLongitude !== null
            ? (float) $buyerLongitude
            : null
    );

    let farmerLatitude = @json(
        $farmerLatitude !== null
            ? (float) $farmerLatitude
            : null
    );

    let farmerLongitude = @json(
        $farmerLongitude !== null
            ? (float) $farmerLongitude
            : null
    );


    /*
    |--------------------------------------------------------------------------
    | SHIPPING SETTINGS
    |--------------------------------------------------------------------------
    |
    | ₱50 base fee
    | + ₱10 per kilometer
    |
    */

    const BASE_SHIPPING_FEE = 50;
    const SHIPPING_RATE_PER_KM = 10;

    /*
    | ₱100,000 and above = Check
    | Below ₱100,000 = Cash
    */

    const CHECK_THRESHOLD = 100000;


    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const purchaseUnit =
        document.getElementById('purchaseUnit');

    const quantityInput =
        document.getElementById('orderQuantity');

    const quantityLabel =
        document.getElementById('quantityLabel');

    const quantityUnitText =
        document.getElementById('quantityUnitText');

    const quantityHelpText =
        document.getElementById('quantityHelpText');

    const priceFieldLabel =
        document.getElementById('priceFieldLabel');

    const priceDisplayInput =
        document.getElementById('priceDisplayInput');

    const quantitySacksInput =
        document.getElementById('quantitySacksInput');

    const quantityKilosInput =
        document.getElementById('quantityKilosInput');

    const deliveryOption =
        document.getElementById('deliveryOption');

    const pickupOption =
        document.getElementById('pickupOption');

    const deliverySection =
        document.getElementById('deliverySection');

    const pickupSection =
        document.getElementById('pickupSection');

    const deliveryAddress =
        document.getElementById('deliveryAddress');

    const pickupAddress =
        document.getElementById('pickupAddress');

    const shippingFeeInput =
        document.getElementById('shippingFeeInput');

    const distanceInput =
        document.getElementById('distanceInput');

    const deliveryLatitudeInput =
        document.getElementById('deliveryLatitude');

    const deliveryLongitudeInput =
        document.getElementById('deliveryLongitude');

    const paymentMethodInput =
        document.getElementById('paymentMethodInput');

    const totalKilosInput =
        document.getElementById('totalKilosInput');

    const subtotalDisplay =
        document.getElementById('subtotalDisplay');

    const shippingDisplay =
        document.getElementById('shippingDisplay');

    const vatableSalesDisplay =
        document.getElementById('vatableSalesDisplay');

    const vatAmountDisplay =
        document.getElementById('vatAmountDisplay');

    const totalSalesDisplay =
        document.getElementById('totalSalesDisplay');

    const distanceDisplay =
        document.getElementById('distanceDisplay');

    const grandTotalDisplay =
        document.getElementById('grandTotalDisplay');

    const summaryQuantity =
        document.getElementById('summaryQuantity');

    const summaryPriceLabel =
        document.getElementById('summaryPriceLabel');

    const summaryUnitPrice =
        document.getElementById('summaryUnitPrice');

    const sackConversionRow =
        document.getElementById('sackConversionRow');

    const paymentMethodDisplay =
        document.getElementById('paymentMethodDisplay');

    const paymentRuleText =
        document.getElementById('paymentRuleText');

    const paymentIcon =
        document.getElementById('paymentIcon');

    const shippingLoading =
        document.getElementById('shippingLoading');

    const distanceText =
        document.getElementById('distanceText');

    const checkoutForm =
        document.getElementById('checkoutForm');

    const routeMapSection =
        document.getElementById('routeMapSection');

    const mapStatus =
        document.getElementById('mapStatus');

    const summaryWeight =
        document.getElementById('summaryWeight');


    let currentShippingFee = 0;
    let currentDistance = 0;
    let shippingReady = false;

    /*
    |--------------------------------------------------------------------------
    | LEAFLET MAP
    |--------------------------------------------------------------------------
    */

    const routeMap = L.map('routeMap', {
        zoomControl: true
    }).setView([18.3625, 121.6400], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(routeMap);

    let buyerMarker = null;
    let farmerMarker = null;
    let routeLayer = null;

    function clearMapLocations() {
        if (buyerMarker) {
            routeMap.removeLayer(buyerMarker);
            buyerMarker = null;
        }

        if (farmerMarker) {
            routeMap.removeLayer(farmerMarker);
            farmerMarker = null;
        }

        if (routeLayer) {
            routeMap.removeLayer(routeLayer);
            routeLayer = null;
        }
    }

    async function showLocationsOnMap(farmerLocation, buyerLocation) {
        clearMapLocations();

        const farmerLatLng = L.latLng(
            Number(farmerLocation.lat),
            Number(farmerLocation.lon)
        );

        const buyerLatLng = L.latLng(
            Number(buyerLocation.lat),
            Number(buyerLocation.lon)
        );

        farmerMarker = L.marker(farmerLatLng)
            .addTo(routeMap)
            .bindPopup('<strong>Farmer / Seller</strong><br>' + FARMER_ADDRESS);

        buyerMarker = L.marker(buyerLatLng)
            .addTo(routeMap)
            .bindPopup('<strong>Admin / Delivery</strong><br>' + (deliveryAddress.value || BUYER_ADDRESS));

        /*
         * For shipping, first try the actual DRIVING route distance from OSRM.
         * OSRM uses OpenStreetMap road data. If routing is temporarily
         * unavailable, fall back to Leaflet's geographic distance.
         */
        try {
            const routeUrl =
                'https://router.project-osrm.org/route/v1/driving/' +
                farmerLocation.lon + ',' + farmerLocation.lat + ';' +
                buyerLocation.lon + ',' + buyerLocation.lat +
                '?overview=full&geometries=geojson&steps=false';

            const routeResponse = await fetch(routeUrl);

            if (routeResponse.ok) {
                const routeData = await routeResponse.json();

                if (
                    routeData &&
                    routeData.code === 'Ok' &&
                    routeData.routes &&
                    routeData.routes.length
                ) {
                    const route = routeData.routes[0];

                    routeLayer = L.geoJSON(route.geometry, {
                        style: {
                            weight: 5,
                            opacity: 0.8
                        }
                    }).addTo(routeMap);

                    routeMap.fitBounds(
                        routeLayer.getBounds(),
                        { padding: [35, 35] }
                    );

                    setTimeout(function () {
                        routeMap.invalidateSize();
                    }, 100);

                    return {
                        distanceKm: Number(route.distance) / 1000,
                        source: 'road'
                    };
                }
            }
        } catch (routeError) {
            console.warn('OSRM route error:', routeError);
        }

        /* Leaflet fallback: straight geographic distance. */
        routeLayer = L.polyline(
            [farmerLatLng, buyerLatLng],
            { weight: 4, opacity: 0.75, dashArray: '8,8' }
        ).addTo(routeMap);

        routeMap.fitBounds(
            L.latLngBounds([farmerLatLng, buyerLatLng]),
            { padding: [35, 35] }
        );

        setTimeout(function () {
            routeMap.invalidateSize();
        }, 100);

        return {
            distanceKm: routeMap.distance(farmerLatLng, buyerLatLng) / 1000,
            source: 'leaflet'
        };
    }



    /*
    |--------------------------------------------------------------------------
    | MONEY
    |--------------------------------------------------------------------------
    */

    function money(value) {

        return new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP'
        }).format(Number(value) || 0);
    }


    /*
    |--------------------------------------------------------------------------
    | GEOCODE AN ADDRESS
    |--------------------------------------------------------------------------
    */

    async function geocodeAddress(address) {

        if (
            !address ||
            address.trim().length < 3
        ) {
            return null;
        }

        let searchAddress =
            address.trim();

        if (
            !searchAddress
                .toLowerCase()
                .includes('philippines')
        ) {
            searchAddress += ', Philippines';
        }

        const url =
            'https://nominatim.openstreetmap.org/search' +
            '?format=json' +
            '&limit=1' +
            '&countrycodes=ph' +
            '&q=' +
            encodeURIComponent(searchAddress);

        try {

            const response =
                await fetch(url, {
                    headers: {
                        'Accept-Language': 'en'
                    }
                });

            if (!response.ok) {
                return null;
            }

            const result =
                await response.json();

            if (!result || !result.length) {
                return null;
            }

            return {
                lat: parseFloat(result[0].lat),
                lon: parseFloat(result[0].lon)
            };

        } catch (error) {

            console.error(
                'Geocoding error:',
                error
            );

            return null;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FARMER COORDINATES
    |--------------------------------------------------------------------------
    */

    async function getFarmerLocation() {

        if (
            farmerLatitude !== null &&
            farmerLongitude !== null &&
            !Number.isNaN(Number(farmerLatitude)) &&
            !Number.isNaN(Number(farmerLongitude))
        ) {
            return {
                lat: Number(farmerLatitude),
                lon: Number(farmerLongitude)
            };
        }

        if (
            !FARMER_ADDRESS ||
            FARMER_ADDRESS ===
                'Farmer address not available'
        ) {
            return null;
        }

        const result =
            await geocodeAddress(
                FARMER_ADDRESS
            );

        if (!result) {
            return null;
        }

        farmerLatitude =
            result.lat;

        farmerLongitude =
            result.lon;

        return result;
    }


    /*
    |--------------------------------------------------------------------------
    | BUYER COORDINATES
    |--------------------------------------------------------------------------
    */

    async function getBuyerLocation() {

        const currentAddress =
            (deliveryAddress.value || '').trim();

        if (!currentAddress) {
            return null;
        }

        /*
         * If the Admin did not change the saved address and we already
         * have saved coordinates, reuse them.
         */
        if (
            currentAddress === (INITIAL_BUYER_ADDRESS || '').trim() &&
            buyerLatitude !== null &&
            buyerLongitude !== null &&
            !Number.isNaN(Number(buyerLatitude)) &&
            !Number.isNaN(Number(buyerLongitude))
        ) {
            return {
                lat: Number(buyerLatitude),
                lon: Number(buyerLongitude)
            };
        }

        /*
         * Admin can type a different delivery address.
         * Geocode the CURRENT form value so shipping matches that address.
         */
        const result =
            await geocodeAddress(currentAddress);

        if (!result) {
            return null;
        }

        BUYER_ADDRESS = currentAddress;
        buyerLatitude = result.lat;
        buyerLongitude = result.lon;

        return result;
    }


    /*
    |--------------------------------------------------------------------------
    | SHIPPING
    |--------------------------------------------------------------------------
    */

    async function calculateShipping() {

        if (pickupOption.checked) {

            currentShippingFee = 0;
            currentDistance = 0;
            shippingReady = true;

            shippingFeeInput.value = '0.00';
            distanceInput.value = '0.00';

            deliveryLatitudeInput.value = '';
            deliveryLongitudeInput.value = '';

            shippingDisplay.innerHTML =
                '<span class="shipping-free">FREE SHIPPING</span>';

            distanceDisplay.textContent =
                'Pickup';

            distanceText.textContent =
                'Pickup orders have no shipping fee.';

            updateTotals();

            return;
        }


        shippingReady = false;

        shippingLoading.style.display =
            'block';

        shippingDisplay.textContent =
            'Calculating...';

        distanceDisplay.textContent =
            '-';

        distanceText.textContent =
            '';


        const farmerLocation =
            await getFarmerLocation();

        if (!farmerLocation) {

            shippingLoading.style.display =
                'none';

            shippingDisplay.innerHTML =
                '<span class="shipping-error">Unable to calculate</span>';

            distanceText.textContent =
                'Farmer location could not be determined.';

            mapStatus.innerHTML =
                '<span class="text-danger">Farmer address could not be located on the map.</span>';

            currentShippingFee = 0;
            currentDistance = 0;

            updateTotals();

            return;
        }


        const buyerLocation =
            await getBuyerLocation();

        if (!buyerLocation) {

            shippingLoading.style.display =
                'none';

            shippingDisplay.innerHTML =
                '<span class="shipping-error">Unable to calculate</span>';

            distanceText.textContent =
                'Buyer location could not be determined from the registered address.';

            mapStatus.innerHTML =
                '<span class="text-danger">Buyer address could not be located on the map.</span>';

            currentShippingFee = 0;
            currentDistance = 0;

            updateTotals();

            return;
        }


        const routeResult =
            await showLocationsOnMap(
                farmerLocation,
                buyerLocation
            );

        currentDistance =
            routeResult.distanceKm;

        if (routeResult.source === 'road') {
            mapStatus.innerHTML =
                '<i class="bi bi-check-circle-fill"></i> ' +
                'Driving distance: <strong>' +
                currentDistance.toFixed(2) +
                ' km</strong>';
        } else {
            mapStatus.innerHTML =
                '<i class="bi bi-exclamation-circle"></i> ' +
                'Road route unavailable. Leaflet straight-line fallback: <strong>' +
                currentDistance.toFixed(2) +
                ' km</strong>';
        }


        currentShippingFee =
            Math.ceil(
                BASE_SHIPPING_FEE +
                (
                    currentDistance *
                    SHIPPING_RATE_PER_KM
                )
            );


        shippingReady = true;

        shippingFeeInput.value =
            currentShippingFee.toFixed(2);

        distanceInput.value =
            currentDistance.toFixed(2);

        deliveryLatitudeInput.value =
            buyerLocation.lat;

        deliveryLongitudeInput.value =
            buyerLocation.lon;


        shippingLoading.style.display =
            'none';

        shippingDisplay.textContent =
            money(currentShippingFee);

        distanceDisplay.textContent =
            currentDistance.toFixed(2) +
            ' km';

        distanceText.innerHTML =
            '<i class="bi bi-truck"></i> ' +
            currentDistance.toFixed(2) +
            ' km from the farmer. Shipping: ' +
            money(currentShippingFee);

        updateTotals();
    }


    /*
    |--------------------------------------------------------------------------
    | TOTALS + PAYMENT
    |--------------------------------------------------------------------------
    */

    function updateTotals() {

        const unit = purchaseUnit.value;

        let quantity =
            parseFloat(quantityInput.value) || 0;

        if (quantity < 0) {
            quantity = 0;
        }

        let totalWeight = 0;
        let subtotal = 0;

        if (unit === 'sack') {

            totalWeight =
                quantity * KG_PER_SACK;

            subtotal =
                quantity * PRICE_PER_SACK;

            quantitySacksInput.value =
                quantity;

            quantityKilosInput.value =
                totalWeight.toFixed(2);

            summaryPriceLabel.textContent =
                'Price per Sack / Cavan';

            summaryUnitPrice.textContent =
                money(PRICE_PER_SACK) + ' / sack';

            summaryQuantity.textContent =
                quantity +
                (
                    quantity === 1
                        ? ' sack / cavan'
                        : ' sacks / cavan'
                );

            sackConversionRow.style.display =
                'flex';

        } else {

            totalWeight =
                quantity;

            subtotal =
                quantity * PRICE_PER_KG;

            quantitySacksInput.value =
                quantity > 0
                    ? (quantity / KG_PER_SACK).toFixed(4)
                    : '0';

            quantityKilosInput.value =
                quantity.toFixed(2);

            summaryPriceLabel.textContent =
                'Price per Kilo';

            summaryUnitPrice.textContent =
                money(PRICE_PER_KG) + ' / kg';

            summaryQuantity.textContent =
                quantity.toLocaleString('en-PH') + ' kg';

            sackConversionRow.style.display =
                'none';
        }

        totalKilosInput.value =
            totalWeight.toFixed(2);

        summaryWeight.textContent =
            totalWeight.toLocaleString(
                'en-PH',
                { maximumFractionDigits: 2 }
            ) + ' kg';

        subtotalDisplay.textContent =
            money(subtotal);

        const shipping =
            deliveryOption.checked
                ? currentShippingFee
                : 0;

        const grandTotal =
            subtotal +
            shipping;

        if (VAT_ENABLED && vatableSalesDisplay && vatAmountDisplay && totalSalesDisplay) {
            const vatableSales =
                Math.round((grandTotal / (1 + VAT_RATE)) * 100) / 100;
            const vatAmount =
                Math.round((grandTotal - vatableSales) * 100) / 100;

            vatableSalesDisplay.textContent = money(vatableSales);
            vatAmountDisplay.textContent = money(vatAmount);
            totalSalesDisplay.textContent = money(grandTotal);
        }

        grandTotalDisplay.textContent =
            money(grandTotal);


        /*
        | AUTOMATIC PAYMENT
        */

        if (
            grandTotal >=
            CHECK_THRESHOLD
        ) {

            paymentMethodInput.value =
                'check';

            paymentMethodDisplay.textContent =
                'Check';

            paymentRuleText.textContent =
                'Orders worth ₱100,000 and above must be paid through Check.';

            paymentIcon.className =
                'bi bi-bank fs-3 text-success';

        } else {

            paymentMethodInput.value =
                'cash';

            paymentMethodDisplay.textContent =
                'Cash';

            paymentRuleText.textContent =
                'Orders below ₱100,000 are paid through Cash.';

            paymentIcon.className =
                'bi bi-cash-stack fs-3 text-success';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PURCHASE UNIT
    |--------------------------------------------------------------------------
    */

    function changePurchaseUnit(resetQuantity = true) {

        const unit =
            purchaseUnit.value;

        if (resetQuantity) {
            quantityInput.value = 1;
        }

        quantityInput.min = '1';
        quantityInput.step = '1';

        if (unit === 'sack') {

            quantityLabel.textContent =
                'Quantity (Sacks / Cavan)';

            quantityUnitText.textContent =
                'Sack';

            priceFieldLabel.textContent =
                'Price Per Sack / Cavan';

            priceDisplayInput.value =
                money(PRICE_PER_SACK) + ' / sack';

            if (AVAILABLE_SACKS !== null) {

                quantityInput.max =
                    String(AVAILABLE_SACKS);

                quantityHelpText.textContent =
                    Number(AVAILABLE_SACKS).toLocaleString('en-PH') +
                    ' sack(s) available';

            } else {

                quantityInput.removeAttribute('max');

                quantityHelpText.textContent =
                    '1 sack / cavan = ' +
                    KG_PER_SACK +
                    ' kg';
            }

        } else {

            quantityLabel.textContent =
                'Quantity (Kilograms)';

            quantityUnitText.textContent =
                'kg';

            priceFieldLabel.textContent =
                'Price Per Kilo';

            priceDisplayInput.value =
                money(PRICE_PER_KG) + ' / kg';

            if (AVAILABLE_KILOS !== null) {

                quantityInput.max =
                    String(AVAILABLE_KILOS);

                quantityHelpText.textContent =
                    Number(AVAILABLE_KILOS).toLocaleString('en-PH') +
                    ' kg available';

            } else {

                quantityInput.removeAttribute('max');

                quantityHelpText.textContent =
                    'Enter how many kilograms you want to order.';
            }
        }

        updateTotals();
    }


    /*
    |--------------------------------------------------------------------------
    | ORDER TYPE
    |--------------------------------------------------------------------------
    */

    async function changeOrderType() {

    /*
    |--------------------------------------------------------------------------
    | DELIVERY
    |--------------------------------------------------------------------------
    */

    if (deliveryOption.checked) {

        deliverySection.style.display = 'block';
        pickupSection.style.display = 'none';
        routeMapSection.style.display = 'block';

        setTimeout(function () {
            routeMap.invalidateSize();
        }, 100);

        if (!deliveryAddress.value.trim()) {
            deliveryAddress.value =
                BUYER_ADDRESS || INITIAL_BUYER_ADDRESS || '';
        }

        BUYER_ADDRESS =
            deliveryAddress.value.trim();

        pickupAddress.value =
            FARMER_ADDRESS || '';

        deliveryAddress.required = true;

        currentShippingFee = 0;
        currentDistance = 0;
        shippingReady = false;

        shippingFeeInput.value = '0.00';
        distanceInput.value = '0.00';

        shippingDisplay.textContent =
            'Calculating...';

        distanceDisplay.textContent =
            '-';

        updateTotals();


        if (
            deliveryAddress.value &&
            deliveryAddress.value.trim() !== ''
        ) {

            await calculateShipping();

        } else {

            shippingLoading.style.display =
                'none';

            shippingDisplay.innerHTML =
                '<span class="shipping-error">Buyer address unavailable</span>';

            distanceDisplay.textContent =
                '-';

            distanceText.textContent =
                'Please update your registered address.';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PICKUP
    |--------------------------------------------------------------------------
    */

    if (pickupOption.checked) {

        deliverySection.style.display =
            'none';

        pickupSection.style.display =
            'block';

        routeMapSection.style.display =
            'none';


        /*
        | Seller/Farmer pickup address
        */

        pickupAddress.value =
            FARMER_ADDRESS || 'Farmer address not available';


        deliveryAddress.required =
            false;


        /*
        | Pickup = FREE SHIPPING
        */

        currentShippingFee = 0;
        currentDistance = 0;
        shippingReady = true;

        shippingFeeInput.value =
            '0.00';

        distanceInput.value =
            '0.00';

        deliveryLatitudeInput.value =
            '';

        deliveryLongitudeInput.value =
            '';

        shippingLoading.style.display =
            'none';

        shippingDisplay.innerHTML =
            '<span class="shipping-free">FREE SHIPPING</span>';

        distanceDisplay.textContent =
            'Pickup';

        distanceText.textContent =
            'Pickup orders have no shipping fee.';

        updateTotals();
    }
}


/*
|--------------------------------------------------------------------------
| ADMIN DELIVERY ADDRESS CHANGE
|--------------------------------------------------------------------------
|
| Recalculate the map, road distance and shipping fee whenever the Admin
| changes the delivery address.
|
*/
let deliveryAddressTimer = null;

deliveryAddress.addEventListener('input', function () {

    BUYER_ADDRESS =
        deliveryAddress.value.trim();

    buyerLatitude = null;
    buyerLongitude = null;
    shippingReady = false;

    clearTimeout(deliveryAddressTimer);

    deliveryAddressTimer = setTimeout(function () {
        if (deliveryOption.checked && BUYER_ADDRESS !== '') {
            calculateShipping();
        }
    }, 700);
});


/*
|--------------------------------------------------------------------------
| ORDER TYPE EVENTS
|--------------------------------------------------------------------------
*/

deliveryOption.addEventListener(
    'change',
    function () {
        changeOrderType();
    }
);

pickupOption.addEventListener(
    'change',
    function () {
        changeOrderType();
    }
);


/*
|--------------------------------------------------------------------------
| PURCHASE UNIT CHANGE
|--------------------------------------------------------------------------
*/

purchaseUnit.addEventListener(
    'change',
    function () {
        changePurchaseUnit(true);
    }
);


/*
|--------------------------------------------------------------------------
| QUANTITY CHANGE
|--------------------------------------------------------------------------
*/

quantityInput.addEventListener(
    'input',
    function () {
        updateTotals();
    }
);


/*
|--------------------------------------------------------------------------
| FORM VALIDATION
|--------------------------------------------------------------------------
*/

checkoutForm.addEventListener(
    'submit',
    function (event) {

        const quantity =
            parseFloat(quantityInput.value) || 0;

        const unit =
            purchaseUnit.value;


        /*
        | Minimum quantity
        */

        if (quantity < 1) {

            event.preventDefault();

            alert(
                unit === 'sack'
                    ? 'Please enter at least 1 sack / cavan.'
                    : 'Please enter at least 1 kilogram.'
            );

            quantityInput.focus();

            return;
        }


        /*
        | Stock limits
        */

        if (
            unit === 'sack' &&
            AVAILABLE_SACKS !== null &&
            quantity > Number(AVAILABLE_SACKS)
        ) {

            event.preventDefault();

            alert(
                'Only ' +
                AVAILABLE_SACKS +
                ' sack(s) are available.'
            );

            quantityInput.focus();

            return;
        }

        if (
            unit === 'kilo' &&
            AVAILABLE_KILOS !== null &&
            quantity > Number(AVAILABLE_KILOS)
        ) {

            event.preventDefault();

            alert(
                'Only ' +
                AVAILABLE_KILOS +
                ' kg are available.'
            );

            quantityInput.focus();

            return;
        }


        /*
        | DELIVERY
        */

        if (deliveryOption.checked) {

            if (
                !deliveryAddress.value ||
                deliveryAddress.value.trim() === ''
            ) {

                event.preventDefault();

                alert(
                    'Please enter a delivery address before placing the order.'
                );

                return;
            }


            if (!shippingReady) {

                event.preventDefault();

                alert(
                    'Please wait until the shipping fee is calculated.'
                );

                return;
            }
        }


        /*
        | PICKUP
        */

        if (pickupOption.checked) {

            if (
                !FARMER_ADDRESS ||
                FARMER_ADDRESS ===
                    'Farmer address not available'
            ) {

                event.preventDefault();

                alert(
                    'The farmer pickup address is unavailable.'
                );

                return;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | FINAL PAYMENT METHOD
        |--------------------------------------------------------------------------
        */

        updateTotals();
    }
);


/*
|--------------------------------------------------------------------------
| INITIALIZE
|--------------------------------------------------------------------------
*/

changePurchaseUnit(false);

changeOrderType();

});
</script>

</body>
</html>