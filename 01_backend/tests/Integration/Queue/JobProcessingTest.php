<?php

namespace Tests\Integration\Queue;

use Tests\TestCase;
use App\Jobs\ProcessAIScoringJob;
use App\Models\Evaluation;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JobProcessingTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_processes_successfully()
    {
        $evaluation = Evaluation::factory()->create();
        $job = new ProcessAIScoringJob($evaluation);
        
        $job->handle();
        
        $evaluation->refresh();
        $this->assertNotNull($evaluation->ai_score);
    }

    public function test_job_handles_exceptions_gracefully()
    {
        $this->expectException(\Exception::class);
        
        $evaluation = Evaluation::factory()->create();
        $job = new ProcessAIScoringJob($evaluation);
        
        // Simulate failure
        config(['ai.api_url' => 'http://invalid-url']);
        $job->handle();
    }

    public function test_job_releases_on_failure()
    {
        $job = new ProcessAIScoringJob(Evaluation::factory()->create());
        
        try {
            $job->handle();
        } catch (\Exception $e) {
            $this->assertEquals(1, $job->attempts());
        }
    }
}