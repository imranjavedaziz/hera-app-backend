<?php

namespace App\Services;

use App\Models\User;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\ProfileMatch;
use App\Services\ReceiptService;
use Log;
use Carbon\Carbon;
use App\Traits\StoreReceiptTrait;
use App\Jobs\UpdateStatusOnFirebaseJob;

class SubscriptionService
{
    use StoreReceiptTrait;

    public function createSubscription($fields,$userId)
    {
        $subscriptionFields = [];
        $fields[USER_ID] = $userId;
        if($fields[DEVICE_TYPE] == IOS) {
            $plan = SubscriptionPlan::where(IOS_PRODUCT,$fields[PRODUCT_ID])->first();            
            $fields[SUBSCRIPTION_PLAN_ID] = $plan->id;
            $fields[PRICE] = $plan->price;
            $receiptService = new ReceiptService();
            $receiptService = $receiptService->verifyIosReceipt($fields[PURCHASE_TOKEN]);
            if($receiptService['status'] > ZERO) {
                return $receiptService;
            }
            if(trim($fields[PRODUCT_ID]) == trim($receiptService[DATA][PRODUCT_ID])) {
                $fields[SUBSCRIPTION_ID] = $receiptService[DATA][TRANSACTION_ID];
            } else {
                $fields[SUBSCRIPTION_ID] = NULL;
            }
            $fields[CURRENT_PERIOD_START] = $receiptService[DATA][PURCHASE_DATE];
            $fields[CURRENT_PERIOD_END] = $receiptService[DATA][EXPIRES_DATE];
            $fields[ORIGINAL_TRANSACTION_ID] = $receiptService[DATA][ORIGINAL_TRANSACTION_ID];
            $subscriptionFields = $this->setSubscriptionFields($fields);
            Subscription::where(USER_ID,$userId)->where(STATUS_ID,ACTIVE)->update([STATUS_ID => INACTIVE]);
            if(!empty($subscriptionFields)) {
                return $this->createNewSubscription($subscriptionFields);
            }
        }

        if($fields[DEVICE_TYPE] == ANDROID) {
            return $this->androidSubscription($fields);
        }
    }

    private function androidSubscription($fields){
        $plan = SubscriptionPlan::where(ANDROID_PRODUCT,$fields[PRODUCT_ID])->first();
        $fields[SUBSCRIPTION_PLAN_ID] = $plan->id;
        $fields[PRICE] = $plan->price;
        $receiptService = StoreReceiptTrait::playStoreServiceAccount($fields[PURCHASE_TOKEN],$fields[PRODUCT_ID]);
        /***purchaseState
         * 0: Purchase was completed.
         * 1: Purchase was canceled.
         * 2: Purchase is pending.
         ****/
        if(!empty($receiptService)
            && !empty($receiptService->orderId)
            && $receiptService->acknowledgementState == ONE
            && $receiptService->autoRenewing == ONE
            ) {     
            $startEndDate = $this->calulateSubscriptionStartEndDate($plan);
            $fields[CURRENT_PERIOD_START] = $startEndDate[CURRENT_PERIOD_START];
            $fields[CURRENT_PERIOD_END] = $startEndDate[CURRENT_PERIOD_END];
            $fields[SUBSCRIPTION_ID] = $receiptService->orderId;
            $fields[ORIGINAL_TRANSACTION_ID] = $receiptService->orderId;
            $subscriptionFields = $this->setSubscriptionFields($fields);
            Subscription::where(USER_ID,$fields[USER_ID])->where(STATUS_ID,ACTIVE)->update([STATUS_ID => INACTIVE]);
            if(!empty($subscriptionFields)) {
                return $this->createNewSubscription($subscriptionFields);
            }
        } else {
            return $receiptService;
        }
    }

