<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Marketplace | Resident</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body{
      background:#f5f7fb;
      font-family:"Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .topbar{
      background:#198754;
    }

    .wrap{
      max-width:1100px;
      margin:0 auto;
      padding:16px 14px 70px;
    }

    .soft{
      border:1px solid rgba(0,0,0,.06);
      border-radius:18px;
      background:#fff;
      box-shadow:0 8px 20px rgba(16,24,40,.05);
    }

    .pill{
      border-radius:999px;
      padding:6px 10px;
      font-size:12px;
      font-weight:700;
    }

    .product-img{
      width:100%;
      height:180px;
      object-fit:cover;
      border-radius:14px;
      background:#e9ecef;
    }

    .no-photo{
      width:100%;
      height:180px;
      border-radius:14px;
      background:#e9ecef;
      display:flex;
      align-items:center;
      justify-content:center;
      color:#6c757d;
      font-weight:600;
    }

    .product-card{
      border:1px solid rgba(0,0,0,.06);
      border-radius:18px;
      background:#fff;
      padding:14px;
      height:100%;
      box-shadow:0 8px 20px rgba(16,24,40,.04);
    }

    .product-title{
      font-weight:700;
      font-size:18px;
      margin-bottom:4px;
      color:#1f2937;
    }

    .product-meta{
      font-size:13px;
      color:#6b7280;
      margin-bottom:4px;
    }

    .product-price{
      font-size:24px;
      font-weight:800;
      color:#198754;
      margin:8px 0 2px;
    }

    .stock-note{
      font-size:13px;
      color:#6b7280;
      margin-bottom:10px;
    }

    .buy-btn{
      width:100%;
      border:none;
      border-radius:12px;
      padding:12px;
      font-size:14px;
      font-weight:700;
      background:#198754;
      color:#fff;
      text-align:center;
      display:inline-flex;
      align-items:center;
      justify-content:center;
    }

    .buy-btn:hover{
      background:#157347;
      color:#fff;
    }

    .buy-btn.disabled{
      pointer-events:none;
      background:#cbd5e1;
      color:#6b7280;
    }

    .section-title{
      font-weight:800;
      margin:0;
    }

    .small-note{
      color:#6b7280;
      font-size:13px;
    }

    @media (max-width: 768px){
      .wrap{
        padding:14px 10px 50px;
      }
    }
  </style>
</head>
<body>

<nav class="navbar navbar-dark topbar">
  <div class="container-fluid px-3">
    <span class="navbar-brand fw-bold m-0">ANI-CARE | Resident</span>

    <div class="d-flex gap-2 flex-wrap">
      <a href="{{ route('resident.marketplace') }}" class="btn btn-outline-light btn-sm">Marketplace</a>
      <a href="{{ route('resident.orders.index') }}" class="btn btn-light btn-sm">My Orders</a>
      <a href="{{ route('resident.profile') }}" class="btn btn-outline-light btn-sm">My Profile</a>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-warning btn-sm">Logout</button>
      </form>
    </div>
  </div>
</nav>

<div class="wrap">

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <h3 class="fw-bold mb-0">Marketplace</h3>
  <div class="text-muted mb-3">Browse rice/palay posts and see miller availability.</div>

  {{-- Search + Open Millers --}}
  <div class="row g-3 mb-3">
    <div class="col-md-7">
      <div class="soft p-3">
        <form method="GET" action="{{ route('resident.marketplace') }}" class="row g-2">
          <div class="col-8">
            <input
              class="form-control"
              name="q"
              value="{{ $q ?? '' }}"
              placeholder="Search variety / farmer (e.g., IR64, Juan)">
          </div>
          <div class="col-4 d-grid">
            <button class="btn btn-success">Search</button>
          </div>
        </form>
      </div>
    </div>

    <div class="col-md-5">
      <div class="soft p-3" style="background:#e8f3ff;">
        <div class="text-muted">Open Millers</div>
        <div class="fs-2 fw-bold">{{ $openMillersCount ?? 0 }}</div>
        <div class="text-muted small">Live status today</div>
      </div>
    </div>
  </div>

  {{-- Millers --}}
  <div class="soft p-3 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div class="fw-bold">Millers</div>
      <div class="text-muted small">OPEN / CLOSED</div>
    </div>

    <div class="row g-2">
      @forelse(($millers ?? []) as $m)
        <div class="col-md-4">
          <div class="border rounded-3 p-2 d-flex justify-content-between align-items-center">
            <div>
              <div class="fw-semibold">{{ $m->fullname ?? $m->username }}</div>
              <div class="text-muted small">{{ '@'.$m->username }}</div>
            </div>

            @if($m->is_open)
              <span class="pill bg-success text-white">OPEN</span>
            @else
              <span class="pill bg-secondary text-white">CLOSED</span>
            @endif
          </div>
        </div>
      @empty
        <div class="text-muted">No millers found.</div>
      @endforelse
    </div>
  </div>

  {{-- Products --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="section-title">Rice / Palay Posts</h4>
    <span class="small-note">Latest posts</span>
  </div>

  <div class="row g-3">
    @forelse(($products ?? []) as $p)
      @php
        $img = !empty($p->photo_path) ? asset('storage/'.$p->photo_path) : null;
        $stock = (float) ($p->kilos_available ?? 0);
        $price = (float) ($p->price_per_kg ?? 0);
      @endphp

      <div class="col-md-4">
        <div class="product-card">

          @if($img)
            <img src="{{ $img }}" class="product-img mb-3" alt="product">
          @else
            <div class="no-photo mb-3">No photo</div>
          @endif

          <div class="product-title">{{ $p->name }}</div>

          <div class="product-meta">
            Type:
            <span class="badge bg-secondary text-uppercase">{{ $p->type ?? '-' }}</span>
          </div>

          <div class="product-meta">
            By {{ $p->user->fullname ?? $p->user->username ?? 'Unknown' }}
          </div>

          <div class="product-price">₱{{ number_format($price, 2) }} / kg</div>
          <div class="stock-note">{{ number_format($stock, 2) }} kg available</div>

          <a href="{{ route('resident.checkout.show', $p->id) }}"
             class="buy-btn {{ $stock <= 0 ? 'disabled' : '' }}">
            Buy Now
          </a>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="soft p-4 text-center text-muted">No products found.</div>
      </div>
    @endforelse
  </div>

  <div class="pagination-wrap mt-4 d-flex justify-content-center">
    {{ $products->links() }}
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>