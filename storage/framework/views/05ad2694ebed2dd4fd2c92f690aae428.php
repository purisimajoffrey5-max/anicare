<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports | ANI-CARE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        *{box-sizing:border-box}
        body{margin:0;background:#f5f7fb;color:#20252b;font-family:'Segoe UI',sans-serif}
        .topbar{position:sticky;top:0;z-index:1030;background:#198754;color:#fff;box-shadow:0 3px 14px rgba(0,0,0,.12)}
        .topbar-inner{width:min(1200px,100%);min-height:64px;margin:auto;padding:10px 14px;display:flex;justify-content:space-between;align-items:center;gap:12px}
        .brand{font-weight:800;font-size:18px}
        .page{max-width:1200px;margin:auto;padding:22px 12px 60px}
        .card-soft{border:1px solid rgba(0,0,0,.06);border-radius:16px;background:#fff;box-shadow:0 5px 18px rgba(15,23,42,.045)}
        .stat-card{height:100%;padding:16px}.stat-icon{width:42px;height:42px;border-radius:12px;background:#eaf7f0;color:#198754;display:grid;place-items:center;font-size:20px;margin-bottom:9px}
        .stat-value{font-size:25px;font-weight:800}.stat-label{font-size:12px;color:#6c757d}
        .table-wrap{overflow-x:auto}.report-table{min-width:1050px}.report-table th{white-space:nowrap;font-size:12px}.report-table td{font-size:12px;vertical-align:middle}.name{font-weight:700}.muted{font-size:11px;color:#6c757d}
        .pill{display:inline-flex;padding:5px 9px;border-radius:999px;font-size:10px;font-weight:800;text-transform:capitalize;background:#e9ecef;color:#495057}
        .pill-success{background:#d1e7dd;color:#0f5132}.pill-warning{background:#fff3cd;color:#664d03}.pill-danger{background:#f8d7da;color:#842029}.pill-info{background:#cff4fc;color:#055160}
        .detail-label{font-size:11px;color:#6c757d;font-weight:700;text-transform:uppercase;letter-spacing:.02em}.detail-value{font-size:14px;font-weight:600;word-break:break-word}
        .section-title{color:#198754;font-weight:800}.empty{padding:35px;text-align:center;color:#6c757d}
        @media(max-width:768px){.brand{font-size:15px}.page{padding:17px 10px 50px}.stat-value{font-size:21px}}
    </style>
</head>
<body>
<header class="topbar">
    <div class="topbar-inner">
        <div class="brand"><i class="bi bi-file-earmark-bar-graph-fill me-1"></i> ANI-CARE | Reports</div>
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-warning btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
    </div>
</header>

<main class="page">
    <div class="mb-4">
        <h2 class="fw-bold text-success mb-1">Transaction Reports</h2>
        <div class="text-muted">Monitor product purchases and Farmer/Miller milling transactions in one place.</div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3"><div class="card-soft stat-card"><div class="stat-icon"><i class="bi bi-receipt"></i></div><div class="stat-label">Total Transactions</div><div class="stat-value"><?php echo e(number_format($totalTransactions)); ?></div></div></div>
        <div class="col-6 col-lg-3"><div class="card-soft stat-card"><div class="stat-icon"><i class="bi bi-cart-check"></i></div><div class="stat-label">Product Purchases</div><div class="stat-value"><?php echo e(number_format($orders->count())); ?></div></div></div>
        <div class="col-6 col-lg-3"><div class="card-soft stat-card"><div class="stat-icon"><i class="bi bi-gear-wide-connected"></i></div><div class="stat-label">Milling Transactions</div><div class="stat-value"><?php echo e(number_format($millingRequests->count())); ?></div></div></div>
        <div class="col-6 col-lg-3"><div class="card-soft stat-card"><div class="stat-icon"><i class="bi bi-cash-stack"></i></div><div class="stat-label">Recorded Transaction Value</div><div class="stat-value">₱<?php echo e(number_format($productTotal + $millingTotal, 2)); ?></div></div></div>
    </div>

    <div class="card-soft p-3 mb-4">
        <form method="GET" action="<?php echo e(route('admin.reports')); ?>">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-bold">Transaction Type</label>
                    <select name="type" class="form-select form-select-sm">
                        <option value="all" <?php if($type === 'all'): echo 'selected'; endif; ?>>All Transactions</option>
                        <option value="product" <?php if($type === 'product'): echo 'selected'; endif; ?>>Product Purchases</option>
                        <option value="milling" <?php if($type === 'milling'): echo 'selected'; endif; ?>>Farmer / Miller Milling</option>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small fw-bold">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <?php $__currentLoopData = $allStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($option); ?>" <?php if(strtolower($status) === $option): echo 'selected'; endif; ?>><?php echo e(ucfirst(str_replace('_',' ', $option))); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-6 col-md-2"><label class="form-label small fw-bold">From</label><input type="date" name="from" value="<?php echo e($from); ?>" class="form-control form-control-sm"></div>
                <div class="col-6 col-md-2"><label class="form-label small fw-bold">To</label><input type="date" name="to" value="<?php echo e($to); ?>" class="form-control form-control-sm"></div>
                <div class="col-12 col-md-3 d-flex gap-2"><button class="btn btn-success btn-sm flex-grow-1"><i class="bi bi-funnel"></i> Apply Filter</button><a href="<?php echo e(route('admin.reports')); ?>" class="btn btn-outline-secondary btn-sm">Reset</a></div>
            </div>
        </form>
    </div>

    <?php if($type !== 'milling'): ?>
    <section class="card-soft p-3 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 gap-2">
            <div><h5 class="section-title mb-0"><i class="bi bi-cart-check me-1"></i> Product Purchase Transactions</h5><small class="text-muted">Resident or Admin buyer → Farmer seller</small></div>
            <span class="badge text-bg-success"><?php echo e($orders->count()); ?> records</span>
        </div>
        <div class="table-wrap">
            <?php if($orders->isNotEmpty()): ?>
            <table class="table table-bordered table-hover report-table mb-0">
                <thead class="table-light"><tr><th>Date</th><th>Buyer</th><th>Buyer Role</th><th>Farmer / Seller</th><th>Product</th><th>Qty (kg)</th><th>Total</th><th>Payment</th><th>Order Status</th><th>Action</th></tr></thead>
                <tbody>
                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $buyer = $order->resident;
                        $buyerRole = strtolower((string)($buyer->role ?? 'resident')) === 'admin' ? 'Admin' : 'Resident';
                        $total = $order->grand_total ?? $order->total_price ?? 0;
                        $payment = $order->payment_status ?? 'unpaid';
                        $orderStatus = strtolower((string)($order->status ?? 'pending'));
                    ?>
                    <tr>
                        <td><?php echo e(optional($order->created_at)->timezone('Asia/Manila')->format('M d, Y h:i A')); ?></td>
                        <td><div class="name"><?php echo e($order->buyer_name ?? ($buyer->fullname ?? $buyer->username ?? 'Unknown')); ?></div><div class="muted">Order #<?php echo e($order->id); ?></div></td>
                        <td><span class="pill pill-info"><?php echo e($buyerRole); ?></span></td>
                        <td><?php echo e($order->farmer->fullname ?? $order->farmer->username ?? $order->farmer_name_snapshot ?? 'Unknown Farmer'); ?></td>
                        <td><?php echo e($order->product->name ?? $order->product_name_snapshot ?? 'Product unavailable'); ?></td>
                        <td><?php echo e(number_format((float)($order->total_kilos ?? $order->quantity_kilos ?? 0), 2)); ?></td>
                        <td class="fw-bold text-success">₱<?php echo e(number_format((float)$total, 2)); ?></td>
                        <td><span class="pill <?php echo e(strtolower($payment)==='paid' ? 'pill-success' : 'pill-warning'); ?>"><?php echo e($payment); ?></span></td>
                        <td><span class="pill <?php echo e(in_array($orderStatus,['completed','complete']) ? 'pill-success' : (in_array($orderStatus,['cancelled','rejected']) ? 'pill-danger' : 'pill-warning')); ?>"><?php echo e(str_replace('_',' ', $orderStatus)); ?></span></td>
                        <td><button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#orderDetail<?php echo e($order->id); ?>"><i class="bi bi-eye"></i> View</button></td>
                    </tr>
                    <div class="modal fade" id="orderDetail<?php echo e($order->id); ?>" tabindex="-1" aria-labelledby="orderDetailLabel<?php echo e($order->id); ?>" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <div><h5 class="modal-title fw-bold" id="orderDetailLabel<?php echo e($order->id); ?>"><i class="bi bi-receipt me-1"></i> Order #<?php echo e($order->id); ?> — Full Details</h5><small>Product Purchase Transaction</small></div>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body"><div class="row g-3">
                                    <div class="col-12"><h6 class="text-success fw-bold border-bottom pb-2">Transaction</h6></div>
                                    <div class="col-md-6"><div class="detail-label">Order ID</div><div class="detail-value">#<?php echo e($order->id); ?></div></div>
                                    <div class="col-md-6"><div class="detail-label">Date & Time</div><div class="detail-value"><?php echo e(optional($order->created_at)->timezone('Asia/Manila')->format('M d, Y h:i A')); ?></div></div>
                                    <div class="col-md-6"><div class="detail-label">Order Status</div><div class="detail-value text-capitalize"><?php echo e(str_replace('_',' ', $orderStatus)); ?></div></div>
                                    <div class="col-md-6"><div class="detail-label">Payment Status</div><div class="detail-value text-capitalize"><?php echo e($payment); ?></div></div>
                                    <div class="col-12 mt-4"><h6 class="text-success fw-bold border-bottom pb-2">Buyer & Seller</h6></div>
                                    <div class="col-md-6"><div class="detail-label">Buyer</div><div class="detail-value"><?php echo e($order->buyer_name ?? ($buyer->fullname ?? $buyer->username ?? 'Unknown')); ?></div></div>
                                    <div class="col-md-6"><div class="detail-label">Buyer Role</div><div class="detail-value"><?php echo e($buyerRole); ?></div></div>
                                    <div class="col-md-6"><div class="detail-label">Buyer Contact</div><div class="detail-value"><?php echo e($order->contact_number ?? ($buyer->mobile_number ?? $buyer->contact_number ?? $buyer->phone ?? '—')); ?></div></div>
                                    <div class="col-md-6"><div class="detail-label">Farmer / Seller</div><div class="detail-value"><?php echo e($order->farmer->fullname ?? $order->farmer->username ?? $order->farmer_name_snapshot ?? 'Unknown Farmer'); ?></div></div>
                                    <div class="col-12 mt-4"><h6 class="text-success fw-bold border-bottom pb-2">Product & Amount</h6></div>
                                    <div class="col-md-6"><div class="detail-label">Product</div><div class="detail-value"><?php echo e($order->product->name ?? $order->product_name_snapshot ?? 'Product unavailable'); ?></div></div>
                                    <div class="col-md-3"><div class="detail-label">Quantity</div><div class="detail-value"><?php echo e(number_format((float)($order->total_kilos ?? $order->quantity_kilos ?? 0), 2)); ?> kg</div></div>
                                    <div class="col-md-3"><div class="detail-label">Unit Price</div><div class="detail-value">₱<?php echo e(number_format((float)($order->unit_price ?? 0), 2)); ?></div></div>
                                    <div class="col-md-6"><div class="detail-label">Total Price</div><div class="detail-value text-success">₱<?php echo e(number_format((float)$total, 2)); ?></div></div>
                                    <div class="col-md-6"><div class="detail-label">Payment Method</div><div class="detail-value text-capitalize"><?php echo e(str_replace('_',' ', $order->payment_method ?? '—')); ?></div></div>
                                    <div class="col-12 mt-4"><h6 class="text-success fw-bold border-bottom pb-2">Fulfillment & Delivery</h6></div>
                                    <div class="col-md-6"><div class="detail-label">Fulfillment Type</div><div class="detail-value text-capitalize"><?php echo e(str_replace('_',' ', $order->fulfillment_type ?? '—')); ?></div></div>
                                    <div class="col-md-6"><div class="detail-label">Delivery Address</div><div class="detail-value"><?php echo e($order->delivery_address ?? '—'); ?></div></div>
                                    <div class="col-md-6"><div class="detail-label">Pickup Address</div><div class="detail-value"><?php echo e($order->pickup_address ?? '—'); ?></div></div>
                                    <div class="col-md-3"><div class="detail-label">Latitude</div><div class="detail-value"><?php echo e($order->delivery_latitude ?? '—'); ?></div></div>
                                    <div class="col-md-3"><div class="detail-label">Longitude</div><div class="detail-value"><?php echo e($order->delivery_longitude ?? '—'); ?></div></div>
                                    <div class="col-12"><div class="detail-label">Notes</div><div class="detail-value"><?php echo e($order->notes ?? '—'); ?></div></div>
                                </div></div>
                                <div class="modal-footer"><button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php else: ?> <div class="empty">No product purchase transactions found for the selected filters.</div> <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if($type !== 'product'): ?>
    <section class="card-soft p-3 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 gap-2">
            <div><h5 class="section-title mb-0"><i class="bi bi-gear-wide-connected me-1"></i> Farmer / Miller Transactions</h5><small class="text-muted">Farmer or Admin requester → Miller service transaction</small></div>
            <span class="badge text-bg-success"><?php echo e($millingRequests->count()); ?> records</span>
        </div>
        <div class="table-wrap">
            <?php if($millingRequests->isNotEmpty()): ?>
            <table class="table table-bordered table-hover report-table mb-0">
                <thead class="table-light"><tr><th>Date</th><th>Requester</th><th>Requester Role</th><th>Miller</th><th>Quantity</th><th>Milling Fee</th><th>Total</th><th>Payment</th><th>Milling Status</th><th>Schedule</th><th>Action</th></tr></thead>
                <tbody>
                <?php $__currentLoopData = $millingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $milling): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $requester = $milling->actual_requester;
                        $requesterRole = ucfirst($milling->actual_requester_role);
                        $quantity = $milling->quantity_value ?? $milling->kilos ?? 0;
                        $unit = $milling->quantity_unit ?? 'kg';
                        $millingStatus = strtolower((string)($milling->status ?? 'pending'));
                        $paymentStatus = strtolower((string)($milling->payment_status ?? 'unpaid'));
                    ?>
                    <tr>
                        <td><?php echo e(optional($milling->created_at)->timezone('Asia/Manila')->format('M d, Y h:i A')); ?></td>
                        <td><div class="name"><?php echo e($requester?->fullname ?? $requester?->username ?? 'Unknown'); ?></div><div class="muted">Milling #<?php echo e($milling->id); ?></div></td>
                        <td><span class="pill pill-info"><?php echo e($requesterRole); ?></span></td>
                        <td><?php echo e($milling->miller?->fullname ?? $milling->miller?->username ?? 'Not assigned'); ?></td>
                        <td><?php echo e(number_format((float)$quantity, 2)); ?> <?php echo e($unit); ?></td>
                        <td>₱<?php echo e(number_format((float)($milling->milling_fee_per_kg ?? 0), 2)); ?>/kg</td>
                        <td class="fw-bold text-success">₱<?php echo e(number_format((float)($milling->total_amount ?? 0), 2)); ?></td>
                        <td><span class="pill <?php echo e($paymentStatus==='paid' ? 'pill-success' : 'pill-warning'); ?>"><?php echo e($paymentStatus); ?></span></td>
                        <td><span class="pill <?php echo e($millingStatus==='completed' ? 'pill-success' : (in_array($millingStatus,['cancelled','rejected']) ? 'pill-danger' : 'pill-warning')); ?>"><?php echo e(str_replace('_',' ', $millingStatus)); ?></span></td>
                        <td><?php echo e($milling->scheduled_at ? $milling->scheduled_at->timezone('Asia/Manila')->format('M d, Y h:i A') : '—'); ?></td>
                        <td><button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#millingDetail<?php echo e($milling->id); ?>"><i class="bi bi-eye"></i> View</button></td>
                    </tr>
                    <div class="modal fade" id="millingDetail<?php echo e($milling->id); ?>" tabindex="-1" aria-labelledby="millingDetailLabel<?php echo e($milling->id); ?>" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
                            <div class="modal-header bg-success text-white"><div><h5 class="modal-title fw-bold" id="millingDetailLabel<?php echo e($milling->id); ?>"><i class="bi bi-gear-wide-connected me-1"></i> Milling #<?php echo e($milling->id); ?> — Full Details</h5><small>Farmer / Miller Transaction</small></div><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button></div>
                            <div class="modal-body"><div class="row g-3">
                                <div class="col-12"><h6 class="text-success fw-bold border-bottom pb-2">Transaction</h6></div>
                                <div class="col-md-6"><div class="detail-label">Milling ID</div><div class="detail-value">#<?php echo e($milling->id); ?></div></div>
                                <div class="col-md-6"><div class="detail-label">Created</div><div class="detail-value"><?php echo e(optional($milling->created_at)->timezone('Asia/Manila')->format('M d, Y h:i A')); ?></div></div>
                                <div class="col-md-6"><div class="detail-label">Milling Status</div><div class="detail-value text-capitalize"><?php echo e(str_replace('_',' ', $millingStatus)); ?></div></div>
                                <div class="col-md-6"><div class="detail-label">Payment Status</div><div class="detail-value text-capitalize"><?php echo e($paymentStatus); ?></div></div>
                                <div class="col-12 mt-4"><h6 class="text-success fw-bold border-bottom pb-2">Requester & Miller</h6></div>
                                <div class="col-md-6"><div class="detail-label">Requester</div><div class="detail-value"><?php echo e($requester?->fullname ?? $requester?->username ?? $milling->requester_name_snapshot ?? 'Unknown Requester'); ?></div></div>
                                <div class="col-md-6"><div class="detail-label">Requester Role</div><div class="detail-value"><?php echo e($requesterRole); ?></div></div>
                                <div class="col-md-6"><div class="detail-label">Requester Contact</div><div class="detail-value"><?php echo e($milling->requester_contact_snapshot ?? ($requester?->mobile_number ?? $requester?->contact_number ?? $requester?->phone ?? '—')); ?></div></div>
                                <div class="col-md-6"><div class="detail-label">Requester Address</div><div class="detail-value"><?php echo e($milling->requester_address_snapshot ?? ($requester?->address ?? '—')); ?></div></div>
                                <div class="col-md-6"><div class="detail-label">Miller</div><div class="detail-value"><?php echo e($milling->miller?->fullname ?? $milling->miller?->username ?? 'Not assigned'); ?></div></div>
                                <div class="col-12 mt-4"><h6 class="text-success fw-bold border-bottom pb-2">Milling Details</h6></div>
                                <div class="col-md-4"><div class="detail-label">Quantity</div><div class="detail-value"><?php echo e(number_format((float)$quantity, 2)); ?> <?php echo e($unit); ?></div></div>
                                <div class="col-md-4"><div class="detail-label">Milling Fee / kg</div><div class="detail-value">₱<?php echo e(number_format((float)($milling->milling_fee_per_kg ?? 0), 2)); ?></div></div>
                                <div class="col-md-4"><div class="detail-label">Total Amount</div><div class="detail-value text-success">₱<?php echo e(number_format((float)($milling->total_amount ?? 0), 2)); ?></div></div>
                                <div class="col-md-6"><div class="detail-label">Payment Method</div><div class="detail-value text-capitalize"><?php echo e(str_replace('_',' ', $milling->payment_method ?? '—')); ?></div></div>
                                <div class="col-md-6"><div class="detail-label">Preferred Date</div><div class="detail-value"><?php echo e($milling->preferred_date ? $milling->preferred_date->format('M d, Y') : '—'); ?></div></div>
                                <div class="col-md-6"><div class="detail-label">Scheduled At</div><div class="detail-value"><?php echo e($milling->scheduled_at ? $milling->scheduled_at->timezone('Asia/Manila')->format('M d, Y h:i A') : '—'); ?></div></div>
                                <div class="col-md-6"><div class="detail-label">Transport Type</div><div class="detail-value text-capitalize"><?php echo e(str_replace('_',' ', $milling->transport_type ?? '—')); ?></div></div>
                                <div class="col-12"><div class="detail-label">Notes / Remarks</div><div class="detail-value"><?php echo e($milling->notes ?? $milling->remarks ?? '—'); ?></div></div>
                                <div class="col-12 mt-4"><h6 class="text-success fw-bold border-bottom pb-2">Workflow Dates</h6></div>
                                <div class="col-md-4"><div class="detail-label">Started</div><div class="detail-value"><?php echo e($milling->started_at ? $milling->started_at->timezone('Asia/Manila')->format('M d, Y h:i A') : '—'); ?></div></div>
                                <div class="col-md-4"><div class="detail-label">Finished</div><div class="detail-value"><?php echo e($milling->finished_at ? $milling->finished_at->timezone('Asia/Manila')->format('M d, Y h:i A') : '—'); ?></div></div>
                                <div class="col-md-4"><div class="detail-label">Completed</div><div class="detail-value"><?php echo e($milling->completed_at ? $milling->completed_at->timezone('Asia/Manila')->format('M d, Y h:i A') : '—'); ?></div></div>
                                <div class="col-md-6"><div class="detail-label">Paid At</div><div class="detail-value"><?php echo e($milling->paid_at ? $milling->paid_at->timezone('Asia/Manila')->format('M d, Y h:i A') : '—'); ?></div></div>
                                <div class="col-md-6"><div class="detail-label">Requester Confirmed</div><div class="detail-value"><?php echo e($milling->requester_confirmed_at ? $milling->requester_confirmed_at->timezone('Asia/Manila')->format('M d, Y h:i A') : '—'); ?></div></div>
                            </div></div>
                            <div class="modal-footer"><button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button></div>
                        </div></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php else: ?> <div class="empty">No milling transactions found for the selected filters.</div> <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Allacapan_Anicare\resources\views/admin/reports.blade.php ENDPATH**/ ?>