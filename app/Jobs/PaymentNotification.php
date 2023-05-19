<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\CustomHelper;
use App\Models\Notification;
use App\Models\User;
use App\Models\DeviceRegistration;
use App\Constants\NotificationType;
use App\Traits\FcmTrait;
use App\Mail\PaymentRequestMail;
use App\Mail\DonarPaymentSuccessMail;
use App\Mail\PtbPaymentSuccessMail;
use App\Mail\PaymentDeclinedMail;
use Mail;

class PaymentNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, FcmTrait;
    
    protected $title;

    protected $description;

    protected $data;

    protected $notifyType;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($title, $description, $data, $notifyType)
    {
        $this->title = $title;
        $this->description = $description;
        $this->data = $data;
        $this->notifyType = $notifyType;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $userId = $this->data[USER_ID];
        $fromUser = User::where(ID, $userId)->first();
        $toUser = User::where(ID, $this->data[TO_USER_ID])->first();
        $this->sendMail($this->notifyType, $this->data, $toUser->email, $fromUser->email);
        $paymentArray[USER_ID] = $userId;
        $paymentArray[TO_USER_ID] = $this->data[TO_USER_ID];
        $paymentArray[NOTIFY_TYPE] = $this->notifyType;
        $userDevices = DeviceRegistration::where([USER_ID => $this->data[TO_USER_ID], STATUS_ID => ACTIVE])->get();
        foreach($userDevices as $device) {
            FcmTrait::sendPush($device->device_token, $this->title, $this->description, $paymentArray);
            $this->saveNotificationInDB($this->title, $this->description, $this->data[TO_USER_ID]);
        }
    }

    private function saveNotificationInDB($title, $description, $recipient) {
        Notification::create([
            'title' => $title,
            'description' => $description,
            'notify_type' => NotificationType::PAYMENT,
            'recipient_id' => $recipient,
        ]);

        return true;
    }

    private function sendMail($notifyType, $data, $toEmail, $fromEmail) {
        switch ($notifyType) {
            case "payment_request":
                Mail::to($toEmail)->send(new PaymentRequestMail($data));
              break;
            case "payment_transfer":
                Mail::to($toEmail)->send(new DonarPaymentSuccessMail($data));
                Mail::to($fromEmail)->send(new PtbPaymentSuccessMail($data));
              break;
            case "payment_declined":
                Mail::to($toEmail)->send(new PaymentDeclinedMail($data));
              break;
            default:
            Mail::to($toEmail)->send(new PaymentRequestMail($data));
          }
    }
}
