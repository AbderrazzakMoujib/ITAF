<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer la table des visiteurs inscrits.
     */
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')                   // Référence à l'événement
                  ->constrained('events')
                  ->onDelete('cascade');
            $table->string('nom');                          // Nom de famille
            $table->string('prenom');                       // Prénom
            $table->string('entreprise')->nullable();       // Entreprise / organisation
            $table->string('fonction')->nullable();         // Poste / fonction
            $table->string('email')->unique();              // Email unique par visiteur
            $table->string('telephone')->nullable();        // Téléphone (optionnel)
            $table->string('pays')->nullable();             // Pays (optionnel)
            $table->string('token')->unique();              // Token UUID pour QR code
            $table->string('qr_code_path')->nullable();    // Chemin du QR code généré
            $table->integer('scan_count')->default(0);     // Nombre de fois scanné
            $table->timestamp('first_scanned_at')->nullable(); // 1er scan (heure présence)
            $table->timestamp('registered_at')->useCurrent(); // Date d'inscription
            $table->timestamps();
        });
    }

    /**
     * Supprimer la table des visiteurs.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
