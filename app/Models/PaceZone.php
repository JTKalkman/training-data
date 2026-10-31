<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaceZone extends Model
{
    protected $fillable = [
        'pace_zone_set_id',
        'zone_number',
        'name',
        'min_seconds_per_km',
        'max_seconds_per_km'
    ];

    public function paceZoneSet(): BelongsTo
    {
        return $this->belongsTo(PaceZoneSet::class);
    }
}
