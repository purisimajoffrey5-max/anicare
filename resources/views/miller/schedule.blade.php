<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Schedule Milling | Miller</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4" style="max-width:1100px;">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold text-success m-0">Schedule Milling</h3>
      <div class="text-muted small">Set schedule for approved requests</div>
    </div>
    <a href="{{ route('miller.dashboard') }}" class="btn btn-outline-success btn-sm">Back</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card shadow-sm">
    <div class="card-body table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Farmer ID</th>
            <th>Kilos</th>
            <th>Current Schedule</th>
            <th>Set New Schedule</th>
          </tr>
        </thead>
        <tbody>
          @forelse($approved as $r)
            <tr>
              <td>{{ $r->id }}</td>
              <td>{{ $r->user_id }}</td>
              <td>{{ number_format($r->kilos,2) }}</td>
              <td>{{ $r->scheduled_at ? $r->scheduled_at->format('Y-m-d H:i') : '-' }}</td>
              <td>
                <form class="d-flex gap-2" method="POST" action="{{ route('miller.schedule.set', $r->id) }}">
                  @csrf
                  <input type="datetime-local" name="scheduled_at" class="form-control form-control-sm" required>
                  <button class="btn btn-success btn-sm">Save</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted py-4">No approved requests yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>

      <div class="mt-3">
        {{ $approved->links() }}
      </div>
    </div>
  </div>
</div>

</body>
</html>