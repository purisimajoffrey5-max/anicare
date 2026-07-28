<div id="landingSplash" class="landing-splash" role="status" aria-label="Loading ANI-CARE">

    <div class="splash-sun"></div>

    <div class="splash-cloud splash-cloud-one"></div>
    <div class="splash-cloud splash-cloud-two"></div>

    <div class="splash-content">

        <div class="splash-logo-box">

            <div class="splash-logo-ring"></div>

            <img
                src="{{ asset('images/logo.jpg') }}"
                alt="ANI-CARE Allacapan Logo"
                class="splash-logo"
            >

        </div>

        <div class="splash-subtitle">
            Municipal Agriculture Platform
        </div>

        <h1>ANI-CARE</h1>

        <h2>ALLACAPAN</h2>

        <p class="splash-description">
            Connecting farmers, millers, residents, and the local government.
        </p>

        <div class="splash-services">

            <div class="splash-service">
                <span class="service-icon">🌾</span>
                <span>Farmers</span>
            </div>

            <div class="service-line"></div>

            <div class="splash-service">
                <span class="service-icon">⚙️</span>
                <span>Millers</span>
            </div>

            <div class="service-line"></div>

            <div class="splash-service">
                <span class="service-icon">🏠</span>
                <span>Residents</span>
            </div>

        </div>

        <div class="splash-progress-area">

            <div class="splash-progress-track">
                <div id="splashProgress" class="splash-progress-fill"></div>
            </div>

            <div class="splash-progress-info">

                <span id="splashStatus">
                    Preparing agricultural services
                </span>

                <span id="splashPercent">0%</span>

            </div>

        </div>

    </div>

    <div class="rice-field">

        <div class="rice rice-one">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <div class="rice rice-two">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <div class="rice rice-three">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <div class="rice rice-four">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <div class="rice rice-five">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <div class="field-layer field-layer-one"></div>
        <div class="field-layer field-layer-two"></div>

    </div>

    <div class="splash-footer">
        Municipal Agriculture Office • Allacapan, Cagayan
    </div>

</div>

<style>
#landingSplash {
    --splash-green: #198754;
    --splash-dark-green: #07552f;
    --splash-light-green: #48c987;
    --splash-yellow: #ffc107;
    --splash-white: #ffffff;

    position: fixed;
    inset: 0;
    z-index: 9999999;
    width: 100%;
    min-height: 100vh;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 25px;
    font-family: "Segoe UI", Arial, sans-serif;
    color: var(--splash-white);
    background:
        radial-gradient(
            circle at 50% 15%,
            rgba(255, 255, 255, 0.14),
            transparent 30%
        ),
        linear-gradient(
            180deg,
            #0a7043 0%,
            #198754 55%,
            #0b5d36 100%
        );
    opacity: 1;
    visibility: visible;
    transition:
        opacity 0.7s ease,
        visibility 0.7s ease;
}

#landingSplash.hide-splash {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}

.splash-sun {
    position: absolute;
    top: 8%;
    right: 10%;
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: rgba(255, 193, 7, 0.22);
    box-shadow:
        0 0 40px rgba(255, 193, 7, 0.2),
        0 0 90px rgba(255, 193, 7, 0.14);
    animation: splashSunPulse 4s ease-in-out infinite;
}

.splash-cloud {
    position: absolute;
    width: 160px;
    height: 45px;
    border-radius: 50px;
    background: rgba(255, 255, 255, 0.08);
    filter: blur(1px);
}

.splash-cloud::before,
.splash-cloud::after {
    content: "";
    position: absolute;
    bottom: 0;
    border-radius: 50%;
    background: inherit;
}

.splash-cloud::before {
    left: 25px;
    width: 65px;
    height: 65px;
}

.splash-cloud::after {
    right: 25px;
    width: 80px;
    height: 80px;
}

.splash-cloud-one {
    top: 18%;
    left: -180px;
    animation: splashCloudMove 18s linear infinite;
}

.splash-cloud-two {
    top: 32%;
    left: -220px;
    transform: scale(0.7);
    animation: splashCloudMove 24s linear 5s infinite;
}

.splash-content {
    position: relative;
    z-index: 10;
    width: min(100%, 560px);
    margin-bottom: 80px;
    text-align: center;
    animation: splashContentEnter 0.8s ease;
}

