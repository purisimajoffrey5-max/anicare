<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | ANI-CARE</title>
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
    #registrationMap { height: 260px; border-radius: 14px; border: 1px solid rgba(0,0,0,.10); }
    .leaflet-container { background: #f8f9fa; }
  </style>
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm">
        <div class="card-body p-4">

          <h4 class="fw-bold text-success text-center mb-2">Create Account</h4>
          <p class="text-center text-muted small mb-4">Register as a Resident, Farmer, or Miller</p>

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" name="fullname" class="form-control"
                     value="{{ old('fullname') }}" required minlength="3" maxlength="100">
            </div>

            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" name="username" class="form-control"
                     value="{{ old('username') }}" required minlength="4" maxlength="30">
              <div class="form-text">Letters, numbers, dash/underscore only.</div>
            </div>

            <div class="mb-3">
              <label class="form-label">Email (optional)</label>
              <input type="email" name="email" class="form-control" value="{{ old('email') }}">
            </div>

            <div class="mb-3">
              <label class="form-label">Barangay</label>
              <select name="barangay" class="form-select" required>
                <option value="" disabled {{ old('barangay') ? '' : 'selected' }}>-- Select Barangay --</option>
                <option value="Bessang" {{ old('barangay') == 'Bessang' ? 'selected' : '' }}>Bessang</option>
                <option value="Binubungan" {{ old('barangay') == 'Binubungan' ? 'selected' : '' }}>Binubungan</option>
                <option value="Bulo" {{ old('barangay') == 'Bulo' ? 'selected' : '' }}>Bulo</option>
                <option value="Burot" {{ old('barangay') == 'Burot' ? 'selected' : '' }}>Burot</option>
                <option value="Capagaran (Brigida)" {{ old('barangay') == 'Capagaran (Brigida)' ? 'selected' : '' }}>Capagaran (Brigida)</option>
                <option value="Capalutan" {{ old('barangay') == 'Capalutan' ? 'selected' : '' }}>Capalutan</option>
                <option value="Cataratan" {{ old('barangay') == 'Cataratan' ? 'selected' : '' }}>Cataratan</option>
                <option value="Centro East (Poblacion)" {{ old('barangay') == 'Centro East (Poblacion)' ? 'selected' : '' }}>Centro East (Poblacion)</option>
                <option value="Centro West (Poblacion)" {{ old('barangay') == 'Centro West (Poblacion)' ? 'selected' : '' }}>Centro West (Poblacion)</option>
                <option value="Daan-Ili" {{ old('barangay') == 'Daan-Ili' ? 'selected' : '' }}>Daan-Ili</option>
                <option value="Dagupan" {{ old('barangay') == 'Dagupan' ? 'selected' : '' }}>Dagupan</option>
                <option value="Dalayap" {{ old('barangay') == 'Dalayap' ? 'selected' : '' }}>Dalayap</option>
                <option value="Gagaddangan" {{ old('barangay') == 'Gagaddangan' ? 'selected' : '' }}>Gagaddangan</option>
                <option value="Iringan" {{ old('barangay') == 'Iringan' ? 'selected' : '' }}>Iringan</option>
                <option value="Kapanickian Norte" {{ old('barangay') == 'Kapanickian Norte' ? 'selected' : '' }}>Kapanickian Norte</option>
                <option value="Kapanickian Sur" {{ old('barangay') == 'Kapanickian Sur' ? 'selected' : '' }}>Kapanickian Sur</option>
                <option value="Labben" {{ old('barangay') == 'Labben' ? 'selected' : '' }}>Labben</option>
                <option value="Maluyo" {{ old('barangay') == 'Maluyo' ? 'selected' : '' }}>Maluyo</option>
                <option value="Mapurao" {{ old('barangay') == 'Mapurao' ? 'selected' : '' }}>Mapurao</option>
                <option value="Matucay" {{ old('barangay') == 'Matucay' ? 'selected' : '' }}>Matucay</option>
                <option value="Nagattatan" {{ old('barangay') == 'Nagattatan' ? 'selected' : '' }}>Nagattatan</option>
                <option value="Pacac" {{ old('barangay') == 'Pacac' ? 'selected' : '' }}>Pacac</option>
                <option value="San Juan (Maguininango)" {{ old('barangay') == 'San Juan (Maguininango)' ? 'selected' : '' }}>San Juan (Maguininango)</option>
                <option value="Silagan" {{ old('barangay') == 'Silagan' ? 'selected' : '' }}>Silagan</option>
                <option value="Tamboli" {{ old('barangay') == 'Tamboli' ? 'selected' : '' }}>Tamboli</option>
                <option value="Tubel" {{ old('barangay') == 'Tubel' ? 'selected' : '' }}>Tubel</option>
                <option value="Utan" {{ old('barangay') == 'Utan' ? 'selected' : '' }}>Utan</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Register As</label>
              <select name="role" class="form-select" required>
                <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Select Role --</option>
                <option value="resident" {{ old('role') === 'resident' ? 'selected' : '' }}>Resident</option>
                <option value="farmer" {{ old('role') === 'farmer' ? 'selected' : '' }}>Farmer</option>
                <option value="miller" {{ old('role') === 'miller' ? 'selected' : '' }}>Miller</option>
              </select>
              <small class="text-muted">All accounts require admin approval before login.</small>
            </div>

            <div class="row g-2">
              <div class="col-md-6 mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required minlength="8">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required minlength="8">
              </div>
            </div>

            <div class="alert alert-info small">
              Password must be at least 8 characters and include <b>uppercase</b>, <b>lowercase</b>, and a <b>number</b>.
            </div>

            <button type="submit" class="btn btn-success w-100">Register</button>

            <div class="text-center mt-3">
              <a href="{{ route('login') }}">Already have an account?</a><br>
              <a href="{{ route('main') }}" class="small">Back to Home</a>
            </div>
          </form>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  const savedLat = {{ old('latitude') !== null ? (float) old('latitude') : 'null' }};
  const savedLng = {{ old('longitude') !== null ? (float) old('longitude') : 'null' }};
  const mapCenter = savedLat !== null && savedLng !== null ? [savedLat, savedLng] : [18.2760, 121.6440];
  const registrationMap = L.map('registrationMap').setView(mapCenter, savedLat !== null && savedLng !== null ? 14 : 12);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(registrationMap);

  let registrationMarker = null;

  if (savedLat !== null && savedLng !== null) {
    registrationMarker = L.marker([savedLat, savedLng]).addTo(registrationMap)
      .bindPopup('Selected location').openPopup();
  }

  registrationMap.on('click', function(e) {
    const lat = e.latlng.lat.toFixed(8);
    const lng = e.latlng.lng.toFixed(8);

    if (registrationMarker) {
      registrationMap.removeLayer(registrationMarker);
    }

    registrationMarker = L.marker([lat, lng]).addTo(registrationMap)
      .bindPopup('Location selected').openPopup();

    document.getElementById('registration_lat').value = lat;
    document.getElementById('registration_lng').value = lng;
    document.getElementById('registration_lat_display').value = lat;
    document.getElementById('registration_lng_display').value = lng;
  });
</script>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
