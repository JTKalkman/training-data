<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'map_watts',
        'ftp_watts',
    ];

    protected $appends = ['mas_pace'];

    protected function masPace(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->mas_seconds_per_km !== null
                ? sprintf('%d:%02d', intdiv($this->mas_seconds_per_km, 60), $this->mas_seconds_per_km % 60)
                : null,
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
