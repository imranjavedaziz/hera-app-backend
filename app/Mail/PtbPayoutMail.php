<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PtbPayoutMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;
    
    protected $success;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $success)
    {
        $this->data = $data;
        $this->success = $success;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "HERA | Payment Failed";
        $emailTemplate = 'emails.ptb_payout_failed';
        if ($this->success) {
            $subject = "HERA | Payment Successful";
            $emailTemplate = 'emails.ptb_payout_success';
        }
        return $this->subject($subject)->view($emailTemplate, [
            'data' => $this->data
        ]);
    }
}
