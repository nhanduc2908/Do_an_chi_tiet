<?php

namespace Tests\Feature\Sync;

use Tests\TestCase;
use App\Models\User;
use App\Models\DeviceConnection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VersionVectorTest extends TestCase
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

    public function test_version_vector_is_returned_on_pull()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/pull', [
                             'device_id' => 'TEST_DEVICE_001',
                         ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('version_vector', $response->json());
    }

    public function test_version_vector_increments_after_update()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $firstResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                               ->postJson('/api/sync/pull', [
                                   'device_id' => 'TEST_DEVICE_001',
                               ]);
        
        $firstVersion = $firstResponse->json('version_vector');
        
        // Create an update
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson('/api/sync/push', [
                 'device_id' => 'TEST_DEVICE_001',
                 'items' => [['type' => 'test', 'data' => ['key' => 'value']]],
             ]);
        
        $secondResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                                ->postJson('/api/sync/pull', [
                                    'device_id' => 'TEST_DEVICE_001',
                                ]);
        
        $secondVersion = $secondResponse->json('version_vector');
        
        $this->assertNotEquals($firstVersion['server'], $secondVersion['server']);
    }
}