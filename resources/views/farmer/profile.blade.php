<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Farm Profile | ANI-CARE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- ✅ Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
</head>
<body class="bg-light">

<div class="container py-4" style="max-width:900px;">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold text-success m-0">My Farm Profile</h3>
      <div class="text-muted small">Update your farm information</div>
    </div>
    <a href="{{ route('farmer.dashboard') }}" class="btn btn-outline-success btn-sm">Back</a>
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

  <!-- ✅ FARM INFO -->
  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <div class="mb-3">
        <div class="fw-bold">Account</div>
        <div class="text-muted small">Fullname: {{ $user->fullname }} • Username: {{ $user->username }}</div>
      </div>

      <form method="POST" action="{{ route('farmer.profile.update') }}">
        @csrf

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Address</label>
            <input class="form-control" name="address" value="{{ old('address', $profile->address ?? '') }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Barangay</label>
            <input class="form-control" name="barangay" value="{{ old('barangay', $profile->barangay ?? '') }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Municipality</label>
            <input class="form-control" name="municipality" value="{{ old('municipality', $profile->municipality ?? '') }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Province</label>
            <input class="form-control" name="province" value="{{ old('province', $profile->province ?? '') }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Farm Size (hectares)</label>
            <input type="number" step="0.01" class="form-control" name="farm_size_hectares"
                   value="{{ old('farm_size_hectares', $profile->farm_size_hectares ?? '') }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Contact No.</label>
            <input class="form-control" name="contact_no" value="{{ old('contact_no', $profile->contact_no ?? '') }}">
          </div>
        </div>

        <button class="btn btn-success mt-3">Save Profile</button>
      </form>
    </div>
  </div>

  <!-- ✅ LOCATION PICKER (FARMER) -->
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="fw-bold">📍 Set Farm Location (Map)</div>
          <div class="text-muted small">Click the map to drop a pin. Residents will see your location.</div>
        </div>
        <span class="badge bg-success">Farmer</span>
      </div>

      <form method="POST" action="{{ route('farmer.location.save') }}" class="mt-3">
        @csrf

        <div class="row g-2 mb-2">
          <div class="col-md-6">
            <label class="form-label">Latitude</label>
            <input id="farmer_lat" name="latitude" class="form-control"
                   value="{{ old('latitude', auth()->user()->latitude) }}" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Longitude</label>
            <input id="farmer_lng" name="longitude" class="form-control"
                   value="{{ old('longitude', auth()->user()->longitude) }}" readonly>
          </div>
        </div>

        <div id="farmerMap" style="height:420px;border-radius:14px;border:1px solid rgba(0,0,0,.10);"></div>

        <div class="d-flex gap-2 mt-3">
          <button class="btn btn-success">Save Location</button>
          <button type="button" id="farmerReset" class="btn btn-outline-secondary">Reset Pin</button>
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

  const farmerMap = L.map('farmerMap').setView(center, 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(farmerMap);

  let farmerMarker = null;

  if (savedLat && savedLng) {
    farmerMarker = L.marker([parseFloat(savedLat), parseFloat(savedLng)]).addTo(farmerMap);
    farmerMap.setView([parseFloat(savedLat), parseFloat(savedLng)], 15);
  }

  function setPin(lat, lng) {
    document.getElementById('farmer_lat').value = lat;
    document.getElementById('farmer_lng').value = lng;

    if (farmerMarker) farmerMap.removeLayer(farmerMarker);
    farmerMarker = L.marker([lat, lng]).addTo(farmerMap);
  }

  farmerMap.on('click', (e) => {
    setPin(e.latlng.lat.toFixed(8), e.latlng.lng.toFixed(8));
  });

  document.getElementById('farmerReset').addEventListener('click', () => {
    if (farmerMarker) farmerMap.removeLayer(farmerMarker);
    farmerMarker = null;
    document.getElementById('farmer_lat').value = '';
    document.getElementById('farmer_lng').value = '';
    farmerMap.setView(center, 13);
  });
</script>

</body>
</html>