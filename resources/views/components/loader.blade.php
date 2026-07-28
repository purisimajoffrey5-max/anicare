<div id="appLoader" role="status" aria-live="polite">

    <div class="shop-loader-card">

        <img
            src="{{ asset('images/logo.jpg') }}"
            alt="ANI-CARE Logo"
            class="shop-loader-logo"
        >

        <h2>ANI-CARE ALLACAPAN</h2>

        <p>Please wait while we prepare your dashboard.</p>

        <div class="shop-spinner" aria-hidden="true"></div>

        <div class="shop-loading-text">
            Loading
            <span class="shop-dots">
                <span>.</span>
                <span>.</span>
                <span>.</span>
            </span>
        </div>

    </div>

</div>

<style>
#appLoader {
    position: fixed;
    inset: 0;
    z-index: 999999;
    width: 100%;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    background: rgba(255, 255, 255, 0.97);
    font-family: "Segoe UI", Arial, sans-serif;
    opacity: 1;
    visibility: visible;
    transition:
        opacity 0.45s ease,
        visibility 0.45s ease;
}

.shop-loader-card {
    width: min(100%, 380px);
    padding: 34px 30px;
    text-align: center;
    border: 1px solid #e8ecef;
    border-radius: 18px;
    background: #ffffff;
    box-shadow:
        0 12px 35px rgba(0, 0, 0, 0.1);
    animation: shopLoaderEnter 0.45s ease;
}

.shop-loader-logo {
    width: 105px;
    height: 105px;
    display: block;
    margin: 0 auto 20px;
    padding: 7px;
    object-fit: contain;
    border-radius: 50%;
    background: #ffffff;
    border: 1px solid #e8ecef;
    box-shadow:
        0 6px 18px rgba(0, 0, 0, 0.12);
}

.shop-loader-card h2 {
    margin: 0;
    color: #198754;
    font-size: 23px;
    font-weight: 750;
    letter-spacing: 0.4px;
}

.shop-loader-card p {
    margin: 10px auto 22px;
    max-width: 290px;
    color: #6c757d;
    font-size: 14px;
    line-height: 1.6;
}

.shop-spinner {
    width: 42px;
    height: 42px;
    margin: 0 auto;
    border: 4px solid #dceee4;
    border-top-color: #198754;
    border-radius: 50%;
    animation: shopSpinnerRotate 0.9s linear infinite;
}

.shop-loading-text {
    margin-top: 15px;
    color: #555555;
    font-size: 13px;
    font-weight: 600;
}

.shop-dots span {
    display: inline-block;
    animation: shopDotBlink 1.2s infinite;
}

.shop-dots span:nth-child(2) {
    animation-delay: 0.2s;
}

.shop-dots span:nth-child(3) {
    animation-delay: 0.4s;
}

#appLoader.hide-loader {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}

#appLoader.hide-loader .shop-loader-card {
    opacity: 0;
    transform: translateY(-8px);
    transition:
        opacity 0.4s ease,
        transform 0.4s ease;
}

@keyframes shopSpinnerRotate {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}

@keyframes shopDotBlink {
    0%,
    80%,
    100% {
        opacity: 0.25;
    }

    40% {
        opacity: 1;
    }
}

@keyframes shopLoaderEnter {
    from {
        opacity: 0;
        transform: translateY(14px) scale(0.98);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@media (max-width: 576px) {
    #appLoader {
        padding: 15px;
    }

    .shop-loader-card {
        padding: 28px 20px;
        border-radius: 15px;
    }

    .shop-loader-logo {
        width: 90px;
        height: 90px;
    }

    .shop-loader-card h2 {
        font-size: 20px;
    }

    .shop-loader-card p {
        font-size: 13px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .shop-loader-card,
    .shop-spinner,
    .shop-dots span {
        animation: none;
    }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const loader = document.getElementById("appLoader");

    if (!loader) {
        return;
    }

    document.body.style.overflow = "hidden";

    const loaderDuration = 2200;

    setTimeout(function () {

        loader.classList.add("hide-loader");
        document.body.style.overflow = "";

        setTimeout(function () {

            if (loader.parentNode) {
                loader.remove();
            }

        }, 500);

    }, loaderDuration);

});
</script>