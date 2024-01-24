<?php

namespace App\Services;

use App\Models\State;

class StateService
{
    public function getStates()
    {
        $dbStates = State::select(ID, CODE, NAME)->where('STATUS_ID', 1)->get();
        $customData = [
            ID   => ZERO,
            CODE => NO_PREFERENCE,
            NAME => NO_PREFERENCE
        ];
        $dbStatesArray = $dbStates->toArray();
        array_unshift($dbStatesArray, $customData);

        return $dbStatesArray;
    }
}
