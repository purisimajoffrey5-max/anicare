@php
    $statusClass = match(strtolower($invoice['order_status'])) {
        'completed', 'delivered', 'confirmed', 'approved' => 'success',
        'cancelled', 'canceled', 'rejected' => 'danger',
        default => 'warning'
    };
    $paymentClass = strtolower($invoice['payment_status']) === 'paid' ? 'success' : 'warning';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $invoice['invoice_number'] }} | ANI-CARE Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body{background:#f3f6f5;font-family:'Segoe UI',sans-serif;color:#20252b}
        .page{max-width:900px;margin:auto;padding:28px 14px 50px}
        .invoice{background:#fff;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.07);overflow:hidden}
        .head{background:#198754;color:#fff;padding:26px}.head h1{font-size:28px;margin:0;font-weight:800}
        .body{padding:26px}.meta{display:grid;grid-template-columns:repeat(2,1fr);gap:12px;background:#f6f8f7;padding:16px;border-radius:12px}
        .party-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-top:20px}.party{border:1px solid #e5ebe8;border-radius:12px;padding:16px}
        .party h3{font-size:15px;color:#198754;font-weight:800;margin-bottom:10px}.party p{margin:3px 0;font-size:14px}
        .table-wrap{margin-top:22px;overflow:auto}.table{min-width:650px}.table th{background:#f4f7f6}
        .summary{max-width:430px;margin-left:auto;margin-top:18px}.sum-row{display:flex;justify-content:space-between;padding:8px 0}.grand{border-top:2px solid #198754;margin-top:6px;padding-top:13px;font-size:21px;color:#198754;font-weight:800}
        .status-box{display:flex;gap:10px;flex-wrap:wrap;margin-top:20px}.pill{padding:8px 12px;border-radius:999px;font-size:13px;font-weight:700}
        .pill-success{background:#e7f6ed;color:#147347}.pill-warning{background:#fff3cd;color:#8a6d00}.pill-danger{background:#fde7ea;color:#b02a37}
        .actions{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:14px}.actions .btn{border-radius:10px}
        .disclaimer{margin-top:22px;background:#fff8e1;border:1px solid #ffe7a3;border-radius:10px;padding:12px;font-size:13px;color:#6a5a19}
        @media(max-width:650px){.party-grid,.meta{grid-template-columns:1fr}.body{padding:18px}.head{padding:20px}.head h1{font-size:23px}}
        @media print{body{background:#fff}.page{max-width:none;padding:0}.invoice{box-shadow:none;border-radius:0}.actions{display:none}}
    </style>
</head>
<body>
<div class="page">
    <div class="actions">
        <a href="{{ route('resident.orders.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> My Orders</a>
        <a href="{{ route('resident.orders.invoice.download', $invoice['order_id']) }}" class="btn btn-success"><i class="bi bi-file-earmark-pdf"></i> Download PDF</a>
        <button class="btn btn-outline-success" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
    </div>

    <div class="invoice">
        <div class="head">
            <div class="small opacity-75">ANI-CARE ALLACAPAN</div>
            <h1>ORDER INVOICE</h1>
            <div class="mt-2">{{ $invoice['invoice_number'] }}</div>
        </div>

        <div class="body">
            <div class="meta">
                <div><small class="text-muted">Order Number</small><br><strong>{{ $invoice['order_number'] }}</strong></div>
                <div><small class="text-muted">Date & Time</small><br><strong>{{ $invoice['date'] }} • {{ $invoice['time'] }}</strong></div>
            </div>

            <div class="party-grid">
                <div class="party">
                    <h3>BUYER</h3>
                    <p><strong>{{ $invoice['buyer_name'] }}</strong></p>
                    <p>{{ $invoice['buyer_contact'] ?: 'No contact number' }}</p>
                    <p>{{ $invoice['buyer_address'] ?: 'No address available' }}</p>
                </div>
                <div class="party">
                    <h3>SELLER / FARMER</h3>
                    <p><strong>{{ $invoice['farmer_name'] }}</strong></p>
                    <p>{{ $invoice['farmer_address'] }}</p>
                </div>
            </div>

            <div class="table-wrap">
                <table class="table align-middle">
                    <thead><tr><th>Product</th><th>Purchase By</th><th>Quantity</th><th>Unit Price</th><th class="text-end">Subtotal</th></tr></thead>
                    <tbody><tr>
                        <td><strong>{{ $invoice['product_name'] }}</strong></td>
                        <td>{{ $invoice['purchase_unit'] === 'kilo' ? 'Per Kilo' : 'Sack / Cavan' }}</td>
                        <td>{{ $invoice['quantity_display'] }}<br><small class="text-muted">Total weight: {{ number_format($invoice['quantity_kilos'], 2) }} kg</small></td>
                        <td>₱{{ number_format($invoice['unit_price'], 2) }} {{ $invoice['unit_price_label'] }}</td>
                        <td class="text-end">₱{{ number_format($invoice['subtotal'], 2) }}</td>
                    </tr></tbody>
                </table>
            </div>

            <div class="summary">
                <div class="sum-row"><span>Subtotal</span><strong>₱{{ number_format($invoice['subtotal'], 2) }}</strong></div>
                <div class="sum-row"><span>Shipping Fee</span><strong>{{ $invoice['fulfillment_type'] === 'pickup' ? 'FREE' : '₱'.number_format($invoice['shipping_fee'], 2) }}</strong></div>
                <div class="sum-row"><span>Distance</span><strong>{{ $invoice['fulfillment_type'] === 'pickup' ? 'Pickup' : number_format($invoice['distance_km'], 2).' km' }}</strong></div>
                <div class="sum-row grand"><span>GRAND TOTAL</span><span>₱{{ number_format($invoice['grand_total'], 2) }}</span></div>
            </div>

            <div class="mt-4">
                <div><strong>Order Type:</strong> {{ ucfirst($invoice['fulfillment_type']) }}</div>
                @if($invoice['fulfillment_type'] === 'delivery')
                    <div><strong>Delivery Address:</strong> {{ $invoice['delivery_address'] }}</div>
                @else
                    <div><strong>Pickup Address:</strong> {{ $invoice['pickup_address'] }}</div>
                @endif
                @if($invoice['notes'])<div class="mt-2"><strong>Notes:</strong> {{ $invoice['notes'] }}</div>@endif
            </div>

            <div class="status-box">
                <span class="pill pill-{{ $paymentClass }}">Payment: {{ strtoupper($invoice['payment_status']) }} • {{ strtoupper($invoice['payment_method']) }}</span>
                <span class="pill pill-{{ $statusClass }}">Order: {{ strtoupper($invoice['order_status']) }}</span>
            </div>

            <div class="disclaimer">
                This document is an Order Invoice / Order Confirmation for transparency. It is not an official payment receipt while the payment status is UNPAID.
            </div>
        </div>
    </div>
</div>
</body>
</html>
