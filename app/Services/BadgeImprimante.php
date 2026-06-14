<?php

namespace App\Services;

use App\Models\Visitor;
use Dompdf\Dompdf;
use Dompdf\Options;

class BadgeImprimante
{
    private string $nomImprimante;
    private string $sumatraPath;

    public function __construct(?string $imprimante = null)
    {
        $this->nomImprimante = $imprimante ?? $this->detecterImprimante();
        $this->sumatraPath   = base_path('SumatraPDF.exe');
    }

    private function detecterImprimante(): string
    {
        $candidates = ['Rongta RP4xx Series', 'VRSTTI'];
        $output = shell_exec('powershell -Command "Get-Printer | Where-Object {$_.PrinterStatus -eq \'Normal\'} | Select-Object -ExpandProperty Name" 2>&1');
        if ($output) {
            foreach ($candidates as $nom) {
                if (str_contains($output, $nom)) {
                    return $nom;
                }
            }
        }
        return env('IMPRIMANTE_CIBLE', 'Rongta RP4xx Series');
    }

    public function imprimer(Visitor $visiteur): void
    {
        $pdfPath = $this->genererPdf($visiteur);
        $this->imprimerViaSumatra($pdfPath);
        @unlink($pdfPath);
    }

    private function genererHtml(Visitor $visiteur): string
    {
        $nomComplet = mb_strtoupper($visiteur->prenom . ' ' . $visiteur->nom);
        $entreprise = $visiteur->entreprise ? mb_strtoupper($visiteur->entreprise) : '';
        $fonction   = $visiteur->fonction ?? '';
        $qrBase64   = $this->genererQrBase64(route('visiteur.confirmation', $visiteur->token), 200);

        $qrImg = $qrBase64
            ? '<img src="data:image/png;base64,' . $qrBase64 . '" style="width:100%;height:auto;">'
            : '';

        return '<!DOCTYPE html>
<html><head><meta charset="UTF-8">
<style>
@page { size: 7cm 4cm; margin: 0; }
* { margin:0; padding:0; box-sizing:border-box; }
html, body { width:7cm; height:4cm; overflow:hidden; font-family:Arial,sans-serif; background:#fff; position:relative; }
.info {
    position:absolute;
    left:0; top:0;
    width:4.6cm; height:4cm;
    display:flex; flex-direction:column; justify-content:center;
    padding:6px 4px 6px 6px;
}
.qr {
    position:absolute;
    right:0; top:0;
    width:2.4cm; height:4cm;
    display:flex; align-items:center; justify-content:center;
    padding:4px;
}
.nom { font-size:13px; font-weight:bold; color:#0A1628; text-transform:uppercase; line-height:1.2; margin-bottom:5px; }
.ent { font-size:8px; font-weight:bold; color:#0A1628; text-transform:uppercase; margin-bottom:3px; }
.fnc { font-size:7px; color:#555; }
img  { width:100%; height:auto; display:block; }
</style></head><body>
<div class="info">
  <div class="nom">' . htmlspecialchars($nomComplet) . '</div>'
  . ($entreprise ? '<div class="ent">' . htmlspecialchars($entreprise) . '</div>' : '')
  . ($fonction   ? '<div class="fnc">' . htmlspecialchars($fonction)   . '</div>' : '') .
'</div>
<div class="qr">' . $qrImg . '</div>
</body></html>';
    }

    private function genererPdf(Visitor $visiteur): string
    {
        $nomComplet = mb_strtoupper($visiteur->prenom . ' ' . $visiteur->nom);
        $entreprise = $visiteur->entreprise ? mb_strtoupper($visiteur->entreprise) : '';
        $fonction   = $visiteur->fonction ?? '';
        $pays       = $visiteur->pays ? mb_strtoupper($visiteur->pays) : '';
        $qrBase64   = $this->genererQrBase64(route('visiteur.confirmation', $visiteur->token), 100);

        $qrImg = $qrBase64
            ? '<img src="data:image/png;base64,' . $qrBase64 . '" style="width:80pt;height:80pt;display:block;">'
            : '';

        $html = '<!DOCTYPE html>
<html><head><meta charset="UTF-8">
<style>
@page { margin:0; }
* { margin:0; padding:0; box-sizing:border-box; }
html, body { width:198.43pt; height:113.39pt; overflow:hidden; font-family:Arial,Helvetica,sans-serif; background:#fff; }
.badge { position:relative; width:198.43pt; height:113.39pt; overflow:hidden; }
.left  { position:absolute; left:0; top:0; width:112pt; height:113.39pt; overflow:hidden; padding:9pt 4pt 0 9pt; }
.right { position:absolute; right:0; top:10pt; width:80pt; overflow:hidden; }
img { width:80pt; height:80pt; display:block; }
.pays { font-size:7pt; font-weight:bold; color:#0A1628; text-transform:uppercase; text-align:center; margin-top:3pt; }
.nom { font-size:13pt; font-weight:bold; color:#0A1628; text-transform:uppercase; line-height:1.2; }
.ent { font-size:9pt; font-weight:bold; color:#0A1628; text-transform:uppercase; margin-top:4pt; }
.fnc { font-size:10pt; color:#0A1628; margin-top:2pt; text-transform:capitalize; }
</style>
</head>
<body>
<div class="badge">
  <div class="left">
    <div class="nom">' . htmlspecialchars($nomComplet) . '</div>'
    . ($entreprise ? '<div class="ent">' . htmlspecialchars($entreprise) . '</div>' : '')
    . ($fonction   ? '<div class="fnc">' . htmlspecialchars($fonction)   . '</div>' : '') .
  '</div>
  <div class="right">' . $qrImg . ($pays ? '<div class="pays">' . htmlspecialchars($pays) . '</div>' : '') . '</div>
</div>
</body></html>';

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'Arial');
        $options->set('isFontSubsettingEnabled', true);
        $options->set('dpi', 300);
        $options->set('margin_top',    0);
        $options->set('margin_right',  0);
        $options->set('margin_bottom', 0);
        $options->set('margin_left',   0);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper([0, 0, 198.43, 113.39], 'portrait');
        $dompdf->render();


        $pdfPath = sys_get_temp_dir() . '\\badge-' . uniqid() . '.pdf';
        file_put_contents($pdfPath, $dompdf->output());

        return $pdfPath;
    }

    private function imprimerViaSumatra(string $pdfPath): void
    {
        $sumatra = $this->sumatraPath;
        $printer = $this->nomImprimante;

        $cmd = '"' . $sumatra . '"'
             . ' -print-to "' . $printer . '"'
             . ' -print-settings "fit,landscape"'
             . ' -silent'
             . ' "' . $pdfPath . '"';

        shell_exec($cmd . ' 2>&1');
    }

    private function genererQrBase64(string $url, int $taille): ?string
    {
        if (!extension_loaded('gd')) return null;

        try {
            // Générer SVG → sauvegarder temporairement
            $renderer = new \BaconQrCode\Renderer\ImageRenderer(
                new \BaconQrCode\Renderer\RendererStyle\RendererStyle($taille),
                new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
            );
            $svg     = (new \BaconQrCode\Writer($renderer))->writeString($url);
            $svgPath = sys_get_temp_dir() . '\\qr-' . uniqid() . '.svg';
            file_put_contents($svgPath, $svg);

            // Convertir SVG → PNG via Inkscape CLI si disponible
            $pngPath = sys_get_temp_dir() . '\\qr-' . uniqid() . '.png';
            $inkscape = 'C:\\Program Files\\Inkscape\\bin\\inkscape.exe';

            if (file_exists($inkscape)) {
                shell_exec('"' . $inkscape . '" --export-type=png --export-filename="' . $pngPath . '" --export-width=' . $taille . ' "' . $svgPath . '" 2>&1');
                if (file_exists($pngPath)) {
                    $data = file_get_contents($pngPath);
                    @unlink($svgPath);
                    @unlink($pngPath);
                    return base64_encode($data);
                }
            }

            // Fallback : générer QR en PNG pixel par pixel via ZXing-style algorithm
            // On utilise une lib PHP pure pour QR en bitmap
            @unlink($svgPath);
            return $this->genererQrBitmapBase64($url, $taille);

        } catch (\Exception $e) {
            logger()->error('QR base64 failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Génère un QR code PNG en pur PHP via une matrice binaire.
     * Utilise la lib chillerlan/php-qrcode si installée, sinon BaconQrCode EPS parser.
     */
    private function genererQrBitmapBase64(string $url, int $taille): ?string
    {
        // Utilise BaconQrCode pour obtenir la matrice QR brute
        try {
            $qrCode = \BaconQrCode\Encoder\Encoder::encode(
                $url,
                \BaconQrCode\Common\ErrorCorrectionLevel::M(),
                'UTF-8'
            );
            $matrix  = $qrCode->getMatrix();
            $modules = $matrix->getWidth();

            // Taille de chaque module en pixels
            $moduleSize = (int)floor($taille / ($modules + 8));
            $marge      = (int)(($taille - $modules * $moduleSize) / 2);
            $realSize   = $modules * $moduleSize + $marge * 2;

            $png   = imagecreatetruecolor($realSize, $realSize);
            $blanc = imagecolorallocate($png, 255, 255, 255);
            $noir  = imagecolorallocate($png, 0, 0, 0);
            imagefill($png, 0, 0, $blanc);

            for ($row = 0; $row < $modules; $row++) {
                for ($col = 0; $col < $modules; $col++) {
                    if ($matrix->get($col, $row)) {
                        $x1 = $marge + $col * $moduleSize;
                        $y1 = $marge + $row * $moduleSize;
                        $x2 = $x1 + $moduleSize - 1;
                        $y2 = $y1 + $moduleSize - 1;
                        imagefilledrectangle($png, $x1, $y1, $x2, $y2, $noir);
                    }
                }
            }

            ob_start();
            imagepng($png);
            $data = ob_get_clean();
            imagedestroy($png);

            return base64_encode($data);

        } catch (\Exception $e) {
            logger()->error('QR bitmap failed: ' . $e->getMessage());
            return null;
        }
    }
}
