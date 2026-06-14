<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer la table des scans QR code (journal de présence).
     */
    public function up(): void
    {
        Schema::create('scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')                 // Référence au visiteur scanné
                  ->constrained('visitors')
                  ->onDelete('cascade');
            $table->timestamp('scanned_at')->useCurrent();  // Heure exacte du scan
            $table->string('kiosque_id')->nullable();       // Identifiant du kiosque (traçabilité)
            $table->string('ip_address')->nullable();       // IP du kiosque
            $table->timestamps();
        });
    }

    /**
     * Supprimer la table des scans.
     */
    public function down(): void
    {
        Schema::dropIfExists('scans');
    }
};
