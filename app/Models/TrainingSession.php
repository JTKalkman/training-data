<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TrainingSession extends Model
{
    /** @use HasFactory<\Database\Factories\TrainingSessionFactory> */
    use HasFactory;

    use HasUuids;

    protected $fillable = [
        'user_id',
        'sport_type_id',
        'started_at',
        'utc_offset',
        'duration_seconds',
        'data_source_id',
        'external_id',
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

    public function filePath(): string
    {
        return storage_path("app/training_data/{$this->user_id}/{$this->id}.json");
    }

    public function dataSource(): BelongsTo
    {
        return $this->belongsTo(DataSource::class);
    }
}
