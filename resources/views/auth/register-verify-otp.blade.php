<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verify Registration OTP | ANI-CARE</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    >

    <style>
        body {
            background: #f4f7f8;
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }

        .verify-card {
            border: none;
            border-radius: 18px;
            overflow: hidden;
        }

        .title {
            color: #198754;
            font-weight: 700;
        }

        .otp-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 18px;
            border-radius: 50%;
            background: #e9f7ef;
            color: #198754;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
        }

        .otp-box {
            font-size: 28px;
            text-align: center;
            letter-spacing: 10px;
            font-weight: bold;
            min-height: 58px;
            border-radius: 10px;
        }

        .otp-box:focus {
            border-color: #198754;
            box-shadow: 0 0 0 .2rem rgba(25, 135, 84, .15);
        }

        .btn-main {
            min-height: 48px;
            border-radius: 9px;
            font-weight: 600;
        }

        .resend-wrapper {
            border-top: 1px solid #e9ecef;
            margin-top: 25px;
            padding-top: 20px;
        }

        .countdown {
            font-weight: 700;
            color: #198754;
        }

        #resendButton:disabled {
            cursor: not-allowed;
            opacity: .65;
        }

        @media (max-width: 576px) {
            .otp-box {
                font-size: 23px;
                letter-spacing: 7px;
            }
        }
    </style>
</head>

<body>

@php
    /*
    |--------------------------------------------------------------------------
    | RESEND COUNTDOWN
    |--------------------------------------------------------------------------
    */

    $sentAt = session('registration_otp_sent_at');

    $remainingSeconds = 0;

    if ($sentAt) {
        $elapsed = now()->timestamp - (int) $sentAt;
        $remainingSeconds = max(0, 60 - $elapsed);
    }
@endphp


<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-6 col-lg-5">

            <div class="card verify-card shadow">

                <div class="card-body p-4 p-md-5">

                    <div class="otp-icon">
                        <i class="bi bi-envelope-check"></i>
                    </div>

                    <h3 class="title text-center mb-2">
                        Verify Your Email
                    </h3>

                    <p class="text-center text-muted mb-4">
                        Please check your inbox and enter the
                        6-digit verification code sent to your email.
                    </p>


                    {{-- SUCCESS --}}
                    @if(session('success'))

                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-1"></i>
                            {{ session('success') }}
                        </div>

                    @endif


                    {{-- ERRORS --}}
                    @if($errors->any())

                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            {{ $errors->first() }}
                        </div>

                    @endif


                    {{-- VERIFY OTP --}}
                    <form
                        method="POST"
                        action="{{ route('register.otp.verify') }}"
                    >

                        @csrf


                        {{-- EMAIL --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Email Address
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    value="{{ session('pending_registration_email') }}"
                                    readonly
                                >

                            </div>

                        </div>


                        {{-- OTP --}}
                        <div class="mb-4">

                            <label class="form-label">
                                Verification Code
                            </label>

                            <input
                                type="text"
                                name="otp"
                                id="otp"
                                maxlength="6"
                                minlength="6"
                                inputmode="numeric"
                                pattern="[0-9]{6}"
                                autocomplete="one-time-code"
                                class="form-control otp-box"
                                placeholder="000000"
                                required
                            >

                            <div class="form-text text-center mt-2">
                                The verification code expires after 1 minutes.
                            </div>

                        </div>


                        <button
                            type="submit"
                            class="btn btn-success btn-main w-100"
                        >
                            <i class="bi bi-shield-check me-1"></i>
                            Verify Code
                        </button>

                    </form>


                    {{-- RESEND OTP --}}
                    <div class="resend-wrapper text-center">

                        <div class="text-muted small mb-2">
                            Didn't receive the verification code?
                        </div>


                        <form
                            method="POST"
                            action="{{ route('register.otp.resend') }}"
                            id="resendForm"
                        >

                            @csrf

                            <button
                                type="submit"
                                id="resendButton"
                                class="btn btn-outline-success"
                                {{ $remainingSeconds > 0 ? 'disabled' : '' }}
                            >

                                <i class="bi bi-arrow-clockwise"></i>

                                <span id="resendButtonText">
                                    @if($remainingSeconds > 0)
                                        Resend OTP in {{ $remainingSeconds }}s
                                    @else
                                        Resend OTP
                                    @endif
                                </span>

                            </button>

                        </form>


                        <div
                            id="timerMessage"
                            class="small text-muted mt-2"
                            style="{{ $remainingSeconds > 0 ? '' : 'display:none;' }}"
                        >

                            You can request another code after

                            <span
                                id="countdown"
                                class="countdown"
                            >
                                {{ $remainingSeconds }}
                            </span>

                            seconds.

                        </div>

                    </div>


                    {{-- BACK --}}
                    <div class="text-center mt-4">

                        <a
                            href="{{ route('register') }}"
                            class="text-decoration-none"
                        >
                            <i class="bi bi-arrow-left"></i>
                            Back to registration
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    let remaining =
        {{ (int) $remainingSeconds }};

    const resendButton =
        document.getElementById('resendButton');

    const resendButtonText =
        document.getElementById('resendButtonText');

    const timerMessage =
        document.getElementById('timerMessage');

    const countdown =
        document.getElementById('countdown');

    const otpInput =
        document.getElementById('otp');


    /*
    |--------------------------------------------------------------------------
    | OTP INPUT - NUMBERS ONLY
    |--------------------------------------------------------------------------
    */

    otpInput.addEventListener('input', function () {

        this.value =
            this.value
                .replace(/\D/g, '')
                .slice(0, 6);

    });


    /*
    |--------------------------------------------------------------------------
    | 60 SECOND RESEND TIMER
    |--------------------------------------------------------------------------
    */

    if (remaining > 0) {

        resendButton.disabled = true;
        timerMessage.style.display = 'block';

        const timer = setInterval(function () {

            remaining--;

            if (remaining > 0) {

                countdown.textContent =
                    remaining;

                resendButtonText.textContent =
                    'Resend OTP in ' +
                    remaining +
                    's';

            } else {

                clearInterval(timer);

                resendButton.disabled =
                    false;

                resendButtonText.textContent =
                    'Resend OTP';

                timerMessage.style.display =
                    'none';

            }

        }, 1000);

    }

});
</script>

</body>
</html>