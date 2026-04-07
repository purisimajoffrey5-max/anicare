<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Miller Dashboard | ANI-CARE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    *{
      margin:0;
      padding:0;
      box-sizing:border-box;
    }

    :root{
      --primary:#198754;
      --primary-dark:#157347;
      --bg:#f5f5f5;
      --card:#ffffff;
      --text:#222;
      --muted:#6b7280;
      --border:#e5e7eb;
      --shadow:0 4px 14px rgba(15,23,42,.06);
      --radius:16px;
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
      background:var(--primary);
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

    .status-btn{
      border:none;
      background:#ffffff;
      color:var(--primary);
      padding:7px 12px;
      border-radius:4px;
      font-size:13px;
      font-weight:700;
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
      padding:14px 14px 40px;
    }

    .hero-box{
      background:var(--card);
      border:1px solid var(--border);
      border-radius:18px;
      padding:22px 20px;
      box-shadow:var(--shadow);
      margin-bottom:14px;
    }

    .hero-title{
      font-size:28px;
      font-weight:800;
      color:#111827;
      margin-bottom:6px;
    }

    .hero-sub{
      color:var(--muted);
      font-size:14px;
      margin-bottom:16px;
    }

    .hero-badges{
      display:flex;
      gap:10px;
      flex-wrap:wrap;
    }

    .hero-badge{
      background:#eefaf3;
      color:var(--primary);
      border:1px solid #cfead8;
      border-radius:999px;
      padding:7px 12px;
      font-size:12px;
      font-weight:700;
    }

    .stats-strip{
      display:grid;
      grid-template-columns:repeat(4, 1fr);
      gap:12px;
      margin-bottom:14px;
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
      font-size:22px;
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
      grid-template-columns:1fr 1fr;
      gap:14px;
      margin-bottom:14px;
    }

    .section-card{
      background:var(--card);
      border:1px solid var(--border);
      border-radius:16px;
      padding:16px;
      box-shadow:var(--shadow);
      height:100%;
    }

    .section-head{
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:10px;
      margin-bottom:12px;
      flex-wrap:wrap;
    }

    .section-head h3,
    .section-head h4{
      margin:0;
      font-size:18px;
      font-weight:800;
      color:#111827;
    }

    .section-note{
      color:var(--muted);
      font-size:12px;
    }

    .cards-grid{
      display:grid;
      grid-template-columns:repeat(3, 1fr);
      gap:14px;
      margin-bottom:14px;
    }

    .card-link{
      display:block;
      height:100%;
    }

    .dash-card{
      background:var(--card);
      border:1px solid var(--border);
      border-radius:16px;
      padding:18px;
      box-shadow:var(--shadow);
      transition:.18s ease;
      height:100%;
    }

    .dash-card:hover{
      transform:translateY(-4px);
      box-shadow:0 12px 24px rgba(15,23,42,.08);
    }

    .card-icon{
      width:52px;
      height:52px;
      border-radius:14px;
      display:flex;
      align-items:center;
      justify-content:center;
      font-size:24px;
      margin-bottom:14px;
      background:#eefaf3;
      color:var(--primary);
    }

    .dash-card h4{
      font-size:20px;
      font-weight:800;
      color:#198754;
      margin-bottom:6px;
    }

    .dash-card p{
      color:var(--muted);
      font-size:14px;
      margin-bottom:14px;
      min-height:42px;
    }

    .card-btn{
      display:inline-block;
      background:var(--primary);
      color:#fff;
      padding:9px 14px;
      border-radius:8px;
      font-size:13px;
      font-weight:700;
    }

    .card-btn-outline{
      display:inline-block;
      background:#fff;
      color:var(--primary);
      border:1px solid var(--primary);
      padding:9px 14px;
      border-radius:8px;
      font-size:13px;
      font-weight:700;
    }

    .status-pill{
      display:inline-flex;
      align-items:center;
      gap:6px;
      border-radius:999px;
      padding:6px 12px;
      font-size:12px;
      font-weight:800;
    }

    .status-open{
      background:#dcfce7;
      color:#166534;
    }

    .status-closed{
      background:#e5e7eb;
      color:#4b5563;
    }

    .announcement{
      padding:14px 16px;
      border-radius:12px;
      border:1px solid #dbeafe;
      background:#f8fbff;
      margin-bottom:10px;
    }

    .announcement:last-child{
      margin-bottom:0;
    }

    .announcement .title{
      font-weight:800;
      color:#111827;
      margin-bottom:5px;
      font-size:14px;
    }

    .announcement .meta{
      color:var(--muted);
      font-size:11px;
      margin-top:6px;
    }

    .empty-state{
      text-align:center;
      color:var(--muted);
      padding:20px 0;
      font-size:13px;
    }

    @media (max-width: 1100px){
      .stats-strip{
        grid-template-columns:repeat(2, 1fr);
      }

      .cards-grid{
        grid-template-columns:1fr 1fr;
      }

      .dashboard-grid{
        grid-template-columns:1fr;
      }
    }

    @media (max-width: 768px){
      .stats-strip{
        grid-template-columns:1fr;
      }

      .cards-grid{
        grid-template-columns:1fr;
      }

      .hero-title{
        font-size:24px;
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

<header class="top-header">
  <div class="top-header-inner">
    <div class="brand">ANI-CARE | Miller</div>

    <div class="header-actions">
      <a href="{{ route('miller.profile') }}" class="header-chip">My Profile</a>
      <form method="POST" action="{{ route('miller.toggleOpen') }}" class="m-0">
        @csrf
        <button class="status-btn" type="submit">
          {{ $user->is_open ? 'Set CLOSED' : 'Set OPEN' }}
        </button>
      </form>

      <form method="POST" action="{{ route('logout') }}" class="m-0">
        @csrf
        <button class="logout-btn" type="submit">Logout</button>
      </form>
    </div>
  </div>
</header>

<div class="page-wrap">

  @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-3">{{ session('success') }}</div>
  @endif

  <section class="hero-box">
    <div class="hero-title">Welcome, {{ $user->fullname ?? $user->username ?? 'Miller' }} 👋</div>
    <div class="hero-sub">Access requests, manage schedules, review reports, and monitor announcements.</div>

    <div class="hero-badges">
      <span class="hero-badge">Milling Requests</span>
      <span class="hero-badge">Schedule Management</span>
      <span class="hero-badge">Reports</span>
      <span class="hero-badge">System Updates</span>
    </div>
  </section>

  <section class="stats-strip">
    <div class="stat-box">
      <div class="stat-label">Miller Status</div>
      <div class="stat-number">{{ $user->is_open ? 'OPEN' : 'CLOSED' }}</div>
      <div class="stat-foot">Current service availability</div>
    </div>

    <div class="stat-box">
      <div class="stat-label">Requests Module</div>
      <div class="stat-number">Active</div>
      <div class="stat-foot">Manage incoming farmer requests</div>
    </div>

    <div class="stat-box">
      <div class="stat-label">Schedule Module</div>
      <div class="stat-number">Ready</div>
      <div class="stat-foot">Set approved milling schedules</div>
    </div>

    <div class="stat-box">
      <div class="stat-label">Announcements</div>
      <div class="stat-number">{{ isset($announcements) ? $announcements->count() : 0 }}</div>
      <div class="stat-foot">Latest updates from admin</div>
    </div>
  </section>

  <section class="cards-grid">
    <a class="card-link" href="{{ route('miller.requests') }}">
      <div class="dash-card">
        <div class="card-icon">📋</div>
        <h4>Milling Requests</h4>
        <p>View and manage farmer milling requests and update their status.</p>
        <span class="card-btn">View Requests</span>
      </div>
    </a>

    <a class="card-link" href="{{ route('miller.schedule') }}">
      <div class="dash-card">
        <div class="card-icon">🗓️</div>
        <h4>Schedule Milling</h4>
        <p>Set schedules for approved milling services and track upcoming work.</p>
        <span class="card-btn">Manage Schedule</span>
      </div>
    </a>

    <a class="card-link" href="{{ route('miller.reports') }}">
      <div class="dash-card">
        <div class="card-icon">📊</div>
        <h4>Milling Reports</h4>
        <p>Review completed milling records and monitor finished transactions.</p>
        <span class="card-btn-outline">View Reports</span>
      </div>
    </a>
  </section>

  <section class="dashboard-grid">
    <div class="section-card">
      <div class="section-head">
        <div>
          <h4>Service Availability</h4>
          <div class="section-note">Current miller account status</div>
        </div>
      </div>

      @if($user->is_open)
        <span class="status-pill status-open">● OPEN</span>
      @else
        <span class="status-pill status-closed">● CLOSED</span>
      @endif

      <div class="mt-3 text-muted small">
        Use the button in the header to change your service availability anytime.
      </div>
    </div>

    <div class="section-card">
      <div class="section-head">
        <div>
          <h4>Announcements</h4>
          <div class="section-note">Latest updates from admin</div>
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

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>