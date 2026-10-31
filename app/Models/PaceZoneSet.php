<?php

namespace App\Models;

use App\Support\Services\PaceZoneGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class PaceZoneSet extends Model
{
    protected $fillable = [
        'user_id', 
        'mas_seconds_per_km', 
        'source',
        'scheme'
    ];

    public function paceZones(): HasMany
    {
        return $this->hasMany(PaceZone::class);
    }

    public static function forMas(user $user, int $masSecondsPerKm)
    {
        return DB::transaction(function () use ($user, $masSecondsPerKm) {
            $set = self::firstOrCreate([
                'user_id' => $user->id,
                'source' => 'default',
                'scheme' => 'polar_mas_5',
                'mas_seconds_per_km' => $masSecondsPerKm,
            ]);

            if ($set->wasRecentlyCreated) {
                $zones = (new PaceZoneGenerator())->generateDefaultPaceZones(
                    config('pace_zones.polar_mas_5'),
                    $masSecondsPerKm
                );
    
                foreach ($zones as $zone) {
                    $set->paceZones()->create($zone);
                }
            }

            return $set;
        });
    }
}
