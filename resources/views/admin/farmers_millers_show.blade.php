<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Profile | ANI-CARE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold text-success m-0">User Profile</h3>
      <div class="text-muted small">Farmer / Miller information</div>
    </div>
    <a href="{{ route('admin.farmers_millers') }}" class="btn btn-outline-success btn-sm">Back</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="mb-2"><strong>ID:</strong> {{ $user->id }}</div>
          <div class="mb-2"><strong>Fullname:</strong> {{ $user->fullname }}</div>
          <div class="mb-2"><strong>Username:</strong> {{ $user->username }}</div>
          <div class="mb-2"><strong>Email:</strong> {{ $user->email ?? '-' }}</div>
          <div class="mb-2"><strong>Role:</strong> <span class="badge bg-secondary text-uppercase">{{ $user->role }}</span></div>
        </div>

        <div class="col-md-6">
          <div class="mb-2">
            <strong>Status:</strong>
            @if($user->is_approved)
              <span class="badge bg-success">APPROVED</span>
            @else
              <span class="badge bg-warning text-dark">PENDING</span>
            @endif
          </div>
          <div class="mb-2"><strong>Approved At:</strong> {{ $user->approved_at ? $user->approved_at->format('Y-m-d H:i') : '-' }}</div>
          <div class="mb-2"><strong>Registered At:</strong> {{ $user->created_at ? $user->created_at->format('Y-m-d H:i') : '-' }}</div>
          <div class="mb-2"><strong>Updated At:</strong> {{ $user->updated_at ? $user->updated_at->format('Y-m-d H:i') : '-' }}</div>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>