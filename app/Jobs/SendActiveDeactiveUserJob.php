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
    protected $status_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $status_id)
    {
        $this->id = $id;
        $this->status_id = $status_id;
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
        if($this->status_id == ACTIVE){
            Mail::to($user->email)->send(new EmailActivatedByAdminMail($user, true));
        }elseif ($this->status_id == INACTIVE) {
            Mail::to($user->email)->send(new EmailDeactivatedByAdminMail($user));
        }elseif ($this->status_id == DELETED) {
            Mail::to($user->email)->send(new EmailDeletedByAdminMail($user));
        }
    }
}
