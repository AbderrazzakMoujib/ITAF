<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Enregistrer les services de l'application.
     */
    public function register(): void
    {
        //
    }

    /**
     * Initialiser les services de l'application.
     */
    public function boot(): void
    {
        // Utiliser Bootstrap 5 pour la pagination Laravel
        Paginator::useBootstrapFive();

        // Forcer HTTPS en production (cPanel/NindoHost)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
