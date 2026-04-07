<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Products | ANI-CARE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    .img-thumb{ width:70px; height:52px; object-fit:cover; border-radius:10px; border:1px solid rgba(0,0,0,.08); }
  </style>
</head>
<body class="bg-light">

<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold text-success m-0">My Posted Products</h3>
      <div class="text-muted small">Manage your rice/palay products</div>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('farmer.dashboard') }}" class="btn btn-outline-success btn-sm">Back</a>
      <a href="{{ route('farmer.products.create') }}" class="btn btn-success btn-sm">+ Post New</a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card shadow-sm">
    <div class="card-body">

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Photo</th>
              <th>Name</th>
              <th>Type</th>
              <th>Price / kg</th>
              <th>Stocks (kg)</th>
              <th>Status</th>
              <th>Created</th>
              <th class="text-end">Action</th>
            </tr>
          </thead>
          <tbody>
          @forelse($products as $p)
            <tr>
              <td>{{ $p->id }}</td>

              <td style="width:90px;">
                @if(!empty($p->photo_path))
                  <img src="{{ asset('storage/'.$p->photo_path) }}" class="img-thumb" alt="photo">
                @else
                  <span class="text-muted small">No photo</span>
                @endif
              </td>

              <td class="fw-semibold">{{ $p->name }}</td>

              <td>
                <span class="badge bg-secondary text-uppercase">{{ $p->type ?? '-' }}</span>
              </td>

              <td class="fw-bold text-success">
                ₱{{ number_format((float)($p->price_per_kg ?? 0), 2) }}
              </td>

              <td class="fw-bold">
                {{ number_format((float)($p->kilos_available ?? 0), 2) }}
              </td>

              <td>
                @if($p->is_active)
                  <span class="badge bg-success">ACTIVE</span>
                @else
                  <span class="badge bg-secondary">INACTIVE</span>
                @endif
              </td>

              <td>{{ $p->created_at ? $p->created_at->format('Y-m-d') : '-' }}</td>

              <td class="text-end">
                <form method="POST" action="{{ route('farmer.products.toggle', $p->id) }}" class="d-inline">
                  @csrf
                  <button class="btn btn-outline-success btn-sm"
                          onclick="return confirm('Toggle active status?')">
                    Toggle
                  </button>
                </form>

                <form method="POST" action="{{ route('farmer.products.delete', $p->id) }}" class="d-inline">
                  @csrf
                  <button class="btn btn-outline-danger btn-sm"
                          onclick="return confirm('Delete this product?')">
                    Delete
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-center text-muted py-4">No products posted yet.</td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-3">
        {{ $products->links() }}
      </div>

    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>