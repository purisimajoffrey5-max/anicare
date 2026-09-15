<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Marketplace | Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    *{
      box-sizing:border-box;
    }

    html,body{
      margin:0;
      min-height:100%;
      font-family:"Segoe UI",Tahoma,Geneva,Verdana,sans-serif;
    }

    body{
      background:#f5f7fb;
      color:#1f2937;
      overflow-x:hidden;
    }

    .topbar{
      background:#198754;
      box-shadow:0 2px 8px rgba(0,0,0,.08);
    }

    .wrap{
      max-width:1100px;
      margin:0 auto;
      padding:18px 14px 70px;
    }

    .soft{
      border:1px solid rgba(0,0,0,.06);
      border-radius:18px;
      background:#fff;
      box-shadow:0 8px 20px rgba(16,24,40,.05);
    }

    .pill{
      border-radius:999px;
      padding:6px 10px;
      font-size:11px;
      font-weight:800;
      flex-shrink:0;
    }

    .section-title{
      font-weight:800;
      margin:0;
    }

    .small-note{
      color:#6b7280;
      font-size:13px;
    }

    /* =========================
       MILLERS
    ========================== */

    .millers-card{
      overflow:hidden;
    }

    .millers-header{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:12px;
    }

    .millers-title-wrap{
      min-width:0;
    }

    .millers-title{
      font-size:17px;
      font-weight:800;
      margin:0;
    }

    .millers-subtitle{
      color:#6b7280;
      font-size:12px;
      margin-top:2px;
    }

    .miller-item{
      border:1px solid #e5e7eb;
      border-radius:12px;
      padding:10px 11px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:10px;
      background:#fff;
      height:100%;
      transition:.18s ease;
    }

    .miller-item:hover{
      border-color:#b7d7c5;
      box-shadow:0 4px 12px rgba(25,135,84,.08);
    }

    .miller-info{
      min-width:0;
    }

    .miller-name{
      font-weight:700;
      font-size:13px;
      color:#111827;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
    }

    .miller-username{
      color:#6b7280;
      font-size:11px;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
    }

    .miller-extra{
      display:none;
    }

    .miller-extra.show{
      display:block;
    }

    .view-all-wrap{
      display:flex;
      justify-content:center;
      margin-top:14px;
      padding-top:12px;
      border-top:1px solid #eef0f2;
    }

    .view-all-btn{
      min-width:190px;
      border-radius:12px;
      font-weight:700;
      padding:9px 16px;
    }

    /* =========================
       PRODUCTS
    ========================== */

    .product-img{
      width:100%;
      height:180px;
      object-fit:cover;
      border-radius:14px;
      background:#e9ecef;
    }

    .no-photo{
      width:100%;
      height:180px;
      border-radius:14px;
      background:#e9ecef;
      display:flex;
      align-items:center;
      justify-content:center;
      color:#6c757d;
      font-weight:600;
    }

    .product-card{
      border:1px solid rgba(0,0,0,.06);
      border-radius:18px;
      background:#fff;
      padding:14px;
      height:100%;
      box-shadow:0 8px 20px rgba(16,24,40,.04);
    }

    .product-title{
      font-weight:700;
      font-size:18px;
      margin-bottom:4px;
      color:#1f2937;
    }

    .product-meta{
      font-size:13px;
      color:#6b7280;
      margin-bottom:4px;
    }

    .product-price{
      font-size:24px;
      font-weight:800;
      color:#198754;
      margin:8px 0 2px;
    }

    .stock-note{
      font-size:13px;
      color:#6b7280;
      margin-bottom:10px;
    }

    .view-btn{
      width:100%;
      border:none;
      border-radius:12px;
      padding:12px;
      font-size:14px;
      font-weight:700;
      background:#198754;
      color:#fff;
      text-align:center;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      text-decoration:none;
    }

    .view-btn:hover{
      background:#157347;
      color:#fff;
      text-decoration:none;
    }

    .view-btn.disabled{
      pointer-events:none;
      background:#cbd5e1;
      color:#6b7280;
    }

    /* =========================
       RESPONSIVE
    ========================== */

    @media (max-width: 768px){
      .wrap{
        padding:14px 10px 50px;
      }

      .navbar .container-fluid{
        gap:10px;
      }

      .navbar-brand{
        width:100%;
        text-align:center;
      }

      .navbar .d-flex{
        width:100%;
        display:grid !important;
        grid-template-columns:repeat(2,minmax(0,1fr));
      }

      .navbar .d-flex .btn,
      .navbar .d-flex form,
      .navbar .d-flex form button{
        width:100%;
      }

      .millers-header{
        align-items:flex-start;
      }

      .miller-name{
        font-size:12px;
      }

      .miller-username{
        font-size:10px;
      }

      .pill{
        font-size:9px;
        padding:5px 8px;
      }

      .view-all-btn{
        width:100%;
      }
    }

    @media (max-width: 575.98px){
      .wrap{
        padding-left:9px;
        padding-right:9px;
      }

      .soft{
        border-radius:14px;
      }

      .miller-item{
        padding:9px;
      }

      .product-img,
      .no-photo{
        height:160px;
      }
    }
  </style>
