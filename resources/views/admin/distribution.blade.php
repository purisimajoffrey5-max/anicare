<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Distribution | ANI-CARE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body{
      background:#f5f7fb;
    }
    .soft-card{
      border:1px solid rgba(0,0,0,.06);
      border-radius:16px;
      background:#fff;
      box-shadow:0 8px 20px rgba(15,23,42,.05);
    }
    .stat-card{
      border:1px solid rgba(0,0,0,.06);
      border-radius:16px;
      background:#fff;
      box-shadow:0 6px 18px rgba(15,23,42,.04);
      height:100%;
    }
    .section-title{
      font-weight:700;
      color:#198754;
    }
    .badge-soft{
      padding:7px 12px;
      border-radius:999px;
      font-size:12px;
      font-weight:700;
    }
    .badge-pending{
      background:#fff3cd;
      color:#7a5a00;
    }
    .badge-scheduled{
      background:#cfe2ff;
      color:#084298;
    }
    .badge-completed{
      background:#d1e7dd;
      color:#0f5132;
    }
    .badge-cancelled{
      background:#e2e3e5;
      color:#41464b;
    }
    .badge-depleted{
      background:#f8d7da;
      color:#842029;
    }
    .btn-soft{
      border-radius:12px;
    }
    .table thead th{
      white-space:nowrap;
    }
  </style>
</head>
<body class="bg-light">

