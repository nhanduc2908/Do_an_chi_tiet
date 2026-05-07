<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\Evaluation;
use App\Models\User;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $evaluations = Evaluation::all();
        $users = User::all();

        foreach ($evaluations as $evaluation) {
            Report::create([
                'title' => "Báo cáo đánh giá {$evaluation->title}",
                'type' => 'evaluation',
                'format' => 'pdf',
                'evaluation_id' => $evaluation->id,
                'user_id' => $users->random()->id,
                'file_path' => "reports/eval_{$evaluation->id}.pdf",
                'status' => 'completed',
                'generated_at' => now(),
            ]);
        }
    }
}