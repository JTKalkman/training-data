<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingSettings extends Model
{
    protected $fillable = [
        'date_of_birth',
        'weight_kg',
        'height_cm',
        'resting_heart_rate',
        'max_heart_rate',
        'aerobic_threshold_bpm',
        'anaerobic_threshold_bpm',
        'mas_seconds_per_km',
        'ftp_watts',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
