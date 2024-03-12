<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * User Login required field.
     *
     * @return void
     */
    public function testRequiredFieldsForLogin()
    {
        $userData = [
            COUNTRY_CODE=> "",
            PHONE_NO=> "",
            PASSWORD=> ""
        ];
        $response = $this->post(LOGIN_API,$userData);
        $response->assertStatus(Response::HTTP_EXPECTATION_FAILED);
    }
    
    /**
     * User Login restricat deleted user.
     *
     * @return void
     */
    public function testDeletedUserLogin()
    {
        $userData = [
            COUNTRY_CODE=> TEST_COUNTRY_CODE,
            PHONE_NO=> TEST_DELETED_USER_PHONE,
            PASSWORD=> TEST_PASSWORD
        ];
        $response = $this->post(LOGIN_API,$userData);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
    
    /**
     * User Login required field.
     *
     * @return void
     */
    public function testDeletedByAdminUserLogin()
    {
        $userData = [
            COUNTRY_CODE=> TEST_COUNTRY_CODE,
            PHONE_NO=> TEST_DELETED_ADMIN_PHONE,
            PASSWORD=> TEST_PASSWORD
        ];
        $response = $this->post(LOGIN_API,$userData);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * User Login.
     *
     * @return void
     */
    public function testSuccessfulLogin()
    {
        $userData = [
            COUNTRY_CODE=> TEST_COUNTRY_CODE,
            PHONE_NO=> TEST_PHONE_NO,
            PASSWORD=> TEST_PASSWORD
        ];
        $response = $this->post(LOGIN_API,$userData);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            MESSAGE,
            DATA
        ]);
    }
}
