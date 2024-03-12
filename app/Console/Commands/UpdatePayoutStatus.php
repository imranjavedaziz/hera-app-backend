<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StripeService;
use App\Models\Payout;
use App\Constants\PayoutStatus;
use Carbon\Carbon;
use Log;

class UpdatePayoutStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:payoutStatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Payout status';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dateUpto = Carbon::now()->subDays(7);
        $payouts = Payout::where('payout_txn_id', '=', null)
            ->where('payout_date', '>=', $dateUpto)
            ->with(['donor'])
            ->get();
        Log::info('Payout to be processed' . $payouts->pluck('id'));
        foreach ($payouts as $payout) {
            Log::info("Processing payout " . $payout->id);
            $stripe = new StripeService();
            $data = $stripe->retrievePayout($payout['payout_txn_id'], $payout['donor']['connected_acc_token']);
            if ($data) {
                switch ($data['status']) {
                    case 'paid':
                        $payout->status = PayoutStatus::PAID;
                        break;

                    case 'pending':
                        $payout->status = PayoutStatus::PENDING;
                        break;

                    case 'in_transit':
                        $payout->status = PayoutStatus::IN_TRANSIT;
                        break;

                    case 'canceled':
                        $payout->status = PayoutStatus::CANCELED;
                        break;

                    case 'failed':
                        $payout->status = PayoutStatus::FAILED;
                        break;
                    default:
                        $payout->status = PayoutStatus::UNKNOWN_ERROR;
                        break;
                }
                $payout->error_code = $data['failure_code'];
                $payout->error_message =  $data['failure_message'];
                $payout->save();
            }
        }
    }
}
