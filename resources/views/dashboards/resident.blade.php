<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1">

<title>Resident Dashboard | ANI-CARE</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<link rel="stylesheet"
href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{

font-family:'Segoe UI',sans-serif;
background:#f3f5f9;
color:#222;

}

/*==========================
TOP HEADER
==========================*/

.top-header{

background:#198754;
color:#fff;
padding:18px 0;
box-shadow:0 3px 10px rgba(0,0,0,.12);

}

.header-wrapper{

max-width:1200px;
margin:auto;
padding:0 18px;

display:flex;
justify-content:space-between;
align-items:center;
gap:20px;
flex-wrap:wrap;

}

.brand{

font-size:28px;
font-weight:700;

}

/*==========================
NAVIGATION
==========================*/

.header-actions{

display:flex;
gap:10px;
flex-wrap:wrap;

}

.header-actions a,
.logout-btn{

padding:10px 18px;
border-radius:10px;
font-weight:600;
text-decoration:none;
transition:.3s;

}

.header-chip{

background:#fff;
color:#222;

}

.header-chip.active{

background:#157347;
color:#fff;

}

.logout-btn{

background:#ffc107;
border:none;

}

.logout-btn:hover{

background:#e0a800;

}

/*==========================
PAGE
==========================*/

.page-wrap{

max-width:1200px;
margin:auto;
padding:25px 15px 50px;

}

/*==========================
WELCOME
==========================*/

.welcome-box{

margin-bottom:25px;

}

.welcome-title{

font-size:38px;
font-weight:700;

}

.welcome-sub{

font-size:17px;
color:#666;

}

/*==========================
STATS
==========================*/

.stats-strip{

display:grid;
grid-template-columns:repeat(4,1fr);
gap:18px;

margin-bottom:25px;

}

.stat-box{

background:#fff;

border-radius:18px;

padding:22px;

box-shadow:0 8px 20px rgba(0,0,0,.08);

transition:.3s;

}

.stat-box:hover{

transform:translateY(-3px);

}

.stat-label{

font-size:15px;
color:#666;

}

.stat-number{

font-size:34px;
font-weight:700;
color:#198754;

}

.stat-foot{

font-size:14px;
color:#888;

}

/*==========================
CARDS
==========================*/

.section-card{

background:#fff;

border:none;

border-radius:18px;

padding:20px;

box-shadow:0 8px 20px rgba(0,0,0,.08);

margin-bottom:20px;

}

.section-head{

display:flex;

justify-content:space-between;

align-items:center;

margin-bottom:15px;

flex-wrap:wrap;

}

.section-head h3,
.section-head h4{

font-weight:700;
margin:0;

}

.section-note{

font-size:14px;

color:#777;

}

/*==========================
MAP
==========================*/

#map{

width:100%;

height:400px;

border-radius:15px;

overflow:hidden;

}

/*==========================
MOBILE
==========================*/

@media(max-width:991px){

.stats-strip{

grid-template-columns:repeat(2,1fr);

}

}

@media(max-width:768px){

.header-wrapper{

flex-direction:column;

align-items:flex-start;

}

.header-actions{

display:grid;

grid-template-columns:repeat(2,1fr);

width:100%;

}

.header-actions a,
.logout-btn{

text-align:center;

width:100%;

}

.brand{

font-size:24px;

}

.welcome-title{

font-size:30px;

}

.stats-strip{

grid-template-columns:repeat(2,1fr);

gap:12px;

}

.stat-box{

padding:18px;

}

.stat-number{

font-size:28px;

}

#map{

height:260px;

}

}

@media(max-width:576px){

.stats-strip{

grid-template-columns:1fr;

}

.welcome-title{

font-size:26px;

}

.welcome-sub{

font-size:15px;

}

}

</style>

</head>

<body>
  @if(session()->pull('show_login_loader'))
    @include('components.loader')
@endif

@php

$residentName = $user->fullname ?? $user->username ?? 'Resident';

@endphp

<header class="top-header">

<div class="header-wrapper">

<div class="brand">

ANI-CARE | Resident

</div>

<div class="header-actions">

<a href="{{ route('resident.marketplace') }}"
class="header-chip active">

<i class="bi bi-shop"></i>

Marketplace

