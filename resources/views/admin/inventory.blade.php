<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Inventory | ANI-CARE</title>
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
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold text-success m-0">Inventory</h3>
      <div class="text-muted">Real-time rice and palay stock managed by admin.</div>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-success btn-sm">Back</a>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-6">
      <div class="card border-success h-100">
        <div class="card-body">
          <h5 class="card-title">Rice Stock</h5>
          <p class="display-6 mb-0">{{ number_format($totalRice ?? 0, 2) }} kg</p>
          <p class="text-muted mb-0">Available rice inventory</p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card border-primary h-100">
        <div class="card-body">
          <h5 class="card-title">Palay Stock</h5>
          <p class="display-6 mb-0">{{ number_format($totalPalay ?? 0, 2) }} kg</p>
          <p class="text-muted mb-0">Palay awaiting milling or in stock</p>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm mb-4">
    <div class="card-body p-3">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h5 class="mb-1">Inventory Items</h5>
          <div class="text-muted">Latest inventory movements and stock records.</div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Item</th>
              <th>Type</th>
              <th>Stock</th>
              <th>Price/kg</th>
              <th>Status</th>
              <th>Notes</th>
              <th>Created</th>
            </tr>
          </thead>
          <tbody>
            @forelse($items as $item)
              <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td class="text-uppercase">{{ $item->product_type }}</td>
                <td>{{ number_format($item->kilos_available, 2) }} kg</td>
                <td>₱{{ number_format($item->price_per_kg, 2) }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $item->status)) }}</td>
                <td>{{ $item->notes ?? '-' }}</td>
                <td>{{ $item->created_at->format('M d, Y') }}</td>
                <td>
                  @if(strtolower($item->product_type) === 'palay' && $item->status === 'awaiting_milling')
                    @if($item->millingRequest && !$item->millingRequest->miller_id)
                      <a href="{{ route('admin.inventory.assign.form', $item->id) }}" class="btn btn-sm btn-primary">Assign Miller</a>
                    @else
                      <span class="small text-muted">Assigned</span>
                    @endif
                  @else
                    -
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center text-muted">No inventory records found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-center mt-3">
        {{ $items->links() }}
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
