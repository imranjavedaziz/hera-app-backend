<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Helpers\AuthHelper;

class LogoutUserTest extends TestCase
{

    /**
     * User logout.
     *
     * @return void
     */
    public function testLogoutUserAuthentication()
    {
        $response = $this->get(LOGOUT_API.TEST_DEVICE_TOKEN, []);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonStructure([
            MESSAGE
        ]);
    }

    /**
     * User logout.
     *
     * @return void
     */
    public function testLogoutUser()
    {
        $response = $this->get(LOGOUT_API.TEST_DEVICE_TOKEN, $this->headers);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            MESSAGE
        ]);
    }
}
