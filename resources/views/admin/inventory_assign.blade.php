<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Assign Miller | ANI-CARE</title>
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
<div class="container py-4" style="max-width:900px">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold text-success m-0">Assign Miller</h3>
      <div class="text-muted">Assign a miller to mill the selected palay.</div>
    </div>
    <a href="{{ route('admin.inventory') }}" class="btn btn-outline-success btn-sm">Back</a>
  </div>

  <div class="card mb-3">
    <div class="card-body">
      <h5 class="mb-1">Item: {{ $item->name }}</h5>
      <div class="text-muted">Stock: {{ number_format($item->kilos_available,2) }} kg • Status: {{ ucfirst($item->status) }}</div>
      @if($item->millingRequest)
        <div class="small text-muted">Milling request #{{ $item->millingRequest->id }} • Kilos: {{ number_format($item->millingRequest->kilos,2) }}</div>
      @endif
    </div>
  </div>

  <form method="POST" action="{{ route('admin.inventory.assign', $item->id) }}">
    @csrf

    <div class="card">
      <div class="card-body">
        <h6>Select a Miller</h6>

        @if($millers->isEmpty())
          <div class="alert alert-warning">No millers found.</div>
        @else
          <div class="list-group">
            @foreach($millers as $m)
              <label class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <div class="fw-semibold">{{ $m->fullname ?? $m->username }}</div>
                  <div class="small text-muted">@{{ $m->username }} • {{ $m->is_open ? 'OPEN' : 'CLOSED' }}</div>
                </div>
                <div>
                  <input type="radio" name="miller_id" value="{{ $m->id }}" required>
                </div>
              </label>
            @endforeach
          </div>
        @endif

        <div class="mt-3">
          <button class="btn btn-success">Assign Miller</button>
        </div>
      </div>
    </div>
  </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

