<?php

namespace App\Services;

use App\Models\ProfileMatch;
use App\Models\User;
use App\Jobs\SendProfileMatchJob;
use App\Jobs\FirebaseChatFriend;

class ProfileMatchService
{
    public function profileMatchRequest($user_id, $input)
    {
        $input[FROM_USER_ID] = $user_id;
         $profile_match = ProfileMatch::where(function ($query) use ($input) {
            $query->where(FROM_USER_ID, $input[FROM_USER_ID]);
            $query->where(TO_USER_ID, $input[TO_USER_ID]);  
        })
        ->orWhere(function ($query) use ($input) {
            $query->where(FROM_USER_ID, $input[TO_USER_ID]);
            $query->where(TO_USER_ID, $input[FROM_USER_ID]);  
        })
        ->first();
        if($profile_match){
            $input[STATUS] = ($input[STATUS] == 3) ? $input[STATUS] : 2;
            $profile_match->status = $input[STATUS]; 
        }else{
            $profile_match = new ProfileMatch();
            $profile_match->status = $input[STATUS];
            $profile_match->from_user_id = $input[FROM_USER_ID];
            $profile_match->to_user_id = $input[TO_USER_ID];
        }
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
                /**dispatch(new FirebaseChatFriend($from_user, $to_user, SENT_REQUEST));**/
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
                /**dispatch(new FirebaseChatFriend($from_user, $to_user, APPROVED_REQUEST));**/
                break;
            default:
                $message = __('messages.profile_match.request_rejected');
                /**dispatch(new FirebaseChatFriend($from_user, $to_user, REJECTED_REQUEST));**/
                break;
        }
        return $message;
    }
    public function profileMatchRequestResponse($user_id, $input)
    {
        $profile_match = ProfileMatch::where(ID, $input[ID])->first();
        if($profile_match->update([STATUS => $input[STATUS]])){
            $input[FROM_USER_ID] = $user_id;
            $input[TO_USER_ID] = $profile_match->from_user_id == $user_id ? $profile_match->to_user_id : $profile_match->from_user_id;
            $message = $this->getMatchRequestMsg($input, $input[ID]);
            return [SUCCESS => true, DATA => $profile_match, MESSAGE=> $message];
        }
        return [SUCCESS => false];
    }

    public function getProfileMatches($userId)
    {
        return ProfileMatch::with([
            TOUSER => function($q) {
                return $q->select([
                    ID, ROLE_ID, USERNAME, FIRST_NAME, MIDDLE_NAME, LAST_NAME, EMAIL, PHONE_NO, PROFILE_PIC,
                ])->with([NOTIFICATION => function ($query) {
                    $query->orderBy(ID, DESC);
                    $query->limit(ONE);
                }]);
            },
            FROMUSER => function($q) {
                return $q->select([
                    ID, ROLE_ID, USERNAME, FIRST_NAME, MIDDLE_NAME, LAST_NAME, EMAIL, PHONE_NO, PROFILE_PIC
                ])->with([NOTIFICATION =>function ($query) {
                    $query->orderBy(ID, DESC);
                    $query->limit(ONE);
                }]);
            }])->where(function ($query) use ($userId) {
                $query->where(FROM_USER_ID, $userId);
                $query->orWhere(TO_USER_ID, $userId);
            })->where(STATUS,APPROVED_AND_MATCHED)->get();
    }
}
