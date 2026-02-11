<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HeartRateZone extends Model
{
    /** @use HasFactory<\Database\Factories\HeartRateZoneFactory> */
    use HasFactory;

    protected $fillable = [
        'training_session_id',
        'zone_number',
        'name',
        'min_bpm',
        'max_bpm',
    ];

    public function trainingSession(): BelongsTo
    {
        return $this->BelongsTo(TrainingSession::class);
    }
}
