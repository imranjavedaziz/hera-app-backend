<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Facades\{
    App\Services\SubscriptionService,
};
use App\Jobs\adminChatFreiendList;

class AdminChatFriend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:friend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is for creating admin chat friend list';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        adminChatFreiendList::dispatch();
    }
}
