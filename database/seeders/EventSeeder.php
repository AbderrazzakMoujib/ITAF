<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Créer l'événement ITAF principal.
     */
    public function run(): void
    {
        Event::firstOrCreate(
            ['slug' => 'itaf-2026'],
            [
                'nom'         => 'Industrial Transformation Africa',
                'date_debut'  => '2026-09-29',
                'date_fin'    => '2026-10-01',
                'lieu'        => 'OFEC – Casablanca, Maroc',
                'description' => 'Le rendez-vous africain de la transformation industrielle — ITA 2026',
                'actif'       => true,
            ]
        );

        // Mettre à jour l'événement existant si nécessaire
        Event::where('slug', 'itaf-2026')->update([
            'nom'        => 'Industrial Transformation Africa',
            'date_debut' => '2026-09-29',
            'date_fin'   => '2026-10-01',
            'lieu'       => 'OFEC – Casablanca, Maroc',
        ]);
    }
}
