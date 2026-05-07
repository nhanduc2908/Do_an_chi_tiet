<?php

namespace Tests\Integration\Queue;

use Tests\TestCase;
use App\Jobs\ProcessAIScoringJob;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QueueRetryTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_retries_on_failure()
    {
        $evaluation = Evaluation::factory()->create();
        $job = new ProcessAIScoringJob($evaluation);
        
        $attempts = 0;
        try {
            $job->handle();
        } catch (\Exception $e) {
            $attempts = $job->attempts();
        }
        
        $this->assertEquals(1, $attempts);
    }

    public function test_max_retries_configuration()
    {
        $maxRetries = config('queue.connections.redis.retry_after');
        $this->assertIsInt($maxRetries);
    }
}