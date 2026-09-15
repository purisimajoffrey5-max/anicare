<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, viewport-fit=cover"
    >

    <title>My Products | ANI-CARE</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #198754;
            --primary-dark: #157347;
            --primary-soft: #eefaf3;
            --danger: #dc3545;
            --blue: #0d6efd;
            --bg: #f4f6f8;
            --card: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --border: #e5e7eb;
            --shadow: 0 5px 18px rgba(15, 23, 42, .06);
        }

        html {
            width: 100%;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        body {
            width: 100%;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;

            background: var(--bg);
            color: var(--text);

            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;

            -webkit-text-size-adjust: 100%;
        }

        a {
            text-decoration: none;
        }

        img {
            display: block;
            max-width: 100%;
        }

        button,
        input,
        a {
            -webkit-tap-highlight-color: transparent;
        }

        /* =========================================
           PAGE WRAPPER
        ========================================= */

        .products-page {
            width: 100%;
            max-width: 1180px;
            margin: 0 auto;

            padding: 26px 16px 45px;
        }

        /* =========================================
           HEADER
        ========================================= */

        .toolbar {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;

            margin-bottom: 22px;
        }

        .page-heading {
            min-width: 0;
        }

        .page-title {
            margin: 0 0 5px;

            color: var(--primary);

            font-size: 32px;
            font-weight: 800;
            line-height: 1.2;
        }

        .page-subtitle {
            margin: 0;

            color: var(--muted);

            font-size: 15px;
            line-height: 1.5;
        }

        .toolbar-buttons {
            flex-shrink: 0;

            display: flex;
            align-items: center;
            gap: 9px;
        }

        .toolbar-buttons .btn {
            min-height: 42px;

            padding: 8px 15px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border-radius: 9px;

            font-size: 13px;
            font-weight: 700;

            white-space: nowrap;
        }

        /* =========================================
           ALERT
        ========================================= */

        .alert {
            border: 0;
            border-radius: 12px;
        }

        /* =========================================
           DESKTOP
        ========================================= */

        .desktop-products {
            overflow: hidden;

            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;

            box-shadow: var(--shadow);
        }

        .desktop-products .table-responsive {
            width: 100%;
            overflow-x: auto;

            -webkit-overflow-scrolling: touch;
        }

        .desktop-products table {
            min-width: 1050px;
            margin: 0;
        }

        .desktop-products thead {
            background: var(--primary);
            color: #fff;
        }

        .desktop-products thead th {
            padding: 14px;

            border: 0 !important;

            font-size: 13px;
            font-weight: 700;

            white-space: nowrap;
        }

        .desktop-products tbody td {
            padding: 13px 14px;

            vertical-align: middle;

            font-size: 13px;
        }

        .img-thumb {
            width: 78px;
            height: 58px;

            object-fit: cover;

            border: 1px solid #ddd;
            border-radius: 9px;
        }

        .price {
            color: var(--primary);
            font-weight: 800;
        }

        .stock {
            font-weight: 700;
        }

        .action-buttons {
            display: flex;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 6px;
        }

        .restock-group {
            width: 195px;
        }

        /* =========================================
           MOBILE
        ========================================= */

        .mobile-products {
            display: none;
        }

        .mobile-card {
            overflow: hidden;

            margin-bottom: 14px;

            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;

            box-shadow: var(--shadow);
        }

        .mobile-card:last-child {
            margin-bottom: 0;
        }

        .mobile-image {
            width: 100%;
            height: 190px;

            object-fit: cover;
            object-position: center;

            background: #f0f0f0;
        }

        .mobile-content {
            padding: 16px;
        }

        .mobile-product-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;

            margin-bottom: 14px;
        }

        .mobile-title {
            min-width: 0;

            color: var(--primary);

            font-size: 18px;
            font-weight: 800;
            line-height: 1.25;

            text-transform: uppercase;
            overflow-wrap: anywhere;
        }

        .mobile-status {
            flex-shrink: 0;
        }

        .mobile-status .badge {
            padding: 6px 9px;

            border-radius: 7px;

            font-size: 10px;
            letter-spacing: .3px;
        }

        /* =========================================
           MOBILE PRODUCT INFORMATION
        ========================================= */

        .mobile-info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 9px;

            margin-bottom: 14px;
        }

        .mobile-info-box {
            min-width: 0;

            padding: 10px 11px;

            background: #f8fafc;

            border: 1px solid #eef0f2;
            border-radius: 10px;
        }

        .mobile-info-box.full {
            grid-column: 1 / -1;
        }

        .mobile-label {
            display: block;

            margin-bottom: 3px;

            color: #8a94a3;

            font-size: 10px;
            font-weight: 600;

            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .mobile-value {
            color: var(--text);

            font-size: 13px;
            font-weight: 700;
            line-height: 1.35;

            overflow-wrap: anywhere;
        }

        .mobile-value.price-value {
            color: var(--primary);
        }

        /* =========================================
           MOBILE ACTIONS
        ========================================= */

        .mobile-actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;

            margin-top: 2px;
            margin-bottom: 14px;
        }

        .mobile-actions form {
            width: 100%;
            min-width: 0;

            margin: 0;
        }

        .mobile-actions .btn {
            width: 100%;
            min-height: 40px;

            padding: 7px 8px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 8px;

            font-size: 12px;
            font-weight: 700;
        }

        /* =========================================
           RESTOCK
        ========================================= */

        .mobile-restock {
            padding-top: 13px;

            border-top: 1px solid #edf0f2;
        }

        .mobile-restock-label {
            display: block;

            margin-bottom: 7px;

            color: #374151;

            font-size: 12px;
            font-weight: 700;
        }

        .mobile-restock .input-group {
            width: 100%;
        }

        .mobile-restock .form-control {
            min-width: 0;
            min-height: 42px;

            border-radius: 8px 0 0 8px;

            font-size: 14px;
        }

        .mobile-restock .btn {
            min-height: 42px;

            padding-left: 15px;
            padding-right: 15px;

            border-radius: 0 8px 8px 0;

            font-size: 12px;
            font-weight: 700;
        }

        /* =========================================
           DELETE
        ========================================= */

        .delete-form {
            margin-top: 9px;
        }

        .delete-btn {
            width: 100%;
            min-height: 41px;

            border-radius: 8px;

            font-size: 12px;
            font-weight: 700;
        }

        /* =========================================
           EMPTY STATE
        ========================================= */

        .empty-state {
            padding: 45px 15px;

            text-align: center;

            background: #fff;

            border-radius: 16px;

            box-shadow: var(--shadow);
        }

        .empty-state h5 {
            margin-bottom: 5px;

            color: #4b5563;
            font-weight: 700;
        }

        .empty-state p {
            margin-bottom: 14px;

            color: var(--muted);
            font-size: 13px;
        }

        /* =========================================
           PAGINATION
        ========================================= */

        .pagination-wrap {
            margin-top: 20px;
        }

        /* =========================================
           TABLET
        ========================================= */

        @media (max-width: 991.98px) {

            .desktop-products {
                display: none;
            }

            .mobile-products {
                display: block;
            }

            .products-page {
                max-width: 760px;
            }
        }

        /* =========================================
           MOBILE
        ========================================= */

        @media (max-width: 767.98px) {

            .products-page {
                padding:
                    20px
                    12px
                    calc(35px + env(safe-area-inset-bottom));
            }

            .toolbar {
                align-items: stretch;
                flex-direction: column;

                gap: 14px;

                margin-bottom: 18px;
            }

            .page-title {
                font-size: 27px;
            }

            .page-subtitle {
                font-size: 13px;
            }

            .toolbar-buttons {
                width: 100%;

                display: grid;
                grid-template-columns:
                    minmax(0, .85fr)
                    minmax(0, 1.15fr);

                gap: 8px;
            }

            .toolbar-buttons .btn {
                width: 100%;
                min-width: 0;

                padding-left: 8px;
                padding-right: 8px;

                font-size: 12px;

                white-space: normal;
            }

            .mobile-image {
                height: 175px;
            }

            .mobile-content {
                padding: 14px;
            }

            .mobile-title {
                font-size: 17px;
            }

            .pagination-wrap {
                overflow-x: auto;
            }

            .pagination {
                flex-wrap: wrap;
                gap: 2px;
            }
        }

        /* =========================================
           SMALL PHONE
        ========================================= */

        @media (max-width: 420px) {

            .products-page {
                padding-left: 10px;
                padding-right: 10px;
            }

            .page-title {
                font-size: 25px;
            }

            .mobile-image {
                height: 160px;
            }

            .mobile-content {
                padding: 13px;
            }

            .mobile-info-grid {
                gap: 7px;
            }

            .mobile-info-box {
                padding: 9px;
            }

            .toolbar-buttons .btn {
                min-height: 41px;
                font-size: 11px;
            }
        }

        /* =========================================
           VERY SMALL PHONE
        ========================================= */

        @media (max-width: 340px) {

            .toolbar-buttons {
                grid-template-columns: 1fr;
            }

            .mobile-info-grid {
                grid-template-columns: 1fr;
            }

            .mobile-info-box.full {
                grid-column: auto;
            }

            .mobile-actions {
                grid-template-columns: 1fr;
            }

            .mobile-image {
                height: 150px;
            }
        }
    </style>
