<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Status;
use App\Models\Favorite;
use App\Models\Location;
use App\Models\Role;
use App\Models\SubscriptionPlan;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\DB;

class CustomHelper
{
    public static function phoneNumberFormat($phone) {
        $phone  = preg_replace(preg_quote("/[^\d]/"),"",$phone);
        if(strlen($phone) == TEN) {
            $phone = preg_replace(preg_quote("/^1?(\d{3})(\d{3})(\d{4})$/"), "$1-$2-$3", $phone);
        }
        return $phone;
    }
        
    public static function removePhoneNumberFormat($phone) {
        return preg_replace(preg_quote("/[^\d]/"),"",$phone);
    }

    public static function nameRegex() {
        return 'regex:/^[a-zA-Z\s\. ]+$/';
    }

    public static function emailRegex() {
        return 'regex:/^([a-zA-Z0-9\+_\-]+)(\.[a-zA-Z0-9\+_\-]+)*@([a-zA-Z0-9\-]+\.)+[a-zA-Z]{2,6}$/';
    }

    public static function phoneRegex() {
        return 'regex:/^\s*(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\s*$/';
    }

    public static function passwordRegex() {
        return 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    }

    public static function urlRegex() {
        return 'regex:/^\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;A-Z]*[-a-z0-9+&@#\/%=~_|A-Z]$/';
    }

    public static function ageCalculator($dob) {
        if(!empty($dob)){
            $birthdate = new DateTime($dob);
            $today = new DateTime('today');
            return $birthdate->diff($today)->y;
        }else{
            return ZERO;
        }
    }

    public static function dateConvert($date, $format) {
        return date_format(date_create($date), $format);
    }

    public static function dateTimeZoneConversion($dateTime,$toTimeZone) {
        $dt = new DateTime($dateTime, new DateTimeZone(UTC));
        $dt->setTimezone(new DateTimeZone($toTimeZone));
        return $dt->format('M d, Y');
    }

    public static function fullName($user) {
        $fullName = $user->first_name.' '. $user->last_name;
        if(!empty($user->middle_name)) {
            $fullName = $user->first_name.' '.$user->middle_name.' '. $user->last_name;
        }

        return $fullName;
    }

    public static function getLocation($userId) {
       $location = Location::getLocationByUserId($userId);
       return !empty($location) ? $location->state_name.', '.$location->zipcode : 'N/A';
    }

    public static function getRoleName($roleId) {
        return Role::getRoleById($roleId);
    }

    public static function getStatusName($id) {
        return Status::getStatusById($id);
    }

    public static function totalSubscriptionAmountDeduct($deviceType, $amount) {
        $totalAmount = ($deviceType == 'ios') ? $amount - ($amount  * APPLE_CHARGES /100) : $amount - ($amount  * GOOGLE_CHARGES /100);
        return number_format($totalAmount, 2, '.', '');
    }

    public static function getDeleteInactiveMsg($user){
        switch ($user) {
            case ($user->deleted_by == ONE && $user->deleted_at != null):
                $message = trans('messages.user_account_deleted_by_admin');
                break;
            case ($user->deleted_by == TWO && $user->deleted_at != null):
                $message = trans('messages.user_account_deleted');
                break;
            case ($user->deactivated_by == ONE):
                $message = trans('messages.user_account_deactivated_by_admin');
                break;
            default:
                $message = trans('messages.invalid_user_pass');
                break;
        }
        return $message;
    }

    public static function getNotifyMessage($notifyType) {
        $message = trans('messages.notify_status_in_active');
        if ($notifyType) {
            $message = trans('messages.notify_status_active');
        }
        return $message;
    }

    public static function createRefreshTokenForUser(User $user): string
    {
        $data = serialize([
            USER_ID => $user->id
        ]);
        $iv = openssl_random_pseudo_bytes(IV_LENGTH);
        $token = openssl_encrypt($data, CIPHER_REFRESH_TOKEN, env('JWT_SECRET'), OPENSSL_RAW_DATA, $iv);
        $token = base64_encode($iv . $token);
        $user->timestamps = false;
        User::where(ID, $user->id)->update([REFRESH_TOKEN => $token]);
        return $token;
    }

    public static function getProductAmount($productId) {
        $subscriptionPlan = SubscriptionPlan::where('product_id',$productId)->first();
        return !empty($subscriptionPlan) ? $subscriptionPlan->unit_amount : 0;
    }
}
