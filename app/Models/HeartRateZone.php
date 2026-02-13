<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeartRateZone extends Model
{

    protected $fillable = [
        'training_session_id',
        'zone_number',
        'name',
        'min_bpm',
        'max_bpm',
        'color',
    ];

    public function trainingSession(): BelongsTo
    {
        return $this->BelongsTo(TrainingSession::class);
    }
}
