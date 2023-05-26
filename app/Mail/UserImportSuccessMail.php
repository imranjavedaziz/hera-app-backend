<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserImportSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    protected $password;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $role_name = $this->user->role->name;
        return $this->subject("HERA | You have been onboarded as a  ".$role_name." on HERA")->view('emails.user-imported',[
            'user' => $this->user,
            'password' => $this->password,
        ]);
    }
}
