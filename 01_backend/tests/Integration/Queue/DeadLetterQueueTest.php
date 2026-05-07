<?php

namespace Tests\Integration\Queue;

use Tests\TestCase;
use App\Models\SyncQueue;
use App\Jobs\ProcessSyncQueueJob;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeadLetterQueueTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
    }

    public function test_failed_jobs_moved_to_dead_letter()
    {
        // Create a job that will fail
        $queue = SyncQueue::create([
            'type' => 'test',
            'data' => ['test' => true, 'will_fail' => true],
            'status' => 'processing',
            'attempts' => 0,
        ]);

        // Simulate processing failure
        try {
            $job = new ProcessSyncQueueJob($queue);
            $job->handle();
        } catch (\Exception $e) {
            // Job failed
        }

        // Manually mark as failed after max attempts
        $queue->update([
            'status' => 'failed',
            'attempts' => 5,
            'error_message' => 'Max retries exceeded',
        ]);

        // Process dead letter queue
        \Artisan::call('queue:dead-letter-process');

        $queue->refresh();
        $this->assertEquals('dead_letter', $queue->status);
    }

    public function test_dead_letter_queue_stores_error_details()
    {
        $errorMessage = 'Connection timeout';
        
        $queue = SyncQueue::create([
            'type' => 'test',
            'data' => ['test' => true],
            'status' => 'failed',
            'attempts' => 5,
            'error_message' => $errorMessage,
        ]);

        \Artisan::call('queue:dead-letter-process');

        $queue->refresh();
        $this->assertEquals('dead_letter', $queue->status);
        $this->assertEquals($errorMessage, $queue->error_message);
    }

    public function test_dead_letter_queue_cleanup()
    {
        // Create old dead letter entries
        SyncQueue::create([
            'type' => 'test',
            'data' => ['test' => true],
            'status' => 'dead_letter',
            'created_at' => now()->subDays(31),
        ]);

        SyncQueue::create([
            'type' => 'test',
            'data' => ['test' => true],
            'status' => 'dead_letter',
            'created_at' => now()->subDays(15),
        ]);

        // Run cleanup command (keep 30 days)
        \Artisan::call('queue:cleanup-dead-letter');

        $this->assertDatabaseCount('sync_queues', 1);
        $this->assertDatabaseHas('sync_queues', [
            'created_at' => now()->subDays(15),
        ]);
    }

    public function test_dead_letter_queue_can_be_retried()
    {
        $queue = SyncQueue::create([
            'type' => 'test',
            'data' => ['test' => true],
            'status' => 'dead_letter',
            'attempts' => 5,
        ]);

        // Retry from dead letter
        \Artisan::call('queue:retry-dead-letter', ['--id' => $queue->id]);

        $queue->refresh();
        $this->assertEquals('pending', $queue->status);
        $this->assertEquals(0, $queue->attempts);
    }

    public function test_dead_letter_queue_notification()
    {
        $queue = SyncQueue::create([
            'type' => 'test',
            'data' => ['test' => true],
            'status' => 'dead_letter',
            'attempts' => 5,
        ]);

        // Run command that sends notification
        \Artisan::call('queue:notify-dead-letter');

        $this->assertDatabaseHas('notifications', [
            'type' => 'dead_letter_alert',
            'content' => 'contains 1 items',
        ]);
    }

    public function test_dead_letter_queue_max_size()
    {
        // Create 100 dead letter entries
        for ($i = 0; $i < 100; $i++) {
            SyncQueue::create([
                'type' => 'test',
                'data' => ['test' => true, 'index' => $i],
                'status' => 'dead_letter',
            ]);
        }

        // Run cleanup that limits to 50
        \Artisan::call('queue:limit-dead-letter', ['--max' => 50]);

        $this->assertDatabaseCount('sync_queues', 50);
    }

    public function test_dead_letter_queue_export()
    {
        SyncQueue::create([
            'type' => 'test',
            'data' => ['test' => true],
            'status' => 'dead_letter',
            'error_message' => 'Test error',
        ]);

        $exportFile = storage_path('logs/dead_letter_export.json');
        
        \Artisan::call('queue:export-dead-letter', ['--file' => $exportFile]);

        $this->assertFileExists($exportFile);
        
        $content = json_decode(file_get_contents($exportFile), true);
        $this->assertIsArray($content);
        
        unlink($exportFile);
    }

    public function test_dead_letter_queue_web_view()
    {
        $queue = SyncQueue::create([
            'type' => 'test',
            'data' => ['test' => true],
            'status' => 'dead_letter',
            'error_message' => 'Test error',
        ]);

        $admin = \App\Models\User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/queue/dead-letter');

        $response->assertStatus(200)
                 ->assertJsonCount(1)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'type', 'data', 'error_message', 'created_at']
                     ]
                 ]);
    }

    public function test_dead_letter_queue_clear_all()
    {
        SyncQueue::create([
            'type' => 'test',
            'data' => ['test' => true],
            'status' => 'dead_letter',
        ]);

        SyncQueue::create([
            'type' => 'test2',
            'data' => ['test' => true],
            'status' => 'dead_letter',
        ]);

        \Artisan::call('queue:clear-dead-letter');

        $this->assertDatabaseCount('sync_queues', 0);
    }

    public function test_dead_letter_queue_stats()
    {
        SyncQueue::create([
            'type' => 'test',
            'data' => ['test' => true],
            'status' => 'dead_letter',
            'error_message' => 'Error type A',
        ]);

        SyncQueue::create([
            'type' => 'test',
            'data' => ['test' => true],
            'status' => 'dead_letter',
            'error_message' => 'Error type A',
        ]);

        SyncQueue::create([
            'type' => 'test2',
            'data' => ['test' => true],
            'status' => 'dead_letter',
            'error_message' => 'Error type B',
        ]);

        $stats = \Artisan::call('queue:dead-letter-stats');
        
        $output = \Artisan::output();
        $this->assertStringContainsString('Total: 3', $output);
        $this->assertStringContainsString('Error type A: 2', $output);
        $this->assertStringContainsString('Error type B: 1', $output);
    }

    public function test_dead_letter_auto_move_after_max_retries()
    {
        $queue = SyncQueue::create([
            'type' => 'test',
            'data' => ['test' => true],
            'status' => 'failed',
            'attempts' => 5, // Max retries reached
        ]);

        // Run job that checks for max retries
        \Artisan::call('queue:check-max-retries');

        $queue->refresh();
        $this->assertEquals('dead_letter', $queue->status);
    }
}   