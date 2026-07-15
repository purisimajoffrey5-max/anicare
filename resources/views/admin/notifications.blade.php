<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Notifications | ANI-CARE</title>
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
      <h3 class="fw-bold text-success m-0">Notifications</h3>
      <div class="text-muted small">Recent alerts for your admin account.</div>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-success btn-sm">Back</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="list-group shadow-sm">
    @forelse($notifications as $notification)
      <div class="list-group-item d-flex justify-content-between align-items-start {{ $notification->read_at ? 'bg-light text-muted' : '' }}">
        <div>
          <div class="fw-bold">{{ $notification->data['title'] ?? ucfirst($notification->type) }}</div>
          <div class="small">{{ $notification->data['message'] ?? '' }}</div>
          <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
        </div>
        <div class="text-end">
          @unless($notification->read_at)
            <form method="POST" action="{{ route('admin.notifications.read', $notification->id) }}">
              @csrf
              <button class="btn btn-sm btn-outline-primary">Mark as read</button>
            </form>
          @endunless
        </div>
      </div>
    @empty
      <div class="list-group-item text-center text-muted">No notifications yet.</div>
    @endforelse
  </div>

  <div class="mt-3">
    {{ $notifications->links() }}
  </div>
</div>

</body>
</html>

