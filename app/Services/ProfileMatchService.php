<?php

namespace App\Services;

use App\Models\ProfileMatch;
use App\Models\User;
use App\Jobs\SendProfileMatchJob;

class ProfileMatchService
{
    public function profileMatchRequest($input)
    {
        $profile_match = ProfileMatch::where(FROM_USER_ID, $input[FROM_USER_ID])->where(TO_USER_ID, $input[TO_USER_ID])->first();
        
        if(!$profile_match){
            $profile_match = new ProfileMatch();
        }
        $profile_match->from_user_id = $input[FROM_USER_ID];
        $profile_match->to_user_id = $input[TO_USER_ID];
        $profile_match->status = $input[STATUS];
        if($profile_match->save()){
            $message = $this->getMatchRequestMsg($input, $profile_match->id);
            return [SUCCESS => true, DATA => $profile_match, MESSAGE=> $message];
        }
        return [SUCCESS => false];
    }

    private function getMatchRequestMsg($input, $profile_match_id){
        $to_user = User::where(ID, $input[TO_USER_ID])->first();
        $from_user = User::where(ID, $input[FROM_USER_ID])->first();
        switch ($input[STATUS]) {
            case 1:
                $name = ( $from_user->role_id == 2 ) ? $from_user->first_name : $from_user->username;
                $title = 'Profile Match Request.';
                $description = $from_user->role->name .' '. $name. ' sent you a match request. Please accept to start the conversation.';
                $message = __('messages.profile_match.request_sent', [NAME => $name]);
                dispatch(new SendProfileMatchJob($to_user, $profile_match_id, $description, $title));
                break;
            case 2:
                $title = 'Profile Match Request Approved.';
                if($to_user->role_id == 2){
                    $name = $to_user->first_name;
                    $description = 'It\'s a Match! You have a new match with Parent to be '.$name .'.';

                }else{
                    $name = $to_user->username;
                    $description = 'It\'s a Match! You have a new match with '.$to_user->role->name.' '.$name.'.  Please initiate the conversation.';
                }
                $message = __('messages.profile_match.request_approved');
                dispatch(new SendProfileMatchJob($from_user, $profile_match_id, $description, $title));
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
