<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\CustomHelper;
use App\Models\Notification;
use App\Models\User;
use App\Models\DeviceRegistration;
use App\Notifications\SubscriptionReminder;

class SubscriptionWillEnd implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $title = 'Renew Susbcription!';
        $description  = 'Your subscription will end on '.$subscriptionEndDate.'. Please renew to get good matches.';
        $userId = $this->data->user_id;
        $this->saveNotificationInDB($title, $description, $userId);
        $membershipArrayPtb[USER_ID] = $userId;
        $membershipArrayPtb[NAME] = $this->data->user->first_name.' '.$this->data->user->last_name;
        $membershipArrayPtb['membership_name'] = $this->data->subscriptionPlan->name;
        $membershipArrayPtb['membership_end'] = $subscriptionEndDate;
        $membershipArrayPtb['membership_id'] = $this->data->id;
        $userDevice = DeviceRegistration::where([USER_ID => $userId, STATUS => ACTIVE])->first();
        if ($userDevice != null) {
            $ptb = User::find($userId);
            $ptb->notify(new SubscriptionReminder($userDevice->device_token, $title, $description, $membershipArrayPtb));
        }
    }

    private function saveNotificationInDB($title, $description, $recipient) {
        Notification::create([
            'title' => $title,
            'description' => $description,
            'recipient' => $recipient,
        ]);

        return true;
    }
}
