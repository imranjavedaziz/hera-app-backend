<?php

namespace App\Services;

use App\Models\PaymentRequest;
use App\Models\ProfileMatch;
use App\Models\User;

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
                $query->where(USERNAME, 'like', "%{$keyword}%");
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
        return PaymentRequest::where(ID, $input[PAYMENT_REQUEST_ID])->update([STATUS => $input[STATUS]]);
    }

    public function checkPaymentRequestBelongToPtb($input, $userId) {
        return PaymentRequest::where([ID => $input[PAYMENT_REQUEST_ID], TO_USER_ID => $userId])->first();
    }
}
