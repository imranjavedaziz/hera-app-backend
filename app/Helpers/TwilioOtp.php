<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\PhoneVerification;
use Twilio\Rest\Client;

class TwilioOtp
{
    public static function sendOTPOnPhone($countryCode, $phoneNo){
        try{
            $client = new Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
            $otp =  mt_rand(11,99).mt_rand(11,99).mt_rand(0,9).mt_rand(0,9);
            $client->messages->create(
                $countryCode.$phoneNo,
                array(
                'from' => env('TWILIO_FROM'),
                'body' => $otp .' is your One Time Password for phone verification.'
                )
            );
            $phoneVerify = PhoneVerification::firstOrNew([COUNTRY_CODE => $countryCode, PHONE_NO => $phoneNo]);
            $otpBlockedTime = $phoneVerify->otp_block_time - Carbon::now()->getTimestamp();
            if ($otpBlockedTime >= ONE) {
                PhoneVerification::where([COUNTRY_CODE => $countryCode, PHONE_NO => $phoneNo])->update([MAX_ATTEMPT => ZERO]);
                return [MESSAGE => __('messages.MOBILE_OTP_EXCEEDED_ATTEMPT'), STATUS => false];
            }
            $phoneVerify->otp_block_time = null;
            $attempt = $phoneVerify->max_attempt;
            $attempt++; 
            if($attempt == 5){
                $phoneVerify->otp_block_time = Carbon::now()->getTimestamp() + (60 * 60 * 24);              
            }
            $phoneVerify->otp = $otp;
            $phoneVerify->max_attempt = $attempt;
            $phoneVerify->save();

            return [MESSAGE => __('messages.MOBILE_OTP_SENT_SUCCESS'), STATUS => true];
        } catch (\Exception $e) {
            return [MESSAGE => __('messages.INVALID_MOBILE'), STATUS => false];
        }
    }

    public static function otpVerification($data){
        $isVerifyeOtp = PhoneVerification::where([COUNTRY_CODE => $data[COUNTRY_CODE],PHONE_NO => $data[PHONE_NO]])->where(OTP,$data[OTP])->first();
        if($isVerifyeOtp) {
            $otpExpired = $isVerifyeOtp[UPDATED_AT] <= (Carbon::now()->subMinutes(30)->toDateTimeString());
            if ($otpExpired) {
                return [STATUS => false, MESSAGE => __('messages.MOBILE_OTP_EXPIRED')];
            }
            PhoneVerification::where([COUNTRY_CODE => $data[COUNTRY_CODE],PHONE_NO => $data[PHONE_NO]])->where(OTP,$data[OTP])->delete();
            $data =[MESSAGE => __('messages.MOBILE_OTP_SUCCESS'), STATUS=> true];        
        }else{
            $data =[MESSAGE => __('messages.MOBILE_OTP_FAIL'), STATUS=> false];
        }

        return $data;
    }
}
