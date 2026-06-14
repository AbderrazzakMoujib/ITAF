<?php $__env->startSection('titre', 'Inscription — Industrial Transformation Africa'); ?>

<?php $__env->startSection('contenu'); ?>

<div class="page-hero">
    <div class="container">
        <div class="d-flex flex-column align-items-center text-center gap-3">
            <img src="/img/logo/content-2025-12-23-10-27-07.png"
                 alt="Industrial Transformation Africa 2026"
                 style="height:130px;width:auto;border-radius:4px;">
            <div>
                <div class="event-title">Industrial <span>Transformation</span> Africa</div>
                <div class="event-meta">
                    <i class="bi bi-calendar3"></i>&nbsp; 29 septembre – 1er octobre 2026
                    <span class="sep">|</span>
                    <i class="bi bi-geo-alt"></i>&nbsp; OFEC – Casablanca, Maroc
                </div>
                <div class="event-meta" style="opacity:.6;margin-top:.2rem;font-size:.75rem;">
                    Automation &amp; Smart Manufacturing Exhibition
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="d-flex align-items-center gap-2 mb-3">
                <div style="width:4px;height:28px;background:var(--ita-red);border-radius:2px;"></div>
                <h4 class="mb-0" style="font-family:'Barlow Condensed',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--ita-dark);">
                    Formulaire d'accréditation
                </h4>
            </div>

            <div class="form-card shadow-sm">
                <div class="section-title">
                    <i class="bi bi-person me-2"></i>Informations personnelles
                </div>

                <form action="<?php echo e(route('visiteur.store', $evenement->slug)); ?>" method="POST" novalidate>
                    <?php echo csrf_field(); ?>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="prenom" class="form-label">Prénom <span class="required-star">*</span></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['prenom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="prenom" name="prenom" value="<?php echo e(old('prenom')); ?>"
                                   placeholder="Mohammed" required autofocus>
                            <?php $__errorArgs = ['prenom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom <span class="required-star">*</span></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="nom" name="nom" value="<?php echo e(old('nom')); ?>"
                                   placeholder="El Idrissi" required>
                            <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email professionnel <span class="required-star">*</span></label>
                        <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="email" name="email" value="<?php echo e(old('email')); ?>"
                               placeholder="votre@entreprise.ma" required>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text" style="color:#888;font-size:.8rem;">
                            <i class="bi bi-envelope me-1"></i>Votre QR code sera envoyé à cette adresse.
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="entreprise" class="form-label">Entreprise / Organisation</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['entreprise'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="entreprise" name="entreprise" value="<?php echo e(old('entreprise')); ?>"
                                   placeholder="OCP Group">
                            <?php $__errorArgs = ['entreprise'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label for="fonction" class="form-label">Fonction / Poste</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['fonction'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="fonction" name="fonction" value="<?php echo e(old('fonction')); ?>"
                                   placeholder="Directeur Général">
                            <?php $__errorArgs = ['fonction'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="text" inputmode="numeric" pattern="[0-9]*"
                                   class="form-control <?php $__errorArgs = ['telephone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="telephone" name="telephone" value="<?php echo e(old('telephone')); ?>"
                                   placeholder="0612345678"
                                   oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                            <?php $__errorArgs = ['telephone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label for="pays" class="form-label">Pays</label>
                            <select class="form-select <?php $__errorArgs = ['pays'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="pays" name="pays">
                                <option value="">— Sélectionnez —</option>
                                <?php $__currentLoopData = ['Maroc','France','Belgique','Espagne','Sénégal',"Côte d'Ivoire",'Tunisie','Algérie','Autre']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($p); ?>" <?php echo e(old('pays') == $p ? 'selected' : ''); ?>><?php echo e($p); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['pays'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="mb-4" style="border:1px solid #DDDDDD;border-left:4px solid var(--ita-red);border-radius:4px;padding:16px 18px;background:#fafafa;">
                        <div class="form-check align-items-start d-flex gap-2">
                            <input class="form-check-input mt-1 flex-shrink-0 <?php $__errorArgs = ['cndp_consent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   type="checkbox" name="cndp_consent" id="cndp_consent"
                                   value="1" <?php echo e(old('cndp_consent') ? 'checked' : ''); ?> required>
                            <label class="form-check-label small" for="cndp_consent" style="color:#444;line-height:1.5;">
                                J'accepte que mes données personnelles soient traitées conformément à la
                                <strong style="color:var(--ita-dark);">loi 09-08</strong> relative à la protection
                                des personnes physiques à l'égard du traitement des données à caractère personnel
                                (<strong style="color:var(--ita-dark);">CNDP – Maroc</strong>). Ces données sont
                                collectées uniquement pour la gestion de mon accréditation à l'événement ITA 2026
                                et ne seront pas communiquées à des tiers.
                                <span class="required-star">*</span>
                            </label>
                        </div>
                        <?php $__errorArgs = ['cndp_consent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger small mt-2" style="padding-left:1.5rem;">
                                <i class="bi bi-exclamation-circle me-1"></i><?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="bi bi-qr-code"></i>Valider mon inscription
                    </button>
                </form>

                <div class="privacy-note">
                    <i class="bi bi-shield-check me-1"></i>
                    Vos données sont protégées conformément à la loi 09-08 (CNDP – Maroc) et utilisées uniquement pour la gestion de l'accès à l'événement ITA.
                </div>
            </div>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\js\Desktop\ALL THE WEBSITES\ITAF\resources\views/visitor/formulaire.blade.php ENDPATH**/ ?>