<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DataSource extends Model
{
    public function trainingSession(): BelongsToMany
    {
        return $this->belongsToMany(TrainingSession::class);
    }
}
