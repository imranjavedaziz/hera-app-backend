<?php

namespace App\Services;

use App\Models\User;
use DB;
class UserProfileService
{
    public function getDonerProfileDetails($input)
    {
        return User::select(ID, USERNAME, ROLE_ID, PROFILE_PIC)
        ->selectRaw('(select name from roles where id='.ROLE_ID.') AS '.ROLE.' ')
        ->with([
            USER_PROFILE => function($q) {
                return $q->select(ID, USER_ID, DOB, OCCUPATION, BIO)
                ->selectRaw('DATE_FORMAT(FROM_DAYS(DATEDIFF(now(),dob)), "%Y")+0 AS age');
            },
            DONERATTRIBUTE => function($q) {
                return $q->select(ID, USER_ID, HEIGHT_ID, RACE_ID, MOTHER_ETHNICITY_ID, FATHER_ETHNICITY_ID, WEIGHT_ID, HAIR_COLOUR_ID, EYE_COLOUR_ID)
                ->selectRaw('(select name from heights where id='.HEIGHT_ID.') AS '.HEIGHT.' ')
                ->selectRaw('(select name from races where id='.RACE_ID.') AS '.RACE.' ')
                ->selectRaw('(select name from ethnicities where id='.MOTHER_ETHNICITY_ID.') AS '.MOTHER_ETHNICITY.' ')
                ->selectRaw('(select name from ethnicities where id='.FATHER_ETHNICITY_ID.') AS '.FATHER_ETHNICITY.' ')
                ->selectRaw('(select name from weights where id='.WEIGHT_ID.') AS '.WEIGHT.' ')
                ->selectRaw('(select name from hair_colours where id='.HAIR_COLOUR_ID.') AS '.HAIR_COLOUR.' ')
                ->selectRaw('(select name from eye_colours where id='.EYE_COLOUR_ID.') AS '.EYE_COLOUR.' ');
            },
        ])->where(ID, $input[USER_ID])->get();
    }

    public function getPtbProfileDetails($input)
    {
        return User::select(ID, ROLE_ID, PROFILE_PIC)
        ->selectRaw('(select name from roles where id='.ROLE_ID.') AS '.ROLE.' ')
        ->with([
            USER_PROFILE => function($q) {
                return $q->select(ID, USER_ID, BIO, GENDER_ID, SEXUAL_ORIENTATION_ID, RELATIONSHIP_STATUS_ID)
                ->selectRaw('DATE_FORMAT(FROM_DAYS(DATEDIFF(now(),dob)), "%Y")+0 AS age')
                ->selectRaw('(select name from heights where id='.GENDER_ID.') AS '.GENDER.' ')
                ->selectRaw('(select name from heights where id='.SEXUAL_ORIENTATION_ID.') AS '.SEXUAL_ORIENTATION.' ')
                ->selectRaw('(select name from heights where id='.RELATIONSHIP_STATUS_ID.') AS '.RELATIONSHIP_STATUS.' ');
            },
        ])->where(ID, $input[USER_ID])->get();
    }
}
