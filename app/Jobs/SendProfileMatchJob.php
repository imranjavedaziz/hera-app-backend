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

class SendProfileMatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, FcmTrait;

    protected $user;
    protected $receiver_user;
    protected $profile_match;
    protected $description;
    protected $title;
    protected $feedback;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $receiver_user, $profile_match, $description, $title, $feedback)
    {
        $this->user = $user;
        $this->receiver_user = $receiver_user;
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
        $deviceRegistrations = DeviceRegistration::where([USER_ID => $this->user->id, STATUS_ID => ACTIVE])->get();
        $this->saveProfileMatchNotification();
        $profileMatchArray[USER] = $this->user;
        $profileMatchArray[RECEIVER_USER] = $this->receiver_user;
        $profileMatchArray[USER_ID] = $this->user->id;
        $profileMatchArray[PROFILE_MATCH] = $this->profile_match;
        $profileMatchArray[FEEDBACK] = $this->feedback;
        $profileMatchArray[NOTIFY_TYPE] = PROFILE;
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
