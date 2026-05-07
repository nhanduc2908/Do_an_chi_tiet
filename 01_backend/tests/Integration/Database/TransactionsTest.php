<?php

namespace Tests\Integration\Database;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_transaction_rollback_on_error()
    {
        $this->expectException(\Exception::class);
        
        DB::transaction(function () {
            User::factory()->create(['email' => 'test@example.com']);
            User::factory()->create(['email' => 'test@example.com']); // Duplicate
        });
        
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
    }

    public function test_transaction_commit_on_success()
    {
        DB::transaction(function () {
            User::factory()->create(['email' => 'test@example.com']);
        });
        
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_nested_transactions()
    {
        DB::transaction(function () {
            User::factory()->create(['email' => 'outer@example.com']);
            
            DB::transaction(function () {
                User::factory()->create(['email' => 'inner@example.com']);
            });
        });
        
        $this->assertDatabaseHas('users', ['email' => 'outer@example.com']);
        $this->assertDatabaseHas('users', ['email' => 'inner@example.com']);
    }
}