<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\Sync\QueueManager;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QueueManagerTest extends TestCase
{
    use RefreshDatabase;

    protected QueueManager $queueManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->queueManager = new QueueManager();
    }

    public function test_push_to_queue()
    {
        $queue = $this->queueManager->push('test', ['data' => 'test']);
        
        $this->assertDatabaseHas('sync_queues', [
            'type' => 'test',
            'status' => 'pending',
        ]);
    }
}