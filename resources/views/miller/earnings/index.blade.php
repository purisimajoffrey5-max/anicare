<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Earnings & Analytics | Miller | ANI-CARE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root{
            --green:#198754;
            --green-dark:#146c43;
            --green-soft:#eaf7f0;
            --page:#f4f7f6;
            --card:#fff;
            --text:#18212a;
            --muted:#6b7280;
            --border:#e2e8e5;
        }

        *{box-sizing:border-box}
        html,body{margin:0;min-height:100%;font-family:"Segoe UI",sans-serif}
        body{background:var(--page);color:var(--text)}
        a{text-decoration:none}

        .topbar{
            position:sticky;top:0;z-index:1000;
            color:#fff;
            background:linear-gradient(135deg,var(--green),var(--green-dark));
            box-shadow:0 4px 18px rgba(0,0,0,.12);
        }

        .topbar-inner{
            max-width:1220px;min-height:64px;margin:0 auto;padding:10px 16px;
            display:flex;align-items:center;justify-content:space-between;gap:12px;
        }

        .brand{font-size:18px;font-weight:850}
        .page{max-width:1220px;margin:0 auto;padding:24px 14px 60px}

        .hero{
            border-radius:20px;padding:24px;margin-bottom:16px;
            color:#fff;
            background:
                radial-gradient(circle at 90% 10%,rgba(255,255,255,.18),transparent 28%),
                linear-gradient(135deg,#198754,#0f6b40);
            box-shadow:0 12px 28px rgba(25,135,84,.18);
        }

        .hero-title{font-size:30px;font-weight:900;margin-bottom:7px}
        .hero-sub{max-width:760px;color:rgba(255,255,255,.82);font-size:14px}

        .metrics{
            display:grid;grid-template-columns:repeat(4,minmax(0,1fr));
            gap:12px;margin-bottom:16px;
        }

        .metric{
            background:#fff;border:1px solid var(--border);
            border-radius:17px;padding:17px;
            box-shadow:0 5px 18px rgba(15,23,42,.045);
        }

        .metric-head{display:flex;justify-content:space-between;gap:10px;align-items:center}
        .metric-icon{
            width:42px;height:42px;border-radius:12px;display:grid;place-items:center;
            background:var(--green-soft);color:var(--green);font-size:19px;
        }

        .metric-label{font-size:10px;color:var(--muted);font-weight:700}
        .metric-value{font-size:23px;font-weight:900;margin-top:10px;line-height:1}
        .metric-foot{font-size:10px;color:var(--muted);margin-top:6px}

        .layout{
            display:grid;
            grid-template-columns:minmax(0,1.45fr) minmax(280px,.75fr);
            gap:14px;margin-bottom:16px;
        }

        .panel{
            background:#fff;border:1px solid var(--border);
            border-radius:18px;padding:18px;
            box-shadow:0 5px 18px rgba(15,23,42,.045);
        }

        .panel-title{font-size:17px;font-weight:850;margin:0}
        .panel-sub{font-size:11px;color:var(--muted);margin-top:3px}

        .bar-chart{
            height:240px;margin-top:18px;display:flex;align-items:flex-end;
            gap:10px;padding:12px 4px 0;border-bottom:1px solid #e8eeeb;
        }

        .bar-col{
            flex:1;height:100%;display:flex;flex-direction:column;
            justify-content:flex-end;align-items:center;gap:7px;min-width:0;
        }

        .bar-value{font-size:9px;color:var(--muted);white-space:nowrap}
        .bar{
            width:min(48px,72%);min-height:4px;border-radius:9px 9px 3px 3px;
            background:linear-gradient(180deg,#23a86b,#198754);
            box-shadow:0 5px 12px rgba(25,135,84,.14);
        }
        .bar-label{font-size:10px;color:#475569;font-weight:750}

        .summary{
            border-radius:16px;padding:15px;margin-top:14px;
            background:#f8fbf9;border:1px solid #e0ece5;
        }

        .summary-row{
            display:flex;justify-content:space-between;gap:12px;
            padding:9px 0;border-bottom:1px dashed #dce7e1;font-size:12px;
        }
        .summary-row:last-child{border-bottom:0}

        .role-grid{
            display:grid;grid-template-columns:1fr 1fr;gap:9px;margin-top:12px;
        }

        .role-box{
            border:1px solid #e4ebe7;border-radius:13px;padding:13px;background:#fafcfb;
        }

        .role-number{font-size:22px;font-weight:900;color:var(--green)}
        .role-label{font-size:10px;color:var(--muted);margin-top:3px}

        .table-panel{padding:0;overflow:hidden}
        .table-head{padding:18px 18px 10px}
        .table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch}
        table{min-width:980px;margin:0}
        th{font-size:10px!important;color:#667085!important;text-transform:uppercase}
        td{font-size:12px!important;vertical-align:middle!important}

        .pill{
            display:inline-flex;align-items:center;border-radius:999px;
            padding:5px 8px;font-size:9px;font-weight:850;
        }

        .paid{background:#d1e7dd;color:#0f5132}
        .unpaid{background:#fff3cd;color:#705700}
        .completed{background:#d1e7dd;color:#0f5132}
        .active{background:#dbeafe;color:#1d4ed8}
        .stopped{background:#eceff1;color:#59636c}

        .mobile-list{display:none}
        .tx-card{
            background:#fff;border:1px solid var(--border);border-radius:15px;
            padding:13px;margin-bottom:10px;
        }

        .tx-head{display:flex;justify-content:space-between;gap:10px;margin-bottom:10px}
        .tx-title{font-size:13px;font-weight:850}
        .tx-sub{font-size:10px;color:var(--muted);margin-top:2px}
        .tx-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:7px}
        .tx-info{border-radius:10px;background:#f8faf9;border:1px solid #edf1ef;padding:8px}
        .tx-label{font-size:9px;color:var(--muted)}
        .tx-value{font-size:11px;font-weight:750;margin-top:2px;overflow-wrap:anywhere}

        .note{
            margin-top:13px;padding:11px 13px;border-radius:12px;
            background:#eef6ff;border:1px solid #d6e8ff;
            color:#365a7c;font-size:10px;line-height:1.45;
        }

        @media(max-width:950px){
            .metrics{grid-template-columns:repeat(2,1fr)}
            .layout{grid-template-columns:1fr}
        }

        @media(max-width:650px){
            .topbar-inner{padding:9px 10px}
            .brand{font-size:15px}
            .page{padding:16px 10px 45px}
            .hero{padding:18px;border-radius:17px}
            .hero-title{font-size:24px}
            .metrics{gap:9px}
            .metric{padding:13px;border-radius:14px}
            .metric-value{font-size:19px}
            .desktop-list{display:none}
            .mobile-list{display:block}
            .bar-chart{height:210px;gap:5px}
            .bar-value{font-size:8px}
            .panel{padding:14px}
        }

        @media(max-width:380px){
            .metrics{grid-template-columns:1fr}
            .tx-grid,.role-grid{grid-template-columns:1fr}
        }
    </style>
</head>
<body>

<header class="topbar">
    <div class="topbar-inner">
        <div class="brand">
            <i class="bi bi-cash-coin me-1"></i>
            ANI-CARE | Miller Analytics
        </div>

        <a href="{{ route('miller.dashboard') }}" class="btn btn-warning btn-sm fw-bold">
            <i class="bi bi-arrow-left"></i>
            Dashboard
        </a>
    </div>
</header>

<main class="page">

    <section class="hero">
        <div class="hero-title">Milling Earnings & Analytics</div>
        <div class="hero-sub">
            Monitor paid milling services, cash received, transport collections,
            pending receivables, processed kilos, requester activity, and monthly performance.
        </div>
    </section>


    <section class="metrics">

        <div class="metric">
            <div class="metric-head">
                <div class="metric-label">MILLING SERVICE REVENUE</div>
                <div class="metric-icon"><i class="bi bi-cash-stack"></i></div>
            </div>
            <div class="metric-value text-success">₱{{ number_format($serviceRevenue,2) }}</div>
            <div class="metric-foot">Paid milling fees only</div>
        </div>

        <div class="metric">
            <div class="metric-head">
                <div class="metric-label">THIS MONTH</div>
                <div class="metric-icon"><i class="bi bi-calendar2-check"></i></div>
            </div>
            <div class="metric-value">₱{{ number_format($thisMonthRevenue,2) }}</div>
            <div class="metric-foot">Paid service revenue this month</div>
        </div>

        <div class="metric">
            <div class="metric-head">
                <div class="metric-label">PENDING RECEIVABLES</div>
                <div class="metric-icon"><i class="bi bi-hourglass-split"></i></div>
            </div>
            <div class="metric-value text-warning">₱{{ number_format($pendingReceivables,2) }}</div>
            <div class="metric-foot">Active milling requests still unpaid</div>
        </div>

        <div class="metric">
            <div class="metric-head">
                <div class="metric-label">COMPLETED JOBS</div>
                <div class="metric-icon"><i class="bi bi-check2-circle"></i></div>
            </div>
            <div class="metric-value">{{ number_format($completedJobs) }}</div>
            <div class="metric-foot">{{ number_format($totalKilosProcessed,2) }} kg finished / completed</div>
        </div>

    </section>


    <section class="layout">

        <div class="panel">
            <h2 class="panel-title">6-Month Service Revenue</h2>
            <div class="panel-sub">
                Milling fee revenue recognized when payment is actually marked PAID.
            </div>

            <div class="bar-chart">
                @foreach($monthlySeries as $point)
                    @php
                        $height = max(
                            3,
                            ($point['amount'] / $chartMax) * 82
                        );
                    @endphp

                    <div class="bar-col">
                        <div class="bar-value">₱{{ number_format($point['amount'],0) }}</div>
                        <div
                            class="bar"
                            style="height:{{ $height }}%"
                            title="{{ $point['month'] }}: ₱{{ number_format($point['amount'],2) }}"
                        ></div>
                        <div class="bar-label">{{ $point['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>


        <div class="panel">
            <h2 class="panel-title">Performance Snapshot</h2>
            <div class="panel-sub">Operational and financial service indicators.</div>

            <div class="summary">
                <div class="summary-row">
                    <span>Total Cash Received</span>
                    <strong class="text-success">₱{{ number_format($totalCashReceived,2) }}</strong>
                </div>

                <div class="summary-row">
                    <span>Transport Collected</span>
                    <strong>₱{{ number_format($transportCollected,2) }}</strong>
                </div>

                <div class="summary-row">
                    <span>Average Revenue / Paid Job</span>
                    <strong>₱{{ number_format($averageRevenuePerPaidJob,2) }}</strong>
                </div>

                <div class="summary-row">
                    <span>Average Milling Rate</span>
                    <strong>₱{{ number_format($averageMillingRate,2) }}/kg</strong>
                </div>
            </div>

            <div class="role-grid">
                <div class="role-box">
                    <div class="role-number">{{ number_format($requesterBreakdown['farmer']) }}</div>
                    <div class="role-label">Farmer Requests</div>
                </div>

                <div class="role-box">
                    <div class="role-number">{{ number_format($requesterBreakdown['admin']) }}</div>
                    <div class="role-label">Admin Requests</div>
                </div>
            </div>

            <div class="note">
                <i class="bi bi-info-circle-fill me-1"></i>
                Revenue is counted only after the Miller records actual payment as
                <strong>PAID</strong>. Unpaid active transactions stay under Pending Receivables.
            </div>
        </div>

    </section>


    <section class="panel table-panel desktop-list">
        <div class="table-head">
            <h2 class="panel-title">Recent Milling Transactions</h2>
            <div class="panel-sub">
                Latest Farmer/Admin requests assigned to your Miller account.
            </div>
        </div>

        <div class="table-wrap">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Request</th>
                        <th>Requester</th>
                        <th>Role</th>
                        <th>Quantity</th>
                        <th>Milling Fee</th>
                        <th>Transport</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentRequests as $r)
                        @php
                            $payment = strtolower((string)($r->payment_status ?? 'unpaid'));
                            $status = strtolower((string)($r->status ?? 'pending'));

                            $requester =
                                $r->requester
                                ?? $r->farmer
                                ?? null;

                            $requesterName =
                                $r->requester_name_snapshot
                                ?? $requester?->fullname
                                ?? $requester?->username
                                ?? 'Requester';

                            $role =
                                strtolower((string)(
                                    $r->requester_role
                                    ?? $r->actual_requester_role
                                    ?? 'farmer'
                                ));

                            $kilos = (float)(
                                $r->quantity_kilos
                                ?? $r->quantity_kg
                                ?? $r->kilos
                                ?? $r->quantity
                                ?? 0
                            );
                        @endphp

                        <tr>
                            <td><strong>#{{ $r->id }}</strong></td>
                            <td>{{ $requesterName }}</td>
                            <td class="text-capitalize">{{ $role }}</td>
                            <td>{{ number_format($kilos,2) }} kg</td>
                            <td class="fw-bold text-success">₱{{ number_format((float)($r->total_amount ?? 0),2) }}</td>
                            <td>₱{{ number_format((float)($r->shipping_fee ?? 0),2) }}</td>
                            <td>
                                <span class="pill {{ $payment === 'paid' ? 'paid' : 'unpaid' }}">
                                    {{ strtoupper($payment) }}
                                </span>
                            </td>
                            <td>
                                <span class="pill {{ $status === 'completed' ? 'completed' : (in_array($status,['rejected','cancelled']) ? 'stopped' : 'active') }}">
                                    {{ strtoupper(str_replace('_',' ',$status)) }}
                                </span>
                            </td>
                            <td>{{ $r->created_at?->timezone('Asia/Manila')->format('M d, Y') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                No milling transactions yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>


    <section class="mobile-list">
        <h2 class="panel-title mb-2">Recent Milling Transactions</h2>

        @forelse($recentRequests as $r)
            @php
                $payment = strtolower((string)($r->payment_status ?? 'unpaid'));
                $status = strtolower((string)($r->status ?? 'pending'));

                $requester =
                    $r->requester
                    ?? $r->farmer
                    ?? null;

                $requesterName =
                    $r->requester_name_snapshot
                    ?? $requester?->fullname
                    ?? $requester?->username
                    ?? 'Requester';

                $role =
                    strtolower((string)(
                        $r->requester_role
                        ?? $r->actual_requester_role
                        ?? 'farmer'
                    ));

                $kilos = (float)(
                    $r->quantity_kilos
                    ?? $r->quantity_kg
                    ?? $r->kilos
                    ?? $r->quantity
                    ?? 0
                );
            @endphp

            <article class="tx-card">
                <div class="tx-head">
                    <div>
                        <div class="tx-title">Milling Request #{{ $r->id }}</div>
                        <div class="tx-sub">
                            {{ $requesterName }} • {{ ucfirst($role) }}
                        </div>
                    </div>

                    <span class="pill {{ $payment === 'paid' ? 'paid' : 'unpaid' }}">
                        {{ strtoupper($payment) }}
                    </span>
                </div>

                <div class="tx-grid">
                    <div class="tx-info">
                        <div class="tx-label">Quantity</div>
                        <div class="tx-value">{{ number_format($kilos,2) }} kg</div>
                    </div>

                    <div class="tx-info">
                        <div class="tx-label">Milling Fee</div>
                        <div class="tx-value text-success">₱{{ number_format((float)($r->total_amount ?? 0),2) }}</div>
                    </div>

                    <div class="tx-info">
                        <div class="tx-label">Status</div>
                        <div class="tx-value">{{ strtoupper(str_replace('_',' ',$status)) }}</div>
                    </div>

                    <div class="tx-info">
                        <div class="tx-label">Requested</div>
                        <div class="tx-value">{{ $r->created_at?->timezone('Asia/Manila')->format('M d, Y') ?? '-' }}</div>
                    </div>
                </div>
            </article>
        @empty
            <div class="text-muted small">
                No milling transactions yet.
            </div>
        @endforelse
    </section>

</main>
</body>
</html>