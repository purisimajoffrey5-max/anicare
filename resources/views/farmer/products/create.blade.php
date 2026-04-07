<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Post Rice Product | ANI-CARE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4" style="max-width:900px;">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold text-success m-0">Post Rice Product</h3>
      <div class="text-muted small">Add a product with photo, price, and available kilos</div>
    </div>
    <a href="{{ route('farmer.dashboard') }}" class="btn btn-outline-success btn-sm">Back</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('farmer.products.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
          <label class="form-label">Product Name</label>
          <input name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Type</label>
            <select name="type" class="form-select" required>
              <option value="rice" {{ old('type')==='rice'?'selected':'' }}>Rice</option>
              <option value="palay" {{ old('type')==='palay'?'selected':'' }}>Palay</option>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Price per kg</label>
            <input type="number" step="0.01" name="price_per_kg" class="form-control" value="{{ old('price_per_kg') }}" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Kilos Available</label>
            <input type="number" step="0.01" name="kilos_available" class="form-control" value="{{ old('kilos_available') }}" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Photo (optional)</label>
            <input type="file" name="photo" class="form-control">
            <small class="text-muted">Max 2MB. JPG/PNG.</small>
          </div>
        </div>

        <button class="btn btn-success mt-3">Post Product</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>