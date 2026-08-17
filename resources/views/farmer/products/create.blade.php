<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>Post Rice Product | ANI-CARE</title>

    <meta name="viewport"
          content="width=device-width, initial-scale=1, viewport-fit=cover">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            width: 100%;
            overflow-x: hidden;
        }

        body {
            width: 100%;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
            background: #eef3f8;
            font-family: 'Segoe UI', sans-serif;
            color: #333;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        /*========================================
        PAGE CONTAINER
        ========================================*/

        .product-page {
            width: 100%;
            max-width: 850px;
            margin: 0 auto;
            padding: 28px 18px 50px;
        }

        /*========================================
        PAGE HEADER
        ========================================*/

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 28px;
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: 10px;

            margin: 0 0 7px;

            font-size: 36px;
            line-height: 1.2;
            font-weight: 750;

            color: #198754;
        }

        .page-title-icon {
            font-size: 36px;
        }

        .page-subtitle {
            max-width: 600px;
            color: #6c757d;
            font-size: 16px;
            line-height: 1.6;
        }

        .back-btn {
            flex-shrink: 0;
            min-height: 44px;
            border-radius: 10px;
            padding: 9px 16px;
        }

        /*========================================
        CARD
        ========================================*/

        .product-card {
            border: 0;
            border-radius: 22px;
            overflow: hidden;

            background: #fff;

            box-shadow:
                0 12px 35px rgba(0, 0, 0, .08);
        }

        .product-card .card-body {
            padding: 32px;
        }

        /*========================================
        INFORMATION BOX
        ========================================*/

        .conversion-info {
            display: flex;
            gap: 14px;
            align-items: flex-start;

            padding: 16px 18px;
            margin-bottom: 28px;

            border: 1px solid #badbcc;
            border-radius: 13px;

            background: #ecf8f1;
        }

        .conversion-info-icon {
            width: 42px;
            height: 42px;
            flex: 0 0 42px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #198754;
            color: #fff;

            font-size: 20px;
        }

        .conversion-title {
            color: #146c43;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .conversion-description {
            color: #4f6358;
            font-size: 14px;
            line-height: 1.5;
        }

        /*========================================
        FORM
        ========================================*/

        .form-group {
            margin-bottom: 23px;
        }

        .form-label {
            display: block;
            margin-bottom: 9px;

            color: #198754;

            font-size: 15px;
            font-weight: 700;
        }

        .form-label i {
            margin-right: 5px;
        }

        .form-control,
        .form-select {
            width: 100%;
            min-height: 54px;

            padding: 12px 14px;

            border: 1px solid #d5d9dd;
            border-radius: 11px;

            font-size: 16px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #198754;

            box-shadow:
                0 0 0 .22rem
                rgba(25, 135, 84, .13);
        }

        .input-group-text {
            min-width: 54px;

            display: flex;
            justify-content: center;

            background: #f8f9fa;

            font-weight: 600;
        }

        .form-text {
            margin-top: 7px;
            color: #6c757d;
            font-size: 13px;
        }

        /*========================================
        STOCK CONVERSION
        ========================================*/

        .conversion-result {
            display: none;

            margin-top: 10px;
            padding: 12px 14px;

            border-radius: 9px;

            background: #f3f9f5;
            border: 1px solid #d4edda;

            color: #156f43;
        }

        .conversion-result.show {
            display: block;
        }

        .conversion-result strong {
            color: #198754;
        }

        /*========================================
        INVENTORY SUMMARY
        ========================================*/

        .stock-summary {
            display: none;

            margin-top: 25px;
            padding: 20px;

            background: #f8f9fa;

            border: 1px solid #e3e6e8;
            border-radius: 13px;
        }

        .stock-summary.show {
            display: block;
        }

        .summary-title {
            margin-bottom: 15px;

            color: #198754;

            font-size: 17px;
            font-weight: 700;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;

            gap: 15px;

            margin-bottom: 9px;
        }

        .summary-row:last-child {
            margin-bottom: 0;
        }

        .summary-label {
            color: #6c757d;
        }

        .summary-value {
            text-align: right;
            font-weight: 700;
        }

        .summary-total {
            margin-top: 12px;
            padding-top: 12px;

            border-top: 1px solid #ddd;

            color: #198754;
            font-size: 17px;
        }

        /*========================================
        FILE INPUT
        ========================================*/

        .form-control[type="file"] {
            height: auto;
            min-height: 54px;
            padding: 11px;
        }

        /*========================================
        IMAGE PREVIEW
        ========================================*/

        .preview-wrapper {
            display: none;
            margin-top: 18px;
        }

        .preview-wrapper.show {
            display: block;
        }

        .preview-label {
            margin-bottom: 10px;

            color: #198754;

            font-weight: 700;
        }

        .preview-image-container {
            position: relative;

            width: 100%;

            overflow: hidden;

            border: 1px solid #ddd;
            border-radius: 14px;

            background: #f8f9fa;
        }

        .preview-image-container img {
            display: block;

            width: 100%;
            max-height: 360px;

            object-fit: cover;
        }

        .remove-photo-btn {
            position: absolute;
            top: 10px;
            right: 10px;

            width: 38px;
            height: 38px;

            padding: 0;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        /*========================================
        ALERT
        ========================================*/

        .alert {
            border: 0;
            border-radius: 12px;
        }

        /*========================================
        SUBMIT BUTTON
        ========================================*/

        .submit-section {
            margin-top: 30px;
            padding-top: 25px;

            border-top: 1px solid #e1e1e1;
        }

        .submit-btn {
            width: 100%;
            min-height: 56px;

            padding: 13px 20px;

            border: 0;
            border-radius: 12px;

            background: #198754;

            font-size: 18px;
            font-weight: 700;
        }

        .submit-btn:hover {
            background: #157347;
        }

        /*========================================
        TABLET
        ========================================*/

        @media (max-width: 768px) {

            .product-page {
                padding: 22px 15px 40px;
            }

            .page-header {
                flex-direction: column;
            }

            .page-title {
                font-size: 31px;
            }

            .page-title-icon {
                font-size: 31px;
            }

            .back-btn {
                width: 100%;
                min-height: 50px;

                display: flex;
                justify-content: center;
                align-items: center;
            }

            .product-card .card-body {
                padding: 25px;
            }
        }

        /*========================================
        MOBILE
        ========================================*/

        @media (max-width: 576px) {

            body {
                background: #f1f5f8;
            }

            .product-page {
                padding:
                    19px
                    12px
                    calc(30px + env(safe-area-inset-bottom));
            }

            .page-header {
                margin-bottom: 20px;
                gap: 17px;
            }

            .page-title {
                gap: 8px;

                font-size: 27px;
                line-height: 1.25;
            }

            .page-title-icon {
                font-size: 27px;
            }

            .page-subtitle {
                font-size: 14px;
                line-height: 1.55;
            }

            .product-card {
                border-radius: 18px;
            }

            .product-card .card-body {
                padding: 20px 17px;
            }

            .conversion-info {
                padding: 14px;
            }

            .conversion-info-icon {
                width: 38px;
                height: 38px;
                flex-basis: 38px;

                font-size: 17px;
            }

            .form-group {
                margin-bottom: 21px;
            }

            .form-control,
            .form-select {
                min-height: 54px;

                /* Prevent iOS zoom */
                font-size: 16px;
            }

            .input-group-text {
                min-width: 50px;
            }

            .summary-row {
                font-size: 14px;
            }

            .submit-btn {
                min-height: 55px;
                font-size: 17px;
            }
        }

        /*========================================
        VERY SMALL DEVICES
        ========================================*/

        @media (max-width: 375px) {

            .product-page {
                padding-left: 10px;
                padding-right: 10px;
            }

            .page-title {
                font-size: 24px;
            }

            .product-card .card-body {
                padding: 17px 14px;
            }

            .conversion-info {
                gap: 10px;
            }

            .summary-row {
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

<div class="product-page">

    {{-- ==========================================
        HEADER
    =========================================== --}}

    <div class="page-header">

        <div>

            <h1 class="page-title">

                <span class="page-title-icon">
                    🌾
                </span>

                <span>
                    Post Rice Product
                </span>

            </h1>

            <div class="page-subtitle">

                Add your rice or palay product using
                sack/cavan-based pricing and stock quantity.

            </div>

        </div>


        <a
            href="{{ route('farmer.dashboard') }}"
            class="btn btn-outline-success back-btn"
        >

            <i class="bi bi-arrow-left me-1"></i>

            Back to Dashboard

        </a>

    </div>


    {{-- ==========================================
        SUCCESS
    =========================================== --}}

    @if(session('success'))

        <div class="alert alert-success">

            <i class="bi bi-check-circle-fill me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- ==========================================
        ERRORS
    =========================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-bold mb-2">

                <i class="bi bi-exclamation-triangle-fill me-1"></i>

                Please check the following:

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ==========================================
        FORM CARD
    =========================================== --}}

    <div class="card product-card">

        <div class="card-body">


            {{-- ==================================
                CONVERSION INFORMATION
            =================================== --}}

            <div class="conversion-info">

                <div class="conversion-info-icon">

                    <i class="bi bi-info-lg"></i>

                </div>


                <div>

                    <div class="conversion-title">

                        Sack / Cavan Conversion

                    </div>

                    <div class="conversion-description">

                        For ANI-CARE marketplace computation,
                        <strong>1 sack/cavan = 60 kilograms</strong>.

                        Enter your stock by number of sacks instead
                        of kilograms.

                    </div>

                </div>

            </div>


            <form
                method="POST"
                action="{{ route('farmer.products.store') }}"
                enctype="multipart/form-data"
                id="productForm"
            >

                @csrf


                {{-- ==================================
                    PRODUCT NAME
                =================================== --}}

                <div class="form-group">

                    <label
                        for="productName"
                        class="form-label"
                    >

                        <i class="bi bi-box-seam"></i>

                        Product Name

                    </label>

                    <input
                        type="text"
                        id="productName"
                        name="name"
                        class="form-control"
                        placeholder="Example: Premium Jasmine Rice"
                        value="{{ old('name') }}"
                        maxlength="150"
                        required
                    >

                </div>


                <div class="row g-3">


                    {{-- ==================================
                        PRODUCT TYPE
                    =================================== --}}

                    <div class="col-12 col-md-6">

                        <div class="form-group">

                            <label
                                for="productType"
                                class="form-label"
                            >

                                <i class="bi bi-grid"></i>

                                Product Type

                            </label>

                            <select
                                id="productType"
                                name="type"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Product Type
                                </option>

                                <option
                                    value="rice"
                                    {{ old('type') === 'rice' ? 'selected' : '' }}
                                >
                                    🌾 Rice
                                </option>

                                <option
                                    value="palay"
                                    {{ old('type') === 'palay' ? 'selected' : '' }}
                                >
                                    🌱 Palay
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- ==================================
                        PRICE PER SACK
                    =================================== --}}

                    <div class="col-12 col-md-6">

                        <div class="form-group">

                            <label
                                for="pricePerSack"
                                class="form-label"
                            >

                                <i class="bi bi-cash-stack"></i>

                                Price per Sack / Cavan

                            </label>


                            <div class="input-group">

                                <span class="input-group-text">
                                    ₱
                                </span>

                                <input
                                    type="number"
                                    id="pricePerSack"
                                    name="price_per_sack"
                                    class="form-control"
                                    step="0.01"
                                    min="0.01"
                                    placeholder="0.00"
                                    value="{{ old('price_per_sack') }}"
                                    required
                                >

                            </div>


                            <div class="form-text">

                                Enter the selling price for
                                one 60 kg sack/cavan.

                            </div>

                        </div>

                    </div>


                    {{-- ==================================
                        AVAILABLE STOCK
                    =================================== --}}

                    <div class="col-12 col-md-6">

                        <div class="form-group">

                            <label
                                for="stockSacks"
                                class="form-label"
                            >

                                <i class="bi bi-basket"></i>

                                Available Stocks (Sacks)

                            </label>


                            <div class="input-group">

                                <input
                                    type="number"
                                    id="stockSacks"
                                    name="stock_sacks"
                                    class="form-control"
                                    min="1"
                                    step="1"
                                    placeholder="Example: 20"
                                    value="{{ old('stock_sacks') }}"
                                    required
                                >

                                <span class="input-group-text">
                                    Sacks
                                </span>

                            </div>


                            {{-- Automatic KG Conversion --}}

                            <div
                                id="conversionResult"
                                class="conversion-result"
                            >

                                <i class="bi bi-calculator me-1"></i>

                                Equivalent weight:

                                <strong id="equivalentKg">
                                    0 kg
                                </strong>

                            </div>

                        </div>

                    </div>


                    {{-- ==================================
                        KG PER SACK
                    =================================== --}}

                    <div class="col-12 col-md-6">

                        <div class="form-group">

                            <label class="form-label">

                                <i class="bi bi-speedometer"></i>

                                Weight per Sack

                            </label>


                            <div class="input-group">

                                <input
                                    type="text"
                                    class="form-control"
                                    value="60"
                                    readonly
                                >

                                <span class="input-group-text">
                                    kg
                                </span>

                            </div>


                            <div class="form-text">

                                Standard conversion used by
                                this marketplace.

                            </div>

                        </div>

                    </div>


                    {{-- ==================================
                        PHOTO
                    =================================== --}}

                    <div class="col-12">

                        <div class="form-group">

                            <label
                                for="photo"
                                class="form-label"
                            >

                                <i class="bi bi-image"></i>

                                Product Photo

                                <span
                                    class="fw-normal text-muted"
                                >
                                    (Optional)
                                </span>

                            </label>

                            <input
                                type="file"
                                name="photo"
                                id="photo"
                                class="form-control"
                                accept="image/jpeg,image/jpg,image/png"
                            >

                            <div class="form-text">

                                JPG, JPEG or PNG • Maximum 2 MB

                            </div>


                            {{-- IMAGE PREVIEW --}}

                            <div
                                class="preview-wrapper"
                                id="previewWrapper"
                            >

                                <div class="preview-label">

                                    <i class="bi bi-eye"></i>

                                    Photo Preview

                                </div>

                                <div class="preview-image-container">

                                    <img
                                        id="previewImage"
                                        src=""
                                        alt="Product preview"
                                    >


                                    <button
                                        type="button"
                                        id="removePhoto"
                                        class="btn btn-danger remove-photo-btn"
                                        title="Remove photo"
                                    >

                                        <i class="bi bi-x-lg"></i>

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ==================================
                    HIDDEN COMPATIBILITY FIELDS
                ===================================

                These fields allow you to calculate
                the kilogram equivalent too.

                =================================== --}}

                <input
                    type="hidden"
                    name="kg_per_sack"
                    value="60"
                >

                <input
                    type="hidden"
                    name="kilos_available"
                    id="kilosAvailable"
                    value=""
                >

                <input
                    type="hidden"
                    name="price_per_kg"
                    id="pricePerKg"
                    value=""
                >


                {{-- ==================================
                    STOCK SUMMARY
                =================================== --}}

                <div
                    id="stockSummary"
                    class="stock-summary"
                >

                    <div class="summary-title">

                        <i class="bi bi-receipt"></i>

                        Inventory Summary

                    </div>


                    <div class="summary-row">

                        <span class="summary-label">
                            Number of Sacks
                        </span>

                        <span
                            class="summary-value"
                            id="summarySacks"
                        >
                            0
                        </span>

                    </div>


                    <div class="summary-row">

                        <span class="summary-label">
                            Equivalent Weight
                        </span>

                        <span
                            class="summary-value"
                            id="summaryKg"
                        >
                            0 kg
                        </span>

                    </div>


                    <div class="summary-row">

                        <span class="summary-label">
                            Price per Sack
                        </span>

                        <span
                            class="summary-value"
                            id="summaryPrice"
                        >
                            ₱0.00
                        </span>

                    </div>


                    <div class="summary-row summary-total">

                        <span>
                            Estimated Stock Value
                        </span>

                        <span id="summaryTotal">
                            ₱0.00
                        </span>

                    </div>

                </div>


                {{-- ==================================
                    SUBMIT
                =================================== --}}

                <div class="submit-section">

                    <button
                        type="submit"
                        class="btn btn-success submit-btn"
                        id="submitBtn"
                    >

                        <i class="bi bi-upload me-1"></i>

                        Post Product

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script>
document.addEventListener(
    'DOMContentLoaded',
    function () {

        /*
        |--------------------------------------------------------------------------
        | CONFIGURATION
        |--------------------------------------------------------------------------
        */

        const KG_PER_SACK = 60;


        /*
        |--------------------------------------------------------------------------
        | ELEMENTS
        |--------------------------------------------------------------------------
        */

        const form =
            document.getElementById('productForm');

        const submitBtn =
            document.getElementById('submitBtn');

        const stockSacks =
            document.getElementById('stockSacks');

        const pricePerSack =
            document.getElementById('pricePerSack');

        const kilosAvailable =
            document.getElementById('kilosAvailable');

        const pricePerKg =
            document.getElementById('pricePerKg');

        const conversionResult =
            document.getElementById('conversionResult');

        const equivalentKg =
            document.getElementById('equivalentKg');

        const stockSummary =
            document.getElementById('stockSummary');

        const summarySacks =
            document.getElementById('summarySacks');

        const summaryKg =
            document.getElementById('summaryKg');

        const summaryPrice =
            document.getElementById('summaryPrice');

        const summaryTotal =
            document.getElementById('summaryTotal');

        const photoInput =
            document.getElementById('photo');

        const previewWrapper =
            document.getElementById('previewWrapper');

        const previewImage =
            document.getElementById('previewImage');

        const removePhoto =
            document.getElementById('removePhoto');


        /*
        |--------------------------------------------------------------------------
        | PHP MONEY
        |--------------------------------------------------------------------------
        */

        function money(value) {

            return new Intl.NumberFormat(
                'en-PH',
                {
                    style: 'currency',
                    currency: 'PHP'
                }
            ).format(
                Number(value) || 0
            );

        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE STOCK INFORMATION
        |--------------------------------------------------------------------------
        */

        function updateStockInformation() {

            const sacks =
                parseInt(stockSacks.value) || 0;

            const price =
                parseFloat(pricePerSack.value) || 0;


            /*
            |--------------------------------------------------------------------------
            | SACKS TO KG
            |--------------------------------------------------------------------------
            */

            const totalKg =
                sacks * KG_PER_SACK;


            /*
            |--------------------------------------------------------------------------
            | PRICE PER KG
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | ₱3,000 / sack
            | 60 kg / sack
            |
            | ₱3,000 ÷ 60 = ₱50 / kg
            |
            */

            const calculatedPricePerKg =
                price > 0
                    ? price / KG_PER_SACK
                    : 0;


            /*
            |--------------------------------------------------------------------------
            | INVENTORY VALUE
            |--------------------------------------------------------------------------
            */

            const totalValue =
                sacks * price;


            /*
            |--------------------------------------------------------------------------
            | HIDDEN VALUES
            |--------------------------------------------------------------------------
            */

            kilosAvailable.value =
                totalKg;

            pricePerKg.value =
                calculatedPricePerKg.toFixed(2);


            /*
            |--------------------------------------------------------------------------
            | CONVERSION DISPLAY
            |--------------------------------------------------------------------------
            */

            if (sacks > 0) {

                conversionResult.classList.add(
                    'show'
                );

                equivalentKg.textContent =
                    totalKg.toLocaleString() +
                    ' kg';

            } else {

                conversionResult.classList.remove(
                    'show'
                );

            }


            /*
            |--------------------------------------------------------------------------
            | SUMMARY
            |--------------------------------------------------------------------------
            */

            if (
                sacks > 0 ||
                price > 0
            ) {

                stockSummary.classList.add(
                    'show'
                );

            } else {

                stockSummary.classList.remove(
                    'show'
                );

            }


            summarySacks.textContent =
                sacks.toLocaleString() +
                (
                    sacks === 1
                        ? ' sack'
                        : ' sacks'
                );


            summaryKg.textContent =
                totalKg.toLocaleString() +
                ' kg';


            summaryPrice.textContent =
                money(price);


            summaryTotal.textContent =
                money(totalValue);

        }


        /*
        |--------------------------------------------------------------------------
        | QUANTITY CHANGE
        |--------------------------------------------------------------------------
        */

        stockSacks.addEventListener(
            'input',
            updateStockInformation
        );


        /*
        |--------------------------------------------------------------------------
        | PRICE CHANGE
        |--------------------------------------------------------------------------
        */

        pricePerSack.addEventListener(
            'input',
            updateStockInformation
        );


        /*
        |--------------------------------------------------------------------------
        | PHOTO PREVIEW
        |--------------------------------------------------------------------------
        */

        photoInput.addEventListener(
            'change',
            function () {

                const file =
                    this.files[0];


                if (!file) {

                    clearPhotoPreview();

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | FILE SIZE VALIDATION
                |--------------------------------------------------------------------------
                */

                const maxSize =
                    2 * 1024 * 1024;


                if (file.size > maxSize) {

                    alert(
                        'Product photo must not exceed 2 MB.'
                    );

                    this.value = '';

                    clearPhotoPreview();

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | FILE TYPE VALIDATION
                |--------------------------------------------------------------------------
                */

                const allowedTypes = [
                    'image/jpeg',
                    'image/jpg',
                    'image/png'
                ];


                if (
                    !allowedTypes.includes(
                        file.type
                    )
                ) {

                    alert(
                        'Only JPG, JPEG, and PNG images are allowed.'
                    );

                    this.value = '';

                    clearPhotoPreview();

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | READ IMAGE
                |--------------------------------------------------------------------------
                */

                const reader =
                    new FileReader();


                reader.onload =
                    function (event) {

                        previewImage.src =
                            event.target.result;

                        previewWrapper.classList.add(
                            'show'
                        );

                    };


                reader.readAsDataURL(file);

            }
        );


        /*
        |--------------------------------------------------------------------------
        | REMOVE PHOTO
        |--------------------------------------------------------------------------
        */

        removePhoto.addEventListener(
            'click',
            function () {

                photoInput.value = '';

                clearPhotoPreview();

            }
        );


        function clearPhotoPreview() {

            previewImage.src = '';

            previewWrapper.classList.remove(
                'show'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FORM VALIDATION
        |--------------------------------------------------------------------------
        */

        form.addEventListener(
            'submit',
            function (event) {

                const sacks =
                    parseInt(
                        stockSacks.value
                    ) || 0;

                const price =
                    parseFloat(
                        pricePerSack.value
                    ) || 0;


                if (sacks < 1) {

                    event.preventDefault();

                    alert(
                        'Available stock must be at least 1 sack.'
                    );

                    stockSacks.focus();

                    return;

                }


                if (price <= 0) {

                    event.preventDefault();

                    alert(
                        'Please enter a valid price per sack.'
                    );

                    pricePerSack.focus();

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | FINAL CALCULATION BEFORE SUBMIT
                |--------------------------------------------------------------------------
                */

                updateStockInformation();


                /*
                |--------------------------------------------------------------------------
                | SUBMIT LOADING
                |--------------------------------------------------------------------------
                */

                submitBtn.disabled =
                    true;

                submitBtn.innerHTML = `
                    <span
                        class="spinner-border spinner-border-sm me-2"
                    ></span>

                    Posting Product...
                `;

            }
        );


        /*
        |--------------------------------------------------------------------------
        | INITIAL OLD VALUES
        |--------------------------------------------------------------------------
        */

        updateStockInformation();

    }
);
</script>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>