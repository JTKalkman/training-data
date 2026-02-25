<?php

namespace Database\Seeders;

use App\Models\DataSource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DataSource::create(['name' => 'polar', 'label' => 'Polar']);
        DataSource::create(['name' => 'garmin', 'label' => 'Garmin']);
    }
}
