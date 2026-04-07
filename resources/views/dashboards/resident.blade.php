<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Resident Dashboard | ANI-CARE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

  <style>
    *{
      margin:0;
      padding:0;
      box-sizing:border-box;
    }

    :root{
      --primary:#ee4d2d;
      --primary-dark:#d94224;
      --accent:#fff1ec;
      --bg:#f5f5f5;
      --card:#ffffff;
      --text:#222;
      --muted:#6b7280;
      --border:#e5e7eb;
      --shadow:0 4px 14px rgba(15,23,42,.06);
    }

    body{
      font-family:"Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background:var(--bg);
      color:var(--text);
    }

    a{
      text-decoration:none;
      color:inherit;
    }

    .top-header{
      background:#198754;
      color:#fff;
      box-shadow:0 4px 14px rgba(0,0,0,.08);
    }

    .top-header-inner{
      width:100%;
      padding:10px 14px;
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:12px;
      flex-wrap:wrap;
    }

    .brand{
      font-size:18px;
      font-weight:800;
      color:#fff;
    }

    .header-actions{
      display:flex;
      align-items:center;
      gap:8px;
      flex-wrap:wrap;
    }

    .header-chip{
      background:#ffffff;
      color:#111827;
      border:1px solid rgba(255,255,255,.25);
      padding:7px 12px;
      border-radius:4px;
      font-size:13px;
      font-weight:600;
    }

    .header-chip.active{
      border:1px solid #ffffff;
      background:transparent;
      color:#fff;
    }

    .logout-btn{
      border:none;
      background:#facc15;
      color:#111827;
      padding:7px 12px;
      border-radius:4px;
      font-size:13px;
      font-weight:700;
    }

    .page-wrap{
      max-width:1120px;
      margin:0 auto;
      padding:12px 14px 40px;
    }

    .welcome-box{
      margin-bottom:14px;
    }

    .welcome-title{
      font-size:24px;
      font-weight:800;
      color:#111827;
      margin-bottom:4px;
    }

    .welcome-sub{
      font-size:13px;
      color:var(--muted);
    }

    .stats-strip{
      display:grid;
      grid-template-columns:repeat(4, 1fr);
      gap:12px;
      margin-bottom:12px;
    }

    .stat-box{
      background:var(--card);
      border:1px solid var(--border);
      border-radius:14px;
      padding:16px;
      box-shadow:var(--shadow);
    }

    .stat-label{
      color:var(--muted);
      font-size:12px;
      margin-bottom:6px;
    }

    .stat-number{
      font-size:18px;
      font-weight:800;
      color:#111827;
      line-height:1.1;
      margin-bottom:4px;
    }

    .stat-foot{
      font-size:12px;
      color:#6b7280;
    }

    .dashboard-grid{
      display:grid;
      grid-template-columns:1.65fr .85fr;
      gap:12px;
      margin-bottom:12px;
    }

    .bottom-grid{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:12px;
      margin-bottom:12px;
    }

    .section-card{
      background:var(--card);
      border:1px solid var(--border);
      border-radius:14px;
      padding:14px;
      box-shadow:var(--shadow);
    }

    .section-head{
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:10px;
      margin-bottom:10px;
      flex-wrap:wrap;
    }

    .section-head h3,
    .section-head h4{
      font-size:16px;
      font-weight:800;
      margin:0;
      color:#111827;
    }

    .section-note{
      color:var(--muted);
      font-size:11px;
    }

    #map{
      width:100%;
      height:330px;
      border-radius:12px;
      overflow:hidden;
      border:1px solid var(--border);
    }

    .announcement{
      padding:12px 14px;
      border-radius:10px;
      border:1px solid #bfd7ff;
      background:#f8fbff;
      margin-bottom:10px;
    }

    .announcement:last-child{
      margin-bottom:0;
    }

    .announcement .title{
      font-weight:800;
      margin-bottom:4px;
      color:#111827;
      font-size:14px;
    }

    .announcement .meta{
      color:var(--muted);
      font-size:11px;
      margin-top:6px;
    }

    .list-item{
      display:flex;
      justify-content:space-between;
      align-items:flex-start;
      gap:10px;
      padding:12px 0;
      border-bottom:1px solid var(--border);
    }

    .list-item:last-child{
      border-bottom:none;
      padding-bottom:0;
    }

    .list-item:first-child{
      padding-top:0;
    }

    .item-title{
      font-weight:700;
      color:#111827;
      margin-bottom:2px;
      font-size:14px;
    }

    .item-sub{
      color:var(--muted);
      font-size:12px;
    }

    .price{
      color:#198754;
      font-weight:800;
      white-space:nowrap;
      font-size:14px;
    }

    .pill{
      display:inline-flex;
      align-items:center;
      gap:6px;
      border-radius:999px;
      padding:5px 10px;
      font-size:11px;
      font-weight:800;
    }

    .pill-open{
      background:#dcfce7;
      color:#166534;
    }

    .pill-closed{
      background:#e5e7eb;
      color:#4b5563;
    }

    .empty-state{
      text-align:center;
      color:var(--muted);
      padding:18px 0;
      font-size:13px;
    }

    .browse-btn{
      border:1px solid #198754;
      background:#fff;
      color:#198754;
      font-weight:600;
      padding:6px 12px;
      border-radius:4px;
      font-size:12px;
    }

    @media (max-width: 1100px){
      .stats-strip{
        grid-template-columns:repeat(2, 1fr);
      }

      .dashboard-grid,
      .bottom-grid{
        grid-template-columns:1fr;
      }
    }

    @media (max-width: 768px){
      .stats-strip{
        grid-template-columns:1fr;
      }

      .welcome-title{
        font-size:22px;
      }

      #map{
        height:280px;
      }

      .top-header-inner{
        flex-direction:column;
        align-items:flex-start;
      }

      .header-actions{
        width:100%;
      }
    }
  </style>
