<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingSummary extends Model
{
    protected $fillable = [
        'training_session_id',
        'min_heart_rate',
        'avg_heart_rate',
        'max_heart_rate',
        'distance',
        'calories',
        'has_route',
        'training_load',
    ];

    public function trainingSession(): BelongsTo
    {
        return $this->belongsTo(TrainingSession::class);
    }
}
