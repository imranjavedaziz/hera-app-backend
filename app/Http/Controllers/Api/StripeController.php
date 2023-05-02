<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\AuthHelper;
use App\Models\User;
use Facades\{
    App\Services\StripeService
};
use App\Http\Requests\KycRequest;

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

    /**
     * @OA\Post(
     *      path="/v1/save-kyc-details",
     *      operationId="save-kyc-details",
     *      tags={"Stripe"},
     *      summary=" kyc details",
     *      description="Save kyc details",
     *      @OA\RequestBody(
     *        description = "Save kyc details",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(
     *                         @OA\Property(
     *                             description="Item image PNG/JPEG",
     *                             property="document_front",
     *                             type="string",
     *                             format="binary"
     *                         ),
     *                         @OA\Property(
     *                             description="Item image PNG/JPEG",
     *                             property="document_back",
     *                             type="string",
     *                             format="binary"
     *                         ),
     *                         @OA\Property(
     *                             property="first_name",
     *                             type="string",
     *                             example="jhon"
     *                         ),
     *                         @OA\Property(
     *                             property="last_name",
     *                             type="string",
     *                             example="Doe"
     *                         ),
     *                         @OA\Property(
     *                            property="dob_year",
     *                            type="string",
     *                            example="1980"
     *                        ),
     *                        @OA\Property(
     *                           property="dob_month",
     *                           type="string",
     *                           example="10"
     *                        ),
     *                        @OA\Property(
     *                            property="dob_day",
     *                            type="string",
     *                            example="15"
     *                         ),
     *                        @OA\Property(
     *                           property="address",
     *                           type="string",
     *                           example="123 Main St"
     *                        ),
     *                        @OA\Property(
     *                           property="city",
     *                           type="string",
     *                           example="Anytown"
     *                        ),
     *                        @OA\Property(
     *                           property="state",
     *                           type="string",
     *                           example="CA"
     *                       ),
     *                       @OA\Property(
     *                           property="postal_code",
     *                           type="string",
     *                           example="12345"
     *                      ),
     *                      @OA\Property(
     *                         property="ssn_last_4",
     *                         type="string",
     *                         example="1234"
     *                      ),
     *                     @OA\Property(
     *                         property="bank_token_id",
     *                         type="string",
     *                           example="ba_1N1t65KStVxXZYaxpiESHneS"
     *                      ),
     *                  )
     *                }
     *             )
     *         )
     *     ),
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
    public function saveKycDetails(KycRequest $request) {
        try {
            $result = StripeService::saveKycDetails(
                AuthHelper::authenticatedUser(),
                $request->all(),
                $request->ip()
            );
            $response = response()->Success(trans('messages.payment.save_kyc'), [DATA => $result]);
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
}
