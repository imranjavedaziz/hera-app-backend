<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Location;

class SetLocationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $input;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($input)
    {
        $this->input = $input;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $location = Location::where(USER_ID, $this->input[USER_ID])->first();
        if (!$location) {
            $location = new Location();
        }
        $location->user_id = $this->input[USER_ID];
        $location->state_id = $this->input[STATE_ID];
        $location->zipcode = $this->input[ZIPCODE];
        $location->save();
    }
}
