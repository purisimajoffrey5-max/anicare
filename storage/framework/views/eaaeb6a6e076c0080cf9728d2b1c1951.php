<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Milling Requests | ANI-CARE Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        *{box-sizing:border-box}
        html,body{margin:0;min-height:100%;font-family:"Segoe UI",sans-serif}
        body{background:#f4f7f6;color:#1f2937}

        .topbar{background:#198754;color:#fff}
        .topbar-inner{
            min-height:60px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:9px;
            padding:9px 14px
        }

        .brand{
            font-size:18px;
            font-weight:850
        }

        .actions-top{
            display:flex;
            gap:7px
        }

        .page{
            max-width:980px;
            margin:auto;
            padding:22px 14px 60px
        }

        .title{
            margin:0;
            color:#198754;
            font-size:30px;
            font-weight:850
        }

        .subtitle{
            color:#6c757d;
            margin-top:4px;
            margin-bottom:18px
        }

        .request-card{
            background:#fff;
            border:1px solid #e3e8e5;
            border-radius:16px;
            padding:16px;
            margin-bottom:11px;
            box-shadow:0 5px 16px rgba(15,23,42,.04)
        }

        .head{
            display:flex;
            justify-content:space-between;
            gap:12px;
            align-items:flex-start
        }

        .request-id{
            font-size:11px;
            color:#6b7280
        }

        .miller-name{
            font-size:18px;
            font-weight:850;
            margin-top:2px
        }

        .pill{
            padding:6px 10px;
            border-radius:999px;
            font-size:9px;
            font-weight:850
        }

        .pending{
            background:#fff3cd;
            color:#755b00
        }

        .active{
            background:#e8f1ff;
            color:#174ea6
        }

        .success{
            background:#d1e7dd;
            color:#0f5132
        }

        .cancelled{
            background:#eceff1;
            color:#495057
        }

        .info-grid{
            display:grid;
            grid-template-columns:repeat(4,minmax(0,1fr));
            gap:8px;
            margin-top:13px
        }

        .info{
            background:#f8faf9;
            border:1px solid #edf1ef;
            border-radius:11px;
            padding:9px
        }

        .label{
            font-size:9px;
            color:#6b7280
        }

        .value{
            font-size:12px;
            font-weight:750;
            margin-top:2px;
            overflow-wrap:anywhere
        }

        .expected{
            margin-top:10px;
            padding:10px 11px;
            border:1px solid #cfe2ff;
            border-radius:11px;
            background:#eef6ff;
            color:#174ea6;
            font-size:11px
        }

        .card-actions{
            display:flex;
            gap:7px;
            flex-wrap:wrap;
            margin-top:12px
        }

        .card-actions .btn{
            font-size:11px;
            border-radius:9px
        }

        .empty{
            background:#fff;
            border:1px solid #e3e8e5;
            border-radius:16px;
            padding:45px 20px;
            text-align:center;
            color:#6b7280
        }

        @media(max-width:767px){
            .page{
                padding:16px 10px 50px
            }

            .title{
                font-size:25px
            }

            .info-grid{
                grid-template-columns:repeat(2,minmax(0,1fr))
            }

            .brand{
                font-size:15px
            }

            .topbar .btn span{
                display:none
            }
        }
    </style>
</head>

<body>

<header class="topbar">
    <div class="topbar-inner">

        <div class="brand">
            ANI-CARE | Admin
        </div>

        <div class="actions-top">

            <a
                href="<?php echo e(route('admin.milling.create')); ?>"
                class="btn btn-light btn-sm"
            >
                <i class="bi bi-plus-circle"></i>
                <span>New Request</span>
            </a>

            <a
                href="<?php echo e(route('admin.dashboard')); ?>"
                class="btn btn-warning btn-sm"
            >
                <i class="bi bi-arrow-left"></i>
                <span>Back</span>
            </a>

        </div>

    </div>
</header>

<main class="page">

    <h1 class="title">
        <i class="bi bi-clipboard-check me-1"></i>
        My Milling Requests
    </h1>

    <div class="subtitle">
        Track milling requests you submitted to Millers.
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success rounded-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger rounded-4">
            <?php echo e($errors->first()); ?>

        </div>
    <?php endif; ?>

    <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

        <?php
            $status = strtolower((string) ($request->status ?? 'pending'));

            $statusClass = match($status) {
                'completed', 'finished' => 'success',
                'cancelled', 'rejected' => 'cancelled',
                'accepted', 'scheduled', 'in_progress' => 'active',
                default => 'pending',
            };

            $quantity =
                $request->quantity_kilos
                ?? $request->quantity_kg
                ?? $request->kilos
                ?? $request->quantity
                ?? $request->weight_kg
                ?? 0;

            $productType =
                $request->product_type
                ?? $request->product
                ?? $request->rice_type
                ?? $request->crop_type
                ?? $request->palay_type
                ?? '-';

            $schedule = $request->scheduled_at ?? null;
        ?>

        <article class="request-card">

            <div class="head">

                <div>
                    <div class="request-id">
                        Milling Request #<?php echo e($request->id); ?>

                    </div>

                    <div class="miller-name">
                        <?php echo e($request->miller?->fullname
                            ?? $request->miller?->username
                            ?? 'Miller'); ?>

                    </div>

                    <div class="small text-muted">
                        Miller
                    </div>
                </div>

                <span class="pill <?php echo e($statusClass); ?>">
                    <?php echo e(strtoupper(str_replace('_',' ', $status))); ?>

                </span>

            </div>

            <div class="info-grid">

                <div class="info">
                    <div class="label">Product</div>
                    <div class="value"><?php echo e($productType); ?></div>
                </div>

                <div class="info">
                    <div class="label">Quantity</div>
                    <div class="value">
                        <?php echo e(number_format((float)$quantity,2)); ?> kg
                    </div>
                </div>

                <div class="info">
                    <div class="label">Payment</div>
                    <div class="value">
                        <?php echo e(strtoupper($request->payment_status ?? 'unpaid')); ?>

                    </div>
                </div>

                <div class="info">
                    <div class="label">Submitted</div>
                    <div class="value">
                        <?php echo e($request->created_at
                            ? $request->created_at
                                ->copy()
                                ->timezone('Asia/Manila')
                                ->format('M d, h:i A')
                            : '-'); ?>

                    </div>
                </div>

            </div>

            <?php if($schedule): ?>
                <div class="expected">
                    <i class="bi bi-calendar-check me-1"></i>
                    Milling Schedule:
                    <strong>
                        <?php echo e($schedule
                            ->copy()
                            ->timezone('Asia/Manila')
                            ->format('M d, Y h:i A')); ?>

                    </strong>
                </div>
            <?php endif; ?>

            <div class="card-actions">

                <a
                    href="<?php echo e(route('admin.milling.show', $request->id)); ?>"
                    class="btn btn-outline-success"
                >
                    <i class="bi bi-eye"></i>
                    View Details
                </a>

                <?php if(in_array($status, ['pending','accepted','scheduled'], true)): ?>

                    <form
                        method="POST"
                        action="<?php echo e(route('admin.milling.cancel', $request->id)); ?>"
                        class="m-0"
                    >
                        <?php echo csrf_field(); ?>

                        <button
                            type="submit"
                            class="btn btn-outline-danger"
                            onclick="return confirm('Cancel this milling request?')"
                        >
                            <i class="bi bi-x-circle"></i>
                            Cancel
                        </button>
                    </form>

                <?php endif; ?>

            </div>

        </article>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

        <div class="empty">

            <i class="bi bi-gear-wide-connected fs-1 d-block mb-2"></i>

            No milling requests yet.

            <div class="mt-3">
                <a
                    href="<?php echo e(route('admin.milling.create')); ?>"
                    class="btn btn-success"
                >
                    Request Milling
                </a>
            </div>

        </div>

    <?php endif; ?>

    <?php if($requests->hasPages()): ?>
        <div class="mt-4 d-flex justify-content-center">
            <?php echo e($requests->links()); ?>

        </div>
    <?php endif; ?>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html><?php /**PATH C:\xampp\htdocs\Allacapan_Anicare\resources\views/admin/milling/index.blade.php ENDPATH**/ ?>