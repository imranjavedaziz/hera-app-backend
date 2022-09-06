<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileRegisterRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SetPreferencesRequest;
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
     *                             property="profile_pic",
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
            DB::commit();
            if ($user) {
                $oauth_token = JWTAuth::attempt([PHONE_NO => strtolower($request->phone_no), PASSWORD => $request->password]);
                $user->access_token = $oauth_token;
                $response = response()->Success(trans('messages.register.success'), $user);
            } else {
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
                $response = response()->Success(trans('messages.common_msg.data_found'), $profile_setter_data);
            } else {
                $response = response()->Error(trans('messages.common_msg.no_data_found'));
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
     *                property="dob",
     *                type="string",
     *                example="14-12-1990"
     *             ),
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
                $response = response()->Success(trans('messages.common_msg.data_found'), $preferences_setter_data);
            } else {
                $response = response()->Error(trans('messages.common_msg.no_data_found'));
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
     *        description = "User set-preferences",
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
     *                example="12,24"
     *             ),
     *             @OA\Property(
     *                property="height",
     *                type="string",
     *                example="60,70"
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
            DB::commit();
            if ($user_preferences) {
                $response = response()->Success(trans('messages.register.preferences_save_success'), $user_preferences);
            } else {
                $response = response()->Error(trans(LANG_SOMETHING_WRONG));
            }
        } catch (\Exception $e) {
            DB::rollback();
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
}
