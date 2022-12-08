<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Facades\{
    App\Services\SubscriptionService,
};
use App\Jobs\UpdateSubscriptionStatus;

class SubscriptionExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check expired subscription and update the subscription status';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $trialSubscription = SubscriptionService::getTrialExpiredSubscription();
        $subscription = SubscriptionService::getExpiredSubcription();
        foreach($subscription as $sub) {
            UpdateSubscriptionStatus::dispatch($sub->user_id);
        }

        foreach($trialSubscription as $trialSub) {
            UpdateSubscriptionStatus::dispatch($trialSub->id);
        }
    }
}
