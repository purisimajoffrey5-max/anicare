<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>System Settings | ANI-CARE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background:#f5f7fb; color:#20252b; font-family:'Segoe UI',sans-serif; }
        .topbar { background:#198754; color:#fff; box-shadow:0 3px 14px rgba(0,0,0,.12); }
        .topbar-inner { width:min(1200px,100%); min-height:64px; margin:auto; padding:10px 14px; display:flex; justify-content:space-between; align-items:center; gap:12px; }
        .brand { font-weight:800; font-size:1.15rem; }
        .page { width:min(1000px,100%); margin:auto; padding:38px 14px 60px; }
        .settings-card { border:0; border-radius:20px; background:#fff; box-shadow:0 8px 28px rgba(15,23,42,.07); transition:.18s ease; height:100%; }
        .settings-card:hover { transform:translateY(-2px); box-shadow:0 12px 32px rgba(25,135,84,.12); }
        .icon-box { width:52px; height:52px; border-radius:14px; display:grid; place-items:center; background:#eaf7f0; color:#198754; font-size:24px; }
        .status-dot { width:10px; height:10px; border-radius:50%; display:inline-block; }
        .status-on { background:#dc3545; }
        .status-off { background:#198754; }
    </style>
</head>
<body>
<header class="topbar">
    <div class="topbar-inner">
        <div class="brand"><i class="bi bi-shield-check me-1"></i> ANI-CARE | LGU</div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-warning btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>
</header>

<main class="page">
    @if(session('success'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="mb-4">
        <h1 class="fw-bold text-success mb-1">System Settings</h1>
        <p class="text-muted mb-0">Manage central system configuration for ANI-CARE.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="settings-card p-4">
                <div class="icon-box mb-3"><i class="bi bi-percent"></i></div>
                <h4 class="fw-bold text-success">Central VAT Setting</h4>
                <p class="text-muted">Manage the VAT rate and enable or disable VAT for applicable transactions.</p>
                <a href="{{ route('admin.reports.vat.settings') }}" class="btn btn-success">
                    <i class="bi bi-sliders me-1"></i> Manage VAT
                </a>
            </div>
        </div>

        <div class="col-md-6">
            <div class="settings-card p-4">
                <div class="icon-box mb-3"><i class="bi bi-tools"></i></div>
                <h4 class="fw-bold text-success">Maintenance Mode</h4>
                <p class="text-muted">Temporarily restrict system access for Resident, Farmer, and Miller accounts during updates.</p>

                <div class="mb-3">
                    @if($maintenanceEnabled)
                        <span class="badge bg-danger-subtle text-danger-emphasis px-3 py-2">
                            <span class="status-dot status-on me-1"></span> System Under Maintenance
                        </span>
                    @else
                        <span class="badge bg-success-subtle text-success-emphasis px-3 py-2">
                            <span class="status-dot status-off me-1"></span> System Available
                        </span>
                    @endif
                </div>

                <a href="{{ route('admin.settings.maintenance') }}" class="btn btn-success">
                    <i class="bi bi-gear me-1"></i> Manage Maintenance
                </a>
            </div>
        </div>
    </div>
</main>
</body>
</html>
