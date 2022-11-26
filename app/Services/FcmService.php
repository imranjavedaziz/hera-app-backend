<?php

namespace App\Services;

use App\Models\DeviceRegistration;
use App\Models\User;
use Facades\{
    App\Services\SubscriptionService
};
use App\Helpers\CustomHelper;
use App\Models\ProfileMatch;
use App\Models\Feedback;
use App\Traits\FcmTrait;

class FcmService
{
    use FcmTrait;
    public function registerDevice($data)
    {
        $device = DeviceRegistration::where(DEVICE_TOKEN, $data[DEVICE_TOKEN])
            ->orWhere(DEVICE_ID, $data[DEVICE_ID])
            ->first();

        if (empty($device)) {
            $device = new DeviceRegistration;
        }
        $device->user_id = $data[USER_ID];
        $device->device_id = $data[DEVICE_ID];
        $device->device_token = $data[DEVICE_TOKEN];
        $device->device_type = $data[DEVICE_TYPE];
        $device->status_id = 1;
        $device->save();
        return true;
    }

    public function deactivateRegisterDevice($user_id)
    {
        return DeviceRegistration::where(USER_ID, $user_id)->update([STATUS_ID=>2]);
    }

    public function sendPushNotification($input, $sender_id)
    {
        $msgId = ($input[RECEIVER_ID] > $sender_id) ? $input[RECEIVER_ID] : $sender_id;
        $userDevice = DeviceRegistration::where([USER_ID => $input[RECEIVER_ID], STATUS_ID => ONE])->first();
        $sender_user = User::select(ID, USERNAME, ROLE_ID, FIRST_NAME, MIDDLE_NAME, LAST_NAME, PROFILE_PIC, SUBSCRIPTION_STATUS)
        ->where(ID, $sender_id)->first();
        $receiver_user = User::select(ID, USERNAME, ROLE_ID, FIRST_NAME, MIDDLE_NAME, LAST_NAME, PROFILE_PIC, SUBSCRIPTION_STATUS)
        ->where(ID, $input[RECEIVER_ID])->first();
        $profile_match = ProfileMatch::where(function ($query) use ($input, $sender_id) {
            $query->where(FROM_USER_ID, $sender_id);
            $query->where(TO_USER_ID, $input[RECEIVER_ID]);  
        })
        ->orWhere(function ($query) use ($input, $sender_id) {
            $query->where(FROM_USER_ID, $input[RECEIVER_ID]);
            $query->where(TO_USER_ID, $sender_id);  
        })
        ->first();
        $feedback = Feedback::where(SENDER_ID, $input[RECEIVER_ID])->where(RECIPIENT_ID, $sender_id)->first();
        if(!empty($userDevice)) {
            $chatArray[NOTIFY_TYPE] = CHAT;
            $chatArray["chat_start"] = ONE;
            $chatArray["currentRole"] = $sender_user->role_id;
            $chatArray["deviceToken"] = "deviceToken";
            $chatArray["message"] = $input[MESSAGE];
            $chatArray["msgId"] = $msgId."-".time();
            $chatArray["read"] = ZERO;
            $chatArray["feedback_status"] = !empty($feedback) ? $feedback->like : NULL;
            $chatArray["recieverId"] = $sender_id;
            $chatArray["recieverImage"] = $sender_user->profile_pic;
            $chatArray["recieverName"] = CustomHelper::fullName($sender_user);
            $chatArray["recieverUserName"] = $sender_user->username;
            $chatArray["recieverSubscription"] = SubscriptionService::getSubscriptionStatus($sender_user->id);
            $chatArray["senderId"] = $receiver_user->id;
            $chatArray["senderImage"] = $receiver_user->profile_pic;
            $chatArray["senderName"] = CustomHelper::fullName($receiver_user);
            $chatArray["senderUserName"] = $receiver_user->username;
            $chatArray["senderSubscription"] = SubscriptionService::getSubscriptionStatus($receiver_user->id);
            $chatArray["status_id"] = ACTIVE;
            $chatArray[MATCH_REQUEST] = $profile_match;
            $chatArray["time"] = time();
            $chatArray["type"] = "Text";
            $this->sendPush($userDevice->device_token,$input['title'],$input['message'],$chatArray);
            $response = response()->Success(trans('messages.sent_push_notification'));
        } else {
            $response = response()->Success('No device found!');
        }
        return $response;
    }
}
