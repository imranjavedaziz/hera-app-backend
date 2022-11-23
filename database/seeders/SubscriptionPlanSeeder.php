<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;
use DB;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        SubscriptionPlan::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $subscriptionPlan = [
            [
                NAME  => MONTHLY_PLAN_NAME,
                PRICE  => MONTHLY_PLAN_PRICE,
                INTERVAL  => 'month',
                INTERVAL_COUNT  => ONE,
                DESCRIPTION  => MONTHLY_PLAN_DESCRIPTION,
                IOS_PRODUCT  => MONTHLY_IOS_PRODUCT,
                ANDROID_PRODUCT  => MONTHLY_ANDROID_PRODUCT,
                FOR_WHOM  => ONE,
                STATUS_ID   => ACTIVE     
            ],
            [
                NAME  => YEARLY_PLAN_NAME,
                PRICE  => YEARLY_PLAN_PRICE,
                INTERVAL  => 'year',
                INTERVAL_COUNT  => ONE,
                DESCRIPTION  => YEARLY_PLAN_DESCRIPTION,
                IOS_PRODUCT  => YEARLY_IOS_PRODUCT,
                ANDROID_PRODUCT  => YEARLY_ANDROID_PRODUCT,
                FOR_WHOM  => ONE,
                STATUS_ID   => ACTIVE
            ],
        ];
        SubscriptionPlan::insert($subscriptionPlan);
    }
}
