<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <title>Reset Password | ANI-CARE</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        body{
            background:#f4f7f8;
        }

        .card{
            border:none;
            border-radius:18px;
        }

        .title{
            color:#198754;
            font-weight:bold;
        }

    </style>

</head>

<body>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-md-5">

<div class="card shadow">

<div class="card-body p-4">

<h3 class="title text-center mb-4">

Create New Password

</h3>

<p class="text-center text-muted">

Your OTP has been verified.

Please create your new password.

</p>

@if($errors->any())

<div class="alert alert-danger">

{{ $errors->first() }}

</div>

@endif

<form method="POST"
      action="{{ route('password.reset') }}">

@csrf

<div class="mb-3">

<label class="form-label">

New Password

</label>

<input
type="password"
name="password"
id="password"
class="form-control"
required>

</div>

<div class="mb-4">

<label class="form-label">

Confirm Password

</label>

<input
type="password"
name="password_confirmation"
id="confirm_password"
class="form-control"
required>

</div>

<div class="form-check mb-3">

<input
class="form-check-input"
type="checkbox"
id="showPassword">

<label
class="form-check-label"
for="showPassword">

Show Password

</label>

</div>

<button
class="btn btn-success w-100">

Update Password

</button>

</form>

<div class="text-center mt-4">

<a href="{{ route('login') }}">

Back to Login

</a>

</div>

</div>

</div>

</div>

</div>

</div>

<script>

document.getElementById('showPassword').addEventListener('change',function(){

    let type=this.checked?'text':'password';

    document.getElementById('password').type=type;

    document.getElementById('confirm_password').type=type;

});

</script>

</body>
</html>