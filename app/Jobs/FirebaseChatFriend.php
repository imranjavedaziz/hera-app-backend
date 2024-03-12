<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Facades\{
    App\Services\FirebaseService
};

class FirebaseChatFriend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sender;

    protected $receiver;

    protected $action;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sender, $receiver, $action)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->action = $action;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        switch ($this->action) {
            case SENT_REQUEST:
                FirebaseService::createChatFriends($this->sender, $this->receiver);
              break;
            case APPROVED_REQUEST:
                FirebaseService::updateMatchRequestStatus($this->sender, $this->receiver);
              break;
            case REJECTED_REQUEST:
                FirebaseService::removeChatFriends($this->sender, $this->receiver);
              break;
            default:
            FirebaseService::createChatFriends($this->sender, $this->receiver);
          }
    }
}
