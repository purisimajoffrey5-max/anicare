<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ANI-CARE ALLACAPAN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { scroll-behavior: smooth; background:#fff; }

        .hero {
            background: linear-gradient(rgba(0,0,0,.55), rgba(0,0,0,.55)),
            url('{{ asset("images/hero-rice.jpg") }}') center/cover no-repeat;
            min-height: 90vh;
            display: flex;
            align-items: center;
            color: #fff;
            text-align: center;
        }

        .section-title {
            font-weight: 800;
            letter-spacing: .2px;
        }

        .feature-card {
            border-radius: 12px;
            transition: .2s;
            border: 1px solid rgba(0,0,0,.05);
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,.08);
        }

        .about-split-img{
            border-radius: 14px;
            box-shadow: 0 12px 28px rgba(16,24,40,.12);
            object-fit: cover;
            width: 100%;
            height: 310px;
        }

        .pill{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 7px 12px;
            border-radius: 999px;
            background:#eaf7ef;
            color:#198754;
            font-size: .85rem;
            font-weight: 600;
        }

        footer {
            background: #198754;
            color: #fff;
        }

        .navbar { box-shadow: 0 8px 18px rgba(0,0,0,.06); }
        .nav-link { opacity: .95; }
        .nav-link:hover { opacity: 1; }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#home">ANI-CARE ALLACAPAPAN</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="nav" class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <li class="nav-item"><a href="#home" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="#about" class="nav-link">About</a></li>
                <li class="nav-item"><a href="#features" class="nav-link">Features</a></li>
                <li class="nav-item"><a href="#modules" class="nav-link">Modules</a></li>
                <li class="nav-item"><a href="#contact" class="nav-link">Contact</a></li>

                <li class="nav-item ms-lg-2">
                    <a href="{{ route('login') }}" class="btn btn-warning btn-sm">Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- HERO -->
<section id="home" class="hero">
    <div class="container">
        <h1 class="display-5 fw-bold">ANI-CARE ALLACAPAN</h1>
        <p class="lead mt-3">
            A web-based LGU Rice Assistance & Marketplace System connecting
            <strong>Farmers, Millers, Residents, and Administrators</strong>
            for faster, transparent, and traceable transactions in Allacapan, Cagayan.
        </p>

        <div class="mt-4">
            <a href="{{ route('login') }}" class="btn btn-warning btn-lg me-2">Login</a>
            <a href="#about" class="btn btn-outline-light btn-lg">Learn More</a>
        </div>

        <p class="mt-4 small">
            Role-based access • Real-time miller availability (OPEN/CLOSED) • Palay & Rice posting • Chat + Logs
        </p>
    </div>
</section>

<!-- ABOUT -->
<section id="about" class="py-5">
    <div class="container text-center">
        <h2 class="section-title mb-3">About ANI-CARE</h2>
        <p class="text-muted mx-auto" style="max-width: 920px;">
            <strong>ANI-CARE ALLACAPAN</strong> is a centralized digital platform designed to modernize the LGU’s rice
            assistance and marketplace operations. It enables farmers to post <strong>Palay</strong> and <strong>Rice</strong>
            products, residents to browse and submit purchase requests, and millers to manage milling services through
            an <strong>OPEN/CLOSED</strong> availability status.
        </p>
        <p class="text-muted mx-auto" style="max-width: 920px;">
            The system supports <strong>real-time monitoring</strong>, <strong>order and milling request tracking</strong>,
            and <strong>chat-based coordination</strong>, ensuring that transactions are transparent, traceable, and efficient
            for both users and the LGU.
        </p>

        <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
            <span class="pill">✅ Transparent Records</span>
            <span class="pill">📍 Local LGU Operations</span>
            <span class="pill">⏱ Faster Processing</span>
            <span class="pill">🔒 Role-Based Access</span>
        </div>
    </div>
</section>

