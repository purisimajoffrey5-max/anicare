<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Earnings & Analytics | Farmer | ANI-CARE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root{
            --green:#198754;
            --green-dark:#146c43;
            --green-soft:#eaf7f0;
            --page:#f4f7f6;
            --card:#ffffff;
            --text:#18212a;
            --muted:#6b7280;
            --border:#e2e8e5;
            --warning:#f59e0b;
            --blue:#2563eb;
            --purple:#7c3aed;
        }

        *{box-sizing:border-box}
        html,body{margin:0;min-height:100%;font-family:"Segoe UI",sans-serif}
        body{background:var(--page);color:var(--text)}
        a{text-decoration:none}

        .topbar{
            background:linear-gradient(135deg,var(--green),var(--green-dark));
            color:#fff;
            position:sticky;
            top:0;
            z-index:1000;
            box-shadow:0 4px 18px rgba(0,0,0,.12);
        }

        .topbar-inner{
            max-width:1220px;
            min-height:64px;
            margin:0 auto;
            padding:10px 16px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
        }

        .brand{font-size:18px;font-weight:850}
        .page{
            max-width:1220px;
            margin:0 auto;
            padding:24px 14px 60px;
        }

        .hero{
            border-radius:20px;
            padding:24px;
            margin-bottom:16px;
            background:
                radial-gradient(circle at 90% 10%,rgba(255,255,255,.18),transparent 28%),
                linear-gradient(135deg,#198754,#0f6b40);
            color:#fff;
            box-shadow:0 12px 28px rgba(25,135,84,.18);
        }

        .hero-title{
            font-size:30px;
            line-height:1.2;
            font-weight:900;
            margin-bottom:7px;
        }

        .hero-sub{
            max-width:760px;
            color:rgba(255,255,255,.82);
            font-size:14px;
        }

        .metric-grid{
            display:grid;
            grid-template-columns:repeat(4,minmax(0,1fr));
            gap:12px;
            margin-bottom:16px;
        }

        .metric{
            background:var(--card);
            border:1px solid var(--border);
            border-radius:17px;
            padding:17px;
            box-shadow:0 5px 18px rgba(15,23,42,.045);
        }

        .metric-top{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
        }

        .metric-icon{
            width:42px;height:42px;border-radius:12px;
            display:grid;place-items:center;
            background:var(--green-soft);color:var(--green);
            font-size:19px;
        }

        .metric-label{font-size:11px;color:var(--muted);font-weight:650}
        .metric-value{margin-top:10px;font-size:24px;font-weight:900;line-height:1}
        .metric-foot{margin-top:6px;color:var(--muted);font-size:11px}

        .layout{
            display:grid;
            grid-template-columns:minmax(0,1.45fr) minmax(280px,.75fr);
            gap:14px;
            margin-bottom:16px;
        }

        .panel{
            background:#fff;
            border:1px solid var(--border);
            border-radius:18px;
            padding:18px;
            box-shadow:0 5px 18px rgba(15,23,42,.045);
        }

        .panel-title{font-size:17px;font-weight:850;margin:0}
        .panel-sub{font-size:11px;color:var(--muted);margin-top:3px}

        .bar-chart{
            height:240px;
            margin-top:18px;
            display:flex;
            align-items:flex-end;
            gap:10px;
            padding:12px 4px 0;
            border-bottom:1px solid #e8eeeb;
        }

        .bar-col{
            flex:1;
            height:100%;
            min-width:0;
            display:flex;
            flex-direction:column;
            justify-content:flex-end;
            align-items:center;
            gap:7px;
        }

        .bar-value{
            font-size:9px;
            color:var(--muted);
            white-space:nowrap;
        }

        .bar{
            width:min(48px,72%);
            min-height:4px;
            border-radius:9px 9px 3px 3px;
            background:linear-gradient(180deg,#23a86b,#198754);
            box-shadow:0 5px 12px rgba(25,135,84,.14);
        }

        .bar-label{font-size:10px;color:#475569;font-weight:750}

        .balance-box{
            border-radius:16px;
            padding:17px;
            background:#f7fbf9;
            border:1px solid #dcece4;
            margin-top:14px;
        }

        .balance-row{
            display:flex;
            justify-content:space-between;
            gap:12px;
            padding:9px 0;
            font-size:12px;
            border-bottom:1px dashed #d8e5df;
        }

        .balance-row:last-child{border-bottom:0}
        .balance-grand{font-size:15px;font-weight:900;color:var(--green)}
        .warning-text{color:#b7791f}

        .product-row{
            display:grid;
            grid-template-columns:minmax(0,1fr) auto;
            gap:10px;
            padding:12px 0;
            border-bottom:1px solid #edf1ef;
        }

        .product-row:last-child{border-bottom:0}
        .product-name{font-size:13px;font-weight:800}
        .product-meta{font-size:10px;color:var(--muted);margin-top:3px}
        .product-amount{font-size:13px;font-weight:850;color:var(--green)}

        .table-panel{padding:0;overflow:hidden}
        .table-head{padding:18px 18px 10px}

        .table-wrap{
            overflow-x:auto;
            -webkit-overflow-scrolling:touch;
        }

        table{min-width:930px;margin:0}
        th{font-size:10px!important;color:#667085!important;text-transform:uppercase;letter-spacing:.03em}
        td{font-size:12px!important;vertical-align:middle!important}

        .pill{
            display:inline-flex;align-items:center;
            border-radius:999px;padding:5px 8px;
            font-size:9px;font-weight:850;
        }

        .paid{background:#d1e7dd;color:#0f5132}
        .unpaid{background:#fff3cd;color:#705700}
        .completed{background:#d1e7dd;color:#0f5132}
        .cancelled{background:#eceff1;color:#59636c}
        .active{background:#dbeafe;color:#1d4ed8}

        .mobile-transactions{display:none}
        .tx-card{
            background:#fff;
            border:1px solid var(--border);
            border-radius:15px;
            padding:13px;
            margin-bottom:10px;
        }

        .tx-head{display:flex;justify-content:space-between;gap:10px;margin-bottom:10px}
        .tx-title{font-size:13px;font-weight:850}
        .tx-sub{font-size:10px;color:var(--muted);margin-top:2px}

        .tx-grid{
            display:grid;
            grid-template-columns:repeat(2,minmax(0,1fr));
            gap:7px;
        }

        .tx-info{
            border-radius:10px;
            background:#f8faf9;
            border:1px solid #edf1ef;
            padding:8px;
        }

        .tx-label{font-size:9px;color:var(--muted)}
        .tx-value{font-size:11px;font-weight:750;margin-top:2px;overflow-wrap:anywhere}

        .info-note{
            margin-top:14px;
            padding:11px 13px;
            border-radius:12px;
            background:#eef6ff;
            border:1px solid #d6e8ff;
            color:#365a7c;
            font-size:10px;
            line-height:1.45;
        }

        @media(max-width:950px){
            .metric-grid{grid-template-columns:repeat(2,1fr)}
            .layout{grid-template-columns:1fr}
        }

        @media(max-width:650px){
            .topbar-inner{padding:9px 10px}
            .brand{font-size:15px}
            .page{padding:16px 10px 45px}
            .hero{padding:18px;border-radius:17px}
            .hero-title{font-size:24px}
            .metric-grid{gap:9px}
            .metric{padding:13px;border-radius:14px}
            .metric-value{font-size:19px}
            .desktop-transactions{display:none}
            .mobile-transactions{display:block}
            .bar-chart{height:210px;gap:5px}
            .bar-value{font-size:8px}
            .panel{padding:14px}
        }

        @media(max-width:380px){
            .metric-grid{grid-template-columns:1fr}
            .tx-grid{grid-template-columns:1fr}
        }
    </style>
</head>
<body>

<header class="topbar">
    <div class="topbar-inner">
        <div class="brand">
            <i class="bi bi-graph-up-arrow me-1"></i>
            ANI-CARE | Farmer Analytics
        </div>

        <a href="{{ route('farmer.dashboard') }}" class="btn btn-warning btn-sm fw-bold">
            <i class="bi bi-arrow-left"></i>
            Dashboard
        </a>
    </div>
</header>

<main class="page">

    <section class="hero">
        <div class="hero-title">
            Earnings & Analytics
        </div>

        <div class="hero-sub">
            Monitor marketplace payments, completed sales, pending receivables,
            milling expenses, product performance, and your transaction balance.
        </div>
    </section>


    <section class="metric-grid">

        <div class="metric">
            <div class="metric-top">
                <div>
                    <div class="metric-label">MARKETPLACE SALES RECEIVED</div>
                </div>
                <div class="metric-icon"><i class="bi bi-cash-stack"></i></div>
            </div>
            <div class="metric-value text-success">₱{{ number_format($salesRevenue,2) }}</div>
            <div class="metric-foot">Paid product sales only</div>
        </div>

        <div class="metric">
            <div class="metric-top">
                <div class="metric-label">THIS MONTH</div>
                <div class="metric-icon"><i class="bi bi-calendar2-check"></i></div>
            </div>
            <div class="metric-value">₱{{ number_format($thisMonthSales,2) }}</div>
            <div class="metric-foot">Paid marketplace sales this month</div>
        </div>

        <div class="metric">
            <div class="metric-top">
                <div class="metric-label">PENDING RECEIVABLES</div>
                <div class="metric-icon"><i class="bi bi-hourglass-split"></i></div>
            </div>
            <div class="metric-value warning-text">₱{{ number_format($pendingReceivables,2) }}</div>
            <div class="metric-foot">Active orders not yet marked paid</div>
        </div>

        <div class="metric">
            <div class="metric-top">
                <div class="metric-label">COMPLETED SALES</div>
                <div class="metric-icon"><i class="bi bi-check2-circle"></i></div>
            </div>
            <div class="metric-value">{{ number_format($completedSalesCount) }}</div>
            <div class="metric-foot">{{ number_format($totalKilosSold,2) }} kg confirmed received</div>
        </div>

    </section>


    <section class="layout">

        <div class="panel">
            <h2 class="panel-title">6-Month Sales Trend</h2>
            <div class="panel-sub">
                Product sales recognized when the Farmer marks payment as PAID.
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
                        <div class="bar-value">
                            ₱{{ number_format($point['amount'],0) }}
                        </div>

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
            <h2 class="panel-title">Transaction Balance</h2>
            <div class="panel-sub">
                Marketplace inflow versus paid milling service expenses.
            </div>

            <div class="balance-box">
                <div class="balance-row">
                    <span>Marketplace Sales Received</span>
                    <strong>₱{{ number_format($salesRevenue,2) }}</strong>
                </div>

                <div class="balance-row">
                    <span>Milling Expenses Paid</span>
                    <strong class="text-danger">− ₱{{ number_format($millingExpenses,2) }}</strong>
                </div>

                <div class="balance-row">
                    <span>This Month Milling Expense</span>
                    <strong>₱{{ number_format($thisMonthMillingExpenses,2) }}</strong>
                </div>

                <div class="balance-row">
                    <span>Shipping Collected</span>
                    <strong>₱{{ number_format($shippingCollected,2) }}</strong>
                </div>

                <div class="balance-row balance-grand">
                    <span>Net Transaction Balance</span>
                    <span>
                        {{ $netTransactionBalance < 0 ? '− ' : '' }}
                        ₱{{ number_format(abs($netTransactionBalance),2) }}
                    </span>
                </div>
            </div>

            <div class="info-note">
                <i class="bi bi-info-circle-fill me-1"></i>
                <strong>Net Transaction Balance is not Net Profit.</strong>
                ANI-CARE does not yet subtract production costs such as seeds,
                fertilizer, labor, fuel, equipment, taxes, and other farm expenses.
            </div>
        </div>

    </section>


    <section class="panel mb-3">
        <h2 class="panel-title">Top Product Performance</h2>
        <div class="panel-sub">
            Based on PAID + COMPLETED marketplace orders.
        </div>

        <div class="mt-2">
            @forelse($topProducts as $product)
                <div class="product-row">
                    <div>
                        <div class="product-name">{{ $product['name'] }}</div>
                        <div class="product-meta">
                            {{ number_format($product['kilos'],2) }} kg sold
                            • {{ $product['orders'] }} completed order{{ $product['orders'] === 1 ? '' : 's' }}
                        </div>
                    </div>

                    <div class="product-amount">
                        ₱{{ number_format($product['revenue'],2) }}
                    </div>
                </div>
            @empty
                <div class="text-muted small py-3">
                    No completed paid marketplace sales yet.
                </div>
            @endforelse
        </div>
    </section>


    <section class="panel table-panel desktop-transactions">
        <div class="table-head">
            <h2 class="panel-title">Recent Marketplace Transactions</h2>
            <div class="panel-sub">
                Latest orders received for your posted Rice / Palay products.
            </div>
        </div>

        <div class="table-wrap">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Order</th>
                        <th>Buyer</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Sales Amount</th>
                        <th>Payment</th>
                        <th>Order Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        @php
                            $payment = strtolower((string)($order->payment_status ?? 'unpaid'));
                            $status = strtolower((string)($order->status ?? 'pending'));
                            $amount = (float)(
                                $order->total_price
                                ?? $order->subtotal
                                ?? 0
                            );
                        @endphp
                        <tr>
                            <td><strong>#{{ $order->id }}</strong></td>
                            <td>
                                {{ $order->buyer_name
                                    ?? $order->resident?->fullname
                                    ?? $order->resident?->username
                                    ?? 'Buyer' }}
                            </td>
                            <td>
                                {{ $order->product_name_snapshot
                                    ?? $order->product?->name
                                    ?? 'Product' }}
                            </td>
                            <td>{{ number_format((float)($order->quantity_kilos ?? 0),2) }} kg</td>
                            <td class="fw-bold text-success">₱{{ number_format($amount,2) }}</td>
                            <td>
                                <span class="pill {{ $payment === 'paid' ? 'paid' : 'unpaid' }}">
                                    {{ strtoupper($payment) }}
                                </span>
                            </td>
                            <td>
                                <span class="pill {{ $status === 'completed' ? 'completed' : ($status === 'cancelled' ? 'cancelled' : 'active') }}">
                                    {{ strtoupper(str_replace('_',' ',$status)) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at?->timezone('Asia/Manila')->format('M d, Y') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                No marketplace transactions yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>


    <section class="mobile-transactions">
        <h2 class="panel-title mb-2">Recent Transactions</h2>

        @forelse($recentOrders as $order)
            @php
                $payment = strtolower((string)($order->payment_status ?? 'unpaid'));
                $status = strtolower((string)($order->status ?? 'pending'));
                $amount = (float)($order->total_price ?? $order->subtotal ?? 0);
            @endphp

            <article class="tx-card">
                <div class="tx-head">
                    <div>
                        <div class="tx-title">
                            Order #{{ $order->id }}
                            • {{ $order->product_name_snapshot ?? $order->product?->name ?? 'Product' }}
                        </div>
                        <div class="tx-sub">
                            {{ $order->buyer_name ?? $order->resident?->fullname ?? 'Buyer' }}
                        </div>
                    </div>

                    <span class="pill {{ $payment === 'paid' ? 'paid' : 'unpaid' }}">
                        {{ strtoupper($payment) }}
                    </span>
                </div>

                <div class="tx-grid">
                    <div class="tx-info">
                        <div class="tx-label">Quantity</div>
                        <div class="tx-value">{{ number_format((float)($order->quantity_kilos ?? 0),2) }} kg</div>
                    </div>

                    <div class="tx-info">
                        <div class="tx-label">Sales Amount</div>
                        <div class="tx-value text-success">₱{{ number_format($amount,2) }}</div>
                    </div>

                    <div class="tx-info">
                        <div class="tx-label">Status</div>
                        <div class="tx-value">{{ strtoupper(str_replace('_',' ',$status)) }}</div>
                    </div>

                    <div class="tx-info">
                        <div class="tx-label">Ordered</div>
                        <div class="tx-value">{{ $order->created_at?->timezone('Asia/Manila')->format('M d, Y') ?? '-' }}</div>
                    </div>
                </div>
            </article>
        @empty
            <div class="text-muted small">
                No marketplace transactions yet.
            </div>
        @endforelse
    </section>

</main>
</body>
</html>