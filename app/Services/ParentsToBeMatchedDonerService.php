<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use App\Helpers\AuthHelper;
use App\Helpers\CustomHelper;
use Log;

class ParentsToBeMatchedDonerService
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
        $result['age'] = CustomHelper::ageCalculator($donar->user_profile->dob);
        $result[MATCH_VALUE] = $matchValue;
        return $result;
    }

    /**
     * @param array $donarBaseCondition
     * @return DoctorProfile[]
     */
    private function getDonarList($donarBaseCondition): Collection
    {
        $srch = User::with(['user_profile','location','donar_attribute']);
        $srch->where($donarBaseCondition);
        $srch->whereIn('role_id',[SURROGATE_MOTHER,EGG_DONER,SPERM_DONER]);
        return $srch->get();
    }

    /**
     * @param User $donar
     * @param User $parents
     * @return int
     */
    private function getLocationValue ($donar, $parents): int
    {
        $value = LOCATION_VALUE * 1/3;
        if ($donar->location->state_id === $parents->location->state_id) {
            $value = LOCATION_VALUE;
        }
        return $value;
    }

    /**
     * @param int $age
     * @param string $age_range
     * @return int
     */
    private function getAgeValue($age, $age_range): int
    {
        $ageRange = explode(',',$age_range);
        $value = AGE_VALUE * 1/3;
        if ($ageRange[ZERO] <= $age && $age <= $ageRange[ONE]) {
            $value = AGE_VALUE;
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
        $race_preference = explode(',',$preference);
        $value = RACE_VALUE * 1/3;
        if (in_array($donerRace, $race_preference)) {
            $value = RACE_VALUE;
        }
        return $value;
    }

    /**
     * @param DonarAttribute $donarAttribute
     * @param string $preference
     * @return int
     */
    private function getEthnicityValue($donarAttribute, $preference): int
    {
        $ethinicityPreference = explode(',',$preference);
        $value = ETHNICITY_VALUE * 1/3;
        if (in_array($donarAttribute->mother_ethinicity_id, $ethinicityPreference)
        && in_array($donarAttribute->father_ethinicity_id, $ethinicityPreference)
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
        $heightPreference = explode(',',$preference);
        $value = HEIGHT_VALUE * 1/3;
        if (in_array($height, $heightPreference)) {
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
       $totalPoint =  $this->getAgeValue(CustomHelper::ageCalculator($donar->user_profile->dob),$parents->parents_preference->age)
        + $this->getHeightValue($donar->donar_attribute->height_id,$parents->parents_preference->height)
        + $this->getRaceValue($donar->donar_attribute->race_id, $parents->parents_preference->race)
        + $this->getEthnicityValue($donar->donar_attribute, $parents->parents_preference->ethnicity)
        + $this->getLocationValue($donar, $parents)
        + $this->getHairColourValue($donar->donar_attribute->hair_colour_id, $parents->parents_preference->hair_colour)
        + $this->getEyeColourValue($donar->donar_attribute->eye_colour_id, $parents->parents_preference->eye_colour)
        + $this->getEducationValue($donar->donar_attribute->education_id, $parents->parents_preference->education);
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
