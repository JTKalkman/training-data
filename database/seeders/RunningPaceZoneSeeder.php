<?php

namespace Database\Seeders;

use App\Models\RunningPaceZone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Pace zones based on the Jack Daniels running formula.
 * Values are stored in seconds per km.
 * Ranges are approximate for a recreational runner (5K around 25-28 min).
*/
class RunningPaceZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RunningPaceZone::create([
            'zone_number' => 1,
            'name' => 'Recovery',
            'min_seconds' => 450,
            'max_seconds' => 1200,
            'color' => null,
        ]);

        RunningPaceZone::create([
            'zone_number' => 2,
            'name' => 'Easy',
            'min_seconds' => 360,
            'max_seconds' => 450,
            'color' => null,
        ]);

        RunningPaceZone::create([
            'zone_number' => 3,
            'name' => 'Tempo',
            'min_seconds' => 300,
            'max_seconds' => 360,
            'color' => null,
        ]);

        RunningPaceZone::create([
            'zone_number' => 4,
            'name' => 'Interval',
            'min_seconds' => 255,
            'max_seconds' => 300,
            'color' => null,
        ]);

        RunningPaceZone::create([
            'zone_number' => 5,
            'name' => 'Repetition',
            'min_seconds' => null,
            'max_seconds' => 255,
            'color' => 210,
        ]);
    }
}
