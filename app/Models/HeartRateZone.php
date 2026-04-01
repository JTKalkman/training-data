<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeartRateZone extends Model
{
    protected $fillable = [
        'training_session_id', // Better not to be mass assignable.
        'zone_number',
        'name',
        'min_bpm',
        'max_bpm',
        'color',
        'in_zone_seconds',
    ];

    public function trainingSession(): BelongsTo
    {
        return $this->BelongsTo(TrainingSession::class);
    }
}
