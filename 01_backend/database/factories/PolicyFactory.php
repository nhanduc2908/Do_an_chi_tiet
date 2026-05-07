<?php

namespace Database\Factories;

use App\Models\Policy;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PolicyFactory extends Factory
{
    protected $model = Policy::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(5, true),
            'category' => $this->faker->randomElement(['security', 'compliance', 'hr', 'it']),
            'version' => $this->faker->randomElement(['1.0', '1.1', '2.0']),
            'effective_date' => $this->faker->date(),
            'created_by' => User::factory(),
            'is_active' => true,
        ];
    }
}