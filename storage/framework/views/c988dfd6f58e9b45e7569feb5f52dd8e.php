<?php $__env->startSection('titre', 'Journal des scans'); ?>

<?php $__env->startSection('contenu'); ?>

<div class="card">
    <div class="card-header card-header-ita d-flex align-items-center justify-content-between">
        <span>
            <i class="bi bi-clock-history me-2" style="color:var(--ita-red);"></i>
            <?php echo e($scans->total()); ?> scan(s) enregistré(s)
        </span>
        <?php if($evenement): ?>
            <span style="background:var(--ita-red);color:white;font-family:'Barlow Condensed',sans-serif;font-size:.75rem;font-weight:700;letter-spacing:.06em;padding:.2rem .75rem;border-radius:3px;text-transform:uppercase;">
                <?php echo e($evenement->nom); ?>

            </span>
        <?php endif; ?>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Visiteur</th>
                        <th>Entreprise</th>
                        <th>Heure du scan</th>
                        <th>Kiosque</th>
                        <th class="text-center">N° scan</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $scans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="ps-3 text-muted small"><?php echo e($scan->id); ?></td>
                            <td>
                                <div class="nom-visiteur"><?php echo e($scan->visiteur->nom_complet); ?></div>
                                <small style="color:#888;font-size:.75rem;"><?php echo e($scan->visiteur->email); ?></small>
                            </td>
                            <td><?php echo e($scan->visiteur->entreprise ?? '—'); ?></td>
                            <td>
                                <div class="heure-scan"><?php echo e($scan->scanned_at->format('H:i:s')); ?></div>
                                <small class="text-muted" style="font-size:.75rem;"><?php echo e($scan->scanned_at->format('d/m/Y')); ?></small>
                            </td>
                            <td style="font-size:.8rem;color:#888;font-family:'Barlow Condensed',sans-serif;">
                                <?php echo e($scan->kiosque_id ?? '—'); ?>

                            </td>
                            <td class="text-center" style="font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:1.1rem;color:<?php echo e($scan->visiteur->scan_count > 1 ? 'var(--ita-blue)' : 'var(--ita-red)'); ?>;">
                                <?php echo e($scan->visiteur->scan_count); ?>

                            </td>
                            <td>
                                <?php if($scan->visiteur->scan_count > 1): ?>
                                    <span class="badge-double">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Double scan
                                    </span>
                                <?php else: ?>
                                    <span class="badge-premier">
                                        <i class="bi bi-check2 me-1"></i>1er scan
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2" style="color:var(--ita-red);opacity:.4;"></i>
                                Aucun scan enregistré.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($scans->hasPages()): ?>
        <div class="card-footer bg-white"><?php echo e($scans->links()); ?></div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\js\Desktop\ALL THE WEBSITES\ITAF\resources\views/admin/scans.blade.php ENDPATH**/ ?>