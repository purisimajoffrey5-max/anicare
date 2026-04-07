<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Farmer Dashboard | ANI-CARE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body { background:#f5f7fb; }
    .topbar{ background:#198754; height:56px; display:flex; align-items:center; color:#fff; }
    .page-wrap{ max-width:1100px; margin:0 auto; padding:28px 16px 60px; }
    .dash-card{ border:1px solid rgba(0,0,0,.06); border-radius:12px; background:#fff; height:100%; transition:.15s; }
    .dash-card:hover{ transform: translateY(-3px); box-shadow: 0 12px 28px rgba(16,24,40,.08); }
    .card-link{ text-decoration:none; color:inherit; display:block; }
  </style>
</head>
<body>

{{-- TOP BAR --}}
<div class="topbar">
  <div class="container-fluid d-flex align-items-center justify-content-between px-4">
    <div class="fw-bold">ANI-CARE | Farmer</div>

    <div class="d-flex gap-2">
      <a href="{{ route('farmer.orders.index') }}" class="btn btn-light btn-sm">Orders</a>

      <form method="POST" action="{{ route('logout') }}" class="m-0">
        @csrf
        <button class="btn btn-warning btn-sm">Logout</button>
      </form>
    </div>
  </div>
</div>

{{-- CONTENT --}}
<div class="page-wrap">
  <h3 class="fw-bold mb-1">Welcome, Farmer 👨‍🌾</h3>
  <div class="text-muted mb-4">
    Access your profile, submit milling requests, manage rice products, and track updates.
  </div>

  <div class="row g-3">

    {{-- FARM PROFILE --}}
    <div class="col-md-4">
      <a class="card-link" href="{{ route('farmer.profile') }}">
        <div class="dash-card p-3">
          <h5 class="fw-bold text-success mb-1">My Farm Profile</h5>
          <div class="text-muted small mb-3">View and update your farm information.</div>
          <span class="btn btn-success btn-sm">View Profile</span>
        </div>
      </a>
    </div>

    {{-- REQUEST MILLING --}}
    <div class="col-md-4">
      <a class="card-link" href="{{ route('farmer.milling.create') }}">
        <div class="dash-card p-3">
          <h5 class="fw-bold text-success mb-1">Request Milling</h5>
          <div class="text-muted small mb-3">Submit a milling service request.</div>
          <span class="btn btn-success btn-sm">Request Now</span>
        </div>
      </a>
    </div>

    {{-- MY MILLING REQUESTS --}}
    <div class="col-md-4">
      <a class="card-link" href="{{ route('farmer.milling.index') }}">
        <div class="dash-card p-3">
          <h5 class="fw-bold text-success mb-1">My Requests</h5>
          <div class="text-muted small mb-3">Track status of submitted requests.</div>
          <span class="btn btn-success btn-sm">View Requests</span>
        </div>
      </a>
    </div>

    {{-- POST RICE PRODUCT --}}
    <div class="col-md-4">
      <a class="card-link" href="{{ route('farmer.products.create') }}">
        <div class="dash-card p-3">
          <h5 class="fw-bold text-success mb-1">Post Rice Product</h5>
          <div class="text-muted small mb-3">
            Add a rice product with photo, price, and kilos.
          </div>
          <span class="btn btn-success btn-sm">Post a Product</span>
        </div>
      </a>
    </div>

    {{-- ✅ NEW CARD: MY RICE PRODUCTS --}}
    <div class="col-md-4">
      <a class="card-link" href="{{ route('farmer.products.index') }}">
        <div class="dash-card p-3">
          <h5 class="fw-bold text-success mb-1">My Rice Products</h5>
          <div class="text-muted small mb-3">
            View, update, or disable your posted rice products.
          </div>
          <span class="btn btn-outline-success btn-sm">View Products</span>
        </div>
      </a>
    </div>

  </div>

  {{-- ✅ ANNOUNCEMENTS --}}
@if(isset($announcements) && $announcements->count())
  <div class="mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div class="fw-bold">📢 Announcements</div>
      <div class="text-muted small">Latest updates</div>
    </div>

    @foreach($announcements as $a)
      <div class="alert alert-info mb-2">
        <div class="fw-bold">{{ $a->title }}</div>
        <div>{{ $a->message }}</div>
        <div class="text-muted small mt-1">
          Posted: {{ $a->created_at?->format('Y-m-d H:i') }}
        </div>
      </div>
    @endforeach
  </div>
@endif

</div>

</body>
</html>