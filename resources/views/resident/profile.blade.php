<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile | Resident</title>
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
    #residentMap { height: 320px; border-radius: 14px; border: 1px solid rgba(0,0,0,.10); }
    .leaflet-container { background: #f8f9fa; }
  </style>
</head>
<body class="bg-light">

<div class="container py-4" style="max-width:700px;">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold text-success m-0">My Profile</h3>
      <div class="text-muted small">Update your account information</div>
    </div>
    <a href="{{ route('resident.dashboard') }}" class="btn btn-outline-success btn-sm">Back</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <div class="card shadow-sm">
    <div class="card-body p-4">
      <form method="POST" action="{{ route('resident.profile.update') }}">
        @csrf

        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="fullname" class="form-control" value="{{ old('fullname', $user->fullname) }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Email (optional)</label>
          <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
        </div>

        <div class="mb-3">
          <label class="form-label">Delivery Location</label>
          <div class="text-muted small mb-2">Click the map to save your delivery coordinates. This helps track your orders and show your address on the order map.</div>
          <input id="resident_lat" type="hidden" name="latitude" value="{{ old('latitude', $user->latitude) }}">
          <input id="resident_lng" type="hidden" name="longitude" value="{{ old('longitude', $user->longitude) }}">
          <div id="residentMap"></div>
          <div class="row mt-3">
            <div class="col">
              <label class="form-label">Latitude</label>
              <input id="resident_lat_display" type="text" class="form-control" value="{{ old('latitude', $user->latitude) }}" readonly>
            </div>
            <div class="col">
              <label class="form-label">Longitude</label>
              <input id="resident_lng_display" type="text" class="form-control" value="{{ old('longitude', $user->longitude) }}" readonly>
            </div>
          </div>
        </div>

        <button class="btn btn-success w-100">Save Changes</button>
      </form>
    </div>
  </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  const savedLat = {{ $user->latitude !== null ? (float) $user->latitude : 'null' }};
  const savedLng = {{ $user->longitude !== null ? (float) $user->longitude : 'null' }};
  const mapCenter = savedLat !== null && savedLng !== null ? [savedLat, savedLng] : [18.2760, 121.6440];
  const residentMap = L.map('residentMap').setView(mapCenter, savedLat !== null && savedLng !== null ? 14 : 12);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(residentMap);

  let residentMarker = null;

  if (savedLat !== null && savedLng !== null) {
    residentMarker = L.marker([savedLat, savedLng]).addTo(residentMap)
      .bindPopup('Saved delivery location').openPopup();
  }

  residentMap.on('click', function(e) {
    const lat = e.latlng.lat.toFixed(8);
    const lng = e.latlng.lng.toFixed(8);

    if (residentMarker) {
      residentMap.removeLayer(residentMarker);
    }

    residentMarker = L.marker([lat, lng]).addTo(residentMap)
      .bindPopup('Delivery location saved').openPopup();

    document.getElementById('resident_lat').value = lat;
    document.getElementById('resident_lng').value = lng;
    document.getElementById('resident_lat_display').value = lat;
    document.getElementById('resident_lng_display').value = lng;
  });
</script>
</body>
</html>

