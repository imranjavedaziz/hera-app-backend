<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;

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
     *                property="email",
     *                type="string",
     *                example="johan@gmail.com"
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
                EMAIL => strtolower($request->email),
                PASSWORD => $request->password,
            ];
            if ($oauth_token = JWTAuth::attempt($user_credentials)) {
                $user = User::checkUser(EMAIL, strtolower($request->email));
                if($user->is_email_verified === ZERO) {
                    $response = response()->Error(trans('messages.email_not_verify'));
                } else {
                    $user->access_token = $oauth_token;
                    $response = response()->Success(trans('messages.logged_in'), $user);
                }
            } else {
                $response = response()->Error(trans('messages.invalid_user_pass'));
            }
        } catch (JWTException $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
}
