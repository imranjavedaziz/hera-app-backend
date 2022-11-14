<?php

namespace App\Services;

use App\Models\User;
use DB;

class DonarDashboardService
{
    public function getPtbProfileCard($input)
    {
        $user = User::select(ID, FIRST_NAME, MIDDLE_NAME, LAST_NAME, PROFILE_PIC, ROLE_ID, RECENT_ACTIVITY)
        ->whereHas(USERPROFILE)
        ->whereHas(PARENTSPREFERENCE);

        if(!empty($input[KEYWORD])){
            $user = $user->where(FIRST_NAME, LIKE, '%'.$input[KEYWORD].'%');
        }

        if(!empty($input[STATE_IDS])){
            $user = $user->whereHas(LOCATION, function($q) use ($input) {
                $q->whereIn(STATE_ID, explode(',', $input[STATE_IDS]));
            });
        }else{
            $user = $user->whereHas(LOCATION);
        }

        return $user->where(ROLE_ID, PARENTS_TO_BE)->where(STATUS_ID, ONE)->orderBy(USERS.'.'.RECENT_ACTIVITY, DESC);
    }
}
