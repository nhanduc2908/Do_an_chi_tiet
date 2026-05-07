<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'display_name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'level' => $this->faker->numberBetween(10, 100),
            'color' => $this->faker->hexColor(),
            'icon' => $this->faker->word(),
            'requires_key' => false,
            'requires_otp' => false,
            'max_sessions' => 3,
        ];
    }
}