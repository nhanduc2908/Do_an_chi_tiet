<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'contact_name' => $this->faker->name(),
            'contact_email' => $this->faker->email(),
            'contact_phone' => $this->faker->phoneNumber(),
            'website' => $this->faker->url(),
            'risk_level' => $this->faker->randomElement(['low', 'medium', 'high', 'critical']),
            'status' => 'active',
            'description' => $this->faker->paragraph(),
        ];
    }
}