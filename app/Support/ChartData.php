<?php

namespace App\Support;

use App\Models\TrainingSession;

class ChartData
{
    public static function fromSession(TrainingSession $session): array
    {
        $path = $session->filePath();

        if (! file_exists($path)) {
            return [];
        }

        $rawData = json_decode(file_get_contents($session->filePath()), true);

        return array_map(fn ($row) => [
            'time' => Duration::clock($row['time']),
            'heart_rate' => $row['heart_rate'],
        ], $rawData);
    }
}
