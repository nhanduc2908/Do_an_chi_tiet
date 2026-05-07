<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'type' => $this->faker->randomElement(['hardware', 'software', 'data', 'service', 'person']),
            'classification' => $this->faker->randomElement(['public', 'internal', 'confidential', 'restricted']),
            'owner_id' => User::factory(),
            'location' => $this->faker->city(),
            'value' => $this->faker->numberBetween(1000, 1000000),
            'status' => 'active',
            'description' => $this->faker->paragraph(),
        ];
    }
}