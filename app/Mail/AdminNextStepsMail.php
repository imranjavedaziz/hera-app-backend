<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNextStepsMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $ptb;

    protected $donar;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ptb, $donar)
    {
        $this->ptb = $ptb;
        $this->donar = $donar;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("HERA Family Planning | ".$this->ptb->first_name." shows interest in ". $this->donar->role->name.' '.$this->donar->username)->view('emails.admin_next_steps', [
            'ptb' => $this->ptb,
            'donar' => $this->donar,
        ]);
    }
}
