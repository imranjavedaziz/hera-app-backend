<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\AgeRangeRequest;
use App\Http\Requests\ProfileRegisterRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SetAttributesRequest;
use App\Http\Requests\SetPreferencesRequest;
use App\Http\Requests\SetGalleryRequest;
use App\Http\Requests\UpdateProfilePicRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use DB;
use App\Helpers\AuthHelper;
use Facades\{
    App\Services\UserRegisterService,
};
use App\Models\User;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/v1/register",
     *     description="User register",
     *     operationId="user-register",
     *     tags={"Register"},
     *     summary="User register",
     *     description="User register for MBC portal.",
     *     @OA\RequestBody(
     *        description = "User register for MBC portal.",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(
     *                         @OA\Property(
     *                             description="Item image PNG/JPEG",
     *                             property="file",
     *                             type="string", 
     *                             format="binary"
     *                         ),
     *                          @OA\Property(
     *                              description="role_id can be 2=>Parents To Be, 3=>Surrogate Mother, 4=>Egg Doner, 5=>Sperm Doner",
     *                              property="role_id",
     *                              type="integer",
     *                              example=2
     *                          ),
     *                          @OA\Property(
     *                              property="first_name",
     *                              type="string",
     *                              example="Xyx"
     *                          ),
     *                          @OA\Property(
     *                              property="middle_name",
     *                              type="string",
     *                              example="Xyz"
     *                          ),
     *                          @OA\Property(
     *                              property="last_name",
     *                              type="string",
     *                              example="Xyz"
     *                          ),
     *                          @OA\Property(
     *                              property="country_code",
     *                              type="string",
     *                              example="+1"
     *                          ),
     *                          @OA\Property(
     *                              property="phone_no",
     *                              type="string",
     *                              example="1234567890"
     *                          ),
     *                          @OA\Property(
     *                              property="email",
     *                              type="string",
     *                              example="xyz@yopmail.com"
     *                          ),
     *                          @OA\Property(
     *                              property="dob",
     *                              type="string",
     *                              example="14-12-1990"
     *                          ),
     *                          @OA\Property(
     *                              property="password",
     *                              type="string",
     *                              example="Xyz@12345"
     *                          ),
     *                      )
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *     ),
     *     @OA\Response(
     *          response=417,
     *          description="Expectation Failed"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *  )
     */
    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = UserRegisterService::register($request->all());
            if ($user) {
                DB::commit();
                $oauth_token = JWTAuth::attempt([PHONE_NO => strtolower($request->phone_no), PASSWORD => $request->password]);
                $user->access_token = $oauth_token;
                $response = response()->Success(trans('messages.register.success'), $user);
            } else {
                DB::rollback();
                $response = response()->Error(trans(LANG_SOMETHING_WRONG));
            }
        } catch (JWTException $e) {
            DB::rollback();
            $response = response()->Error($e->getMessage());
        }catch (\Exception $e) {
            DB::rollback();
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Get(
     *      path="/v1/profile-setter-data",
     *      operationId="profile-setter-data",
     *      tags={"User"},
     *      summary="Get profile-setter-data",
     *      description="Get profile-setter-data",
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *      security={ {"bearer": {}} },
     *  )
     */
    public function getProfileSetterData()
    {
        try {
            $profile_setter_data = UserRegisterService::getProfileSetterData();
            if ($profile_setter_data) {
                $response = response()->Success(trans(LANG_DATA_FOUND), $profile_setter_data);
            } else {
                $response = response()->Error(trans(LANG_DATA_NOT_FOUND));
            }
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Post(
     *     path="/v1/profile-register",
     *     description="User profile register",
     *     operationId="user-profile-register",
     *     tags={"User"},
     *     summary="User register",
     *     description="User profile-register for MBC portal.",
     *     @OA\RequestBody(
     *        required = true,
     *        description = "User profile register",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="gender_id",
     *                type="integer",
     *                example=1
     *             ),
     *             @OA\Property(
     *                property="sexual_orientations_id",
     *                type="integer",
     *                example=1
     *             ),
     *             @OA\Property(
     *                property="relationship_status_id",
     *                type="integer",
     *                example=1
     *             ),
     *             @OA\Property(
     *                property="occupation",
     *                type="string",
     *                example="abcd"
     *             ),
     *             @OA\Property(
     *                property="bio",
     *                type="string",
     *                example="Hi i am xyz."
     *             ),
     *             @OA\Property(
     *                property="state_id",
     *                type="integer",
     *                example=1
     *             ),
     *             @OA\Property(
     *                property="zipcode",
     *                type="integer",
     *                example=12345
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *     ),
     *     @OA\Response(
     *          response=417,
     *          description="Expectation Failed"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *      security={ {"bearer": {}} },
     *  )
     */
    public function profileRegister(ProfileRegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $user_profile = UserRegisterService::profileRegister(AuthHelper::authenticatedUser(), $request->all());
            DB::commit();
            if ($user_profile) {
                $response = response()->Success(trans('messages.register.profile_success'), $user_profile);
            } else {
                $response = response()->Error(trans(LANG_SOMETHING_WRONG));
            }
        } catch (\Exception $e) {
            DB::rollback();
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Get(
     *      path="/v1/preferences-age-range-data",
     *      operationId="preferences-age-range-data",
     *      tags={"User"},
     *      summary="Get preferences-age-range-data",
     *      description="Get preferences-age-range-data",
     *      @OA\Parameter(
     *          name="role_id_looking_for",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *      security={ {"bearer": {}} },
     *  )
     */
    public function getPreferencesAgeRangeData(AgeRangeRequest $request)
    {
        try {
            $preferences_age_range_data = UserRegisterService::getPreferencesAgeRangeData($request->all());
            if ($preferences_age_range_data) {
                $response = response()->Success(trans(LANG_DATA_FOUND), $preferences_age_range_data);
            } else {
                $response = response()->Error(trans(LANG_DATA_NOT_FOUND));
            }
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Get(
     *      path="/v1/preferences-setter-data",
     *      operationId="preferences-setter-data",
     *      tags={"User"},
     *      summary="Get preferences-setter-data",
     *      description="Get preferences-setter-data",
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *      security={ {"bearer": {}} },
     *  )
     */
    public function getPreferencesSetterData()
    {
        try {
            $preferences_setter_data = UserRegisterService::getPreferencesSetterData();
            if ($preferences_setter_data) {
                $response = response()->Success(trans(LANG_DATA_FOUND), $preferences_setter_data);
            } else {
                $response = response()->Error(trans(LANG_DATA_NOT_FOUND));
            }
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Post(
     *     path="/v1/set-preferences",
     *     description="User set-preferences",
     *     operationId="user-set-preferences",
     *     tags={"User"},
     *     summary="User set-preferences",
     *     description="User set-preferences for MBC portal.",
     *     @OA\RequestBody(
     *        required = true,
     *        description = "User set-preferences, Height Should be in inches only",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="role_id_looking_for",
     *                type="integer",
     *                example=3
     *             ),
     *             @OA\Property(
     *                property="age",
     *                type="string",
     *                example="21-28, 28-35"
     *             ),
     *             @OA\Property(
     *                property="height",
     *                type="string",
     *                example="60-70"
     *             ),
     *             @OA\Property(
     *                property="race",
     *                type="string",
     *                example="2,3"
     *             ),
     *             @OA\Property(
     *                property="ethnicity",
     *                type="string",
     *                example="2,3"
     *             ),
     *             @OA\Property(
     *                property="hair_colour",
     *                type="string",
     *                example="1,2"
     *             ),
     *             @OA\Property(
     *                property="eye_colour",
     *                type="string",
     *                example="1,2"
     *             ),
     *             @OA\Property(
     *                property="education",
     *                type="string",
     *                example="1,2"
     *             ),
     *             @OA\Property(
     *                property="state",
     *                type="string",
     *                example="1,2"
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *     ),
     *     @OA\Response(
     *          response=417,
     *          description="Expectation Failed"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *      security={ {"bearer": {}} },
     *  )
     */
    public function setPreferences(SetPreferencesRequest $request)
    {
        try {
            DB::beginTransaction();
            $user_preferences = UserRegisterService::setPreferences(AuthHelper::authenticatedUser(), $request->all());
            if ($user_preferences) {
                DB::commit();
                $response = response()->Success(trans('messages.register.preferences_save_success'), $user_preferences);
            } else {
                DB::rollback();
                $response = response()->Error(trans(LANG_SOMETHING_WRONG));
            }
        } catch (\Exception $e) {
            DB::rollback();
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Get(
     *      path="/v1/attributes-setter-data",
     *      operationId="attributes-setter-data",
     *      tags={"User"},
     *      summary="Get attributes-setter-data",
     *      description="Get attributes-setter-data",
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *      security={ {"bearer": {}} },
     *  )
     */
    public function getAttributesSetterData()
    {
        try {
            $attributes_setter_data = UserRegisterService::getAttributesSetterData();
            if ($attributes_setter_data) {
                $response = response()->Success(trans(LANG_DATA_FOUND), $attributes_setter_data);
            } else {
                $response = response()->Error(trans(LANG_DATA_NOT_FOUND));
            }
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Post(
     *     path="/v1/set-attributes",
     *     description="User set-attributes",
     *     operationId="user-set-attributes",
     *     tags={"User"},
     *     summary="User set-attributes",
     *     description="User set-attributes for MBC portal.",
     *     @OA\RequestBody(
     *        required = true,
     *        description = "User set-attributes",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="height_id",
     *                type="integer",
     *                example=3
     *             ),
     *             @OA\Property(
     *                property="race_id",
     *                type="integer",
     *                example=3
     *             ),
     *             @OA\Property(
     *                property="mother_ethnicity_id",
     *                type="integer",
     *                example=3
     *             ),
     *             @OA\Property(
     *                property="father_ethnicity_id",
     *                type="integer",
     *                example=3
     *             ),
     *             @OA\Property(
     *                property="weight_id",
     *                type="integer",
     *                example=3
     *             ),
     *             @OA\Property(
     *                property="hair_colour_id",
     *                type="integer",
     *                example=3
     *             ),
     *             @OA\Property(
     *                property="eye_colour_id",
     *                type="integer",
     *                example=3
     *             ),
     *             @OA\Property(
     *                property="education_id",
     *                type="integer",
     *                example=3
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *     ),
     *     @OA\Response(
     *          response=417,
     *          description="Expectation Failed"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *      security={ {"bearer": {}} },
     *  )
     */
    public function setAttributes(SetAttributesRequest $request)
    {
        try {
            DB::beginTransaction();
            $doner_attributes = UserRegisterService::setAttributes(AuthHelper::authenticatedUser(), $request->all());
            if ($doner_attributes) {
                DB::commit();
                $response = response()->Success(trans('messages.register.attributes_save_success'), $doner_attributes);
            } else {
                DB::rollback();
                $response = response()->Error(trans(LANG_SOMETHING_WRONG));
            }
        } catch (\Exception $e) {
            DB::rollback();
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Post(
     *     path="/v1/set-gallery",
     *     description="User set-gallery",
     *     operationId="user-set-gallery",
     *     tags={"User"},
     *     summary="User set-gallery",
     *     description="User set-gallery for MBC portal.",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(
     *                         @OA\Property(
     *                             description="Item file jpeg/png",
     *                             property="image",
     *                             type="string", 
     *                             format="binary"
     *                         ),
     *                         @OA\Property(
     *                             description="Item file mp4,ogx,oga,ogv,ogg,webm",
     *                             property="video",
     *                             type="string", 
     *                             format="binary"
     *                         ),
     *                          @OA\Property(
     *                              property="old_file_name",
     *                              type="string",
     *                              example="abc.png"
     *                          ),
     *                     )
     *                 }
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *     ),
     *     @OA\Response(
     *          response=417,
     *          description="Expectation Failed"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *      security={ {"bearer": {}} },
     *  )
     */
    public function setGallery(SetGalleryRequest $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->all();
            $user = AuthHelper::authenticatedUser();
            $existed_count = UserRegisterService::uploadedFilesCountValidation($user, $input);
            $response = response()->Error($existed_count[MESSAGE]);
            if($existed_count[SUCCESS]){
                $doner_gallery = UserRegisterService::setGallery($user, $input);
                if ($doner_gallery[SUCCESS]) {
                    DB::commit();
                    $response = response()->Success(trans('messages.register.gallery_save_success'), $doner_gallery[DATA]);
                } else {
                    DB::rollback();
                    $message = !empty($doner_gallery[MESSAGE]) ? $doner_gallery[MESSAGE] : trans(LANG_SOMETHING_WRONG);
                    $response = response()->Error($message);
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Delete(
     *      path="/v1/delete-gallery",
     *      operationId="user-delete-gallery",
     *      tags={"User"},
     *      summary="Delete user gallery and save later",
     *      description="Delete user gallery and save later",
     *      @OA\RequestBody(
     *        required = true,
     *        description = "Delete user gallery and save later",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="ids",
     *                type="array",
     *                @OA\Items(
     *                         type="string",
     *                         example="1"
     *                ),
  
     *             ),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *      security={ {"bearer": {}} },
     *  )
     */
    public function deleteGallery(Request $request) {
        try {
            $deleted_gallery = UserRegisterService::deleteGallery(AuthHelper::authenticatedUser()->id, $request->all()['ids']);
            $response = response()->Success(trans('messages.common_msg.data_deleted'), $deleted_gallery);
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Get(
     *      path="/v1/get-gallery",
     *      operationId="get-gallery",
     *      tags={"User"},
     *      summary="get-gallery",
     *      description="get-gallery",
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *      security={ {"bearer": {}} },
     *  )
     */
    public function getGalleryData()
    {
        try {
            $user = AuthHelper::authenticatedUser();
            $response = response()->Success(trans(LANG_DATA_FOUND), [DONER_PHOTO_GALLERY => $user->donerPhotoGallery, DONER_VIDEO_GALLERY => $user->donerVideoGallery]);
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Post(
     *      path="/v1/update-profile-pic",
     *      operationId="update-profile-pic",
     *      tags={"User"},
     *      summary="update profile pic",
     *      description="update profile pic.",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(
     *                         @OA\Property(
     *                             description="Item image PNG/JPEG",
     *                             property="file",
     *                             type="string", 
     *                             format="binary"
     *                         )
     *                     )
     *                 }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Request successfully completed.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=417,
     *          description="Expectation Failed"
     *      ),
     *      @OA\Response(
     *          response=409,
     *          description="Conflict",
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *      security={ {"bearer": {}} },
     *  )
     */
    public function updateProfilePic(UpdateProfilePicRequest $request)
    {
        try {
            $update_profile_image = UserRegisterService::updateProfilePic(AuthHelper::authenticatedUser(), $request->all());
            if ($update_profile_image) {
                $response = response()->Success(trans('messages.profile_update.image'), $update_profile_image);
            } else {
                $response = response()->Error(trans(LANG_SOMETHING_WRONG));
            }
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
    
    /**
     * @OA\Get(
     *      path="/v1/get-attributes",
     *      operationId="get-attributes",
     *      tags={"User"},
     *      summary="get-attributes",
     *      description="get-attributes",
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *      security={ {"bearer": {}} },
     *  )
     */
    public function getAttributes()
    {
        try {
            $response = response()->Success(trans(LANG_DATA_FOUND), AuthHelper::authenticatedUser()->donerAttribute);
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
    
    /**
     * @OA\Get(
     *      path="/v1/get-user-profile",
     *      operationId="get-user-profile",
     *      tags={"User"},
     *      summary="get-user-profile",
     *      description="get-user-profile",
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *      security={ {"bearer": {}} },
     *  )
     */
    public function getUserProfile()
    {
        try {
            $user_profile_data = UserRegisterService::getUserProfile(AuthHelper::authenticatedUser()->id);
            $response = response()->Success(trans(LANG_DATA_FOUND), $user_profile_data);
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
    
    /**
     * @OA\Post(
     *     path="/v1/update-profile",
     *     description="User update profile",
     *     operationId="user-update-profile",
     *     tags={"User"},
     *     summary="User update profile",
     *     description="User update profile for MBC portal.",
     *     @OA\RequestBody(
     *        description = "User update profile for MBC portal.",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(
     *                          @OA\Property(
     *                              property="first_name",
     *                              type="string",
     *                              example="Xyx"
     *                          ),
     *                          @OA\Property(
     *                              property="middle_name",
     *                              type="string",
     *                              example="Xyz"
     *                          ),
     *                          @OA\Property(
     *                              property="last_name",
     *                              type="string",
     *                              example="Xyz"
     *                          ),
     *                          @OA\Property(
     *                              property="dob",
     *                              type="string",
     *                              example="14-12-1990"
     *                          ),
     *                           @OA\Property(
     *                               property="gender_id",
     *                                type="integer",
     *                                example=1
     *                          ),
     *                          @OA\Property(
     *                               property="sexual_orientations_id",
     *                               type="integer",
     *                               example=1
     *                           ),
     *                           @OA\Property(
     *                              property="relationship_status_id",
     *                              type="integer",
     *                              example=1
     *                          ),
     *                           @OA\Property(
     *                              property="occupation",
     *                              type="string",
     *                              example="abcd"
     *                           ),
     *                           @OA\Property(
     *                              property="bio",
     *                              type="string",
     *                              example="Hi i am xyz."
     *                           ),
     *                          @OA\Property(
     *                             property="state_id",
     *                             type="integer",
     *                             example=1
     *                          ),
     *                          @OA\Property(
     *                              property="zipcode",
     *                              type="integer",
     *                              example=12345
     *                           ),
     *                      )
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *     ),
     *     @OA\Response(
     *          response=417,
     *          description="Expectation Failed"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *      security={ {"bearer": {}} },
     *  )
     */
    public function updateProfile(UpdateUserProfileRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = UserRegisterService::updateUser(AuthHelper::authenticatedUser(), $request->all());
            if ($user) {
                DB::commit();
                $response = response()->Success(trans('messages.profile_update.profile_data'), $user);
            } else {
                DB::rollback();
                $response = response()->Error(trans(LANG_SOMETHING_WRONG));
            }
        } catch (\Exception $e) {
            DB::rollback();
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
}
