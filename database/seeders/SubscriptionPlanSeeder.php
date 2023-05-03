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
                ROLE_ID_LOOKING_FOR => SURROGATE_MOTHER,
                NAME  => SURROGATE_MONTHLY_PLAN_NAME,
                PRICE  => SURROGATE_MONTHLY_PLAN_PRICE,
                INTERVAL  => MONTH,
                INTERVAL_COUNT  => ONE,
                DESCRIPTION  => MONTHLY_PLAN_DESCRIPTION,
                IOS_PRODUCT  => SURROGATE_MONTHLY_IOS_PLAN,
                ANDROID_PRODUCT  => SURROGATE_MONTHLY_ANDROID_PLAN,
                OFFER_TOKEN => SURROGATE_MONTHLY_OFFER_TOKEN,
                FOR_WHOM  => ONE,
                STATUS_ID   => ACTIVE
            ],
            [
                ROLE_ID_LOOKING_FOR => EGG_DONER,
                NAME  => EGG_DONER_MONTHLY_PLAN_NAME,
                PRICE  => EGG_DONER_MONTHLY_PLAN_PRICE,
                INTERVAL  => MONTH,
                INTERVAL_COUNT  => ONE,
                DESCRIPTION  => MONTHLY_PLAN_DESCRIPTION,
                IOS_PRODUCT  => EGG_DONER_MONTHLY_IOS_PLAN,
                ANDROID_PRODUCT  => EGG_DONER_MONTHLY_ANDROID_PLAN,
                OFFER_TOKEN => EGG_DONER_MONTHLY_OFFER_TOKEN,
                FOR_WHOM  => ONE,
                STATUS_ID   => ACTIVE
            ],
            [
                ROLE_ID_LOOKING_FOR => SPERM_DONER,
                NAME  => SPERM_DONER_MONTHLY_PLAN_NAME,
                PRICE  => SPERM_DONER_MONTHLY_PLAN_PRICE,
                INTERVAL  => MONTH,
                INTERVAL_COUNT  => ONE,
                DESCRIPTION  => MONTHLY_PLAN_DESCRIPTION,
                IOS_PRODUCT  => SPERM_DONER_MONTHLY_IOS_PLAN,
                ANDROID_PRODUCT  => SPERM_DONER_MONTHLY_ANDROID_PLAN,
                OFFER_TOKEN => SPERM_DONER_MONTHLY_OFFER_TOKEN,
                FOR_WHOM  => ONE,
                STATUS_ID   => ACTIVE
            ],
            [
                ROLE_ID_LOOKING_FOR => SURROGATE_MOTHER,
                NAME  => SURROGATE_QUARTERLY_PLAN_NAME,
                PRICE  => SURROGATE_QUARTERLY_PLAN_PRICE,
                INTERVAL  => MONTH,
                INTERVAL_COUNT  => ONE,
                DESCRIPTION  => QUARTERLY_PLAN_DESCRIPTION,
                IOS_PRODUCT  => SURROGATE_QUARTERLY_IOS_PLAN,
                ANDROID_PRODUCT  => SURROGATE_QUARTERLY_ANDROID_PLAN,
                OFFER_TOKEN => SURROGATE_QUARTERLY_OFFER_TOKEN,
                FOR_WHOM  => ONE,
                STATUS_ID   => ACTIVE
            ],
            [
                ROLE_ID_LOOKING_FOR => EGG_DONER,
                NAME  => EGG_DONER_QUARTERLY_PLAN_NAME,
                PRICE  => EGG_DONER_QUARTERLY_PLAN_PRICE,
                INTERVAL  => MONTH,
                INTERVAL_COUNT  => ONE,
                DESCRIPTION  => QUARTERLY_PLAN_DESCRIPTION,
                IOS_PRODUCT  => EGG_DONER_QUARTERLY_IOS_PLAN,
                ANDROID_PRODUCT  => EGG_DONER_QUARTERLY_ANDROID_PLAN,
                OFFER_TOKEN => EGG_DONER_QUARTERLY_OFFER_TOKEN,
                FOR_WHOM  => ONE,
                STATUS_ID   => ACTIVE
            ],
            [
                ROLE_ID_LOOKING_FOR => SPERM_DONER,
                NAME  => SPERM_DONER_QUARTERLY_PLAN_NAME,
                PRICE  => SPERM_DONER_QUARTERLY_PLAN_PRICE,
                INTERVAL  => MONTH,
                INTERVAL_COUNT  => ONE,
                DESCRIPTION  => QUARTERLY_PLAN_DESCRIPTION,
                IOS_PRODUCT  => SPERM_DONER_QUARTERLY_IOS_PLAN,
                ANDROID_PRODUCT  => SPERM_DONER_QUARTERLY_ANDROID_PLAN,
                OFFER_TOKEN => SPERM_DONER_QUARTERLY_OFFER_TOKEN,
                FOR_WHOM  => ONE,
                STATUS_ID   => ACTIVE
            ],
        ];
        SubscriptionPlan::insert($subscriptionPlan);
    }
}
