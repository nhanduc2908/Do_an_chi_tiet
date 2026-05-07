<?php

namespace Tests\Feature\Sync;

use Tests\TestCase;
use App\Models\User;
use App\Models\DeviceConnection;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConflictResolutionTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $device;
    protected $evaluation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->device = DeviceConnection::factory()->create([
            'user_id' => $this->user->id,
            'device_id' => 'TEST_DEVICE_001',
        ]);
        $this->evaluation = Evaluation::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Original',
        ]);
    }

    public function test_resolve_conflict_with_server_win()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/resolve', [
                             'conflict_id' => 'conflict_123',
                             'resolution' => 'server',
                             'data' => [
                                 'evaluation_id' => $this->evaluation->id,
                                 'title' => 'Server Version',
                             ],
                         ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('evaluations', [
            'id' => $this->evaluation->id,
            'title' => 'Server Version',
        ]);
    }

    public function test_resolve_conflict_with_client_win()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/resolve', [
                             'conflict_id' => 'conflict_123',
                             'resolution' => 'client',
                             'data' => [
                                 'evaluation_id' => $this->evaluation->id,
                                 'title' => 'Client Version',
                             ],
                         ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('evaluations', [
            'id' => $this->evaluation->id,
            'title' => 'Client Version',
        ]);
    }

    public function test_resolve_conflict_with_manual_merge()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/resolve', [
                             'conflict_id' => 'conflict_123',
                             'resolution' => 'manual',
                             'data' => [
                                 'evaluation_id' => $this->evaluation->id,
                                 'title' => 'Merged Version',
                                 'notes' => 'Combined from both versions',
                             ],
                         ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('evaluations', [
            'id' => $this->evaluation->id,
            'title' => 'Merged Version',
        ]);
    }
}