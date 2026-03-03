<?php

namespace App\Support;

use App\Models\TrainingSession;
use Illuminate\Support\Facades\Storage;

class MapData
{
    // TMP: Very simple downsampling. Use RDP of Visvalingam for more accurate results.
    private static function downSample(array $coordinates, int $nth = 5): array
    {
        $result = [];
    
        foreach ($coordinates as $i => $coordinate) {
            if ($i % $nth === 0) {
                $result[] = $coordinate;
            }
        }

        if (end($coordinates) !== end($result)) {
            $result[] = end($coordinates);
        }

        return $result;
    }

    public static function fromSession(TrainingSession $session): ?string
    {
        $path = $session->routeDataPath();

        if (! Storage::exists($path)) {
            return null;
        }

        $coordinates = json_decode(Storage::get($path), true);
        $downSampled = self::downSample($coordinates);

        return json_encode($downSampled);
    }
}
