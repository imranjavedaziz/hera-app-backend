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

class UpdateStatusOnFirebaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    protected $status;

    protected $keyName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $status, $keyName)
    {
        $this->user = $user;
        $this->status = $status;
        $this->keyName = $keyName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        FirebaseService::updateUserStatus($this->user, $this->status, $this->keyName);
    }
}
