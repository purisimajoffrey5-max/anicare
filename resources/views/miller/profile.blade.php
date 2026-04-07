<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Miller Profile | ANI-CARE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- ✅ Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
</head>
<body class="bg-light">

<div class="container py-4" style="max-width:900px;">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold text-primary m-0">My Miller Profile</h3>
      <div class="text-muted small">Update your milling information & location</div>
    </div>
    <a href="{{ route('miller.dashboard') }}" class="btn btn-outline-primary btn-sm">Back</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <!-- ✅ MILLER INFO (basic) -->
  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <div class="mb-2">
        <div class="fw-bold">Account</div>
        <div class="text-muted small">Fullname: {{ $user->fullname }} • Username: {{ $user->username }}</div>
      </div>

      {{-- If you have a miller profile table, you can add fields here later --}}
      <div class="alert alert-info mb-0">
        Tip: You can add more fields (milling capacity, contact no, address) anytime. For now, location picker is ready.
      </div>
    </div>
  </div>

  <!-- ✅ LOCATION PICKER (MILLER) -->
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="fw-bold">📍 Set Milling Station Location (Map)</div>
          <div class="text-muted small">Click the map to drop a pin. Residents will see your location + open/closed status.</div>
        </div>
        <span class="badge bg-primary">Miller</span>
      </div>

      <form method="POST" action="{{ route('miller.location.save') }}" class="mt-3">
        @csrf

        <div class="row g-2 mb-2">
          <div class="col-md-6">
            <label class="form-label">Latitude</label>
            <input id="miller_lat" name="latitude" class="form-control"
                   value="{{ old('latitude', auth()->user()->latitude) }}" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Longitude</label>
            <input id="miller_lng" name="longitude" class="form-control"
                   value="{{ old('longitude', auth()->user()->longitude) }}" readonly>
          </div>
        </div>

        <div id="millerMap" style="height:420px;border-radius:14px;border:1px solid rgba(0,0,0,.10);"></div>

        <div class="d-flex gap-2 mt-3">
          <button class="btn btn-primary">Save Location</button>
          <button type="button" id="millerReset" class="btn btn-outline-secondary">Reset Pin</button>
        </div>
      </form>
    </div>
  </div>

</div>

<!-- ✅ Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
  const center = [18.2760, 121.6440]; // Allacapan center
  const savedLat = "{{ auth()->user()->latitude }}";
  const savedLng = "{{ auth()->user()->longitude }}";

  const millerMap = L.map('millerMap').setView(center, 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(millerMap);

  let millerMarker = null;

  if (savedLat && savedLng) {
    millerMarker = L.marker([parseFloat(savedLat), parseFloat(savedLng)]).addTo(millerMap);
    millerMap.setView([parseFloat(savedLat), parseFloat(savedLng)], 15);
  }

  function setPin(lat, lng) {
    document.getElementById('miller_lat').value = lat;
    document.getElementById('miller_lng').value = lng;

    if (millerMarker) millerMap.removeLayer(millerMarker);
    millerMarker = L.marker([lat, lng]).addTo(millerMap);
  }

  millerMap.on('click', (e) => {
    setPin(e.latlng.lat.toFixed(8), e.latlng.lng.toFixed(8));
  });

  document.getElementById('millerReset').addEventListener('click', () => {
    if (millerMarker) millerMap.removeLayer(millerMarker);
    millerMarker = null;
    document.getElementById('miller_lat').value = '';
    document.getElementById('miller_lng').value = '';
    millerMap.setView(center, 13);
  });
</script>

</body>
</html>