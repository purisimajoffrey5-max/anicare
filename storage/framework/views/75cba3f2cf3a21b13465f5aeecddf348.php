<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>System Under Maintenance | ANI-CARE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { min-height:100vh; margin:0; background:#f5f7fb; color:#20252b; font-family:'Segoe UI',sans-serif; display:grid; place-items:center; padding:20px; }
        .box { width:min(680px,100%); background:#fff; border-radius:24px; padding:42px 32px; text-align:center; box-shadow:0 14px 40px rgba(15,23,42,.08); }
        .icon { width:82px; height:82px; margin:0 auto 20px; border-radius:50%; display:grid; place-items:center; background:#fff3cd; color:#997404; font-size:38px; }
    </style>
</head>
<body>
<div class="box">
    <div class="icon"><i class="bi bi-tools"></i></div>
    <h1 class="fw-bold text-success mb-3">System Under Maintenance</h1>
    <p class="text-muted mb-4" style="white-space:pre-line"><?php echo e($message); ?></p>
    <div class="small text-muted mb-4">Thank you for your patience.</div>
    <a href="<?php echo e(route('login')); ?>" class="btn btn-success px-4">
        <i class="bi bi-box-arrow-in-right me-1"></i> Login
    </a>
</div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Allacapan_Anicare\resources\views/maintenance/index.blade.php ENDPATH**/ ?>