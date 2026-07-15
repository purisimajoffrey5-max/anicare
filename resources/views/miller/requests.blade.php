<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Milling Requests | Miller</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Mobile responsive helpers -->
  <style>
    html { box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
    *, *::before, *::after { box-sizing: inherit; }
    body { min-height: 100vh; margin: 0; }
    img, video, iframe, svg, canvas { max-width: 100%; height: auto; }
    .container, .container-fluid { width: 100% !important; max-width: 100% !important; padding-left: 1rem !important; padding-right: 1rem !important; }
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .table-responsive table { min-width: 100%; }
    .leaflet-container, #registrationMap, #residentMap, #orderMap, #trackMap { width: 100% !important; max-width: 100%; }
    .card, .card-body { word-wrap: break-word; }
    .btn, .form-control, .form-select, .input-group, .form-check-input { min-width: 0; }
    @media (max-width: 768px) {
      .navbar, .topbar { flex-wrap: wrap !important; }
      .navbar-brand, .navbar-nav, .btn { width: 100% !important; text-align: center !important; }
      .table-responsive { margin-left: -1rem !important; margin-right: -1rem !important; padding-left: 1rem !important; padding-right: 1rem !important; }
    }
  </style>
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
            <th>Requester</th>
            <th>Product</th>
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
              <td>
                <div>{{ optional($r->user)->fullname ?? 'User #' . $r->user_id }}</div>
                <div class="small text-muted">Farmer / Resident</div>
              </td>
              <td>
                {{ optional($r->inventoryItem)->name ?? 'Milling request' }}
                @if($r->status === 'assigned')
                  <div class="small text-info">Assigned by admin</div>
                @endif
              </td>
              <td>{{ number_format($r->kilos,2) }}</td>
              <td>
                <span class="badge bg-{{ $r->status === 'approved' ? 'primary' : ($r->status === 'assigned' ? 'warning' : ($r->status === 'completed' ? 'success' : 'secondary')) }} text-uppercase">
                  {{ $r->status }}
                </span>
              </td>
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
                @elseif($r->status === 'assigned')
                  <form class="d-inline" method="POST" action="{{ route('miller.requests.accept', $r->id) }}">
                    @csrf
                    <button class="btn btn-primary btn-sm" onclick="return confirm('Accept this assigned request?')">Accept</button>
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
              <td colspan="8" class="text-center text-muted py-4">No requests found.</td>
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
