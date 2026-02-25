<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SportType extends Model
{
    /** @use HasFactory<\Database\Factories\SportTypeFactory> */
    use HasFactory;

    protected $fillable = ['name', 'label'];

    public function trainingSessions(): HasMany
    {
        return $this->hasMany(TrainingSession::class);
    }
}
