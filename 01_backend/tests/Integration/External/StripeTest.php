<?php

namespace Tests\Integration\External;

use Tests\TestCase;
use Stripe\Stripe;
use Stripe\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StripeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function test_stripe_connection()
    {
        $this->assertNotNull(Stripe::getApiKey());
    }

    public function test_stripe_customer_creation()
    {
        $customer = Customer::create([
            'email' => 'test@example.com',
            'name' => 'Test Customer',
        ]);
        
        $this->assertNotNull($customer->id);
    }
}