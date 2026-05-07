<?php

namespace Tests\Integration\External;

use Tests\TestCase;
use Twilio\Rest\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TwilioTest extends TestCase
{
    protected $twilio;

    protected function setUp(): void
    {
        parent::setUp();
        $this->twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    public function test_twilio_connection()
    {
        $this->assertNotNull($this->twilio);
    }

    public function test_twilio_sms_sending()
    {
        $message = $this->twilio->messages->create(
            '+84123456789',
            [
                'from' => config('services.twilio.from'),
                'body' => 'Test SMS from Security System',
            ]
        );
        
        $this->assertNotNull($message->sid);
    }
}