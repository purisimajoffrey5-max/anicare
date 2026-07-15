<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Track Order #{{ $order->id }} | Resident</title>
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
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
  <style>
    #trackMap { height: 420px; border-radius: 14px; border: 1px solid rgba(0,0,0,.12); }
    .leaflet-container { background: #f8f9fa; }
    .delivery-marker, .home-marker, .farm-marker {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 38px;
      height: 38px;
      border-radius: 50%;
      color: #fff;
      font-size: 18px;
      text-align: center;
      border: 2px solid #fff;
      box-shadow: 0 0 0 2px rgba(0,0,0,.08);
    }
    .delivery-marker { background: #198754; }
    .farm-marker { background: #dc3545; }
    .home-marker { background: #0d6efd; }
    .track-label { font-size: 0.95rem; color: #6c757d; }
  </style>
</head>
<body class="bg-light">

<div class="container py-4" style="max-width:1100px;">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold text-success m-0">Track Order #{{ $order->id }}</h3>
      <div class="text-muted small">Delivery status and rider location</div>
    </div>
    <a href="{{ route('resident.orders.index') }}" class="btn btn-outline-success btn-sm">Back to Orders</a>
  </div>

  <div class="row gy-3">
    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title fw-semibold">Order Summary</h5>
          <dl class="row mb-0">
            <dt class="col-5 text-muted">Product</dt>
            <dd class="col-7">{{ $order->product->name ?? '-' }}</dd>

            <dt class="col-5 text-muted">Farmer</dt>
            <dd class="col-7">{{ $order->farmer->fullname ?? $order->farmer->username ?? '-' }}</dd>

            <dt class="col-5 text-muted">Quantity</dt>
            <dd class="col-7">{{ number_format($order->quantity_kilos, 2) }} kg</dd>

            <dt class="col-5 text-muted">Total</dt>
            <dd class="col-7 fw-bold text-success">₱{{ number_format($order->total_price, 2) }}</dd>

            <dt class="col-5 text-muted">Fulfillment</dt>
            <dd class="col-7 text-capitalize">{{ $order->fulfillment_type }}</dd>

            <dt class="col-5 text-muted">Status</dt>
            <dd class="col-7">{{ strtoupper($order->status) }}</dd>

            <dt class="col-5 text-muted">Delivery Address</dt>
            <dd class="col-7">{{ $order->delivery_address ?? 'Pickup order / no delivery address' }}</dd>

            <dt class="col-5 text-muted">Order created</dt>
            <dd class="col-7">{{ $order->created_at ? $order->created_at->format('Y-m-d H:i') : '-' }}</dd>
          </dl>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <h5 class="card-title fw-semibold">Delivery Tracking</h5>
              <p class="track-label mb-0">{{ $deliveryNote }}</p>
            </div>
            <span class="badge bg-secondary text-uppercase">{{ $order->status }}</span>
          </div>

          <div id="trackMap"></div>

          <div class="mt-3">
            <div class="d-flex gap-2 flex-wrap">
              <span class="badge bg-success">🛵 Rider</span>
              <span class="badge bg-danger">🌾 Farmer</span>
              <span class="badge bg-primary">🏠 You</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  const mapCenter = [{{ $mapCenterLat }}, {{ $mapCenterLng }}];
  const map = L.map('trackMap').setView(mapCenter, 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  const riderIcon = L.divIcon({ className: 'delivery-marker', html: '🛵', iconSize: [38, 38], iconAnchor: [19, 19] });
  const farmIcon = L.divIcon({ className: 'farm-marker', html: '🌾', iconSize: [38, 38], iconAnchor: [19, 19] });
  const homeIcon = L.divIcon({ className: 'home-marker', html: '🏠', iconSize: [38, 38], iconAnchor: [19, 19] });

  const currentLocation = @json($currentLocation);
  const farmerLocation = @json($farmerLocation);
  const buyerLocation = @json($buyerLocation);
  const currentLocationText = @json($currentLocationText);
  const farmerLabel = @json($order->farmer->fullname ?? $order->farmer->username ?? 'Farmer');
  const buyerLabel = @json($order->resident->fullname ?? $order->resident->username ?? 'You');

  const markers = [];

  if (currentLocation) {
    const marker = L.marker([currentLocation.lat, currentLocation.lng], { icon: riderIcon })
      .addTo(map)
      .bindPopup(`<strong>Delivery Rider</strong><br>${currentLocationText}`);
    markers.push(marker);
  }

  if (farmerLocation) {
    const marker = L.marker([farmerLocation.lat, farmerLocation.lng], { icon: farmIcon })
      .addTo(map)
      .bindPopup(`<strong>Farmer</strong><br>${farmerLabel}`);
    markers.push(marker);
  }

  if (buyerLocation) {
    const marker = L.marker([buyerLocation.lat, buyerLocation.lng], { icon: homeIcon })
      .addTo(map)
      .bindPopup(`<strong>Your Location</strong><br>${buyerLabel}`);
    markers.push(marker);
  }

  if (markers.length > 0) {
    const group = L.featureGroup(markers);
    map.fitBounds(group.getBounds().pad(0.4));
  }
</script>

</body>
</html>

