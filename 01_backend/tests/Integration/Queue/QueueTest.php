<?php

namespace Tests\Integration\Queue;

use Tests\TestCase;
use App\Jobs\ProcessAIScoringJob;
use App\Models\Evaluation;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QueueTest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_scoring_job_is_dispatched()
    {
        Queue::fake();
        
        $evaluation = Evaluation::factory()->create();
        
        ProcessAIScoringJob::dispatch($evaluation);
        
        Queue::assertPushed(ProcessAIScoringJob::class, function ($job) use ($evaluation) {
            return $job->evaluation->id === $evaluation->id;
        });
    }

    public function test_ai_scoring_job_processes_correctly()
    {
        $evaluation = Evaluation::factory()->create();
        $job = new ProcessAIScoringJob($evaluation);
        
        $job->handle();
        
        $evaluation->refresh();
        $this->assertNotNull($evaluation->ai_score);
    }
}