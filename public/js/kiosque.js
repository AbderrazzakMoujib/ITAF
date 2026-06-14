/* ============================================================
   ITA — Kiosque Scanner
   Dépendances : jsQR (chargé avant ce fichier)
   Variables globales requises : window.ITA_SCANNER_URL, window.ITA_SCANS_URL, window.ITA_CSRF
   ============================================================ */

const URL_SCANNER = window.ITA_SCANNER_URL;
const URL_SCANS   = window.ITA_SCANS_URL;
const CSRF        = window.ITA_CSRF;

let modeActif    = 'camera';
let enTraitement = false;
let dernierToken = '';
let streamCamera = null;
let animCamera   = null;

/* ─── Horloge ─────────────────────────────────── */
function majHorloge() {
    document.getElementById('horloge').textContent = new Date().toLocaleTimeString('fr-FR');
}
setInterval(majHorloge, 1000);
majHorloge();

/* ─── Mode Caméra ─────────────────────────────── */
async function activerCamera() {
    modeActif = 'camera';
    document.getElementById('btn-camera').classList.add('actif');
    document.getElementById('btn-douchette').classList.remove('actif');
    document.getElementById('video-camera').style.display = 'block';
    document.getElementById('input-douchette').blur();

    try {
        streamCamera = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'environment', width: 640, height: 480 }
        });
        const video = document.getElementById('video-camera');
        video.srcObject = streamCamera;
        video.play();
        video.addEventListener('loadedmetadata', boucleScanner, { once: true });
    } catch (err) {
        afficherErreur('Caméra non disponible. Passez en mode Douchette.');
    }
}

function boucleScanner() {
    const video  = document.getElementById('video-camera');
    const canvas = document.getElementById('canvas-qr');
    const ctx    = canvas.getContext('2d');

    function lire() {
        if (modeActif !== 'camera') return;
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.width  = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: 'dontInvert' });
            if (code && code.data) traiterScan(code.data);
        }
        animCamera = requestAnimationFrame(lire);
    }
    lire();
}

/* ─── Mode Douchette ──────────────────────────── */
function activerDouchette() {
    modeActif = 'douchette';
    document.getElementById('btn-douchette').classList.add('actif');
    document.getElementById('btn-camera').classList.remove('actif');
    if (streamCamera) { streamCamera.getTracks().forEach(t => t.stop()); streamCamera = null; }
    if (animCamera)   { cancelAnimationFrame(animCamera); animCamera = null; }
    document.getElementById('input-douchette').focus();
}

document.getElementById('input-douchette').addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
        const v = this.value.trim();
        this.value = '';
        if (v) traiterScan(v);
    }
});

document.addEventListener('click', () => {
    if (modeActif === 'douchette') document.getElementById('input-douchette').focus();
});

/* ─── Traitement scan ─────────────────────────── */
async function traiterScan(valeurQr) {
    if (enTraitement) return;

    let token = valeurQr;
    try {
        const url = new URL(valeurQr);
        const seg = url.pathname.split('/');
        const idx = seg.indexOf('confirmation');
        if (idx !== -1 && seg[idx + 1]) token = seg[idx + 1];
    } catch (_) {}

    if (token === dernierToken) return;
    dernierToken = token;
    setTimeout(() => { dernierToken = ''; }, 3000);

    enTraitement = true;
    afficherChargement();

    try {
        const rep  = await fetch(URL_SCANNER, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ token }),
        });
        const data = await rep.json();
        if (!rep.ok || !data.succes) afficherInconnu(data.message || 'QR code non reconnu.');
        else { afficherResultat(data); rafraichirScansRecents(); }
    } catch (_) {
        afficherErreur('Erreur de connexion au serveur.');
    } finally {
        enTraitement = false;
    }
}

/* ─── Affichage résultats ─────────────────────── */
function afficherChargement() {
    document.getElementById('resultat-zone').innerHTML = `
        <div class="d-flex align-items-center gap-2" style="color:rgba(255,255,255,.4);font-family:'Barlow Condensed',sans-serif;letter-spacing:.06em;text-transform:uppercase;">
            <div class="spinner-border spinner-border-sm" role="status"></div>
            Vérification en cours…
        </div>`;
}

function majBadgeTiki(v, token) {
    const tiki = document.getElementById('badge-tiki');
    tiki.classList.remove('vide');

    document.getElementById('tiki-name').textContent     = v.nom_complet || (v.prenom + ' ' + v.nom);
    document.getElementById('tiki-company').textContent  = v.entreprise || '';
    document.getElementById('tiki-position').textContent = v.fonction || '';

    const img = document.getElementById('tiki-qr-img');
    if (token) {
        img.src = window.location.origin + '/confirmation/' + token + '/qr';
        img.style.display = 'block';
    } else {
        img.style.display = 'none';
    }
}

