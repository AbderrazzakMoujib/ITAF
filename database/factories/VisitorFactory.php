<?php

namespace Database\Factories;

use App\Models\Visitor;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VisitorFactory extends Factory
{
    protected $model = Visitor::class;

    public function definition(): array
    {
        $entreprises = [
            'OCP Group', 'Maroc Telecom', 'Attijariwafa Bank', 'CIH Bank',
            'ONCF', 'RAM', 'Al Omrane', 'CDG', 'BMCE Bank', 'Société Générale Maroc',
        ];

        $fonctions = [
            'Directeur Général', 'Responsable RH', 'Chef de Projet', 'Ingénieur',
            'Commercial', 'Consultant', 'Analyste', 'Manager', 'Directeur Technique',
        ];

        $pays = ['Maroc', 'France', 'Belgique', 'Espagne', 'Sénégal', 'Côte d\'Ivoire'];

        return [
            'event_id'    => Event::factory(),
            'nom'         => $this->faker->lastName(),
            'prenom'      => $this->faker->firstName(),
            'entreprise'  => $this->faker->randomElement($entreprises),
            'fonction'    => $this->faker->randomElement($fonctions),
            'email'       => $this->faker->unique()->safeEmail(),
            'telephone'   => '+212' . $this->faker->numerify('#########'),
            'pays'        => $this->faker->randomElement($pays),
            'token'       => Str::uuid()->toString(),
            'qr_code_path' => null,
            'scan_count'  => 0,
            'first_scanned_at' => null,
            'registered_at' => now(),
        ];
    }

    /**
     * État : visiteur déjà scanné (présent).
     */
    public function present(): static
    {
        return $this->state(fn (array $attributs) => [
            'scan_count'       => $this->faker->numberBetween(1, 3),
            'first_scanned_at' => $this->faker->dateTimeBetween('-2 hours', 'now'),
        ]);
    }
}
