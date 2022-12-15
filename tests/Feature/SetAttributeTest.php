<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Helpers\AuthHelper;

class SetAttributeTest extends TestCase
{

    /**
     * Set Attributes Registration required field.
     *
     * @return void
     */
    public function testUserAuthentication()
    {
        $payload = [
            HEIGHT_ID => TEST_HEIGHT_ID,
            RACE_ID => TEST_RACE_ID,
            MOTHER_ETHNICITY_ID => TEST_MOTHER_ETHNICITY_ID,
            FATHER_ETHNICITY_ID => TEST_FATHER_ETHNICITY_ID,
            WEIGHT_ID => TEST_WEIGHT_ID,
            HAIR_COLOUR_ID => TEST_HAIR_COLOUR_ID,
            EYE_COLOUR_ID => TEST_EYE_COLOUR_ID,
            EDUCATION_ID => TEST_EDUCATION_ID,
        ];
        $response = $this->post(SET_ATTRIBUTES_API,$payload,[]);
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
        $payload = [
            HEIGHT_ID => TEST_HEIGHT_ID,
            RACE_ID => TEST_RACE_ID,
            MOTHER_ETHNICITY_ID => TEST_MOTHER_ETHNICITY_ID,
            FATHER_ETHNICITY_ID => TEST_FATHER_ETHNICITY_ID,
            WEIGHT_ID => TEST_WEIGHT_ID,
            HAIR_COLOUR_ID => TEST_HAIR_COLOUR_ID,
            EYE_COLOUR_ID => TEST_EYE_COLOUR_ID,
            EDUCATION_ID => TEST_EDUCATION_ID,
        ];
        $response = $this->post(SET_ATTRIBUTES_API,$payload,$headers);
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
    public function testRequiredFieldsForSetAttributes()
    {
        $payload = [
            HEIGHT_ID => "",
            RACE_ID => "",
            MOTHER_ETHNICITY_ID => "",
            FATHER_ETHNICITY_ID => "",
            WEIGHT_ID => "",
            HAIR_COLOUR_ID => "",
            EYE_COLOUR_ID => "",
            EDUCATION_ID => "",
        ];
        $response = $this->post(SET_ATTRIBUTES_API,$payload,$this->headers);
        $response->assertStatus(Response::HTTP_EXPECTATION_FAILED);
        $response->assertJsonStructure([
            DATA,
            MESSAGE
        ]);
    }
    
    /**
     * Set Attributes Registration.
     *
     * @return void
     */
    public function testSuccessfulSetAttributes()
    {
        $payload = [
            HEIGHT_ID => TEST_HEIGHT_ID,
            RACE_ID => TEST_RACE_ID,
            MOTHER_ETHNICITY_ID => TEST_MOTHER_ETHNICITY_ID,
            FATHER_ETHNICITY_ID => TEST_FATHER_ETHNICITY_ID,
            WEIGHT_ID => TEST_WEIGHT_ID,
            HAIR_COLOUR_ID => TEST_HAIR_COLOUR_ID,
            EYE_COLOUR_ID => TEST_EYE_COLOUR_ID,
            EDUCATION_ID => TEST_EDUCATION_ID,
        ];
        $response = $this->post(SET_ATTRIBUTES_API,$payload,$this->headers);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            MESSAGE,
            DATA
        ]);
    }
}
