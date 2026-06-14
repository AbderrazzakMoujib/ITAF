<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $nom = 'ITAF ' . $this->faker->year();

        return [
            'nom'        => $nom,
            'slug'       => Str::slug($nom),
            'date_debut' => $this->faker->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
            'date_fin'   => $this->faker->dateTimeBetween('+6 months', '+7 months')->format('Y-m-d'),
            'lieu'       => 'Centre de Conférences, Casablanca',
            'description' => 'Forum international des affaires et technologies',
            'actif'      => true,
        ];
    }
}
