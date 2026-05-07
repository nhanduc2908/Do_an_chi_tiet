<?php

namespace Tests\Feature\Sync;

use Tests\TestCase;
use App\Models\User;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IncrementalSyncTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_incremental_sync_only_gets_new_changes()
    {
        // Old evaluations (should not be synced)
        Evaluation::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'updated_at' => now()->subDays(2),
        ]);
        
        // New evaluations (should be synced)
        Evaluation::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'updated_at' => now(),
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/incremental', [
                             'device_id' => 'TEST_DEVICE',
                             'last_sync' => now()->subDay()->toIso8601String(),
                         ]);

        $response->assertStatus(200);
        $this->assertCount(3, $response->json('items'));
    }
}