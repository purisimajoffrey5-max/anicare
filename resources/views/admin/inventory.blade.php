<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory | ANI-CARE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        *{box-sizing:border-box}
        body{margin:0;background:#f4f7f6;font-family:"Segoe UI",sans-serif;color:#1f2937}
        .page{max-width:1180px;margin:auto;padding:24px 14px 60px}
        .head{display:flex;justify-content:space-between;gap:12px;align-items:flex-start;margin-bottom:18px}
        .title{font-size:30px;font-weight:850;color:#198754;margin:0}
        .sub{font-size:13px;color:#6b7280;margin-top:3px}
        .stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:18px}
        .stat,.panel,.mobile-card{background:#fff;border:1px solid #e0e7e3;border-radius:16px;box-shadow:0 6px 18px rgba(15,23,42,.04)}
        .stat{padding:17px}.stat.rice{border-top:4px solid #198754}.stat.palay{border-top:4px solid #0d6efd}.stat.orders{border-top:4px solid #f59f00}
        .stat-label{font-size:11px;color:#6b7280}.stat-value{font-size:28px;font-weight:850;line-height:1.15}.stat-note{font-size:10px;color:#6b7280;margin-top:4px}
        .panel{padding:15px}.panel-title{font-size:18px;font-weight:850}.panel-sub{font-size:11px;color:#6b7280;margin:2px 0 14px}
        .table-wrap{overflow-x:auto}.table{min-width:1050px;margin:0}.table th{font-size:11px;background:#f8faf9;white-space:nowrap}.table td{font-size:12px;vertical-align:middle}
        .pill{display:inline-flex;align-items:center;gap:4px;border-radius:999px;padding:5px 8px;font-size:9px;font-weight:850;white-space:nowrap}
        .rice-pill{background:#d1e7dd;color:#0f5132}.palay-pill{background:#dbeafe;color:#174ea6}.available{background:#d1e7dd;color:#0f5132}.awaiting{background:#fff3cd;color:#705700}.other{background:#eceff1;color:#495057}
        .mobile-list{display:none}.mobile-card{padding:13px;margin-bottom:10px}.mobile-head{display:flex;justify-content:space-between;gap:10px}.item-name{font-weight:850}.muted{font-size:10px;color:#6b7280}
        .grid{display:grid;grid-template-columns:repeat(2,1fr);gap:7px;margin-top:10px}.box{background:#f8faf9;border:1px solid #edf1ef;border-radius:10px;padding:9px}.box-label{font-size:9px;color:#6b7280}.box-value{font-size:11px;font-weight:750;margin-top:2px;overflow-wrap:anywhere}
        @media(max-width:767px){.page{padding:16px 10px 50px}.title{font-size:25px}.stats{grid-template-columns:repeat(2,1fr);gap:8px}.stat.orders{grid-column:1/-1}.desktop{display:none}.mobile-list{display:block}.head .btn{width:42px;height:40px;padding:0;font-size:0;display:grid;place-items:center}.head .btn i{font-size:18px}}
        @media(max-width:380px){.grid{grid-template-columns:1fr}}
    </style>
</head>
<body>

<main class="page">

    <div class="head">
        <div>
            <h1 class="title"><i class="bi bi-box-seam"></i> Inventory</h1>
            <div class="sub">All Rice and Palay purchased and received by Admin from the marketplace.</div>
        </div>

        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-success btn-sm">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-4">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger rounded-4">{{ $errors->first() }}</div>
    @endif

    <section class="stats">
        <div class="stat rice">
            <div class="stat-label">Rice Stock</div>
            <div class="stat-value text-success">{{ number_format((float)($totalRice ?? 0),2) }} <small class="fs-6">kg</small></div>
            <div class="stat-note">Rice currently available in Admin inventory.</div>
        </div>

        <div class="stat palay">
            <div class="stat-label">Palay Stock</div>
            <div class="stat-value text-primary">{{ number_format((float)($totalPalay ?? 0),2) }} <small class="fs-6">kg</small></div>
            <div class="stat-note">Palay available or awaiting milling.</div>
        </div>

        <div class="stat orders">
            <div class="stat-label">Completed Purchases</div>
            <div class="stat-value">{{ (int)($purchasedCount ?? 0) }}</div>
            <div class="stat-note">Marketplace orders already confirmed as received.</div>
        </div>
    </section>

    <section class="panel">
        <div class="panel-title">Purchased Inventory</div>
        <div class="panel-sub">Completed Admin purchases are automatically synchronized here. Rice and Palay are both included.</div>

        <div class="desktop table-wrap">
            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Order</th>
                    <th>Item</th>
                    <th>Type</th>
                    <th>Stock</th>
                    <th>Price/kg</th>
                    <th>Seller</th>
                    <th>Status</th>
                    <th>Received</th>
                    <th>Action</th>
                </tr>
                </thead>

                <tbody>
                @forelse($items as $item)
                    @php
                        $type = strtolower((string)($item->product_type ?? ''));
                        $status = strtolower((string)($item->status ?? ''));
                        $order = isset($orderMap) ? $orderMap->get($item->order_id) : null;
                        $seller = $order?->farmer;
                        $sellerName = $seller?->fullname ?? $seller?->username ?? 'Farmer';
                        $received = $order?->buyer_confirmed_at ?? $order?->updated_at ?? $item->created_at;
                        $statusClass = $status === 'available' ? 'available' : ($status === 'awaiting_milling' ? 'awaiting' : 'other');
                    @endphp

                    <tr>
                        <td class="fw-bold">#{{ $item->id }}</td>
                        <td>Order #{{ $item->order_id }}</td>
                        <td class="fw-bold">{{ $item->name }}</td>
                        <td>
                            <span class="pill {{ $type === 'palay' ? 'palay-pill' : 'rice-pill' }}">{{ strtoupper($type) }}</span>
                        </td>
                        <td class="fw-bold">{{ number_format((float)$item->kilos_available,2) }} kg</td>
                        <td>₱{{ number_format((float)($item->price_per_kg ?? 0),2) }}</td>
                        <td>{{ $sellerName }}</td>
                        <td>
                            <span class="pill {{ $statusClass }}">{{ strtoupper(str_replace('_',' ', $status)) }}</span>
                        </td>
                        <td>{{ $received ? \Carbon\Carbon::parse($received)->timezone('Asia/Manila')->format('M d, Y h:i A') : '-' }}</td>
                        <td>
                            @if($type === 'palay' && $status === 'awaiting_milling')
                                @if($item->millingRequest && $item->millingRequest->miller_id)
                                    <span class="small text-muted">Miller Assigned</span>
                                @else
                                    <a href="{{ route('admin.inventory.assign.form', $item->id) }}" class="btn btn-primary btn-sm">Assign Miller</a>
                                @endif
                            @elseif($type === 'rice')
                                <span class="small text-success fw-bold">Available</span>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            No completed Admin purchases found yet.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mobile-list">
            @forelse($items as $item)
                @php
                    $type = strtolower((string)($item->product_type ?? ''));
                    $status = strtolower((string)($item->status ?? ''));
                    $order = isset($orderMap) ? $orderMap->get($item->order_id) : null;
                    $seller = $order?->farmer;
                    $sellerName = $seller?->fullname ?? $seller?->username ?? 'Farmer';
                    $received = $order?->buyer_confirmed_at ?? $order?->updated_at ?? $item->created_at;
                    $statusClass = $status === 'available' ? 'available' : ($status === 'awaiting_milling' ? 'awaiting' : 'other');
                @endphp

                <article class="mobile-card">
                    <div class="mobile-head">
                        <div>
                            <div class="muted">Marketplace Order #{{ $item->order_id }}</div>
                            <div class="item-name">{{ $item->name }}</div>
                        </div>

                        <span class="pill {{ $type === 'palay' ? 'palay-pill' : 'rice-pill' }}">{{ strtoupper($type) }}</span>
                    </div>

                    <div class="grid">
                        <div class="box">
                            <div class="box-label">Stock</div>
                            <div class="box-value">{{ number_format((float)$item->kilos_available,2) }} kg</div>
                        </div>

                        <div class="box">
                            <div class="box-label">Price/kg</div>
                            <div class="box-value">₱{{ number_format((float)($item->price_per_kg ?? 0),2) }}</div>
                        </div>

                        <div class="box">
                            <div class="box-label">Seller</div>
                            <div class="box-value">{{ $sellerName }}</div>
                        </div>

                        <div class="box">
                            <div class="box-label">Status</div>
                            <div class="box-value"><span class="pill {{ $statusClass }}">{{ strtoupper(str_replace('_',' ', $status)) }}</span></div>
                        </div>

                        <div class="box" style="grid-column:1/-1">
                            <div class="box-label">Received</div>
                            <div class="box-value">{{ $received ? \Carbon\Carbon::parse($received)->timezone('Asia/Manila')->format('M d, Y h:i A') : '-' }}</div>
                        </div>
                    </div>

                    @if($type === 'palay' && $status === 'awaiting_milling')
                        <div class="mt-2">
                            @if($item->millingRequest && $item->millingRequest->miller_id)
                                <button class="btn btn-light border w-100" disabled>Miller Already Assigned</button>
                            @else
                                <a href="{{ route('admin.inventory.assign.form', $item->id) }}" class="btn btn-primary w-100">Assign Miller</a>
                            @endif
                        </div>
                    @endif
                </article>
            @empty
                <div class="text-center text-muted py-5">No completed Admin purchases found yet.</div>
            @endforelse
        </div>

        @if(method_exists($items, 'hasPages') && $items->hasPages())
            <div class="d-flex justify-content-center mt-3">{{ $items->links() }}</div>
        @endif
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
