<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Subscription;

class EnsureParentsSubscriptionIsActive
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
        if ($user->role_id == PARENTS_TO_BE &&  $user->subscription_status == SUBSCRIPTION_DISABLED && $user->registration_step == THREE) {
            $subscription = Subscription::where(USER_ID,$user->id)->orderBy('id','desc')->first();
            $message = !empty($subscription) ? trans('messages.subscription_expire') : trans('messages.trial_subscription_expire');
            return response()->json([DATA => [], MESSAGE => $message], 421);
        }
        return $next($request);
    }
}