</a>

<a href="{{ route('resident.orders.index') }}"
class="header-chip">

<i class="bi bi-bag"></i>

My Orders

</a>

<a href="{{ route('resident.profile') }}"
class="header-chip">

<i class="bi bi-person-circle"></i>

My Profile

</a>

<form method="POST"
action="{{ route('logout') }}">

@csrf

<button class="logout-btn">

<i class="bi bi-box-arrow-right"></i>

Logout

</button>

</form>

</div>

</div>

</header>

<div class="page-wrap">

@if(session('success'))

<div class="alert alert-success shadow-sm">

{{ session('success') }}

</div>

@endif

@if($errors->any())

<div class="alert alert-danger shadow-sm">

{{ $errors->first() }}

</div>

@endif

<div class="welcome-box">

<h1 class="welcome-title">

Welcome, {{ $residentName }}!

</h1>

<div class="welcome-sub">

View farmers, millers, marketplace listings, orders and local announcements.

</div>

</div>

<section class="stats-strip">
  <div class="stat-box">

    <div class="stat-label">

        <i class="bi bi-gear-fill text-success me-1"></i>

        Open Millers

    </div>

    <div class="stat-number">

        {{ $openMillersCount ?? 0 }}

    </div>

    <div class="stat-foot">

        Live status today

    </div>

</div>

<div class="stat-box">

    <div class="stat-label">

        <i class="bi bi-shop text-success me-1"></i>

        Marketplace Posts

    </div>

    <div class="stat-number">

        {{ $marketplaceCount ?? 0 }}

    </div>

    <div class="stat-foot">

        Active rice and palay listings

    </div>

</div>

<div class="stat-box">

    <div class="stat-label">

        <i class="bi bi-bag-check-fill text-success me-1"></i>

        My Orders

    </div>

    <div class="stat-number">

        {{ $myOrdersCount ?? 0 }}

    </div>

    <div class="stat-foot">

        Orders you've placed

    </div>

</div>

<div class="stat-box">

    <div class="stat-label">

        <i class="bi bi-megaphone-fill text-success me-1"></i>

        Announcements

    </div>

    <div class="stat-number">

        {{ isset($announcements) ? $announcements->count() : 0 }}

    </div>

    <div class="stat-foot">

        Latest updates

    </div>

</div>

</section>

<div class="row g-4">

    <!-- LEFT COLUMN -->

    <div class="col-lg-8">

        <div class="section-card">

            <div class="section-head">

                <div>

                    <h3>

                        🗺️ Allacapan Farmers & Millers Map

                    </h3>

                    <div class="section-note">

                        Click the markers to view farmer and miller information.

                    </div>

                </div>

                <div class="d-flex flex-wrap gap-2">

                    <span class="badge bg-success px-3 py-2">

                        🌾 Farmer

                    </span>

                    <span class="badge bg-primary px-3 py-2">

                        ⚙️ Miller

                    </span>

                </div>

            </div>

            <div id="map"></div>

        </div>

        <div class="section-card">

            <div class="section-head">

                <div>

                    <h3>

                        📢 Announcements

                    </h3>

                    <div class="section-note">

                        Latest updates for residents

                    </div>

                </div>

            </div>

            @if(isset($announcements) && $announcements->count())

                @foreach($announcements as $a)

                    <div class="card mb-3 border-0 shadow-sm">

                        <div class="card-body">

                            <h5 class="fw-bold text-success">

                                {{ $a->title }}

                            </h5>

                            <p class="mb-2">

                                {{ $a->message }}

                            </p>

                            <small class="text-muted">

                                <i class="bi bi-clock"></i>

                                Posted:

                                {{ $a->created_at?->format('F d, Y h:i A') }}

                            </small>

                        </div>

                    </div>

                @endforeach

            @else

                <div class="text-center py-5 text-muted">

                    <i class="bi bi-megaphone display-5"></i>

                    <p class="mt-3">

                        No announcements available.

                    </p>

                </div>

            @endif

        </div>

    </div>

    <!-- RIGHT COLUMN -->

    <div class="col-lg-4">
      <div class="section-card">

    <div class="section-head">

        <div>

            <h4>

                ⚙️ Millers Status

            </h4>

            <div class="section-note">

                Live updates

            </div>

        </div>

    </div>

    @forelse(($millers ?? []) as $m)

        <div class="d-flex justify-content-between align-items-center border-bottom py-3">

            <div>

                <div class="fw-bold">

                    {{ $m->fullname ?? $m->username }}

                </div>

                <small class="text-muted">

                    {{ '@'.$m->username }}

                </small>

            </div>

            @if($m->is_open)

                <span class="badge rounded-pill bg-success px-3 py-2">

                    OPEN

                </span>

            @else

                <span class="badge rounded-pill bg-secondary px-3 py-2">

                    CLOSED

                </span>

            @endif

        </div>

    @empty

        <div class="text-center py-4 text-muted">

            No millers available.

        </div>

    @endforelse

