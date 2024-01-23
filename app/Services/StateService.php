<?php

namespace App\Services;

use App\Models\State;

class StateService
{
    public function getStates()
    {
        $dbStates = State::select(ID, CODE, NAME)->where('STATUS_ID', 1)->get();
        $customData = [
            'id'   => 0,
            'code' => 'No Preference',
            'name' => 'No Preference',
        ];
        $dbStatesArray = $dbStates->toArray();
        array_unshift($dbStatesArray, $customData);
echo"<pre>";
print_r($customData);exit;
        return $dbStatesArray;
    }
}
