<?php

namespace App\Services;

use App\Models\ProfileMatch;
use App\Models\User;
use App\Models\Feedback;
use App\Models\NotificationSetting;
use App\Jobs\SendProfileMatchJob;
use App\Jobs\FirebaseChatFriend;

class ProfileMatchService
{
    public function profileMatchRequest($user_id, $input)
    {
        $input[FROM_USER_ID] = $user_id;
        $to_user = User::where(ID, $input[TO_USER_ID])->where(DELETED_AT, null)->first();
        $from_user = User::where(ID, $input[FROM_USER_ID])->where(DELETED_AT, null)->first();

        $profile_match = ProfileMatch::where(function ($query) use ($input) {
            $query->where(FROM_USER_ID, $input[FROM_USER_ID]);
            $query->where(TO_USER_ID, $input[TO_USER_ID]);
        })
        ->orWhere(function ($query) use ($input) {
            $query->where(FROM_USER_ID, $input[TO_USER_ID]);
            $query->where(TO_USER_ID, $input[FROM_USER_ID]);
        })
        ->first();
        if ($profile_match) {
            if ($profile_match->from_user_id != $input[FROM_USER_ID]) {
                $input[STATUS] = ($input[STATUS] == 3) ? $input[STATUS] : 2;
                $profile_match->status = $input[STATUS];
            }
        } else {
            $profile_match = new ProfileMatch();
            $profile_match->status = $input[STATUS];
            $profile_match->from_user_id = $input[FROM_USER_ID];
            $profile_match->to_user_id = $input[TO_USER_ID];
        }
        if (!empty($to_user)) {
            $profile_match->save();
            $message = $this->getMatchRequestMsg($from_user, $to_user, $input, $profile_match);
            return [SUCCESS => true, DATA => $profile_match, MESSAGE=> $message];
        }
        return [SUCCESS => false];
    }

    private function getMatchRequestMsg($from_user, $to_user, $input, $profile_match)
    {
        $toUserNotify = NotificationSetting::where([USER_ID => $input[TO_USER_ID], NOTIFY_STATUS => ONE])->first();
        switch ($input[STATUS]) {
            case 1:
                $to_name = ($to_user->role_id == 2) ? $to_user->first_name : $to_user->username;
                $name = ($from_user->role_id == 2) ? $from_user->first_name : $from_user->username;
                $title = 'Match Request!';
                $description = $from_user->role->name .' '. $name. ' sent you a match request. Please accept to start the conversation.';
                $message = __('messages.profile_match.request_sent', [NAME => $to_name]);
                $feedback = Feedback::where(SENDER_ID, $input[TO_USER_ID])->where(RECIPIENT_ID, $input[FROM_USER_ID])->first();
                if ($from_user->role_id == 2) {
                    if (!empty($toUserNotify) && $profile_match->from_user_id != $input[FROM_USER_ID]) {
                        dispatch(new SendProfileMatchJob($to_user, $from_user, $profile_match, $description, $title, $feedback));
                    }
                    dispatch(new FirebaseChatFriend($from_user, $to_user, SENT_REQUEST));
                }
                break;
            case 2:
                $title = 'It\'s a Match!';
                $message = __('messages.profile_match.request_approved');
                $feedback = Feedback::where(SENDER_ID, $input[FROM_USER_ID])->where(RECIPIENT_ID, $input[TO_USER_ID])->first();
                if ($to_user->role_id == 2) {
                    //notification to ptb
                    $name = $from_user->username;
                    $description = 'You matched with '.$from_user->role->name.' '.$name.'.  Please initiate the conversation.';
                }else {
                    //notification to donor
                    $name = $from_user->first_name;
                    $description = 'You have a new match with Intended Parent '.$name .'.';
                }
                $this->sendProfileMatchNotification($toUserNotify, $to_user, $from_user, $profile_match, $description, $title, $feedback);
                dispatch(new FirebaseChatFriend($from_user, $to_user, APPROVED_REQUEST));
                break;
            default:
                $message = __('messages.profile_match.request_rejected');
                dispatch(new FirebaseChatFriend($from_user, $to_user, REJECTED_REQUEST));
                break;
        }
        return $message;
    }

    private function sendProfileMatchNotification($userNotify, $from_user, $to_user, $profile_match, $description, $title, $feedback)
    {
        if (!empty($userNotify)) {
            dispatch(new SendProfileMatchJob($from_user, $to_user, $profile_match, $description, $title, $feedback));
        }
        return true;
    }

    public function profileMatchRequestResponse($user_id, $input)
    {
        $profile_match = ProfileMatch::where(ID, $input[ID])->first();

        $input[FROM_USER_ID] = $user_id; //donor id
        $input[TO_USER_ID] = ($profile_match->from_user_id == $user_id) ? $profile_match->to_user_id : $profile_match->from_user_id; //ptb id
        $to_user = User::where(ID, $input[TO_USER_ID])->where(DELETED_AT, null)->first();
        $from_user = User::where(ID, $input[FROM_USER_ID])->where(DELETED_AT, null)->first();
        $profile_match->status = $input[STATUS];
        if (!empty($to_user)) {
            $profile_match->save();
            $message = $this->getMatchRequestMsg($from_user, $to_user, $input, $profile_match);
            return [SUCCESS => true, DATA => $profile_match, MESSAGE=> $message];
        }
        return [SUCCESS => false];
    }

    public function getProfileMatches($userId)
    {
        return ProfileMatch::with([
            TOUSER => function ($q) {
                return $q->select([
                    ID, ROLE_ID, USERNAME, FIRST_NAME, MIDDLE_NAME, LAST_NAME, EMAIL, PHONE_NO, PROFILE_PIC,
                ])->with([NOTIFICATION => function ($query) {
                    $query->orderBy(ID, DESC);
                    $query->limit(ONE);
                }]);
            },
            FROMUSER => function ($q) {
                return $q->select([
                    ID, ROLE_ID, USERNAME, FIRST_NAME, MIDDLE_NAME, LAST_NAME, EMAIL, PHONE_NO, PROFILE_PIC
                ])->with([NOTIFICATION =>function ($query) {
                    $query->orderBy(ID, DESC);
                    $query->limit(ONE);
                }]);
            }])->where(function ($query) use ($userId) {
                $query->where(FROM_USER_ID, $userId);
                $query->orWhere(TO_USER_ID, $userId);
            })->where(STATUS, APPROVED_AND_MATCHED)->get();
    }
}
