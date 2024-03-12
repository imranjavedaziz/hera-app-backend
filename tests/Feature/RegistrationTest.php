<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    
    /**
     * User Registration required field.
     *
     * @return void
     */
    public function testRequiredFieldsForRegistration()
    {
        $userData = [
            FILE => "",
            ROLE_ID => "",
            FIRST_NAME => " ",
            MIDDLE_NAME => "",
            LAST_NAME=> "",
            COUNTRY_CODE=> "",
            PHONE_NO=> "",
            EMAIL=> " ",
            DOB=> "",
            PASSWORD=> ""
        ];
        $response = $this->post(REGISTER_API,$userData);
        $response->assertStatus(Response::HTTP_EXPECTATION_FAILED);
    }
    
    /**
     * User Registration Unique field.
     *
     * @return void
     */
    public function testAlreadyExitsPhoneEmailForRegistration()
    {
        $userData = [
            FILE => UploadedFile::fake()->image(TEST_IMAGE_NAME),
            ROLE_ID => PARENTS_TO_BE,
            FIRST_NAME => TEST_FIRST_NAME,
            MIDDLE_NAME => "",
            LAST_NAME=> TEST_PTB_LAST_NAME,
            COUNTRY_CODE=> TEST_COUNTRY_CODE,
            PHONE_NO=> TEST_PHONE_NO,
            EMAIL=> TEST_PTB_EMAIL,
            DOB=> TEST_DOB,
            PASSWORD=> TEST_PASSWORD
        ];
        $response = $this->post(REGISTER_API,$userData);
        $response->assertStatus(Response::HTTP_EXPECTATION_FAILED);
    }

    /**
     * User Registration Success.
     *
     * @return void
     */
    public function testSuccessfulRegistrationPtb()
    {
        $userData = [
            FILE => UploadedFile::fake()->image(TEST_IMAGE_NAME),
            ROLE_ID => PARENTS_TO_BE,
            FIRST_NAME => TEST_FIRST_NAME,
            MIDDLE_NAME => "",
            LAST_NAME=> TEST_PTB_LAST_NAME,
            COUNTRY_CODE=> TEST_COUNTRY_CODE,
            PHONE_NO=> rand(1000000000,9999999999),
            EMAIL=> rand(12345,67891).TEST_YOPMAIL,
            DOB=> TEST_DOB,
            PASSWORD=> TEST_PASSWORD
        ];
        $response = $this->post(REGISTER_API,$userData);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            MESSAGE,
            DATA
        ]);
    }

    /**
     * User Registration.
     *
     * @return void
     */
    public function testSuccessfulRegistrationDonor()
    {
        $userData = [
            FILE => UploadedFile::fake()->image(TEST_IMAGE_NAME),
            ROLE_ID => SURROGATE_MOTHER,
            FIRST_NAME => TEST_FIRST_NAME,
            MIDDLE_NAME => "",
            LAST_NAME=> TEST_DONOR_LAST_NAME,
            COUNTRY_CODE=> TEST_COUNTRY_CODE,
            PHONE_NO=> rand(1000000000,9999999999),
            EMAIL=> rand(12345,67891).TEST_YOPMAIL,
            DOB=> TEST_DOB,
            PASSWORD=> TEST_PASSWORD
        ];
        $response = $this->post(REGISTER_API,$userData);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            MESSAGE,
            DATA
        ]);
    }
}
