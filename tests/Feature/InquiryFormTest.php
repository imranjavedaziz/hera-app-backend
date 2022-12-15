<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Helpers\AuthHelper;

class InquiryFormTest extends TestCase
{

    /**
     * Get Roles.
     *
     * @return void
     */
    public function testGetRoles()
    {
        $response = $this->get(GET_ROLES_API);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            MESSAGE
        ]);
    }

    /**
     * Set Attributes Registration required field.
     *
     * @return void
     */
    public function testRequiredFieldsForInquiry()
    {
        $payload = [
            USER_TIMEZONE => "",
            NAME => "",
            EMAIL => "",
            COUNTRY_CODE => "",
            PHONE_NO => "",
            ENQUIRING_AS => "",
            MESSAGE => "",
        ];
        $response = $this->post(INQUIRY_API,$payload);
        $response->assertStatus(Response::HTTP_EXPECTATION_FAILED);
        $response->assertJsonStructure([
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
            USER_TIMEZONE => TEST_USER_TIMEZONE,
            NAME => TEST_NAME,
            EMAIL => rand(12345,67891).TEST_YOPMAIL,
            COUNTRY_CODE => TEST_COUNTRY_CODE,
            PHONE_NO => TEST_PHONE_NO,
            ENQUIRING_AS => PARENTS_TO_BE,
            MESSAGE => TEST_MESSAGE,
        ];
        $response = $this->post(INQUIRY_API,$payload);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            MESSAGE,
            DATA
        ]);
    }
}