<div class="container py-4">

  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
      <h3 class="fw-bold text-success m-0">Distribution Management</h3>
      <div class="text-muted small">
        Schedule, monitor, and confirm rice distributions to beneficiaries.
      </div>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-success btn-sm btn-soft">Back</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  {{-- Summary Cards --}}
  <div class="row g-3 mb-3">
    <div class="col-md-3">
      <div class="stat-card p-3">
        <div class="text-muted small">Total Distributions</div>
        <div class="fs-3 fw-bold">{{ $stats['total'] ?? 0 }}</div>
        <div class="small text-muted">All recorded distribution entries</div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="stat-card p-3">
        <div class="text-muted small">Pending</div>
        <div class="fs-3 fw-bold text-warning">{{ $stats['pending'] ?? 0 }}</div>
        <div class="small text-muted">Waiting for schedule</div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="stat-card p-3">
        <div class="text-muted small">Scheduled</div>
        <div class="fs-3 fw-bold text-primary">{{ $stats['scheduled'] ?? 0 }}</div>
        <div class="small text-muted">Ready for release</div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="stat-card p-3">
        <div class="text-muted small">Completed</div>
        <div class="fs-3 fw-bold text-success">{{ $stats['completed'] ?? 0 }}</div>
        <div class="small text-muted">Successfully distributed</div>
      </div>
    </div>
  </div>

  {{-- Filter/Search + Add Form --}}
  <div class="row g-3 mb-3">
    <div class="col-lg-8">
      <div class="soft-card p-3">
        <form class="row g-2" method="GET" action="{{ route('admin.distribution') }}">
          <div class="col-md-4">
            <label class="form-label small">Search Beneficiary / Ref No.</label>
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="e.g. Juan Dela Cruz or 1">
          </div>

          <div class="col-md-3">
            <label class="form-label small">Status</label>
            <select name="status" class="form-select">
              <option value="all"{{ request('status') === 'all' ? ' selected' : '' }}>All Status</option>
              <option value="pending"{{ request('status') === 'pending' ? ' selected' : '' }}>Pending</option>
              <option value="scheduled"{{ request('status') === 'scheduled' ? ' selected' : '' }}>Scheduled</option>
              <option value="completed"{{ request('status') === 'completed' ? ' selected' : '' }}>Completed</option>
              <option value="cancelled"{{ request('status') === 'cancelled' ? ' selected' : '' }}>Cancelled</option>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label small">Barangay</label>
            <select name="barangay" class="form-select">
              <option value="all">All Barangays</option>
              @foreach($barangays as $barangay)
                <option value="{{ $barangay }}"{{ request('barangay') === $barangay ? ' selected' : '' }}>{{ $barangay }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-2 d-grid">
            <label class="form-label small invisible">Action</label>
            <button type="submit" class="btn btn-success btn-soft">Filter</button>
          </div>
        </form>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="soft-card p-3 h-100">
        <h5 class="mb-3">Add Distribution</h5>
        <form method="POST" action="{{ route('admin.distribution.store') }}">
          @csrf
          <div class="mb-2">
            <label class="form-label small">Beneficiary Name</label>
            <input type="text" name="beneficiary_name" class="form-control form-control-sm" value="{{ old('beneficiary_name') }}" required>
          </div>
          <div class="mb-2">
            <label class="form-label small">Email</label>
            <input type="email" name="beneficiary_email" class="form-control form-control-sm" value="{{ old('beneficiary_email') }}">
          </div>
          <div class="mb-2">
            <label class="form-label small">Barangay</label>
            <input type="text" name="barangay" class="form-control form-control-sm" value="{{ old('barangay') }}">
          </div>
          <div class="mb-2">
            <label class="form-label small">Rice Quantity (kg)</label>
            <input type="number" step="0.1" min="0.5" name="rice_qty" class="form-control form-control-sm" value="{{ old('rice_qty') }}" required>
          </div>
          <div class="mb-2">
            <label class="form-label small">Schedule</label>
            <input type="datetime-local" name="scheduled_at" class="form-control form-control-sm" value="{{ old('scheduled_at') }}">
          </div>
          <div class="mb-2">
            <label class="form-label small">Notes</label>
            <input type="text" name="notes" class="form-control form-control-sm" value="{{ old('notes') }}">
          </div>
          <div class="d-grid">
            <button class="btn btn-success btn-soft btn-sm">Create Record</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Distribution Table --}}
  <div class="soft-card p-3">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <div>
        <h5 class="fw-bold m-0">Distribution Records</h5>
        <div class="text-muted small">Monitor rice distribution schedule and beneficiary release status.</div>
      </div>
      <div class="text-muted small">Showing {{ $distributions->count() }} of {{ $distributions->total() }} records</div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th>Ref No.</th>
            <th>Beneficiary</th>
            <th>Barangay</th>
            <th>Rice Qty</th>
            <th>Schedule</th>
            <th>Status</th>
            <th>Processed By</th>
            <th class="text-end">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($distributions as $distribution)
            <tr>
              <td class="fw-semibold">DIST-{{ str_pad($distribution->id, 3, '0', STR_PAD_LEFT) }}</td>
              <td>
                <div class="fw-semibold">{{ $distribution->beneficiary_name }}</div>
                <div class="text-muted small">{{ $distribution->beneficiary_email ?: 'No email' }}</div>
              </td>
              <td>{{ $distribution->barangay ?: '-' }}</td>
              <td>{{ number_format($distribution->rice_qty, 2) }} kg</td>
              <td>{{ $distribution->scheduled_at ? $distribution->scheduled_at->format('Y-m-d H:i') : '-' }}</td>
              <td>
                <span class="badge-soft badge-{{ $distribution->status === 'pending' ? 'pending' : ($distribution->status === 'scheduled' ? 'scheduled' : ($distribution->status === 'completed' ? 'completed' : 'cancelled')) }}">
                  {{ strtoupper($distribution->status) }}
                </span>
              </td>
              <td>{{ optional($distribution->processedBy)->fullname ?? 'Admin' }}</td>
              <td class="text-end">
                @if($distribution->status === 'pending')
                  <form class="d-inline" method="POST" action="{{ route('admin.distribution.schedule', $distribution->id) }}">
                    @csrf
                    <input type="hidden" name="scheduled_at" value="{{ now()->addDay()->format('Y-m-d\TH:i') }}">
                    <button class="btn btn-primary btn-sm btn-soft">Schedule</button>
                  </form>
                @elseif($distribution->status === 'scheduled')
                  <form class="d-inline" method="POST" action="{{ route('admin.distribution.complete', $distribution->id) }}">
                    @csrf
                    <button class="btn btn-success btn-sm btn-soft">Complete</button>
                  </form>
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">No distribution records found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">{{ $distributions->links() }}</div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
