<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Kiosque ITA — Industrial Transformation Africa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,400;0,600;0,700;0,800;1,700;1,800&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/kiosque.css">
    <link rel="icon" type="image/png" href="/img/logo/favicon.png">
</head>
<body>

<div class="flash-overlay" id="flash-overlay"></div>

<div class="kiosque-header">
    <div class="logo-zone">
        <img src="/img/logo/ita_logo.png" alt="ITA" style="height:48px;width:auto;">
        <div>
            <div class="logo-text">Industrial Transformation Africa</div>
            <div class="logo-sub">29 sept – 1er oct 2026 &nbsp;·&nbsp; OFEC – Casablanca &nbsp;·&nbsp; Kiosque Accréditation</div>
        </div>
    </div>
    <div class="heure" id="horloge">--:--:--</div>
    <div class="ms-3">
        <select id="select-imprimante" class="form-select form-select-sm bg-dark text-white border-secondary" style="min-width:180px;" onchange="changerImprimante(this.value)">
            <option value="Rongta RP4xx Series" <?php echo e(session('imprimante','Rongta RP4xx Series') == 'Rongta RP4xx Series' ? 'selected' : ''); ?>>Rongta RP4xx</option>
            <option value="VRSTTI" <?php echo e(session('imprimante') == 'VRSTTI' ? 'selected' : ''); ?>>VRSTTI</option>
        </select>
    </div>
</div>

<div class="kiosque-main">

    <div class="scan-zone">
        <div class="position-relative">
            <video id="video-camera" autoplay muted playsinline></video>
            <canvas id="canvas-qr"></canvas>
            <div class="scan-overlay">
                <div class="scan-line"></div>
                <div class="scan-corner tl"></div>
                <div class="scan-corner tr"></div>
                <div class="scan-corner bl"></div>
                <div class="scan-corner br"></div>
            </div>
        </div>

        <input type="text" id="input-douchette" autocomplete="off">

        <div class="mode-buttons">
            <button class="btn-mode actif" id="btn-camera" onclick="activerCamera()">
                <i class="bi bi-camera-video me-1"></i>Caméra
            </button>
            <button class="btn-mode" id="btn-douchette" onclick="activerDouchette()">
                <i class="bi bi-upc-scan me-1"></i>Douchette USB
            </button>
        </div>
        <div class="scan-hint">
            <i class="bi bi-info-circle me-1"></i>
            Pointez la caméra vers le QR code ou utilisez une douchette USB
        </div>
    </div>

    <div class="resultat-panel">
        <div class="panel-header">
            <i class="bi bi-person-badge me-1"></i>Accréditation visiteur
        </div>

        <div class="resultat-scan" id="resultat-zone">
            <span class="statut-badge statut-attente">
                <i class="bi bi-hourglass-split"></i> En attente d'un scan
            </span>
            <div style="color:rgba(255,255,255,.25);font-size:.9rem;font-family:'Barlow Condensed',sans-serif;">
                Scannez un badge pour afficher les informations du visiteur.
            </div>
        </div>

        <div class="badge-preview-wrap" id="badge-preview-wrap">
            <div class="badge-preview-label">
                <i class="bi bi-credit-card me-1"></i>Aperçu badge
            </div>
            <div class="badge-tiki vide" id="badge-tiki">
                <div class="badge-tiki-header">
                    <img src="/img/logo/ita_logo.png" alt="ITA">
                    <div class="badge-tiki-header-text">
                        Industrial<br>Transformation Africa
                    </div>
                </div>
                <div class="badge-tiki-body">
                    <div class="badge-tiki-infos">
                        <div class="badge-tiki-name" id="tiki-name">En attente d'un scan…</div>
                        <div class="badge-tiki-company" id="tiki-company"></div>
                        <div class="badge-tiki-position" id="tiki-position"></div>
                    </div>
                    <div class="badge-tiki-qr" id="tiki-qr-wrap">
                        <img id="tiki-qr-img" src="" alt="" style="display:none;">
                    </div>
                </div>
                <div class="badge-tiki-footer">
                    <span>ITAF 2026 &nbsp;·&nbsp; OFEC – Casablanca</span>
                </div>
            </div>
        </div>

        <div class="panel-header" style="border-top:1px solid rgba(255,255,255,.05);">
            <i class="bi bi-clock-history me-1"></i>Derniers scans
        </div>

        <div class="scans-recents">
            <div id="liste-scans">
                <div style="color:rgba(255,255,255,.2);font-size:.85rem;font-family:'Barlow Condensed',sans-serif;">
                    Aucun scan enregistré.
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    window.ITA_SCANNER_URL   = "<?php echo e(route('kiosque.scanner')); ?>";
    window.ITA_SCANS_URL     = "<?php echo e(route('kiosque.scans-recents')); ?>";
    window.ITA_IMPRIMANTE_URL = "<?php echo e(route('kiosque.imprimante')); ?>";
    window.ITA_CSRF          = document.querySelector('meta[name="csrf-token"]').content;

    function changerImprimante(val) {
        fetch(window.ITA_IMPRIMANTE_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.ITA_CSRF },
            body: JSON.stringify({ imprimante: val })
        });
    }
</script>
<script src="/js/kiosque.js"></script>
</body>
</html>
<?php /**PATH C:\Users\js\Desktop\ALL THE WEBSITES\ITAF\resources\views/kiosque/scanner.blade.php ENDPATH**/ ?>