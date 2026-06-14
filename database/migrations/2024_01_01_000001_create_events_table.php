<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer la table des événements.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('nom');                          // Nom de l'événement
            $table->string('slug')->unique();               // URL conviviale (ex: itaf-2026)
            $table->date('date_debut');                     // Date de début
            $table->date('date_fin')->nullable();           // Date de fin (optionnel)
            $table->string('lieu')->nullable();             // Lieu de l'événement
            $table->text('description')->nullable();        // Description courte
            $table->string('logo_path')->nullable();        // Chemin du logo
            $table->boolean('actif')->default(true);        // Événement en cours
            $table->timestamps();
        });
    }

    /**
     * Supprimer la table des événements.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
