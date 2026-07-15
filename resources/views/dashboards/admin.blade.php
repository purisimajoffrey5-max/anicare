<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | ANI-CARE</title>
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

  <style>
    body { background:#f5f7fb; }

    /* TOPBAR */
    .topbar{
      background:#198754;
      min-height:56px;
      display:flex;
      align-items:center;
      color:#fff;
      padding:10px 0;
    }

    /* WRAPPER */
    .page-wrap{
      max-width:1200px;
      margin:0 auto;
      padding:20px 12px 50px;
    }

    /* CARDS */
    .dash-card{
      border:1px solid rgba(0,0,0,.06);
      border-radius:12px;
      background:#fff;
      height:100%;
      transition:.15s;
    }

    .dash-card:hover{
      transform: translateY(-3px);
      box-shadow: 0 12px 28px rgba(25,135,84,.15);
    }

    .card-link{
      text-decoration:none;
      color:inherit;
      display:block;
    }

    /* MOBILE IMPROVEMENTS */
    @media (max-width: 768px){

      .topbar .container-fluid{
        flex-direction:column;
        align-items:flex-start !important;
        gap:6px;
      }

      .page-wrap{
        padding:18px 10px 40px;
      }

      h3{
        font-size:20px;
      }

      .dash-card{
        padding:14px !important;
      }

      .btn{
        width:100%;
      }

      table{
        font-size:13px;
      }
    }
  </style>
</head>
<body>

{{-- TOP BAR --}}
<div class="topbar">
  <div class="container-fluid d-flex justify-content-between align-items-center px-3 px-md-4">

    <div class="fw-bold">ANI-CARE | Admin</div>

    <div class="d-flex gap-2 align-items-center flex-wrap">
      <span class="text-white small">
        {{ Auth::user()->fullname ?? 'Admin' }}
      </span>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-warning btn-sm">Logout</button>
      </form>
    </div>

  </div>
</div>

{{-- CONTENT --}}
<div class="page-wrap">

  <h3 class="fw-bold mb-1">Welcome, Admin 👨‍💼</h3>
  <div class="text-muted mb-4">
    Manage ANI-CARE system operations including farmers, inventory, and distributions.
  </div>

  {{-- QUICK CARDS --}}
  <div class="row g-3 mb-4">

    <div class="col-12 col-sm-6 col-lg-4">
      <a href="{{ route('admin.farmers_millers') }}" class="card-link">
        <div class="dash-card p-3">
          <h5 class="fw-bold text-success">Farmers & Millers</h5>
          <div class="text-muted small mb-3">Manage user profiles</div>
          <span class="btn btn-success btn-sm">Open</span>
        </div>
      </a>
    </div>

    <div class="col-12 col-sm-6 col-lg-4">
      <a href="{{ route('admin.inventory') }}" class="card-link">
        <div class="dash-card p-3">
          <h5 class="fw-bold text-success">Inventory</h5>
          <div class="text-muted small mb-3">Track rice stock</div>
          <span class="btn btn-success btn-sm">View</span>
        </div>
      </a>
    </div>

    <div class="col-12 col-sm-6 col-lg-4">
      <a href="{{ route('admin.distribution') }}" class="card-link">
        <div class="dash-card p-3">
          <h5 class="fw-bold text-success">Distribution</h5>
          <div class="text-muted small mb-3">Manage schedules</div>
          <span class="btn btn-success btn-sm">Manage</span>
        </div>
      </a>
    </div>

    <div class="col-12 col-sm-6 col-lg-4">
      <a href="{{ route('admin.approvals') }}" class="card-link">
        <div class="dash-card p-3">
          <h5 class="fw-bold text-success">User Approvals</h5>
          <div class="text-muted small mb-3">Approve accounts</div>
          <span class="btn btn-success btn-sm">Review</span>
        </div>
      </a>
    </div>

    <div class="col-12 col-sm-6 col-lg-4">
      <a href="{{ route('admin.market') }}" class="card-link">
        <div class="dash-card p-3">
          <h5 class="fw-bold text-success">Marketplace</h5>
          <div class="text-muted small mb-3">View products</div>
          <span class="btn btn-success btn-sm">Open</span>
        </div>
      </a>
    </div>

    <div class="col-12 col-sm-6 col-lg-4">
      <a href="{{ route('admin.announcements.index') }}" class="card-link">
        <div class="dash-card p-3">
          <h5 class="fw-bold text-success">Announcements</h5>
          <div class="text-muted small mb-3">Post updates</div>
          <span class="btn btn-success btn-sm">Manage</span>
        </div>
      </a>
    </div>

    <div class="col-12 col-sm-6 col-lg-4">
      <a href="{{ route('admin.notifications') }}" class="card-link">
        <div class="dash-card p-3">
          <h5 class="fw-bold text-success">Notifications</h5>
          <div class="text-muted small mb-3">View admin alerts</div>
          <span class="btn btn-success btn-sm">Open</span>
        </div>
      </a>
    </div>

  </div>

  {{-- STATS --}}
  <div class="row g-3 mb-4 text-center">

    <div class="col-6 col-md-3">
      <div class="dash-card p-3">
        <div class="text-muted small">Farmers</div>
        <h4 class="fw-bold text-success">{{ $activeFarmers ?? 0 }}</h4>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="dash-card p-3">
        <div class="text-muted small">Beneficiaries</div>
        <h4 class="fw-bold text-success">{{ $beneficiaries ?? 0 }}</h4>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="dash-card p-3">
        <div class="text-muted small">Rice Stock</div>
        <h4 class="fw-bold text-success">{{ $currentStock ?? 0 }}</h4>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="dash-card p-3">
        <div class="text-muted small">Pending</div>
        <h4 class="fw-bold text-success">{{ $pendingApprovals ?? 0 }}</h4>
      </div>
    </div>

  </div>

  {{-- RECENT ACTIVITY --}}
  <div class="dash-card p-3 mb-4">
    <h5 class="fw-bold mb-3 text-success">Recent Activities</h5>

    <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>Module</th>
            <th>Description</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Approvals</td>
            <td>New farmer account submitted</td>
            <td><span class="badge bg-warning">Pending</span></td>
            <td>{{ now()->format('M d, Y') }}</td>
          </tr>

          <tr>
            <td>Inventory</td>
            <td>Stock updated</td>
            <td><span class="badge bg-success">Completed</span></td>
            <td>{{ now()->format('M d, Y') }}</td>
          </tr>

          <tr>
            <td>Distribution</td>
            <td>Schedule created</td>
            <td><span class="badge bg-success">Scheduled</span></td>
            <td>{{ now()->format('M d, Y') }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

</div>

</body>
</html>
