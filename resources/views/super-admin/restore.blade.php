<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recovery | ANI-CARE Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-5" style="max-width: 800px">
    <a href="{{ route('super-admin.dashboard') }}" class="text-decoration-none">&larr; Back to Super Admin</a>

    <div class="card border-0 shadow-sm mt-3">
        <div class="card-body p-4">
            <h2 class="fw-bold text-danger">System Recovery</h2>
            <p class="text-muted">
                This operation replaces matching database table data with the data in the selected
                ANI-CARE Super Admin backup and restores application uploads.
            </p>

            <div class="alert alert-warning">
                <strong>Important:</strong> Only restore a backup that you trust and that belongs to
                this ANI-CARE installation. Current data may be replaced. The system will also record
                this operation in the Super Admin audit log.
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('super-admin.restore.execute') }}" enctype="multipart/form-data"
                  onsubmit="return confirm('FINAL CONFIRMATION: restore the selected ANI-CARE backup? Current matching table data may be replaced.')">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">ANI-CARE Backup ZIP</label>
                    <input type="file" name="backup" class="form-control" accept=".zip" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Confirm Super Admin Password</label>
                    <input type="password" name="password" class="form-control" autocomplete="current-password" required>
                </div>

                <button class="btn btn-danger">
                    Restore System Data
                </button>
            </form>
        </div>
    </div>
</main>
</body>
</html>
