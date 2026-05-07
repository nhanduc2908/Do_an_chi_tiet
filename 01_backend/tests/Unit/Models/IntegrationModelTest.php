<?php

// Đường dẫn: C:\xampp\htdocs\security_evaluation_system\01_backend\tests\Unit\Models\IntegrationModelTest.php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Integration;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IntegrationModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function integration_has_configuration_cast(): void
    {
        $integration = Integration::factory()->create([
            'config' => ['api_key' => 'abc123', 'endpoint' => 'https://api.example.com']
        ]);

        $this->assertIsArray($integration->config);
        $this->assertEquals('abc123', $integration->config['api_key']);
    }

    /** @test */
    public function integration_can_be_enabled(): void
    {
        $integration = Integration::factory()->create(['status' => 'disabled']);
        $integration->update(['status' => 'enabled']);

        $this->assertEquals('enabled', $integration->fresh()->status);
    }
}