<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evaluation;
use App\Models\User;
use App\Models\Domain;

class EvaluationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $domains = Domain::all();

        foreach ($users as $user) {
            foreach ($domains->random(3) as $domain) {
                Evaluation::create([
                    'title' => "Đánh giá {$domain->name} - {$user->name}",
                    'domain_id' => $domain->id,
                    'user_id' => $user->id,
                    'company_id' => $user->company_id,
                    'status' => 'approved',
                    'percentage' => rand(60, 95),
                    'submitted_at' => now(),
                    'approved_at' => now(),
                ]);
            }
        }
    }
}