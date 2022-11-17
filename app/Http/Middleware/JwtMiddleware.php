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
            if (JWTAuth::parseToken()->authenticate()==NULL) {
                return response()->json([MESSAGE => 'Token is Expired, Please login again.'], Response::HTTP_FORBIDDEN);
            }
            $user = JWTAuth::parseToken()->authenticate();
            if($user->status_id == DELETED || $user->deactivated_by == 1){
                JWTAuth::invalidate(JWTAuth::parseToken());
                return response()->json([MESSAGE => 'Your account has been deactivated/deleted by Admin. Please contact Admin to get it re-activated.'], HTTP_DELETED_ACCOUNT);
            }
            User::where([ID => JWTAuth::parseToken()->authenticate()->id])->update([RECENT_ACTIVITY => Date(DATE_TIME)]);
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