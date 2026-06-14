<?php $__env->startSection('titre', 'Visiteurs inscrits'); ?>

<?php $__env->startSection('contenu'); ?>

<div class="card mb-3 filter-bar">
    <div class="card-body py-2 px-3">
        <form method="GET" action="<?php echo e(route('admin.visiteurs')); ?>" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="background:var(--ita-red);border-color:var(--ita-red);color:white;">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="q" class="form-control"
                           placeholder="Nom, email, entreprise…"
                           value="<?php echo e($recherche ?? ''); ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select name="statut" class="form-select form-select-sm">
                    <option value="">Tous les statuts</option>
                    <option value="present" <?php echo e(request('statut') === 'present' ? 'selected' : ''); ?>>Présents</option>
                    <option value="absent"  <?php echo e(request('statut') === 'absent'  ? 'selected' : ''); ?>>Non scannés</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">Filtrer</button>
            </div>
            <div class="col-md-2">
                <a href="<?php echo e(route('admin.visiteurs')); ?>" class="btn btn-sm btn-outline-secondary w-100">Réinit.</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header card-header-ita d-flex align-items-center justify-content-between">
        <span>
            <i class="bi bi-people me-2" style="color:var(--ita-red);"></i>
            <?php echo e($visiteurs->total()); ?> visiteur(s)
        </span>
        <a href="<?php echo e(route('admin.visiteurs.export')); ?>"
           class="btn btn-sm"
           style="background:var(--ita-red);color:white;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:.05em;text-transform:uppercase;border:none;font-size:.75rem;">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i>Export CSV
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Nom complet</th>
                        <th>Entreprise</th>
                        <th>Fonction</th>
                        <th>Email</th>
                        <th>Inscription</th>
                        <th>Statut</th>
                        <th>1er scan</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $visiteurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="ps-3 text-muted small"><?php echo e($v->id); ?></td>
                            <td>
                                <div class="nom-visiteur"><?php echo e($v->prenom); ?> <?php echo e($v->nom); ?></div>
                                <?php if($v->pays): ?>
                                    <small class="text-muted" style="font-size:.7rem;"><?php echo e($v->pays); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($v->entreprise ?? '—'); ?></td>
                            <td style="color:#666;"><?php echo e($v->fonction ?? '—'); ?></td>
                            <td>
                                <a href="mailto:<?php echo e($v->email); ?>" class="text-decoration-none" style="font-size:.8rem;color:#444;">
                                    <?php echo e($v->email); ?>

                                </a>
                            </td>
                            <td style="font-size:.8rem;color:#888;"><?php echo e($v->registered_at->format('d/m H:i')); ?></td>
                            <td>
                                <?php if($v->estPresent()): ?>
                                    <span class="badge-present">Présent</span>
                                <?php else: ?>
                                    <span class="badge-absent">Absent</span>
                                <?php endif; ?>
                            </td>
                            <td class="heure-scan" style="font-size:.85rem;">
                                <?php echo e($v->first_scanned_at?->format('H:i:s') ?? '—'); ?>

                            </td>
                            <td class="text-end pe-3">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="<?php echo e(route('visiteur.qr', $v->token)); ?>" target="_blank"
                                       class="btn btn-sm"
                                       style="background:var(--ita-light);border:1px solid var(--ita-border);color:var(--ita-dark);"
                                       title="Voir QR">
                                        <i class="bi bi-qr-code"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.visiteur.email', $v)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm"
                                                style="background:var(--ita-light);border:1px solid var(--ita-border);color:var(--ita-blue);"
                                                title="Renvoyer email"
                                                onclick="return confirm('Renvoyer le QR code à <?php echo e($v->email); ?> ?')">
                                            <i class="bi bi-envelope"></i>
                                        </button>
                                    </form>
                                    <form action="<?php echo e(route('admin.visiteur.supprimer', $v)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm"
                                                style="background:var(--ita-light);border:1px solid var(--ita-border);color:var(--ita-red);"
                                                title="Supprimer"
                                                onclick="return confirm('Supprimer <?php echo e($v->nom_complet); ?> ?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2" style="color:var(--ita-red);opacity:.4;"></i>
                                Aucun visiteur trouvé.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($visiteurs->hasPages()): ?>
        <div class="card-footer bg-white"><?php echo e($visiteurs->links()); ?></div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\js\Desktop\ALL THE WEBSITES\ITAF\resources\views/admin/visiteurs.blade.php ENDPATH**/ ?>