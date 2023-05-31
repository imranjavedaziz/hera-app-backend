<?php

namespace App\Services;

use App\Models\PaymentRequest;
use App\Models\ProfileMatch;
use App\Models\User;
use App\Models\Transaction;
use App\Jobs\PaymentNotification;
use DB;
use App\Helpers\CustomHelper;

class PaymentService
{
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
    
}
