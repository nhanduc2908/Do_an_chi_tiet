<?php

namespace Tests\Feature\Sync;

use Tests\TestCase;
use App\Models\User;
use App\Events\SyncCompleted;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RealTimeSyncTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_sync_completed_event_is_dispatched()
    {
        Event::fake();
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson('/api/sync/push', [
                 'device_id' => 'TEST_DEVICE',
                 'items' => [],
             ]);

        Event::assertDispatched(SyncCompleted::class);
    }

    public function test_websocket_notification_on_sync_complete()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/push', [
                             'device_id' => 'TEST_DEVICE',
                             'items' => [['type' => 'test', 'data' => []]],
                         ]);

        $response->assertStatus(200);
        // WebSocket notification would be tested separately
    }
}