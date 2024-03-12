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
        $data = JWTAuth::decode(JWTAuth::getToken())->toArray();
        $user = User::where([ID => JWTAuth::parseToken()->authenticate()->id])->first();
        $isPasswordUpdated = ($data['iat'] < strtotime($user->password_updated)) ? true :false;
        if($user->status_id == DELETED || $user->status_id == INACTIVE || $isPasswordUpdated){
            JWTAuth::invalidate(JWTAuth::parseToken());
            FcmService::deactivateRegisterDevice($user->id, false, true);
            $message = $isPasswordUpdated ? trans('messages.logout_from_other_device_on_pwd_change') : CustomHelper::getDeleteInactiveMsg($user);
            return response()->json([DATA =>new \stdClass(), MESSAGE => $message], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
