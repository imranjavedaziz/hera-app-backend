<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;
use App\Mail\AdminReportMail;
use App\Models\User;

class SendReportMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fromUserId;

    protected $toUserId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fromUserId, $toUserId)
    {
        $this->fromUserId = $fromUserId;
        $this->toUserId = $toUserId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fromUser = User::where(ID, $this->fromUserId)->first();
        $toUser = User::where(ID, $this->toUserId)->first();
        Mail::to(env('ADMIN_EMAIL', 'admin-mbc@yopmail.com'))->send(new AdminReportMail($fromUser, $toUser));
    }
}
