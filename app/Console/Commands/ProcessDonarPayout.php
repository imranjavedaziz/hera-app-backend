<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StripeService;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Payout;
use App\Constants\PayoutStatus;
use Carbon\Carbon;
use Log;
use Facades\{
    App\Services\PayoutService
};

class ProcessDonarPayout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:payout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process payout to donor';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $transactions = Transaction::where(['payout_status' => ZERO, 'payment_type' => ONE])->whereNotNull('payment_intent')->whereNotNull(ACCOUNT_ID)->orderBy(ID,ASC)->get();
        foreach ($transactions as $transaction) {
            $donor =  User::where('connected_acc_token', $transaction[ACCOUNT_ID])->first();
            $payoutAmount = $transaction[AMOUNT];
            Log::info("Adding payout entry for amount " . $payoutAmount . " to donor " . $donor['id'] . " for the transaction " . $transaction[ID]);

            $payout = new Payout();
            $payout->amount = $payoutAmount;
            $payout->bank_acc_token = $donor['bank_acc_token'];
            $payout->user_id = $donor[ID];
            $payout->status = PayoutStatus::PROCESSING;
            $payout->transfer_txn_id = null;
            $payout->payout_date = Carbon::now();
            $payout->save();
            if (!$payout->id) {
                Log::info("Payout entry failed for donor " . $donor[ID]);
                continue;
            }
            Transaction::where(ID, $transaction[ID])->update(
                [
                    "payout_status" => ONE,
                    "payout_id" => $payout->id
                ]
            );
            Log::info("Payout entrys are added");
            Log::info("Processing payouts one by one");
            $exsistingPayouts = Payout::where(ID, $payout->id)->with('donor')->first();
            if ($exsistingPayouts) {
                Log::info("Processing pending payouts");
                $stripeService = new StripeService();
                PayoutService::processPayout($exsistingPayouts, $stripeService, $transaction);
            } else {
                Log::info("No payout to process");
            }
        }
    }
}
