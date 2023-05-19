<?php

namespace App\Services;

use Carbon\Carbon;
use Log;
use App\Constants\PayoutStatus;
use App\Models\User;

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
        $pendingPayout->transfer_txn_id = $tranfer[DATA][ID];
        $pendingPayout->payout_date = Carbon::now();
        $pendingPayout->status = PayoutStatus::PENDING;
        $pendingPayout->save();
        $payoutConnectedAcc =  $stripeService->payOutToDonor($pendingPayout[DONOR][CONNECTED_ACC_TOKEN], $pendingPayout->amount);
        if ($payoutConnectedAcc[SUCCESS]) {
            $pendingPayout->payout_txn_id = $payoutConnectedAcc[DATA][ID];
            $pendingPayout->status = PayoutStatus::PAID;
            $pendingPayout->payout_date = Carbon::now();
            $pendingPayout->save();
            Log::info("Payout complete for donor " .$pendingPayout[USER_ID]);
            $donor = User::find($pendingPayout[USER_ID]);
            if($donor) {
                /*** send mail */
            }
        } else {
            $pendingPayout->status = PayoutStatus::FAILED;
            $pendingPayout->error_code = $payoutConnectedAcc[CODE];
            $pendingPayout->error_message = $payoutConnectedAcc[MESSAGE];
            $pendingPayout->save();
            Log::info("Payout failed for donor " . $pendingPayout[USER_ID]);
            Log::info("Payout failed msg " . $pendingPayout[USER_ID]);
        }
        return true;
    }
}
