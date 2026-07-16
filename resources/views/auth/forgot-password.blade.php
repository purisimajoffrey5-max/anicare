<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password | ANI-CARE</title>

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

                    <h3 class="text-center title mb-4">

                        Forgot Password

                    </h3>

                    <p class="text-center text-muted">

                        Enter your active email address.

                        We will send a 6-digit verification code.

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
                          action="{{ route('forgot.password.send') }}">

                        @csrf

                        <div class="mb-3">

                            <label class="form-label">

                                Active Email

                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="example@gmail.com"
                                required>

                        </div>

                        <button
                            class="btn btn-success w-100">

                            Send Verification Code

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

</body>
</html>