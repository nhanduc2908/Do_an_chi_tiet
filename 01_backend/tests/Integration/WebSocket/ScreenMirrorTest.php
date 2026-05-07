<?php

namespace Tests\Integration\WebSocket;

use Tests\TestCase;
use App\Models\User;
use App\Events\ScreenMirroringStarted;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScreenMirrorTest extends TestCase
{
    use RefreshDatabase;

    public function test_screen_mirroring_started_event()
    {
        Event::fake();
        
        $user = User::factory()->create();
        
        event(new ScreenMirroringStarted($user, 'source_device', 'target_device', [
            'width' => 1920,
            'height' => 1080,
            'orientation' => 'landscape',
        ]));
        
        Event::assertDispatched(ScreenMirroringStarted::class);
    }

    public function test_screen_mirroring_data_transfer()
    {
        $user = User::factory()->create();
        $screenData = ['width' => 1920, 'height' => 1080];
        
        $response = $this->actingAs($user)
                         ->postJson('/api/device/screen/mirror', [
                             'target_device' => 'device_002',
                             'screen_data' => $screenData,
                         ]);
        
        $response->assertStatus(200);
    }

    public function test_screen_mirroring_stop()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->postJson('/api/device/screen/stop', [
                             'target_device' => 'device_002',
                         ]);
        
        $response->assertStatus(200);
    }
}