</head>

<body>

<nav class="navbar navbar-dark topbar">
  <div class="container-fluid px-3">

    <span class="navbar-brand fw-bold m-0">
      ANI-CARE | Admin
    </span>

    <div class="d-flex gap-2 flex-wrap">
      <a
        href="<?php echo e(route('admin.dashboard')); ?>"
        class="btn btn-outline-light btn-sm"
      >
        <i class="bi bi-arrow-left me-1"></i>
        Back
      </a>

      <a
        href="<?php echo e(route('admin.orders.index')); ?>"
        class="btn btn-light btn-sm"
      >
        <i class="bi bi-bag-check me-1"></i>
        My Orders
      </a>

      <form
        method="POST"
        action="<?php echo e(route('logout')); ?>"
      >
        <?php echo csrf_field(); ?>

        <button class="btn btn-warning btn-sm">
          <i class="bi bi-box-arrow-right me-1"></i>
          Logout
        </button>
      </form>
    </div>

  </div>
</nav>


<div class="wrap">

  <?php if(session('success')): ?>
    <div class="alert alert-success">
      <?php echo e(session('success')); ?>

    </div>
  <?php endif; ?>

  <?php if($errors->any()): ?>
    <div class="alert alert-danger">
      <?php echo e($errors->first()); ?>

    </div>
  <?php endif; ?>


  <h3 class="fw-bold mb-0">
    Marketplace Overview
  </h3>

  <div class="text-muted mb-3">
    Monitor all rice/palay posts and miller availability.
  </div>


  
  <div class="row g-3 mb-3">

    <div class="col-md-7">
      <div class="soft p-3">

        <form
          method="GET"
          action="<?php echo e(route('admin.market')); ?>"
          class="row g-2"
        >
          <div class="col-8">
            <input
              class="form-control"
              name="q"
              value="<?php echo e($q ?? ''); ?>"
              placeholder="Search variety / farmer (e.g., IR64, Juan)"
            >
          </div>

          <div class="col-4 d-grid">
            <button class="btn btn-success">
              Search
            </button>
          </div>
        </form>

      </div>
    </div>


    <div class="col-md-5">
      <div
        class="soft p-3"
        style="background:#e8f3ff;"
      >
        <div class="text-muted">
          Open Millers
        </div>

        <div class="fs-2 fw-bold">
          <?php echo e($openMillersCount ?? 0); ?>

        </div>

        <div class="text-muted small">
          Live status today
        </div>
      </div>
    </div>

  </div>


  

  <?php
    $millerList = collect($millers ?? []);
    $millerCount = $millerList->count();
    $previewLimit = 6;
  ?>

  <div class="soft p-3 mb-4 millers-card">

    <div class="millers-header">

      <div class="millers-title-wrap">
        <h5 class="millers-title">
          Millers
        </h5>

        <div class="millers-subtitle">
          Showing
          <?php echo e(min($previewLimit, $millerCount)); ?>

          of
          <?php echo e($millerCount); ?>

          miller<?php echo e($millerCount === 1 ? '' : 's'); ?>

        </div>
      </div>

      <div class="text-muted small">
        OPEN / CLOSED
      </div>

    </div>


    <div class="row g-2" id="millerGrid">

      <?php $__empty_1 = true; $__currentLoopData = $millerList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

        <?php
          $isExtra = $loop->index >= $previewLimit;
        ?>

        <div
          class="col-12 col-md-6 col-lg-4 <?php echo e($isExtra ? 'miller-extra' : ''); ?>"
          data-miller-extra="<?php echo e($isExtra ? '1' : '0'); ?>"
        >

          <div class="miller-item">

            <div class="miller-info">

              <div
                class="miller-name"
                title="<?php echo e($m->fullname ?? $m->username); ?>"
              >
                <?php echo e($m->fullname ?? $m->username); ?>

              </div>

              <div class="miller-username">
                <?php echo e('@'.$m->username); ?>

              </div>

            </div>


            <?php if($m->is_open): ?>

              <span class="pill bg-success text-white">
                OPEN
              </span>

            <?php else: ?>

              <span class="pill bg-secondary text-white">
                CLOSED
              </span>

            <?php endif; ?>

          </div>

        </div>

      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

        <div class="col-12">
          <div class="text-muted text-center py-4">
            No millers found.
          </div>
        </div>

      <?php endif; ?>

    </div>


    <?php if($millerCount > $previewLimit): ?>

      <div class="view-all-wrap">

        <button
          type="button"
          class="btn btn-outline-success view-all-btn"
          id="toggleMillersBtn"
          aria-expanded="false"
        >
          <i class="bi bi-people-fill me-1"></i>
          <span id="toggleMillersText">
            View All Millers
          </span>
        </button>

      </div>

    <?php endif; ?>

  </div>


  

  <div class="d-flex justify-content-between align-items-center mb-3">

    <h4 class="section-title">
      Rice / Palay Posts
    </h4>

    <span class="small-note">
      Latest posts
    </span>

  </div>


  <div class="row g-3">

    <?php $__empty_1 = true; $__currentLoopData = ($products ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

      <?php
        $img =
          !empty($p->photo_path)
            ? asset('storage/'.$p->photo_path)
            : null;

        $stock =
          (float) ($p->kilos_available ?? 0);

        $price =
          (float) ($p->price_per_kg ?? 0);
      ?>


      <div class="col-12 col-sm-6 col-lg-4">

        <div class="product-card">


          <?php if($img): ?>

            <img
              src="<?php echo e($img); ?>"
              class="product-img mb-3"
              alt="<?php echo e($p->name); ?>"
            >

          <?php else: ?>

            <div class="no-photo mb-3">
              No photo
            </div>

          <?php endif; ?>


          <div class="product-title">
            <?php echo e($p->name); ?>

          </div>


          <div class="product-meta">

            Type:

            <span class="badge bg-secondary text-uppercase">
              <?php echo e($p->type ?? '-'); ?>

            </span>

          </div>


          <div class="product-meta">
            By
            <?php echo e($p->user->fullname
                ?? $p->user->username
                ?? 'Unknown'); ?>

          </div>


          <div class="product-price">
            ₱<?php echo e(number_format($price, 2)); ?> / kg
          </div>


          <div class="stock-note">
            <?php echo e(number_format($stock, 2)); ?> kg available
          </div>


          <a
            href="<?php echo e(route('admin.checkout.show', $p->id)); ?>"
            class="view-btn <?php echo e($stock <= 0 ? 'disabled' : ''); ?>"
          >
            <i class="bi bi-cart-check me-1"></i>
            Order Now
          </a>

        </div>

      </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

      <div class="col-12">

        <div class="soft p-4 text-center text-muted">
          No products found.
        </div>

      </div>

    <?php endif; ?>

  </div>


  <?php if(isset($products) && method_exists($products, 'links')): ?>

    <div class="pagination-wrap mt-4 d-flex justify-content-center">
      <?php echo e($products->links()); ?>

    </div>

  <?php endif; ?>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const toggleButton =
        document.getElementById('toggleMillersBtn');

    if (!toggleButton) {
        return;
    }

    const toggleText =
        document.getElementById('toggleMillersText');

    const extraMillers =
        document.querySelectorAll('.miller-extra');

    let expanded = false;


    toggleButton.addEventListener('click', function () {

        expanded = !expanded;

        extraMillers.forEach(function (item) {

            if (expanded) {
                item.classList.add('show');
            } else {
                item.classList.remove('show');
            }

        });


        toggleButton.setAttribute(
            'aria-expanded',
            expanded ? 'true' : 'false'
        );


        toggleText.textContent =
            expanded
                ? 'Show Less Millers'
                : 'View All Millers';


        const icon =
            toggleButton.querySelector('i');

        if (icon) {

            icon.className =
                expanded
                    ? 'bi bi-chevron-up me-1'
                    : 'bi bi-people-fill me-1';
        }


        /*
         * When collapsing, scroll gently back to the Millers section.
         */
        if (!expanded) {

            document.querySelector('.millers-card')
                ?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
        }

    });

});
</script>

</body>
</html><?php /**PATH C:\xampp\htdocs\Allacapan_Anicare\resources\views/admin/marketplace.blade.php ENDPATH**/ ?>