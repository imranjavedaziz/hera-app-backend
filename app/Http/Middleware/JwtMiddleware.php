<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use App\Models\User;

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
            $headers = apache_request_headers();
            $request->headers->set('Authorization', $headers['Authorization']);
            if (JWTAuth::parseToken()->authenticate() == null) {
                return response()->json([MESSAGE => 'Your session has expired. Please login again.'], Response::HTTP_FORBIDDEN);
            }
            User::where([ID => JWTAuth::parseToken()->authenticate()->id])->update([RECENT_ACTIVITY => Date(DATE_TIME)]);
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                $response = response()->json([MESSAGE => 'Your session has expired. Please login again.'], Response::HTTP_FORBIDDEN);
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                $response = response()->json([MESSAGE => 'Token is expired.'], Response::HTTP_UNAUTHORIZED);
            } else {
                $response = response()->json([MESSAGE => 'Authorization token not found.'], Response::HTTP_FORBIDDEN);
            }
            return $response;
        }
        return $next($request);
    }
}
