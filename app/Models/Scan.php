<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Scan extends Model
{
    protected $table = 'scans';

    protected $fillable = [
        'visitor_id',
        'scanned_at',
        'kiosque_id',
        'ip_address',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    /**
     * Retourne le visiteur associé à ce scan.
     */
    public function visiteur(): BelongsTo
    {
        return $this->belongsTo(Visitor::class, 'visitor_id');
    }
}
