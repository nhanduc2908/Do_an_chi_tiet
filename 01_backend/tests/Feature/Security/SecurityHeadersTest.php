<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_headers_are_present()
    {
        $response = $this->get('/');
        
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_csp_header_is_set()
    {
        $response = $this->get('/');
        $response->assertHeader('Content-Security-Policy');
    }

    public function test_hsts_header_in_production()
    {
        config(['app.env' => 'production']);
        
        $response = $this->get('/');
        $response->assertHeader('Strict-Transport-Security');
    }
}