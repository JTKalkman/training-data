<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function seedUsers(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    public function seedLookups(): void
    {
        $this->call([
            SportTypeSeeder::class,
        ]);
    }

    public function seedTrainingSessions(): void
    {
        $this->call([
            TrainingSessionSeeder::class,
        ]);
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->seedUsers();
        $this->seedLookups();
        $this->seedTrainingSessions();
    }
}
