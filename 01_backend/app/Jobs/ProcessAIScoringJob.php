<?php

namespace App\Jobs;

use App\Models\Evaluation;
use App\Services\AI\AIScoringEngine;
use App\Services\Evaluation\HybridScoringService;
use App\Events\EvaluationSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessAIScoringJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public Evaluation $evaluation;
    public int $timeout = 300;
    public int $tries = 3;

    public function __construct(Evaluation $evaluation)
    {
        $this->evaluation = $evaluation;
    }

    public function handle(AIScoringEngine $aiEngine, HybridScoringService $scoringService): void
    {
        try {
            Log::info("Processing AI scoring for evaluation ID: {$this->evaluation->id}");

            // Sử dụng hybrid scoring (AI + Rule)
            $result = $scoringService->calculateScore($this->evaluation, true);

            // Lưu kết quả AI prediction
            $this->evaluation->update([
                'ai_score' => $result['percentage'],
                'ai_scored_at' => now(),
            ]);

            Log::info("AI scoring completed for evaluation {$this->evaluation->id}, score: {$result['percentage']}");

            // Phát event nếu cần
            if ($this->evaluation->status === 'submitted') {
                event(new EvaluationSubmitted($this->evaluation, $this->evaluation->user));
            }

        } catch (\Exception $e) {
            Log::error("AI scoring failed for evaluation {$this->evaluation->id}: {$e->getMessage()}");
            
            // Fallback to rule-based scoring
            $scoringService->calculateScore($this->evaluation, false);
            
            $this->release(60);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("AI scoring job failed permanently for evaluation {$this->evaluation->id}: {$exception->getMessage()}");
        
        $this->evaluation->update([
            'ai_score' => null,
            'ai_error' => $exception->getMessage(),
        ]);
    }
}