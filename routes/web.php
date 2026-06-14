<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\KiosqueController;
use App\Http\Controllers\VisitorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Web — Système de Badging ITAF
|--------------------------------------------------------------------------
*/

// ─── Page d'accueil : redirige vers le formulaire de l'événement actif ──────
Route::get('/', function () {
    $evenement = \App\Models\Event::actifCourant();
    if ($evenement) {
        return redirect()->route('visiteur.formulaire', $evenement->slug);
    }
    return view('welcome');
})->name('accueil');

// ─── Formulaire d'inscription visiteur ──────────────────────────────────────
Route::prefix('inscription/{slug}')->group(function () {
    Route::get('/', [VisitorController::class, 'formulaire'])
        ->name('visiteur.formulaire');

    Route::post('/', [VisitorController::class, 'store'])
        ->name('visiteur.store');
});

// ─── Confirmation et QR code ─────────────────────────────────────────────────
Route::prefix('confirmation')->group(function () {
    Route::get('/{token}', [VisitorController::class, 'confirmation'])
        ->name('visiteur.confirmation');

    Route::get('/{token}/qr', [VisitorController::class, 'afficherQr'])
        ->name('visiteur.qr');

    Route::get('/{token}/telecharger', [VisitorController::class, 'telechargerQr'])
        ->name('visiteur.telecharger-qr');
});

// ─── Impression badge ────────────────────────────────────────────────────────
Route::get('/badge/print/{token}', [KiosqueController::class, 'printBadge'])
    ->name('badge.print');

// ─── Kiosque de scan événement (accès direct, pas d'auth requise) ────────────
Route::prefix('kiosque')->name('kiosque.')->group(function () {
    Route::get('/', [KiosqueController::class, 'index'])
        ->name('index');

    Route::post('/scanner', [KiosqueController::class, 'scanner'])
        ->name('scanner');

    Route::get('/scans-recents', [KiosqueController::class, 'scansRecents'])
        ->name('scans-recents');

    Route::post('/imprimante', [KiosqueController::class, 'setImprimante'])
        ->name('imprimante');
});

// ─── Tableau de bord administrateur ─────────────────────────────────────────
// Note : en production, protéger ces routes avec auth middleware
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])
        ->name('dashboard');

    // Visiteurs
    Route::get('/visiteurs', [AdminController::class, 'visiteurs'])
        ->name('visiteurs');

    Route::get('/visiteurs/{visiteur}', [AdminController::class, 'voirVisiteur'])
        ->name('visiteur.voir');

    Route::post('/visiteurs/{visiteur}/email', [AdminController::class, 'renvoyerEmail'])
        ->name('visiteur.email');

    Route::delete('/visiteurs/{visiteur}', [AdminController::class, 'supprimerVisiteur'])
        ->name('visiteur.supprimer');

    Route::get('/visiteurs/exporter/csv', [AdminController::class, 'exporterCsv'])
        ->name('visiteurs.export');

    // Scans / journal de présence
    Route::get('/scans', [AdminController::class, 'scans'])
        ->name('scans');

    // Statistiques JSON pour rafraîchissement live du dashboard
    Route::get('/stats', [AdminController::class, 'statsJson'])
        ->name('stats');
});
