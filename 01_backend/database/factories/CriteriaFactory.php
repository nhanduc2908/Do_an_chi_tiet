<?php

namespace Database\Factories;

use App\Models\Criteria;
use App\Models\Domain;
use Illuminate\Database\Eloquent\Factories\Factory;

class CriteriaFactory extends Factory
{
    protected $model = Criteria::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(4),
            'name_en' => $this->faker->sentence(4),
            'code' => $this->faker->unique()->bothify('C###'),
            'domain_id' => Domain::factory(),
            'criteria_group' => $this->faker->randomElement(['policy', 'technical', 'operational', 'compliance']),
            'weight' => $this->faker->numberBetween(1, 10),
            'priority' => $this->faker->randomElement(['high', 'medium', 'low']),
            'description' => $this->faker->paragraph(),
            'order' => $this->faker->numberBetween(1, 100),
            'status' => 'active',
        ];
    }
}