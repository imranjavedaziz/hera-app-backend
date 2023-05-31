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
use Carbon\Carbon;

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
        $this->data[TO_USER_FIRST_NAME] = $toUser->first_name;
        $this->data['to_role'] = $toUser->role->name;
        $this->data['to_username'] = $toUser->username;
        $this->sendMail($this->notifyType, $this->data, $toUser->email, $fromUser->email);
        if ($this->notifyType == 'payment_request' || $this->notifyType == 'payment_declined' || $this->notifyType == 'payment_transfer') {
          $pushData[NOTIFY_TYPE] = $this->notifyType;
          $userDevices = DeviceRegistration::where([USER_ID => $this->data[TO_USER_ID], STATUS_ID => ACTIVE])->get();
          foreach($userDevices as $device) {
            FcmTrait::sendPush($device->device_token, $this->title, $this->description, $pushData);
            $this->saveNotificationInDB($this->title, $this->description, $this->data[TO_USER_ID]);
          }
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
            case "payment_initiate":
                $data['transaction_date'] =  Carbon::now(DEFAULT_TIMEZONE)->format('M d, Y');
                $data['transaction_time'] =  Carbon::now(DEFAULT_TIMEZONE)->format('h:i a (T)');
                $data['fee'] = $data[NET_AMOUNT] - $data[AMOUNT];
                Mail::to($toEmail)->send(new DonarPaymentSuccessMail($data));
                Mail::to($fromEmail)->send(new PtbPaymentSuccessMail($data));
              break;
            case "payment_declined":
                Mail::to($toEmail)->send(new PaymentDeclinedMail($data));
              break;
            default:
            break;
          }
    }
}
