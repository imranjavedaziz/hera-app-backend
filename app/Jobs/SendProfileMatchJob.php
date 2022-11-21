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

class SendProfileMatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, FcmTrait;

    protected $user;
    protected $profile_match_id;
    protected $description;
    protected $title;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $profile_match_id, $description, $title)
    {
        $this->user = $user;
        $this->profile_match_id = $profile_match_id;
        $this->description = $description;
        $this->title = $title;
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
        $profileMatchArray[USER_ID] = $this->user->id;
        $profileMatchArray[PROFILE_MATCH_ID] = $this->profile_match_id;
        $profileMatchArray[NOTIFY_TYPE] = PROFILE;
        if ($deviceRegistrations) {
            foreach ($deviceRegistrations as $deviceRegistration) {
                $this->sendPush($deviceRegistration->deviceToken,$this->title,$this->description,$profileMatchArray);
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
