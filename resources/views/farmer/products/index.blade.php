<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Products | ANI-CARE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
    }

    html{
        scroll-behavior:smooth;
    }

    body{
        background:#f5f7fb;
        font-family:'Segoe UI',sans-serif;
        color:#333;
    }

    a{
        text-decoration:none;
    }

    img{
        max-width:100%;
        height:auto;
    }

    .page-title{
        font-weight:700;
        color:#198754;
        margin-bottom:5px;
    }

    .page-subtitle{
        color:#6c757d;
        font-size:15px;
    }

    .toolbar{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:15px;
        margin-bottom:25px;
        flex-wrap:wrap;
    }

    .toolbar-buttons{
        display:flex;
        gap:10px;
        flex-wrap:wrap;
    }

    .card{
        border:none;
        border-radius:16px;
        box-shadow:0 8px 24px rgba(0,0,0,.06);
    }

    .card-body{
        padding:25px;
    }

    table{
        margin-bottom:0;
    }

    thead{
        background:#198754;
        color:white;
    }

    thead th{
        border:none !important;
        font-weight:600;
        white-space:nowrap;
    }

    tbody td{
        vertical-align:middle;
    }

    .img-thumb{
        width:80px;
        height:60px;
        object-fit:cover;
        border-radius:10px;
        border:1px solid #ddd;
    }

    .badge{
        font-size:12px;
        padding:7px 10px;
        letter-spacing:.5px;
    }

    .price{
        font-weight:700;
        color:#198754;
    }

    .stock{
        font-weight:600;
    }

    .action-buttons{
        display:flex;
        flex-wrap:wrap;
        justify-content:flex-end;
        gap:6px;
    }

    .restock-group{
        display:flex;
        width:180px;
    }

    .mobile-products{
        display:none;
    }

    .mobile-card{

        background:#fff;
        border-radius:16px;
        box-shadow:0 6px 20px rgba(0,0,0,.06);
        margin-bottom:18px;
        overflow:hidden;

    }

    .mobile-image{

        width:100%;
        height:210px;
        object-fit:cover;
        background:#eee;

    }

    .mobile-content{

        padding:18px;

    }

    .mobile-title{

        font-size:20px;
        font-weight:700;
        color:#198754;
        margin-bottom:10px;

    }

    .mobile-info{

        display:flex;
        justify-content:space-between;
        margin-bottom:10px;
        flex-wrap:wrap;
        gap:8px;

    }

    .mobile-label{

        color:#6c757d;
        font-size:14px;

    }

    .mobile-value{

        font-weight:600;

    }

    .mobile-actions{

        display:grid;
        grid-template-columns:repeat(2,1fr);
        gap:8px;
        margin-top:15px;

    }

    .mobile-actions form{
        width:100%;
    }

    .mobile-actions button{
        width:100%;
    }

    @media(max-width:991px){

        .table-responsive{
            display:none;
        }

        .mobile-products{
            display:block;
        }

    }

    @media(max-width:768px){

        .container{
            padding-left:15px;
            padding-right:15px;
        }

        .toolbar{

            flex-direction:column;
            align-items:flex-start;

        }

        .toolbar-buttons{

            width:100%;

        }

        .toolbar-buttons .btn{

            flex:1;

        }

        .card-body{

            padding:18px;

        }

        .page-title{

            font-size:28px;

        }

    }

    @media(max-width:576px){

        .mobile-image{

            height:180px;

        }

        .mobile-title{

            font-size:18px;

        }

        .mobile-actions{

            grid-template-columns:1fr;

        }

    }

    </style>

</head>

<body class="bg-light">

<div class="container py-4">

    <div class="toolbar">

        <div>

            <h2 class="page-title">
                My Posted Products
            </h2>

            <div class="page-subtitle">
                Manage your rice and palay products.
            </div>

        </div>

        <div class="toolbar-buttons">

            <a href="{{ route('farmer.dashboard') }}"
               class="btn btn-outline-success">

                ← Back

            </a>

            <a href="{{ route('farmer.products.create') }}"
               class="btn btn-success">

                + Post New Product

            </a>

        </div>

    </div>

    @if(session('success'))

        <div class="alert alert-success shadow-sm">

            {{ session('success') }}

        </div>

    @endif

    <div class="card">

        <div class="card-body">

            <!-- DESKTOP TABLE -->

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Photo</th>
                            <th>Product</th>
                            <th>Type</th>
                            <th>Price/kg</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>

                        </tr>

                    </thead>

                    <tbody>
                      @forelse($products as $p)

