<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Login | ANI-CARE</title>

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <!-- =========================================
         GOOGLE reCAPTCHA v2
    ========================================== -->

    <script
        src="https://www.google.com/recaptcha/api.js"
        async
        defer
    ></script>


    <!-- =========================================
         BOOTSTRAP
    ========================================== -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- =========================================
         PAGE STYLE
    ========================================== -->

    <style>

        html {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        *,
        *::before,
        *::after {
            box-sizing: inherit;
        }


        body {
            min-height: 100vh;
            margin: 0;
            background: #f8f9fa;
        }


        img,
        video,
        iframe,
        svg,
        canvas {
            max-width: 100%;
            height: auto;
        }


        /* =========================================
           CONTAINER
        ========================================== */

        .container {
            width: 100%;
            max-width: 100%;
            padding-left: 1rem;
            padding-right: 1rem;
        }


        /* =========================================
           LOGIN CARD
        ========================================== */

        .login-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
        }


        .card-body {
            word-wrap: break-word;
        }


        /* =========================================
           FORM
        ========================================== */

        .form-control {
            min-height: 44px;
            border-radius: 10px;
        }


        .form-control:focus {
            border-color: #198754;
            box-shadow:
                0 0 0 0.2rem
                rgba(25, 135, 84, 0.15);
        }


        /* =========================================
           LOGIN BUTTON
        ========================================== */

        .login-button {
            min-height: 45px;
            border-radius: 10px;
            font-weight: 600;
        }


        /* =========================================
           CAPTCHA
        ========================================== */

        .recaptcha-section {
            width: 100%;
            margin-top: 15px;
            margin-bottom: 20px;
        }


        .recaptcha-label {
            display: block;
            font-weight: 600;
            margin-bottom: 10px;
        }


        .recaptcha-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: visible;
        }


        .g-recaptcha {
            display: inline-block;
        }


        /* =========================================
           LINKS
        ========================================== */

        .login-links a {
            text-decoration: none;
        }


        .login-links a:hover {
            text-decoration: underline;
        }


        /* =========================================
           MOBILE
        ========================================== */

        @media (max-width: 576px) {

            .container {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }


            .py-5 {
                padding-top: 1.5rem !important;
                padding-bottom: 1.5rem !important;
            }


            .card-body {
                padding: 1.25rem !important;
            }


            .recaptcha-wrapper {
                transform: scale(0.88);
                transform-origin: center;
                margin-top: 5px;
                margin-bottom: 5px;
            }

        }


        @media (max-width: 400px) {

            .recaptcha-wrapper {
                transform: scale(0.78);
                transform-origin: center;
            }

        }

    </style>

</head>


<body>


<!-- =========================================
     MAIN CONTAINER
========================================== -->

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4">


            <!-- =========================================
                 LOGIN CARD
            ========================================== -->

            <div class="card login-card shadow-sm">

                <div class="card-body p-4">


                    <!-- =========================================
                         TITLE
                    ========================================== -->

                    <h4 class="fw-bold text-success text-center mb-3">

                        ANI-CARE Login

                    </h4>


                    <!-- =========================================
                         SUCCESS MESSAGE
                    ========================================== -->

                    <?php if(session('success')): ?>

                        <div class="alert alert-success text-center">

                            <?php echo e(session('success')); ?>


                        </div>

                    <?php endif; ?>


                    <!-- =========================================
                         LOGIN ERROR
                    ========================================== -->

                    <?php if($errors->has('login')): ?>

                        <div class="alert alert-danger">

                            <?php echo e($errors->first('login')); ?>


                        </div>

                    <?php elseif($errors->any()): ?>

                        <div class="alert alert-danger">

                            <?php echo e($errors->first()); ?>


                        </div>

                    <?php endif; ?>


                    <!-- =========================================
                         LOGIN FORM
                    ========================================== -->

                    <form
                        method="POST"
                        action="<?php echo e(route('login.post')); ?>"
                        id="loginForm"
                    >

                        <?php echo csrf_field(); ?>


                        <!-- =========================================
                             USERNAME
                        ========================================== -->

                        <div class="mb-3">

                            <label
                                for="username"
                                class="form-label"
                            >
                                Username
                            </label>


                            <input
                                type="text"
                                id="username"
                                name="username"
                                class="form-control"
                                value="<?php echo e(old('username')); ?>"
                                required
                                autocomplete="username"
                            >

                        </div>


                        <!-- =========================================
                             PASSWORD
                        ========================================== -->

                        <div class="mb-3">

                            <label
                                for="password"
                                class="form-label"
                            >
                                Password
                            </label>


                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                required
                                autocomplete="current-password"
                            >

                        </div>


                        <!-- =========================================
                             GOOGLE reCAPTCHA
                        ========================================== -->

                        <div class="recaptcha-section">


                            <label class="recaptcha-label">

                                Security Verification

                            </label>


                            <div class="recaptcha-wrapper">

                                <div
    class="g-recaptcha"
    data-sitekey="<?php echo e(config('services.recaptcha.site_key')); ?>"
    style="min-height: 78px;"
></div>

<p style="font-size:12px; color:#666;">
    CAPTCHA configured:
    <?php echo e(config('services.recaptcha.site_key') ? 'YES' : 'NO'); ?>

</p>

                            </div>


                            <!-- CAPTCHA ERROR -->

                            <?php $__errorArgs = ['login'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                                <div class="text-danger small text-center mt-2">

                                    <?php echo e($message); ?>


                                </div>

                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>


                            <?php $__errorArgs = ['recaptcha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                                <div class="text-danger small text-center mt-2">

                                    <?php echo e($message); ?>


                                </div>

                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>


                        </div>


                        <!-- =========================================
                             LOGIN BUTTON
                        ========================================== -->

                        <button
                            type="submit"
                            class="btn btn-success w-100 login-button"
                            id="loginButton"
                        >

                            Login

                        </button>


                        <!-- =========================================
                             LINKS
                        ========================================== -->

                        <div class="text-center mt-3 login-links">


                            <!-- FORGOT PASSWORD -->

                            <a
                                href="<?php echo e(route('forgot.password')); ?>"
                                class="text-danger fw-semibold"
                            >

                                Forgot Password?

                            </a>


                            <br>
                            <br>


                            <!-- CREATE ACCOUNT -->

                            <a href="<?php echo e(route('register')); ?>">

                                Create Account

                            </a>


                            <span class="text-muted mx-1">

                                |

                            </span>


                            <!-- HOME -->

                            <a href="<?php echo e(route('main')); ?>">

                                Back to Home

                            </a>


                        </div>


                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================================
     JAVASCRIPT
========================================== -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {

        const loginForm =
            document.getElementById("loginForm");

        const loginButton =
            document.getElementById("loginButton");


        /*
        =========================================
        PREVENT DOUBLE SUBMISSION
        =========================================
        */

        if (loginForm && loginButton) {

            loginForm.addEventListener(
                "submit",
                function () {

                    loginButton.disabled = true;

                    loginButton.innerHTML =
                        "Logging in...";

                }
            );

        }

    }
);

</script>


</body>

</html><?php /**PATH C:\xampp\htdocs\Allacapan_Anicare\resources\views/auth/login.blade.php ENDPATH**/ ?>