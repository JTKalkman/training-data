<?php

namespace App\Support\Parsers\Mappers;

use App\Models\SportType;

class SportTypeMapper
{
    protected const MAP = [
        'MTB' => 'mountain_bike',
        'CYCLING' => 'cycling',
        'RUNNING' => 'running',
        'OTHER_INDOOR' => 'other_indoor',
        'STRENGTH_TRAINING' => 'weight_training',
        'BOOTCAMP' => 'bootcamp',
        'MOUNTAIN_BIKING' => 'mountain_bike'
    ];

    public static function map(string $external): ?SportType
    {
        $internal = self::MAP[$external] ?? null;

        if (! $internal) {
            return null;
        }

        return SportType::where('name', $internal)->first();
    }
}
