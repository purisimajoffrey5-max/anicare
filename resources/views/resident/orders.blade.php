<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders | Resident</title>
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
      <h3 class="fw-bold text-success m-0">My Orders</h3>
      <div class="text-muted small">View your order history and statuses</div>
    </div>
    <a href="{{ route('resident.marketplace') }}" class="btn btn-outline-success btn-sm">Back to Marketplace</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card shadow-sm">
    <div class="card-body table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Product</th>
            <th>Farmer</th>
            <th>Kilos</th>
            <th>Total</th>
            <th>Status</th>
            <th>Ordered At</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($orders as $o)
            <tr>
              <td>{{ $o->id }}</td>
              <td class="fw-semibold">{{ $o->product->name ?? '-' }}</td>
              <td>{{ $o->farmer->fullname ?? $o->farmer->username ?? '-' }}</td>
              <td>{{ number_format($o->quantity_kilos, 2) }}</td>
              <td class="fw-bold text-success">₱{{ number_format($o->total_price, 2) }}</td>
              <td>
                <span class="badge bg-secondary text-uppercase">{{ $o->status }}</span>
              </td>
              <td>{{ $o->created_at ? $o->created_at->format('Y-m-d H:i') : '-' }}</td>
              <td>
                <a href="{{ route('resident.orders.show', $o->id) }}" class="btn btn-sm btn-outline-primary">Track</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">No orders yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>

      <div class="mt-3">
        {{ $orders->links() }}
      </div>
    </div>
  </div>
</div>

</body>
</html>
