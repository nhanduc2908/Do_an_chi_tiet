<?php

namespace Database\Factories;

use App\Models\Domain;
use Illuminate\Database\Eloquent\Factories\Factory;

class DomainFactory extends Factory
{
    protected $model = Domain::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'name_en' => $this->faker->words(3, true),
            'code' => $this->faker->unique()->bothify('SEC###'),
            'description' => $this->faker->paragraph(),
            'weight' => $this->faker->numberBetween(1, 10),
            'status' => 'active',
        ];
    }
}