<?php

namespace Tests\Feature\Scanner;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NetworkScanTest extends TestCase
{
    use RefreshDatabase;

    public function test_secops_can_run_network_scan()
    {
        $secops = User::factory()->create(['role' => 'secops']);
        $token = $secops->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/network', [
                             'target' => '192.168.1.0/24',
                             'ports' => '22,80,443',
                         ]);
        
        $response->assertStatus(200)
                 ->assertJsonStructure(['scan_id', 'open_ports']);
    }

    public function test_network_scan_detects_open_ports()
    {
        $secops = User::factory()->create(['role' => 'secops']);
        $token = $secops->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/network', [
                             'target' => 'scanme.nmap.org',
                         ]);
        
        $response->assertStatus(200);
        $response->assertJsonStructure(['open_ports', 'closed_ports', 'filtered_ports']);
    }

    public function test_invalid_network_target_rejected()
    {
        $secops = User::factory()->create(['role' => 'secops']);
        $token = $secops->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/network', [
                             'target' => 'invalid-target',
                         ]);
        
        $response->assertStatus(422);
    }
}