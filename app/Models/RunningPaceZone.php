<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RunningPaceZone extends Model
{
    /** @use HasFactory<\Database\Factories\RunningPaceZoneFactory> */
    use HasFactory;

    protected $fillable = [
        'zone_number',
        'name',
        'min_seconds',
        'max_seconds',
        'color',
        'in_zone_seconds',
    ];
}
