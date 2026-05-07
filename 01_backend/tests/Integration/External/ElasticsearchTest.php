<?php

namespace Tests\Integration\External;

use Tests\TestCase;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ElasticsearchTest extends TestCase
{
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = ClientBuilder::create()
            ->setHosts([config('elasticsearch.host')])
            ->build();
    }

    public function test_elasticsearch_connection()
    {
        $info = $this->client->info();
        $this->assertArrayHasKey('version', $info);
    }

    public function test_elasticsearch_indexing()
    {
        $params = [
            'index' => 'test_index',
            'id' => '1',
            'body' => ['test_field' => 'test_value'],
        ];
        
        $response = $this->client->index($params);
        $this->assertArrayHasKey('_id', $response);
    }

    public function test_elasticsearch_search()
    {
        $params = [
            'index' => 'test_index',
            'body' => [
                'query' => [
                    'match_all' => new \stdClass(),
                ],
            ],
        ];
        
        $response = $this->client->search($params);
        $this->assertArrayHasKey('hits', $response);
    }
}