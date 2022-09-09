<?php

namespace App\Services;

use App\Models\User;
use DB;

class DonarDashboardService
{
    public function getDonarProfileCard()
    {
        return User::select(ID,FIRST_NAME,MIDDLE_NAME,LAST_NAME,ROLE_ID,COUNTRY_CODE,PHONE_NO,EMAIL, PROFILE_PIC, RECENT_ACTIVITY)->where(ROLE_ID, PARENTS_TO_BE)
        ->selectRaw('DATE_FORMAT(FROM_DAYS(DATEDIFF(now(),dob)), "%Y")+0 AS age')
        ->with(['user_profile' => function($q) {
            return $q->select(['id','user_id','dob','gender_id','sexual_orientations_id','relationship_status_id','occupation','bio'
            ]);
        }])
        ->orderBy('users.recent_activity', DESC);
    }
}
