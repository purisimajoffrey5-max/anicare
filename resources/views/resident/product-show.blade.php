<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $product->name ?? 'Product Details' }} | ANI-CARE</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <style>
        :root {
            --green: #198754;
            --green-dark: #146c43;
            --green-soft: #eaf7f0;
            --bg: #f4f6f9;
            --card: #ffffff;
            --text: #20252b;
            --muted: #6c757d;
            --border: #e6ebe8;
            --danger: #dc3545;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        a {
            text-decoration: none;
        }

        /* =====================================================
           COMPACT TOPBAR
        ====================================================== */

        .product-topbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            background: var(--green);
            color: #fff;
            box-shadow: 0 3px 14px rgba(0, 0, 0, .10);
        }

        .product-topbar-inner {
            width: min(1050px, 100%);
            min-height: 58px;
            margin: 0 auto;
            padding: 9px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .product-topbar-title {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 17px;
            font-weight: 800;
        }

        .product-topbar-title span {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .back-icon-btn {
            width: 39px;
            height: 39px;
            flex: 0 0 39px;
            border-radius: 11px;
            display: grid;
            place-items: center;
            background: rgba(255,255,255,.16);
            color: #fff;
            font-size: 18px;
        }

        .back-icon-btn:hover {
            background: #fff;
            color: var(--green);
        }

        .market-link {
            min-height: 38px;
            padding: 8px 11px;
            border-radius: 10px;
            background: #fff;
            color: var(--green);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        /* =====================================================
           PAGE
        ====================================================== */

        .product-page {
            width: min(1050px, 100%);
            margin: 0 auto;
            padding: 22px 14px 55px;
        }

        .product-detail-card {
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 20px;
            background: var(--card);
            box-shadow: 0 9px 28px rgba(15, 23, 42, .07);

            display: grid;
            grid-template-columns: minmax(0, .95fr) minmax(0, 1.05fr);
        }

        /* =====================================================
           IMAGE
        ====================================================== */

        .product-image-wrap {
            position: relative;
            min-width: 0;
            background: #eef1ef;
        }

        .product-main-image {
            display: block;
            width: 100%;
            height: 100%;
            min-height: 520px;
            object-fit: cover;
        }

        .product-no-image {
            width: 100%;
            min-height: 520px;
            display: grid;
            place-items: center;
            color: #8b9298;
            background: #eef1ef;
            text-align: center;
        }

        .product-type-badge {
            position: absolute;
            left: 14px;
            top: 14px;
            padding: 6px 11px;
            border-radius: 999px;
            background: var(--green);
            color: #fff;
            font-size: 11px;
            font-weight: 800;
            box-shadow: 0 3px 10px rgba(0,0,0,.12);
        }

        /* =====================================================
           DETAILS
        ====================================================== */

        .product-details {
            min-width: 0;
            padding: 30px;
            display: flex;
            flex-direction: column;
        }

        .product-eyebrow {
            margin-bottom: 7px;
            color: var(--green);
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .product-name {
            margin: 0;
            font-size: 34px;
            line-height: 1.12;
            font-weight: 850;
            overflow-wrap: anywhere;
        }

        .product-price-row {
            margin-top: 17px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px;
        }

        .product-price {
            color: var(--green);
            font-size: 38px;
            line-height: 1;
            font-weight: 850;
        }

        .product-price small {
            color: #4f565d;
            font-size: 17px;
            font-weight: 700;
        }

        .stock-pill {
            padding: 7px 11px;
            border-radius: 999px;
            color: #0f5132;
            background: #d1e7dd;
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }

        .stock-pill.out {
            color: #842029;
            background: #f8d7da;
        }

        .details-grid {
            margin-top: 23px;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .detail-box {
            min-width: 0;
            padding: 13px;
            border: 1px solid var(--border);
            border-radius: 13px;
            background: #fbfdfc;
        }

        .detail-label {
            margin-bottom: 4px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
        }

        .detail-value {
            color: #2b3136;
            font-size: 14px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .detail-value.text-green {
            color: var(--green);
        }

        .product-description {
            margin-top: 18px;
            padding: 14px;
            border-radius: 13px;
            background: #f7faf8;
            border: 1px solid var(--border);
        }

        .product-description-title {
            margin-bottom: 5px;
            font-size: 12px;
            font-weight: 800;
            color: #40474d;
        }

        .product-description-text {
            margin: 0;
            color: #687078;
            font-size: 13px;
            line-height: 1.55;
            overflow-wrap: anywhere;
        }

        .buy-area {
            margin-top: auto;
            padding-top: 22px;
        }

        .buy-btn {
            min-height: 52px;
            width: 100%;
            border: 0;
            border-radius: 13px;
            background: var(--green);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 16px;
            font-weight: 800;
            box-shadow: 0 7px 16px rgba(25,135,84,.18);
        }

        .buy-btn:hover {
            background: var(--green-dark);
            color: #fff;
        }

        .buy-btn.disabled {
            pointer-events: none;
            background: #adb5bd;
            box-shadow: none;
        }

        /* =====================================================
           MOBILE
        ====================================================== */

        @media (max-width: 767.98px) {

            .product-topbar-inner {
                min-height: 54px;
                padding: 7px 9px;
            }

            .product-topbar-title {
                font-size: 14px;
            }

            .back-icon-btn {
                width: 37px;
                height: 37px;
                flex-basis: 37px;
            }

            .market-link {
                min-height: 36px;
                padding: 7px 9px;
                font-size: 0;
            }

            .market-link i {
                font-size: 17px;
            }

            .product-page {
                padding: 12px 9px 28px;
            }

            .product-detail-card {
                grid-template-columns: 1fr;
                border-radius: 16px;
            }

            .product-main-image,
            .product-no-image {
                min-height: 0;
                height: 235px;
            }

            .product-main-image {
                object-fit: cover;
            }

            .product-type-badge {
                left: 10px;
                top: 10px;
                padding: 5px 9px;
                font-size: 9.5px;
            }

            .product-details {
                padding: 17px 14px 15px;
            }

            .product-eyebrow {
                margin-bottom: 4px;
                font-size: 10px;
            }

            .product-name {
                font-size: 23px;
                line-height: 1.15;
            }

            .product-price-row {
                margin-top: 12px;
                align-items: center;
            }

            .product-price {
                font-size: 29px;
            }

            .product-price small {
                font-size: 14px;
            }

            .stock-pill {
                padding: 6px 9px;
                font-size: 9.5px;
            }

            .details-grid {
                margin-top: 16px;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 8px;
            }

            .detail-box {
                padding: 10px;
                border-radius: 11px;
            }

            .detail-label {
                font-size: 9.5px;
            }

            .detail-value {
                font-size: 12px;
                line-height: 1.35;
            }

            .product-description {
                margin-top: 12px;
                padding: 11px;
            }

            .product-description-title {
                font-size: 11px;
            }

            .product-description-text {
                font-size: 11.5px;
            }

            .buy-area {
                padding-top: 14px;
            }

            .buy-btn {
                min-height: 48px;
                border-radius: 11px;
                font-size: 14px;
            }
        }

        @media (max-width: 390px) {

            .product-main-image,
            .product-no-image {
                height: 210px;
            }

            .product-name {
                font-size: 21px;
            }

            .product-price {
                font-size: 27px;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }

            .detail-box {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
            }

            .detail-label {
                margin: 0;
            }

            .detail-value {
                text-align: right;
            }
        }
    </style>
</head>

<body>

@php
    $img =
        !empty($product->photo_path)
            ? asset('storage/'.$product->photo_path)
            : null;

    $stock =
        (float) (
            $product->kilos_available
            ?? $product->stock_kg
            ?? 0
        );

    $price =
        (float) (
            $product->price_per_kg
            ?? $product->price
            ?? 0
        );

    $sellerName =
        $product->user->fullname
        ?? $product->user->username
        ?? 'Unknown Farmer';

    $type =
        ucfirst(
            strtolower(
                (string)($product->type ?? 'Rice')
            )
        );

    $description =
        trim(
            (string) (
                $product->description
                ?? ''
            )
        );
@endphp

{{-- =========================================================
     TOPBAR
========================================================= --}}
<header class="product-topbar">

    <div class="product-topbar-inner">

        <div class="product-topbar-title">

            <a
                href="{{ route('resident.marketplace') }}"
                class="back-icon-btn"
                title="Back to Marketplace"
            >
                <i class="bi bi-arrow-left"></i>
            </a>

            <span>
                Product Details
            </span>

        </div>

        <a
            href="{{ route('resident.marketplace') }}"
            class="market-link"
            title="Marketplace"
        >
            <i class="bi bi-shop"></i>
            <span>Marketplace</span>
        </a>

    </div>

</header>

{{-- =========================================================
     PRODUCT
========================================================= --}}
<main class="product-page">

    <section class="product-detail-card">

        {{-- IMAGE --}}
        <div class="product-image-wrap">

            @if($img)

                <img
                    src="{{ $img }}"
                    class="product-main-image"
                    alt="{{ $product->name }}"
                >

            @else

                <div class="product-no-image">

                    <div>
                        <i class="bi bi-image fs-1"></i>
                        <div class="mt-2">
                            No product photo
                        </div>
                    </div>

                </div>

            @endif

            <span class="product-type-badge">
                {{ strtoupper($type) }}
            </span>

        </div>

        {{-- DETAILS --}}
        <div class="product-details">

            <div class="product-eyebrow">
                ANI-CARE Marketplace
            </div>

            <h1 class="product-name">
                {{ $product->name }}
            </h1>

            <div class="product-price-row">

                <div class="product-price">
                    ₱{{ number_format($price, 2) }}
                    <small>/ kg</small>
                </div>

                @if($stock > 0)

                    <span class="stock-pill">
                        <i class="bi bi-check-circle-fill me-1"></i>
                        In Stock
                    </span>

                @else

                    <span class="stock-pill out">
                        <i class="bi bi-x-circle-fill me-1"></i>
                        Sold Out
                    </span>

                @endif

            </div>

            {{-- INFO --}}
            <div class="details-grid">

                <div class="detail-box">
                    <div class="detail-label">
                        Product Type
                    </div>

                    <div class="detail-value">
                        {{ $type }}
                    </div>
                </div>

                <div class="detail-box">
                    <div class="detail-label">
                        Available Stock
                    </div>

                    <div class="detail-value text-green">
                        {{ number_format($stock, 2) }} kg
                    </div>
                </div>

                <div class="detail-box">
                    <div class="detail-label">
                        Seller / Farmer
                    </div>

                    <div class="detail-value">
                        {{ $sellerName }}
                    </div>
                </div>

                <div class="detail-box">
                    <div class="detail-label">
                        Price per Kilogram
                    </div>

                    <div class="detail-value text-green">
                        ₱{{ number_format($price, 2) }}
                    </div>
                </div>

            </div>

            @if($description !== '')

                <div class="product-description">

                    <div class="product-description-title">
                        Product Description
                    </div>

                    <p class="product-description-text">
                        {{ $description }}
                    </p>

                </div>

            @endif

            {{-- BUY --}}
            <div class="buy-area">

                <a
                    href="{{ route('resident.checkout.show', $product->id) }}"
                    class="buy-btn {{ $stock <= 0 ? 'disabled' : '' }}"
                >
                    <i class="bi bi-cart-fill"></i>

                    {{ $stock > 0 ? 'Buy Now' : 'Out of Stock' }}
                </a>

            </div>

        </div>

    </section>

</main>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>