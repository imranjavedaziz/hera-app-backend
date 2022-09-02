<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ProfileRegisterRequest;
use Illuminate\Http\Response;
use App\Models\User;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/v1/register",
     *     description="User register",
     *     operationId="user-register",
     *     tags={"User"},
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
     *                             description="Item image PNG/JPEG/GIF/DOC/PDF",
     *                             property="profile_pic",
     *                             type="string", 
     *                             format="binary"
     *                         ),
     *                          @OA\Property(
     *                              description="registration_step can be 1, 2, 3 according to from step",
     *                              property="registration_step",
     *                              type="integer",
     *                              example=1
     *                          ),
     *                          @OA\Property(
     *                              description="role_id can be 2=>Parents To Be, 3=>Surrogate Mother, 4=>Egg Doner, 5=>Sperm Doner",
     *                              property="role_id",
     *                              type="integer",
     *                              example=1
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
     *                             description="(can be 0 or 1)",
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
            $input = $request->all();
            if (!empty($input[PASSWORD])) {
                $input[PASSWORD] = bcrypt($input[PASSWORD]);
            }
            $user = UserRegisterHelper::createUser($input);
            DB::commit();
            if ($user) {
                $response = response()->Success(trans('messages.register.success'), $user);
            } else {
                $response = response()->Error(trans('messages.common_msg.something_went_wrong'));
            }
        } catch (\Exception $e) {
            DB::rollback();
            $response = $this->response->array([SUCCESS => false, MESSAGE => $e->getMessage()]);
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
     *                property="registration_step",
     *                type="integer",
     *                example=1
     *             ),
     *             @OA\Property(
     *                property="user_id",
     *                type="integer",
     *                example=1
     *             ),
     *             @OA\Property(
     *                property="dob",
     *                type="string",
     *                example="2021-12-14"
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
     *  )
     */
    public function profileRegister(ProfileRegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $user_profile = UserRegisterHelper::createUser($request->all());
            DB::commit();
            if ($user_profile) {
                $response = response()->Success(trans('messages.register.profile_success'), $user_profile);
            } else {
                $response = response()->Error(trans('messages.common_msg.something_went_wrong'));
            }
        } catch (\Exception $e) {
            DB::rollback();
            $response = $this->response->array([SUCCESS => false, MESSAGE => $e->getMessage()]);
        }
        return $response;
    }
}
