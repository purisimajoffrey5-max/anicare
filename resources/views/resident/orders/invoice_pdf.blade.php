<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @page{margin:28px 34px}
    body{font-family:DejaVu Sans,sans-serif;color:#222;font-size:11px;margin:0}
    .header{background:#198754;color:#fff;padding:18px 20px;margin-bottom:18px}
    .header .title{font-size:22px;font-weight:bold;margin:3px 0}.muted{color:#666}.green{color:#198754}
    .meta{width:100%;border-collapse:collapse;margin-bottom:14px}.meta td{width:50%;background:#f5f7f6;padding:9px;border:1px solid #e6ebe8}
    .parties{width:100%;border-collapse:separate;border-spacing:8px 0;margin:0 -8px 16px}.parties td{width:50%;vertical-align:top;border:1px solid #e3e8e5;padding:10px}.party-title{color:#198754;font-weight:bold;margin-bottom:6px}
    table.items{width:100%;border-collapse:collapse;margin-top:12px}.items th{background:#f1f4f2;text-align:left;padding:8px;border:1px solid #dfe5e2}.items td{padding:8px;border:1px solid #e3e8e5;vertical-align:top}.right{text-align:right}
    .totals{width:45%;margin-left:55%;margin-top:14px;border-collapse:collapse}.totals td{padding:6px 2px}.grand td{border-top:2px solid #198754;padding-top:9px;color:#198754;font-size:14px;font-weight:bold}
    .status{margin-top:18px;padding:10px;background:#f7f8f7;border:1px solid #e4e7e5}.note{margin-top:14px;background:#fff8e1;border:1px solid #f2df9f;padding:9px;color:#65581e}
</style>
</head>
<body>
<div class="header">
    <div>ANI-CARE ALLACAPAN</div>
    <div class="title">ORDER INVOICE</div>
    <div>{{ $invoice['invoice_number'] }}</div>
</div>

<table class="meta">
<tr>
<td><span class="muted">Order Number</span><br><strong>{{ $invoice['order_number'] }}</strong></td>
<td><span class="muted">Date & Time</span><br><strong>{{ $invoice['date'] }} - {{ $invoice['time'] }}</strong></td>
</tr>
</table>

<table class="parties"><tr>
<td><div class="party-title">BUYER</div><strong>{{ $invoice['buyer_name'] }}</strong><br>{{ $invoice['buyer_contact'] }}<br>{{ $invoice['buyer_address'] }}</td>
<td><div class="party-title">SELLER / FARMER</div><strong>{{ $invoice['farmer_name'] }}</strong><br>{{ $invoice['farmer_address'] }}</td>
</tr></table>

<table class="items">
<thead><tr><th>Product</th><th>Purchase By</th><th>Quantity</th><th>Unit Price</th><th class="right">Subtotal</th></tr></thead>
<tbody><tr>
<td><strong>{{ $invoice['product_name'] }}</strong></td>
<td>{{ $invoice['purchase_unit'] === 'kilo' ? 'Per Kilo' : 'Sack / Cavan' }}</td>
<td>{{ $invoice['quantity_display'] }}<br><span class="muted">{{ number_format($invoice['quantity_kilos'],2) }} kg total</span></td>
<td>PHP {{ number_format($invoice['unit_price'],2) }} {{ $invoice['unit_price_label'] }}</td>
<td class="right">PHP {{ number_format($invoice['subtotal'],2) }}</td>
</tr></tbody>
</table>

<table class="totals">
<tr><td>Subtotal</td><td class="right"><strong>PHP {{ number_format($invoice['subtotal'],2) }}</strong></td></tr>
<tr><td>Shipping Fee</td><td class="right"><strong>{{ $invoice['fulfillment_type'] === 'pickup' ? 'FREE' : 'PHP '.number_format($invoice['shipping_fee'],2) }}</strong></td></tr>
<tr><td>Distance</td><td class="right"><strong>{{ $invoice['fulfillment_type'] === 'pickup' ? 'Pickup' : number_format($invoice['distance_km'],2).' km' }}</strong></td></tr>
<tr class="grand"><td>GRAND TOTAL</td><td class="right">PHP {{ number_format($invoice['grand_total'],2) }}</td></tr>
</table>

<div style="margin-top:18px">
<strong>Order Type:</strong> {{ ucfirst($invoice['fulfillment_type']) }}<br>
@if($invoice['fulfillment_type'] === 'delivery')
<strong>Delivery Address:</strong> {{ $invoice['delivery_address'] }}<br>
@else
<strong>Pickup Address:</strong> {{ $invoice['pickup_address'] }}<br>
@endif
@if($invoice['notes'])<strong>Notes:</strong> {{ $invoice['notes'] }}<br>@endif
</div>

<div class="status">
<strong>Payment:</strong> {{ strtoupper($invoice['payment_status']) }} / {{ strtoupper($invoice['payment_method']) }}<br>
<strong>Order Status:</strong> {{ strtoupper($invoice['order_status']) }}
</div>

<div class="note">
This is an Order Invoice / Order Confirmation for transparency. It is not an official payment receipt while payment is unpaid.
</div>
</body>
</html>
