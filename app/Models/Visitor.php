<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Visitor extends Model
{
    use HasFactory;

    protected $table = 'visitors';

    protected $fillable = [
        'event_id',
        'nom',
        'prenom',
        'entreprise',
        'fonction',
        'email',
        'telephone',
        'pays',
        'token',
        'qr_code_path',
        'scan_count',
        'first_scanned_at',
        'registered_at',
        'cndp_consent',
    ];

    protected $casts = [
        'first_scanned_at' => 'datetime',
        'registered_at'    => 'datetime',
        'scan_count'       => 'integer',
    ];

    /**
     * Retourne l'événement auquel ce visiteur est inscrit.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * Retourne tous les scans de ce visiteur.
     */
    public function scans(): HasMany
    {
        return $this->hasMany(Scan::class, 'visitor_id');
    }

    /**
     * Retourne le nom complet du visiteur (Prénom + Nom).
     */
    public function getNomCompletAttribute(): string
    {
        return trim("{$this->prenom} {$this->nom}");
    }

    /**
     * Indique si le visiteur a déjà été scanné (présent).
     */
    public function estPresent(): bool
    {
        return $this->scan_count > 0;
    }

    /**
     * Enregistre un nouveau scan et met à jour les compteurs.
     */
    public function enregistrerScan(string $kiosqueId = null, string $ipAddress = null): Scan
    {
        $this->increment('scan_count');

        // Enregistrer l'heure du premier scan uniquement
        if ($this->scan_count === 1) {
            $this->update(['first_scanned_at' => now()]);
        }

        return $this->scans()->create([
            'scanned_at' => now(),
            'kiosque_id' => $kiosqueId,
            'ip_address' => $ipAddress,
        ]);
    }

    /**
     * Retrouver un visiteur par son token QR code.
     */
    public static function trouverParToken(string $token): ?self
    {
        return static::where('token', $token)->with('event')->first();
    }
}
