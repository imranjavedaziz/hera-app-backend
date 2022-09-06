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
use App\Helpers\TwilioOtp;
use App\Helpers\AuthHelper;
use Log;

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
            ];
            $user = User::where([PHONE_NO => $request->phone_no, COUNTRY_CODE => $request->country_code])->first();
            if (empty($user)) {
                return response()->Error(trans('messages.invalid_user_phone'));
            }
            if ($oauth_token = JWTAuth::attempt($user_credentials)) {
                $user->access_token = $oauth_token;
                $response = response()->Success(trans('messages.logged_in'), $user);
            } else {
                $response = response()->Error(trans('messages.invalid_user_pass'));
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
            $phoneExits = User::where([COUNTRY_CODE => $request->country_code, PHONE_NO => $request->phone_no])->count();
            if ($phoneExits > ZERO) {
                return response()->Error(__('messages.phone_already_exists'));
            } else {
                $result = TwilioOtp::sendOTPOnPhone($request->country_code, $request->phone_no);
                if($result[STATUS]) {
                    $response = response()->Success($result[MESSAGE]);
                } else {
                    $response = response()->Error($result[MESSAGE]);
                }
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
                $response = response()->json([MESSAGE => 'Token is Invalid.'], Response::HTTP_UNAUTHORIZED);
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                $response = response()->json([MESSAGE => 'Token is Expired.'], Response::HTTP_UNAUTHORIZED);
            } else {
                $response = response()->json([MESSAGE => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
            }
        }

        return $response;
    }
}
