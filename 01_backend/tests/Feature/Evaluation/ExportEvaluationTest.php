<?php

namespace Tests\Feature\Evaluation;

use Tests\TestCase;
use App\Models\User;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExportEvaluationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $evaluation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->evaluation = Evaluation::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_can_export_evaluation_as_pdf()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson("/api/evaluations/{$this->evaluation->id}/export?format=pdf");

        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_user_can_export_evaluation_as_excel()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson("/api/evaluations/{$this->evaluation->id}/export?format=excel");

        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_user_can_export_evaluation_as_csv()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson("/api/evaluations/{$this->evaluation->id}/export?format=csv");

        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'text/csv');
    }
}