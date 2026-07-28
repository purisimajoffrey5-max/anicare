<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1">

<title>ANI-CARE Marketplace</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>

:root{

--green:#198754;
--green-dark:#146c43;
--green-light:#dff6ea;

--yellow:#ffc107;

--bg:#f4f6f9;

--card:#ffffff;

--text:#212529;

--muted:#6c757d;

--border:#e9ecef;

}

*{

margin:0;
padding:0;
box-sizing:border-box;

}

html{

scroll-behavior:smooth;

}

body{

background:var(--bg);

font-family:
"Segoe UI",
Tahoma,
Geneva,
Verdana,
sans-serif;

color:var(--text);

}

/**************************
TOP APP BAR
**************************/

.topbar{

position:sticky;

top:0;

z-index:999;

background:linear-gradient(
180deg,
var(--green),
var(--green-dark)
);

padding:12px 14px;

box-shadow:0 4px 18px rgba(0,0,0,.15);

}

.brand{

font-size:24px;

font-weight:800;

color:#fff;

margin-bottom:12px;

}

/**************************
SEARCH
**************************/

.search-box{

background:#fff;

border-radius:14px;

padding:10px;

display:flex;

gap:10px;

align-items:center;

}

.search-input{

border:none;

outline:none;

width:100%;

font-size:15px;

}

.search-btn{

background:var(--green);

border:none;

color:#fff;

padding:10px 18px;

border-radius:10px;

font-weight:700;

transition:.25s;

}

.search-btn:hover{

background:var(--green-dark);

}

/**************************
QUICK MENU
**************************/

.quick-menu{

display:flex;

gap:10px;

margin-top:12px;

overflow-x:auto;

padding-bottom:2px;

}

.quick-menu::-webkit-scrollbar{

display:none;

}

.menu-btn{

flex:0 0 auto;

background:rgba(255,255,255,.15);

color:#fff;

padding:10px 16px;

border-radius:30px;

text-decoration:none;

font-size:14px;

font-weight:600;

backdrop-filter:blur(8px);

transition:.25s;

white-space:nowrap;

}

.menu-btn:hover{

background:#fff;

color:var(--green);

}

.logout-btn{

background:var(--yellow);

color:#222;

}

/**************************
MAIN
**************************/

.container-app{

max-width:1300px;

margin:auto;

padding:16px;

}

/**************************
SECTION
**************************/

.section-title{

font-size:24px;

font-weight:800;

margin-bottom:3px;

}

.section-sub{

font-size:14px;

color:var(--muted);

margin-bottom:18px;

}

/**************************
CARD
**************************/

.soft-card{

background:var(--card);

border-radius:20px;

padding:18px;

box-shadow:

0 10px 25px

rgba(0,0,0,.06);

border:1px solid #eef1f5;

}

/**************************
CHIPS
**************************/

.chips{

display:flex;

gap:10px;

overflow:auto;

padding-bottom:5px;

margin-bottom:18px;

}

.chips::-webkit-scrollbar{

display:none;

}

.chip{

flex:0 0 auto;

padding:9px 16px;

border-radius:30px;

background:#fff;

border:1px solid #ddd;

font-size:14px;

font-weight:700;

cursor:pointer;

transition:.25s;

white-space:nowrap;

}

.chip.active{

background:var(--green);

color:#fff;

border-color:var(--green);

}

/**************************
STATS
**************************/

.stats-card{

background:#eaf5ff;

border-radius:18px;

padding:18px;

}

.stats-number{

font-size:34px;

font-weight:800;

color:var(--green);

}

/**************************
PRODUCT GRID
**************************/

.products{

display:grid;

grid-template-columns:

repeat(4,1fr);

gap:18px;

}

/**************************
RESPONSIVE
**************************/

@media(max-width:1200px){

.products{

grid-template-columns:

repeat(3,1fr);

}

}

@media(max-width:768px){

.products{

grid-template-columns:

repeat(2,1fr);

gap:14px;

}

.brand{

font-size:21px;

}

.section-title{

font-size:21px;

}

.container-app{

padding:12px;

}

}

@media(max-width:480px){

.products{

grid-template-columns:

repeat(2,1fr);

gap:10px;

}

.brand{

font-size:20px;

}

.search-btn{

padding:10px 12px;

}

.quick-menu{

gap:8px;

}

.menu-btn{

font-size:13px;

padding:9px 14px;

}

.section-title{

font-size:20px;

}

.soft-card{

padding:14px;

}

}

</style>

</head>

<body>
{{-- =========================
    TOP HEADER
========================= --}}

