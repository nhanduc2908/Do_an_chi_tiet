<?php

namespace Database\Factories;

use App\Models\Incident;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncidentFactory extends Factory
{
    protected $model = Incident::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'severity' => $this->faker->randomElement(['critical', 'high', 'medium', 'low']),
            'type' => $this->faker->randomElement(['breach', 'attack', 'malware', 'misconfiguration']),
            'status' => $this->faker->randomElement(['open', 'investigating', 'contained', 'resolved', 'closed']),
            'detected_at' => now(),
            'assigned_to' => User::factory(),
            'reported_by' => User::factory(),
        ];
    }
}