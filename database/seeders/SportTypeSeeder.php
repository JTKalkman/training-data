<?php

namespace Database\Seeders;

use App\Models\SportType;
use Illuminate\Database\Seeder;

class SportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SportType::create(['name' => 'cycling', 'label' => 'Cycling']);
        SportType::create(['name' => 'mountain_bike', 'label' => 'Mountain Bike']);
        SportType::create(['name' => 'running', 'label' => 'Running']);
        SportType::create(['name' => 'indoor_cycling', 'label' => 'Indoor Cycling']);
        SportType::create(['name' => 'weight_training', 'label' => 'Weight Training']);
        SportType::create(['name' => 'other_indoor', 'label' => 'Other Indoor']);
        SportType::create(['name' => 'bootcamp', 'label' => 'Bootcamp']);
    }
}
