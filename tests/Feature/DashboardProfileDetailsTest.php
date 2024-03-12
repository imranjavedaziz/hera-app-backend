<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Helpers\AuthHelper;

class DashboardProfileDetailsTest extends TestCase
{

    /**
     * Dashboard profile details user authentication.
     *
     * @return void
     */
    public function testUserAuthentication()
    {
        $response = $this->get(DONOR_PROFILE_DETAILS_API, []);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonStructure([
            MESSAGE
        ]);
    }

    /**
     * Dashboard profile details user authentication.
     * ensure must be ptb for doner card details
     * @return void
     */
    public function testEnsurePtbAuthentication()
    {
        $response = $this->get(DONOR_PROFILE_DETAILS_API, $this->headers);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonStructure([
            MESSAGE
        ]);
    }

    /**
     * Dashboard profile details user authentication.
     * ensure must be donor for ptb card details
     * @return void
     */
    public function testEnsureDonorAuthentication()
    {
        $headers = [
            ACCEPT => APPLICATION_JSON,
            AUTHORIZATION => BEARER. AuthHelper::authenticateTestUser(TESTING_PTB_ID),
        ];
        $response = $this->get(PTB_PROFILE_DETAILS_API, $headers);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonStructure([
            MESSAGE
        ]);
    }

    /**
     * PTB Dashboard profile details RequiredParam.
     *
     * @return void
     */
    public function testRequiredParamDonerProfileDetails()
    {
        $ptb_user = AuthHelper::factoryUserCreate(PARENTS_TO_BE, SUBSCRIPTION_ENABLED);
        $doner_user = AuthHelper::factoryUserCreate(SURROGATE_MOTHER, SUBSCRIPTION_DISABLED);
        $headers = [
            ACCEPT => APPLICATION_JSON,
            AUTHORIZATION => BEARER. AuthHelper::authenticateTestUser($ptb_user->id),
        ];
        $response = $this->get(DONOR_PROFILE_DETAILS_API, $headers);
        $response->assertStatus(Response::HTTP_EXPECTATION_FAILED);
        $response->assertJsonStructure([
            DATA,
            MESSAGE
        ]);
    }

    /**
     * PTB Dashboard profile details.
     *
     * @return void
     */
    public function testSubscriptionStatusPtbDonerProfileDetails()
    {
        $ptb_user = AuthHelper::factoryUserCreate(PARENTS_TO_BE, SUBSCRIPTION_DISABLED);
        $doner_user = AuthHelper::factoryUserCreate(SURROGATE_MOTHER, SUBSCRIPTION_DISABLED);
        $headers = [
            ACCEPT => APPLICATION_JSON,
            AUTHORIZATION => BEARER. AuthHelper::authenticateTestUser($ptb_user->id),
        ];
        $response = $this->get(DONOR_PROFILE_DETAILS_API.$doner_user->id, $headers);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            DATA,
            MESSAGE
        ]);
    }

    /**
     * PTB Dashboard profile details.
     *
     * @return void
     */
    public function testDonerProfileDetails()
    {
        $ptb_user = AuthHelper::factoryUserCreate(PARENTS_TO_BE, SUBSCRIPTION_ENABLED);
        $doner_user = AuthHelper::factoryUserCreate(SURROGATE_MOTHER, SUBSCRIPTION_DISABLED);
        $headers = [
            ACCEPT => APPLICATION_JSON,
            AUTHORIZATION => BEARER. AuthHelper::authenticateTestUser($ptb_user->id),
        ];
        $response = $this->get(DONOR_PROFILE_DETAILS_API.$doner_user->id, $headers);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            DATA,
            MESSAGE
        ]);
    }

    /**
     * DONOR Dashboard profile details.
     *
     * @return void
     */
    public function testPtbProfileDetails()
    {
        $ptb_user = AuthHelper::factoryUserCreate(PARENTS_TO_BE, SUBSCRIPTION_ENABLED);
        $doner_user = AuthHelper::factoryUserCreate(SURROGATE_MOTHER, SUBSCRIPTION_DISABLED);
        $headers = [
            ACCEPT => APPLICATION_JSON,
            AUTHORIZATION => BEARER. AuthHelper::authenticateTestUser($doner_user->id),
        ];
        $response = $this->get(PTB_PROFILE_DETAILS_API.$ptb_user->id, $headers);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            DATA,
            MESSAGE
        ]);
    }
}
