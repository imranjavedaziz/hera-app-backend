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
use Exception;
use Hash;

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
        $user = User::where([ID => JWTAuth::parseToken()->authenticate()->id])->first();
        if($user->status_id == FIVE || $user->deactivated_by == ONE){
            JWTAuth::invalidate(JWTAuth::parseToken());
            FcmService::deactivateRegisterDevice($user->id);
            $message = CustomHelper::getDeleteInactiveMsg($user);
            return response()->json(['data'=>new \stdClass(), MESSAGE => $message], HTTP_DELETED_ACCOUNT);
        }
        return $next($request);
    }
}
