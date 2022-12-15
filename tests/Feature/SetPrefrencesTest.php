<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Helpers\AuthHelper;

class SetPreferencesTest extends TestCase
{

    /**
     * Set Preference Registration required field.
     *
     * @return void
     */
    public function testUserAuthentication()
    {
        $headers = [
            ACCEPT => APPLICATION_JSON,
            AUTHORIZATION => BEARER. AuthHelper::authenticateTestUser(TESTING_PTB_ID),
        ];
        $payload = [
            ROLE_ID_LOOKING_FOR => TEST_ROLE_ID_LOOKING_FOR,
            AGE => TEST_AGE,
            HEIGHT => TEST_HEIGHT,
            RACE => TEST_RACE,
            ETHNICITY => TEST_ETHNICITY,
            HAIR_COLOUR => TEST_HAIR_COLOUR,
            EYE_COLOUR => TEST_EYE_COLOUR,
            EDUCATION => TEST_EDUCATION,
            STATE => TEST_STATE
        ];
        $response = $this->post(SET_PREFERENCES_API,$payload,[]);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonStructure([
            MESSAGE
        ]);
    }

    /**
     * Set Preference Registration required field.
     *
     * @return void
     */
    public function testEnsurePtbAuthentication()
    {
        $headers = [
            ACCEPT => APPLICATION_JSON,
            AUTHORIZATION => BEARER. AuthHelper::authenticateTestUser(TESTING_DONOR_ID),
        ];
        $payload = [
            ROLE_ID_LOOKING_FOR => TEST_ROLE_ID_LOOKING_FOR,
            AGE => TEST_AGE,
            HEIGHT => TEST_HEIGHT,
            RACE => TEST_RACE,
            ETHNICITY => TEST_ETHNICITY,
            HAIR_COLOUR => TEST_HAIR_COLOUR,
            EYE_COLOUR => TEST_EYE_COLOUR,
            EDUCATION => TEST_EDUCATION,
            STATE => TEST_STATE
        ];
        $response = $this->post(SET_PREFERENCES_API,$payload,[]);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonStructure([
            MESSAGE
        ]);
    }
    
    /**
     * Set Preference Registration required field.
     *
     * @return void
     */
    public function testRequiredFieldsForSetPreferences()
    {
        $headers = [
            ACCEPT => APPLICATION_JSON,
            AUTHORIZATION => BEARER. AuthHelper::authenticateTestUser(TESTING_PTB_ID),
        ];
        $payload = [
            ROLE_ID_LOOKING_FOR => "",
            AGE => "",
            HEIGHT => "",
            RACE => "",
            ETHNICITY => "",
            HAIR_COLOUR => "",
            EYE_COLOUR => "",
            EDUCATION => "",
            STATE => ""
        ];
        $response = $this->post(SET_PREFERENCES_API,$payload,$headers);
        $response->assertStatus(Response::HTTP_EXPECTATION_FAILED);
        $response->assertJsonStructure([
            DATA,
            MESSAGE
        ]);
    }
    
    /**
     * Set Preference Registration.
     *
     * @return void
     */
    public function testSuccessfulSetPreferences()
    {
        $headers = [
            ACCEPT => APPLICATION_JSON,
            AUTHORIZATION => BEARER. AuthHelper::authenticateTestUser(TESTING_PTB_ID),
        ];
        $payload = [
            ROLE_ID_LOOKING_FOR => TEST_ROLE_ID_LOOKING_FOR,
            AGE => TEST_AGE,
            HEIGHT => TEST_HEIGHT,
            RACE => TEST_RACE,
            ETHNICITY => TEST_ETHNICITY,
            HAIR_COLOUR => TEST_HAIR_COLOUR,
            EYE_COLOUR => TEST_EYE_COLOUR,
            EDUCATION => TEST_EDUCATION,
            STATE => TEST_STATE
        ];
        $response = $this->post(SET_PREFERENCES_API,$payload,$headers);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            MESSAGE,
            DATA
        ]);
    }
}
