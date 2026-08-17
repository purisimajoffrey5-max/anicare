<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>My Profile | Resident</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    >

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f8;
            color: #212529;
        }

        .profile-container {
            max-width: 750px;
            margin: 0 auto;
            padding: 30px 18px 50px;
        }

        .profile-title {
            color: #198754;
            font-weight: 700;
            margin: 0;
        }

        .profile-subtitle {
            color: #6c757d;
            font-size: 14px;
        }

        .profile-card {
            border: none;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .08);
        }

        .profile-header-box {
            background: #f0faf4;
            border: 1px solid #d4edda;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 25px;
        }

        .profile-header-box i {
            color: #198754;
            font-size: 24px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 7px;
        }

        .form-control {
            min-height: 48px;
            border-radius: 9px;
        }

        .form-control:focus {
            border-color: #198754;
            box-shadow: 0 0 0 .2rem rgba(25, 135, 84, .15);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .input-group-text {
            background: #f8f9fa;
            border-radius: 9px 0 0 9px;
            min-width: 48px;
            justify-content: center;
        }

        .btn-save {
            min-height: 50px;
            border-radius: 9px;
            font-size: 16px;
            font-weight: 600;
        }

        .btn-back {
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .profile-container {
                padding: 20px 12px 40px;
            }

            .profile-card .card-body {
                padding: 20px !important;
            }

            .profile-title {
                font-size: 25px;
            }
        }
    </style>
</head>

<body>

<div class="profile-container">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="profile-title">
                <i class="bi bi-person-circle"></i>
                My Profile
            </h2>

            <div class="profile-subtitle">
                Update your account information
            </div>
        </div>

        <a href="{{ route('resident.dashboard') }}"
           class="btn btn-outline-success btn-sm btn-back">

            <i class="bi bi-arrow-left"></i>
            Back

        </a>
    </div>


    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show" role="alert">

            <i class="bi bi-check-circle-fill me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- VALIDATION ERRORS --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle-fill"></i>
                Please check the following:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <div class="card profile-card">

        <div class="card-body p-4">

            <div class="profile-header-box">

                <div class="d-flex align-items-center gap-3">

                    <i class="bi bi-person-vcard"></i>

                    <div>
                        <div class="fw-bold">
                            Resident Information
                        </div>

                        <div class="small text-muted">
                            Keep your personal and delivery information updated.
                        </div>
                    </div>

                </div>

            </div>


            <form method="POST"
                  action="{{ route('resident.profile.update') }}">

                @csrf

                {{-- FULL NAME --}}
                <div class="mb-4">

                    <label for="fullname"
                           class="form-label">

                        Full Name

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>

                        <input
                            type="text"
                            id="fullname"
                            name="fullname"
                            class="form-control"
                            value="{{ old('fullname', $user->fullname ?? '') }}"
                            placeholder="Enter your full name"
                            required
                        >

                    </div>

                    @error('fullname')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- EMAIL --}}
                <div class="mb-4">

                    <label for="email"
                           class="form-label">

                        Email Address

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email', $user->email ?? '') }}"
                            placeholder="example@email.com"
                        >

                    </div>

                    @error('email')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- MOBILE NUMBER --}}
                <div class="mb-4">

                    <label for="mobile_number"
                           class="form-label">

                        Mobile Number

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-phone"></i>
                        </span>

                        <input
                            type="text"
                            id="mobile_number"
                            name="mobile_number"
                            class="form-control"
                            value="{{ old(
                                'mobile_number',
                                $user->mobile_number
                                ?? $user->contact_number
                                ?? $user->phone
                                ?? ''
                            ) }}"
                            placeholder="09XXXXXXXXX"
                            maxlength="20"
                            required
                        >

                    </div>

                    @error('mobile_number')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- ADDRESS --}}
                <div class="mb-4">

                    <label for="address"
                           class="form-label">

                        Address

                    </label>

                    <div class="input-group">

                        <span class="input-group-text align-items-start pt-3">
                            <i class="bi bi-geo-alt"></i>
                        </span>

                        <textarea
                            id="address"
                            name="address"
                            class="form-control"
                            placeholder="Enter your complete address"
                            required
                        >{{ old('address', $user->address ?? '') }}</textarea>

                    </div>

                    <div class="form-text">
                        Example: Barangay, Municipality, Province
                    </div>

                    @error('address')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- SAVE BUTTON --}}
                <button type="submit"
                        class="btn btn-success btn-save w-100">

                    <i class="bi bi-check-circle me-1"></i>
                    Save Changes

                </button>

            </form>

        </div>

    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>