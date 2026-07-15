<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Notifications | ANI-CARE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
