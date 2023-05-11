<?php

namespace App\Services;

use App\Models\PaymentRequest;
use App\Models\ProfileMatch;
use App\Models\User;
use App\Models\Transaction;
use App\Jobs\PaymentNotification;

class PaymentService
{
    public function getUsersByProfileMatchAndKeyword($user_id, $keyword = false) {
        $users = User::whereIn(ID, function ($query) use ($user_id) {
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
    
        return $users;
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
            $description = $user->role->name .' '. $user->first_name. ' sent you a payment request of amount '. $input[AMOUNT];
            $input[USER_ID] = $user_id;
            PaymentNotification::dispatch($title, $description, $input, $notifyType);
            return [SUCCESS => true, DATA => $paymentRequest];
        }
        return [SUCCESS => false];
    }

    public function getPaymentRequestList($user) {
        if ($user->role_id == PARENTS_TO_BE) {
            return PaymentRequest::with(['donar'])->where(TO_USER_ID, $user->id)->orderBy(ID, DESC);
        } else {
            return PaymentRequest::with(['ptb'])->where(FROM_USER_ID, $user->id)->orderBy(ID, DESC);
        }
    }

    public function updatePaymentRequestStatus($input) {
        $paymentRequest = PaymentRequest::where(ID, $input[PAYMENT_REQUEST_ID])->first();
        $user =  User::where(ID, $paymentRequest->to_user_id)->first();
        $input[USER_ID] = $paymentRequest->to_user_id;
        $input[TO_USER_ID] = $paymentRequest->from_user_id;
        $input[AMOUNT] = $paymentRequest->amount;
        if ($input[STATUS] == TWO) {
            $notifyType = 'payment_declined';
            $title = 'Payment Declined!';
            $description = $user->role->name .' '. $user->first_name. ' declined payment request of amount '. $input[AMOUNT];
        } else {
            $notifyType = 'payment_transfer';
            $title = 'Payment already paid!';
            $description = $user->role->name .' '. $user->first_name. ' already paid amount '. $input[AMOUNT];
        }
        $paymentRequest->status = $input[STATUS];
        $paymentRequest->save();
        PaymentNotification::dispatch($title, $description, $input, $notifyType);
        return true;
    }

    public function checkPaymentRequestBelongToPtb($input, $userId) {
        return PaymentRequest::where([ID => $input[PAYMENT_REQUEST_ID], TO_USER_ID => $userId])->first();
    }

    public function getPtbTransactionHistoryList($userId) {
        return Transaction::selectRaw('transactions.id,transactions.payment_intent,transactions.amount,transactions.net_amount,transactions.payment_status,transactions.brand,transactions.last4,transactions.created_at,users.username, users.profile_pic')
            ->join('users', 'users.connected_acc_token', '=', 'transactions.account_id')
            ->where([USER_ID => $userId, PAYMENT_TYPE => ONE])
            ->groupBy('transactions.id')
            ->orderBy(ID, DESC);
    }

    public function getDonarTransactionHistoryList($accountId) {
        return Transaction::selectRaw('transactions.id,transactions.payment_intent,transactions.amount,transactions.net_amount,transactions.payment_status,transactions.bank_name,transactions.bank_last4,transactions.created_at,users.username, users.profile_pic')
            ->join('users', 'users.id', '=', 'transactions.user_id')
            ->where([ACCOUNT_ID => $accountId, PAYMENT_TYPE => ONE])
            ->groupBy('transactions.id')
            ->orderBy(ID, DESC);
    }
    
}
