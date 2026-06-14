<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Peupler la base de données avec des données de test.
     */
    public function run(): void
    {
        $this->call([
            EventSeeder::class,
            VisitorSeeder::class,
        ]);
    }
}
