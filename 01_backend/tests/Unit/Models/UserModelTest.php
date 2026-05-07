<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_role()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->assertTrue($user->isAdmin());
        $this->assertTrue($user->hasRole('admin'));
    }

    public function test_user_belongs_to_company()
    {
        $company = \App\Models\Company::factory()->create();
        $user = User::factory()->create(['company_id' => $company->id]);
        
        $this->assertEquals($company->id, $user->company->id);
    }

    public function test_user_has_many_evaluations()
    {
        $user = User::factory()->create();
        \App\Models\Evaluation::factory()->count(3)->create(['user_id' => $user->id]);
        
        $this->assertEquals(3, $user->evaluations->count());
    }
}