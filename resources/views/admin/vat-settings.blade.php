<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Central VAT Setting | ANI-CARE</title>
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
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #f4f7f9;
            font-family: "Segoe UI", Arial, sans-serif;
            color: #263238;
        }

        /* =========================
           TOP BAR
        ========================= */

        .topbar {
            height: 54px;
            background: #198754;
            color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .12);
        }

        .topbar-inner {
            width: min(1100px, 100%);
            height: 54px;
            margin: auto;
            padding: 0 14px;

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: .1px;
        }

        .back-btn {
            background: #ffc107;
            border: none;
            color: #212529;
            font-size: 12px;
            font-weight: 600;
            border-radius: 4px;
            padding: 7px 12px;
            text-decoration: none;
        }

        .back-btn:hover {
            background: #ffca2c;
            color: #212529;
        }

        /* =========================
           PAGE
        ========================= */

        .page-wrap {
            width: min(820px, 100%);
            margin: 0 auto;
            padding: 28px 14px 60px;
        }

        .page-heading {
            margin-bottom: 20px;
        }

        .page-heading h2 {
            margin: 0;
            color: #198754;
            font-size: 25px;
            font-weight: 700;
        }

        .page-heading p {
            margin: 4px 0 0;
            color: #6c757d;
            font-size: 12px;
        }

        /* =========================
           ALERTS
        ========================= */

        .alert {
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 18px;
        }

        /* =========================
           MAIN CARD
        ========================= */

        .settings-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 14px;
            box-shadow: 0 5px 18px rgba(15, 23, 42, .06);
            overflow: hidden;
        }

        .card-header-custom {
            padding: 20px 22px 16px;
            border-bottom: 1px solid #edf0f2;
        }

        .header-row {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .module-icon {
            width: 46px;
            height: 46px;
            flex: 0 0 46px;

            display: grid;
            place-items: center;

            border-radius: 12px;
            background: #eaf7f0;
            color: #198754;

            font-size: 21px;
        }

        .card-title {
            margin: 0;
            color: #198754;
            font-size: 17px;
            font-weight: 700;
        }

        .card-description {
            margin: 3px 0 0;
            color: #6c757d;
            font-size: 12px;
        }

        .card-body-custom {
            padding: 22px;
        }

        /* =========================
           STATUS
        ========================= */

        .status-box {
            background: #f8faf9;
            border: 1px solid #e5ebe8;
            border-radius: 10px;
            padding: 14px 16px;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;

            margin-bottom: 24px;
        }

        .status-label {
            font-size: 12px;
            font-weight: 700;
            color: #343a40;
        }

        .status-description {
            margin-top: 3px;
            color: #6c757d;
            font-size: 11px;
        }

        .status-badge {
            white-space: nowrap;
            font-size: 11px;
            font-weight: 600;
            border-radius: 20px;
            padding: 6px 10px;
        }

        /* =========================
           FORM
        ========================= */

        .section-label {
            display: block;
            margin-bottom: 7px;
            color: #343a40;
            font-size: 12px;
            font-weight: 700;
        }

        .vat-input-group {
            display: flex;
            width: 100%;
        }

        .vat-input {
            height: 44px;
            border: 1px solid #ced4da;
            border-right: none;
            border-radius: 7px 0 0 7px;

            padding: 8px 12px;

            font-size: 14px;
            outline: none;
            flex: 1;
        }

        .vat-input:focus {
            border-color: #198754;
            box-shadow: 0 0 0 3px rgba(25, 135, 84, .10);
        }

        .percent-box {
            min-width: 48px;
            height: 44px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #f1f3f5;
            border: 1px solid #ced4da;
            border-radius: 0 7px 7px 0;

            color: #495057;
            font-size: 13px;
            font-weight: 600;
        }

        .form-help {
            margin-top: 6px;
            color: #6c757d;
            font-size: 11px;
        }

        /* =========================
           VAT STATUS BUTTONS
        ========================= */

        .vat-status-section {
            margin-top: 23px;
        }

        .status-options {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .status-option {
            position: relative;
        }

        .status-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .status-option label {
            min-width: 125px;
            padding: 9px 13px;

            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;

            border: 1px solid #ced4da;
            border-radius: 6px;

            background: #fff;
            color: #495057;

            font-size: 12px;
            font-weight: 600;

            cursor: pointer;
            transition: .15s ease;
        }

        .status-option input:checked + label {
            background: #198754;
            border-color: #198754;
            color: #fff;
        }

        .status-option label:hover {
            border-color: #198754;
            color: #198754;
        }

        .status-option input:checked + label:hover {
            color: #fff;
        }

        /* =========================
           INFO BOX
        ========================= */

        .info-box {
            margin-top: 22px;
            padding: 12px 14px;

            background: #f8faf9;
            border-left: 3px solid #198754;
            border-radius: 6px;

            color: #6c757d;
            font-size: 11px;
            line-height: 1.5;
        }

        .info-box strong {
            color: #198754;
        }

        /* =========================
           FOOTER BUTTONS
        ========================= */

        .form-actions {
            margin-top: 25px;
            padding-top: 18px;

            border-top: 1px solid #edf0f2;

            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .btn-cancel {
            background: #fff;
            border: 1px solid #ced4da;
            color: #495057;

            border-radius: 6px;
            padding: 8px 14px;

            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-cancel:hover {
            background: #f8f9fa;
            color: #212529;
        }

        .btn-save {
            background: #198754;
            border: 1px solid #198754;
            color: #fff;

            border-radius: 6px;
            padding: 8px 15px;

            font-size: 12px;
            font-weight: 600;
        }

        .btn-save:hover {
            background: #157347;
            border-color: #157347;
            color: #fff;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 576px) {

            .page-wrap {
                padding-top: 20px;
            }

            .page-heading h2 {
                font-size: 22px;
            }

            .card-header-custom,
            .card-body-custom {
                padding: 17px;
            }

            .status-box {
                align-items: flex-start;
                flex-direction: column;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .btn-cancel,
            .btn-save {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>

<header class="topbar">
    <div class="topbar-inner">

        <div class="brand">
            <i class="bi bi-shield-check me-1"></i>
            ANI-CARE | LGU
        </div>

        <a href="{{ route('admin.dashboard') }}" class="back-btn">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Dashboard
        </a>

    </div>
</header>


<main class="page-wrap">

    {{-- PAGE TITLE --}}
    <div class="page-heading">
        <h2>Central VAT Setting</h2>

        <p>
            Manage the VAT rate and enable or disable VAT for applicable transactions.
        </p>
    </div>


    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill me-1"></i>
            {{ session('success') }}
        </div>
    @endif


    {{-- ERRORS --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                Please check the following:
            </strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- SETTINGS CARD --}}
    <div class="settings-card">

        {{-- HEADER --}}
        <div class="card-header-custom">

            <div class="header-row">

                <div class="module-icon">
                    <i class="bi bi-percent"></i>
                </div>

                <div>
                    <h5 class="card-title">
                        VAT Configuration
                    </h5>

                    <div class="card-description">
                        Configure the central VAT setting used by ANI-CARE transactions.
                    </div>
                </div>

            </div>

        </div>


        {{-- BODY --}}
        <div class="card-body-custom">

            {{-- CURRENT STATUS --}}
            <div class="status-box">

                <div>
                    <div class="status-label">
                        Current VAT Status
                    </div>

                    <div class="status-description">
                        {{ $vatEnabled
                            ? 'VAT is currently applied to applicable transactions.'
                            : 'VAT is currently not applied to transactions.'
                        }}
                    </div>
                </div>


                @if($vatEnabled)

                    <span class="badge bg-success status-badge">
                        <i class="bi bi-check-circle me-1"></i>
                        Enabled
                    </span>

                @else

                    <span class="badge bg-secondary status-badge">
                        <i class="bi bi-x-circle me-1"></i>
                        Disabled
                    </span>

                @endif

            </div>


            {{-- FORM --}}
            <form
                method="POST"
                action="{{ route('admin.reports.vat.update') }}"
            >

                @csrf


                {{-- VAT RATE --}}
                <div>

                    <label class="section-label">
                        VAT Rate
                    </label>

                    <div class="vat-input-group">

                        <input
                            type="number"
                            name="vat_rate"
                            class="vat-input"
                            min="0"
                            max="100"
                            step="0.01"
                            value="{{ number_format($vatRate, 2, '.', '') }}"
                            required
                        >

                        <div class="percent-box">
                            %
                        </div>

                    </div>

                    <div class="form-help">
                        Example:
                        <strong>0.30</strong> = 0.3% VAT
                        &nbsp; | &nbsp;
                        <strong>12.00</strong> = 12% VAT
                    </div>

                </div>


                {{-- VAT STATUS --}}
                <div class="vat-status-section">

                    <label class="section-label">
                        VAT Status
                    </label>

                    <div class="status-options">

                        {{-- ENABLE --}}
                        <div class="status-option">

                            <input
                                type="radio"
                                id="vat_enable"
                                name="vat_enabled"
                                value="1"
                                {{ $vatEnabled ? 'checked' : '' }}
                            >

                            <label for="vat_enable">
                                <i class="bi bi-check-circle"></i>
                                Enable VAT
                            </label>

                        </div>


                        {{-- DISABLE --}}
                        <div class="status-option">

                            <input
                                type="radio"
                                id="vat_disable"
                                name="vat_enabled"
                                value="0"
                                {{ !$vatEnabled ? 'checked' : '' }}
                            >

                            <label for="vat_disable">
                                <i class="bi bi-x-circle"></i>
                                Disable VAT
                            </label>

                        </div>

                    </div>

                </div>


                {{-- INFORMATION --}}
                <div class="info-box">

                    <i class="bi bi-info-circle me-1"></i>

                    <strong>Central Setting:</strong>
                    Changes made here will be used by the system wherever
                    the central VAT configuration is applied.

                </div>


                {{-- ACTIONS --}}
                <div class="form-actions">

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="btn-cancel"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn-save"
                    >
                        <i class="bi bi-save me-1"></i>
                        Save VAT Setting
                    </button>

                </div>

            </form>

        </div>

    </div>

</main>

</body>
</html>