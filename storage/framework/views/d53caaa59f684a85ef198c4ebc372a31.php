<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('titre', 'Admin'); ?> — ITA Badging</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,400;0,600;0,700;0,800;1,700;1,800&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/ita.css">
    <link rel="icon" type="image/png" href="/img/logo/favicon.png">

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
<div class="d-flex" style="min-height:100vh;">

    <nav class="sidebar">
        <div class="brand">
            <img src="/img/logo/ita_logo.png" alt="ITA" style="height:48px;width:auto;display:block;margin-bottom:.6rem;">
            <div class="brand-sub">29 sept – 1er oct 2026 · OFEC Casablanca</div>
            <div class="brand-badge"><i class="bi bi-shield-lock me-1"></i>Admin</div>
        </div>

        <div class="nav-section">Navigation</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="<?php echo e(route('admin.dashboard')); ?>"
                   class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                    <i class="bi bi-speedometer2"></i>Tableau de bord
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(route('admin.visiteurs')); ?>"
                   class="nav-link <?php echo e(request()->routeIs('admin.visiteurs*') ? 'active' : ''); ?>">
                    <i class="bi bi-people"></i>Visiteurs
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(route('admin.scans')); ?>"
                   class="nav-link <?php echo e(request()->routeIs('admin.scans') ? 'active' : ''); ?>">
                    <i class="bi bi-clock-history"></i>Journal des scans
                </a>
            </li>
        </ul>

        <div class="nav-section">Export</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="<?php echo e(route('admin.visiteurs.export')); ?>" class="nav-link">
                    <i class="bi bi-file-earmark-spreadsheet"></i>Exporter CSV
                </a>
            </li>
        </ul>

        <div class="nav-section">Kiosque</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="<?php echo e(route('kiosque.index')); ?>" class="nav-link" target="_blank">
                    <i class="bi bi-display"></i>Ouvrir le kiosque
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            &copy; <?php echo e(date('Y')); ?> ITA Badging System
        </div>
    </nav>

    <div class="main-content">
        <div class="topbar">
            <h1 class="mb-0">
                <i class="bi bi-qr-code-scan me-2" style="color:var(--ita-red);"></i><?php echo $__env->yieldContent('titre', 'Tableau de bord'); ?>
            </h1>
            <span style="font-family:'Barlow Condensed',sans-serif;color:#666;font-size:.875rem;letter-spacing:.05em;">
                <?php echo e(now()->format('d/m/Y H:i')); ?>

            </span>
        </div>

        <div class="page-body">
            <?php if(session('succes')): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 mb-3">
                    <i class="bi bi-check-circle me-2"></i><?php echo e(session('succes')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if(session('erreur')): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 mb-3">
                    <i class="bi bi-exclamation-triangle me-2"></i><?php echo e(session('erreur')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('contenu'); ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\js\Desktop\ALL THE WEBSITES\ITAF\resources\views/layouts/admin.blade.php ENDPATH**/ ?>