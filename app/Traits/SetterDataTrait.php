<?php

namespace App\Traits;

use App\Models\Education;
use App\Models\Ethnicity;
use App\Models\EyeColour;
use App\Models\Gender;
use App\Models\HairColour;
use App\Models\Height;
use App\Models\Race;
use App\Models\RelationshipStatus;
use App\Models\Role;
use App\Models\SexualOrientation;
use App\Models\Weight;
use DB;

trait setterDataTrait
{

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

    private function getHeightData()
    {
        return Height::select(ID, NAME)->where(STATUS_ID, 1)->get();
    }

    private function getWeightData()
    {
        return Weight::select(ID, NAME)->where(STATUS_ID, 1)->get();
    }

    private function getEducationData()
    {
        return Education::select(ID, NAME)->where(STATUS_ID, 1)->get();
    }

    private function getRoleData()
    {
        return Role::select(ID, NAME)->where(ID, '>', 2)->get();
    }

    private function setUserName($role_id, $user_id)
    {
        switch ($role_id) {
            case 2:
                $username = 'PTB'.rand(1000, 9999).$user_id;
                break;
            case 3:
                $username = 'SM'.rand(1000, 9999).$user_id;
                break;
            case 4:
                $username = 'ED'.rand(1000, 9999).$user_id;
                break;
            case 5:
                $username = 'SD'.rand(1000, 9999).$user_id;
                break;
            default:
            $username = 'MBC'.rand(1000, 9999).$user_id;
                break;
        }
        return $username;
    }
}
