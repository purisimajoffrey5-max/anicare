<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Approvals | ANI-CARE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold text-success m-0">User Approvals</h3>
      <div class="text-muted small">Approve Resident, Farmer, and Miller accounts</div>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-success btn-sm">Back</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  {{-- Stats --}}
  <div class="row g-2 mb-3">
    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="text-muted small">Pending</div>
          <div class="fs-4 fw-bold">{{ $pendingCount }}</div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="text-muted small">Approved</div>
          <div class="fs-4 fw-bold">{{ $approvedCount }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Filters --}}
  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <form class="row g-2" method="GET" action="{{ route('admin.approvals') }}">
        <div class="col-md-5">
          <input
            type="text"
            name="q"
            class="form-control"
            placeholder="Search fullname / username / email / barangay"
            value="{{ $search ?? '' }}"
          >
        </div>

        <div class="col-md-3">
          <select name="role" class="form-select">
            <option value="all" {{ ($role ?? 'all') === 'all' ? 'selected' : '' }}>All Roles</option>
            <option value="resident" {{ ($role ?? '') === 'resident' ? 'selected' : '' }}>Resident</option>
            <option value="farmer" {{ ($role ?? '') === 'farmer' ? 'selected' : '' }}>Farmer</option>
            <option value="miller" {{ ($role ?? '') === 'miller' ? 'selected' : '' }}>Miller</option>
          </select>
        </div>

        <div class="col-md-3">
          <select name="status" class="form-select">
            <option value="pending" {{ ($status ?? 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ ($status ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="all" {{ ($status ?? '') === 'all' ? 'selected' : '' }}>All</option>
          </select>
        </div>

        <div class="col-md-1 d-grid">
          <button class="btn btn-success">Go</button>
        </div>
      </form>
    </div>
  </div>

  {{-- Table --}}
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Fullname</th>
              <th>Username</th>
              <th>Email</th>
              <th>Barangay</th>
              <th>Role</th>
              <th>Status</th>
              <th>Approved At</th>
              <th>Registered</th>
              <th class="text-end">Action</th>
            </tr>
          </thead>

          <tbody>
          @forelse($users as $u)
            <tr>
              <td>{{ $u->id }}</td>
              <td class="fw-semibold">{{ $u->fullname }}</td>
              <td>{{ $u->username }}</td>
              <td>{{ $u->email ?? '-' }}</td>
              <td>{{ $u->barangay ?? '-' }}</td>
              <td>
                @if($u->role === 'resident')
                  <span class="badge bg-primary text-uppercase">resident</span>
                @elseif($u->role === 'farmer')
                  <span class="badge bg-success text-uppercase">farmer</span>
                @elseif($u->role === 'miller')
                  <span class="badge bg-secondary text-uppercase">miller</span>
                @else
                  <span class="badge bg-dark text-uppercase">{{ $u->role }}</span>
                @endif
              </td>

              <td>
                @if($u->is_approved)
                  <span class="badge bg-success">APPROVED</span>
                @else
                  <span class="badge bg-warning text-dark">PENDING</span>
                @endif
              </td>

              <td>{{ $u->approved_at ? $u->approved_at->format('Y-m-d H:i') : '-' }}</td>
              <td>{{ $u->created_at ? $u->created_at->format('Y-m-d') : '-' }}</td>

              <td class="text-end">
                @if(!$u->is_approved)
                  <form class="d-inline" method="POST" action="{{ route('admin.approvals.approve', $u->id) }}">
                    @csrf
                    <button
                      class="btn btn-success btn-sm"
                      onclick="return confirm('Approve this user?')">
                      Approve
                    </button>
                  </form>
                @else
                  <form class="d-inline" method="POST" action="{{ route('admin.approvals.revoke', $u->id) }}">
                    @csrf
                    <button
                      class="btn btn-outline-danger btn-sm"
                      onclick="return confirm('Revoke approval?')">
                      Revoke
                    </button>
                  </form>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="text-center text-muted py-4">No users found.</td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      <div class="mt-3">
        {{ $users->links() }}
      </div>
    </div>
  </div>

</div>

</body>
</html>