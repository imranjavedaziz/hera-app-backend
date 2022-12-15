<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileRegisterTest extends TestCase
{

    /**
     * User Profile Registration required field.
     *
     * @return void
     */
    public function testUserAuthentication()
    {
        $payload = [
            GENDER_ID => "",
            SEXUAL_ORIENTATION_ID => "",
            RELATIONSHIP_STATUS_ID => "",
            OCCUPATION => "",
            BIO => "",
            STATE_ID => "",
            ZIPCODE => "",
        ];
        $response = $this->post(PROFILE_REGISTER_API,$payload,[]);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonStructure([
            MESSAGE
        ]);
    }
    
    /**
     * User Profile Registration required field.
     *
     * @return void
     */
    public function testRequiredFieldsForProfileRegistration()
    {
        $payload = [
            GENDER_ID => "",
            SEXUAL_ORIENTATION_ID => "",
            RELATIONSHIP_STATUS_ID => "",
            OCCUPATION => "",
            BIO => "",
            STATE_ID => "",
            ZIPCODE => "",
        ];
        $response = $this->post(PROFILE_REGISTER_API,$payload,$this->headers);
        $response->assertStatus(Response::HTTP_EXPECTATION_FAILED);
        $response->assertJsonStructure([
            DATA,
            MESSAGE
        ]);
    }
    
    /**
     * User Profile Registration.
     *
     * @return void
     */
    public function testSuccessfulDonorProfileRegistration()
    {
        $payload = [
            GENDER_ID => TEST_DONOR_GENDER_ID,
            SEXUAL_ORIENTATION_ID => TEST_DONOR_SEXUAL_ORIENTATION_ID,
            RELATIONSHIP_STATUS_ID => TEST_DONOR_RELATIONSHIP_STATUS_ID,
            OCCUPATION => TEST_OCCUPATION,
            BIO => TEST_BIO,
            STATE_ID => TEST_STATE_ID,
            ZIPCODE => TEST_ZIPCODE,
        ];
        $response = $this->post(PROFILE_REGISTER_API,$payload,$this->headers);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            MESSAGE,
            DATA
        ]);
    }
    
    /**
     * User Profile Registration.
     *
     * @return void
     */
    public function testSuccessfulPtbProfileRegistration()
    {
        $headers = [
            ACCEPT => APPLICATION_JSON,
            AUTHORIZATION => BEARER. AuthHelper::authenticateTestUser(TESTING_PTB_ID),
        ];
        $payload = [
            GENDER_ID => TEST_PTB_GENDER_ID,
            SEXUAL_ORIENTATION_ID => TEST_PTB_SEXUAL_ORIENTATION_ID,
            RELATIONSHIP_STATUS_ID => TEST_PTB_RELATIONSHIP_STATUS_ID,
            OCCUPATION => TEST_OCCUPATION,
            BIO => TEST_BIO,
            STATE_ID => TEST_STATE_ID,
            ZIPCODE => TEST_ZIPCODE,
        ];
        $response = $this->post(PROFILE_REGISTER_API,$payload,$headers);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            MESSAGE,
            DATA
        ]);
    }
}
