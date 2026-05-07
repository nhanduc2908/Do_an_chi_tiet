<?php

namespace Tests\Feature\Scanner;

use Tests\TestCase;
use App\Models\User;
use App\Models\ScanResult;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScanResultTest extends TestCase
{
    use RefreshDatabase;

    public function test_view_scan_results()
    {
        $dev = User::factory()->create(['role' => 'dev']);
        $scan = ScanResult::factory()->create(['user_id' => $dev->id]);
        
        $token = $dev->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/scanner/results');
        
        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }

    public function test_view_single_scan_result()
    {
        $dev = User::factory()->create(['role' => 'dev']);
        $scan = ScanResult::factory()->create(['user_id' => $dev->id]);
        
        $token = $dev->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson("/api/scanner/results/{$scan->id}");
        
        $response->assertStatus(200)
                 ->assertJson(['id' => $scan->id]);
    }

    public function test_download_scan_report()
    {
        $dev = User::factory()->create(['role' => 'dev']);
        $scan = ScanResult::factory()->create(['user_id' => $dev->id]);
        
        $token = $dev->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson("/api/scanner/results/{$scan->id}/report");
        
        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_delete_scan_result()
    {
        $dev = User::factory()->create(['role' => 'dev']);
        $scan = ScanResult::factory()->create(['user_id' => $dev->id]);
        
        $token = $dev->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->deleteJson("/api/scanner/results/{$scan->id}");
        
        $response->assertStatus(200);
        $this->assertDatabaseMissing('scan_results', ['id' => $scan->id]);
    }
}