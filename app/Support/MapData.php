<?php

namespace App\Support;

use App\Models\TrainingSession;
use Illuminate\Support\Facades\Storage;

class MapData
{
    public static function fromSession(TrainingSession $session): ?string
    {
        $path = $session->routeDataPath();

        if (! Storage::exists($path)) {
            return null;
        }

        return Storage::get($path);
    }
}
