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
        <div class="fs-3 fw-bold">24</div>
        <div class="small text-muted">All recorded distribution entries</div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="stat-card p-3">
        <div class="text-muted small">Pending</div>
        <div class="fs-3 fw-bold text-warning">6</div>
        <div class="small text-muted">Waiting for schedule or approval</div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="stat-card p-3">
        <div class="text-muted small">Scheduled</div>
        <div class="fs-3 fw-bold text-primary">10</div>
        <div class="small text-muted">Ready for release or delivery</div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="stat-card p-3">
        <div class="text-muted small">Completed</div>
        <div class="fs-3 fw-bold text-success">8</div>
        <div class="small text-muted">Successfully distributed</div>
      </div>
    </div>
  </div>

  {{-- Info Box --}}
  <div class="soft-card p-3 mb-3">
    <h5 class="section-title mb-2">About this Module</h5>
    <p class="text-muted mb-2">
      This module is intended to manage the distribution of rice assistance to qualified beneficiaries.
      The administrator can monitor distribution records, assign schedules, review beneficiary details,
      and confirm completed releases.
    </p>
    <p class="text-muted mb-0">
      In the full implementation, this page can be connected to resident records, inventory deductions,
      distribution logs, and report generation.
    </p>
  </div>

  {{-- Filter/Search --}}
  <div class="soft-card p-3 mb-3">
    <form class="row g-2">
      <div class="col-md-4">
        <label class="form-label small">Search Beneficiary / Reference No.</label>
        <input type="text" class="form-control" placeholder="e.g. Juan Dela Cruz or DIST-001">
      </div>

      <div class="col-md-3">
        <label class="form-label small">Status</label>
        <select class="form-select">
          <option selected>All Status</option>
          <option>Pending</option>
          <option>Scheduled</option>
          <option>Completed</option>
          <option>Cancelled</option>
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label small">Barangay</label>
        <select class="form-select">
          <option selected>All Barangays</option>
          <option>Bessang</option>
          <option>Binubungan</option>
          <option>Bulo</option>
          <option>Burot</option>
          <option>Centro East (Poblacion)</option>
          <option>Centro West (Poblacion)</option>
          <option>Dagupan</option>
          <option>San Juan (Maguininango)</option>
          <option>Tamboli</option>
          <option>Utan</option>
        </select>
      </div>

      <div class="col-md-2 d-grid">
        <label class="form-label small invisible">Action</label>
        <button type="submit" class="btn btn-success btn-soft">Filter</button>
      </div>
    </form>
  </div>

  {{-- Distribution Table --}}
  <div class="soft-card p-3">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <div>
        <h5 class="fw-bold m-0">Distribution Records</h5>
        <div class="text-muted small">Monitor rice distribution schedule and beneficiary release status.</div>
      </div>

      <button class="btn btn-success btn-sm btn-soft">+ Add Distribution</button>
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
          <tr>
            <td class="fw-semibold">DIST-001</td>
            <td>
              <div class="fw-semibold">Juan Dela Cruz</div>
              <div class="text-muted small">juan@email.com</div>
            </td>
            <td>Centro East</td>
            <td>25 kg</td>
            <td>2026-03-12 09:00 AM</td>
            <td><span class="badge-soft badge-pending">PENDING</span></td>
            <td>Admin</td>
            <td class="text-end">
              <button class="btn btn-outline-dark btn-sm btn-soft">View</button>
              <button class="btn btn-primary btn-sm btn-soft">Schedule</button>
            </td>
          </tr>

          <tr>
            <td class="fw-semibold">DIST-002</td>
            <td>
              <div class="fw-semibold">Maria Santos</div>
              <div class="text-muted small">maria@email.com</div>
            </td>
            <td>Dagupan</td>
            <td>50 kg</td>
            <td>2026-03-13 01:30 PM</td>
            <td><span class="badge-soft badge-scheduled">SCHEDULED</span></td>
            <td>Admin</td>
            <td class="text-end">
              <button class="btn btn-outline-dark btn-sm btn-soft">View</button>
              <button class="btn btn-success btn-sm btn-soft">Confirm</button>
            </td>
          </tr>

          <tr>
            <td class="fw-semibold">DIST-003</td>
            <td>
              <div class="fw-semibold">Pedro Reyes</div>
              <div class="text-muted small">pedro@email.com</div>
            </td>
            <td>San Juan</td>
            <td>30 kg</td>
            <td>2026-03-10 10:00 AM</td>
            <td><span class="badge-soft badge-completed">COMPLETED</span></td>
            <td>Admin</td>
            <td class="text-end">
              <button class="btn btn-outline-dark btn-sm btn-soft">View</button>
            </td>
          </tr>

          <tr>
            <td class="fw-semibold">DIST-004</td>
            <td>
              <div class="fw-semibold">Ana Lopez</div>
              <div class="text-muted small">ana@email.com</div>
            </td>
            <td>Tamboli</td>
            <td>20 kg</td>
            <td>2026-03-09 03:00 PM</td>
            <td><span class="badge-soft badge-cancelled">CANCELLED</span></td>
            <td>Admin</td>
            <td class="text-end">
              <button class="btn btn-outline-dark btn-sm btn-soft">View</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="mt-3 text-muted small">
      Showing sample records for layout preview. Connect this table to your actual distribution database later.
    </div>
  </div>

</div>

</body>
</html>