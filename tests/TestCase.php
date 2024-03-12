<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Helpers\AuthHelper;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $authHeader = [];
    protected $headers = [];

    public function setUp() : void
    {
        parent::setUp();

        $this->authHeader = BEARER . AuthHelper::authenticateTestUser(TESTING_DONOR_ID);
        $this->headers = [
            ACCEPT => APPLICATION_JSON,
            AUTHORIZATION => $this->authHeader,
        ];
    }
}
