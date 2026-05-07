<?php

namespace Database\Factories;

use App\Models\Training;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingFactory extends Factory
{
    protected $model = Training::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'type' => $this->faker->randomElement(['course', 'webinar', 'workshop']),
            'duration' => $this->faker->numberBetween(30, 300),
            'content_url' => $this->faker->url(),
            'status' => 'active',
            'created_by' => User::factory(),
        ];
    }
}