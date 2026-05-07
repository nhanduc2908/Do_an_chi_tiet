<?php

namespace Database\Factories;

use App\Models\Evaluation;
use App\Models\User;
use App\Models\Domain;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class EvaluationFactory extends Factory
{
    protected $model = Evaluation::class;

    public function definition(): array
    {
        $statuses = ['draft', 'submitted', 'approved', 'rejected'];
        $status = $this->faker->randomElement($statuses);
        
        return [
            'title' => $this->faker->sentence(),
            'domain_id' => Domain::factory(),
            'user_id' => User::factory(),
            'company_id' => Company::factory(),
            'notes' => $this->faker->paragraph(),
            'status' => $status,
            'total_score' => $this->faker->numberBetween(0, 100),
            'max_score' => 100,
            'percentage' => $this->faker->numberBetween(0, 100),
            'submitted_at' => $status != 'draft' ? now() : null,
            'approved_at' => $status == 'approved' ? now() : null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'submitted_at' => now(),
            'approved_at' => now(),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'submitted_at' => null,
            'approved_at' => null,
        ]);
    }
}