<?php

namespace Database\Factories;

use App\Models\SportType;
use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingSession>
 */
class TrainingSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'sport_type_id' => SportType::factory(),
            'started_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'duration_seconds' => $this->faker->numberBetween(1800, 14400),
            'source' => 'test',
            'file_path' => $this->faker->filePath(),
        ];
    }
}
