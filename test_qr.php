<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$v = App\Models\Visitor::first();
$url = route('visiteur.confirmation', $v->token);

$renderer = new \BaconQrCode\Renderer\ImageRenderer(
    new \BaconQrCode\Renderer\RendererStyle\RendererStyle(150),
    new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
);
$svg = (new \BaconQrCode\Writer($renderer))->writeString($url);

// Sauvegarder SVG pour voir
file_put_contents(public_path('test_qr.svg'), $svg);

// Afficher les 3 premières lignes
$lines = explode("\n", $svg);
foreach (array_slice($lines, 0, 8) as $l) echo $l . PHP_EOL;

echo PHP_EOL . "---" . PHP_EOL;

// Compter les rects
preg_match_all('/<rect[^>]+\/>/i', $svg, $m1);
echo "Rects avec />: " . count($m1[0]) . PHP_EOL;

preg_match_all('/<rect[^>]+>/i', $svg, $m2);
echo "Rects total: " . count($m2[0]) . PHP_EOL;

// Afficher 1er rect
if (!empty($m2[0])) echo "1er rect: " . $m2[0][0] . PHP_EOL;
