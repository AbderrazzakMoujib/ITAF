<?php

use App\Http\Controllers\KiosqueController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes API — Système de Badging ITA
| Préfixe automatique : /api  — Pas de CSRF (stateless pour mobile)
|--------------------------------------------------------------------------
*/

// Sanity check
Route::get('/ping', fn () => response()->json(['status' => 'ok', 'app' => 'ITA Badging']));

// Scan QR code depuis l'app Android (même logique que le kiosque web)
Route::post('/scan', [KiosqueController::class, 'scanner']);
