<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

trait FcmTrait
{
    public static function sendPush($deviceToken,$title,$body,$data) {
        try {
            $response = [];
            $optionBuilder = new OptionsBuilder();
            $optionBuilder->setTimeToLive(60*20);

            $notificationBuilder = new PayloadNotificationBuilder($title);
            $notificationBuilder->setBody($body)
                                ->setSound('default');

            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData($data);

            $option = $optionBuilder->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();

            $downstreamResponse = FCM::sendTo($deviceToken, $option, $notification, $data);

            $response['numberSuccess'] = $downstreamResponse->numberSuccess();
            $response['numberFailure'] = $downstreamResponse->numberFailure();
            $response['numberModification'] = $downstreamResponse->numberModification();

            // return Array - you must remove all this tokens in your database
            $response['tokensToDelete'] = $downstreamResponse->tokensToDelete();

            // return Array (key : oldToken, value : new token - you must change the token in your database)
            $response['tokensToModify'] = $downstreamResponse->tokensToModify();

            // return Array - you should try to resend the message to the tokens in the array
            $response['tokensToRetry'] = $downstreamResponse->tokensToRetry();

            // return Array (key:token, value:error) - in production you should remove from your database the tokens
            $response['tokensWithError'] = $downstreamResponse->tokensWithError();

            return $response;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}