<?php

namespace Tests\Integration\Queue;

use Tests\TestCase;
use App\Models\FailedJob;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FailedJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_failed_jobs_are_recorded()
    {
        Queue::fake();
        
        // Simulate a failing job
        try {
            dispatch(function () {
                throw new \Exception('Job failed');
            });
        } catch (\Exception $e) {
            // Job failed
        }
        
        $this->assertGreaterThan(0, FailedJob::count());
    }

    public function test_retry_failed_job()
    {
        $failedJob = FailedJob::create([
            'uuid' => 'test-uuid',
            'connection' => 'redis',
            'queue' => 'default',
            'payload' => '{}',
            'exception' => 'Test exception',
            'failed_at' => now(),
        ]);
        
        $this->artisan('queue:retry', ['id' => $failedJob->uuid]);
        
        $this->assertDatabaseMissing('failed_jobs', ['uuid' => $failedJob->uuid]);
    }
}