<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'nom',
        'slug',
        'date_debut',
        'date_fin',
        'lieu',
        'description',
        'logo_path',
        'actif',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin'   => 'date',
        'actif'      => 'boolean',
    ];

    /**
     * Retourne tous les visiteurs inscrits à cet événement.
     */
    public function visiteurs(): HasMany
    {
        return $this->hasMany(Visitor::class, 'event_id');
    }

    /**
     * Retourne le nombre de visiteurs présents (scannés au moins une fois).
     */
    public function nombrePresents(): int
    {
        return $this->visiteurs()->where('scan_count', '>', 0)->count();
    }

    /**
     * Retourne le taux de présence en pourcentage.
     */
    public function tauxPresence(): float
    {
        $total = $this->visiteurs()->count();
        if ($total === 0) return 0;
        return round(($this->nombrePresents() / $total) * 100, 1);
    }

    /**
     * Récupère l'événement actif courant (utilisé dans le kiosque).
     */
    public static function actifCourant(): ?self
    {
        return static::where('actif', true)->latest()->first();
    }
}
