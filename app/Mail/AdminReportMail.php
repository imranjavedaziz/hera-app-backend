<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminReportMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $fromUser;

    protected $toUser;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fromUser, $toUser)
    {
        $this->fromUser = $fromUser;
        $this->toUser = $toUser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("HERA Family Planning | User has been reported")->view('emails.admin_reported_email', [
            'fromUser' => $this->fromUser,
            'toUser' => $this->toUser,
        ]);
    }
}
