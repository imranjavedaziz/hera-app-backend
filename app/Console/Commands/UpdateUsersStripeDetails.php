<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Jobs\CreateStripeCustomer;
use App\Jobs\CreateStripeAccount;

class UpdateUsersStripeDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create users stipe customer and account id';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $stripeCustomers = User::whereIn('role_id',[PARENTS_TO_BE,SURROGATE_MOTHER,EGG_DONER,SPERM_DONER])->whereNull('stripe_customer_id')->get();
        foreach($stripeCustomers as $customer) {
            CreateStripeCustomer::dispatch($customer);
        }

        $stripeAccounts = User::whereIn('role_id',[PARENTS_TO_BE,SURROGATE_MOTHER,EGG_DONER,SPERM_DONER])->whereNull('connected_acc_token')->get();
        foreach($stripeAccounts as $account) {
            CreateStripeAccount::dispatch($account);
        }
    }
}
