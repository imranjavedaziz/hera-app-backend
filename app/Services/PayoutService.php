<?php

namespace App\Services;

use Carbon\Carbon;
use Log;
use App\Constants\PayoutStatus;
use App\Models\User;
use App\Mail\DonarPayoutMail;
use App\Mail\PtbPayoutMail;
use Mail;
use App\Jobs\PaymentNotification;

class PayoutService
{
    public function processPayout($pendingPayout, $stripeService, $transaction) {
        if ($pendingPayout[DONOR] && $pendingPayout[DONOR][CONNECTED_ACC_TOKEN]) {
            Log::info("Tranfering amount to donor " . $pendingPayout[USER_ID]);
            $tranfer = false;
            $tranfer =  $stripeService->tranferFund($pendingPayout[DONOR][CONNECTED_ACC_TOKEN], $pendingPayout->amount);
            if ($tranfer && $tranfer[SUCCESS]) {
                $this->savePayoutData($pendingPayout, $tranfer, $stripeService, $transaction);
            } else {
                Log::info("Tranfer failed for donor " . $pendingPayout[USER_ID]);
                Log::info("Details of  Failed transfer fund : ". $tranfer[DATA]);
            }
        } else {
            Log::info("Details missing of donor " . $pendingPayout[USER_ID]);
        }
        return true;
    }

    private function savePayoutData($pendingPayout, $tranfer, $stripeService, $transaction) {
        Log::info("Tranfered amount to donor " . $pendingPayout[USER_ID]);
        $pendingPayout->transfer_txn_id = $tranfer[DATA][ID] ?? null;
        $pendingPayout->payout_date = Carbon::now();
        $pendingPayout->status = PayoutStatus::PENDING;
        $pendingPayout->save();
        $payoutConnectedAcc =  $stripeService->payOutToDonor($pendingPayout[DONOR][CONNECTED_ACC_TOKEN], $pendingPayout->amount);
        $donor = User::find($pendingPayout[USER_ID]);
        $ptb = User::find($transaction[USER_ID]);
        $donarData = $this->getMailData($donor, $ptb, $transaction);
        $ptbData = $this->getMailData($ptb, $donor, $transaction);
        if ($payoutConnectedAcc[SUCCESS]) {
            $pendingPayout->payout_txn_id = $payoutConnectedAcc[DATA][ID] ?? null;
            $pendingPayout->status = PayoutStatus::PAID;
            $pendingPayout->payout_date = Carbon::now();
            $pendingPayout->save();
            Log::info("Payout complete for donor " .$pendingPayout[USER_ID]);
            if($donor) {
                Mail::to($donor->email)->send(new DonarPayoutMail($donarData , true));
                Mail::to($ptb->email)->send(new PtbPayoutMail($ptbData, true));
                $notifyType = 'payment_transfer';
                $title = 'Payment Transfer!';
                $description = $ptb->first_name.' sent you a payment of $'. number_format($transaction[AMOUNT],2);
                $input[USER_ID] = $ptb->id;
                $input[TO_USER_ID] = $donor->id;
                PaymentNotification::dispatch($title, $description, $input, $notifyType);
            }
        } else {
            $pendingPayout->status = PayoutStatus::FAILED;
            $pendingPayout->error_code = $payoutConnectedAcc[CODE];
            $pendingPayout->error_message = $payoutConnectedAcc[MESSAGE];
            $pendingPayout->save();
            $donarData['error_message'] = $payoutConnectedAcc[MESSAGE];
            $ptbData['error_message'] = $payoutConnectedAcc[MESSAGE];
            Log::info("Payout failed for donor " . $pendingPayout[USER_ID]);
            Log::info("Payout failed msg " . $pendingPayout[USER_ID]);
            Mail::to($donor->email)->send(new DonarPayoutMail($donarData , false));
            Mail::to($ptb->email)->send(new PtbPayoutMail($ptbData, false));
        }
        return true;
    }

    private function getMailData($toUser, $fromUser, $transaction) {
        return [
            PAYMENT_INTENT_ID => $transaction[PAYMENT_INTENT],
            AMOUNT => $transaction[AMOUNT],
            NET_AMOUNT => $transaction[NET_AMOUNT],
            'fee' => $transaction[NET_AMOUNT] - $transaction[AMOUNT],
            BANK_LAST4 => $transaction[BANK_LAST4],
            BANK_NAME => $transaction[BANK_NAME],
            'last4' => $transaction['last4'],
            'to_user_first_name' => $toUser->first_name,
            'first_name' => $fromUser->first_name,
            'role' => $fromUser->role->name,
            'username' => ($fromUser->role_id == PARENTS_TO_BE) ? $fromUser->first_name : $fromUser->username,
            'transaction_date' =>Carbon::now(DEFAULT_TIMEZONE)->format('M d, Y'),
            'transaction_time' =>Carbon::now(DEFAULT_TIMEZONE)->format('h:i a (T)'),
        ];
    }
}