    private function createNewSubscription($subscriptionFields){
        $newSubscription = Subscription::create($subscriptionFields);
        $paymenFields = $this->setSubPaymentFields($newSubscription);
        Payment::create($paymenFields);
        $user = User::find($subscriptionFields[USER_ID]);
        $user->subscription_status = SUBSCRIPTION_ENABLED;
        $user->save();
        dispatch(new UpdateStatusOnFirebaseJob($user, SUBSCRIPTION_ENABLED, RECIEVER_SUBSCRIPTION));
        return $this->getSubscriptionByUserId($subscriptionFields[USER_ID]);
    }

    private function setSubscriptionFields($fields){
        return [
            USER_ID => $fields[USER_ID],
            SUBSCRIPTION_PLAN_ID => isset($fields[SUBSCRIPTION_PLAN_ID]) ? $fields[SUBSCRIPTION_PLAN_ID] : NULL,
            PRICE => isset($fields[PRICE]) ? $fields[PRICE] : NULL,
            CURRENT_PERIOD_START => isset($fields[CURRENT_PERIOD_START]) ? $fields[CURRENT_PERIOD_START] : NULL,
            CURRENT_PERIOD_END => isset($fields[CURRENT_PERIOD_END]) ? $fields[CURRENT_PERIOD_END] : NULL,
            SUBSCRIPTION_ID => isset($fields[SUBSCRIPTION_ID]) ? $fields[SUBSCRIPTION_ID] : NULL,
            ORIGINAL_TRANSACTION_ID => isset($fields[ORIGINAL_TRANSACTION_ID]) ? $fields[ORIGINAL_TRANSACTION_ID] : NULL,
            PRODUCT_ID => isset($fields[PRODUCT_ID]) ? $fields[PRODUCT_ID] : NULL,
            PURCHASE_TOKEN => isset($fields[PURCHASE_TOKEN]) ? $fields[PURCHASE_TOKEN] : NULL,
            DEVICE_TYPE => isset($fields[DEVICE_TYPE]) ? $fields[DEVICE_TYPE] : 'ios',
            STATUS_ID => ACTIVE,
            UPDATED_AT => Carbon::now(),
        ];
    }

    public function calulateSubscriptionStartEndDate($plan) {
        $startDate = Carbon::now();
        $newstartDate = clone $startDate;
        if(!empty($plan) && $plan->interval == 'month') {
            $result[CURRENT_PERIOD_START] = $newstartDate;
            $result[CURRENT_PERIOD_END]   = $startDate->addMonth($plan->interval_count);
        } else if(!empty($plan) && $plan->interval == 'year') {
            $result[CURRENT_PERIOD_START] = $newstartDate;
            $result[CURRENT_PERIOD_END]   = $startDate->addYear($plan->interval_count);
        }
        return $result;
    }

    public function setSubPaymentFields($sub){
        return [
            USER_ID => $sub->user_id,
            PRODUCT_ID => $sub->id,
            PAYMENT_FOR => 'subscription',
            PAYMENT_ID => isset($sub->subscription_id)?$sub->subscription_id:$sub->original_transaction_id,
            COST => $sub->price,
            PAYMENT_GATEWAY => 'In-App',
            STATUS_ID => ACTIVE,
        ];
    }
    
    public function getSubscriptionByUserId($userId){
        return Subscription::with(['subscriptionPlan'])
            ->where(STATUS_ID,ACTIVE)
            ->where(USER_ID,$userId)
            ->orderBY(ID,DESC)
            ->first();
    }

    public function updateIosSubscription($fields)
    {
        $data = $this->getSubscriptionDetails($fields);
        $prevSubDetails = Subscription::where(ORIGINAL_TRANSACTION_ID,$data[ORIGINAL_TRANSACTION_ID])->orderBy(ID,DESC)->first();
        if ($data[NOTIFICATION_TYPE] == 'DID_RENEW') {
                $plan = SubscriptionPlan::where(IOS_PRODUCT,$data[PRODUCT_ID])->first(); 
                $this->setAndCreateSubscriptionData($plan, $data);
        }elseif($data[NOTIFICATION_TYPE] == 'CANCEL'){
            $userId = $prevSubDetails[USER_ID];
            Subscription::where(USER_ID,$userId)->where(STATUS_ID,ACTIVE)->update([STATUS_ID => INACTIVE]);
            $user = User::find($userId);
            $user->update([
                SUBSCRIPTION_STATUS=>SUBSCRIPTION_DISABLED
            ]);
            dispatch(new UpdateStatusOnFirebaseJob($user, SUBSCRIPTION_DISABLED, RECIEVER_SUBSCRIPTION));
        }
        return true;
    }

