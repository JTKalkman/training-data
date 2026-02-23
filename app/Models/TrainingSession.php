<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TrainingSession extends Model
{
    /** @use HasFactory<\Database\Factories\TrainingSessionFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sport_type_id',
        'started_at',
        'duration_seconds',
        'source',
        'file_path',
    ];

    protected $casts = [
        'started_at' => 'datetime',
    ];

    public function sportType(): BelongsTo
    {
        return $this->belongsTo(SportType::class);
    }

    public function heartRateZones(): HasMany
    {
        return $this->hasMany(HeartRateZone::class);
    }

    public function trainingSummary(): HasOne
    {
        return $this->hasOne(TrainingSummary::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
