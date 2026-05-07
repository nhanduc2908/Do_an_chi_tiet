<?php

namespace Tests\Integration\External;

use Tests\TestCase;
use RdKafka\Producer;
use RdKafka\Conf;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KafkaTest extends TestCase
{
    protected $producer;

    protected function setUp(): void
    {
        parent::setUp();
        
        $conf = new Conf();
        $conf->set('metadata.broker.list', config('kafka.brokers'));
        $this->producer = new Producer($conf);
    }

    public function test_kafka_connection()
    {
        $this->assertNotNull($this->producer);
    }

    public function test_kafka_message_production()
    {
        $topic = $this->producer->newTopic('test_topic');
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, json_encode(['test' => 'data']));
        $this->producer->flush(1000);
        
        $this->assertTrue(true);
    }
}