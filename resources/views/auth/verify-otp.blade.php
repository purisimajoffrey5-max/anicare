<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <title>Verify OTP | ANI-CARE</title>

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">

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

        .otp-box{

            font-size:28px;

            text-align:center;

            letter-spacing:12px;

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

Verify Email

</h3>

<p class="text-center text-muted">

Please check your email.

<br>

Enter the 6-digit verification code.

</p>

@if(session('success'))

<div class="alert alert-success">

{{ session('success') }}

</div>

@endif

@if($errors->any())

<div class="alert alert-danger">

{{ $errors->first() }}

</div>

@endif

<form method="POST"

action="{{ route('otp.verify') }}">

@csrf

<div class="mb-3">

<label class="form-label">

Email Address

</label>

<input
type="email"
name="email"
class="form-control"
value="{{ session('email') }}"
readonly>

</div>

<div class="mb-4">

<label class="form-label">

Verification Code

</label>

<input
type="text"
name="otp"
maxlength="6"
class="form-control otp-box"
placeholder="000000"
required>

</div>

<button
class="btn btn-success w-100">

Verify Code

</button>

</form>

<div class="text-center mt-4">

<a href="{{ route('forgot.password') }}">

Resend Code

</a>

</div>

</div>

</div>

</div>

</div>

</div>

</body>
</html>