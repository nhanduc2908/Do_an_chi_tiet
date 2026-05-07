<?php

// Đường dẫn: C:\xampp\htdocs\security_evaluation_system\01_backend\tests\Unit\Models\ScannerModelTest.php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Scanner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScannerModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scanner_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $scanner = Scanner::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $scanner->user);
    }

    /** @test */
    public function scanner_has_status(): void
    {
        $scanner = Scanner::factory()->create(['status' => 'running']);
        $this->assertEquals('running', $scanner->status);
    }
}