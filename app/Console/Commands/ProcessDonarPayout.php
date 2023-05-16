<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StripeService;
use App\Services\PayoutService;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Payout;
use App\Constants\PayoutStatus;
use Carbon\Carbon;
use Log;

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
        $donors =  User::where('connected_acc_token', '!=', null)
            ->where('bank_acc_token', '!=', null)
            ->where('connected_acc_status', '!=', ZERO)
            ->where('role_id', '!=', PARENTS_TO_BE)
            ->get();
        foreach ($donors as $donor) {
            $donor['transactions'] = Transaction::where(ACCOUNT_ID,$donor->connected_acc_token)->where(['payout_status' => ZERO, 'payment_type' => ONE])->whereNotNull('payment_intent')->get();
            $payoutAmount = 0;
            $processedTransactionIds = [];
            if (sizeof($donor['transactions']) == 0) {
                Log::info("No transactions for donor " . $donor['id']);
                continue;
            }
            foreach ($donor['transactions'] as $transaction) {
                $processedTransactionIds[] = $transaction['id'];
                $payoutAmount = $payoutAmount + $transaction['amount'];
            }
            if ($payoutAmount == 0) {
                continue;
            }
            Log::info("Adding payout entry for amount " . $payoutAmount . " to donor " . $donor['id'] . " for the transaction " . implode(",",  $processedTransactionIds));

            $payout = new Payout();
            $payout->amount = $payoutAmount;
            $payout->bank_acc_token = $donor['bank_acc_token'];
            $payout->user_id = $donor['id'];
            $payout->status = PayoutStatus::PROCESSING;
            $payout->transfer_txn_id = null;
            $payout->payout_date = Carbon::now();
            $payout->save();
            if (!$payout->id) {
                Log::info("Payout entry failed for donor " . $donor['id']);
                continue;
            }
            Transaction::whereIn('id', $processedTransactionIds)->update(
                [
                    "payout_status" => ONE,
                    "payout_id" => $payout->id
                ]
            );
        }
        Log::info("Payout entrys are added");
        Log::info("Processing payouts one by one");

        $exsistingPayouts = Payout::where('status', PayoutStatus::PROCESSING)
            ->with('donor')
            ->get();
        if ($exsistingPayouts) {
            Log::info("Processing pending payouts");
            $stripeService = new StripeService();
            PayoutService::processPayout($exsistingPayouts, $stripeService);
        } else {
            Log::info("No payout to process");
        }
    }
}
