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
use App\Constants\NotificationType;
use App\Traits\FcmTrait;

class SubscriptionWillEnd implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, FcmTrait;

    protected $data;
    protected $isTrial;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $isTrial)
    {
        $this->data = $data;
        $this->isTrial = $isTrial;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subscriptionEndDate = CustomHelper::dateConvert($this->data->current_period_end, YMD_FORMAT);
        $title = 'Susbcription!';
        $trialDesc = "Your trial period will expire in next 10 days. Please subscribe to continue the services.";
        $description  = (!$this->isTrial) ? 'Your subscription will renew in next 10 days.' : $trialDesc;
        $userId = (!$this->isTrial) ? $this->data->user_id : $this->data->id;
        $membershipArrayPtb[USER_ID] = $userId;
        $membershipArrayPtb[NAME] = (!$this->isTrial)  ? $this->data->user->first_name.' '.$this->data->user->last_name : $this->data->first_name.' '.$this->data->last_name;
        $membershipArrayPtb['membership_name'] = (!$this->isTrial) ? $this->data->subscriptionPlan->name : 'Trial';
        $membershipArrayPtb['membership_end'] = (!$this->isTrial) ? $subscriptionEndDate : '';
        $membershipArrayPtb['membership_id'] = (!$this->isTrial) ? $this->data->id : '';
        $membershipArrayPtb[NOTIFY_TYPE] = SUBSCRIBE;
        $userDevices = DeviceRegistration::where([USER_ID => $userId, STATUS_ID => ACTIVE])->get();
        foreach ($userDevices as $device) {
            FcmTrait::sendPush($device->device_token, $title, $description, $membershipArrayPtb);
            $this->saveNotificationInDB($title, $description, $userId);
        }
    }

    private function saveNotificationInDB($title, $description, $recipient)
    {
        Notification::create([
            'title' => $title,
            'description' => $description,
            'notify_type' => NotificationType::SUBSCRIPTION,
            'recipient_id' => $recipient,
        ]);

        return true;
    }
}
