<?php

namespace Tests\Feature\Sync;

use Tests\TestCase;
use App\Models\User;
use App\Models\DeviceConnection;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PushDataTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $device;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->device = DeviceConnection::factory()->create([
            'user_id' => $this->user->id,
            'device_id' => 'TEST_DEVICE_001',
        ]);
    }

    public function test_user_can_push_data_to_server()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/push', [
                             'device_id' => 'TEST_DEVICE_001',
                             'items' => [
                                 [
                                     'type' => 'evaluation',
                                     'data' => [
                                         'title' => 'Offline Evaluation',
                                         'domain_id' => 1,
                                         'score' => 85,
                                     ],
                                 ],
                             ],
                             'version_vector' => ['device' => 1, 'server' => 1],
                         ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['synced', 'conflicts']);
    }

    public function test_push_creates_new_records()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/push', [
                             'device_id' => 'TEST_DEVICE_001',
                             'items' => [
                                 [
                                     'type' => 'evaluation',
                                     'data' => [
                                         'title' => 'New Offline Evaluation',
                                         'domain_id' => 1,
                                     ],
                                 ],
                             ],
                         ]);

        $response->assertStatus(200);
        $this->assertEquals(1, $response->json('synced'));
        $this->assertDatabaseHas('evaluations', [
            'title' => 'New Offline Evaluation',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_push_updates_existing_records()
    {
        $existing = Evaluation::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Original Title',
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/push', [
                             'device_id' => 'TEST_DEVICE_001',
                             'items' => [
                                 [
                                     'type' => 'evaluation',
                                     'data' => [
                                         'id' => $existing->id,
                                         'title' => 'Updated Title',
                                         'updated_at' => now()->toIso8601String(),
                                     ],
                                 ],
                             ],
                         ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('evaluations', [
            'id' => $existing->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_push_detects_conflicts()
    {
        $existing = Evaluation::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Server Version',
            'updated_at' => now(),
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/push', [
                             'device_id' => 'TEST_DEVICE_001',
                             'items' => [
                                 [
                                     'type' => 'evaluation',
                                     'data' => [
                                         'id' => $existing->id,
                                         'title' => 'Client Version',
                                         'updated_at' => now()->addSecond()->toIso8601String(),
                                     ],
                                 ],
                             ],
                         ]);

        $response->assertStatus(200);
        $this->assertGreaterThan(0, count($response->json('conflicts')));
    }

    public function test_push_updates_device_last_sync()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson('/api/sync/push', [
                 'device_id' => 'TEST_DEVICE_001',
                 'items' => [],
             ]);

        $this->device->refresh();
        $this->assertNotNull($this->device->last_sync_at);
    }
}