<?php

namespace Tests\Integration\Database;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexesTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_index_on_users()
    {
        $indexes = Schema::getConnection()->getDoctrineSchemaManager()
            ->listTableIndexes('users');
        
        $this->assertArrayHasKey('users_email_unique', $indexes);
    }

    public function test_composite_index_on_evaluations()
    {
        $indexes = Schema::getConnection()->getDoctrineSchemaManager()
            ->listTableIndexes('evaluations');
        
        $this->assertArrayHasKey('evaluations_status_created_at_index', $indexes);
    }

    public function test_foreign_key_indexes()
    {
        $indexes = Schema::getConnection()->getDoctrineSchemaManager()
            ->listTableIndexes('evaluation_details');
        
        $this->assertArrayHasKey('evaluation_details_evaluation_id_index', $indexes);
        $this->assertArrayHasKey('evaluation_details_criteria_id_index', $indexes);
    }
}