<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'code' => $this->faker->unique()->bothify('COMP###'),
            'tax_code' => $this->faker->numerify('##########'),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'website' => $this->faker->url(),
            'industry' => $this->faker->randomElement(['technology', 'finance', 'healthcare', 'retail', 'manufacturing']),
            'size' => $this->faker->randomElement(['small', 'medium', 'large', 'enterprise']),
            'status' => 'active',
        ];
    }
}