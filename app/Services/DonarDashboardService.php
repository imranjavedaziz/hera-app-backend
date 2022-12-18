<?php

namespace App\Services;

use App\Models\User;
use DB;
use App\Helpers\AuthHelper;
use App\Models\ProfileMatch;

class DonarDashboardService
{
    public function getPtbProfileCard($input)
    {
        $role_id = AuthHelper::authenticatedUser()->id;
        $donerSentRequest = ProfileMatch::where([FROM_USER_ID => $role_id])->get()->pluck(TO_USER_ID)->toArray();
        $donerRejecteRequest = ProfileMatch::where([TO_USER_ID => $role_id])
        ->whereIn(STATUS, [PENDING_FOR_APPROVAL, APPROVED_AND_MATCHED, REJECTED_BY_DONAR])
        ->get()
        ->pluck(FROM_USER_ID)
        ->toArray();
        $excludePtb = array_merge($donerSentRequest, $donerRejecteRequest);

        $user = User::select(ID, FIRST_NAME, MIDDLE_NAME, LAST_NAME, PROFILE_PIC, ROLE_ID, RECENT_ACTIVITY)
        ->whereHas(USERPROFILE)
        ->whereHas(PARENTSPREFERENCE, function ($q) use ($role_id) {
            $q->where(ROLE_ID_LOOKING_FOR, $role_id);
        })
        ->whereNotIn(ID, $excludePtb);

        if (!empty($input[KEYWORD])) {
            $user = $user->where(FIRST_NAME, LIKE, '%'.$input[KEYWORD].'%');
        }

        if (!empty($input[STATE_IDS])) {
            $user = $user->whereHas(LOCATION, function ($q) use ($input) {
                $q->whereIn(STATE_ID, explode(',', $input[STATE_IDS]));
            });
        } else {
            $user = $user->whereHas(LOCATION);
        }

        return $user->with(LOCATION)->where([ROLE_ID => PARENTS_TO_BE, STATUS_ID => ONE, REGISTRATION_STEP => THREE])->where(SUBSCRIPTION_STATUS, '!=', ZERO)->orderBy(USERS.'.'.RECENT_ACTIVITY, DESC);
    }
}
