<?php

namespace Tests\Feature\Sync;

use Tests\TestCase;
use App\Models\User;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeltaSyncTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_only_changed_fields_are_synced()
    {
        $evaluation = Evaluation::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Original Title',
            'notes' => 'Original Notes',
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        // Push only title change
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/push', [
                             'device_id' => 'TEST_DEVICE',
                             'items' => [
                                 [
                                     'type' => 'evaluation',
                                     'data' => [
                                         'id' => $evaluation->id,
                                         'title' => 'Updated Title',
                                         'updated_at' => now()->toIso8601String(),
                                     ],
                                     'delta' => ['title'],
                                 ],
                             ],
                         ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('evaluations', [
            'id' => $evaluation->id,
            'title' => 'Updated Title',
            'notes' => 'Original Notes', // Notes unchanged
        ]);
    }
}