</head>


<body>

<main class="products-page">

    {{-- =========================================
         HEADER
    ========================================== --}}
    <div class="toolbar">

        <div class="page-heading">

            <h1 class="page-title">
                My Posted Products
            </h1>

            <p class="page-subtitle">
                Manage your rice and palay products.
            </p>

        </div>


        <div class="toolbar-buttons">

            <a
                href="{{ route('farmer.dashboard') }}"
                class="btn btn-outline-success"
            >
                ← Back
            </a>

            <a
                href="{{ route('farmer.products.create') }}"
                class="btn btn-success"
            >
                + Post New Product
            </a>

        </div>

    </div>


    {{-- =========================================
         SUCCESS MESSAGE
    ========================================== --}}
    @if(session('success'))

        <div class="alert alert-success shadow-sm">
            {{ session('success') }}
        </div>

    @endif


    {{-- =========================================
         DESKTOP TABLE
    ========================================== --}}
    <div class="desktop-products">

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
                                    alt="{{ $p->name }}"
                                >

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

                            ₱{{ number_format(
                                (float)($p->price_per_kg ?? 0),
                                2
                            ) }}

                        </td>


                        <td class="stock">

                            {{ number_format(
                                (float)($p->kilos_available ?? 0),
                                2
                            ) }} kg

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

                                {{-- TOGGLE --}}
                                <form
                                    method="POST"
                                    action="{{ route(
                                        'farmer.products.toggle',
                                        $p->id
                                    ) }}"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="btn btn-outline-success btn-sm"
                                        onclick="return confirm('Toggle product status?')"
                                    >
                                        Toggle
                                    </button>

                                </form>


                                {{-- OUT OF STOCK --}}
                                <form
                                    method="POST"
                                    action="{{ route(
                                        'farmer.products.outOfStock',
                                        $p->id
                                    ) }}"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('Mark as out of stock?')"
                                    >
                                        Out of Stock
                                    </button>

                                </form>


                                {{-- RESTOCK --}}
                                <form
                                    method="POST"
                                    action="{{ route(
                                        'farmer.products.restock',
                                        $p->id
                                    ) }}"
                                >
                                    @csrf

                                    <div class="input-group input-group-sm restock-group">

                                        <input
                                            type="number"
                                            name="kilos_available"
                                            step="0.1"
                                            min="0.1"
                                            class="form-control"
                                            value="{{
                                                $p->kilos_available > 0
                                                    ? number_format(
                                                        $p->kilos_available,
                                                        2,
                                                        '.',
                                                        ''
                                                    )
                                                    : ''
                                            }}"
                                            placeholder="kg"
                                            required
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-outline-primary"
                                            onclick="return confirm('Restock this product?')"
                                        >
                                            Restock
                                        </button>

                                    </div>

                                </form>


                                {{-- DELETE --}}
                                <form
                                    method="POST"
                                    action="{{ route(
                                        'farmer.products.delete',
                                        $p->id
                                    ) }}"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('Delete this product permanently?')"
                                    >
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="9"
                            class="text-center py-5 text-muted"
                        >

                            <h5 class="mb-2">
                                No products found
                            </h5>

                            <p class="mb-3">
                                You haven't posted any rice
                                or palay products yet.
                            </p>

                            <a
                                href="{{ route('farmer.products.create') }}"
                                class="btn btn-success"
                            >
                                + Post Your First Product
                            </a>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- =========================================
         MOBILE PRODUCT CARDS
    ========================================== --}}
    <div class="mobile-products">

        @forelse($products as $p)

            <article class="mobile-card">

                {{-- PRODUCT IMAGE --}}
                @if(!empty($p->photo_path))

                    <img
                        src="{{ asset('storage/'.$p->photo_path) }}"
                        class="mobile-image"
                        alt="{{ $p->name }}"
                    >

                @else

                    <div
                        class="mobile-image d-flex align-items-center justify-content-center text-muted"
                    >
                        No Photo
                    </div>

                @endif


                <div class="mobile-content">


                    {{-- =================================
                         PRODUCT NAME + STATUS
                    ================================== --}}
                    <div class="mobile-product-head">

                        <div class="mobile-title">
                            {{ $p->name }}
                        </div>


                        <div class="mobile-status">

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


                    {{-- =================================
                         PRODUCT INFORMATION
                    ================================== --}}
                    <div class="mobile-info-grid">


                        {{-- TYPE --}}
                        <div class="mobile-info-box">

                            <span class="mobile-label">
                                Type
                            </span>

                            <div class="mobile-value">
                                {{ strtoupper($p->type ?? '-') }}
                            </div>

                        </div>


                        {{-- PRICE --}}
                        <div class="mobile-info-box">

                            <span class="mobile-label">
                                Price
                            </span>

                            <div class="mobile-value price-value">

                                ₱{{ number_format(
                                    (float)($p->price_per_kg ?? 0),
                                    2
                                ) }}/kg

                            </div>

                        </div>


                        {{-- STOCK --}}
                        <div class="mobile-info-box">

                            <span class="mobile-label">
                                Stock
                            </span>

                            <div class="mobile-value">

                                {{ number_format(
                                    (float)($p->kilos_available ?? 0),
                                    2
                                ) }} kg

                            </div>

                        </div>


                        {{-- DATE --}}
                        <div class="mobile-info-box">

                            <span class="mobile-label">
                                Posted
                            </span>

                            <div class="mobile-value">

                                {{ optional($p->created_at)
                                    ->format('M d, Y') }}

                            </div>

                        </div>

                    </div>


                    {{-- =================================
                         STATUS ACTIONS
                    ================================== --}}
                    <div class="mobile-actions">


                        {{-- TOGGLE --}}
                        <form
                            method="POST"
                            action="{{ route(
                                'farmer.products.toggle',
                                $p->id
                            ) }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="btn btn-outline-success"
                                onclick="return confirm('Toggle this product?')"
                            >
                                Toggle
                            </button>

                        </form>


                        {{-- OUT OF STOCK --}}
                        <form
                            method="POST"
                            action="{{ route(
                                'farmer.products.outOfStock',
                                $p->id
                            ) }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="btn btn-outline-danger"
                                onclick="return confirm('Mark as out of stock?')"
                            >
                                Out of Stock
                            </button>

                        </form>

                    </div>


                    {{-- =================================
                         RESTOCK
                    ================================== --}}
                    <div class="mobile-restock">

                        <form
                            method="POST"
                            action="{{ route(
                                'farmer.products.restock',
                                $p->id
                            ) }}"
                        >
                            @csrf

                            <label class="mobile-restock-label">
                                Restock Quantity (kg)
                            </label>


                            <div class="input-group">

                                <input
                                    type="number"
                                    step="0.1"
                                    min="0.1"
                                    class="form-control"
                                    name="kilos_available"
                                    value="{{
                                        $p->kilos_available > 0
                                            ? number_format(
                                                $p->kilos_available,
                                                2,
                                                '.',
                                                ''
                                            )
                                            : ''
                                    }}"
                                    placeholder="Enter kilos"
                                    required
                                >

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    onclick="return confirm('Restock this product?')"
                                >
                                    Restock
                                </button>

                            </div>

                        </form>

                    </div>


                    {{-- =================================
                         DELETE
                    ================================== --}}
                    <form
                        class="delete-form"
                        method="POST"
                        action="{{ route(
                            'farmer.products.delete',
                            $p->id
                        ) }}"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="btn btn-danger delete-btn"
                            onclick="return confirm('Delete this product permanently?')"
                        >
                            Delete Product
                        </button>

                    </form>

                </div>

            </article>

        @empty

            <div class="empty-state">

                <h5>
                    No products found.
                </h5>

                <p>
                    You haven't posted any rice or
                    palay products yet.
                </p>

                <a
                    href="{{ route('farmer.products.create') }}"
                    class="btn btn-success"
                >
                    + Post Your First Product
                </a>

            </div>

        @endforelse

    </div>


    {{-- =========================================
         PAGINATION
    ========================================== --}}
    @if(method_exists($products, 'links'))

        <div class="pagination-wrap">

            {{ $products->links() }}

        </div>

    @endif

</main>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>