<?php

namespace Tests\Integration\External;

use Tests\TestCase;
use Kreait\Firebase\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FirebaseTest extends TestCase
{
    protected $firebase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->firebase = (new Factory)
            ->withServiceAccount(config('firebase.credentials'));
    }

    public function test_firebase_connection()
    {
        $auth = $this->firebase->createAuth();
        $this->assertNotNull($auth);
    }

    public function test_firebase_auth_works()
    {
        $auth = $this->firebase->createAuth();
        $users = $auth->listUsers();
        
        $this->assertNotNull($users);
    }
}