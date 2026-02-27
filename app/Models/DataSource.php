<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataSource extends Model
{
    public function trainingSessions(): HasMany
    {
        return $this->hasMany(TrainingSession::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }
}
