<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\AuthHelper;
use Facades\{
    App\Services\PaymentService,
    App\Services\UserRegisterService,
    App\Services\StripeService
};
use App\Http\Requests\UserPaymentRequest;
use App\Http\Requests\UploadDocumentRequest;
use App\Http\Requests\PaymentRequestStatusRequest;
use DB;
use App\Models\User;
use Illuminate\Support\Facades\Log;

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
            $record = PaymentService::getUsersByProfileMatchAndKeyword(AuthHelper::authenticatedUser()->id);
            $response = response()->Success(trans(MESSAGE_DATA_FOUND), [DATA => $matchList->paginate($limit), 'record' => $record->count()]);
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
            $uploadDocument = UserRegisterService::uploadFile($request->all(), 'payment/documents');
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
            $response = response()->Success(trans(MESSAGE_DATA_FOUND), $paymentRequestList->paginate($limit));
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
            $msg = ($request->status == TWO) ? trans('messages.payment.request_rejected') : trans('messages.payment.request_already_paid');
            $response = response()->Success($msg);
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Post(
     *     path="/v1/payment-transfer",
     *     description="User make payment transfer",
     *     operationId="user-payment-transfer",
     *     tags={"Payment"},
     *     summary="User payment transfer",
     *     @OA\RequestBody(
     *        required = true,
     *        description = "User payment transfer",
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
     *                property="net_amount",
     *                type="float",
     *                example=515.24
     *             ),
     *             @OA\Property(
     *                property="payment_method_id",
     *                type="string",
     *                example="pm_1N4ScRGDXbU7wJmtK1BWMhem"
     *             ),
     *             @OA\Property(
     *                property="payment_request_id",
     *                type="integer",
     *                example=1
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
    public function paymentTransfer(UserPaymentRequest $request)
    {
        try {
            $input = $request->all();
            $user = User::where(ID, $input[TO_USER_ID])->first();
            $input[ACCOUNT_ID] = $user->connected_acc_token;
            $input[BANK_ACCOUNT_TOKEN] = $user->bank_acc_token;
            $input[USER_ID] = AuthHelper::authenticatedUser()->id;
            $input[STRIPE_CUSTOMER_ID] = AuthHelper::authenticatedUser()->stripe_customer_id;
            $paymentTransfer = StripeService::createPaymentIntent($input);
            if ($paymentTransfer[SUCCESS]) {
                $response = response()->Success(trans('messages.payment.payment_transfer'), $paymentTransfer[DATA]);
            } else {
                $response = response()->Error($paymentTransfer[MESSAGE]);
            }
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    public function forwardRequest(Request $request)
    {
        return PaymentService::forwardRequest();
    }

    public function makePayment(Request $request)
    {
        $ips = $request->query("ips");
        Log::info("ips:" . $ips);
        return PaymentService::makePayment($ips);
    }

    public function pollTransactionResult(Request $request)
    {
        $ips = $request->query("ips");
        Log::info("ips:" . $ips);
        return PaymentService::pollTransactionResult($ips);
    }


    public function createDonorPaymentPage(Request $request){
        $paymentRequestId = $request->paymentRequestId;
        Log::info("paymentRequestId:" . $paymentRequestId);
        return PaymentService::createDonorPaymentPage($paymentRequestId);
    }

    public function makeDonorPayment(Request $request)
    {
        $ips = $request->query("ips");
        Log::info("ips:" . $ips);
        return PaymentService::makeDonorPayment($ips);
    }

    public function pollDonorTransactionResult(Request $request)
    {
        $ips = $request->query("ips");
        Log::info("ips:" . $ips);
        return PaymentService::pollDonorTransactionResult($ips);
    }

    /**
     * @OA\Get(
     *      path="/v1/transaction-history",
     *      operationId="transaction-history",
     *      tags={"Payment"},
     *      summary="Get payment transaction history",
     *      description="Get payment transaction history",
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
    public function getTransactionHistoryList(Request $request)
    {
        try {
            $limit = isset($request->limit) && ($request->limit > ZERO) ? $request->limit : DASHBOARD_PAGE_LIMIT;
            $user = AuthHelper::authenticatedUser();
            if ($user->role_id === PARENTS_TO_BE) {
                $transactionHistory = PaymentService::getPtbTransactionHistoryList($user->id);
            } else {
                $transactionHistory = PaymentService::getDonarTransactionHistoryList($user->connected_acc_token);
            }
            $response = response()->Success(trans(MESSAGE_DATA_FOUND), $transactionHistory->paginate($limit));
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
}