<div class="topbar">

    <div class="brand">

        🌾 ANI-CARE Marketplace

    </div>

    {{-- SEARCH BAR --}}

    <form
        method="GET"
        action="{{ route('resident.marketplace') }}"
        class="search-box">

        <i class="bi bi-search text-success fs-5"></i>

        <input
            type="text"
            name="q"
            value="{{ $q ?? '' }}"
            class="search-input"
            placeholder="Search rice, palay, farmer...">

        <button
            class="search-btn"
            type="submit">

            Search

        </button>

    </form>

    {{-- QUICK MENU --}}

    <div class="quick-menu">

        <a
            href="{{ route('resident.marketplace') }}"
            class="menu-btn">

            <i class="bi bi-shop"></i>

            Marketplace

        </a>

        <a
            href="{{ route('resident.orders.index') }}"
            class="menu-btn">

            <i class="bi bi-bag"></i>

            Orders

        </a>

        <a
            href="{{ route('resident.profile') }}"
            class="menu-btn">

            <i class="bi bi-person-circle"></i>

            Profile

        </a>

        <form
            method="POST"
            action="{{ route('logout') }}">

            @csrf

            <button
                class="menu-btn logout-btn"
                type="submit">

                <i class="bi bi-box-arrow-right"></i>

                Logout

            </button>

        </form>

    </div>

</div>

{{-- =========================
    MAIN CONTENT
========================= --}}

<div class="container-app">

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

<div class="mb-4">

    <div class="section-title">

        🛍 Marketplace

    </div>

    <div class="section-sub">

        Browse rice and palay products directly from registered farmers.

    </div>

</div>

{{-- =========================
    FILTER CHIPS
========================= --}}

<div class="chips">

    <div class="chip active">

        All Products

    </div>

    <div class="chip">

        🌾 Rice

    </div>

    <div class="chip">

        🌱 Palay

    </div>

    <div class="chip">

        ⭐ Latest

    </div>

    <div class="chip">

        💰 Cheapest

    </div>

    <div class="chip">

        🚜 Farmers

    </div>

</div>

{{-- =========================
    TOP INFO
========================= --}}

<div class="row g-3 mb-4">

    <div class="col-lg-4">

        <div class="stats-card h-100">

            <small class="text-muted">

                Open Millers

            </small>

            <div class="stats-number">

                {{ $openMillersCount ?? 0 }}

            </div>

            <div class="text-muted">

                Available today

            </div>

        </div>

    </div>

    <div class="col-lg-8">

        <div class="soft-card h-100">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <strong>

                    ⚙️ Available Millers

                </strong>

                <small class="text-muted">

                    LIVE

                </small>

            </div>

            <div class="row g-2">

                @forelse(($millers ?? []) as $m)

                <div class="col-md-6">

                    <div class="border rounded-4 p-3 d-flex justify-content-between align-items-center">

                        <div>

                            <div class="fw-bold">

                                {{ $m->fullname ?? $m->username }}

                            </div>

                            <small class="text-muted">

                                {{ '@'.$m->username }}

                            </small>

                        </div>

                        @if($m->is_open)

                        <span class="badge bg-success rounded-pill">

                            OPEN

                        </span>

                        @else

                        <span class="badge bg-secondary rounded-pill">

                            CLOSED

                        </span>

                        @endif

                    </div>

                </div>

                @empty

                <div class="col-12">

                    <div class="text-muted">

                        No millers available.

                    </div>

                </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

{{-- =========================
    PRODUCTS SECTION
========================= --}}

<div class="d-flex justify-content-between align-items-center mb-3">

    <div>

        <div class="section-title">

            🌾 Rice & Palay

        </div>

        <div class="section-sub">

            Latest farmer listings

        </div>

    </div>

</div>

<div class="products">
  @forelse(($products ?? []) as $p)

@php

$img = !empty($p->photo_path)
    ? asset('storage/'.$p->photo_path)
    : null;

$stock = (float)($p->kilos_available ?? 0);

$price = (float)($p->price_per_kg ?? 0);

@endphp

<div class="product-item">

<div class="product-card">

    {{-- ==========================
        PRODUCT IMAGE
    ========================== --}}

    <div class="position-relative">

        @if($img)

            <img
                src="{{ $img }}"
                class="product-image"
                alt="{{ $p->name }}">

        @else

            <div class="product-image d-flex align-items-center justify-content-center bg-light">

                <div class="text-center text-muted">

                    <i class="bi bi-image fs-1"></i>

                    <div class="small">

                        No Photo

                    </div>

                </div>

            </div>

        @endif

        {{-- TYPE BADGE --}}

        <span class="product-badge">

            {{ strtoupper($p->type) }}

        </span>

        {{-- FAVORITE BUTTON --}}

        <button
            class="favorite-btn"
            type="button">

            <i class="bi bi-heart"></i>

        </button>

    </div>

    {{-- ==========================
        PRODUCT DETAILS
    ========================== --}}

    <div class="product-content">

        <div class="product-name">

            {{ $p->name }}

        </div>

        <div class="seller-name">

            👨‍🌾 {{ $p->user->fullname ?? $p->user->username ?? 'Unknown Farmer' }}

        </div>

        <div class="stock-text">

            📦 {{ number_format($stock,2) }} kg available

        </div>

        <div class="price-row">

            <div>

                <div class="price">

                    ₱{{ number_format($price,2) }}

                </div>

                <small class="text-muted">

                    per kilogram

                </small>

            </div>

            @if($stock>0)

                <span class="stock-badge">

                    In Stock

                </span>

            @else

                <span class="stock-badge out">

                    Sold Out

                </span>

            @endif

        </div>

        {{-- ==========================
            ACTION BUTTONS
        ========================== --}}

        <div class="mt-3 d-grid gap-2">

            <a
                href="{{ route('resident.product.show',$p->id) }}"
                class="btn btn-outline-success rounded-3">

                <i class="bi bi-eye"></i>

                View Details

            </a>

            <a
                href="{{ route('resident.checkout.show',$p->id) }}"
                class="btn btn-success rounded-3 {{ $stock<=0 ? 'disabled' : '' }}">

                <i class="bi bi-cart-fill"></i>

                Buy Now

            </a>

        </div>

    </div>

