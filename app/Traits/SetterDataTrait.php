<?php

namespace App\Traits;

use App\Models\Gender;
use App\Models\RelationshipStatus;
use App\Models\SexualOrientation;
use App\Models\Ethnicity;
use App\Models\EyeColour;
use App\Models\HairColour;
use App\Models\Race;
use DB;

trait setterDataTrait {

    private function getGenderData()
    {
        return Gender::select(ID, NAME)->where(STATUS_ID, 1)->get();
    }

    private function getRelationshipStatusData()
    {
        return RelationshipStatus::select(ID, NAME)->where(STATUS_ID, 1)->get();
    }

    private function getSexualOrientationData()
    {
        return SexualOrientation::select(ID, NAME)->where(STATUS_ID, 1)->get();
    }

    private function getEthnicityData()
    {
        return Ethnicity::select(ID, NAME)->where(STATUS_ID, 1)->get();
    }

    private function getEyeColourData()
    {
        return EyeColour::select(ID, NAME)->where(STATUS_ID, 1)->get();
    }

    private function getHairColourData()
    {
        return HairColour::select(ID, NAME)->where(STATUS_ID, 1)->get();
    }

    private function getRaceData()
    {
        return Race::select(ID, NAME)->where(STATUS_ID, 1)->get();
    }
}
