<?php

namespace App\Services;

use App\Models\ProfileMatchUnmatch;

class ProfileMatchUnmatchService
{
    public function profileMatchUnmatch($user_id, $input)
    {
        $input[FROM_USER_ID] = $user_id;
        $profile_match_unmatch = ProfileMatchUnmatch::where(FROM_USER_ID, $input[FROM_USER_ID])->where(TO_USER_ID, $input[TO_USER_ID])->first();
        if(!$profile_match_unmatch){
            $profile_match_unmatch = new ProfileMatchUnmatch();
        }
        $profile_match_unmatch->from_user_id = $input[FROM_USER_ID];
        $profile_match_unmatch->to_user_id = $input[TO_USER_ID];
        $profile_match_unmatch->status = $input[STATUS];
        if($profile_match_unmatch->save()){
            $message = $this->getMatchUnmatchMsg($input[STATUS]);
            return [SUCCESS => true, DATA => $profile_match_unmatch, MESSAGE=> $message];
        }
        return [SUCCESS => false];
    }

    private function getMatchUnmatchMsg($status){
        switch ($status) {
            case 1:
                $message = __('messages.profile_match_unmatch.request_sent');
                break;
            case 2:
                $message = __('messages.profile_match_unmatch.request_approved');
                break;
            default:
                $message = __('messages.profile_match_unmatch.request_rejected');
                break;
        }
        return $message;
    }

    public function getProfileMatches($user_id)
    {
        return ProfileMatchUnmatch::with([
            FROMUSER => function($q) {
                return $q->select([
                    ID, ROLE_ID, USERNAME, FIRST_NAME, MIDDLE_NAME, LAST_NAME, EMAIL, PHONE_NO, PROFILE_PIC
                ]);
            },
            TOUSER => function($q) {
                return $q->select([
                    ID, ROLE_ID, USERNAME, FIRST_NAME, MIDDLE_NAME, LAST_NAME, EMAIL, PHONE_NO, PROFILE_PIC
                ]);
            }])->where(FROM_USER_ID, $user_id)->get();
    }
}
