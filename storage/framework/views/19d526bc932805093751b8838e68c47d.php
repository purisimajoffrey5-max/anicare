<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Maintenance Mode | ANI-CARE</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #f4f7f9;
            font-family: "Segoe UI", Arial, sans-serif;
            color: #263238;
        }

        /* =========================
           TOP BAR
        ========================= */

        .topbar {
            height: 54px;
            background: #198754;
            color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .12);
        }

        .topbar-inner {
            width: min(1100px, 100%);
            height: 54px;
            margin: auto;
            padding: 0 14px;

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: .1px;
        }

        .back-btn {
            background: #ffc107;
            border: none;
            color: #212529;
            font-size: 12px;
            font-weight: 600;
            border-radius: 4px;
            padding: 7px 12px;
            text-decoration: none;
        }

        .back-btn:hover {
            background: #ffca2c;
            color: #212529;
        }

        /* =========================
           PAGE
        ========================= */

        .page-wrap {
            width: min(820px, 100%);
            margin: 0 auto;
            padding: 28px 14px 60px;
        }

        .page-heading {
            margin-bottom: 20px;
        }

        .page-heading h2 {
            margin: 0;
            color: #198754;
            font-size: 25px;
            font-weight: 700;
        }

        .page-heading p {
            margin: 4px 0 0;
            color: #6c757d;
            font-size: 12px;
        }

        /* =========================
           ALERTS
        ========================= */

        .alert {
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 18px;
        }

        /* =========================
           MAIN CARD
        ========================= */

        .settings-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 14px;
            box-shadow: 0 5px 18px rgba(15, 23, 42, .06);
            overflow: hidden;
        }

        .card-header-custom {
            padding: 20px 22px 16px;
            border-bottom: 1px solid #edf0f2;
        }

        .header-row {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .module-icon {
            width: 46px;
            height: 46px;
            flex: 0 0 46px;

            display: grid;
            place-items: center;

            border-radius: 12px;
            background: #fff5d6;
            color: #b58105;

            font-size: 21px;
        }

        .card-title {
            margin: 0;
            color: #198754;
            font-size: 17px;
            font-weight: 700;
        }

        .card-description {
            margin: 3px 0 0;
            color: #6c757d;
            font-size: 12px;
        }

        .card-body-custom {
            padding: 22px;
        }

        /* =========================
           CURRENT STATUS
        ========================= */

        .status-box {
            background: #f8faf9;
            border: 1px solid #e5ebe8;
            border-radius: 10px;
            padding: 14px 16px;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;

            margin-bottom: 24px;
        }

        .status-label {
            font-size: 12px;
            font-weight: 700;
            color: #343a40;
        }

        .status-description {
            margin-top: 3px;
            color: #6c757d;
            font-size: 11px;
        }

        .status-badge {
            white-space: nowrap;
            font-size: 10px;
            font-weight: 700;
            border-radius: 20px;
            padding: 6px 10px;
        }

        /* =========================
           FORM
        ========================= */

        .section-label {
            display: block;
            margin-bottom: 8px;
            color: #343a40;
            font-size: 12px;
            font-weight: 700;
        }

        .maintenance-message {
            width: 100%;
            min-height: 120px;

            border: 1px solid #ced4da;
            border-radius: 7px;

            padding: 11px 12px;

            font-size: 13px;
            line-height: 1.5;

            resize: vertical;
            outline: none;
        }

        .maintenance-message:focus {
            border-color: #198754;
            box-shadow: 0 0 0 3px rgba(25, 135, 84, .10);
        }

        .form-help {
            margin-top: 6px;
            color: #6c757d;
            font-size: 11px;
        }

        /* =========================
           MAINTENANCE OPTIONS
        ========================= */

        .maintenance-section {
            margin-top: 23px;
        }

        .status-options {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .status-option {
            position: relative;
        }

        .status-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .status-option label {
            min-width: 145px;
            padding: 9px 13px;

            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;

            border: 1px solid #ced4da;
            border-radius: 6px;

            background: #fff;
            color: #495057;

            font-size: 12px;
            font-weight: 600;

            cursor: pointer;
            transition: .15s ease;
        }

        .status-option label:hover {
            border-color: #198754;
            color: #198754;
        }

        /* ACTIVE MAINTENANCE */

        .maintenance-option input:checked + label {
            background: #dc3545;
            border-color: #dc3545;
            color: #fff;
        }

        .maintenance-option input:checked + label:hover {
            color: #fff;
        }

        /* ACTIVE AVAILABLE */

        .available-option input:checked + label {
            background: #198754;
            border-color: #198754;
            color: #fff;
        }

        .available-option input:checked + label:hover {
            color: #fff;
        }

        /* =========================
           INFORMATION BOX
        ========================= */

        .info-box {
            margin-top: 22px;
            padding: 12px 14px;

            background: #fff8e1;
            border-left: 3px solid #ffc107;
            border-radius: 6px;

            color: #6c757d;
            font-size: 11px;
            line-height: 1.5;
        }

        .info-box strong {
            color: #856404;
        }

        /* =========================
           FORM ACTIONS
        ========================= */

        .form-actions {
            margin-top: 25px;
            padding-top: 18px;

            border-top: 1px solid #edf0f2;

            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .btn-cancel {
            background: #fff;
            border: 1px solid #ced4da;
            color: #495057;

            border-radius: 6px;
            padding: 8px 14px;

            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-cancel:hover {
            background: #f8f9fa;
            color: #212529;
        }

        .btn-save {
            background: #198754;
            border: 1px solid #198754;
            color: #fff;

            border-radius: 6px;
            padding: 8px 15px;

            font-size: 12px;
            font-weight: 600;
        }

        .btn-save:hover {
            background: #157347;
            border-color: #157347;
            color: #fff;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 576px) {

            .page-wrap {
                padding-top: 20px;
            }

            .page-heading h2 {
                font-size: 22px;
            }

            .card-header-custom,
            .card-body-custom {
                padding: 17px;
            }

            .status-box {
                align-items: flex-start;
                flex-direction: column;
            }

            .status-options {
                flex-direction: column;
            }

            .status-option,
            .status-option label {
                width: 100%;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .btn-cancel,
            .btn-save {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>


<body>

<header class="topbar">

    <div class="topbar-inner">

        <div class="brand">
            <i class="bi bi-shield-check me-1"></i>
            ANI-CARE | LGU
        </div>

        <a
            href="<?php echo e(route('admin.settings')); ?>"
            class="back-btn"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Settings
        </a>

    </div>

</header>


<main class="page-wrap">

    
    <div class="page-heading">

        <h2>
            Maintenance Mode
        </h2>

        <p>
            Temporarily restrict non-admin access while system updates or maintenance are being performed.
        </p>

    </div>


    
    <?php if($errors->any()): ?>

        <div class="alert alert-danger">

            <strong>
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                Please check the following:
            </strong>

            <ul class="mb-0 mt-2">

                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <li><?php echo e($error); ?></li>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </ul>

        </div>

    <?php endif; ?>


    
    <div class="settings-card">

        
        <div class="card-header-custom">

            <div class="header-row">

                <div class="module-icon">
                    <i class="bi bi-tools"></i>
                </div>

                <div>

                    <h5 class="card-title">
                        Maintenance Configuration
                    </h5>

                    <div class="card-description">
                        Control whether Resident, Farmer, and Miller accounts can access ANI-CARE.
                    </div>

                </div>

            </div>

        </div>


        
        <div class="card-body-custom">

            
            <div class="status-box">

                <div>

                    <div class="status-label">
                        Current System Status
                    </div>

                    <div class="status-description">

                        <?php if($maintenanceEnabled): ?>

                            Non-admin accounts are currently blocked by the maintenance page.

                        <?php else: ?>

                            Resident, Farmer, and Miller accounts can access ANI-CARE normally.

                        <?php endif; ?>

                    </div>

                </div>


                <?php if($maintenanceEnabled): ?>

                    <span class="badge bg-danger status-badge">
                        <i class="bi bi-tools me-1"></i>
                        UNDER MAINTENANCE
                    </span>

                <?php else: ?>

                    <span class="badge bg-success status-badge">
                        <i class="bi bi-check-circle me-1"></i>
                        SYSTEM AVAILABLE
                    </span>

                <?php endif; ?>

            </div>


            
            <form
                method="POST"
                action="<?php echo e(route('admin.settings.maintenance.update')); ?>"
            >

                <?php echo csrf_field(); ?>


                
                <div class="maintenance-section">

                    <label class="section-label">
                        Maintenance Status
                    </label>

                    <div class="status-options">

                        
                        <div class="status-option maintenance-option">

                            <input
                                type="radio"
                                id="maintenance_enabled"
                                name="maintenance_enabled"
                                value="1"
                                <?php echo e($maintenanceEnabled ? 'checked' : ''); ?>

                            >

                            <label for="maintenance_enabled">

                                <i class="bi bi-tools"></i>

                                Under Maintenance

                            </label>

                        </div>


                        
                        <div class="status-option available-option">

                            <input
                                type="radio"
                                id="maintenance_disabled"
                                name="maintenance_enabled"
                                value="0"
                                <?php echo e(!$maintenanceEnabled ? 'checked' : ''); ?>

                            >

                            <label for="maintenance_disabled">

                                <i class="bi bi-check-circle"></i>

                                System Available

                            </label>

                        </div>

                    </div>

                </div>


                
                <div class="mt-4">

                    <label
                        for="maintenance_message"
                        class="section-label"
                    >
                        Maintenance Message
                    </label>

                    <textarea
                        id="maintenance_message"
                        name="maintenance_message"
                        rows="5"
                        maxlength="500"
                        class="maintenance-message"
                        required
                    ><?php echo e(old('maintenance_message', $maintenanceMessage)); ?></textarea>

                    <div class="form-help">

                        This message will be shown to Resident, Farmer,
                        and Miller users while Maintenance Mode is enabled.

                    </div>

                </div>


                
                <div class="info-box">

                    <i class="bi bi-info-circle me-1"></i>

                    <strong>Administrator Access:</strong>

                    Admin accounts remain accessible during Maintenance Mode
                    so the administrator can return here and select
                    <strong>System Available</strong> when maintenance is finished.

                </div>


                
                <div class="form-actions">

                    <a
                        href="<?php echo e(route('admin.settings')); ?>"
                        class="btn-cancel"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn-save"
                    >
                        <i class="bi bi-save me-1"></i>
                        Save Maintenance Setting
                    </button>

                </div>

            </form>

        </div>

    </div>

</main>

</body>
</html><?php /**PATH C:\xampp\htdocs\Allacapan_Anicare\resources\views/admin/settings/maintenance.blade.php ENDPATH**/ ?>