</div>

</div>

@empty

<div class="col-12">

<div class="soft-card text-center py-5">

<i class="bi bi-box-seam display-3 text-secondary"></i>

<h4 class="mt-3">

No Products Found

</h4>

<p class="text-muted">

There are currently no rice or palay products available.

</p>

</div>

</div>

@endforelse
</div>

{{-- ===========================
PAGINATION
=========================== --}}

@if(method_exists($products,'links'))

<div class="d-flex justify-content-center mt-4 mb-5">

    {{ $products->links() }}

</div>

@endif

</div> {{-- END container-app --}}


{{-- ===========================
BOTTOM MOBILE NAVIGATION
=========================== --}}

<div class="bottom-nav d-lg-none">

    <a href="{{ route('resident.marketplace') }}"
       class="bottom-item active">

        <i class="bi bi-shop"></i>

        <span>Shop</span>

    </a>

    <a href="{{ route('resident.orders.index') }}"
       class="bottom-item">

        <i class="bi bi-bag"></i>

        <span>Orders</span>

    </a>

    <a href="{{ route('resident.profile') }}"
       class="bottom-item">

        <i class="bi bi-person-circle"></i>

        <span>Profile</span>

    </a>

</div>


<style>

/***************************************************
SHOPPE STYLE PRODUCT CARD
****************************************************/

.product-card{

background:#fff;

border-radius:18px;

overflow:hidden;

box-shadow:0 5px 15px rgba(0,0,0,.08);

transition:.25s;

height:100%;

display:flex;

flex-direction:column;

}

.product-card:hover{

transform:translateY(-4px);

box-shadow:0 12px 25px rgba(0,0,0,.15);

}

.product-image{

width:100%;

aspect-ratio:1/1;

object-fit:cover;

background:#f2f2f2;

}

.product-content{

padding:14px;

display:flex;

flex-direction:column;

flex:1;

}

.product-name{

font-weight:700;

font-size:16px;

line-height:1.3;

height:42px;

overflow:hidden;

margin-bottom:6px;

}

.seller-name{

font-size:13px;

color:#666;

margin-bottom:4px;

}

.stock-text{

font-size:13px;

color:#888;

margin-bottom:10px;

}

.price-row{

display:flex;

justify-content:space-between;

align-items:center;

margin-top:auto;

}

.price{

font-size:23px;

font-weight:800;

color:#198754;

}

.product-badge{

position:absolute;

left:10px;

top:10px;

background:#198754;

color:#fff;

padding:5px 10px;

font-size:11px;

font-weight:700;

border-radius:30px;

}

.favorite-btn{

position:absolute;

right:10px;

top:10px;

width:34px;

height:34px;

border:none;

background:#fff;

border-radius:50%;

box-shadow:0 2px 8px rgba(0,0,0,.15);

}

.favorite-btn i{

color:#198754;

}

.stock-badge{

padding:5px 10px;

background:#198754;

color:#fff;

border-radius:20px;

font-size:11px;

font-weight:700;

}

.stock-badge.out{

background:#dc3545;

}


/***************************************************
BOTTOM NAVIGATION
****************************************************/

.bottom-nav{

position:fixed;

bottom:0;

left:0;

right:0;

height:70px;

background:#fff;

display:flex;

justify-content:space-around;

align-items:center;

box-shadow:0 -5px 20px rgba(0,0,0,.10);

z-index:9999;

}

.bottom-item{

display:flex;

flex-direction:column;

align-items:center;

justify-content:center;

text-decoration:none;

font-size:12px;

font-weight:600;

color:#777;

}

.bottom-item i{

font-size:22px;

margin-bottom:3px;

}

.bottom-item.active{

color:#198754;

}


/***************************************************
RESPONSIVE
****************************************************/

@media(max-width:991px){

.container-app{

padding-bottom:90px;

}

.products{

display:grid;

grid-template-columns:repeat(2,1fr);

gap:12px;

}

}

@media(max-width:575px){

.products{

grid-template-columns:repeat(2,1fr);

gap:10px;

}

.product-name{

font-size:14px;

height:38px;

}

.price{

font-size:19px;

}

.product-content{

padding:10px;

}

.btn{

font-size:13px;

padding:9px;

}

}

@media(min-width:992px){

.bottom-nav{

display:none;

}

.products{

grid-template-columns:repeat(4,1fr);

}

}

</style>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>