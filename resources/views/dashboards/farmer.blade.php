<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Farmer Dashboard | ANI-CARE</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI',sans-serif;
    background:#f4f6f9;
}

/*======================
TOPBAR
=======================*/

.topbar{
    background:#198754;
    color:#fff;
    min-height:60px;
    display:flex;
    align-items:center;
    box-shadow:0 3px 12px rgba(0,0,0,.08);
}

.topbar .brand{
    font-size:20px;
    font-weight:700;
}

.page-wrap{
    max-width:1200px;
    margin:auto;
    padding:30px 20px 60px;
}

/*======================
CARDS
=======================*/

.card-link{
    text-decoration:none;
    color:inherit;
    width:100%;
}

.dash-card{
    background:#fff;
    border-radius:16px;
    border:none;
    box-shadow:0 5px 15px rgba(0,0,0,.06);
    transition:.25s;
    padding:22px;
    height:100%;
}

.dash-card:hover{
    transform:translateY(-4px);
    box-shadow:0 12px 25px rgba(0,0,0,.10);
}

.dash-card h5{
    color:#198754;
    font-weight:700;
    margin-bottom:8px;
}

.dash-card p{
    color:#666;
    font-size:15px;
    margin-bottom:20px;
}

.dash-card .btn{
    width:100%;
    border-radius:8px;
}

/*======================
WELCOME
=======================*/

.welcome-title{
    font-weight:700;
}

.subtitle{
    color:#666;
}

/*======================
ANNOUNCEMENTS
=======================*/

.section-title{
    font-weight:700;
}

.announcement-card{
    background:#fff;
    border:none;
    border-radius:15px;
    box-shadow:0 4px 12px rgba(0,0,0,.05);
}

.announcement-card .card-body{
    padding:20px;
}

/*======================
BUTTONS
=======================*/

.btn-success{
    background:#198754;
    border:none;
}

.btn-success:hover{
    background:#157347;
}

.btn-outline-success:hover{
    color:white;
}

/*======================
RESPONSIVE
=======================*/

@media(max-width:768px){

.page-wrap{
    padding:20px 15px 40px;
}

.topbar{
    padding:10px;
}

.topbar .container-fluid{
    flex-direction:column;
    gap:10px;
}

.topbar .brand{
    font-size:18px;
}

.topbar .actions{
    width:100%;
    display:flex;
    justify-content:center;
}

.welcome-title{
    font-size:28px;
}

.dash-card{
    padding:18px;
}

.dash-card h5{
    font-size:22px;
}

.dash-card p{
    font-size:15px;
}

.btn{
    font-size:15px;
}

}

@media(max-width:576px){

.page-wrap{
    padding:15px;
}

.welcome-title{
    font-size:24px;
}

.subtitle{
    font-size:15px;
}

.topbar .brand{
    text-align:center;
}

.topbar .actions{
    flex-direction:row;
    width:100%;
}

.topbar .actions .btn{
    flex:1;
}

.dash-card{
    padding:18px;
}

.dash-card h5{
    font-size:20px;
}

}

</style>

</head>

<body>

  @if(session()->pull('show_login_loader'))
    @include('components.loader')
@endif
<!-- TOPBAR -->

<div class="topbar">

<div class="container-fluid px-3 d-flex justify-content-between align-items-center flex-wrap">

<div class="brand">
ANI-CARE | Farmer
</div>

<div class="actions d-flex gap-2">

<a href="{{ route('farmer.orders.index') }}" class="btn btn-light btn-sm">
Orders
</a>

<form method="POST" action="{{ route('logout') }}">
@csrf
<button class="btn btn-warning btn-sm">
Logout
</button>
</form>

</div>

</div>

</div>

<!-- CONTENT -->

<div class="page-wrap">

<h2 class="welcome-title">
Welcome, Farmer 👨‍🌾
</h2>

<p class="subtitle mb-4">
Access your profile, submit milling requests, manage rice products, and track updates.
</p>

<div class="row g-4">

<!-- PROFILE -->

<div class="col-12 col-sm-6 col-lg-4 d-flex">

<a href="{{ route('farmer.profile') }}" class="card-link">

<div class="dash-card">

<h5>My Farm Profile</h5>

<p>
View and update your farm information.
</p>

<button class="btn btn-success">
View Profile
</button>

</div>

</a>

</div>

<!-- REQUEST -->

<div class="col-12 col-sm-6 col-lg-4 d-flex">

<a href="{{ route('farmer.milling.create') }}" class="card-link">

<div class="dash-card">

<h5>Request Milling</h5>

<p>
Submit a milling service request.
</p>

<button class="btn btn-success">
Request Now
</button>

</div>

</a>

</div>

<!-- REQUESTS -->

<div class="col-12 col-sm-6 col-lg-4 d-flex">

<a href="{{ route('farmer.milling.index') }}" class="card-link">

<div class="dash-card">

<h5>My Requests</h5>

<p>
Track the status of submitted requests.
</p>

<button class="btn btn-success">
View Requests
</button>

</div>

</a>

</div>

<!-- POST PRODUCT -->

<div class="col-12 col-sm-6 col-lg-4 d-flex">

<a href="{{ route('farmer.products.create') }}" class="card-link">

<div class="dash-card">

<h5>Post Rice Product</h5>

<p>
Add a rice product with photo, price, and kilos.
</p>

<button class="btn btn-success">
Post Product
</button>

</div>

</a>

</div>

<!-- PRODUCTS -->

<div class="col-12 col-sm-6 col-lg-4 d-flex">

<a href="{{ route('farmer.products.index') }}" class="card-link">

<div class="dash-card">

<h5>My Rice Products</h5>

<p>
View, update, or disable your posted rice products.
</p>

<button class="btn btn-outline-success">
View Products
</button>

</div>

</a>

</div>

</div>

<!-- ANNOUNCEMENTS -->

@if(isset($announcements) && $announcements->count())

<div class="mt-5">

<div class="d-flex justify-content-between align-items-center mb-3">

<h5 class="section-title">
📢 Announcements
</h5>

<span class="text-muted small">
Latest Updates
</span>

</div>

@foreach($announcements as $a)

<div class="card announcement-card mb-3">

<div class="card-body">

<h5 class="text-success fw-bold">
{{ $a->title }}
</h5>

<p class="mb-2">
{{ $a->message }}
</p>

<small class="text-muted">
Posted:
{{ $a->created_at?->format('F d, Y h:i A') }}
</small>

</div>

</div>

@endforeach

</div>

@endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>