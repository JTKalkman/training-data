<?php

namespace Database\Seeders;

use App\Models\RunningPaceZone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * 
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
            'min_seconds' => 390,
            'max_seconds' => 1200,
            'color' => null,
        ]);

        RunningPaceZone::create([
            'zone_number' => 2,
            'name' => 'Easy',
            'min_seconds' => 360,
            'max_seconds' => 390,
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
            'color' => null,
        ]);
    }
}