    public function updateAndroidsSubscription($data)
    {
        if ($data[NOTIFICATION_TYPE] == SUBSCRIPTION_RENEWED) {
            $this->updateSubscriptionData($data);
        }elseif($data[NOTIFICATION_TYPE] == SUBSCRIPTION_CANCELED){
            $receiptService = StoreReceiptTrait::playStoreServiceAccount($data[PURCHASE_TOKEN],$data[PRODUCT_ID]);
            $prevSubDetails = Subscription::where(ORIGINAL_TRANSACTION_ID,$receiptService->orderId)->orderBy(ID,DESC)->first();
            $fields[USER_ID] = $userId = $prevSubDetails[USER_ID];
            Subscription::where(USER_ID,$userId)->where(STATUS_ID,ACTIVE)->update([STATUS_ID => INACTIVE]);
            $user = User::find($userId);
            $user->update([
                SUBSCRIPTION_STATUS=>SUBSCRIPTION_DISABLED
            ]);
            dispatch(new UpdateStatusOnFirebaseJob($user, SUBSCRIPTION_DISABLED, RECIEVER_SUBSCRIPTION));
        }
        return true;
    }

    public function getSubscriptionDetails($fields)
    {
        $payload = explode('.',$fields['signedPayload'])[ONE];
        $payloadInfo = json_decode(base64_decode($payload));
        Log::info(json_encode($payloadInfo));
        $signedPayload = explode('.',$payloadInfo->data->signedRenewalInfo)[ONE];
        $signedPayloadInfo = json_decode(base64_decode($signedPayload));
        Log::info(json_encode($signedPayloadInfo));
        $signedTranaction = explode('.',$payloadInfo->data->signedTransactionInfo)[ONE];
        $signedTransactionInfo = json_decode(base64_decode($signedTranaction ));
        Log::info(json_encode($signedTransactionInfo));
        return [
            NOTIFICATION_TYPE  => $payloadInfo->notificationType,
            PRODUCT_ID          => $signedPayloadInfo->productId,
            AUTORENEW_STATUS    => $signedPayloadInfo->autoRenewStatus,
            START_DATE          => date(DATE_TIME, $signedTransactionInfo->purchaseDate/1000),
            END_DATE          => date(DATE_TIME, $signedTransactionInfo->expiresDate/1000),
            ORIGINAL_TRANSACTION_ID  => $signedPayloadInfo->originalTransactionId
        ];
    }

    public function getPrevSubscriptionDetails($originalTransactionId)
    {
        return Subscription::where(ORIGINAL_TRANSACTION_ID,$originalTransactionId)->orderBy(ID,DESC)->first();
    }

    public function getSubscriptionPlan() {
        return SubscriptionPlan::where([STATUS_ID => ONE, FOR_WHOM => PTB])->get();
    }

    private function updateSubscriptionData($data) {
        $plan = SubscriptionPlan::where(ANDROID_PRODUCT,$data[PRODUCT_ID])->first();
        return $this->setAndCreateSubscriptionData($plan, $data);
    }

    public function getSubcriptionEndBeforeTenDay() {
        return Subscription::with('user')
            ->where(STATUS_ID,ACTIVE)
            ->where(CURRENT_PERIOD_START, '<', Carbon::now()->format(DATE_TIME))
            ->where(CURRENT_PERIOD_START, '<=', Carbon::now()->subMinutes(2)->format(DATE_TIME))
            ->get();
    }

