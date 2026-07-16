<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f5f7fa;
        }

        .product-card{
            border:none;
            border-radius:18px;
            overflow:hidden;
            box-shadow:0 10px 25px rgba(0,0,0,.12);
        }

        .product-image{
            width:100%;
            height:500px;
            object-fit:cover;
            background:#eee;
        }

        .price{
            font-size:2rem;
            font-weight:700;
            color:#198754;
        }

        .label{
            font-weight:600;
            color:#444;
        }

        .buy-btn{
            width:100%;
            padding:15px;
            font-size:20px;
            border-radius:10px;
        }

        .badge-stock{
            font-size:15px;
        }
    </style>

</head>

<body>

<div class="container py-5">

    <a href="{{ route('resident.marketplace') }}"
       class="btn btn-secondary mb-4">
        ← Back to Marketplace
    </a>

    <div class="card product-card">

        <div class="row g-0">

            {{-- IMAGE --}}
            <div class="col-lg-5">

                @if(!empty($product->photo_path))
                    <img
                        src="{{ asset('storage/'.$product->photo_path) }}"
                        class="product-image"
                        alt="{{ $product->name }}">
                @else
                    <img
                        src="{{ asset('images/farmer.jpg') }}"
                        class="product-image"
                        alt="No Image">
                @endif

            </div>

            {{-- DETAILS --}}
            <div class="col-lg-7">

                <div class="card-body p-5">

                    <h1 class="mb-3">
                        {{ $product->name }}
                    </h1>

                    <div class="price mb-4">
                        ₱{{ number_format($product->price_per_kg,2) }}
                        <small class="fs-5 text-dark">/ kg</small>
                    </div>

                    <table class="table table-bordered">

                        <tr>
                            <th width="180">Rice Type</th>
                            <td>{{ ucfirst($product->type) }}</td>
                        </tr>

                        <tr>
                            <th>Available Stock</th>
                            <td>

                                @if($product->kilos_available > 0)

                                    <span class="badge bg-success badge-stock">
                                        {{ number_format($product->kilos_available,2) }} kg Available
                                    </span>

                                @else

                                    <span class="badge bg-danger badge-stock">
                                        Out of Stock
                                    </span>

                                @endif

                            </td>
                        </tr>

                        <tr>
                            <th>Seller</th>
                            <td>{{ $product->user->fullname }}</td>
                        </tr>

                        <tr>
                            <th>Price per Kilogram</th>
                            <td>
                                ₱{{ number_format($product->price_per_kg,2) }}
                            </td>
                        </tr>

                    </table>

                    @if(!empty($product->description))

                        <h5 class="mt-4">Description</h5>

                        <p class="text-muted">
                            {{ $product->description }}
                        </p>

                    @endif

                    <div class="mt-4">

                        @if($product->kilos_available > 0)

                            <a href="{{ route('resident.checkout.show',$product->id) }}"
                               class="btn btn-success buy-btn">
                                🛒 Buy Now
                            </a>

                        @else

                            <button
                                class="btn btn-secondary buy-btn"
                                disabled>
                                Out of Stock
                            </button>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>