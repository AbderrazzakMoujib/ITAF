<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('titre', 'ITA — Industrial Transformation Africa'); ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,400;0,600;0,700;0,800;1,700;1,800&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/ita.css">
    <link rel="icon" type="image/png" href="/img/logo/favicon.png">

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

<nav class="ita-navbar navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo e(route('accueil')); ?>">
            <img src="/img/logo/ita_logo.png" alt="ITA Logo" style="height:42px;width:auto;">
            <div>
                <div class="navbar-brand-text">INDUSTRIAL TRANSFORMATION AFRICA</div>
                <div class="event-sub">29 sept – 1er oct 2026 &nbsp;·&nbsp; OFEC – Casablanca</div>
            </div>
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <?php echo $__env->yieldContent('nav-extra'); ?>
            </ul>
        </div>
    </div>
</nav>

<main>
    <?php echo $__env->yieldContent('contenu'); ?>
</main>

<footer>
    <div class="container text-center">
        &copy; <?php echo e(date('Y')); ?> &nbsp;·&nbsp; Industrial Transformation Africa &nbsp;·&nbsp; OFEC – Casablanca, Maroc
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\js\Desktop\ALL THE WEBSITES\ITAF\resources\views/layouts/app.blade.php ENDPATH**/ ?>