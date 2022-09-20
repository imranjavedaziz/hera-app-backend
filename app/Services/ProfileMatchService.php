<?php

namespace App\Services;

use App\Models\ProfileMatch;
use App\Models\User;

class ProfileMatchService
{
    public function profileMatchRequest($user_id, $input)
    {
        $input[FROM_USER_ID] = $user_id;
        $profile_match = ProfileMatch::where(FROM_USER_ID, $input[FROM_USER_ID])->where(TO_USER_ID, $input[TO_USER_ID])->first();
        if(!$profile_match){
            $profile_match = new ProfileMatch();
        }
        $profile_match->from_user_id = $input[FROM_USER_ID];
        $profile_match->to_user_id = $input[TO_USER_ID];
        $profile_match->status = $input[STATUS];
        if($profile_match->save()){
            /*****Notification Code****/
            $message = $this->getMatchRequestMsg($input[STATUS], User::where(ID, $input[TO_USER_ID])->first());
            return [SUCCESS => true, DATA => $profile_match, MESSAGE=> $message];
        }
        return [SUCCESS => false];
    }

    private function getMatchRequestMsg($status, $user){
        switch ($status) {
            case 1:
                $message = __('messages.profile_match.request_sent', [NAME => $user->first_name]);
                break;
            case 2:
                $message = __('messages.profile_match.request_approved');
                break;
            default:
                $message = __('messages.profile_match.request_rejected');
                break;
        }
        return $message;
    }

    public function getProfileMatches($user_id)
    {
        return ProfileMatch::with([
            TOUSER => function($q) {
                return $q->select([
                    ID, ROLE_ID, USERNAME, FIRST_NAME, MIDDLE_NAME, LAST_NAME, EMAIL, PHONE_NO, PROFILE_PIC
                ]);
            }])->where(FROM_USER_ID, $user_id)->get();
    }
}
