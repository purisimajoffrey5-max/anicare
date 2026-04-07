<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile | Resident</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4" style="max-width:700px;">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold text-success m-0">My Profile</h3>
      <div class="text-muted small">Update your account information</div>
    </div>
    <a href="{{ route('resident.dashboard') }}" class="btn btn-outline-success btn-sm">Back</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <div class="card shadow-sm">
    <div class="card-body p-4">
      <form method="POST" action="{{ route('resident.profile.update') }}">
        @csrf

        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="fullname" class="form-control" value="{{ old('fullname', $user->fullname) }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Email (optional)</label>
          <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
        </div>

        <button class="btn btn-success w-100">Save Changes</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>