<?php
require 'vendor/autoload.php';

try {
    $ecl = new \BaconQrCode\Common\ErrorCorrectionLevel(
        \BaconQrCode\Common\ErrorCorrectionLevel::M
    );
    $qr     = \BaconQrCode\Encoder\Encoder::encode('https://test.ma/confirmation/abc', $ecl, 'UTF-8');
    $matrix = $qr->getMatrix();
    echo "Matrix width: " . $matrix->getWidth() . PHP_EOL;
    echo "Matrix height: " . $matrix->getHeight() . PHP_EOL;
    echo "QR Matrix OK!" . PHP_EOL;
} catch (Exception $e) {
    echo "ERREUR: " . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
