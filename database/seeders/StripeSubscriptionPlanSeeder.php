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
        $surrogateMotherMonthlyPlan = SubscriptionPlan::where(NAME,SURROGATE_MONTHLY_PLAN_NAME)->first();
        if(!empty($surrogateMotherMonthlyPlan)) {
            $discription = 'MBC ' . SURROGATE_MONTHLY_PLAN_NAME.' Plan';
            $createProduct = StripeService::createProduct(SURROGATE_MONTHLY_PLAN_NAME, $discription);
            if(!empty($createProduct) && !empty($createProduct->id) && $createProduct->object === PRODUCT) {
                $createPrice = StripeService::createPrice($createProduct->id,SURROGATE_MONTHLY_PLAN_NAME, MONTH, SURROGATE_MONTHLY_PLAN_PRICE);
                StripeSubscriptionService::savePlan($createPrice, $surrogateMotherMonthlyPlan->id);
            }
        }
        $eggDonorMonthlyPlan = SubscriptionPlan::where(NAME,EGG_DONER_MONTHLY_PLAN_NAME)->first();
        if(!empty($eggDonorMonthlyPlan)) {
            $discription = 'MBC ' . EGG_DONER_MONTHLY_PLAN_NAME.' Plan';
            $createProduct = StripeService::createProduct(EGG_DONER_MONTHLY_PLAN_NAME, $discription);
            if(!empty($createProduct) && !empty($createProduct->id) && $createProduct->object === PRODUCT) {
                $createPrice = StripeService::createPrice($createProduct->id,EGG_DONER_MONTHLY_PLAN_NAME, MONTH, EGG_DONER_MONTHLY_PLAN_PRICE);
                StripeSubscriptionService::savePlan($createPrice, $eggDonorMonthlyPlan->id);
            }
        }

        $spermDonorMonthlyPlan = SubscriptionPlan::where(NAME,SPERM_DONER_MONTHLY_PLAN_NAME)->first();
        if(!empty($spermDonorMonthlyPlan)) {
            $discription = 'MBC ' . SPERM_DONER_MONTHLY_PLAN_NAME.' Plan';
            $createProduct = StripeService::createProduct(SPERM_DONER_MONTHLY_PLAN_NAME, $discription);
            if(!empty($createProduct) && !empty($createProduct->id) && $createProduct->object === PRODUCT) {
                $createPrice = StripeService::createPrice($createProduct->id,SPERM_DONER_MONTHLY_PLAN_NAME, MONTH, SPERM_DONER_MONTHLY_PLAN_PRICE);
                StripeSubscriptionService::savePlan($createPrice, $spermDonorMonthlyPlan->id);
            }
        }

        $surrogateMotherQuarterlyPlan = SubscriptionPlan::where(NAME,SURROGATE_QUARTERLY_PLAN_NAME)->first();
        if(!empty($surrogateMotherQuarterlyPlan)) {
            $discription = 'MBC ' . SURROGATE_QUARTERLY_PLAN_NAME.' Plan';
            $createProduct = StripeService::createProduct(SURROGATE_QUARTERLY_PLAN_NAME, $discription);
            if(!empty($createProduct) && !empty($createProduct->id) && $createProduct->object === PRODUCT) {
                $createPrice = StripeService::createPrice($createProduct->id,SURROGATE_QUARTERLY_PLAN_NAME, MONTH, SURROGATE_QUARTERLY_PLAN_PRICE, THREE);
                StripeSubscriptionService::savePlan($createPrice, $surrogateMotherQuarterlyPlan->id);
            }
        }
        $eggDonorQuarterlyPlan = SubscriptionPlan::where(NAME,EGG_DONER_QUARTERLY_PLAN_NAME)->first();
        if(!empty($eggDonorQuarterlyPlan)) {
            $discription = 'MBC ' . EGG_DONER_QUARTERLY_PLAN_NAME.' Plan';
            $createProduct = StripeService::createProduct(EGG_DONER_QUARTERLY_PLAN_NAME, $discription);
            if(!empty($createProduct) && !empty($createProduct->id) && $createProduct->object === PRODUCT) {
                $createPrice = StripeService::createPrice($createProduct->id,EGG_DONER_QUARTERLY_PLAN_NAME, MONTH, EGG_DONER_QUARTERLY_PLAN_PRICE, THREE);
                StripeSubscriptionService::savePlan($createPrice, $eggDonorQuarterlyPlan->id);
            }
        }

        $spermDonorQuarterlyPlan = SubscriptionPlan::where(NAME,SPERM_DONER_QUARTERLY_PLAN_NAME)->first();
        if(!empty($spermDonorQuarterlyPlan)) {
            $discription = 'MBC ' . SPERM_DONER_QUARTERLY_PLAN_NAME.' Plan';
            $createProduct = StripeService::createProduct(SPERM_DONER_QUARTERLY_PLAN_NAME, $discription);
            if(!empty($createProduct) && !empty($createProduct->id) && $createProduct->object === PRODUCT) {
                $createPrice = StripeService::createPrice($createProduct->id,SPERM_DONER_QUARTERLY_PLAN_NAME, MONTH, SPERM_DONER_QUARTERLY_PLAN_PRICE, THREE);
                StripeSubscriptionService::savePlan($createPrice, $spermDonorQuarterlyPlan->id);
            }
        }
    }
}
