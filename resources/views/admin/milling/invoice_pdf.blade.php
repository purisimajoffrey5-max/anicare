<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>{{ $invoice['invoice_number'] }}</title>
<style>
    @page{margin:24px}
    body{font-family:DejaVu Sans,sans-serif;font-size:11px;color:#222}
    .header{background:#198754;color:#fff;padding:18px}
    .title{font-size:23px;font-weight:bold;margin:4px 0}
    table{width:100%;border-collapse:collapse}
    .meta,.parties{margin-top:15px}
    .meta td,.parties td{width:50%;padding:10px;border:1px solid #e2e6e4;vertical-align:top}
    .muted{color:#6c757d;font-size:9px}
    .party-title{font-weight:bold;color:#198754;font-size:10px;margin-bottom:5px}
    .items{margin-top:18px}
    .items th{background:#f1f4f2;padding:8px;text-align:left;border-bottom:1px solid #ccd4d0}
    .items td{padding:9px;border-bottom:1px solid #e4e8e6}
    .right{text-align:right}
    .totals{width:52%;margin-left:auto;margin-top:14px}
    .totals td{padding:6px 4px}
    .grand td{border-top:2px solid #198754;padding-top:9px;color:#198754;font-size:14px;font-weight:bold}
    .status{margin-top:18px;padding:10px;background:#f7f8f7;border:1px solid #e4e7e5}
    .note{margin-top:14px;background:#fff8e1;border:1px solid #f2df9f;padding:9px;color:#65581e}
</style>
</head>

<body>

<div class="header">
    <div>ANI-CARE ALLACAPAN</div>
    <div class="title">MILLING INVOICE</div>
    <div>{{ $invoice['invoice_number'] }}</div>
</div>


<table class="meta">
    <tr>
        <td>
            <span class="muted">Milling Request</span><br>
            <strong>#{{ $invoice['request_id'] }}</strong>
        </td>

        <td>
            <span class="muted">Date & Time</span><br>
            <strong>{{ $invoice['date'] }} - {{ $invoice['time'] }}</strong>
        </td>
    </tr>
</table>


<table class="parties">
    <tr>
        <td>
            <div class="party-title">REQUESTER / ADMIN</div>
            <strong>{{ $invoice['requester_name'] }}</strong><br>
            {{ $invoice['requester_address'] ?: '' }}
        </td>

        <td>
            <div class="party-title">SERVICE PROVIDER / MILLER</div>
            <strong>{{ $invoice['miller_name'] }}</strong><br>
            {{ $invoice['miller_address'] ?: '' }}
        </td>
    </tr>
</table>


<table class="items">
    <thead>
    <tr>
        <th>Product</th>
        <th>Unit</th>
        <th>Quantity</th>
        <th>Fee/kg</th>
        <th class="right">Milling Fee</th>
    </tr>
    </thead>

    <tbody>
    <tr>
        <td>PALAY</td>

        <td>
            {{ $invoice['quantity_unit'] === 'sack'
                ? 'Sack / Cavan'
                : 'Kilogram' }}
        </td>

        <td>
            {{ $invoice['quantity_display'] }}<br>
            <span class="muted">
                {{ number_format($invoice['quantity_kilos'],2) }} kg total
            </span>
        </td>

        <td>
            PHP {{ number_format($invoice['milling_fee_per_kg'],2) }}
        </td>

        <td class="right">
            PHP {{ number_format($invoice['milling_total'],2) }}
        </td>
    </tr>
    </tbody>
</table>


<table class="totals">

    <tr>
        <td>Milling Fee</td>
        <td class="right">
            PHP {{ number_format($invoice['milling_total'],2) }}
        </td>
    </tr>

    <tr>
        <td>Transport</td>
        <td class="right">
            {{ $invoice['transport_type'] === 'pickup'
                ? 'Pickup by Miller'
                : 'Admin Delivery' }}
        </td>
    </tr>

    <tr>
        <td>Distance</td>
        <td class="right">
            {{ $invoice['transport_type'] === 'pickup' && $invoice['shipping_distance_km'] !== null
                ? number_format($invoice['shipping_distance_km'],2).' km'
                : '-' }}
        </td>
    </tr>

    <tr>
        <td>Shipping Fee</td>
        <td class="right">
            {{ $invoice['transport_type'] === 'delivery'
                ? 'FREE'
                : 'PHP '.number_format($invoice['shipping_fee'],2) }}
        </td>
    </tr>

    <tr class="grand">
        <td>GRAND TOTAL</td>
        <td class="right">
            PHP {{ number_format($invoice['grand_total'],2) }}
        </td>
    </tr>

</table>


<div style="margin-top:18px">

    @if($invoice['transport_type'] === 'pickup')
        <strong>Pickup Address:</strong>
        {{ $invoice['pickup_address'] ?: $invoice['requester_address'] ?: 'Not available' }}
        <br>
    @else
        <strong>Transport:</strong>
        Admin will deliver the Palay to the selected Miller.
        <br>
    @endif

    <strong>Preferred Date:</strong>
    {{ $invoice['preferred_date'] ?: 'Not specified' }}
    <br>

    <strong>Milling Schedule:</strong>
    {{ $invoice['scheduled_at'] ?: 'Not scheduled yet' }}

    @if($invoice['notes'])
        <br>
        <strong>Notes:</strong>
        {{ $invoice['notes'] }}
    @endif

</div>


<div class="status">
    <strong>Payment:</strong>
    {{ strtoupper($invoice['payment_status']) }}
    <br>

    <strong>Status:</strong>
    {{ strtoupper(str_replace('_',' ', $invoice['status'])) }}
</div>


<div class="note">
    @if($invoice['fee_is_estimate'])
        Initial milling estimate uses PHP 2.50/kg.
        The Miller may update the final milling rate when setting the schedule.
        <br>
    @endif

    Milling Transaction Invoice / Confirmation.
    This is not an official payment receipt while payment is unpaid.
</div>

</body>
</html>