<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Milling Requests | Miller</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4" style="max-width:1100px;">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold text-success m-0">Milling Requests</h3>
      <div class="text-muted small">Approve / Reject / Complete requests</div>
    </div>
    <a href="{{ route('miller.dashboard') }}" class="btn btn-outline-success btn-sm">Back</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <form class="row g-2" method="GET" action="{{ route('miller.requests') }}">
        <div class="col-md-4">
          <select name="status" class="form-select">
            <option value="pending" {{ $status==='pending'?'selected':'' }}>Pending</option>
            <option value="approved" {{ $status==='approved'?'selected':'' }}>Approved</option>
            <option value="rejected" {{ $status==='rejected'?'selected':'' }}>Rejected</option>
            <option value="completed" {{ $status==='completed'?'selected':'' }}>Completed</option>
            <option value="all" {{ $status==='all'?'selected':'' }}>All</option>
          </select>
        </div>
        <div class="col-md-2 d-grid">
          <button class="btn btn-success">Filter</button>
        </div>
      </form>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Farmer ID</th>
            <th>Kilos</th>
            <th>Status</th>
            <th>Scheduled</th>
            <th>Requested At</th>
            <th class="text-end">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($requests as $r)
            <tr>
              <td>{{ $r->id }}</td>
              <td>{{ $r->user_id }}</td>
              <td>{{ number_format($r->kilos,2) }}</td>
              <td><span class="badge bg-secondary text-uppercase">{{ $r->status }}</span></td>
              <td>{{ $r->scheduled_at ? $r->scheduled_at->format('Y-m-d H:i') : '-' }}</td>
              <td>{{ $r->created_at ? $r->created_at->format('Y-m-d H:i') : '-' }}</td>

              <td class="text-end">
                @if($r->status === 'pending')
                  <form class="d-inline" method="POST" action="{{ route('miller.requests.approve', $r->id) }}">
                    @csrf
                    <button class="btn btn-success btn-sm" onclick="return confirm('Approve this request?')">Approve</button>
                  </form>
                  <form class="d-inline" method="POST" action="{{ route('miller.requests.reject', $r->id) }}">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Reject this request?')">Reject</button>
                  </form>
                @elseif($r->status === 'approved')
                  <form class="d-inline" method="POST" action="{{ route('miller.requests.complete', $r->id) }}">
                    @csrf
                    <button class="btn btn-primary btn-sm" onclick="return confirm('Mark as completed?')">Complete</button>
                  </form>
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-4">No requests found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>

      <div class="mt-3">
        {{ $requests->links() }}
      </div>
    </div>
  </div>
</div>

</body>
</html>