    public function getTrialSubscriptionEndBeforeTenDay() {
        $twentyDaytoday = Carbon::now()->subMinutes(5)->format(DATE_TIME);
        return User::where(CREATED_AT,'<=',$twentyDaytoday)->where(['role_id' => PARENTS_TO_BE,SUBSCRIPTION_STATUS=> SUBSCRIPTION_TRIAL])->orderBy(ID, DESC)->get();
    }

    public function getSubscriptionStatus($userId) {
        $user = User::where([ID => $userId])->first();
        $dateDiff = strtotime(date(YMD_FORMAT)) - strtotime($user->created_at->format(YMD_FORMAT));
        $days = round(($dateDiff / 60));
        $subscription = Subscription::where(USER_ID,$userId)->orderBy('id','desc')->first();
        if ($subscription == null && $user->subscription_status == TWO && $days < 10) {
            $status = SUBSCRIPTION_TRIAL;
        } else {
            $status = SUBSCRIPTION_DISABLED;
            if ($subscription !== null && $subscription->status_id == ACTIVE && ($subscription->current_period_end  > Carbon::now())) {
                $status = SUBSCRIPTION_ENABLED;
            }
        }
        return $status;
    }

    public function getDailiyTrailCardLimit($userId) {
        $maxRequest = THIRTY;
        $sentRequest = ProfileMatch::where([FROM_USER_ID => $userId])->whereDate(CREATED_AT, date(YMD_FORMAT))->get()->count();
        $limit = 0;
        if ($sentRequest < $maxRequest) {
            $limit = $maxRequest - $sentRequest;
        }

        return $limit;
    }

    public function setAndCreateSubscriptionData($plan, $data) {
        $fields[SUBSCRIPTION_PLAN_ID] = $plan->id;
        $fields[PRICE] = $plan->price;
        $fields[PRODUCT_ID] = $data[PRODUCT_ID];
        $fields[CURRENT_PERIOD_START] = $data[START_DATE];
        $fields[CURRENT_PERIOD_END] = $data[END_DATE];
        $fields[ORIGINAL_TRANSACTION_ID] = $data[ORIGINAL_TRANSACTION_ID];
        $prevSubDetails = $this->getPrevSubscriptionDetails($fields[ORIGINAL_TRANSACTION_ID]);
        $fields[USER_ID] = $userId = $prevSubDetails[USER_ID];
        $fields[SUBSCRIPTION_ID] = $prevSubDetails[SUBSCRIPTION_ID];
        $subscriptionFields = $this->setSubscriptionFields($fields);
        Subscription::where(USER_ID,$userId)->where(STATUS_ID,ACTIVE)->update([STATUS_ID => INACTIVE]);
        if(!empty($subscriptionFields)) {
            $this->createNewSubscription($subscriptionFields);
        }

        return true;
    }

    public function getTrialExpiredSubscription() {
        /**$thirtyDaytoday = Carbon::now()->subDays(TWO)->format(YMD_FORMAT);
        return User::whereDate(CREATED_AT,'<=',$thirtyDaytoday)->where(['role_id' => PARENTS_TO_BE,SUBSCRIPTION_STATUS=> SUBSCRIPTION_TRIAL])->orderBy(ID, DESC)->get();**/
        $thirtyDaytoday = Carbon::now()->subMinutes(10)->format(DATE_TIME);
        return User::where(CREATED_AT,'<=',$thirtyDaytoday)->where(['role_id' => PARENTS_TO_BE,SUBSCRIPTION_STATUS=> SUBSCRIPTION_TRIAL])->orderBy(ID, DESC)->get();
    }

    public function getExpiredSubcription() {
        return Subscription::with('user')
            ->where(STATUS_ID,ACTIVE)
            ->where(CURRENT_PERIOD_START, '<', Carbon::now()->format(DATE_TIME))
            ->where(CURRENT_PERIOD_START, '<=', Carbon::now()->format(DATE_TIME))
            ->get();
    }
}
