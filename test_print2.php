<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$v = App\Models\Visitor::first();
$s = new App\Services\BadgeImprimante();
$s->imprimer($v);
echo "Imprimé!" . PHP_EOL;
