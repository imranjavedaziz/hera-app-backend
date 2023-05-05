<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\AuthHelper;
use Facades\{
    App\Services\PaymentService,
    App\Services\UserRegisterService,
};
use App\Http\Requests\UserPaymentRequest;
use App\Http\Requests\UploadDocumentRequest;
use App\Http\Requests\PaymentRequestStatusRequest;
use DB;

class PaymentController extends Controller
{
    /**
     * @OA\Get(
     *      path="/v1/match-list",
     *      operationId="match-list",
     *      tags={"Payment"},
     *      summary="Get profile match list",
     *      description="Get profile match list",
     *      @OA\Parameter(
     *          name="keyword",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
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
    public function getMatchList(Request $request)
    {
        try {
            $limit = isset($request->limit) && ($request->limit > ZERO) ? $request->limit : DASHBOARD_PAGE_LIMIT;
            $matchList = PaymentService::getUsersByProfileMatchAndKeyword(AuthHelper::authenticatedUser()->id, $request->keyword);
            $response = response()->Success(trans('messages.common_msg.data_found'), $matchList->paginate($limit));
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Post(
     *     path="/v1/payment-request",
     *     description="User make payment request",
     *     operationId="user-payment-request",
     *     tags={"Payment"},
     *     summary="User payment request",
     *     @OA\RequestBody(
     *        required = true,
     *        description = "User payment request",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="to_user_id",
     *                type="integer",
     *                example=1
     *             ),
     *             @OA\Property(
     *                property="amount",
     *                type="float",
     *                example=500
     *             ),
     *             @OA\Property(
     *                property="doc_url",
     *                type="string",
     *                example="https://mbc-dev-kiwitech.s3.amazonaws.com/chat/documents/BAhVUNjoIiwM81TNc3NdkNCVjxeU6GyyPRP8C30l.jpg"
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *     ),
     *     @OA\Response(
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
    public function paymentRequest(UserPaymentRequest $request)
    {
        try {
            DB::beginTransaction();
            $paymentRequest = PaymentService::savePaymentRequest(AuthHelper::authenticatedUser()->id, $request->all());
            DB::commit();
            if ($paymentRequest[SUCCESS]) {
                $response = response()->Success(trans('messages.payment.payment_request'), $paymentRequest[DATA]);
            } else {
                $response = response()->Error(trans(LANG_SOMETHING_WRONG));
            }
        } catch (\Exception $e) {
            DB::rollback();
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

     /**
     * @OA\Post(
     *      path="/v1/upload-payment-doc",
     *      operationId="upload-payment-doc",
     *      tags={"Payment"},
     *      summary="Upload payment document",
     *      description="Upload payment document.",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(
     *                         @OA\Property(
     *                             description="Item image/Document",
     *                             property="file",
     *                             type="string",
     *                             format="binary"
     *                         )
     *                     )
     *                 }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Request successfully completed.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=417,
     *          description="Expectation Failed"
     *      ),
     *      @OA\Response(
     *          response=409,
     *          description="Conflict",
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *     security={ {"bearer": {}} },
     *  )
     */
    public function uploadPaymentDocument(UploadDocumentRequest $request)
    {
        try {
            $uploadDocument = UserRegisterService::uploadFile($request->all(),'payment/documents');
            $response = response()->Success(SUCCESS, $uploadDocument);
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Get(
     *      path="/v1/payment-request-list",
     *      operationId="payment-request-list",
     *      tags={"Payment"},
     *      summary="Get payment request list",
     *      description="Get payment request list",
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
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
    public function getPaymentRequestList(Request $request)
    {
        try {
            $limit = isset($request->limit) && ($request->limit > ZERO) ? $request->limit : DASHBOARD_PAGE_LIMIT;
            $paymentRequestList = PaymentService::getPaymentRequestList(AuthHelper::authenticatedUser());
            $response = response()->Success(trans('messages.common_msg.data_found'), $paymentRequestList->paginate($limit));
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Post(
     *      path="/v1/payment-request-status",
     *      operationId="update-payment-request-status",
     *      tags={"Payment"},
     *      summary="Update payment request status",
     *      description="Update payment request status",
     *      @OA\RequestBody(
     *        required = true,
     *        description = "Update booking status 2- > Invalid request, 3- Already paid",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="payment_request_id",
     *                type="integer",
     *                example="1"
     *             ),
     *             @OA\Property(
     *                property="status",
     *                type="integer",
     *                example="2"
     *             )
     *         ),
     *     ),
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
    public function updatePaymentRequestStatus(PaymentRequestStatusRequest $request)
    {
        try {
            if (!PaymentService::checkPaymentRequestBelongToPtb($request->all(), AuthHelper::authenticatedUser()->id)) {
                return response()->Error(trans('messages.payment.invalid_request'));
            }
            PaymentService::updatePaymentRequestStatus($request->all());
            $response = response()->Success(trans('messages.payment.request_rejected'));
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
}
