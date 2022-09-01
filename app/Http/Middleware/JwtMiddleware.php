<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            if (JWTAuth::parseToken()->authenticate()==NULL) {
                return response()->json([MESSAGE => 'Token is Expired, Please login again.'], Response::HTTP_FORBIDDEN);
            }
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                $response = response()->json([MESSAGE => 'Token is Invalid.'], Response::HTTP_UNAUTHORIZED);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                $response = response()->json([MESSAGE => 'Token is Expired.'], Response::HTTP_UNAUTHORIZED);
            } else {
                $response = response()->json([MESSAGE => 'Authorization Token not found.'], Response::HTTP_UNAUTHORIZED);
            }
            return $response;
        }
        return $next($request);
    }
}