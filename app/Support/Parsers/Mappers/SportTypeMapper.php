<?php

namespace App\Support\Parsers\Mappers;

use App\Models\SportType;

class SportTypeMapper
{
    protected const MAP = [
        'MTB' => 'mountain-biking',
        'CYCLING' => 'cycling',
        'RUNNING' => 'running',
        'OTHER_INDOOR' => 'other-indoor',
        'STRENGTH_TRAINING' => 'strength-training',
        'BOOTCAMP' => 'bootcamp',
        'MOUNTAIN_BIKING' => 'mountain-biking',
        'INDOOR_CYCLING' => 'indoor-cycling',
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
