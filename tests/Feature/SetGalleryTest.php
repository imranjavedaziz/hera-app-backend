<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SetGalleryTest extends TestCase
{
    /**
     * User Registration required field.
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
     * User Registration required field.
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
     * User Registration.
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
