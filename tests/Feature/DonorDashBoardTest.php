<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Helpers\AuthHelper;

class DonorDashBoardTest extends TestCase
{

    /**
     * Set Attributes Registration required field.
     *
     * @return void
     */
    public function testUserAuthentication()
    {
        $response = $this->get(PTB_PROFILE_CARD_API, []);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonStructure([
            MESSAGE
        ]);
    }

    /**
     * Set Attributes Registration required field.
     *
     * @return void
     */
    public function testEnsureDonorAuthentication()
    {
        $headers = [
            ACCEPT => APPLICATION_JSON,
            AUTHORIZATION => BEARER. AuthHelper::authenticateTestUser(TESTING_PTB_ID),
        ];
        $response = $this->get(PTB_PROFILE_CARD_API, $headers);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonStructure([
            MESSAGE
        ]);
    }

    /**
     * Set Attributes Registration required field.
     *
     * @return void
     */
    public function testPtbProfileCards()
    {
        $response = $this->get(PTB_PROFILE_CARD_API, $this->headers);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            DATA,
            MESSAGE
        ]);
    }

    /**
     * Set Attributes Registration required field.
     *
     * @return void
     */
    public function testPtbProfileCardsSearch()
    {
        $response = $this->get(PTB_PROFILE_CARD_API.'?'.TEST_KEYWORD, $this->headers);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            DATA,
            MESSAGE
        ]);
    }

    /**
     * Set Attributes Registration required field.
     *
     * @return void
     */
    public function testPtbProfileCardsMinCharSearch()
    {
        $response = $this->get(PTB_PROFILE_CARD_API.'?'.TEST_MIN_CHAR_KEYWORD, $this->headers);
        $response->assertStatus(Response::HTTP_EXPECTATION_FAILED);
        $response->assertJsonStructure([
            MESSAGE
        ]);
    }
}
