<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Milling Reports | Miller</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4" style="max-width:1100px;">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold text-success m-0">Milling Reports</h3>
      <div class="text-muted small">Live report view of completed milling jobs.</div>
    </div>
    <div class="d-flex gap-2 align-items-center">
      <small class="text-muted">Auto-refreshes every 30s</small>
      <a href="{{ route('miller.dashboard') }}" class="btn btn-outline-success btn-sm">Back</a>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Requester</th>
            <th>Product</th>
            <th>Kilos</th>
            <th>Scheduled</th>
            <th>Completed At</th>
          </tr>
        </thead>
        <tbody>
          @forelse($reports as $r)
            <tr>
              <td>{{ $r->id }}</td>
              <td>
                {{ optional($r->user)->fullname ?? 'User #' . $r->user_id }}<br>
                <span class="small text-muted">{{ $r->user ? $r->user->username : 'Farmer' }}</span>
              </td>
              <td>{{ optional($r->inventoryItem)->name ?? 'Milling request' }}</td>
              <td>{{ number_format($r->kilos,2) }}</td>
              <td>{{ $r->scheduled_at ? $r->scheduled_at->format('Y-m-d H:i') : '-' }}</td>
              <td>{{ $r->updated_at ? $r->updated_at->format('Y-m-d H:i') : '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted py-4">No reports yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>

      <div class="mt-3">
        {{ $reports->links() }}
      </div>
    </div>
  </div>
</div>

<script>
  setTimeout(function() {
    window.location.reload();
  }, 30000);
</script>
</body>
</html>