<tr>

    <td>
        {{ $p->id }}
    </td>

    <td style="width:95px;">

        @if(!empty($p->photo_path))

            <img
                src="{{ asset('storage/'.$p->photo_path) }}"
                class="img-thumb"
                alt="{{ $p->name }}">

        @else

            <div class="text-muted small">
                No Photo
            </div>

        @endif

    </td>

    <td>

        <div class="fw-bold">

            {{ $p->name }}

        </div>

    </td>

    <td>

        <span class="badge bg-secondary">

            {{ strtoupper($p->type ?? '-') }}

        </span>

    </td>

    <td class="price">

        ₱{{ number_format((float)($p->price_per_kg ?? 0),2) }}

    </td>

    <td class="stock">

        {{ number_format((float)($p->kilos_available ?? 0),2) }} kg

    </td>

    <td>

        @if($p->is_active)

            <span class="badge bg-success">

                ACTIVE

            </span>

        @else

            <span class="badge bg-danger">

                INACTIVE

            </span>

        @endif

    </td>

    <td>

        {{ optional($p->created_at)->format('M d, Y') }}

    </td>

    <td>

        <div class="action-buttons">

            {{-- Toggle --}}

            <form
                method="POST"
                action="{{ route('farmer.products.toggle',$p->id) }}">

                @csrf

                <button
                    class="btn btn-outline-success btn-sm"
                    onclick="return confirm('Toggle product status?')">

                    Toggle

                </button>

            </form>

            {{-- Out Of Stock --}}

            <form
                method="POST"
                action="{{ route('farmer.products.outOfStock',$p->id) }}">

                @csrf

                <button
                    class="btn btn-outline-danger btn-sm"
                    onclick="return confirm('Mark as out of stock?')">

                    Out of Stock

                </button>

            </form>

            {{-- Restock --}}

            <form
                method="POST"
                action="{{ route('farmer.products.restock',$p->id) }}">

                @csrf

                <div class="input-group input-group-sm restock-group">

                    <input
                        type="number"
                        name="kilos_available"
                        step="0.1"
                        min="0.1"
                        class="form-control"
                        value="{{ $p->kilos_available > 0 ? number_format($p->kilos_available,2,'.','') : '' }}"
                        placeholder="kg">

                    <button
                        class="btn btn-outline-primary"
                        type="submit"
                        onclick="return confirm('Restock this product?')">

                        Restock

                    </button>

                </div>

            </form>

            {{-- Delete --}}

            <form
                method="POST"
                action="{{ route('farmer.products.delete',$p->id) }}">

                @csrf

                <button
                    class="btn btn-outline-danger btn-sm"
                    onclick="return confirm('Delete this product permanently?')">

                    Delete

                </button>

            </form>

        </div>

    </td>

</tr>

@empty

<tr>

    <td colspan="9" class="text-center py-5 text-muted">

        <h5 class="mb-2">

            No products found

        </h5>

        <p class="mb-3">

            You haven't posted any rice or palay products yet.

        </p>

        <a
            href="{{ route('farmer.products.create') }}"
            class="btn btn-success">

            + Post Your First Product

        </a>

    </td>

</tr>

@endforelse

</tbody>

</table>

</div>

<!-- =========================
     MOBILE CARD LAYOUT
========================= -->

<div class="mobile-products">
  @forelse($products as $p)

<div class="mobile-card">

    @if(!empty($p->photo_path))

        <img
            src="{{ asset('storage/'.$p->photo_path) }}"
            class="mobile-image"
            alt="{{ $p->name }}">

    @else

        <div
            class="mobile-image d-flex align-items-center justify-content-center text-muted">

            No Photo

        </div>

    @endif

    <div class="mobile-content">

        <div class="mobile-title">

            {{ $p->name }}

        </div>

        <div class="mobile-info">

            <div>

                <div class="mobile-label">

                    Type

                </div>

                <div class="mobile-value">

                    {{ strtoupper($p->type ?? '-') }}

                </div>

            </div>

            <div>

                <div class="mobile-label">

                    Price

                </div>

                <div class="mobile-value text-success">

                    ₱{{ number_format((float)($p->price_per_kg ?? 0),2) }}/kg

                </div>

            </div>

        </div>

        <div class="mobile-info">

            <div>

                <div class="mobile-label">

                    Stocks

                </div>

                <div class="mobile-value">

                    {{ number_format((float)($p->kilos_available ?? 0),2) }} kg

                </div>

            </div>

            <div>

                <div class="mobile-label">

                    Status

                </div>

                @if($p->is_active)

                    <span class="badge bg-success">

                        ACTIVE

                    </span>

                @else

                    <span class="badge bg-danger">

                        INACTIVE

                    </span>

                @endif

            </div>

        </div>

        <div class="mobile-info">

            <div>

                <div class="mobile-label">

                    Posted

                </div>

                <div class="mobile-value">

                    {{ optional($p->created_at)->format('M d, Y') }}

                </div>

            </div>

        </div>

        <div class="mobile-actions">

            {{-- Toggle --}}

            <form
                method="POST"
                action="{{ route('farmer.products.toggle',$p->id) }}">

                @csrf

                <button
                    class="btn btn-outline-success"
                    onclick="return confirm('Toggle this product?')">

                    Toggle

                </button>

            </form>

            {{-- Out Of Stock --}}

            <form
                method="POST"
                action="{{ route('farmer.products.outOfStock',$p->id) }}">

                @csrf

                <button
                    class="btn btn-outline-danger"
                    onclick="return confirm('Mark as out of stock?')">

                    Out of Stock

                </button>

            </form>

        </div>

        <form
            class="mt-3"
            method="POST"
            action="{{ route('farmer.products.restock',$p->id) }}">

            @csrf

            <label class="form-label fw-semibold">

                Restock Quantity (kg)

            </label>

            <div class="input-group">

                <input
                    type="number"
                    step="0.1"
                    min="0.1"
                    class="form-control"
                    name="kilos_available"
                    value="{{ $p->kilos_available > 0 ? number_format($p->kilos_available,2,'.','') : '' }}"
                    placeholder="Enter kilos">

                <button
                    class="btn btn-primary"
                    onclick="return confirm('Restock this product?')">

                    Restock

                </button>

            </div>

        </form>

        <form
            class="mt-3"
            method="POST"
            action="{{ route('farmer.products.delete',$p->id) }}">

            @csrf

            <button
                class="btn btn-danger w-100"
                onclick="return confirm('Delete this product permanently?')">

                Delete Product

            </button>

        </form>

    </div>

</div>

@empty

<div class="text-center py-5">

    <h5 class="text-muted">

        No products found.

    </h5>

    <p class="text-muted">

        You haven't posted any rice or palay products yet.

    </p>

    <a
        href="{{ route('farmer.products.create') }}"
        class="btn btn-success">

        + Post Your First Product

    </a>

</div>

@endforelse

</div>

<div class="mt-4">

    {{ $products->links() }}

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>