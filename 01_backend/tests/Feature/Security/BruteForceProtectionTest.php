<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BruteForceProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_locks_after_multiple_failed_attempts()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('correctpassword'),
        ]);
        
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/login', [
                'email' => 'test@example.com',
                'password' => 'wrongpassword',
            ]);
        }
        
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'correctpassword',
        ]);
        
        $response->assertStatus(403)
                 ->assertJson(['message' => 'Account locked']);
    }

    public function test_lockout_resets_after_time()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('correctpassword'),
        ]);
        
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/login', [
                'email' => 'test@example.com',
                'password' => 'wrongpassword',
            ]);
        }
        
        $this->travel(16)->minutes();
        
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'correctpassword',
        ]);
        
        $response->assertStatus(200);
    }
}