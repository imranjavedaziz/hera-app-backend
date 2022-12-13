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
            $response = response()->Success(trans('messages.common_msg.data_found'), SubscriptionService::getSubscriptionPlan());
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
            $isTrial = ($user->subscription_status == SUBSCRIPTION_TRIAL) ?  true : false;
            $trial_end = ($user->subscription_status == SUBSCRIPTION_TRIAL) ?  date(YMD_FORMAT, strtotime(SUBSCRIPTION_TRIAL_PERIOD, strtotime($user->created_at))) : null;
            $trial_msg = 'Your free trial expires on';
            $response = response()->Success(trans('messages.common_msg.data_found'), [STATUS => $user->subscription_status,'is_trial' => $isTrial , 'trial_end' => $trial_end,'trial_msg' => $trial_msg]);
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

}
