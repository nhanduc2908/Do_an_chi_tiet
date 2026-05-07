<?php

namespace Tests\Feature\Sync;

use Tests\TestCase;
use App\Models\User;
use App\Models\DeviceConnection;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PullDataTest extends TestCase
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

    public function test_user_can_pull_data_from_server()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/pull', [
                             'device_id' => 'TEST_DEVICE_001',
                             'last_sync' => now()->subDay()->toIso8601String(),
                             'limit' => 50,
                         ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'items',
                     'version_vector',
                     'has_more',
                 ]);
    }

    public function test_pull_returns_changes_since_last_sync()
    {
        // Create evaluation before last sync
        $oldEvaluation = Evaluation::factory()->create([
            'user_id' => $this->user->id,
            'updated_at' => now()->subDays(2),
        ]);
        
        // Create evaluation after last sync
        $newEvaluation = Evaluation::factory()->create([
            'user_id' => $this->user->id,
            'updated_at' => now(),
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/pull', [
                             'device_id' => 'TEST_DEVICE_001',
                             'last_sync' => now()->subDay()->toIso8601String(),
                         ]);

        $response->assertStatus(200);
        $items = $response->json('items');
        
        // Should only return new evaluation
        $this->assertCount(1, $items);
        $this->assertEquals($newEvaluation->id, $items[0]['id']);
    }

    public function test_pull_respects_limit_parameter()
    {
        Evaluation::factory()->count(10)->create([
            'user_id' => $this->user->id,
            'updated_at' => now(),
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/pull', [
                             'device_id' => 'TEST_DEVICE_001',
                             'limit' => 5,
                         ]);

        $response->assertStatus(200);
        $this->assertCount(5, $response->json('items'));
        $this->assertTrue($response->json('has_more'));
    }

    public function test_pull_returns_version_vector()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/pull', [
                             'device_id' => 'TEST_DEVICE_001',
                         ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('version_vector', $response->json());
    }

    public function test_pull_fails_with_invalid_device_id()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/pull', [
                             'device_id' => 'INVALID_DEVICE',
                         ]);

        $response->assertStatus(404);
    }
}