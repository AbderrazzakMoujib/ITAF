<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$v = App\Models\Visitor::first();
if (!$v) { echo 'Aucun visiteur'; exit; }

$s = new App\Services\BadgeImprimante();

// Genere PDF et ouvre dans le navigateur pour vérifier
$reflection = new ReflectionClass($s);
$method = $reflection->getMethod('genererPdf');
$method->setAccessible(true);
$pdfPath = $method->invoke($s, $v);

// Copier dans public pour voir
$dest = public_path('badge_test.pdf');
copy($pdfPath, $dest);
@unlink($pdfPath);

echo "PDF disponible sur: http://127.0.0.1:8000/badge_test.pdf" . PHP_EOL;
echo "Dimensions PDF: 70mm x 40mm (198.4 x 113.4 pts)" . PHP_EOL;
