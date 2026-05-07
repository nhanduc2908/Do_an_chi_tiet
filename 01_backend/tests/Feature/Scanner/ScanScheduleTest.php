<?php

namespace Tests\Feature\Scanner;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScanScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_schedule_daily_scan()
    {
        $secops = User::factory()->create(['role' => 'secops']);
        $token = $secops->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/schedule', [
                             'type' => 'code',
                             'cron' => '0 2 * * *',
                             'target' => 'https://github.com/company/repo',
                             'notify_on_completion' => true,
                         ]);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('scan_schedules', [
            'type' => 'code',
            'cron' => '0 2 * * *',
        ]);
    }

    public function test_list_scheduled_scans()
    {
        $secops = User::factory()->create(['role' => 'secops']);
        $token = $secops->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/scanner/schedule');
        
        $response->assertStatus(200);
    }

    public function test_delete_scheduled_scan()
    {
        $secops = User::factory()->create(['role' => 'secops']);
        $schedule = \App\Models\ScanSchedule::create([
            'type' => 'web',
            'cron' => '0 * * * *',
            'target' => 'https://example.com',
            'created_by' => $secops->id,
        ]);
        
        $token = $secops->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->deleteJson("/api/scanner/schedule/{$schedule->id}");
        
        $response->assertStatus(200);
        $this->assertDatabaseMissing('scan_schedules', ['id' => $schedule->id]);
    }

    public function test_update_scheduled_scan()
    {
        $secops = User::factory()->create(['role' => 'secops']);
        $schedule = \App\Models\ScanSchedule::create([
            'type' => 'web',
            'cron' => '0 * * * *',
            'target' => 'https://example.com',
            'created_by' => $secops->id,
        ]);
        
        $token = $secops->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->putJson("/api/scanner/schedule/{$schedule->id}", [
                             'cron' => '30 2 * * *',
                         ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('scan_schedules', [
            'id' => $schedule->id,
            'cron' => '30 2 * * *',
        ]);
    }
}