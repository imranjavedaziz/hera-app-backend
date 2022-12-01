<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Facades\{
    App\Services\SubscriptionService,
};
use Log;
use DB;

class InAppWebhookController extends Controller
{
    public function iosSubscriptionEvent(Request $request)
    {
        try {
            Log::info("Ios web hook calling :". json_encode($request->all()));
            SubscriptionService::updateIosSubscription($request->all());

        } catch(\Exception $e) {
            Log::error("In-App Exception : " . json_encode($e->getMessage()));
            http_response_code(400);
            exit();
        }
        http_response_code(200);
    }

    public function androidSubscriptionEvent(Request $request)
    {
        try {
            $event = $this->decodeDataPayload($request->all());
            SubscriptionService::updateAndroidSubscription($event);

        } catch(\Exception $e) {
            Log::error("In-App Exception : " . json_encode($e->getMessage()));
            http_response_code(400);
            exit();
        }
        http_response_code(200);
    }

    private function decodeDataPayload($dataPayload) 
    {
        $payloadInfo = json_decode(base64_decode($dataPayload['message'][DATA]));
        $subcription = $payloadInfo->subscriptionNotification;
        return [
            NOTIFICATION_TYPE => $subcription->notificationType,
            PRODUCT_ID => $subcription->subscriptionId,
            PURCHASE_TOKEN => $subcription->purchaseToken,
        ];
    }    
}
