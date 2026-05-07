<?php

namespace Tests\Feature\Evaluation;

use Tests\TestCase;
use App\Models\User;
use App\Models\Domain;
use App\Models\Criteria;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BatchSaveTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $evaluation;
    protected $criteria1;
    protected $criteria2;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $domain = Domain::factory()->create();
        
        $this->evaluation = Evaluation::factory()->create([
            'user_id' => $this->user->id,
            'domain_id' => $domain->id,
        ]);
        
        $this->criteria1 = Criteria::factory()->create(['domain_id' => $domain->id]);
        $this->criteria2 = Criteria::factory()->create(['domain_id' => $domain->id]);
        
        $this->evaluation->details()->create([
            'criteria_id' => $this->criteria1->id,
            'score' => 0,
        ]);
        
        $this->evaluation->details()->create([
            'criteria_id' => $this->criteria2->id,
            'score' => 0,
        ]);
    }

    public function test_user_can_batch_save_scores()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/evaluations/batch-save", [
                             'evaluation_id' => $this->evaluation->id,
                             'items' => [
                                 [
                                     'criteria_id' => $this->criteria1->id,
                                     'score' => 8,
                                     'notes' => 'Good',
                                 ],
                                 [
                                     'criteria_id' => $this->criteria2->id,
                                     'score' => 7,
                                     'notes' => 'Average',
                                 ],
                             ],
                         ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('evaluation_details', [
            'evaluation_id' => $this->evaluation->id,
            'criteria_id' => $this->criteria1->id,
            'score' => 8,
        ]);
        
        $this->assertDatabaseHas('evaluation_details', [
            'evaluation_id' => $this->evaluation->id,
            'criteria_id' => $this->criteria2->id,
            'score' => 7,
        ]);
    }

    public function test_batch_save_updates_total_score()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson("/api/evaluations/batch-save", [
                 'evaluation_id' => $this->evaluation->id,
                 'items' => [
                     ['criteria_id' => $this->criteria1->id, 'score' => 10],
                     ['criteria_id' => $this->criteria2->id, 'score' => 10],
                 ],
             ]);

        $this->evaluation->refresh();
        $this->assertEquals(100, $this->evaluation->percentage);
    }
}