<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Announcement Library | Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{ background:#f5f7fb; }
    .wrap{ max-width:1100px; margin:0 auto; padding:22px 14px 70px; }
    .soft{ border:1px solid rgba(0,0,0,.06); border-radius:16px; background:#fff; }
    .btn-soft{ border-radius:12px; }
  </style>
</head>
<body>

<nav class="navbar navbar-dark" style="background:#198754;">
  <div class="container-fluid px-3">
    <span class="navbar-brand fw-bold m-0">ANI-CARE | Admin</span>
    <div class="d-flex gap-2">
      <a href="{{ route('admin.announcements.index') }}" class="btn btn-light btn-sm btn-soft">Back</a>
      <form method="POST" action="{{ route('logout') }}" class="m-0">
        @csrf
        <button class="btn btn-warning btn-sm btn-soft">Logout</button>
      </form>
    </div>
  </div>
</nav>

<div class="wrap">

  <h3 class="fw-bold text-success mb-1">Announcement Library</h3>
  <div class="text-muted small mb-3">Archived announcements (tapos na).</div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <div class="soft p-3">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Message</th>
            <th>Archived</th>
            <th class="text-end">Action</th>
          </tr>
        </thead>
        <tbody>
        @forelse($archived as $a)
          <tr>
            <td class="fw-semibold">#{{ $a->id }}</td>
            <td class="fw-semibold">{{ $a->title }}</td>
            <td class="text-muted">{{ $a->message }}</td>
            <td class="text-muted small">{{ $a->archived_at?->format('Y-m-d H:i') }}</td>
            <td class="text-end">
              <div class="d-inline-flex gap-1">
                <form method="POST" action="{{ route('admin.announcements.restore', $a->id) }}">
                  @csrf
                  <button class="btn btn-outline-primary btn-sm btn-soft"
                          onclick="return confirm('Restore to active announcements?')">
                    Restore
                  </button>
                </form>

                <form method="POST" action="{{ route('admin.announcements.delete', $a->id) }}">
                  @csrf
                  <button class="btn btn-outline-danger btn-sm btn-soft"
                          onclick="return confirm('Permanent delete? (Cannot undo)')">
                    Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">Library is empty.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      {{ $archived->links() }}
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>