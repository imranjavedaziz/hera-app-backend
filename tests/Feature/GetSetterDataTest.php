<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Helpers\AuthHelper;

class GetSetterDataTest extends TestCase
{
    /**
     * Get States.
     *
     * @return void
     */
    public function testGetStates()
    {
        $response = $this->get(GET_STATES_API);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            MESSAGE
        ]);
    }

    /**
     * GET_PROFILE_SETTER_DATA_API.
     *
     * @return void
     */
    public function testGetProfileSetterData()
    {
        $headers = [
            ACCEPT => APPLICATION_JSON,
            AUTHORIZATION => BEARER. AuthHelper::authenticateTestUser(TESTING_PTB_ID),
        ];
        $response = $this->get(GET_PROFILE_SETTER_DATA_API, $headers);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            MESSAGE
        ]);
    }

    /**
     * GET_PREFERENCES_SETTER_DATA_API.
     *
     * @return void
     */
    public function testGetPreferencesSetterData()
    {
        $headers = [
            ACCEPT => APPLICATION_JSON,
            AUTHORIZATION => BEARER. AuthHelper::authenticateTestUser(TESTING_PTB_ID),
        ];
        $response = $this->get(GET_PREFERENCES_SETTER_DATA_API, $headers);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            MESSAGE
        ]);
    }

    /**
     * GET_ATTRIBUTES_SETTER_DATA_API.
     *
     * @return void
     */
    public function testGetAttributeSetterData()
    {
        $response = $this->get(GET_ATTRIBUTES_SETTER_DATA_API, $this->headers);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            MESSAGE
        ]);
    }
}
