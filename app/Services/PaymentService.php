<?php

namespace App\Services;

use App\Models\PaymentRequest;
use App\Models\ProfileMatch;
use App\Models\User;
use App\Models\Transaction;
use App\Jobs\PaymentNotification;
use DB;
use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\Http;

class PaymentService
{
    private const HOSTED_PAYMENTS_GATEWAY_BASE_URL = 'https://dev-paynow.itscocloud.com';
    private const HOSTED_PAYMENTS_EXECUTE_URL = self::HOSTED_PAYMENTS_GATEWAY_BASE_URL . '/execute';
    private const HOSTED_PAYMENTS_RESPONSE_URL = self::HOSTED_PAYMENTS_GATEWAY_BASE_URL . '/Response';

    public function forwardRequest() {
        $ips = '123';
        $returnQueryParams = [
            'IPS' => $ips
        ];
        $returnUrl = self::createUrlWithQueryParams(self::HOSTED_PAYMENTS_RESPONSE_URL, $returnQueryParams);
        $queryParams = [
            'isTest' => '1',
            'ReturnURL' => $returnUrl,
            'AutoReturn' => '1',
            'License' => '',
            'AccountToken' => 'C8E33719BEC41A4EA07B9E09681ED1323B9074E1651209203BE4C24452E884B4DD848601',
            'AcceptorID' => '364800780',
            'AccountID' => '1217395',
            'TransactionSetup' => '1',
            'Amount' => '16.00',
            'ReferenceNumber' => '001',
            'TerminalID' => '002',
            'LaneNumber' => '003',
            'BillingAddress1' => '100',
            'BillingZipcode' => '33606',
            'CVVRequired' => '1'
        ];
        echo("defined query params\n");

        // Send the POST request to the external URL
        $requestUrl = self::createUrlWithQueryParams(self::HOSTED_PAYMENTS_EXECUTE_URL, $queryParams);
        echo("POST $requestUrl\n");
        $response = Http::post($requestUrl);

        // Check if the request was successful
        if ($response->successful()) {
            echo("got successful response from worldpay\n");
            return response($response->body(), 200)
                ->header('Content-Type', 'text/html');
//            return response()->json($response->json(), $response->status());
        }

        // Handle the error
        return response()->json([
            'error' => 'Failed to fetch from external API'
        ], $response->status());

    }

    public function pollTransactionResult() {
        $queryParams = [
            'IPS' => '123',
            'Response' => 'Y'
        ];

        $request_url = createUrlWithQueryParams(self::HOSTED_PAYMENTS_RESPONSE_URL, $queryParams);
        $response = Http::get($request_url);

        // Check if the request was successful
        if ($response->successful()) {
            echo("got transaction result from worldpay\n");
            return response($response->body(), 200)
                ->header('Content-Type', 'text/html');
//            return response()->json($response->json(), $response->status());
        }

        // Handle the error
        return response()->json([
            'error' => 'Failed to fetch from external API'
        ], $response->status());
    }

    public function getUsersByProfileMatchAndKeyword($user_id, $keyword = false) {
        return User::whereIn(ID, function ($query) use ($user_id) {
                $query->select(FROM_USER_ID)
                    ->from('profile_matches')
                    ->where(function ($query) use ($user_id) {
                        $query->where(TO_USER_ID, $user_id)
                            ->orWhere(FROM_USER_ID, $user_id);
                    })
                    ->where(STATUS, APPROVED_AND_MATCHED)
                    ->union(
                        ProfileMatch::select(TO_USER_ID)
                            ->where(FROM_USER_ID, $user_id)
                            ->where(STATUS, APPROVED_AND_MATCHED)
                    );
            })
            ->where(ID, '!=', $user_id)
            ->where(function ($query) use ($keyword) {
                $query->where(function($query) use ($keyword) {
                    $query->whereRaw("CONCAT_WS(' ', FIRST_NAME, MIDDLE_NAME, LAST_NAME) LIKE ?", ["%{$keyword}%"]);
                })
                ->orWhere(USERNAME, 'like', "%{$keyword}%");
            })
            ->orderBy(FIRST_NAME, ASC);
    }

    public function savePaymentRequest($user_id, $input) {
        $paymentRequest = new PaymentRequest();
        $paymentRequest->from_user_id = $user_id;
        $paymentRequest->to_user_id = $input[TO_USER_ID];
        $paymentRequest->amount = $input[AMOUNT];
        $paymentRequest->doc_url = $input[DOC_URL];
        if($paymentRequest->save()){
            $user =  User::where(ID, $user_id)->first();
            $notifyType = 'payment_request';
            $title = 'Payment Request!';
            $description = 'A new payment request of $'. number_format($input[AMOUNT],2).' from '. $user->username;
            $input[USER_ID] = $user_id;
            $input[FIRST_NAME] = $user->first_name;
            $input[ROLE] = $user->role->name;
            $input[USERNAME] = $user->username;
            $input[PAYMENT_REQUEST_ID] = $paymentRequest->id;
            PaymentNotification::dispatch($title, $description, $input, $notifyType);
            return [SUCCESS => true, DATA => $paymentRequest];
        }
        return [SUCCESS => false];
    }

