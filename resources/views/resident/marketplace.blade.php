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
MILLERS TOGGLE / COLLAPSE
**************************/

.miller-toggle-btn{

border:none;
background:var(--green);
color:#fff;
min-height:46px;
padding:11px 16px;
border-radius:14px;
font-weight:700;
display:inline-flex;
align-items:center;
justify-content:center;
gap:8px;
box-shadow:0 6px 16px rgba(25,135,84,.18);
transition:.2s ease;

}

.miller-toggle-btn:hover{
background:var(--green-dark);
}

.miller-toggle-arrow{
transition:transform .2s ease;
}

.miller-toggle-btn[aria-expanded="true"] .miller-toggle-arrow{
transform:rotate(180deg);
}

.millers-panel{
padding:16px;
}

.miller-grid{
display:grid;
grid-template-columns:repeat(2,minmax(0,1fr));
gap:10px;
}

.miller-card{
border:1px solid var(--border);
border-radius:14px;
padding:13px 14px;
display:flex;
justify-content:space-between;
align-items:center;
gap:10px;
min-width:0;
background:#fff;
}

.miller-info{
min-width:0;
}

.miller-name{
font-weight:700;
font-size:14px;
white-space:nowrap;
overflow:hidden;
text-overflow:ellipsis;
}

.live-pill{
font-size:11px;
font-weight:700;
color:var(--muted);
display:inline-flex;
align-items:center;
gap:6px;
}

.live-dot{
width:7px;
height:7px;
border-radius:50%;
background:var(--green);
display:inline-block;
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

grid-template-columns:repeat(2,minmax(0,1fr));

gap:12px;

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

grid-template-columns:1fr;

gap:14px;

}

.miller-toggle-btn{
width:100%;
}

.miller-grid{
grid-template-columns:1fr;
}

.miller-card{
padding:12px;
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
    TOP INFO / MILLERS
========================= --}}

<div class="mb-4">

    <div class="stats-card">

        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">

            <div>

                <small class="text-muted d-block mb-1">Open Millers</small>

                <div class="stats-number">
                    {{ $openMillersCount ?? 0 }}
                </div>

                <div class="text-muted">Available today</div>

            </div>

            <button
                id="millerToggleBtn"
                class="miller-toggle-btn"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#availableMillersCollapse"
                aria-expanded="false"
                aria-controls="availableMillersCollapse">

                <i class="bi bi-gear-fill"></i>
                <span class="miller-toggle-text">View Available Millers</span>
                <i class="bi bi-chevron-down miller-toggle-arrow"></i>

            </button>

        </div>

    </div>

    {{-- Hidden by default. It only appears after pressing the button above. --}}
    <div class="collapse" id="availableMillersCollapse">

        <div class="soft-card millers-panel mt-3">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <strong>⚙️ Available Millers</strong>

                <span class="live-pill">
                    <span class="live-dot"></span>
                    LIVE
                </span>

            </div>

            <div class="miller-grid">

                @forelse(($millers ?? []) as $m)

                    <div class="miller-card">

                        <div class="miller-info">

                            <div class="miller-name">
                                {{ $m->fullname ?? $m->username }}
                            </div>

                            <small class="text-muted">
                                {{ '@'.$m->username }}
                            </small>

                        </div>

                        @if($m->is_open)

                            <span class="badge bg-success rounded-pill">OPEN</span>

                        @else

                            <span class="badge bg-secondary rounded-pill">CLOSED</span>

                        @endif

                    </div>

                @empty

                    <div class="text-muted py-2">
                        No millers available.
                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

{{-- =========================
    PRODUCTS SECTION
========================= --}}

<div class="market-products-heading">

    <div>
        <div class="market-products-title">
            🌾 Rice & Palay
        </div>

        <div class="market-products-sub">
            Latest farmer listings
        </div>
    </div>

    <span class="market-products-count">
        {{ isset($products) && method_exists($products, 'total')
            ? $products->total()
            : (isset($products) ? $products->count() : 0) }}
    </span>

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

        <div class="product-actions">

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


<style>

/***************************************************
SHOPPE STYLE PRODUCT CARD
****************************************************/

.product-item{
min-width:0;
width:100%;
}

.product-card{

background:#fff;

border-radius:18px;

overflow:hidden;

box-shadow:0 5px 15px rgba(0,0,0,.08);

transition:.25s;

height:100%;
width:100%;
min-width:0;

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

grid-template-columns:1fr;

gap:14px;

}

.product-card{

border-radius:16px;

}

.product-image{

aspect-ratio:auto;
height:190px;

}