.splash-logo-box {
    position: relative;
    width: 155px;
    height: 155px;
    margin: 0 auto 24px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.splash-logo-ring {
    position: absolute;
    inset: 0;
    border: 3px solid rgba(255, 255, 255, 0.17);
    border-top-color: var(--splash-yellow);
    border-right-color: #ffffff;
    border-radius: 50%;
    animation: splashRingRotate 5s linear infinite;
}

.splash-logo {
    position: relative;
    z-index: 2;
    width: 125px;
    height: 125px;
    padding: 8px;
    object-fit: contain;
    border-radius: 50%;
    background: #ffffff;
    box-shadow:
        0 14px 35px rgba(0, 0, 0, 0.3),
        0 0 0 7px rgba(255, 255, 255, 0.1);
    animation: splashLogoFloat 3s ease-in-out infinite;
}

.splash-subtitle {
    display: inline-block;
    margin-bottom: 13px;
    padding: 7px 15px;
    border: 1px solid rgba(255, 255, 255, 0.22);
    border-radius: 50px;
    background: rgba(255, 255, 255, 0.08);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}

.splash-content h1 {
    margin: 0;
    font-size: clamp(42px, 8vw, 64px);
    line-height: 1;
    font-weight: 850;
    letter-spacing: 3px;
    text-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
}

.splash-content h2 {
    margin: 9px 0 17px;
    color: var(--splash-yellow);
    font-size: 20px;
    font-weight: 750;
    letter-spacing: 10px;
}

.splash-description {
    max-width: 470px;
    margin: 0 auto;
    color: rgba(255, 255, 255, 0.82);
    font-size: 15px;
    line-height: 1.7;
}

.splash-services {
    margin-top: 25px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 13px;
}

.splash-service {
    display: flex;
    align-items: center;
    gap: 6px;
    color: rgba(255, 255, 255, 0.85);
    font-size: 12px;
    font-weight: 600;
}

.service-icon {
    font-size: 18px;
}

.service-line {
    width: 25px;
    height: 1px;
    background: rgba(255, 255, 255, 0.23);
}

.splash-progress-area {
    width: min(100%, 430px);
    margin: 28px auto 0;
}

.splash-progress-track {
    width: 100%;
    height: 8px;
    overflow: hidden;
    border-radius: 50px;
    background: rgba(255, 255, 255, 0.18);
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.16);
}

.splash-progress-fill {
    position: relative;
    width: 0;
    height: 100%;
    border-radius: inherit;
    background: linear-gradient(
        90deg,
        #ffffff,
        #8fe0b2,
        #ffc107
    );
    box-shadow: 0 0 13px rgba(255, 193, 7, 0.45);
    transition: width 0.12s linear;
}

.splash-progress-fill::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.75),
        transparent
    );
    animation: splashProgressShine 2s linear infinite;
}

.splash-progress-info {
    margin-top: 11px;
    display: flex;
    justify-content: space-between;
    gap: 15px;
    color: rgba(255, 255, 255, 0.72);
    font-size: 11px;
}

#splashPercent {
    min-width: 35px;
    color: var(--splash-yellow);
    font-weight: 700;
}

.rice-field {
    position: absolute;
    z-index: 3;
    right: 0;
    bottom: 0;
    left: 0;
    height: 165px;
    overflow: hidden;
    pointer-events: none;
}

.field-layer {
    position: absolute;
    right: -5%;
    bottom: -80px;
    left: -5%;
    height: 155px;
    border-radius: 50% 50% 0 0;
}

.field-layer-one {
    z-index: 1;
    background: #0b6339;
    transform: rotate(-2deg);
}

.field-layer-two {
    z-index: 2;
    bottom: -105px;
    background: #064627;
    transform: rotate(2deg);
}

.rice {
    position: absolute;
    z-index: 4;
    bottom: 25px;
    width: 4px;
    height: 95px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.16);
    transform-origin: bottom center;
    animation: riceSway 3.5s ease-in-out infinite;
}

.rice span {
    position: absolute;
    width: 25px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255, 193, 7, 0.48);
}

.rice span:nth-child(1) {
    top: 18px;
    left: -20px;
    transform: rotate(30deg);
}

.rice span:nth-child(2) {
    top: 38px;
    left: 1px;
    transform: rotate(-30deg);
}

.rice span:nth-child(3) {
    top: 58px;
    left: -20px;
    transform: rotate(30deg);
}

.rice-one {
    left: 8%;
}

.rice-two {
    left: 25%;
    height: 115px;
    animation-delay: 0.4s;
}

