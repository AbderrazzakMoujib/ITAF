<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Visitor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VisitorSeeder extends Seeder
{
    /**
     * Créer des visiteurs de test pour l'événement ITAF.
     */
    public function run(): void
    {
        $evenement = Event::where('slug', 'itaf-2026')->first();

        if (!$evenement) {
            $this->command->warn('Événement itaf-2026 introuvable. Lancez EventSeeder en premier.');
            return;
        }

        // Visiteurs non encore scannés (inscrits seulement)
        Visitor::factory()
            ->count(30)
            ->state(['event_id' => $evenement->id])
            ->create();

        // Visiteurs déjà scannés (présents)
        Visitor::factory()
            ->count(20)
            ->present()
            ->state(['event_id' => $evenement->id])
            ->create();

        $this->command->info('50 visiteurs de test créés pour ITAF 2026.');
    }
}
