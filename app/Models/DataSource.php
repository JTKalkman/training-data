<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataSource extends Model
{
    public function trainingSessions(): BelongsToMany
    {
        return $this->belongsToMany(TrainingSession::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }
}
