<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Facades\{
    App\Services\SubscriptionService,
};
use DB;
use App\Helpers\AuthHelper;
use Log;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Subscription;
use App\Models\User;
use App\Models\ParentsPreference;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
     /**
     * @OA\Get(
     *      path="/v1/subscription-plan",
     *      operationId="get-subscription-plan",
     *      tags={"Subscription"},
     *      summary="Get Subscription plan",
     *      description="Get Subscription plan",
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
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
    public function getPlan(Request $request)
    {
        try {
            $userId = AuthHelper::authenticatedUser()->id;
            $upcoming = Subscription::with(['subscriptionPlan'])->select('subscriptions.id','subscriptions.user_id','subscriptions.subscription_plan_id','subscriptions.current_period_start','subscriptions.current_period_end')->where(STATUS_ID,ACTIVE)->where(USER_ID,$userId)->orderBY(ID,DESC)->first();
            $current = Subscription::with(['subscriptionPlan'])->select('subscriptions.id','subscriptions.user_id','subscriptions.subscription_plan_id','subscriptions.current_period_start','subscriptions.current_period_end')->where(ID,'!=',$upcoming->id)->where(USER_ID,$userId)->where(CURRENT_PERIOD_START, '<=', Carbon::now())
            ->where(CURRENT_PERIOD_END,'>=', Carbon::now())->orderBY(ID,DESC)->first();
            $currentSubscription = !empty($current) ? $current : $upcoming;
            $upcomingSubscription  = !empty($upcoming) && !empty($current) && ($upcoming->subscription_plan->role_id_looking_for === $current->subscription_plan->role_id_looking_for) ? $upcoming : null;
            $preference = ParentsPreference::where(USER_ID, $userId)->first();
            $response = response()->Success(trans('messages.common_msg.data_found'),['plan' =>  SubscriptionService::getSubscriptionPlan(),'subscription' => $currentSubscription,'preference' => $preference, 'upcomingSubscription' => $upcomingSubscription]);
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Post(
     *      path="/v1/create-subscription",
     *      operationId="create-subscription",
     *      tags={"Subscription"},
     *      summary="Create stripe subscription",
     *      description="Create stripe subscription",
     *      @OA\RequestBody(
     *        required = true,
     *        description = "User profile register",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="device_type",
     *                type="string",
     *                example="android"
     *             ),
     *             @OA\Property(
     *                property="product_id",
     *                type="string",
     *                example="monthly"
     *             ),
     *             @OA\Property(
     *                property="purchase_token",
     *                type="string",
     *                example="abcd"
     *             ),
     *         ),
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Subscription has been created successfully.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
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
    public function createSubscription(SubscriptionRequest $request)
    {
        try {
            Log::info("subscription api calling");
            DB::beginTransaction();
            $subscription = SubscriptionService::createSubscription($request->all(),AuthHelper::authenticatedUser()->id);
            DB::commit();
            $response = response()->Success(trans('messages.subscription_created'));
            if(!empty($subscription[CODE])) {
                $response = response()->Error($subscription[MESSAGE]);
            }
        } catch (\Exception $e) {
            DB::rollback($e->getMessage());
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

     /**
     * @OA\Get(
     *      path="/v1/subscription-status",
     *      operationId="get-subscription-status",
     *      tags={"Subscription"},
     *      summary="Get Subscription status",
     *      description="Get Subscription status",
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
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
    public function getSubscriptionStatus(Request $request)
    {
        try {
            $userId = AuthHelper::authenticatedUser()->id;
            $user = User::where(ID,$userId)->first();
            $subscription = Subscription::where(USER_ID,$userId)->orderBY(ID,DESC)->first();
            $isTrial = empty($subscription) ?  true : false;
            $trial_end =  !empty($user->trial_start) ? date(YMD_FORMAT, strtotime(SUBSCRIPTION_TRIAL_PERIOD, strtotime($user->trial_start))) : null;
            $trial_msg = 'Your free trial period expires on';
            $response = response()->Success(trans('messages.common_msg.data_found'), [STATUS => $user->subscription_status,'is_trial' => $isTrial , 'trial_end' => $trial_end,'trial_msg' => $trial_msg]);
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

}