.rice-three {
    left: 50%;
    height: 105px;
    animation-delay: 0.8s;
}

.rice-four {
    left: 72%;
    height: 120px;
    animation-delay: 0.2s;
}

.rice-five {
    left: 91%;
    animation-delay: 0.6s;
}

.splash-footer {
    position: absolute;
    z-index: 10;
    bottom: 14px;
    width: 100%;
    padding: 0 20px;
    text-align: center;
    color: rgba(255, 255, 255, 0.5);
    font-size: 9px;
    letter-spacing: 1px;
    text-transform: uppercase;
}

@keyframes splashRingRotate {
    from {
        transform: rotate(0);
    }

    to {
        transform: rotate(360deg);
    }
}

@keyframes splashLogoFloat {
    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-6px);
    }
}

@keyframes splashContentEnter {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.97);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes splashCloudMove {
    from {
        left: -220px;
    }

    to {
        left: calc(100% + 220px);
    }
}

@keyframes riceSway {
    0%,
    100% {
        transform: rotate(-4deg);
    }

    50% {
        transform: rotate(5deg);
    }
}

@keyframes splashProgressShine {
    from {
        transform: translateX(-100%);
    }

    to {
        transform: translateX(100%);
    }
}

@keyframes splashSunPulse {
    0%,
    100% {
        opacity: 0.65;
        transform: scale(0.95);
    }

    50% {
        opacity: 1;
        transform: scale(1.08);
    }
}

@media (max-width: 576px) {
    #landingSplash {
        padding: 18px;
    }

    .splash-content {
        margin-bottom: 75px;
    }

    .splash-logo-box {
        width: 125px;
        height: 125px;
        margin-bottom: 20px;
    }

    .splash-logo {
        width: 100px;
        height: 100px;
    }

    .splash-content h1 {
        font-size: 40px;
    }

    .splash-content h2 {
        font-size: 15px;
        letter-spacing: 7px;
    }

    .splash-description {
        font-size: 13px;
    }

    .splash-services {
        gap: 8px;
    }

    .splash-service {
        font-size: 9px;
    }

    .service-icon {
        font-size: 15px;
    }

    .service-line {
        width: 12px;
    }

    .splash-progress-info {
        font-size: 9px;
    }

    .rice-field {
        height: 125px;
    }

    .splash-sun {
        width: 75px;
        height: 75px;
    }
}

@media (max-height: 700px) {
    .splash-content {
        transform: scale(0.83);
    }

    .rice-field {
        height: 100px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .splash-logo-ring,
    .splash-logo,
    .splash-cloud,
    .rice,
    .splash-sun,
    .splash-progress-fill::after {
        animation: none;
    }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const splash = document.getElementById("landingSplash");
    const progress = document.getElementById("splashProgress");
    const percent = document.getElementById("splashPercent");
    const status = document.getElementById("splashStatus");

    if (!splash) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Ipakita lamang nang isang beses bawat browser tab
    |--------------------------------------------------------------------------
    */

    if (sessionStorage.getItem("anicareLandingSplashShown") === "yes") {
        splash.remove();
        return;
    }

    sessionStorage.setItem("anicareLandingSplashShown", "yes");

    document.body.style.overflow = "hidden";

    const statusMessages = [
        {
            at: 0,
            message: "Preparing agricultural services"
        },
        {
            at: 25,
            message: "Connecting farmers and millers"
        },
        {
            at: 50,
            message: "Loading marketplace information"
        },
        {
            at: 75,
            message: "Preparing ANI-CARE Allacapan"
        },
        {
            at: 95,
            message: "Welcome to ANI-CARE"
        }
    ];

    let currentProgress = 0;
    let messageIndex = 0;

    const progressTimer = setInterval(function () {

        currentProgress++;

        if (progress) {
            progress.style.width = currentProgress + "%";
        }

        if (percent) {
            percent.textContent = currentProgress + "%";
        }

        const nextMessage = statusMessages[messageIndex];

        if (
            nextMessage &&
            currentProgress >= nextMessage.at
        ) {
            status.textContent = nextMessage.message;
            messageIndex++;
        }

        if (currentProgress >= 100) {

            clearInterval(progressTimer);

            setTimeout(function () {

                splash.classList.add("hide-splash");
                document.body.style.overflow = "";

                setTimeout(function () {
                    splash.remove();
                }, 750);

            }, 450);

        }

    }, 28);

});
</script>