<?php

namespace App\Traits;

use App\Models\User;
use App\Models\ProfileMatch;
use Illuminate\Database\Eloquent\Collection;
use App\Helpers\AuthHelper;
use App\Helpers\CustomHelper;
use Log;

trait ParentsToBeMatchedDonerTrait
{
    public function myMatchedDonars(): array
    {
        $donarBaseCondition = [
            [STATUS_ID, '=', ACTIVE],
            [REGISTRATION_STEP, '=', THREE]
        ];
        return $this->getMatchDonars($this->getDonarList($donarBaseCondition));
    }

    /**
     * @param User[] $donarList
     * @return array
     */
    private function getMatchDonars($donarList): array
    {
        $doctorData = $this->setMatchedDonars($donarList);
        usort($doctorData,array($this,'sortDataByMatchValue'));
        return $doctorData;
    }

    /**
     * @param User[] $donars
     * @return array
     */
    private function setMatchedDonars($donars): array
    {
        $match = [];
        $parents = AuthHelper::authenticatedUser();
        foreach ($donars as $donar) {
            $match[] = $this->setData($donar, $this->getMatchValue($donar, $parents));
        }
        return $match;
    }

    /**
     * @param User $donar
     * @param User $parents
     * @param int $matchValue
     * @return array
     */
    private function setData($donar, $matchValue): array
    {
        $result['user']['first_name'] = ucfirst($donar->first_name);
        $result['user']['middle_name'] = $donar->middle_name;
        $result['user']['last_name'] = $donar->last_name;
        $result['user']['username'] = $donar->username;
        $result['user']['email'] = $donar->email;
        $result['user']['id'] = $donar->id;
        $result['user']['profile_image'] = $donar->profile_image;
        $result['user']['zipcode'] = $donar->location->zipcode;
        $result['user']['state_id'] = $donar->location->state_id;
        $result['user']['state_name'] = $donar->location->name;
        $result['age'] = CustomHelper::ageCalculator($donar->dob);
        $result[MATCH_VALUE] = $matchValue;
        return $result;
    }

    /**
     * @param array $donarBaseCondition
     * @return DoctorProfile[]
     */
    private function getDonarList($donarBaseCondition): Collection
    {
        $notInterstedDonar = ProfileMatch::where([FROM_USER_ID => AuthHelper::authenticatedUser()->id, STATUS => REJECTED_BY_PTB])->get()->pluck(TO_USER_ID)->toArray();
        $srch = User::with(['userProfile','location','donerAttribute']);
        $srch->where($donarBaseCondition);
        $srch->whereIn('role_id',[SURROGATE_MOTHER,EGG_DONER,SPERM_DONER])
        ->whereNotIn(ID, $notInterstedDonar);
        return $srch->get();
    }

    /**
     * @param int $donarState
     * @param string $preference
     * @return int
     */
    private function getLocationValue ($donarState, $preference): int
    {
        $value = LOCATION_VALUE * 1/3;
        $statePreference = explode(',',$preference);
        if (in_array($donarState, $statePreference)) {
            $value = LOCATION_VALUE;
        }
        return $value;
    }

    /**
     * @param int $age
     * @param string $agePreference
     * @return int
     */
    private function getAgeValue($age, $agePreference): int
    {
        $agePreference = explode(',',$agePreference);
        $value = AGE_VALUE * 1/3;
        foreach ($agePreference as $ageValue) {
            $ageRange = explode('-',$ageValue);
            if ($ageRange[ZERO] <= $age && $age <= $ageRange[ONE]) {
                $value = AGE_VALUE;
                break;
            }
        }
        return $value;
    }

    /**
     * @param int $donerRace
     * @param string $preference
     * @return int
     */
    private function getRaceValue($donerRace, $preference): int
    {
        $racePreference = explode(',',$preference);
        $value = RACE_VALUE * 1/3;
        if (in_array($donerRace, $racePreference)) {
            $value = RACE_VALUE;
        }
        return $value;
    }

    /**
     * @param int $motherEthinicity
     * @param int $fatherEthinicity
     * @param string $preference
     * @return int
     */
    private function getEthnicityValue($motherEthinicity, $fatherEthinicity, $preference): int
    {
        $ethinicityPreference = explode(',',$preference);
        $value = ETHNICITY_VALUE * 1/3;
        if (in_array($motherEthinicity, $ethinicityPreference)
        && in_array($fatherEthinicity, $ethinicityPreference)
        ) {
            $value = ETHNICITY_VALUE;
        }
        return $value;
    }

    /**
     * @param int $height
     * @param string $preference
     * @return int
     */
    private function getHeightValue($height, $preference): int
    {
        $heightRange = explode('-',$preference);
        $value = HEIGHT_VALUE * 1/3;
        if ($heightRange[ZERO] <= $height && $height <= $heightRange[ONE]) {
            $value = HEIGHT_VALUE;
        }
        return $value;
    }

    /**
     * @param int $hairColour
     * @param string $preference
     * @return int
     */
    private function getHairColourValue($hairColour, $preference): int
    {
        $hairPreference = explode(',',$preference);
        $value = HAIR_COLOUR_VALUE * 1/3;
        if (in_array($hairColour, $hairPreference)) {
            $value = HAIR_COLOUR_VALUE;
        }
        return $value;
    }

    /**
     * @param int $eyeColour
     * @param string $preference
     * @return int
     */
    private function getEyeColourValue($eyeColour, $preference): int
    {
        $eyePreference = explode(',',$preference);
        $value = EYE_COLOUR_VALUE * 1/3;
        if (in_array($eyeColour, $eyePreference)) {
            $value = EYE_COLOUR_VALUE;
        }
        return $value;
    }

    /**
     * @param int $education
     * @param string $preference
     * @return int
     */
    private function getEducationValue($education, $preference): int
    {
        $educationPreference = explode(',',$preference);
        $value = EDUCATION_VALUE * 1/3;
        if (in_array($education, $educationPreference)) {
            $value = EDUCATION_VALUE;
        }
        return $value;
    }

    /**
     * @param User $donar
     * @param User $parents
     * @return int
     */
    private function getMatchValue($donar, $parents): int
    {
       $totalPoint =  $this->getAgeValue(CustomHelper::ageCalculator($donar->dob),$parents->parentsPreference->age)
        + $this->getHeightValue($donar->donerAttribute->height_id,$parents->parentsPreference->height)
        + $this->getRaceValue($donar->donerAttribute->race_id, $parents->parentsPreference->race)
        + $this->getEthnicityValue($donar->donerAttribute->mother_ethinicity_id, $donar->donerAttribute->father_ethinicity_id,$parents->parentsPreference->ethnicity)
        + $this->getLocationValue($donar->location->state_id, $parents->parentsPreference->state)
        + $this->getHairColourValue($donar->donerAttribute->hair_colour_id, $parents->parentsPreference->hair_colour)
        + $this->getEyeColourValue($donar->donerAttribute->eye_colour_id, $parents->parentsPreference->eye_colour)
        + $this->getEducationValue($donar->donerAttribute->education_id, $parents->parentsPreference->education);
        return intval(($totalPoint * 100) / CRITERIA_WEIGHT);
    }

    /**
     * @param array $item1
     * @param array $item2
     * @return int
     */
    private function sortDataByMatchValue($item1, $item2): int
    {
        if ($item1[MATCH_VALUE] == $item2[MATCH_VALUE]) {
            return 0;
        }
        return ($item1[MATCH_VALUE] > $item2[MATCH_VALUE]) ? -1 : 1;
    }
}
