<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;
use App\Models\User;
use App\Mail\EmailActivatedByAdminMail;
use App\Mail\EmailDeactivatedByAdminMail;
use App\Mail\EmailDeletedByAdminMail;
use Log;

class SendActiveDeactiveUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::where(ID, $this->id)->first();
        Log::debug($this->id);
        Log::debug($user);
        if($user->status_id == ACTIVE){
            Mail::to($user->email)->send(new EmailActivatedByAdminMail($user));
        }elseif ($user->status_id == INACTIVE) {
            Mail::to($user->email)->send(new EmailDeactivatedByAdminMail($user));
        }elseif ($user->status_id == DELETED) {
            Mail::to($user->email)->send(new EmailDeletedByAdminMail($user));
        }
    }
}
