<?php

// Đường dẫn: C:\xampp\htdocs\security_evaluation_system\01_backend\database\factories\IntegrationFactory.php

namespace Database\Factories;

use App\Models\Integration;
use Illuminate\Database\Eloquent\Factories\Factory;

class IntegrationFactory extends Factory
{
    protected $model = Integration::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'type' => $this->faker->randomElement(['api', 'webhook', 'smtp']),
            'config' => [
                'api_key' => $this->faker->uuid(),
                'endpoint' => $this->faker->url(),
            ],
            'status' => $this->faker->randomElement(['enabled', 'disabled']),
        ];
    }
}