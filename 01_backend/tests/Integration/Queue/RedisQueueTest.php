<?php

namespace Tests\Integration\Queue;

use Tests\TestCase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\ProcessAIScoringJob;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RedisQueueTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_is_pushed_to_redis_queue()
    {
        Queue::fake();
        
        $evaluation = Evaluation::factory()->create();
        ProcessAIScoringJob::dispatch($evaluation);
        
        Queue::assertPushed(ProcessAIScoringJob::class);
    }

    public function test_queue_connection_is_redis()
    {
        $this->assertEquals('redis', config('queue.default'));
    }

    public function test_multiple_queues()
    {
        Queue::fake();
        
        $evaluation = Evaluation::factory()->create();
        ProcessAIScoringJob::dispatch($evaluation)->onQueue('ai_scoring');
        
        Queue::assertPushedOn('ai_scoring', ProcessAIScoringJob::class);
    }
}