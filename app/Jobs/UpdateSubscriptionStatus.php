<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Subscription;
use App\Jobs\UpdateStatusOnFirebaseJob;

class UpdateSubscriptionStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Subscription::where(USER_ID, $this->userId)->where(STATUS_ID, ACTIVE)->update([STATUS_ID => INACTIVE]);
        $user = User::find($this->userId);
        $user->update([
            SUBSCRIPTION_STATUS=>SUBSCRIPTION_DISABLED
        ]);
        dispatch(new UpdateStatusOnFirebaseJob($user, SUBSCRIPTION_DISABLED, RECIEVER_SUBSCRIPTION));
    }
}
