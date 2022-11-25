<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Facades\{
    App\Services\FcmService,
};
use App\Helpers\CustomHelper;
use App\Models\User;

class CheckUserAccountStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = User::where([ID => JWTAuth::parseToken()->authenticate()->id])->first();
            if($user->status_id == 5 || $user->deactivated_by == 1){
                JWTAuth::invalidate(JWTAuth::parseToken());
                FcmService::deactivateRegisterDevice($user->id);
                $message = CustomHelper::getDeleteInactiveMsg($user);
                return response()->json(['data'=>new \stdClass(), MESSAGE => $message], HTTP_DELETED_ACCOUNT);
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
