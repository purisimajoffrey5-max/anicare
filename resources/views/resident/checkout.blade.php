<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout | ANI-CARE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold text-success m-0">Checkout</h3>
    <a href="{{ route('resident.marketplace') }}" class="btn btn-outline-success btn-sm">Back</a>
  </div>

  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <div class="card shadow-sm">
    <div class="card-body">
      <h4>{{ $product->name }}</h4>
      <p class="text-muted mb-1">Seller: {{ $product->user->fullname ?? $product->user->username ?? 'Unknown' }}</p>
      <p class="text-muted mb-3">Price: ₱{{ number_format((float)$product->price_per_kg, 2) }} / kg</p>

      <form method="POST" action="{{ route('resident.checkout.place', $product->id) }}">
        @csrf

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Buyer Name</label>
            <input type="text" name="buyer_name" class="form-control" value="{{ old('buyer_name', $user->fullname ?? $user->username) }}" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Contact Number</label>
            <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number') }}" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Quantity (kg)</label>
            <input type="number" step="0.01" min="0.01" name="quantity_kilos" class="form-control" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Order Type</label>
            <select name="fulfillment_type" class="form-select" required>
              <option value="delivery">Delivery</option>
              <option value="pickup">Pickup</option>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Delivery Address</label>
            <input type="text" name="delivery_address" class="form-control" value="{{ old('delivery_address') }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Pickup Address</label>
            <input type="text" name="pickup_address" class="form-control" value="{{ old('pickup_address') }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Payment Method</label>
            <select name="payment_method" class="form-select" required>
              <option value="gcash">GCash</option>
              <option value="bank_transfer">Bank Transfer</option>
              <option value="cash_on_delivery">Cash on Delivery</option>
              <option value="cash_on_pickup">Cash on Pickup</option>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Notes</label>
            <input type="text" name="notes" class="form-control" value="{{ old('notes') }}">
          </div>
        </div>

        <div class="mt-4">
          <button class="btn btn-success">Place Order</button>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>