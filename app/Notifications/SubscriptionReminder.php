<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Traits\FcmTrait;
use Log;

class SubscriptionReminder extends Notification
{
    use Queueable, FcmTrait;

    protected $deviceToken;

    protected $title;

    protected $description;

    protected $data;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($deviceToken, $title, $description, $data)
    {
        $this->deviceToken = $deviceToken;
        $this->title = $title;
        $this->description = $description;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','fcm'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        /**return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');***/
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toFcm($notifiable)
    {
        Log::debug("Membership Notification");
        return $this->sendPush($this->deviceToken, $this->title, $this->description, $this->data);
    }
}
