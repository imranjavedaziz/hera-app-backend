<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\CheckPhoneRequest;
use App\Http\Requests\ValidateOtpRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\UpdateAccountStatusRequest;
use App\Helpers\TwilioOtp;
use App\Helpers\AuthHelper;
use Log;
use Facades\{
    App\Services\FcmService,
    App\Services\UserRegisterService,
    App\Services\FirebaseService
};
use Illuminate\Support\Facades\Hash;
use App\Jobs\PasswordResetJob;
use App\Models\AccountDeactiveReason;
use App\Jobs\UpdateStatusOnFirebaseJob;
use App\Constants\MobileVerificationType;
use DB;
use Carbon\Carbon;
use App\Helpers\CustomHelper;
use App\Jobs\SendDeactiveDeleteUserJob;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/v1/login",
     *      operationId="login",
     *      tags={"Auth"},
     *      summary="User Login",
     *      description="User login for MBC portal.",
     *      @OA\RequestBody(
     *        required = true,
     *        description = "User login",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="country_code",
     *                type="string",
     *                example="+1"
     *             ),
     *             @OA\Property(
     *                property="phone_no",
     *                type="string",
     *                example="1234567897"
     *             ),
     *             @OA\Property(
     *                property="password",
     *                type="string",
     *                example="Johan@123"
     *             ),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Logged in successfully.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
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
    public function login(LoginRequest $request) {
        try {
            $user_credentials = [
                COUNTRY_CODE => $request->country_code,
                PHONE_NO => $request->phone_no,
                PASSWORD => $request->password,
                ROLE_ID => [PARENTS_TO_BE, SURROGATE_MOTHER, EGG_DONER, SPERM_DONER],
                DELETED_AT => NULL
            ];
            $user = User::where([PHONE_NO => $request->phone_no, COUNTRY_CODE => $request->country_code])->orderBy(ID, 'desc')->first();
            if (empty($user)) {
                return response()->Error(trans('messages.invalid_user_phone'));
            }
            $message = CustomHelper::getDeleteInactiveMsg($user);
            if ($oauth_token = JWTAuth::attempt($user_credentials)) {
                if (($user->status_id === ACTIVE || $user->status_id === INACTIVE ) && $user->deactivated_by != ONE) {
                    if ($user->status_id === INACTIVE) {
                        User::where(ID, $user->id)->update([STATUS_ID => ACTIVE]);
                        dispatch(new SendDeactiveDeleteUserJob($user->id, ACTIVE));
                    }
                    $user->access_token = $oauth_token;
                    $response = response()->Success(trans('messages.logged_in'), $user);
                } else {
                    $response = response()->Error($message);
                }
            } else {
                $response = response()->Error($message);
            }
        } catch (JWTException $e) {
            $response = response()->Error($e->getMessage());
        }
    
        return $response;
    }

    /**
     * @OA\Post(
     *      path="/v1/sent-otp",
     *      operationId="sentOtp",
     *      tags={"Auth"},
     *      summary="Sent OTP for phone verification",
     *      description="Sent OTP for phone verification.",
     *      @OA\RequestBody(
     *        required = true,
     *        description = "Sent OTP",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="country_code",
     *                type="string",
     *                example="+1"
     *             ),
     *             @OA\Property(
     *                property="phone_no",
     *                type="string",
     *                example="1234567897"
     *             ),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Success.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
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
    public function sentOtp(CheckPhoneRequest $request) {
        try {
            if (isset($request->type) && MobileVerificationType::FORGOT_PWD == $request->type) {
                $response = UserRegisterService::sentOtpForFogotPassword($request);
            } else {
                $response = UserRegisterService::sentOtpForMobileNumberVerify($request);
            }
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }

        return $response;
    }

     /**
     * @OA\Post(
     *      path="/v1/verify-otp",
     *      operationId="verifyOtp",
     *      tags={"Auth"},
     *      summary="Verify OTP for phone verification",
     *      description="Verify OTP for phone verification.",
     *      @OA\RequestBody(
     *        required = true,
     *        description = "Verify OTP",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="country_code",
     *                type="string",
     *                example="+1"
     *             ),
     *             @OA\Property(
     *                property="phone_no",
     *                type="string",
     *                example="1234567897"
     *             ),
     *             @OA\Property(
     *                property="otp",
     *                type="integer",
     *                example="123456"
     *             ),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Success.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
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
    public function verifyOtp(ValidateOtpRequest $request) {
        try {
            $result = TwilioOtp::otpVerification($request->all());
            if($result[STATUS]) {
                $response = response()->Success($result[MESSAGE]);
            } else {
                $response = response()->Error($result[MESSAGE]);
            }
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }

        return $response;
    }

    /**
     * @OA\Get(
     *      path="/v1/logout",
     *      operationId="logout",
     *      tags={"Auth"},
     *      summary="User logout",
     *      description="User logout for MBC portal.",
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
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
    public function logout(Request $request)
    {
        $user = AuthHelper::authenticatedUser();
        if (empty($user) || (!empty($user) && $user->role_id === ADMIN)) {
            return response()->Error(__('messages.invalid_access_token'));
        }
        JWTAuth::invalidate(JWTAuth::parseToken());
        FcmService::deactivateRegisterDevice($user->id);
        return response()->Success(__('messages.logged_out'));
    }

    /**
     * @OA\Get(
     *      path="/v1/refresh-token",
     *      operationId="refresh-token",
     *      tags={"Auth"},
     *      summary="User refresh token",
     *      description="User refresh token for MBC portal.",
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
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
    public function refreshToken()
    {
        $token = JWTAuth::getToken();
        try {
            $newToken = JWTAuth::refresh($token);
            $response = response()->json(['token' => $newToken], Response::HTTP_OK);
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                $response = response()->json([MESSAGE => 'Token is Invalid.'], Response::HTTP_FORBIDDEN);
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                $response = response()->json([MESSAGE => 'Token is Expired.'], Response::HTTP_FORBIDDEN);
            } else {
                $response = response()->json([MESSAGE => $e->getMessage()], Response::HTTP_FORBIDDEN);
            }
        }

        return $response;
    }

    /**
     * @OA\Post(
     *      path="/v1/reset-password",
     *      operationId="reset-password",
     *      tags={"Forgot Password"},
     *      summary="Reset Password",
     *      description="For generating new password in mbc portal.",  
     *      @OA\RequestBody(
     *        required = true,
     *        description = "Reset Password Details",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="user_id",
     *                type="integer",
     *                example="11"
     *             ),
     *             @OA\Property(
     *                property="password",
     *                type="string",
     *                example="john@123"
     *             ),
     *             @OA\Property(
     *                property="confirm_password",
     *                type="integer",
     *                example="john@123"
     *             ),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Success.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
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
    public function resetPassword(ForgotPasswordRequest $request) {
        try {
            DB::beginTransaction();
            $user = User::where(ID, $request->user_id)->first();
            if (empty($user)) {
                return response()->Error(__('messages.reset_password_invalid_user'));
            }
            $user->password = Hash::make($request->password);
            $user->password_updated = Carbon::now();
            $user->save();
            dispatch(new PasswordResetJob($user));
            DB::commit();
            $response = response()->Success(__('messages.change_password.change_password_success'));
        } catch (\Exception $e) {
            DB::rollback();
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

     /**
     * @OA\Get(
     *      path="/v1/account-deactive-reason",
     *      operationId="account-deactive-reason",
     *      tags={"Auth"},
     *      summary="Deactive reasons",
     *      description="Deactive reasons",
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
     *      )
     *  )
     */
    public function getAccountDeactiveReason(Request $request)
    {
        try {
            $response = response()->Success(trans('messages.common_msg.data_found'), AccountDeactiveReason::getReasons());
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Post(
     *      path="/v1/update-account-status",
     *      operationId="update-account-status",
     *      tags={"Auth"},
     *      summary="Update acount status",
     *      description="Activate and deactivate user account status.",  
     *      @OA\RequestBody(
     *        required = true,
     *        description = "status_id 1->activate , 2 -> deactivate",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="status_id",
     *                type="integer",
     *                example="0"
     *             ),
     *             @OA\Property(
     *                property="reason_id",
     *                type="integer",
     *                example="1"
     *             ),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Success.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
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
    public function updateAccountStatus(UpdateAccountStatusRequest $request) {
        try {
            DB::beginTransaction();
            $msg = __('messages.account_deactive');
            if ($request->status_id == ACTIVE) {
                $msg = __('messages.account_active');
            }
            $user = AuthHelper::authenticatedUser();
            UserRegisterService::updateUserAccountStatus($user->id, $request->all());
            DB::commit();
            dispatch(new SendDeactiveDeleteUserJob($user->id, $request->status_id));
            dispatch(new UpdateStatusOnFirebaseJob($user, $request->status_id, STATUS_ID));
            $response = response()->Success($msg);
        } catch (\Exception $e) {
            DB::rollback();
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Post(
     *      path="/v1/match-password",
     *      operationId="user-match-password",
     *      tags={"User"},
     *      summary="User match password",
     *      description="User match password",
     *      @OA\Parameter(
     *         description="password",
     *         in="query",
     *         name="password",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
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
    public function matchPassword(Request $request)
    {
        try {
            $input = $request->all();
            $user = AuthHelper::authenticatedUser();
            if (Hash::check($input[PASSWORD], $user->password)) {
                $response = response()->Success(__('messages.password_matched'));
            } else {
                $response = response()->Error(__('messages.password_does_not_match'));
            }
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Delete(
     *      path="/v1/delete-account",
     *      operationId="user-delete-account",
     *      tags={"User"},
     *      summary="User Delete Account",
     *      description="User Delete Account",
     *      @OA\Parameter(
     *         description="password",
     *         in="query",
     *         name="password",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
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
    public function deleteAccount(Request $request)
    {
        try {
            $input = $request->all();
            $user = AuthHelper::authenticatedUser();
            if (Hash::check($input[PASSWORD], $user->password)) {
                $user->deleted_at = Carbon::now();
                $user->status_id = DELETED;
                $user->deleted_by = TWO;
                $user->save();
                dispatch(new SendDeactiveDeleteUserJob($user->id, DELETED));
                dispatch(new UpdateStatusOnFirebaseJob($user, DELETED, STATUS_ID));
                $response = response()->Success(__('messages.account_delete_success'));
            } else {
                $response = response()->Error(__('messages.password_does_not_match'));
            }
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
}
