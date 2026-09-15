<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | ANI-CARE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google reCAPTCHA v2 -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Leaflet -->
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    >

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

        .container {
            width: 100%;
            max-width: 100%;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
        }

        .card-body {
            word-wrap: break-word;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            min-height: 44px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
        }

        .btn-success {
            border-radius: 10px;
            min-height: 45px;
            font-weight: 600;
        }

        /* =========================
           CAPTCHA
        ========================= */

        .recaptcha-container {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 15px 0;
            overflow: visible;
        }

        .g-recaptcha {
            display: inline-block;
        }

        /* =========================
           FARMER FIELDS
        ========================= */

        #farmerFields {
            display: none;
        }

        #iccNameContainer {
            display: none;
        }

        /* =========================
           MAP
        ========================= */

        #registrationMap {
            height: 260px;
            border-radius: 14px;
            border: 1px solid rgba(0, 0, 0, 0.10);
        }

        .leaflet-container {
            background: #f8f9fa;
        }

        /* =========================
           MOBILE
        ========================= */

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

            h4 {
                font-size: 1.25rem;
            }

            .recaptcha-container {
                transform: scale(0.88);
                transform-origin: center;
                margin-top: 10px;
                margin-bottom: 10px;
            }
        }

        @media (max-width: 400px) {

            .recaptcha-container {
                transform: scale(0.78);
                transform-origin: center;
            }
        }
    </style>
</head>