</div>


<div class="section-card">

    <div class="section-head">

        <div>

            <h4>

                🌾 Latest Farmer Posts

            </h4>

            <div class="section-note">

                Rice and palay listings

            </div>

        </div>

        <a href="{{ route('resident.marketplace') }}"
           class="btn btn-outline-success btn-sm">

            Browse

        </a>

    </div>

    @forelse(($products ?? []) as $p)

        <div class="card mb-3 border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex align-items-center">

                    <div class="flex-grow-1">

                        <h6 class="fw-bold mb-1">

                            {{ $p->name }}

                        </h6>

                        <small class="text-muted d-block">

                            By {{ $p->user->fullname ?? $p->user->username ?? 'Unknown Farmer' }}

                        </small>

                        <small class="text-muted d-block">

                            Stock:

                            {{ number_format($p->kilos_available ?? 0) }} kg

                        </small>

                    </div>

                    <div class="text-end">

                        <div class="fw-bold text-success fs-5">

                            ₱{{ number_format($p->price_per_kg ?? 0,2) }}

                        </div>

                        <small class="text-muted">

                            per kg

                        </small>

                    </div>

                </div>

            </div>

        </div>

    @empty

        <div class="text-center py-5 text-muted">

            <i class="bi bi-box display-5"></i>

            <p class="mt-3">

                No marketplace posts yet.

            </p>

        </div>

    @endforelse

</div>

</div>

</div>
{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const map = L.map('map').setView([18.3625, 121.6400], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {

        maxZoom: 19,

        attribution: '&copy; OpenStreetMap'

    }).addTo(map);

    const bounds = [];

    /* ==========================
       FARMERS
    ========================== */

    @if(isset($farmers))

    @foreach($farmers as $farmer)

        @if($farmer->latitude && $farmer->longitude)

        var farmerMarker = L.marker([
            {{ $farmer->latitude }},
            {{ $farmer->longitude }}
        ]).addTo(map);

        farmerMarker.bindPopup(`
            <div style="min-width:200px">
                <h6 style="margin-bottom:8px;color:#198754;">
                    🌾 Farmer
                </h6>

                <strong>{{ $farmer->fullname ?? $farmer->username }}</strong><br>

                {{ $farmer->barangay ?? '' }}
            </div>
        `);

        bounds.push([
            {{ $farmer->latitude }},
            {{ $farmer->longitude }}
        ]);

        @endif

    @endforeach

    @endif

    /* ==========================
       MILLERS
    ========================== */

    @if(isset($millers))

    @foreach($millers as $miller)

        @if($miller->latitude && $miller->longitude)

        var millerMarker = L.marker([
            {{ $miller->latitude }},
            {{ $miller->longitude }}
        ]).addTo(map);

        millerMarker.bindPopup(`
            <div style="min-width:200px">

                <h6 style="margin-bottom:8px;color:#0d6efd;">
                    ⚙️ Miller
                </h6>

                <strong>{{ $miller->fullname ?? $miller->username }}</strong><br>

                Status:
                {!! $miller->is_open
                    ? '<span style="color:green;font-weight:bold;">OPEN</span>'
                    : '<span style="color:red;font-weight:bold;">CLOSED</span>' !!}
            </div>
        `);

        bounds.push([
            {{ $miller->latitude }},
            {{ $miller->longitude }}
        ]);

        @endif

    @endforeach

    @endif

    if(bounds.length){

        map.fitBounds(bounds,{padding:[40,40]});

    }

});

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>