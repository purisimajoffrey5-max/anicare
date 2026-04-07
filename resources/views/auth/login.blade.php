<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | ANI-CARE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-sm">
        <div class="card-body p-4">
          <h4 class="fw-bold text-success text-center mb-3">ANI-CARE Login</h4>

          @if(session('success'))
            <div class="alert alert-success text-center">
              {{ session('success') }}
            </div>
          @endif

          @if($errors->has('login'))
            <div class="alert alert-danger">
              {{ $errors->first('login') }}
            </div>
          @elseif($errors->any())
            <div class="alert alert-danger">
              {{ $errors->first() }}
            </div>
          @endif

          <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" name="username" class="form-control"
                     value="{{ old('username') }}" required autocomplete="username">
            </div>

            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required autocomplete="current-password">
            </div>

            <button class="btn btn-success w-100">Login</button>

            <div class="text-center mt-3">
              <a href="{{ route('register') }}">Create Account</a> |
              <a href="{{ route('main') }}">Back to Home</a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>