<!-- NEW SECTION (YOUR SCREENSHOT) -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center g-4">
            <!-- LEFT -->
            <div class="col-lg-6">
                <h2 class="section-title text-success mb-3">
                    Faster Transactions. Better Coordination.
                </h2>

                <p class="text-muted" style="line-height: 1.7;">
                    ANI-CARE streamlines how rice and palay move from farmers to residents with complete request tracking
                    — from <strong>Pending</strong> to <strong>Approved</strong> to <strong>Completed</strong> — with built-in messaging
                    to reduce delays and improve coordination.
                </p>

                <p class="text-muted" style="line-height: 1.7;">
                    Inventory becomes more accurate through <strong>automatic stock deductions</strong> after approval/completion and
                    <strong>auto-disable products when stock reaches zero</strong>, preventing over-ordering and ensuring updated availability.
                </p>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    <span class="badge text-bg-success">Request Tracking</span>
                    <span class="badge text-bg-success">Chat & Logs</span>
                    <span class="badge text-bg-success">Auto Inventory</span>
                    <span class="badge text-bg-success">Accountability</span>
                </div>

                <div class="mt-4">
                    <a href="#features" class="btn btn-success px-4">See System Features</a>
                </div>

                <!-- small info -->
                <div class="row g-3 mt-4">
                    <div class="col-md-4">
                        <div class="feature-card p-3 h-100">
                            <div class="fw-bold">Traceable</div>
                            <div class="text-muted small">Every action is logged with timestamps.</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card p-3 h-100">
                            <div class="fw-bold">Efficient</div>
                            <div class="text-muted small">Less manual paperwork & faster processing.</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card p-3 h-100">
                            <div class="fw-bold">Secure</div>
                            <div class="text-muted small">Role-based access for every user type.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT IMAGE -->
            <div class="col-lg-6">
                <img
                    src="{{ asset('images/farmer.jpg') }}"
                    alt="ANI-CARE Farmer"
                    class="about-split-img"
                >
            </div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section id="features" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center section-title mb-4">Core Features</h2>

        @php
            $features = [
                ['Role-Based Access','Separate dashboards and permissions for Admin, Farmer, Miller, and Resident.'],
                ['Miller Availability','Each miller sets OPEN/CLOSED status so residents can quickly choose available services.'],
                ['Marketplace (Palay & Rice)','Farmers post Palay/Rice products with photos, kilos, and pricing. Residents can browse and request orders.'],
                ['Milling Requests','Farmers submit milling requests, millers approve/reject, and schedules are managed efficiently.'],
                ['Chat & Transaction Logs','Built-in messaging with timestamps to improve accountability and reduce miscommunication.'],
                ['Inventory Auto-Update','Automatic stock deduction after approval/completion and auto-disable when stock reaches 0.'],
            ];
        @endphp

        <div class="row g-4">
            @foreach($features as $f)
                <div class="col-md-4">
                    <div class="card feature-card h-100 p-3">
                        <h5 class="fw-bold text-success">{{ $f[0] }}</h5>
                        <p class="text-muted mb-0">{{ $f[1] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- MODULES -->
<section id="modules" class="py-5">
    <div class="container">
        <h2 class="text-center section-title mb-4">System Modules</h2>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card feature-card p-3 h-100">
                    <h5 class="text-success fw-bold">Farmer Module</h5>
                    <p class="text-muted mb-0">Post Palay/Rice products, manage stock, track orders, and coordinate with millers and residents.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card feature-card p-3 h-100">
                    <h5 class="text-success fw-bold">Miller Module</h5>
                    <p class="text-muted mb-0">Set OPEN/CLOSED status, manage milling requests, approve/reject schedules, and view operations.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card feature-card p-3 h-100">
                    <h5 class="text-success fw-bold">Resident Module</h5>
                    <p class="text-muted mb-0">Browse marketplace, submit orders, track purchase history, and communicate via chat.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card feature-card p-3 h-100">
                    <h5 class="text-success fw-bold">Admin Module</h5>
                    <p class="text-muted mb-0">Approve accounts, monitor platform activity, manage inventory, and generate reports/logs.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card feature-card p-3 h-100">
                    <h5 class="text-success fw-bold">Orders & Approvals</h5>
                    <p class="text-muted mb-0">Workflow status updates (Pending → Approved → Completed/Cancelled) with automatic inventory update.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card feature-card p-3 h-100">
                    <h5 class="text-success fw-bold">Reports & Logs</h5>
                    <p class="text-muted mb-0">Consolidated reports and timestamped logs for transparency, auditing, and LGU documentation.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CONTACT / FOOTER -->
<footer id="contact" class="py-4">
    <div class="container text-center">
        <h5 class="fw-bold mb-1">ANI-CARE ALLACAPAN</h5>
        <p class="mb-1">LGU Rice Assistance & Marketplace System</p>
        <div class="small mb-2">
            📍 Allacapan, Cagayan • ☎️ (Add contact number) • ✉️ (Add email)
        </div>
        <small>© {{ date('Y') }} All Rights Reserved | Allacapan, Cagayan</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>