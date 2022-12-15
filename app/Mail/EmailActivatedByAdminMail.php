<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailActivatedByAdminMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $isAdmin;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $isAdmin = false)
    {
        $this->user = $user;
        $this->isAdmin = $isAdmin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = ($this->isAdmin) ? 'Account Reactivated!' : 'Account Reactivated Successfully!' ;
        return $this->subject("HERA | ".$title)->view('emails.email-activated-by-admin');
    }
}
