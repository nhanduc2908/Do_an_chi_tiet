<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(['evaluation', 'summary', 'compliance']),
            'format' => $this->faker->randomElement(['pdf', 'excel', 'csv']),
            'evaluation_id' => Evaluation::factory(),
            'user_id' => User::factory(),
            'file_path' => 'reports/' . $this->faker->uuid() . '.pdf',
            'file_size' => $this->faker->numberBetween(1000, 5000000),
            'status' => 'completed',
            'generated_at' => now(),
        ];
    }
}