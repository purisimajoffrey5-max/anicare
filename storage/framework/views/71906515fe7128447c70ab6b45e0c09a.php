<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | ANI-CARE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    * { box-sizing: border-box; }

    html, body {
      min-height: 100%;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background: #f5f7fb;
      color: #20252b;
    }

    .topbar {
      position: sticky;
      top: 0;
      z-index: 1030;
      background: #198754;
      color: #fff;
      box-shadow: 0 3px 14px rgba(0,0,0,.12);
    }

    .topbar-inner {
      width: min(1200px, 100%);
      min-height: 64px;
      margin: 0 auto;
      padding: 10px 14px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
    }

    .brand {
      font-weight: 800;
      font-size: 18px;
      white-space: nowrap;
    }

    .top-actions {
      display: flex;
      align-items: center;
      gap: 8px;
      min-width: 0;
    }

    .admin-name {
      max-width: 190px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .page-wrap {
      max-width: 1200px;
      margin: 0 auto;
      padding: 22px 12px 60px;
    }

    .dash-card {
      border: 1px solid rgba(0,0,0,.06);
      border-radius: 16px;
      background: #fff;
      height: 100%;
      box-shadow: 0 5px 18px rgba(15,23,42,.045);
      transition: .18s;
    }

    .dash-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 28px rgba(25,135,84,.12);
    }

    .card-link {
      text-decoration: none;
      color: inherit;
      display: block;
      height: 100%;
    }

    .module-icon {
      width: 44px;
      height: 44px;
      display: grid;
      place-items: center;
      border-radius: 12px;
      background: #eaf7f0;
      color: #198754;
      font-size: 20px;
      margin-bottom: 12px;
    }

    /* Special milling cards */
    .milling-request-card {
      border-color: rgba(13,110,253,.18);
      background: linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
    }

    .milling-request-card .module-icon {
      background: #e8f1ff;
      color: #0d6efd;
    }

    .milling-track-card {
      border-color: rgba(111,66,193,.16);
      background: linear-gradient(180deg, #ffffff 0%, #fbf9ff 100%);
    }

    .milling-track-card .module-icon {
      background: #f0eaff;
      color: #6f42c1;
    }

    .stat-icon {
      width: 38px;
      height: 38px;
      margin: 0 auto 8px;
      display: grid;
      place-items: center;
      border-radius: 50%;
      background: #eaf7f0;
      color: #198754;
    }

    .recent-mobile {
      display: none;
    }

    @media (max-width: 768px) {
      .topbar-inner {
        min-height: 60px;
        padding: 9px 10px;
      }

      .brand {
        font-size: 16px;
      }

      .admin-name {
        display: none;
      }

      .top-actions .btn-warning {
        width: 42px;
        height: 42px;
        padding: 0;
        font-size: 0;
        display: grid;
        place-items: center;
        border-radius: 12px;
      }

      .top-actions .btn-warning i {
        font-size: 18px;
      }

      .page-wrap {
        padding: 18px 10px 50px;
      }

      .desktop-activity {
        display: none;
      }

      .recent-mobile {
        display: block;
      }

      .activity-card {
        background: #f8faf9;
        border: 1px solid #edf0ef;
        border-radius: 12px;
        padding: 11px;
        margin-bottom: 8px;
      }
    }
  </style>
</head>
<body>

<?php if(session()->pull('show_login_loader')): ?>
  <?php echo $__env->make('components.loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>

<header class="topbar">
  <div class="topbar-inner">

    <div class="brand">
      <i class="bi bi-shield-check me-1"></i>
      ANI-CARE | LGU
    </div>

    <div class="top-actions">
      <span class="admin-name text-white small">
        <?php echo e(Auth::user()->fullname ?? 'Admin'); ?>

      </span>

      
      <?php echo $__env->make('components.notification-bell', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

      <form method="POST" action="<?php echo e(route('logout')); ?>" class="m-0">
        <?php echo csrf_field(); ?>
        <button class="btn btn-warning btn-sm" title="Logout">
          <i class="bi bi-box-arrow-right"></i>
          <span class="d-none d-md-inline">Logout</span>
        </button>
      </form>
    </div>

  </div>
</header>

<main class="page-wrap">

  <h3 class="fw-bold mb-1 text-success">
    Welcome, Admin 👨‍💼
  </h3>

  <div class="text-muted mb-4">
    Manage ANI-CARE operations and monitor account, marketplace, milling, inventory,
    distribution, payment, and delivery transactions.
  </div>

  
  <div class="row g-3 mb-4">

    <?php
      $adminModules = [
        [
          'route' => 'admin.farmers_millers',
          'icon' => 'bi-people-fill',
          'title' => 'Farmers & Millers',
          'text' => 'Manage user profiles',
          'button' => 'Open',
          'class' => '',
        ],
        [
          'route' => 'admin.inventory',
          'icon' => 'bi-box-seam-fill',
          'title' => 'Inventory',
          'text' => 'Track purchased rice and palay stock',
          'button' => 'View',
          'class' => '',
        ],
        [
          'route' => 'admin.reports',
          'icon' => 'bi-file-earmark-bar-graph-fill',
          'title' => 'Reports',
          'text' => 'View all product purchases and milling transactions',
          'button' => 'View Reports',
          'class' => '',
        ],

        /*
        |--------------------------------------------------------------------------
        | ADMIN -> MILLER REQUEST
        |--------------------------------------------------------------------------
        |
        | Admin can now submit a milling request just like a Farmer.
        |
        */
        [
          'route' => 'admin.milling.create',
          'icon' => 'bi-gear-wide-connected',
          'title' => 'Request Milling',
          'text' => 'Send a palay milling request to an available Miller',
          'button' => 'Request',
          'class' => 'milling-request-card',
        ],

        /*
        |--------------------------------------------------------------------------
        | ADMIN MILLING TRANSACTION TRACKER
        |--------------------------------------------------------------------------
        */
        [
          'route' => 'admin.milling.index',
          'icon' => 'bi-clipboard2-check-fill',
          'title' => 'My Milling Requests',
          'text' => 'Track schedule, payment, milling progress and proof',
          'button' => 'Track',
          'class' => 'milling-track-card',
        ],

        [
          'route' => 'admin.distribution',
          'icon' => 'bi-box2-heart-fill',
          'title' => 'Distribution',
          'text' => 'Manage schedules',
          'button' => 'Manage',
          'class' => '',
        ],
        [
          'route' => 'admin.approvals',
          'icon' => 'bi-person-check-fill',
          'title' => 'User Approvals',
          'text' => 'Approve registered accounts',
          'button' => 'Review',
          'class' => '',
        ],
        [
          'route' => 'admin.market',
          'icon' => 'bi-shop',
          'title' => 'Marketplace',
          'text' => 'Buy and monitor farmer marketplace products',
          'button' => 'Open',
          'class' => '',
        ],
        [
          'route' => 'admin.announcements.index',
          'icon' => 'bi-megaphone-fill',
          'title' => 'Announcements',
          'text' => 'Post system updates',
          'button' => 'Manage',
          'class' => '',
        ],
        [
          'route' => 'admin.reports.vat.settings',
          'icon' => 'bi-percent',
          'title' => 'Central VAT Setting',
          'text' => 'Configure VAT rate and enable or disable VAT',
          'button' => 'Open Settings',
          'class' => '',
        ],
        [
          'route' => 'notifications.index',
          'icon' => 'bi-bell-fill',
          'title' => 'Notifications',
          'text' => 'View all transaction alerts',
          'button' => 'Open',
          'class' => '',
        ],
      ];
    ?>

    <?php $__currentLoopData = $adminModules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="col-12 col-sm-6 col-lg-4">
        <a href="<?php echo e(route($module['route'])); ?>" class="card-link">
          <div class="dash-card p-3 <?php echo e($module['class']); ?>">
            <div class="module-icon">
              <i class="bi <?php echo e($module['icon']); ?>"></i>
            </div>

            <h5 class="fw-bold text-success">
              <?php echo e($module['title']); ?>

            </h5>

            <div class="text-muted small mb-3">
              <?php echo e($module['text']); ?>

            </div>

            <span class="btn btn-success btn-sm">
              <?php echo e($module['button']); ?>

            </span>
          </div>
        </a>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

  </div>

  
  <div class="row g-3 mb-4 text-center">

    <div class="col-6 col-md-3">
      <div class="dash-card p-3">
        <div class="stat-icon"><i class="bi bi-person-fill"></i></div>
        <div class="text-muted small">Farmers</div>
        <h4 class="fw-bold text-success"><?php echo e($activeFarmers ?? 0); ?></h4>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="dash-card p-3">
        <div class="stat-icon"><i class="bi bi-people"></i></div>
        <div class="text-muted small">Beneficiaries</div>
        <h4 class="fw-bold text-success"><?php echo e($beneficiaries ?? 0); ?></h4>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="dash-card p-3">
        <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
        <div class="text-muted small">Rice Stock</div>
        <h4 class="fw-bold text-success"><?php echo e($currentStock ?? 0); ?></h4>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="dash-card p-3">
        <div class="stat-icon"><i class="bi bi-person-exclamation"></i></div>
        <div class="text-muted small">Pending</div>
        <h4 class="fw-bold text-success"><?php echo e($pendingApprovals ?? 0); ?></h4>
      </div>
    </div>

  </div>

  
  <section class="dash-card p-3 mb-4">
    <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
      <div>
        <h5 class="fw-bold mb-0 text-success">Recent Activities</h5>
        <small class="text-muted">
          System transaction overview
        </small>
      </div>

      <a href="<?php echo e(route('notifications.index')); ?>" class="btn btn-outline-success btn-sm">
        <i class="bi bi-bell"></i>
        All Alerts
      </a>
    </div>

    <div class="table-responsive desktop-activity">
      <table class="table table-bordered align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Module</th>
            <th>Description</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Approvals</td>
            <td>New account registrations appear in the notification bell.</td>
            <td><span class="badge bg-warning text-dark">Live</span></td>
            <td><?php echo e(now()->timezone('Asia/Manila')->format('M d, Y')); ?></td>
          </tr>

          <tr>
            <td>Marketplace</td>
            <td>Orders, payments and delivery updates are monitored.</td>
            <td><span class="badge bg-success">Active</span></td>
            <td><?php echo e(now()->timezone('Asia/Manila')->format('M d, Y')); ?></td>
          </tr>

          <tr>
            <td>Milling</td>
            <td>Admin can request milling and track Miller schedules, payments, proof and completion.</td>
            <td><span class="badge bg-success">Active</span></td>
            <td><?php echo e(now()->timezone('Asia/Manila')->format('M d, Y')); ?></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="recent-mobile">
      <div class="activity-card">
        <strong>Account Approvals</strong>
        <div class="small text-muted">
          New account registrations notify the admin bell.
        </div>
      </div>

      <div class="activity-card">
        <strong>Marketplace Transactions</strong>
        <div class="small text-muted">
          Orders, approvals, payment and delivery updates are monitored.
        </div>
      </div>

      <div class="activity-card">
        <strong>Milling Transactions</strong>
        <div class="small text-muted">
          Admin can send milling requests to a selected Miller and track the complete transaction.
        </div>
      </div>
    </div>
  </section>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html><?php /**PATH C:\xampp\htdocs\Allacapan_Anicare\resources\views/dashboards/admin.blade.php ENDPATH**/ ?>