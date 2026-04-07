<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Request Milling | ANI-CARE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

  <style>
    body { background:#f5f7fb; }
    .card-soft{
      border-radius:16px;
      border:1px solid rgba(0,0,0,.07);
      background:#fff;
      box-shadow: 0 10px 22px rgba(16,24,40,.06);
    }
    .btn-soft{ border-radius:12px; }
    .pill{
      border-radius:999px;
      padding:6px 10px;
      font-size:12px;
      display:inline-flex;
      align-items:center;
      gap:6px;
    }
    .miller-row{
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:10px;
      padding:10px 0;
      border-bottom:1px solid rgba(0,0,0,.06);
    }
    .miller-row:last-child{ border-bottom:0; }
    #map{
      height: 420px;
      border-radius: 14px;
      border:1px solid rgba(0,0,0,.10);
    }
    .small-muted{ font-size: 13px; color:#6c757d; }
  </style>
</head>
<body>

<div class="container py-4" style="max-width:1100px;">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold text-success m-0">Request Milling</h3>
      <div class="text-muted small">Choose an OPEN miller, view location on map, and submit your request.</div>
    </div>
    <a href="{{ route('farmer.dashboard') }}" class="btn btn-outline-success btn-sm btn-soft">Back</a>
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

  <div class="row g-3">

    {{-- LEFT: FORM --}}
    <div class="col-12 col-lg-5">
      <div class="card-soft p-4">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <div class="fw-bold">🧾 Request Details</div>
            <div class="small-muted">Fill up kilos, choose miller, then submit.</div>
          </div>
        </div>

        <form method="POST" action="{{ route('farmer.milling.store') }}" id="millingForm">
          @csrf

          <div class="mb-3">
            <label class="form-label">Kilos to Mill</label>
            <input type="number" step="0.01" min="1" name="kilos" class="form-control"
                   value="{{ old('kilos') }}" required>
            <div class="form-text">Example: 50, 120.5</div>
          </div>

          <div class="mb-3">
            <label class="form-label">Notes (optional)</label>
            <textarea name="notes" class="form-control" rows="3" maxlength="1000">{{ old('notes') }}</textarea>
            <div class="form-text">Add details like schedule preference, rice type, etc.</div>
          </div>

          {{-- ✅ Selected Miller --}}
          <div class="mb-3">
            <label class="form-label">Selected Miller</label>
            <input type="hidden" name="miller_id" id="miller_id" value="{{ old('miller_id') }}">
            <div class="card border-0 bg-light p-3" style="border-radius:12px;">
              <div class="fw-bold" id="selectedMillerName">
                {{ old('miller_id') ? 'Selected from previous input' : 'No miller selected' }}
              </div>
              <div class="small-muted" id="selectedMillerMeta">Select from list or click marker on map.</div>
            </div>
          </div>

          {{-- ✅ Nearest OPEN miller info --}}
          <div class="alert alert-info small mb-3">
            <div class="fw-bold">📍 Nearest OPEN Miller:</div>
            <div id="nearestBox">Calculating...</div>
            <div class="text-muted">
              Uses your saved farm location (latitude/longitude). If you have no location saved,
              please set it in Farm Profile map.
            </div>
          </div>

          <button class="btn btn-success btn-soft w-100 mb-2">Submit Request</button>
          <a href="{{ route('farmer.milling.index') }}" class="btn btn-outline-secondary btn-soft w-100">View My Requests</a>
        </form>
      </div>
    </div>

    {{-- RIGHT: MAP + LIST --}}
    <div class="col-12 col-lg-7">
      <div class="card-soft p-4 mb-3">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <div class="fw-bold">🗺️ Millers Map (Allacapan)</div>
            <div class="small-muted">Click a marker to select a miller.</div>
          </div>
          <div class="d-flex gap-2">
            <span class="pill bg-success text-white">OPEN</span>
            <span class="pill bg-secondary text-white">CLOSED</span>
          </div>
        </div>

        <div id="map"></div>
      </div>

      <div class="card-soft p-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div class="fw-bold">⚙️ Registered Millers</div>
          <div class="text-muted small">Select one (OPEN recommended)</div>
        </div>

        @forelse(($millers ?? []) as $m)
          <div class="miller-row">
            <div>
              <div class="fw-semibold">{{ $m->fullname ?? $m->username }}</div>
              <div class="small-muted">@{{ $m->username }}</div>
              <div class="small-muted">
                Location:
                @if($m->latitude && $m->longitude)
                  <span class="text-success">Available</span>
                @else
                  <span class="text-muted">Not set</span>
                @endif
              </div>
            </div>

            <div class="text-end">
              @if($m->is_open)
                <div class="pill bg-success text-white mb-2">OPEN</div>
              @else
                <div class="pill bg-secondary text-white mb-2">CLOSED</div>
              @endif

              <button type="button"
                class="btn btn-sm {{ $m->is_open ? 'btn-outline-success' : 'btn-outline-secondary' }} btn-soft"
                onclick="selectMiller({{ $m->id }}, @json($m->fullname ?? $m->username), @json($m->username), {{ $m->is_open ? 'true' : 'false' }})">
                Select
              </button>
            </div>
          </div>
        @empty
          <div class="text-muted">No millers found.</div>
        @endforelse
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
  // ✅ Farmer location (from users table)
  const farmerLat = {{ auth()->user()->latitude ? (float) auth()->user()->latitude : 'null' }};
  const farmerLng = {{ auth()->user()->longitude ? (float) auth()->user()->longitude : 'null' }};

  // ✅ Map center (Allacapan)
  const center = [18.2760, 121.6440];
  const map = L.map('map').setView(center, 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  // ✅ Icons
  const openIcon = new L.Icon({
    iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png",
    shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png",
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
  });

  const closedIcon = new L.Icon({
    iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-grey.png",
    shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png",
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
  });

  // ✅ Farmer marker (if has location)
  if (farmerLat && farmerLng) {
    L.circleMarker([farmerLat, farmerLng], {
      radius: 8,
      weight: 2,
      color: "#198754"
    }).addTo(map).bindPopup("<b>Your Farm Location</b>");
    map.setView([farmerLat, farmerLng], 13);
  }

  // ✅ Millers data from blade (FIXED: safe arrow function)
  const millersRaw = @json($millers ?? []);

  const millers = (Array.isArray(millersRaw) ? millersRaw : []).map(m => ({
    id: m.id,
    fullname: m.fullname ?? m.username ?? 'Miller',
    username: m.username ?? '',
    is_open: !!m.is_open,
    latitude: m.latitude,
    longitude: m.longitude
  }));

  // ✅ helper: select miller
  function selectMiller(id, fullname, username, isOpen){
    document.getElementById('miller_id').value = id;
    document.getElementById('selectedMillerName').textContent = fullname;
    document.getElementById('selectedMillerMeta').textContent = `@${username} • ${isOpen ? 'OPEN' : 'CLOSED'}`;
  }

  // ✅ Haversine distance (km)
  function haversine(lat1, lon1, lat2, lon2){
    const R = 6371;
    const dLat = (lat2 - lat1) * Math.PI/180;
    const dLon = (lon2 - lon1) * Math.PI/180;
    const a =
      Math.sin(dLat/2) * Math.sin(dLat/2) +
      Math.cos(lat1*Math.PI/180) * Math.cos(lat2*Math.PI/180) *
      Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
  }

  // ✅ Place millers markers + compute nearest OPEN
  let nearest = null;

  millers.forEach(m => {
    const lat = parseFloat(m.latitude);
    const lng = parseFloat(m.longitude);
    if (!lat || !lng) return; // skip if no location

    const icon = m.is_open ? openIcon : closedIcon;

    const popupHtml = `
      <div style="min-width:200px">
        <b>${m.fullname}</b><br>
        @${m.username}<br>
        Status: <b>${m.is_open ? 'OPEN' : 'CLOSED'}</b><br>
        <button type="button" class="btn btn-sm btn-success mt-2"
          onclick="selectMiller(${m.id}, ${JSON.stringify(m.fullname)}, ${JSON.stringify(m.username)}, ${m.is_open})">
          Select this miller
        </button>
      </div>
    `;

    L.marker([lat,lng], {icon}).addTo(map).bindPopup(popupHtml);

    if (farmerLat && farmerLng && m.is_open) {
      const km = haversine(farmerLat, farmerLng, lat, lng);
      if (!nearest || km < nearest.km) {
        nearest = { ...m, km };
      }
    }
  });

  // ✅ Update nearest box
  const nearestBox = document.getElementById('nearestBox');

  if (!farmerLat || !farmerLng) {
    nearestBox.innerHTML = `<span class="text-danger">No farm location saved.</span> Please set your farm location in <b>Farm Profile</b>.`;
  } else if (!nearest) {
    nearestBox.innerHTML = `<span class="text-danger">No OPEN miller with location found.</span>`;
  } else {
    nearestBox.innerHTML = `
      <b>${nearest.fullname}</b> (@${nearest.username})<br>
      Distance: <b>${nearest.km.toFixed(2)} km</b><br>
      <button type="button" class="btn btn-sm btn-success mt-2 btn-soft"
        onclick="selectMiller(${nearest.id}, ${JSON.stringify(nearest.fullname)}, ${JSON.stringify(nearest.username)}, true)">
        Select Nearest Open Miller
      </button>
    `;
  }
</script>

</body>
</html>