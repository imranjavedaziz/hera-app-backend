<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Faker\Generator as Faker;
use App\Models\User;
use App\Helpers\AuthHelper;

class SetGalleryTest extends TestCase
{
    use WithFaker;
    /**
     * Set Gallery User Authentication.
     *
     * @return void
     */
    public function testUserAuthentication()
    {
        $userData = [
            IMAGE => "",
            VIDEO => "",
            OLD_FILE_NAME => " ",
        ];
        $response = $this->post(SET_GALLERY_API,$userData, []);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
    
    /**
     * Set Gallery required field.
     *
     * @return void
     */
    public function testRequiredFieldsForSetGallery()
    {
        $userData = [
            IMAGE => "",
            VIDEO => "",
            OLD_FILE_NAME => " ",
        ];
        $response = $this->post(SET_GALLERY_API,$userData, $this->headers);
        $response->assertStatus(Response::HTTP_EXPECTATION_FAILED);
    }

    /**
     * Set Gallery limit.
     *
     * @return void
     */
    public function testSuccessfulSetGallery()
    {
        $userData = [
            IMAGE => UploadedFile::fake()->image(TEST_IMAGE_GALLERY),
            VIDEO => "",
            OLD_FILE_NAME => " ",
        ];
        $response = $this->post(SET_GALLERY_API,$userData, $this->headers);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonStructure([
            MESSAGE
        ]);
    }

    /**
     * Set Gallery.
     *
     * @return void
     */
    public function testSetGalleryLimit()
    {
        $user = AuthHelper::factoryUserCreate(PARENTS_TO_BE, SUBSCRIPTION_ENABLED);
        $headers = [
            ACCEPT => APPLICATION_JSON,
            AUTHORIZATION => BEARER . AuthHelper::authenticateTestUser($user->id)
        ];
        $userData = [
            IMAGE => UploadedFile::fake()->image(TEST_IMAGE_GALLERY),
            VIDEO => "",
            OLD_FILE_NAME => "",
        ];
        $response = $this->actingAs($user, 'api')
            ->postJson(SET_GALLERY_API, $userData, $headers);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            MESSAGE
        ]);
    }

    /**
     * Get Gallery.
     *
     * @return void
     */
    public function testGetStates()
    {
        $response = $this->get(GET_GALLERY_API, $this->headers);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            MESSAGE
        ]);
    }
}
