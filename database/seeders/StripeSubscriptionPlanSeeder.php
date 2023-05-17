<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;
use Facades\{
    App\Services\StripeService,
    App\Services\StripeSubscriptionService,
};

class StripeSubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            SURROGATE_MONTHLY_PLAN_NAME => [
                DESCRIPTION => MBC.' '. SURROGATE_MONTHLY_PLAN_NAME . ' ' . PLAN,
                INTERVAL => MONTH,
                PRICE => SURROGATE_MONTHLY_PLAN_PRICE,
            ],
            EGG_DONER_MONTHLY_PLAN_NAME => [
                DESCRIPTION => MBC.' ' . EGG_DONER_MONTHLY_PLAN_NAME . ' ' . PLAN,
                INTERVAL => MONTH,
                PRICE => EGG_DONER_MONTHLY_PLAN_PRICE,
            ],
            SPERM_DONER_MONTHLY_PLAN_NAME => [
                DESCRIPTION => MBC.' ' . SPERM_DONER_MONTHLY_PLAN_NAME . ' ' . PLAN,
                INTERVAL => MONTH,
                PRICE => SPERM_DONER_MONTHLY_PLAN_PRICE,
            ],
            SURROGATE_QUARTERLY_PLAN_NAME => [
                DESCRIPTION => MBC.' ' . SURROGATE_QUARTERLY_PLAN_NAME . ' ' . PLAN,
                INTERVAL => MONTH,
                PRICE => SURROGATE_QUARTERLY_PLAN_PRICE,
                INTERVAL_COUNT => THREE,
            ],
            EGG_DONER_QUARTERLY_PLAN_NAME => [
                DESCRIPTION => MBC.' ' . EGG_DONER_QUARTERLY_PLAN_NAME . ' ' . PLAN,
                INTERVAL => MONTH,
                PRICE => EGG_DONER_QUARTERLY_PLAN_PRICE,
                INTERVAL_COUNT => THREE,
            ],
            SPERM_DONER_QUARTERLY_PLAN_NAME => [
                DESCRIPTION => MBC.' ' . SPERM_DONER_QUARTERLY_PLAN_NAME . ' ' . PLAN,
                INTERVAL => MONTH,
                PRICE => SPERM_DONER_QUARTERLY_PLAN_PRICE,
                INTERVAL_COUNT => THREE,
            ],
        ];
        foreach ($plans as $planName => $planData) {
            $subscriptionPlan = SubscriptionPlan::where(NAME, $planName)->first();
            if (empty($subscriptionPlan)) {
                continue;
            }
            $createProduct = StripeService::createProduct($planName, $planData[DESCRIPTION]);
            if (empty($createProduct) || empty($createProduct->id) || $createProduct->object !== PRODUCT) {
                continue;
            }
            $createPrice = StripeService::createPrice(
                $createProduct->id,
                $planName,
                $planData[INTERVAL],
                $planData[PRICE],
                $planData[INTERVAL_COUNT] ?? ONE
            );
            StripeSubscriptionService::savePlan($createPrice, $subscriptionPlan->id);
        }
    }
}
