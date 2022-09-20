<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\CustomHelper;

class SubscriptionWillEnd implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, FcmTrait;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subscriptionEndDate = CustomHelper::dateConvert($this->data->current_period_end, YMD_FORMAT);
        $this->saveNotification($subscriptionEndDate, $this->data->user_id);
        $membershipArrayPtb[USER_ID] = $this->data->user_id;
        $membershipArrayPtb[NAME] = $this->data->user->first_name.' '.$this->data->user->last_name;
        $membershipArrayPtb['membership_name'] = $this->data->subscriptionPlan->name;
        $membershipArrayPtb['membership_end'] = $subscriptionEndDate;
        $membershipArrayPtb['membership_id'] = $this->data->id;
    }

    private function saveNotification($endDate, $recipient) {
        $title = 'Renew Susbcription!';
        $description  = 'Your subscription will end on '.$endDate.'. Please renew to get good matches.';
        FcmTrait::saveNotificationInDB($title, $description, $recipient);
        return true;
    }
}
