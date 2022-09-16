<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subscriptionPlan = [
            [
                NAME  => 'monthly',
                PRICE  => 9.9,
                INTERVAL  => 'month',
                INTERVAL_COUNT  => 1,
                DESCRIPTION  => 'monthly',
                IOS_PRODUCT  => 'monthly',
                ANDROID_PRODUCT  => 'monthly',
                FOR_WHOM  => ONE,
                STATUS_ID   => ACTIVE     
            ],
            [
                NAME  => 'yearly',
                PRICE  => 50.0,
                INTERVAL  => 'year',
                INTERVAL_COUNT  => 1,
                DESCRIPTION  => 'yearly',
                IOS_PRODUCT  => 'yearly',
                ANDROID_PRODUCT  => 'monthly',
                FOR_WHOM  => ONE,
                STATUS_ID   => ACTIVE
            ],
        ];
        SubscriptionPlan::insert($subscriptionPlan);
    }
}