</head>
<body>

@php
  $residentName = $user->fullname ?? $user->username ?? 'Resident';
@endphp

<header class="top-header">
  <div class="top-header-inner">
    <div class="brand">ANI-CARE | Resident</div>

    <div class="header-actions">
      <a href="{{ route('resident.marketplace') }}" class="header-chip active">Marketplace</a>
      <a href="{{ route('resident.orders.index') }}" class="header-chip">My Orders</a>
      <a href="{{ route('resident.profile') }}" class="header-chip">My Profile</a>

      <form method="POST" action="{{ route('logout') }}" class="m-0">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
      </form>
    </div>
  </div>
</header>

<div class="page-wrap">

  @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-3">{{ session('success') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-3">{{ $errors->first() }}</div>
  @endif

  <div class="welcome-box">
    <div class="welcome-title">Welcome, {{ $residentName }}!</div>
    <div class="welcome-sub">View farmers, millers, marketplace posts, orders, and local announcements.</div>
  </div>

  <section class="stats-strip">
    <div class="stat-box">
      <div class="stat-label">Open Millers</div>
      <div class="stat-number">{{ $openMillersCount ?? 0 }}</div>
      <div class="stat-foot">Live status today</div>
    </div>

    <div class="stat-box">
      <div class="stat-label">Marketplace Posts</div>
      <div class="stat-number">{{ $marketplaceCount ?? 0 }}</div>
      <div class="stat-foot">Active rice and palay listings</div>
    </div>

    <div class="stat-box">
      <div class="stat-label">My Orders</div>
      <div class="stat-number">{{ $myOrdersCount ?? 0 }}</div>
      <div class="stat-foot">Your placed orders</div>
    </div>

    <div class="stat-box">
      <div class="stat-label">Announcements</div>
      <div class="stat-number">{{ isset($announcements) ? $announcements->count() : 0 }}</div>
      <div class="stat-foot">Latest updates for residents</div>
    </div>
  </section>

  <section class="dashboard-grid">
    <div class="section-card">
      <div class="section-head">
        <div>
          <h3>Allacapan Farmers & Millers Map</h3>
          <div class="section-note">Click markers to view farmer and miller details.</div>
        </div>

        <div class="d-flex flex-wrap gap-2">
          <span class="pill pill-open">🌾 Farmer</span>
          <span class="pill" style="background:#dbeafe;color:#1d4ed8;">⚙️ Miller</span>
        </div>
      </div>

      <div id="map"></div>
    </div>

    <div class="section-card">
      <div class="section-head">
        <div>
          <h4>Announcements</h4>
          <div class="section-note">Latest updates for residents</div>
        </div>
      </div>

      @if(isset($announcements) && $announcements->count())
        @foreach($announcements as $a)
          <div class="announcement">
            <div class="title">{{ $a->title }}</div>
            <div>{{ $a->message }}</div>
            <div class="meta">Posted: {{ $a->created_at?->format('Y-m-d H:i') }}</div>
          </div>
        @endforeach
      @else
        <div class="empty-state">No announcements available.</div>
      @endif
    </div>
  </section>

  <section class="bottom-grid">
    <div class="section-card">
      <div class="section-head">
        <div>
          <h4>Millers Status</h4>
          <div class="section-note">Open and closed millers</div>
        </div>
        <span class="section-note">Live updates</span>
      </div>

      @forelse(($millers ?? []) as $m)
        <div class="list-item">
          <div>
            <div class="item-title">{{ $m->fullname ?? $m->username }}</div>
            <div class="item-sub">{{ '@'.$m->username }}</div>
          </div>

          @if($m->is_open)
            <span class="pill pill-open">OPEN</span>
          @else
            <span class="pill pill-closed">CLOSED</span>
          @endif
        </div>
      @empty
        <div class="empty-state">No millers found.</div>
      @endforelse
    </div>

    <div class="section-card">
      <div class="section-head">
        <div>
          <h4>Latest Farmer Posts</h4>
          <div class="section-note">Rice and palay listings</div>
        </div>
        <a href="{{ route('resident.marketplace') }}" class="browse-btn">Browse</a>
      </div>

      @forelse(($products ?? []) as $p)
        <div class="list-item">
          <div>
            <div class="item-title">{{ $p->name }}</div>
            <div class="item-sub">
              By {{ $p->user->fullname ?? $p->user->username ?? '-' }}
              • Stock: {{ $p->kilos_available ?? $p->kilos ?? 0 }} kg
            </div>
          </div>
          <div class="price">₱{{ number_format((float)($p->price_per_kg ?? $p->price ?? 0), 2) }}</div>
        </div>
      @empty
        <div class="empty-state">No marketplace posts yet.</div>
      @endforelse
    </div>
  </section>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
  const map = L.map('map').setView([18.2760, 121.6440], 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  const greenIcon = new L.Icon({
    iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png",
    shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png",
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
  });

  const blueIcon = new L.Icon({
    iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png",
    shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png",
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
  });

  fetch("{{ route('resident.map.data') }}")
    .then(r => r.json())
    .then(users => {
      if (!Array.isArray(users)) return;

      let hasMarkers = false;
      const bounds = [];

      users.forEach(u => {
        const lat = parseFloat(u.latitude);
        const lng = parseFloat(u.longitude);

        if (isNaN(lat) || isNaN(lng)) return;

        hasMarkers = true;
        bounds.push([lat, lng]);

        const icon = (u.role === 'farmer') ? greenIcon : blueIcon;

        const statusLine = (u.role === 'miller')
          ? `<br>Status: <b>${u.is_open ? 'OPEN' : 'CLOSED'}</b>`
          : '';

        const popup = `
          <div style="min-width:190px">
            <b>${u.fullname ?? u.username ?? 'User'}</b><br>
            Role: ${u.role}
            ${statusLine}
            <br><small>Lat: ${lat.toFixed(5)}, Lng: ${lng.toFixed(5)}</small>
          </div>
        `;

        L.marker([lat, lng], { icon }).addTo(map).bindPopup(popup);
      });

      if (hasMarkers) {
        map.fitBounds(bounds, { padding: [30, 30] });
      }
    })
    .catch(error => {
      console.error('Map data fetch failed:', error);
    });
</script>

</body>
</html>