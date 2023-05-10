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
     * Doner Dashboard user authentication.
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
     * Doner Dashboard user authentication ensure donor.
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
     * get ptb profile cards for donor dashboard.
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
     * get searched ptb profile cards for donor dashboard.
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
     * get searched ptb profile cards for donor dashboard.
     *  min char restriction
     * @return void
     */
    public function testPtbProfileCardsMaxStateSelectForSerach()
    {
        $response = $this->get(PTB_PROFILE_CARD_API.'?'.TEST_MAX_STATE_IDS, $this->headers);
        $response->assertStatus(Response::HTTP_EXPECTATION_FAILED);
        $response->assertJsonStructure([
            MESSAGE
        ]);
    }
}
