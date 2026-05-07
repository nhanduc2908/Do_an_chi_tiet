<?php

namespace Tests\Integration\Queue;

use Tests\TestCase;
use App\Jobs\ProcessAIScoringJob;
use App\Jobs\SendCrossDeviceNotification;
use App\Jobs\SyncDeviceDataJob;
use App\Models\Evaluation;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JobDispatchTest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_scoring_job_dispatched()
    {
        Queue::fake();
        
        $evaluation = Evaluation::factory()->create();
        ProcessAIScoringJob::dispatch($evaluation);
        
        Queue::assertPushed(ProcessAIScoringJob::class, function ($job) use ($evaluation) {
            return $job->evaluation->id === $evaluation->id;
        });
    }

    public function test_notification_job_dispatched()
    {
        Queue::fake();
        
        SendCrossDeviceNotification::dispatch(1, 'from_device', 'to_device', 'Title', 'Body');
        
        Queue::assertPushed(SendCrossDeviceNotification::class);
    }

    public function test_sync_job_dispatched()
    {
        Queue::fake();
        
        SyncDeviceDataJob::dispatch(1, 'device_id', [], []);
        
        Queue::assertPushed(SyncDeviceDataJob::class);
    }
}