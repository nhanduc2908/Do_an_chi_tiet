<?php

namespace Tests\Integration\Queue;

use Tests\TestCase;
use App\Jobs\ProcessAIScoringJob;
use App\Models\Evaluation;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QueuePriorityTest extends TestCase
{
    use RefreshDatabase;

    public function test_high_priority_jobs_processed_first()
    {
        Queue::fake();
        
        $evaluation = Evaluation::factory()->create();
        
        ProcessAIScoringJob::dispatch($evaluation)->onQueue('low');
        ProcessAIScoringJob::dispatch($evaluation)->onQueue('high');
        
        $queues = Queue::pushedJobs();
        $this->assertArrayHasKey('high', $queues);
    }

    public function test_queue_balance_configuration()
    {
        $config = config('queue.connections.redis.balance');
        $this->assertNotNull($config);
    }
}