<?php

namespace Tests\Integration\Database;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RawQueriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_raw_select_query()
    {
        User::factory()->create(['name' => 'Test User']);
        
        $result = DB::select('SELECT * FROM users WHERE name = ?', ['Test User']);
        
        $this->assertCount(1, $result);
        $this->assertEquals('Test User', $result[0]->name);
    }

    public function test_raw_insert_query()
    {
        DB::insert('INSERT INTO users (name, email, password) VALUES (?, ?, ?)', [
            'New User', 'new@example.com', bcrypt('password')
        ]);
        
        $this->assertDatabaseHas('users', ['email' => 'new@example.com']);
    }

    public function test_raw_update_query()
    {
        $user = User::factory()->create(['name' => 'Old Name']);
        
        DB::update('UPDATE users SET name = ? WHERE id = ?', ['New Name', $user->id]);
        
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'New Name']);
    }

    public function test_raw_delete_query()
    {
        $user = User::factory()->create();
        
        DB::delete('DELETE FROM users WHERE id = ?', [$user->id]);
        
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}