.product-name{

font-size:15px;
height:auto;
min-height:0;
margin-bottom:7px;

}

.seller-name,
.stock-text{

font-size:12px;

}

.price{

font-size:20px;

}

.product-content{

padding:12px;

}

.product-content .btn{

font-size:13px;
padding:9px 10px;

}

.section-title{
font-size:19px;
}

.section-sub{
font-size:13px;
margin-bottom:12px;
}

.stats-card{
padding:15px;
}

.stats-number{
font-size:30px;
}

.miller-grid{
grid-template-columns:1fr;
}

}


@media(max-width:380px){

.product-image{
height:170px;
}

.container-app{
padding-left:10px;
padding-right:10px;
}

.search-box{
padding:8px;
}

.search-btn{
padding:9px 11px;
font-size:13px;
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


/* ==========================================================
   FINAL MARKETPLACE MOBILE POLISH
   - Compact Rice & Palay heading
   - Compact horizontal product cards on phones
   - Two-column cards on larger phones/tablets
========================================================== */

.products-section-header{
    display:flex;
    align-items:center;
    gap:11px;
    margin:8px 0 14px;
    padding:12px 13px;
    border:1px solid #e4ece8;
    border-radius:16px;
    background:#fff;
    box-shadow:0 4px 14px rgba(15,23,42,.04);
}

.products-section-icon{
    width:40px;
    height:40px;
    flex:0 0 40px;
    border-radius:12px;
    display:grid;
    place-items:center;
    background:var(--green-light);
    color:var(--green);
    font-size:18px;
}

.products-section-copy{
    min-width:0;
    flex:1;
}

.products-section-title{
    font-size:18px;
    line-height:1.15;
    font-weight:800;
    color:#1f2933;
}

.products-section-sub{
    margin-top:2px;
    font-size:11.5px;
    color:var(--muted);
}

.products-section-count{
    flex:0 0 auto;
    min-width:30px;
    height:30px;
    padding:0 8px;
    border-radius:999px;
    background:#eef8f2;
    color:var(--green);
    display:inline-flex;
    align-items:center;
    justify-content:center;
    font-size:11px;
    font-weight:800;
}

.product-actions{
    margin-top:12px;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:8px;
}

.product-actions .btn{
    min-width:0;
    font-size:12px;
    font-weight:700;
    padding:8px 8px;
    white-space:nowrap;
}

/* Tablet / small desktop */
@media (max-width: 991px){
    .products{
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:12px;
    }

    .product-image{
        aspect-ratio:4/3;
    }
}

/* PHONE LAYOUT */
@media (max-width:575px){

    .container-app{
        padding:12px 10px 92px;
    }

    .products-section-header{
        margin:2px 0 12px;
        padding:11px;
        border-radius:14px;
    }

    .products-section-icon{
        width:36px;
        height:36px;
        flex-basis:36px;
        font-size:16px;
        border-radius:10px;
    }

    .products-section-title{
        font-size:16px;
    }

    .products-section-sub{
        font-size:10.5px;
    }

    .products{
        grid-template-columns:1fr;
        gap:10px;
    }

    .product-item{
        width:100%;
        min-width:0;
    }

    .product-card{
        display:grid !important;
        grid-template-columns:118px minmax(0,1fr);
        grid-template-rows:auto;
        align-items:stretch;
        min-height:166px;
        height:auto;
        border-radius:15px;
        overflow:hidden;
        box-shadow:0 4px 14px rgba(15,23,42,.07);
        border:1px solid #e7ece9;
    }

    .product-card > .position-relative{
        min-width:0;
        min-height:100%;
        height:100%;
        overflow:hidden;
        background:#f1f4f2;
    }

    .product-image{
        display:block;
        width:100%;
        height:100% !important;
        min-height:166px;
        aspect-ratio:auto !important;
        object-fit:cover;
    }

    .product-content{
        min-width:0;
        padding:11px 10px 10px;
        display:flex;
        flex-direction:column;
        justify-content:flex-start;
    }

    .product-name{
        height:auto;
        min-height:0;
        margin:0 0 5px;
        font-size:14px;
        line-height:1.25;
        font-weight:800;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }

    .seller-name,
    .stock-text{
        max-width:100%;
        margin-bottom:3px;
        font-size:10.5px;
        line-height:1.3;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }

    .stock-text{
        margin-bottom:7px;
    }

    .price-row{
        margin-top:0;
        gap:6px;
        align-items:flex-end;
    }

    .price{
        font-size:18px;
        line-height:1;
    }

    .price-row small{
        font-size:9.5px;
    }

    .stock-badge{
        padding:4px 7px;
        font-size:9px;
        white-space:nowrap;
    }

    .product-badge{
        left:7px;
        top:7px;
        padding:4px 7px;
        font-size:9px;
    }

    .favorite-btn{
        right:7px;
        top:7px;
        width:30px;
        height:30px;
        display:grid;
        place-items:center;
        padding:0;
    }

    .product-actions{
        margin-top:auto;
        padding-top:9px;
        grid-template-columns:1fr;
        gap:6px;
    }

    .product-actions .btn{
        min-height:33px;
        padding:6px 7px;
        border-radius:9px !important;
        font-size:10.5px;
        line-height:1.1;
    }

    .product-actions .btn i{
        font-size:10px;
    }
}

/* VERY SMALL PHONES */
@media (max-width:380px){

    .product-card{
        grid-template-columns:104px minmax(0,1fr);
        min-height:158px;
    }

    .product-image{
        min-height:158px;
    }

    .product-content{
        padding:9px 8px 8px;
    }

    .product-name{
        font-size:13px;
    }

    .seller-name,
    .stock-text{
        font-size:9.5px;
    }

    .price{
        font-size:16px;
    }

    .stock-badge{
        padding:3px 6px;
        font-size:8px;
    }

    .product-actions .btn{
        font-size:9.5px;
        min-height:31px;
    }
}

/* Phones wide enough for a comfortable 2-column product grid */
@media (min-width:576px) and (max-width:767px){
    .products{
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:12px;
    }

    .product-image{
        height:auto !important;
        aspect-ratio:1/1 !important;
    }

    .product-actions{
        grid-template-columns:1fr;
    }
}


/* ==========================================================
   FINAL MOBILE PRODUCT LAYOUT
   Goal: 2-column Shopee-style cards like the reference image.
========================================================== */

.market-products-heading{
    display:flex;
    align-items:flex-end;
    justify-content:space-between;
    gap:12px;
    margin:8px 2px 14px;
}

.market-products-title{
    font-size:20px;
    line-height:1.15;
    font-weight:800;
    color:#1f2933;
}

.market-products-sub{
    margin-top:3px;
    font-size:12px;
    color:var(--muted);
}

.market-products-count{
    flex:0 0 auto;
    min-width:30px;
    height:30px;
    padding:0 8px;
    border-radius:999px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    background:#e8f6ee;
    color:var(--green);
    font-size:11px;
    font-weight:800;
}

/* Default product cards */
.products{
    align-items:stretch;
}

.product-item{
    width:100%;
    min-width:0;
}

.product-card{
    width:100%;
    min-width:0;
    height:100%;
    display:flex !important;
    flex-direction:column !important;
    border:1px solid #e7ece9;
    border-radius:16px;
    overflow:hidden;
    background:#fff;
    box-shadow:0 5px 14px rgba(15,23,42,.07);
}

.product-card > .position-relative{
    width:100%;
    height:auto;
    min-height:0;
    overflow:hidden;
    background:#f1f4f2;
}

.product-image{
    display:block;
    width:100%;
    height:auto !important;
    aspect-ratio:1 / 1 !important;
    object-fit:cover;
}

.product-content{
    min-width:0;
    padding:11px;
    display:flex;
    flex-direction:column;
    flex:1;
}

.product-name{
    height:auto;
    min-height:34px;
    margin:0 0 7px;
    font-size:14px;
    line-height:1.25;
    font-weight:800;
    color:#20252b;
    display:-webkit-box;
    -webkit-box-orient:vertical;
    -webkit-line-clamp:2;
    overflow:hidden;
}

.seller-name,
.stock-text{
    font-size:10.5px;
    line-height:1.35;
    color:#6f767d;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.seller-name{
    margin-bottom:4px;
}

.stock-text{
    margin-bottom:9px;
}

.price-row{
    margin-top:auto;
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
    gap:6px;
}

.price{
    font-size:20px;
    line-height:1;
    font-weight:800;
    color:var(--green);
}

.price-row small{
    font-size:9.5px;
}

.stock-badge{
    flex:0 0 auto;
    padding:4px 7px;
    font-size:8.5px;
    line-height:1;
    border-radius:999px;
    white-space:nowrap;
}

.product-badge{
    left:8px;
    top:8px;
    padding:4px 8px;
    font-size:9px;
    line-height:1.15;
}

.favorite-btn{
    right:8px;
    top:8px;
    width:30px;
    height:30px;
    padding:0;
    display:grid;
    place-items:center;
}

.product-actions{
    margin-top:10px;
    padding-top:0;
    display:grid;
    grid-template-columns:1fr 1fr !important;
    gap:7px;
}

.product-actions .btn{
    width:100%;
    min-width:0;
    min-height:34px;
    padding:7px 6px;
    border-radius:8px !important;
    font-size:10.5px;
    line-height:1.15;
    font-weight:700;
    white-space:nowrap;
}

.product-actions .btn i{
    font-size:10px;
}

/* MOBILE: keep TWO products in one row, like the user's reference */
@media (max-width:575.98px){

    .container-app{
        padding:12px 9px 94px;
    }

    .market-products-heading{
        margin:7px 1px 12px;
    }

    .market-products-title{
        font-size:18px;
    }

    .market-products-sub{
        font-size:11px;
    }

    .products{
        display:grid !important;
        grid-template-columns:repeat(2,minmax(0,1fr)) !important;
        gap:9px !important;
    }

    .product-card{
        display:flex !important;
        flex-direction:column !important;
        min-height:0 !important;
        border-radius:14px !important;
    }

    .product-card > .position-relative{
        width:100% !important;
        height:auto !important;
        min-height:0 !important;
    }

    .product-image{
        width:100% !important;
        height:auto !important;
        min-height:0 !important;
        aspect-ratio:1 / 1 !important;
        object-fit:cover !important;
    }

    .product-content{
        padding:9px 8px 8px !important;
    }

    .product-name{
        min-height:32px !important;
        margin-bottom:6px !important;
        font-size:12.5px !important;
        line-height:1.25 !important;
    }

    .seller-name,
    .stock-text{
        font-size:9.5px !important;
    }

    .stock-text{
        margin-bottom:7px !important;
    }

    .price{
        font-size:17px !important;
    }

    .price-row small{
        font-size:8.5px !important;
    }

    .stock-badge{
        padding:4px 6px !important;
        font-size:7.8px !important;
    }

    .product-badge{
        left:6px !important;
        top:6px !important;
        padding:4px 6px !important;
        font-size:8px !important;
    }

    .favorite-btn{
        right:6px !important;
        top:6px !important;
        width:28px !important;
        height:28px !important;
    }

    .favorite-btn i{
        font-size:13px;
    }

    .product-actions{
        margin-top:8px !important;
        grid-template-columns:1fr 1fr !important;
        gap:5px !important;
    }

    .product-actions .btn{
        min-height:31px !important;
        padding:6px 4px !important;
        font-size:9px !important;
    }

    .product-actions .btn i{
        font-size:8.5px !important;
    }
}

/* Very small phones: still 2 columns, just tighter */
@media (max-width:380px){

    .products{
        gap:7px !important;
    }

    .product-content{
        padding:8px 7px 7px !important;
    }

    .product-name{
        min-height:29px !important;
        font-size:11.5px !important;
    }

    .seller-name,
    .stock-text{
        font-size:8.7px !important;
    }

    .price{
        font-size:15.5px !important;
    }

    .stock-badge{
        font-size:7px !important;
        padding:3px 5px !important;
    }

    .product-actions .btn{
        font-size:8.2px !important;
        min-height:29px !important;
    }
}

/* Tablet */
@media (min-width:576px) and (max-width:991.98px){
    .products{
        grid-template-columns:repeat(2,minmax(0,1fr)) !important;
        gap:12px !important;
    }

    .product-image{
        aspect-ratio:1 / 1 !important;
    }
}

/* Desktop */
@media (min-width:992px){
    .products{
        grid-template-columns:repeat(4,minmax(0,1fr)) !important;
        gap:18px !important;
    }
}


/* ==========================================================
   FINAL UI FIX - COMPACT HEADER + NO BOTTOM NAV
   + BIGGER PRODUCT TEXT
========================================================== */

/* ----------------------------------------------------------
   COMPACT GREEN HEADER
---------------------------------------------------------- */

.topbar{
    position:sticky;
    top:0;
    z-index:999;
    padding:8px 10px 9px !important;
    background:linear-gradient(180deg,var(--green),var(--green-dark));
    box-shadow:0 3px 12px rgba(0,0,0,.12);
}

.brand{
    margin-bottom:7px !important;
    font-size:19px !important;
    line-height:1.2;
    font-weight:800;
}

.search-box{
    min-height:46px;
    padding:6px 7px !important;
    gap:7px !important;
    border-radius:12px !important;
}

.search-box > i{
    font-size:18px !important;
    flex:0 0 auto;
}

.search-input{
    min-width:0;
    font-size:13px !important;
    line-height:1.2;
}

.search-btn{
    min-height:36px;
    padding:7px 12px !important;
    border-radius:9px !important;
    font-size:12px !important;
}

.quick-menu{
    margin-top:7px !important;
    gap:6px !important;
    padding-bottom:0 !important;
}

.menu-btn{
    min-height:35px;
    padding:7px 11px !important;
    border-radius:20px !important;
    font-size:11.5px !important;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:4px;
}

.logout-btn{
    border:none;
}

/* ----------------------------------------------------------
   REMOVE ALL BOTTOM-NAV SPACING / STYLING
---------------------------------------------------------- */

.bottom-nav{
    display:none !important;
}

.container-app{
    padding-bottom:34px !important;
}

/* ----------------------------------------------------------
   PRODUCTS - KEEP 2 COLUMNS ON MOBILE
   BUT MAKE TEXT EASIER TO READ
---------------------------------------------------------- */

@media (max-width:575.98px){

    .container-app{
        padding:11px 9px 34px !important;
    }

    .products{
        display:grid !important;
        grid-template-columns:repeat(2,minmax(0,1fr)) !important;
        gap:9px !important;
    }

    .product-card{
        border-radius:14px !important;
    }

    .product-content{
        padding:11px 9px 10px !important;
    }

    .product-name{
        min-height:38px !important;
        margin-bottom:8px !important;
        font-size:14px !important;
        line-height:1.28 !important;
        font-weight:800 !important;
    }

    .seller-name{
        margin-bottom:5px !important;
        font-size:10.8px !important;
        line-height:1.35 !important;
        color:#5f666d !important;
    }

    .stock-text{
        margin-bottom:9px !important;
        font-size:10.6px !important;
        line-height:1.35 !important;
        color:#71787f !important;
    }

    .price{
        font-size:20px !important;
        line-height:1.05 !important;
        font-weight:800 !important;
    }

    .price-row small{
        font-size:9.7px !important;
    }

    .stock-badge{
        padding:5px 7px !important;
        font-size:8.7px !important;
    }

    .product-badge{
        left:7px !important;
        top:7px !important;
        padding:4px 7px !important;
        font-size:8.5px !important;
    }

    .favorite-btn{
        right:7px !important;
        top:7px !important;
        width:30px !important;
        height:30px !important;
    }

    .favorite-btn i{
        font-size:14px !important;
    }

    .product-actions{
        margin-top:10px !important;
        grid-template-columns:1fr 1fr !important;
        gap:6px !important;
    }

    .product-actions .btn{
        min-height:34px !important;
        padding:7px 4px !important;
        font-size:9.8px !important;
        font-weight:800 !important;
    }

    .product-actions .btn i{
        font-size:9px !important;
    }

    .market-products-title{
        font-size:19px !important;
    }

    .market-products-sub{
        font-size:11.5px !important;
    }
}

/* Very small phones */
@media (max-width:380px){

    .topbar{
        padding:7px 8px 8px !important;
    }

    .brand{
        font-size:17px !important;
    }

    .search-box{
        min-height:43px;
    }

    .search-input{
        font-size:12px !important;
    }

    .search-btn{
        min-height:34px;
        padding:6px 10px !important;
        font-size:11px !important;
    }

    .menu-btn{
        padding:6px 9px !important;
        font-size:10.5px !important;
    }

    .products{
        gap:7px !important;
    }

    .product-content{
        padding:10px 8px 9px !important;
    }

    .product-name{
        min-height:35px !important;
        font-size:13px !important;
    }

    .seller-name,
    .stock-text{
        font-size:9.8px !important;
    }

    .price{
        font-size:18px !important;
    }

    .product-actions .btn{
        font-size:8.8px !important;
    }
}

/* Desktop / tablet keeps normal readable sizes */
@media (min-width:576px){

    .product-name{
        font-size:16px;
    }

    .seller-name,
    .stock-text{
        font-size:13px;
    }

    .price{
        font-size:23px;
    }
}

</style>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const millerCollapse = document.getElementById('availableMillersCollapse');
    const millerButton = document.getElementById('millerToggleBtn');
    const millerText = millerButton?.querySelector('.miller-toggle-text');

    if (!millerCollapse || !millerButton || !millerText) return;

    millerCollapse.addEventListener('show.bs.collapse', function () {
        millerText.textContent = 'Hide Available Millers';
    });

    millerCollapse.addEventListener('hide.bs.collapse', function () {
        millerText.textContent = 'View Available Millers';
    });
});
</script>

</body>

</html>