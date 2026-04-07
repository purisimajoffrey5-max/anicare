<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders | Resident</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4" style="max-width:1100px;">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold text-success m-0">My Orders</h3>
      <div class="text-muted small">View your order history and statuses</div>
    </div>
    <a href="{{ route('resident.marketplace') }}" class="btn btn-outline-success btn-sm">Back to Marketplace</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card shadow-sm">
    <div class="card-body table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Product</th>
            <th>Farmer</th>
            <th>Kilos</th>
            <th>Total</th>
            <th>Status</th>
            <th>Ordered At</th>
          </tr>
        </thead>
        <tbody>
          @forelse($orders as $o)
            <tr>
              <td>{{ $o->id }}</td>
              <td class="fw-semibold">{{ $o->product->name ?? '-' }}</td>
              <td>{{ $o->farmer->fullname ?? $o->farmer->username ?? '-' }}</td>
              <td>{{ number_format($o->quantity_kilos, 2) }}</td>
              <td class="fw-bold text-success">₱{{ number_format($o->total_price, 2) }}</td>
              <td>
                <span class="badge bg-secondary text-uppercase">{{ $o->status }}</span>
              </td>
              <td>{{ $o->created_at ? $o->created_at->format('Y-m-d H:i') : '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-4">No orders yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>

      <div class="mt-3">
        {{ $orders->links() }}
      </div>
    </div>
  </div>
</div>

</body>
</html>