<?php

namespace App\Support;

use App\Models\TrainingSession;

class ChartData
{
    public static function fromSession(TrainingSession $session): array
    {
        if (!file_exists($session->file_path)) {
            return [];
        }

        $rawData = json_decode(file_get_contents($session->file_path), true);

        return array_map(fn($row) => [
            'time' => Duration::clock($row['time']),
            'heart_rate'   => $row['heart_rate'],
        ], $rawData);
    }
}