    public function getPaymentRequestList($user) {
        if ($user->role_id == PARENTS_TO_BE) {
            return PaymentRequest::with(['donar'])
            ->leftJoin('transactions', 'transactions.payment_request_id', '=', PAYMENT_REQUESTS.'.'.ID)
            ->leftJoin(PAYOUTS, PAYOUTS.'.'.ID, '=', TRANSACTIONS.'.'.PAYOUT_ID)
            ->where(TO_USER_ID, $user->id)
            ->orderBy(PAYMENT_REQUESTS.'.'.ID, DESC)
            ->select(DB::raw('DISTINCT payment_requests.*'), DB::raw('COALESCE(payouts.status, 1) as payout_status'));

        } else {
            return PaymentRequest::with(['ptb'])->leftJoin('transactions', 'transactions.payment_request_id', '=', PAYMENT_REQUESTS.'.'.ID)
            ->leftJoin(PAYOUTS, PAYOUTS.'.'.ID, '=', TRANSACTIONS.'.'.PAYOUT_ID)
            ->where(FROM_USER_ID, $user->id)
            ->orderBy(PAYMENT_REQUESTS.'.'.ID, DESC)
            ->select(DB::raw('DISTINCT payment_requests.*'), DB::raw('COALESCE(payouts.status, 1) as payout_status'));
        }
    }

    public function updatePaymentRequestStatus($input) {
        $paymentRequest = PaymentRequest::where(ID, $input[PAYMENT_REQUEST_ID])->first();
        $user =  User::where(ID, $paymentRequest->to_user_id)->first();
        $input[USER_ID] = $paymentRequest->to_user_id;
        $input[TO_USER_ID] = $paymentRequest->from_user_id;
        $input[AMOUNT] = $paymentRequest->amount;
        $input[FIRST_NAME] = CustomHelper::fullName($user);
        $input[ROLE] = $user->role->name;
        $input[USERNAME] = $user->username;
        if ($input[STATUS] == TWO) {
            $notifyType = 'payment_declined';
            $title = 'Payment Declined!';
            $description = $user->first_name. ' rejected your payment request.';
            PaymentNotification::dispatch($title, $description, $input, $notifyType);
        }
        $paymentRequest->status = $input[STATUS];
        $paymentRequest->save();
        return true;
    }

    public function checkPaymentRequestBelongToPtb($input, $userId) {
        return PaymentRequest::where([ID => $input[PAYMENT_REQUEST_ID], TO_USER_ID => $userId])->first();
    }

    public function getPtbTransactionHistoryList($userId) {
        return Transaction::selectRaw('transactions.id,transactions.payment_intent,transactions.amount,transactions.net_amount,transactions.payment_status,transactions.brand,transactions.last4,transactions.created_at,users.username, users.profile_pic,COALESCE(payouts.status, 1) as payout_status')
            ->join('users', 'users.connected_acc_token', '=', 'transactions.account_id')
            ->leftJoin(PAYOUTS, PAYOUTS.'.'.ID, '=', TRANSACTIONS.'.'.PAYOUT_ID)
            ->where(['transactions.user_id' => $userId, 'transactions.payment_type' => ONE])
            ->groupBy(TRANSACTIONS.'.'.ID)
            ->orderBy(TRANSACTIONS.'.'.ID, DESC);
    }

    public function getDonarTransactionHistoryList($accountId) {
        return Transaction::selectRaw('transactions.id,transactions.payment_intent,transactions.amount,transactions.net_amount,transactions.payment_status,transactions.bank_name,transactions.bank_last4,transactions.created_at,CONCAT_WS(" ", users.first_name, users.middle_name, users.last_name) as username, users.profile_pic,COALESCE(payouts.status, 1) as payout_status')
            ->join('users', 'users.id', '=', 'transactions.user_id')
            ->leftJoin(PAYOUTS, PAYOUTS.'.'.ID, '=', TRANSACTIONS.'.'.PAYOUT_ID)
            ->where(['transactions.account_id'=> $accountId, 'transactions.payment_type' => ONE])
            ->where('payouts.status' ,'!=', ONE)
            ->groupBy(TRANSACTIONS.'.'.ID)
            ->orderBy(TRANSACTIONS.'.'.ID, DESC);
    }

    private function createUrlWithQueryParams($baseUrl, $queryParams) {
        return $baseUrl . '?' . http_build_query($queryParams);
    }
}
