<?php

namespace Tests\Integration\Database;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConstraintsTest extends TestCase
{
    use RefreshDatabase;

    public function test_unique_email_constraint()
    {
        User::factory()->create(['email' => 'unique@example.com']);
        
        $this->expectException(\Exception::class);
        User::factory()->create(['email' => 'unique@example.com']);
    }

    public function test_not_null_constraints()
    {
        $this->expectException(\Exception::class);
        User::factory()->create(['name' => null]);
    }

    public function test_foreign_key_constraint()
    {
        $this->expectException(\Exception::class);
        DB::table('evaluations')->insert([
            'title' => 'Test',
            'user_id' => 99999,
        ]);
    }

    public function test_check_constraint_on_status()
    {
        $this->expectException(\Exception::class);
        DB::table('evaluations')->insert([
            'title' => 'Test',
            'user_id' => 1,
            'status' => 'invalid_status',
        ]);
    }
}