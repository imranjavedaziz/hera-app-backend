<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\CustomHelper;
use App\Traits\FcmTrait;
use App\Models\Notification;
use App\Constants\NotificationType;
use App\Models\DeviceRegistration;
use Facades\{
    App\Services\SubscriptionService
};

class SendProfileMatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, FcmTrait;

    protected $user;
    protected $sender_user;
    protected $profile_match;
    protected $description;
    protected $title;
    protected $feedback;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $sender_user, $profile_match, $description, $title, $feedback)
    {
        $this->user = $user;
        $this->sender_user = $sender_user;
        $this->profile_match = $profile_match;
        $this->description = $description;
        $this->title = $title;
        $this->feedback = $feedback;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $msgId = ($this->user->id > $this->sender_user->id) ? $this->user->id : $this->sender_user->id;
        $deviceRegistrations = DeviceRegistration::where([USER_ID => $this->user->id, STATUS_ID => ACTIVE])->get();
        $this->saveProfileMatchNotification();
        $profileMatchArray[NOTIFY_TYPE] = PROFILE;
        $profileMatchArray[USER] = $this->user;
        $profileMatchArray["chat_start"] = ZERO;
        $profileMatchArray["currentRole"] = $this->sender_user->role_id;
        $profileMatchArray["deviceToken"] = "deviceToken";
        $profileMatchArray["message"] = NULL;
        $profileMatchArray["msgId"] = $msgId."-".time();
        $profileMatchArray["read"] = ZERO;
        $profileMatchArray["feedback_status"] = !empty($this->feedback) ? $this->feedback->like : NULL;
        $profileMatchArray["recieverId"] = $this->user->id;
        $profileMatchArray["recieverImage"] = $this->user->profile_pic;
        $profileMatchArray["recieverName"] = CustomHelper::fullName($this->user);
        $profileMatchArray["recieverUserName"] = $this->user->username;
        $profileMatchArray["recieverSubscription"] = SubscriptionService::getSubscriptionStatus($this->user->id);
        $profileMatchArray["senderId"] = $this->sender_user->id;
        $profileMatchArray["senderImage"] = $this->sender_user->profile_pic;
        $profileMatchArray["senderName"] = CustomHelper::fullName($this->sender_user);
        $profileMatchArray["senderUserName"] = $this->sender_user->username;
        $profileMatchArray["senderSubscription"] = SubscriptionService::getSubscriptionStatus($this->sender_user->id);
        $profileMatchArray["status_id"] = ACTIVE;
        $profileMatchArray[MATCH_REQUEST] = $this->profile_match;
        $profileMatchArray["time"] = time();
        $profileMatchArray["type"] = "Text";
        if ($deviceRegistrations) {
            foreach ($deviceRegistrations as $deviceRegistration) {
                $this->sendPush($deviceRegistration->device_token,$this->title,$this->description,$profileMatchArray);
            }
        }
    }

    private function saveProfileMatchNotification() {
        Notification::create([
            TITLE => $this->title,
            DESCRIPTION => $this->description,
            NOTIFY_TYPE => NotificationType::MATCH,
            RECIPIENT_ID => $this->user->id
        ]);
        return true;
    }
}
