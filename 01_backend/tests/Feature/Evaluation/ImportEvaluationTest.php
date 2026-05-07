<?php

namespace Tests\Feature\Evaluation;

use Tests\TestCase;
use App\Models\User;
use App\Models\Domain;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImportEvaluationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $domain;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->domain = Domain::factory()->create();
    }

    public function test_user_can_import_evaluations_from_csv()
    {
        Storage::fake('local');
        
        $csvContent = "title,score\nSecurity Check,85\nData Protection,90";
        $file = UploadedFile::fake()->createWithContent('evaluations.csv', $csvContent);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/evaluations/import', [
                             'file' => $file,
                             'domain_id' => $this->domain->id,
                         ]);

        $response->assertStatus(200);
    }

    public function test_import_fails_with_invalid_file_format()
    {
        $file = UploadedFile::fake()->create('document.pdf', 100);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/evaluations/import', [
                             'file' => $file,
                             'domain_id' => $this->domain->id,
                         ]);

        $response->assertStatus(422);
    }
}