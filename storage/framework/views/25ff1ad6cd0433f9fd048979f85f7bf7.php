<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Approvals | ANI-CARE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Mobile responsive helpers -->
  <style>
    html { box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
    *, *::before, *::after { box-sizing: inherit; }
    body { min-height: 100vh; margin: 0; }
    img, video, iframe, svg, canvas { max-width: 100%; height: auto; }
    .container, .container-fluid { width: 100% !important; max-width: 100% !important; padding-left: 1rem !important; padding-right: 1rem !important; }
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .table-responsive table { min-width: 100%; }
    .leaflet-container, #registrationMap, #residentMap, #orderMap, #trackMap { width: 100% !important; max-width: 100%; }
    .card, .card-body { word-wrap: break-word; }
    .btn, .form-control, .form-select, .input-group, .form-check-input { min-width: 0; }
    @media (max-width: 768px) {
      .navbar, .topbar { flex-wrap: wrap !important; }
      .navbar-brand, .navbar-nav, .btn { width: 100% !important; text-align: center !important; }
      .table-responsive { margin-left: -1rem !important; margin-right: -1rem !important; padding-left: 1rem !important; padding-right: 1rem !important; }
    }
  </style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold text-success m-0">User Approvals</h3>
      <div class="text-muted small">Approve Resident, Farmer, and Miller accounts</div>
    </div>
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-outline-success btn-sm">Back</a>
  </div>

  <?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
  <?php endif; ?>

  <?php if($errors->any()): ?>
    <div class="alert alert-danger"><?php echo e($errors->first()); ?></div>
  <?php endif; ?>

  
  <div class="row g-2 mb-3">
    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="text-muted small">Pending</div>
          <div class="fs-4 fw-bold"><?php echo e($pendingCount); ?></div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="text-muted small">Approved</div>
          <div class="fs-4 fw-bold"><?php echo e($approvedCount); ?></div>
        </div>
      </div>
    </div>
  </div>

  
  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <form class="row g-2" method="GET" action="<?php echo e(route('admin.approvals')); ?>">
        <div class="col-md-5">
          <input
            type="text"
            name="q"
            class="form-control"
            placeholder="Search fullname / username / email / barangay"
            value="<?php echo e($search ?? ''); ?>"
          >
        </div>

        <div class="col-md-3">
          <select name="role" class="form-select">
            <option value="all" <?php echo e(($role ?? 'all') === 'all' ? 'selected' : ''); ?>>All Roles</option>
            <option value="resident" <?php echo e(($role ?? '') === 'resident' ? 'selected' : ''); ?>>Resident</option>
            <option value="farmer" <?php echo e(($role ?? '') === 'farmer' ? 'selected' : ''); ?>>Farmer</option>
            <option value="miller" <?php echo e(($role ?? '') === 'miller' ? 'selected' : ''); ?>>Miller</option>
          </select>
        </div>

        <div class="col-md-3">
          <select name="status" class="form-select">
            <option value="pending" <?php echo e(($status ?? 'pending') === 'pending' ? 'selected' : ''); ?>>Pending</option>
            <option value="approved" <?php echo e(($status ?? '') === 'approved' ? 'selected' : ''); ?>>Approved</option>
            <option value="all" <?php echo e(($status ?? '') === 'all' ? 'selected' : ''); ?>>All</option>
          </select>
        </div>

        <div class="col-md-1 d-grid">
          <button class="btn btn-success">Go</button>
        </div>
      </form>
    </div>
  </div>

  
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Fullname</th>
              <th>Username</th>
              <th>Email</th>
              <th>Barangay</th>
              <th>Role</th>
              <th>Status</th>
              <th>Approved At</th>
              <th>Registered</th>
              <th class="text-end">Action</th>
            </tr>
          </thead>

          <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td><?php echo e($u->id); ?></td>
              <td class="fw-semibold"><?php echo e($u->fullname); ?></td>
              <td><?php echo e($u->username); ?></td>
              <td><?php echo e($u->email ?? '-'); ?></td>
              <td><?php echo e($u->barangay ?? '-'); ?></td>
              <td>
                <?php if($u->role === 'resident'): ?>
                  <span class="badge bg-primary text-uppercase">resident</span>
                <?php elseif($u->role === 'farmer'): ?>
                  <span class="badge bg-success text-uppercase">farmer</span>
                <?php elseif($u->role === 'miller'): ?>
                  <span class="badge bg-secondary text-uppercase">miller</span>
                <?php else: ?>
                  <span class="badge bg-dark text-uppercase"><?php echo e($u->role); ?></span>
                <?php endif; ?>
              </td>

              <td>
                <?php if($u->is_approved): ?>
                  <span class="badge bg-success">APPROVED</span>
                <?php else: ?>
                  <span class="badge bg-warning text-dark">PENDING</span>
                <?php endif; ?>
              </td>

              <td><?php echo e($u->approved_at ? $u->approved_at->format('Y-m-d H:i') : '-'); ?></td>
              <td><?php echo e($u->created_at ? $u->created_at->format('Y-m-d') : '-'); ?></td>

              <td class="text-end">
                <?php if(!$u->is_approved): ?>
                  <form class="d-inline" method="POST" action="<?php echo e(route('admin.approvals.approve', $u->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <button
                      class="btn btn-success btn-sm"
                      onclick="return confirm('Approve this user?')">
                      Approve
                    </button>
                  </form>
                <?php else: ?>
                  <form class="d-inline" method="POST" action="<?php echo e(route('admin.approvals.revoke', $u->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <button
                      class="btn btn-outline-danger btn-sm"
                      onclick="return confirm('Revoke approval?')">
                      Revoke
                    </button>
                  </form>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="10" class="text-center text-muted py-4">No users found.</td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>

      
      <div class="mt-3">
        <?php echo e($users->links()); ?>

      </div>
    </div>
  </div>

</div>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\Allacapan_Anicare\resources\views/admin/approvals.blade.php ENDPATH**/ ?>