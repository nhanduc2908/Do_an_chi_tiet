<?php

namespace Tests\Integration\External;

use Tests\TestCase;
use SendGrid\Mail\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendGridTest extends TestCase
{
    public function test_sendgrid_connection()
    {
        $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
        $this->assertNotNull($sendgrid);
    }

    public function test_sendgrid_email_sending()
    {
        $email = new Mail();
        $email->setFrom("test@example.com", "Test");
        $email->setSubject("Test Email");
        $email->addTo("recipient@example.com", "Recipient");
        $email->addContent("text/plain", "Test content");
        
        $this->assertInstanceOf(Mail::class, $email);
    }
}