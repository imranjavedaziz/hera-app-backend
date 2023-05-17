<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;
use App\Mail\AdminNextStepsMail;
use App\Mail\PtbNextStepsMail;
use App\Mail\DonarNextStepsMail;
use App\Models\User;

class SendNextStepsMail implements ShouldQueue
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
        $ptb = User::where(ID, $this->fromUserId)->first();
        $donar = User::where(ID, $this->toUserId)->first();
        Mail::to(env('ADMIN_EMAIL', 'admin-mbc@yopmail.com'))->send(new AdminNextStepsMail($ptb, $donar));
        Mail::to($ptb->email)->send(new PtbNextStepsMail($ptb, $donar));
        Mail::to($donar->email)->send(new DonarNextStepsMail($ptb, $donar));
    }
}
