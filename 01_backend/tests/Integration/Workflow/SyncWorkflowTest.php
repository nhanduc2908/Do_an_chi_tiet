<?php

namespace Tests\Integration\Workflow;

use Tests\TestCase;
use App\Models\User;
use App\Models\DeviceConnection;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SyncWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_sync_workflow()
    {
        $user = User::factory()->create();
        $device = DeviceConnection::factory()->create([
            'user_id' => $user->id,
            'device_id' => 'SYNC_DEVICE',
        ]);
        
        $evaluation = Evaluation::factory()->create([
            'user_id' => $user->id,
            'title' => 'Sync Test',
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        // Push data from device
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson('/api/sync/push', [
                 'device_id' => 'SYNC_DEVICE',
                 'items' => [
                     ['type' => 'evaluation', 'data' => $evaluation->toArray()],
                 ],
             ]);
        
        // Pull data to device
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/pull', [
                             'device_id' => 'SYNC_DEVICE',
                         ]);
        
        $response->assertStatus(200);
        $this->assertIsArray($response->json('items'));
    }
}