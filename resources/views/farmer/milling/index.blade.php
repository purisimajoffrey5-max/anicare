<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Requests | ANI-CARE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4" style="max-width:1100px;">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold text-success m-0">My Milling Requests</h3>
      <div class="text-muted small">Track your submitted requests</div>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('farmer.milling.create') }}" class="btn btn-success btn-sm">New Request</a>
      <a href="{{ route('farmer.dashboard') }}" class="btn btn-outline-success btn-sm">Back</a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Kilos</th>
              <th>Status</th>
              <th>Notes</th>
              <th>Requested At</th>
            </tr>
          </thead>
          <tbody>
            @forelse($requests as $r)
              <tr>
                <td>{{ $r->id }}</td>
                <td>{{ number_format($r->kilos, 2) }}</td>
                <td>
                  @php $s = strtolower($r->status); @endphp
                  @if($s === 'pending')
                    <span class="badge bg-warning text-dark">PENDING</span>
                  @elseif($s === 'approved')
                    <span class="badge bg-primary">APPROVED</span>
                  @elseif($s === 'rejected')
                    <span class="badge bg-danger">REJECTED</span>
                  @else
                    <span class="badge bg-success">COMPLETED</span>
                  @endif
                </td>
                <td class="text-muted">{{ $r->notes ?? '-' }}</td>
                <td>{{ $r->created_at ? $r->created_at->format('Y-m-d H:i') : '-' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted py-4">No requests yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-3">
        {{ $requests->links() }}
      </div>
    </div>
  </div>
</div>

</body>
</html>