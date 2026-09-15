<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Central VAT Setting | ANI-CARE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            background: #f5f7fb;
            font-family: 'Segoe UI', sans-serif;
            color: #20252b;
        }

        .topbar {
            background: #198754;
            color: #fff;
            box-shadow: 0 3px 14px rgba(0,0,0,.12);
        }

        .topbar-inner {
            width: min(1200px, 100%);
            min-height: 64px;
            margin: 0 auto;
            padding: 10px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .brand {
            font-weight: 800;
            font-size: 18px;
        }

        .page-wrap {
            width: min(900px, 100%);
            margin: 0 auto;
            padding: 30px 14px 60px;
        }

        .settings-card {
            border: 1px solid rgba(0,0,0,.06);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 5px 18px rgba(15,23,42,.045);
        }

        .module-icon {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            background: #eaf7f0;
            color: #198754;
            font-size: 20px;
            margin-bottom: 12px;
        }

        .form-control {
            min-height: 46px;
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: #198754;
            box-shadow: 0 0 0 .2rem rgba(25,135,84,.15);
        }

        .status-box {
            border: 1px solid #edf0ef;
            border-radius: 12px;
            background: #f8faf9;
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

        <a href="{{ route('admin.dashboard') }}" class="btn btn-warning btn-sm">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Dashboard
        </a>
    </div>
</header>

<main class="page-wrap">
    <div class="mb-4">
        <h3 class="fw-bold text-success mb-1">Central VAT Setting</h3>
        <div class="text-muted">
            Manage the VAT setting used by the system for applicable transactions.
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please check the following:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="settings-card p-4">
        <div class="module-icon">
            <i class="bi bi-percent"></i>
        </div>

        <h5 class="fw-bold text-success mb-1">VAT Configuration</h5>
        <div class="text-muted small mb-4">
            The setting is controlled here instead of directly on the dashboard card.
        </div>

        <div class="status-box p-3 mb-4 d-flex justify-content-between align-items-center">
            <div>
                <div class="fw-semibold">VAT Status</div>
                <div class="text-muted small">
                    {{ $vatEnabled ? 'VAT will be applied.' : 'VAT is currently not applied.' }}
                </div>
            </div>

            @if($vatEnabled)
                <span class="badge bg-success">
                    <i class="bi bi-check-circle me-1"></i> Enabled
                </span>
            @else
                <span class="badge bg-secondary">
                    <i class="bi bi-x-circle me-1"></i> Disabled
                </span>
            @endif
        </div>

        <form method="POST" action="{{ route('admin.reports.vat.update') }}">
            @csrf

            <div class="mb-4">
                <label class="form-label fw-semibold">VAT Rate</label>
                <div class="input-group">
                    <input
                        type="number"
                        name="vat_rate"
                        class="form-control"
                        min="0"
                        max="100"
                        step="0.01"
                        value="{{ number_format($vatRate, 2, '.', '') }}"
                        required
                    >
                    <span class="input-group-text">%</span>
                </div>
                <div class="form-text">
                    Example: enter <strong>0.30</strong> for 0.3% VAT or <strong>12.00</strong> for 12%.
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">VAT Status</label>

                <div class="d-flex gap-2 flex-wrap">
                    <label class="btn {{ $vatEnabled ? 'btn-success' : 'btn-outline-success' }}">
                        <input
                            type="radio"
                            name="vat_enabled"
                            value="1"
                            class="d-none"
                            {{ $vatEnabled ? 'checked' : '' }}
                        >
                        <i class="bi bi-check-circle me-1"></i>
                        Enable VAT
                    </label>

                    <label class="btn {{ !$vatEnabled ? 'btn-secondary' : 'btn-outline-secondary' }}">
                        <input
                            type="radio"
                            name="vat_enabled"
                            value="0"
                            class="d-none"
                            {{ !$vatEnabled ? 'checked' : '' }}
                        >
                        <i class="bi bi-x-circle me-1"></i>
                        Disable VAT
                    </label>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-light border">
                    Cancel
                </a>

                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save me-1"></i>
                    Save VAT Setting
                </button>
            </div>
        </form>
    </div>
</main>
</body>
</html>
