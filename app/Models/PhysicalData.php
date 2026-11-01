<?php

namespace App\Models;

use App\Observers\PhysicalDataObserver;
use App\Support\Calculators\PaceCalculator;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([PhysicalDataObserver::class])]
class PhysicalData extends Model
{
    /**
     * active_pace_zone_set_id should stay out of fillable, so a
     * form can never set the pointer. Only the observer and the
     * zones page set it.
     */
    protected $fillable = [
        'date_of_birth',
        'weight_kg',
        'height_cm',
        'sex',
        'resting_heart_rate',
        'max_heart_rate',
        'aerobic_threshold_bpm',
        'anaerobic_threshold_bpm',
        'mas_seconds_per_km',
        'vo2_max',
        'map_watts',
        'ftp_watts',
    ];

    protected $appends = ['mas_pace'];

    protected function masPace(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->mas_seconds_per_km !== null
                ? PaceCalculator::fromSeconds($this->mas_seconds_per_km)
                : null,
        );
    }

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date:Y-m-d',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function activePaceZoneSet(): BelongsTo
    {
        return $this->belongsTo(PaceZoneSet::class, 'active_pace_zone_set_id');
    }
}
