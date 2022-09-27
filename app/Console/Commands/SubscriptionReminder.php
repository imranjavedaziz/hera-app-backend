<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Facades\{
    App\Services\SubscriptionService,
};
use App\Jobs\SubscriptionWillEnd;

class SubscriptionReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscription end notification on before one day.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $subscription = SubscriptionService::getSubcriptionEndBeforeTenDay();
        foreach($subscription as $sub) {
            SubscriptionWillEnd::dispatch($sub);
        }
    }
}
