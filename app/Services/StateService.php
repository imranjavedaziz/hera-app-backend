<?php

namespace App\Services;

use App\Models\State;

class StateService
{
    public function getStates()
    {
        return State::select(ID, CODE, NAME)->where(STATUS_ID, 1)->get();
    }
}