<body>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">

            <div class="card shadow-sm">

                <div class="card-body p-4">

                    <!-- =========================
                         HEADER
                    ========================= -->

                    <h4 class="fw-bold text-success text-center mb-2">
                        Create Account
                    </h4>

                    <p class="text-center text-muted small mb-4">
                        Register as a Resident, Farmer, or Miller
                    </p>


                    <!-- =========================
                         VALIDATION ERRORS
                    ========================= -->

                    @if ($errors->any())

                        <div class="alert alert-danger">

                            <ul class="mb-0 small">

                                @foreach ($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <!-- =========================
                         REGISTRATION FORM
                    ========================= -->

                    <form
                        method="POST"
                        action="{{ route('register.post') }}"
                        id="registerForm"
                    >

                        @csrf


                        <!-- =========================
                             FULL NAME
                        ========================= -->

                        <div class="mb-3">

                            <label class="form-label">
                                Full Name
                            </label>

                            <input
                                type="text"
                                name="fullname"
                                class="form-control"
                                value="{{ old('fullname') }}"
                                required
                                minlength="3"
                                maxlength="100"
                                autocomplete="name"
                            >

                        </div>


                        <!-- =========================
                             USERNAME
                        ========================= -->

                        <div class="mb-3">

                            <label class="form-label">
                                Username
                            </label>

                            <input
                                type="text"
                                name="username"
                                class="form-control"
                                value="{{ old('username') }}"
                                required
                                minlength="4"
                                maxlength="30"
                                pattern="[A-Za-z0-9_-]+"
                                autocomplete="username"
                            >

                            <div class="form-text">
                                Letters, numbers, dash/underscore only.
                            </div>

                        </div>


                        <!-- =========================
                             EMAIL
                        ========================= -->

                        <div class="mb-3">

                            <label class="form-label">
                                Email (Required)
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                            >

                        </div>


                        <!-- =========================
                             MOBILE NUMBER
                        ========================= -->

                        <div class="mb-3">

                            <label class="form-label">
                                Mobile Number
                            </label>

                            <input
                                type="text"
                                name="mobile_number"
                                class="form-control"
                                value="{{ old('mobile_number') }}"
                                placeholder="09123456789"
                                maxlength="11"
                                pattern="09[0-9]{9}"
                                required
                                inputmode="numeric"
                                autocomplete="tel"
                            >

                            <div class="form-text">
                                Example: 09123456789
                            </div>

                        </div>


                        <!-- =========================
                             BARANGAY
                        ========================= -->

                        <div class="mb-3">

                            <label class="form-label">
                                Barangay
                            </label>

                            <select
                                name="barangay"
                                class="form-select"
                                required
                            >

                                <option
                                    value=""
                                    disabled
                                    {{ old('barangay') ? '' : 'selected' }}
                                >
                                    -- Select Barangay --
                                </option>

                                <option value="Bessang"
                                    {{ old('barangay') == 'Bessang' ? 'selected' : '' }}>
                                    Bessang
                                </option>

                                <option value="Binubungan"
                                    {{ old('barangay') == 'Binubungan' ? 'selected' : '' }}>
                                    Binubungan
                                </option>

                                <option value="Bulo"
                                    {{ old('barangay') == 'Bulo' ? 'selected' : '' }}>
                                    Bulo
                                </option>

                                <option value="Burot"
                                    {{ old('barangay') == 'Burot' ? 'selected' : '' }}>
                                    Burot
                                </option>

                                <option value="Capagaran (Brigida)"
                                    {{ old('barangay') == 'Capagaran (Brigida)' ? 'selected' : '' }}>
                                    Capagaran (Brigida)
                                </option>

                                <option value="Capalutan"
                                    {{ old('barangay') == 'Capalutan' ? 'selected' : '' }}>
                                    Capalutan
                                </option>

                                <option value="Cataratan"
                                    {{ old('barangay') == 'Cataratan' ? 'selected' : '' }}>
                                    Cataratan
                                </option>

                                <option value="Centro East (Poblacion)"
                                    {{ old('barangay') == 'Centro East (Poblacion)' ? 'selected' : '' }}>
                                    Centro East (Poblacion)
                                </option>

                                <option value="Centro West (Poblacion)"
                                    {{ old('barangay') == 'Centro West (Poblacion)' ? 'selected' : '' }}>
                                    Centro West (Poblacion)
                                </option>

                                <option value="Daan-Ili"
                                    {{ old('barangay') == 'Daan-Ili' ? 'selected' : '' }}>
                                    Daan-Ili
                                </option>

                                <option value="Dagupan"
                                    {{ old('barangay') == 'Dagupan' ? 'selected' : '' }}>
                                    Dagupan
                                </option>

                                <option value="Dalayap"
                                    {{ old('barangay') == 'Dalayap' ? 'selected' : '' }}>
                                    Dalayap
                                </option>

                                <option value="Gagaddangan"
                                    {{ old('barangay') == 'Gagaddangan' ? 'selected' : '' }}>
                                    Gagaddangan
                                </option>

                                <option value="Iringan"
                                    {{ old('barangay') == 'Iringan' ? 'selected' : '' }}>
                                    Iringan
                                </option>

                                <option value="Kapanickian Norte"
                                    {{ old('barangay') == 'Kapanickian Norte' ? 'selected' : '' }}>
                                    Kapanickian Norte
                                </option>

                                <option value="Kapanickian Sur"
                                    {{ old('barangay') == 'Kapanickian Sur' ? 'selected' : '' }}>
                                    Kapanickian Sur
                                </option>

                                <option value="Labben"
                                    {{ old('barangay') == 'Labben' ? 'selected' : '' }}>
                                    Labben
                                </option>

                                <option value="Maluyo"
                                    {{ old('barangay') == 'Maluyo' ? 'selected' : '' }}>
                                    Maluyo
                                </option>

                                <option value="Mapurao"
                                    {{ old('barangay') == 'Mapurao' ? 'selected' : '' }}>
                                    Mapurao
                                </option>

                                <option value="Matucay"
                                    {{ old('barangay') == 'Matucay' ? 'selected' : '' }}>
                                    Matucay
                                </option>

                                <option value="Nagattatan"
                                    {{ old('barangay') == 'Nagattatan' ? 'selected' : '' }}>
                                    Nagattatan
                                </option>

                                <option value="Pacac"
                                    {{ old('barangay') == 'Pacac' ? 'selected' : '' }}>
                                    Pacac
                                </option>

                                <option value="San Juan (Maguininango)"
                                    {{ old('barangay') == 'San Juan (Maguininango)' ? 'selected' : '' }}>
                                    San Juan (Maguininango)
                                </option>

                                <option value="Silagan"
                                    {{ old('barangay') == 'Silagan' ? 'selected' : '' }}>
                                    Silagan
                                </option>

                                <option value="Tamboli"
                                    {{ old('barangay') == 'Tamboli' ? 'selected' : '' }}>
                                    Tamboli
                                </option>

                                <option value="Tubel"
                                    {{ old('barangay') == 'Tubel' ? 'selected' : '' }}>
                                    Tubel
                                </option>

                                <option value="Utan"
                                    {{ old('barangay') == 'Utan' ? 'selected' : '' }}>
                                    Utan
                                </option>

                            </select>

                        </div>


                        <!-- =========================
                             ROLE
                        ========================= -->

                        <div class="mb-3">

                            <label class="form-label">
                                Register As
                            </label>

                            <select
                                name="role"
                                id="roleSelect"
                                class="form-select"
                                required
                            >

                                <option
                                    value=""
                                    disabled
                                    {{ old('role') ? '' : 'selected' }}
                                >
                                    -- Select Role --
                                </option>

                                <option
                                    value="resident"
                                    {{ old('role') === 'resident' ? 'selected' : '' }}
                                >
                                    Resident
                                </option>

                                <option
                                    value="farmer"
                                    {{ old('role') === 'farmer' ? 'selected' : '' }}
                                >
                                    Farmer
                                </option>

                                <option
                                    value="miller"
                                    {{ old('role') === 'miller' ? 'selected' : '' }}
                                >
                                    Miller
                                </option>

                            </select>

                            <small class="text-muted">
                                All accounts require admin approval before login.
                            </small>

                        </div>


                        <!-- =========================
                             FARMER FIELDS
                        ========================= -->

                        <div
                            id="farmerFields"
                        >

                            <!-- RSBSA -->

                            <div class="mb-3">

                                <label class="form-label">
                                    RSBSA No.
                                </label>

                                <input
                                    type="text"
                                    name="rsbsa_no"
                                    class="form-control"
                                    value="{{ old('rsbsa_no') }}"
                                    maxlength="50"
                                >

                            </div>


                            <!-- ICC/IP -->

                            <div class="mb-3">

                                <label class="form-label fw-bold">
                                    Part of Indigenous Cultural Community
                                    (ICC) / Indigenous People (IPs)
                                </label>

                                <div class="d-flex gap-4 mt-2">

                                    <div class="form-check">

                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="is_icc_ip"
                                            id="icc_yes"
                                            value="1"
                                            {{ old('is_icc_ip') == '1' ? 'checked' : '' }}
                                        >

                                        <label
                                            class="form-check-label"
                                            for="icc_yes"
                                        >
                                            Yes
                                        </label>

                                    </div>


                                    <div class="form-check">

                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="is_icc_ip"
                                            id="icc_no"
                                            value="0"
                                            {{ old('is_icc_ip', '0') == '0' ? 'checked' : '' }}
                                        >

                                        <label
                                            class="form-check-label"
                                            for="icc_no"
                                        >
                                            No
                                        </label>

                                    </div>

                                </div>

                            </div>


                            <!-- ICC NAME -->

                            <div
                                id="iccNameContainer"
                                class="mb-3"
                            >

                                <label class="form-label">
                                    Name of ICC/IP
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="icc_ip_name"
                                    id="icc_ip_name"
                                    value="{{ old('icc_ip_name') }}"
                                >

                            </div>


                            <!-- MEMBERSHIP -->

                            <div class="mb-3">

                                <label class="form-label fw-bold">
                                    Membership in Farmers / Irrigators
                                    Association / Cooperative / Organization
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="membership"
                                    value="{{ old('membership') }}"
                                >

                            </div>

                        </div>


                        <!-- =========================
                             PASSWORD
                        ========================= -->

                        <div class="row g-2">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Password
                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    required
                                    minlength="8"
                                    autocomplete="new-password"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Confirm Password
                                </label>

                                <input
                                    type="password"
                                    name="password_confirmation"
                                    class="form-control"
                                    required
                                    minlength="8"
                                    autocomplete="new-password"
                                >

                            </div>

                        </div>


                        <!-- =========================
                             PASSWORD REQUIREMENTS
                        ========================= -->

                        <div class="alert alert-info small">

                            Password must be at least 8 characters
                            and include
                            <b>uppercase</b>,
                            <b>lowercase</b>,
                            and a <b>number</b>.

                        </div>


                        <!-- =========================
                             GOOGLE reCAPTCHA
                        ========================= -->

                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                Security Verification
                            </label>

                            <div class="recaptcha-container">

                                <div
                                    class="g-recaptcha"
                                    data-sitekey="{{ config('services.recaptcha.site_key') }}"
                                ></div>

                            </div>

                            @error('recaptcha')

                                <div class="text-danger small text-center mt-2">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- =========================
                             REGISTER BUTTON
                        ========================= -->

                        <button
                            type="submit"
                            class="btn btn-success w-100"
                            id="registerButton"
                        >
                            Register
                        </button>


                        <!-- =========================
                             LINKS
                        ========================= -->

                        <div class="text-center mt-3">

                            <a href="{{ route('login') }}">
                                Already have an account?
                            </a>

                            <br>

                            <a
                                href="{{ route('main') }}"
                                class="small"
                            >
                                Back to Home
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================
     JAVASCRIPT
========================= -->

<script>

document.addEventListener("DOMContentLoaded", function () {

    const roleSelect =
        document.getElementById("roleSelect");

    const farmerFields =
        document.getElementById("farmerFields");

    const yes =
        document.getElementById("icc_yes");

    const no =
        document.getElementById("icc_no");

    const iccNameContainer =
        document.getElementById("iccNameContainer");


    /* =========================
       TOGGLE FARMER FIELDS
    ========================= */

    function toggleFarmerFields() {

        if (!roleSelect || !farmerFields) {
            return;
        }

        if (roleSelect.value === "farmer") {

            farmerFields.style.display = "block";

        } else {

            farmerFields.style.display = "none";

        }

    }


    /* =========================
       TOGGLE ICC FIELD
    ========================= */

    function toggleICCField() {

        if (!yes || !no || !iccNameContainer) {
            return;
        }

        if (yes.checked) {

            iccNameContainer.style.display = "block";

        } else {

            iccNameContainer.style.display = "none";

        }

    }


    /* =========================
       EVENTS
    ========================= */

    if (roleSelect) {

        roleSelect.addEventListener(
            "change",
            toggleFarmerFields
        );

    }


    if (yes) {

        yes.addEventListener(
            "change",
            toggleICCField
        );

    }


    if (no) {

        no.addEventListener(
            "change",
            toggleICCField
        );

    }


    /* =========================
       INITIAL STATE
    ========================= */

    toggleFarmerFields();
    toggleICCField();

});

</script>

</body>
</html>