function imprimerBadge(v, token) {
    const nomComplet = v.nom_complet || (v.prenom + ' ' + v.nom);
    const entreprise = v.entreprise || '';
    const fonction   = v.fonction   || '';
    const qrUrl      = window.location.origin + '/confirmation/' + token + '/qr';

    const win = window.open('', '_blank', 'width=400,height=300');
    win.document.write(`<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Badge</title>
<style>
  @page {
    size: 70mm 40mm;
    margin: 0;
  }
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    width: 70mm;
    height: 40mm;
    font-family: Arial, Helvetica, sans-serif;
    background: #fff;
    overflow: hidden;
  }
  .badge {
    width: 70mm;
    height: 40mm;
    display: flex;
    flex-direction: column;
  }
  .badge-body {
    flex: 1;
    display: flex;
    align-items: center;
    padding: 4mm 3mm 4mm 4mm;
    gap: 3mm;
  }
  .badge-infos {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-width: 0;
  }
  .badge-name {
    font-size: 14pt;
    font-weight: 900;
    color: #0A1628;
    text-transform: uppercase;
    line-height: 1.15;
    word-break: break-word;
  }
  .badge-company {
    font-size: 8pt;
    font-weight: 700;
    color: #4A90C4;
    text-transform: uppercase;
    margin-top: 2mm;
    letter-spacing: 0.03em;
  }
  .badge-position {
    font-size: 7pt;
    color: #666;
    margin-top: 1mm;
  }
  .badge-qr {
    width: 22mm;
    height: 22mm;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .badge-qr img {
    width: 22mm;
    height: 22mm;
    display: block;
  }
  .badge-footer {
    background: #0A1628;
    height: 5mm;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .badge-footer span {
    font-size: 5pt;
    color: rgba(255,255,255,0.5);
    text-transform: uppercase;
    letter-spacing: 0.1em;
  }
</style>
</head>
<body>
<div class="badge">
  <div class="badge-body">
    <div class="badge-infos">
      <div class="badge-name">${nomComplet}</div>
      ${entreprise ? `<div class="badge-company">${entreprise}</div>` : ''}
      ${fonction   ? `<div class="badge-position">${fonction}</div>`  : ''}
    </div>
    <div class="badge-qr">
      <img src="${qrUrl}" onload="window.print();window.close();" onerror="window.print();window.close();">
    </div>
  </div>
  <div class="badge-footer">
    <span>ITAF 2026 &nbsp;·&nbsp; OFEC – Casablanca</span>
  </div>
</div>
</body>
</html>`);
    win.document.close();
}

function afficherResultat(data) {
    const v         = data.visiteur;
    const estDouble = data.statut === 'deja_scanne';

    flashEcran(estDouble ? 'bleu' : 'rouge');
    majBadgeTiki(v, dernierToken);
    if (!estDouble) imprimerBadge(v, dernierToken);

    let html = `
        <span class="statut-badge ${estDouble ? 'statut-double' : 'statut-nouveau'}">
            <i class="bi bi-${estDouble ? 'exclamation-triangle' : 'check-circle-fill'}"></i>
            ${estDouble ? '⚠ Déjà scanné' : '✓ Accès autorisé'}
        </span>
        <div class="visiteur-nom">${v.prenom}<br>${v.nom}</div>`;

    if (v.entreprise) html += `<div class="visiteur-entreprise">${v.entreprise}</div>`;
    if (v.fonction)   html += `<div class="visiteur-fonction">${v.fonction}</div>`;

    html += `<div class="scan-info">
        <i class="bi bi-clock me-1"></i>${data.scan.scanned_at}
        &nbsp;·&nbsp; Scan n°${v.scan_count}
        ${data.impression ? '&nbsp;·&nbsp; <i class="bi bi-printer"></i> Badge imprimé' : ''}
    </div>`;

    if (estDouble) {
        html += `<div class="alerte-double">
            <i class="bi bi-exclamation-triangle me-1"></i>
            Visiteur déjà enregistré à <strong>${v.first_scanned_at}</strong>. Aucun badge supplémentaire.
        </div>`;
    }

    html += `<button class="btn-print-badge" onclick="imprimerBadge(window._dernierVisiteur, window._dernierTokenPrint)">
        <i class="bi bi-printer-fill"></i> Imprimer le badge
    </button>`;

    const zone = document.getElementById('resultat-zone');
    zone.innerHTML = html;

    window._dernierVisiteur   = v;
    window._dernierTokenPrint = dernierToken;
    if (!estDouble) {
        zone.classList.add('pulse-rouge');
        setTimeout(() => zone.classList.remove('pulse-rouge'), 700);
    }
}

function afficherInconnu(msg) {
    flashEcran('rouge');
    document.getElementById('resultat-zone').innerHTML = `
        <span class="statut-badge statut-inconnu">
            <i class="bi bi-x-circle"></i> QR Code inconnu
        </span>
        <div style="color:#ff6b6b;margin-top:.5rem;font-family:'Barlow Condensed',sans-serif;">${msg}</div>`;
}

function afficherErreur(msg) {
    document.getElementById('resultat-zone').innerHTML = `
        <span class="statut-badge statut-inconnu">
            <i class="bi bi-wifi-off"></i> Erreur
        </span>
        <div style="color:#ff6b6b;margin-top:.5rem;font-family:'Barlow Condensed',sans-serif;">${msg}</div>`;
}

function flashEcran(couleur) {
    const o = document.getElementById('flash-overlay');
    o.className = 'flash-overlay' + (couleur === 'bleu' ? ' bleu' : '');
    o.style.display = 'block';
    setTimeout(() => { o.style.display = 'none'; }, 300);
}

/* ─── Scans récents ───────────────────────────── */
async function rafraichirScansRecents() {
    try {
        const rep  = await fetch(URL_SCANS, { headers: { Accept: 'application/json' } });
        const data = await rep.json();
        const liste = document.getElementById('liste-scans');
        if (!data.scans || data.scans.length === 0) {
            liste.innerHTML = '<div style="color:rgba(255,255,255,.2);font-size:.85rem;font-family:\'Barlow Condensed\',sans-serif;">Aucun scan.</div>';
            return;
        }
        liste.innerHTML = data.scans.map(s => `
            <div class="scan-item">
                <div>
                    <div class="scan-nom">${s.nom_complet}</div>
                    <div class="scan-societe">${s.entreprise || ''}</div>
                </div>
                <div class="scan-heure">${s.scanned_at}</div>
            </div>`).join('');
    } catch (_) {}
}

setInterval(rafraichirScansRecents, 15000);
activerCamera();
rafraichirScansRecents();
