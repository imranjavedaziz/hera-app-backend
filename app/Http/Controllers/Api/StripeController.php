<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\AuthHelper;
use App\Models\User;
use Facades\{
    App\Services\StripeService
};

class StripeController extends Controller
{
     /**
     * @OA\Get(
     *      path="/v1/account-status",
     *      operationId="account-status",
     *      tags={"Stripe"},
     *      summary="Stripe account status",
     *      description="Stripe acount status",
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=417,
     *          description="Expectation Failed"
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
    public function getAccountStatus(Request $request)
    {
        try {
            $user = User::where(ID,AuthHelper::authenticatedUser()->id)->first();
            $accountStatus = StripeService::retrieveAccountStatus($user->connected_acc_token);
            $status = 0;
            if (!empty($accountStatus['capabilities']['transfers']) && $accountStatus['capabilities']['transfers'] == 'active') {
                $status = 1;
            }
            $user->connected_acc_status = $status;
            $user->save();
            $response = response()->Success(SUCCESS, [STATUS => $status]);
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
}
