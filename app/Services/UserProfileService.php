<?php

namespace App\Services;

use App\Models\User;
use App\Models\CtaNextStep;
use App\Models\ProfileMatch;
use App\Helpers\AuthHelper;
use DB;
use Facades\{
    App\Services\FcmService,
};

class UserProfileService
{
    public function getDonerProfileDetails($input)
    {
        $user = User::select(ID, USERNAME, ROLE_ID, PROFILE_PIC, DOB, SUBSCRIPTION_STATUS)
        ->selectRaw('(select name from roles where id='.ROLE_ID.AS_CONNECT.ROLE.' ')
        ->selectRaw('DATE_FORMAT(FROM_DAYS(DATEDIFF(now(),dob)), "%Y")+0 AS age')
        ->with([
            USERPROFILE => function($q) {
                return $q->select(ID, USER_ID, OCCUPATION, BIO);
            },
            DONERATTRIBUTE => function($q) {
                return $q->select(ID, USER_ID, HEIGHT_ID, RACE_ID, MOTHER_ETHNICITY_ID, FATHER_ETHNICITY_ID, WEIGHT_ID, HAIR_COLOUR_ID, EYE_COLOUR_ID)
                ->selectRaw('(select name from heights where id='.HEIGHT_ID.AS_CONNECT.HEIGHT.' ')
                ->selectRaw('(select name from races where id='.RACE_ID.AS_CONNECT.RACE.' ')
                ->selectRaw('(select name from ethnicities where id='.MOTHER_ETHNICITY_ID.AS_CONNECT.MOTHER_ETHNICITY.' ')
                ->selectRaw('(select name from ethnicities where id='.FATHER_ETHNICITY_ID.AS_CONNECT.FATHER_ETHNICITY.' ')
                ->selectRaw('(select name from weights where id='.WEIGHT_ID.AS_CONNECT.WEIGHT.' ')
                ->selectRaw('(select name from hair_colours where id='.HAIR_COLOUR_ID.AS_CONNECT.HAIR_COLOUR.' ')
                ->selectRaw('(select name from eye_colours where id='.EYE_COLOUR_ID.AS_CONNECT.EYE_COLOUR.' ')
                ->selectRaw('(select name from education where id='.EDUCATION_ID.AS_CONNECT.EDUCATION.' ');
            }, LOCATION, DONERPHOTOGALLERY, DONERVIDEOGALLERY
        ])->where(ID, $input[USER_ID])->first();
        $fromUserId = AuthHelper::authenticatedUser()->id;
        $input[RECEIVER_ID] = $fromUserId; /** use sender id  as reciver_id for matching chat section **/
        $input[MESSAGE] = "";
        $user->profile_match_request = $this->profileMatchRequest($input[RECEIVER_ID], $input[USER_ID]);
        $user->profile_match_chat = FcmService::sendPushNotification($input, $input[USER_ID],false);
        $ctaNextStep = CtaNextStep::where([FROM_USER_ID => $fromUserId, TO_USER_ID => $input[USER_ID]])->first();
        $user->next_step = !empty($ctaNextStep) ? true :false;
        return $user;
    }

    public function getPtbProfileDetails($input)
    {
        $user = User::select(ID, FIRST_NAME, MIDDLE_NAME, LAST_NAME, ROLE_ID, PROFILE_PIC)
        ->selectRaw('(select name from roles where id='.ROLE_ID.AS_CONNECT.ROLE.' ')
        ->selectRaw('DATE_FORMAT(FROM_DAYS(DATEDIFF(now(),dob)), "%Y")+0 AS age')
        ->with([
            USERPROFILE => function($q) {
                return $q->select(ID, USER_ID, BIO, OCCUPATION, GENDER_ID, SEXUAL_ORIENTATION_ID, RELATIONSHIP_STATUS_ID)
                ->selectRaw('(select name from genders where id='.GENDER_ID.AS_CONNECT.GENDER.' ')
                ->selectRaw('(select name from sexual_orientations where id='.SEXUAL_ORIENTATION_ID.AS_CONNECT.SEXUAL_ORIENTATION.' ')
                ->selectRaw('(select name from relationship_statuses where id='.RELATIONSHIP_STATUS_ID.AS_CONNECT.RELATIONSHIP_STATUS.' ');
            }, LOCATION, DONERVIDEOGALLERY
        ])->where(ID, $input[USER_ID])->first();

        $input[RECEIVER_ID] = AuthHelper::authenticatedUser()->id;
        $input[MESSAGE] = "";
        $user->profile_match_request = $this->profileMatchRequest($input[RECEIVER_ID], $input[USER_ID]);
        $user->profile_match_chat = FcmService::sendPushNotification($input, $input[USER_ID],false);
        return $user;
    }

    private function profileMatchRequest($from_user_id, $to_user_id){
        return ProfileMatch::select(FROM_USER_ID, TO_USER_ID, STATUS, UPDATED_AT, CREATED_AT)
        ->where(function ($query) use ($from_user_id, $to_user_id) {
            $query->where(FROM_USER_ID, $from_user_id);
            $query->where(TO_USER_ID, $to_user_id);  
        })
        ->orWhere(function ($query) use ($from_user_id, $to_user_id) {
            $query->where(FROM_USER_ID, $to_user_id);
            $query->where(TO_USER_ID, $from_user_id);  
        })
        ->first();
    }
}
