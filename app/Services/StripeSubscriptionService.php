<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\ParentsPreference;
use Carbon\Carbon;
use Facades\{
    App\Services\StripeService
};
use App\Jobs\UpdateStatusOnFirebaseJob;

class StripeSubscriptionService
{
    public function savePlan($fields, $planId)
    {
        $fields = self::setPlanFields($fields, $planId);
        $fields[UPDATED_AT] = Carbon::now();
        return SubscriptionPlan::where(ID, $planId)->update($fields);
    }
    
    public function setPlanFields($object)
    {
        $result = [];
        if(!empty($object) && !empty($object->id) && $object->object === 'price') {
            $result = [
                ANDROID_PRODUCT => isset($object->product) ? $object->product:NULL,
                PRICE_ID => isset($object->id) ? $object->id:NULL
            ];
        }

        return $result;
    }

    public function createStripeSubscription($fields) {
        $plan = SubscriptionPlan::where(ANDROID_PRODUCT,$fields[PRODUCT_ID])->first();
        $subscribe = false;
        $user = User::find($fields[USER_ID]);
        if(empty($user[STRIPE_CUSTOMER_ID])) {
            $stripeCustomer = StripeService::createStripeCustomer($user);
            $user[STRIPE_CUSTOMER_ID] = $stripeCustomer[ID];
            $user->save();
        }
        $subscriptionsInfo = [
            'customer' => $user[STRIPE_CUSTOMER_ID],
            'items' => [
                [
                    'price' => $plan->price_id
                ],
            ],
            'default_payment_method' => $fields[PAYMENT_METHOD_ID],
            METADATA => [USER_ID => $user[ID]]
        ];
        $session = StripeService::createSubscription($subscriptionsInfo);
        if(!empty($session) && !empty($session->id) && $session->status == 'active') {            
            $this->cancelActiveSubscription($user[ID]);
            $fields = $this->setSubscriptionObject($session, $user, $plan);
            Subscription::create($fields);
            ParentsPreference::where(USER_ID, $user[ID])->update([ROLE_ID_LOOKING_FOR => $plan->role_id_looking_for]);
            $subscribe = true;
        }
        return $subscribe;
    }

    public function setSubscriptionObject($object, $user, $plan)
    {
        return [
            USER_ID => $user[ID],
            SUBSCRIPTION_PLAN_ID => $plan->id,
            SUBSCRIPTION_ID => isset($object->id)?$object->id:NULL,
            PRODUCT_ID => isset($object->plan->product)?$object->plan->product:NULL,
            PRICE => isset($object->plan->amount)?($object->plan->amount/100):NULL,
            CURRENT_PERIOD_START => isset($object->current_period_start)?date(DATE_TIME,$object->current_period_start):NULL,
            CURRENT_PERIOD_END => isset($object->current_period_end)?date(DATE_TIME,$object->current_period_end):NULL,
            DEVICE_TYPE => ANDROID,
            STATUS_ID => ACTIVE,
            UPDATED_AT => Carbon::now(),
        ];
    }

    public function cancelActiveSubscription($userId) {
        $subscription = $this->getActiveSubscription($userId);
        if(!empty($subscription)){
            $cancelSubscription = StripeService::cancelSubscription($subscription->subscription_id);
            if(!empty($cancelSubscription)
            && !empty($cancelSubscription->id)
            && $cancelSubscription->object === SUBSCRIPTION_OBJECT
            && $cancelSubscription->status === KEY_CANCELED) {
                $fields[STATUS_ID]  = INACTIVE;
                $fields[CANCELED_AT] = date(DATE_TIME,$cancelSubscription->canceled_at);
                Subscription::where(ID, $subscription->id)->update($fields);
            }
            $user = User::find($userId);
            if ($user->subscription_cancel == ONE) {
                $user->subscription_cancel = ZERO;
                $user->save();
                $fields[STATUS_ID]  = INACTIVE;
                $fields[CANCELED_AT] = Carbon::now();
                Subscription::where(ID, $subscription->id)->update($fields);
            }
        }
        return true;
    }

    public function getActiveSubscription($userId)
    {
        return Subscription::where(USER_ID,$userId)
            ->where(STATUS_ID,ACTIVE)
            ->first();
    }

    public function cancelSubscription($userId)
    {
        $subscription  = Subscription::where(USER_ID,$userId)
            ->where(STATUS_ID,ACTIVE)
            ->first();
        if(!empty($subscription) && !empty($subscription->subscription_id)) {
            $retrieveSubscription = StripeService::retrieveSubscription($subscription->subscription_id);
            $fields[STATUS_ID]    = INACTIVE;
            $fields[UPDATED_AT]   = Carbon::now();
            if(!empty($retrieveSubscription)
                && !empty($retrieveSubscription->id)
                && $retrieveSubscription->object === SUBSCRIPTION_OBJECT
                && $retrieveSubscription->status === KEY_CANCELED) {
                $fields[STATUS_ID]  = INACTIVE;
                $fields[CANCELED_AT] = date(DATE_TIME,$retrieveSubscription->canceled_at);
            } else {
                $cancelSubscription = StripeService::cancelSubscription($subscription->subscription_id);
                if(!empty($cancelSubscription)
                    && !empty($cancelSubscription->id)
                    && $cancelSubscription->object === SUBSCRIPTION_OBJECT
                    && $cancelSubscription->status === KEY_CANCELED) {
                    $fields[STATUS_ID]  = INACTIVE;
                    $fields[CANCELED_AT] = date(DATE_TIME,$cancelSubscription->canceled_at);
                }
            }
            if(!empty($fields[STATUS_ID]) && $fields[STATUS_ID] === INACTIVE) {
                $user = User::find($userId);
                $user->update([
                    SUBSCRIPTION_CANCEL=>ONE
                ]);
                return $fields;
            }
            return $retrieveSubscription;
        }
        return false;
    }

    public function updateSubscription($userId,$input) {
        return Subscription::where([USER_ID => $userId,SUBSCRIPTION_ID => $input[SUBSCRIPTION_ID],PRODUCT_ID => $input[PRODUCT_ID]])
        ->update([CURRENT_PERIOD_START => $input[CURRENT_PERIOD_START],CURRENT_PERIOD_END => $input[CURRENT_PERIOD_END], STATUS_ID => $input[STATUS_ID],ORIGINAL_TRANSACTION_ID => $input[PAYMENT_INTENT]]);
    }
}
