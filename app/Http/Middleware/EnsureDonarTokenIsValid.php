<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;

class EnsureDonarTokenIsValid
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
        $user = JWTAuth::parseToken()->authenticate();
        if (!in_array($user->role_id,[SURROGATE_MOTHER, EGG_DONER, SPERM_DONER])) {
            return response()->json(['data'=>new \stdClass(),MESSAGE => trans('messages.access_denied')], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}
