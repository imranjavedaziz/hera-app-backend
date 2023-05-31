<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Facades\{
    App\Services\StripeSubscriptionService
};

class SubscriptionUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    protected $subscription;

    protected $payment;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user,$data,$payment=null)
    {
        $this->user = $user;
        $this->subscription = $data;
        $this->payment = $payment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        StripeSubscriptionService::updateSubscription($this->user->id, $this->subscription);
    }
}
