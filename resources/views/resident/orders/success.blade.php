<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Successful | ANI-CARE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body{background:#f4f7f6;font-family:'Segoe UI',sans-serif;color:#20252b}
        .wrap{max-width:720px;margin:auto;padding:35px 15px}
        .success-card{background:#fff;border-radius:22px;padding:30px;box-shadow:0 10px 35px rgba(0,0,0,.08);text-align:center}
        .success-icon{width:78px;height:78px;margin:0 auto 18px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#eaf7f0;color:#198754;font-size:40px}
        .order-code{background:#f5f7f6;border-radius:12px;padding:14px;margin:20px 0;font-weight:700}
        .action-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;margin-top:22px}
        .btn{min-height:48px;display:flex;align-items:center;justify-content:center;gap:7px;border-radius:11px;font-weight:650}
        .note{margin-top:18px;color:#6c757d;font-size:13px;line-height:1.5}
        @media(max-width:520px){.wrap{padding:22px 12px}.success-card{padding:23px 16px}.action-grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="wrap">
    <div class="success-card">
        <div class="success-icon"><i class="bi bi-check-lg"></i></div>
        <h2 class="fw-bold text-success">Order Placed Successfully!</h2>
        <p class="text-muted mb-0">Your order has been recorded and sent to the farmer.</p>

        <div class="order-code">
            <div class="small text-muted fw-normal">Order / Invoice Number</div>
            {{ $invoice['invoice_number'] }}
        </div>

        <div class="text-start bg-light rounded-4 p-3">
            <div class="d-flex justify-content-between mb-2"><span>Product</span><strong>{{ $invoice['product_name'] }}</strong></div>
            <div class="d-flex justify-content-between mb-2"><span>Quantity</span><strong>{{ $invoice['quantity_display'] }}</strong></div>
            <div class="d-flex justify-content-between mb-2"><span>Order Type</span><strong>{{ ucfirst($invoice['fulfillment_type']) }}</strong></div>
            <div class="d-flex justify-content-between"><span>Grand Total</span><strong class="text-success">₱{{ number_format($invoice['grand_total'], 2) }}</strong></div>
        </div>

        <div class="action-grid">
            <a href="{{ route('resident.orders.invoice.show', $invoice['order_id']) }}" class="btn btn-success">
                <i class="bi bi-receipt"></i> View Invoice
            </a>
            <a href="{{ route('resident.orders.invoice.download', $invoice['order_id']) }}" class="btn btn-outline-success">
                <i class="bi bi-file-earmark-pdf"></i> Download PDF
            </a>
            <a href="{{ route('resident.orders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-bag-check"></i> My Orders
            </a>
            <a href="{{ route('resident.marketplace') }}" class="btn btn-outline-secondary">
                <i class="bi bi-shop"></i> Marketplace
            </a>
        </div>

        <div class="note">
            This is an <strong>Order Invoice / Order Confirmation</strong>. If payment is still marked unpaid, it is not yet an official payment receipt.
        </div>
    </div>
</div>
</body>
</html>
