<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout | ANI-CARE Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Mobile responsive helpers -->
  <style>
    html { box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
    *, *::before, *::after { box-sizing: inherit; }
    body { min-height: 100vh; margin: 0; }
    img, video, iframe, svg, canvas { max-width: 100%; height: auto; }
    .container, .container-fluid { width: 100% !important; max-width: 100% !important; padding-left: 1rem !important; padding-right: 1rem !important; }
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .table-responsive table { min-width: 100%; }
    .leaflet-container, #registrationMap, #residentMap, #orderMap, #trackMap { width: 100% !important; max-width: 100%; }
    .card, .card-body { word-wrap: break-word; }
    .btn, .form-control, .form-select, .input-group, .form-check-input { min-width: 0; }
    @media (max-width: 768px) {
      .navbar, .topbar { flex-wrap: wrap !important; }
      .navbar-brand, .navbar-nav, .btn { width: 100% !important; text-align: center !important; }
      .table-responsive { margin-left: -1rem !important; margin-right: -1rem !important; padding-left: 1rem !important; padding-right: 1rem !important; }
    }
  </style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body{
      background:#f5f7fb;
      font-family:"Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .topbar{
      background:#198754;
    }

    .soft{
      border:1px solid rgba(0,0,0,.06);
      border-radius:18px;
      background:#fff;
      box-shadow:0 8px 20px rgba(16,24,40,.05);
    }

    .checkout-card{
      border:1px solid rgba(0,0,0,.06);
      border-radius:18px;
      background:#fff;
      padding:30px;
      box-shadow:0 8px 20px rgba(16,24,40,.04);
    }

    .product-info{
      background:#f5f7fb;
      padding:20px;
      border-radius:12px;
      margin-bottom:30px;
    }

    .product-title{
      font-weight:700;
      font-size:20px;
      color:#1f2937;
      margin-bottom:8px;
    }

    .product-meta{
      font-size:14px;
      color:#6b7280;
      margin-bottom:4px;
    }

    .product-price{
      font-size:24px;
      font-weight:800;
      color:#198754;
      margin-top:12px;
    }

    .form-section-title{
      font-weight:700;
      font-size:16px;
      margin-top:24px;
      margin-bottom:16px;
      color:#1f2937;
      border-bottom:2px solid #198754;
      padding-bottom:8px;
    }

    .btn-place-order{
      width:100%;
      border:none;
      border-radius:12px;
      padding:16px;
      font-size:16px;
      font-weight:700;
      background:#198754;
      color:#fff;
      margin-top:30px;
    }

    .btn-place-order:hover{
      background:#157347;
      color:#fff;
    }

    .alert{
      border-radius:12px;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-dark topbar">
  <div class="container-fluid px-3">
    <span class="navbar-brand fw-bold m-0">ANI-CARE | Admin</span>
    <a href="{{ route('admin.market') }}" class="btn btn-outline-light btn-sm">Back to Marketplace</a>
  </div>
</nav>

<div style="max-width:800px; margin:0 auto; padding:30px 14px;">

  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <div class="checkout-card">
    <h3 class="fw-bold mb-4">Order Details</h3>

    <div class="product-info">
      <div class="product-title">{{ $product->name }}</div>
      <div class="product-meta">
        Type: <span class="badge bg-secondary text-uppercase">{{ $product->type ?? '-' }}</span>
      </div>
      <div class="product-meta">
        Seller: {{ $product->user->fullname ?? $product->user->username ?? 'Unknown' }}
      </div>
      <div class="product-meta">
        Available Stock: {{ number_format((float)$product->kilos_available, 2) }} kg
      </div>
      <div class="product-price">₱{{ number_format((float)$product->price_per_kg, 2) }} / kg</div>
    </div>

    <form method="POST" action="{{ route('admin.checkout.place', $product->id) }}">
      @csrf

      <div class="form-section-title">Buyer Information</div>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Buyer Name *</label>
          <input type="text" name="buyer_name" class="form-control" value="{{ old('buyer_name', $user->fullname ?? $user->username) }}" required>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Contact Number *</label>
          <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number') }}" required>
        </div>
      </div>

      <div class="form-section-title">Order Details</div>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Quantity (kg) *</label>
          <input type="number" step="0.01" min="0.01" name="quantity_kilos" class="form-control" value="{{ old('quantity_kilos') }}" required>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Fulfillment Type *</label>
          <select name="fulfillment_type" class="form-select" required onchange="toggleAddress()">
            <option value="delivery">Delivery</option>
            <option value="pickup">Pickup</option>
          </select>
        </div>
      </div>

      <div class="form-section-title">Location</div>
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label fw-semibold">Delivery Address</label>
          <input type="text" name="delivery_address" class="form-control" value="{{ old('delivery_address') }}" placeholder="Required for delivery orders">
        </div>

        <div class="col-12">
          <label class="form-label fw-semibold">Pickup Address</label>
          <input type="text" name="pickup_address" class="form-control" value="{{ old('pickup_address') }}" placeholder="Required for pickup orders">
        </div>
      </div>

      <div class="form-section-title">Payment & Notes</div>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Payment Method *</label>
          <select name="payment_method" class="form-select" required>
            <option value="">-- Select Payment Method --</option>
            <option value="gcash">GCash</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="cash_on_delivery">Cash on Delivery</option>
            <option value="cash_on_pickup">Cash on Pickup</option>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Additional Notes</label>
          <input type="text" name="notes" class="form-control" value="{{ old('notes') }}" placeholder="Optional">
        </div>
      </div>

      <button type="submit" class="btn btn-place-order">Place Order</button>
    </form>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function toggleAddress() {
    const type = document.querySelector('select[name="fulfillment_type"]').value;
    const deliveryInput = document.querySelector('input[name="delivery_address"]');
    const pickupInput = document.querySelector('input[name="pickup_address"]');

    if (type === 'delivery') {
      deliveryInput.required = true;
      pickupInput.required = false;
      pickupInput.value = '';
    } else {
      pickupInput.required = true;
      deliveryInput.required = false;
      deliveryInput.value = '';
    }
  }

  // Initialize on page load
  document.addEventListener('DOMContentLoaded', toggleAddress);
</script>
</body>
</html>

