<?php

namespace App\Services;

use Ramsey\Uuid\Uuid;
use App\Models\DonerAttribute;
use App\Models\Location;
use App\Models\User;
use App\Models\ParentsPreference;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use App\Traits\SetterDataTrait;
use Storage;

class UserRegisterService
{
    use SetterDataTrait;

    public function register($input)
    {
        $input[PASSWORD] = bcrypt($input[PASSWORD]);
        $input[REGISTRATION_STEP] = ONE;
        $input[DOB] = date(YMD_FORMAT,strtotime($input[DOB]));
        $user = User::create($input);
        if($user){
            $user->username = $this->setUserName($input[ROLE_ID], $user->id);
            $filename = 'ABCDEF';
            $user->profile_pic = $filename;
            $user->save();
        }
        return $user;
    }

    private function setUserName($role_id, $user_id){
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

    public function getProfileSetterData()
    {
        $data = [];
        $data[GENDER] = $this->getGenderData();
        $data[RELATIONSHIP_STATUS] = $this->getRelationshipStatusData();
        $data[SEXUAL_ORIENTATION] = $this->getSexualOrientationData();
        return $data;
    }

    public function profileRegister($user, $input)
    {
        $input[USER_ID] = $user->id;
        $user_profile = UserProfile::where(USER_ID, $input[USER_ID])->first();
        if(!$user_profile){
            $user_profile = new UserProfile();
        }
        $user_profile->user_id = $input[USER_ID];
        $user_profile->gender_id = $input[GENDER_ID];
        $user_profile->sexual_orientations_id = $input[SEXUAL_ORIENTATION_ID];
        $user_profile->relationship_status_id = $input[RELATIONSHIP_STATUS_ID];
        $user_profile->occupation = !empty($input[OCCUPATION]) ? $input[OCCUPATION]: NULL;
        $user_profile->bio = $input[BIO];
        if($user_profile->save()){
            $user->registration_step = TWO;
            $user->save();
            $user_profile->location = $this->setLocation($input);
        }
        return $user_profile;
    }

    private function setLocation($input)
    {
        $location = Location::where(USER_ID, $input[USER_ID])->first();
        if(!$location){
            $location = new Location();
        }
        $location->user_id = $input[USER_ID];
        $location->state_id = $input[STATE_ID];
        $location->zipcode = $input[ZIPCODE];
        return $location->save();
    }

    public function getPreferencesSetterData()
    {
        $data = [];
        $data[ETHNICITY] = $this->getEthnicityData();
        $data[EYE_COLOUR] = $this->getEyeColourData();
        $data[HAIR_COLOUR] = $this->getHairColourData();
        $data[RACE] = $this->getRaceData();
        return $data;
    }

    public function setPreferences($user, $input)
    {
        $input[USER_ID] = $user->id;
        $parents_preference = ParentsPreference::where(USER_ID, $input[USER_ID])->first();
        if(!$parents_preference){
            $parents_preference = new ParentsPreference();
        }
        $parents_preference->user_id = $input[USER_ID];
        $parents_preference->role_id_looking_for = $input[ROLE_ID_LOOKING_FOR];
        $parents_preference->age = $input[AGE];
        $parents_preference->height = $input[HEIGHT];
        $parents_preference->race = $input[RACE];
        $parents_preference->ethnicity = $input[ETHNICITY];
        $parents_preference->hair_colour = $input[HAIR_COLOUR];
        $parents_preference->eye_colour = $input[EYE_COLOUR];
        $parents_preference->education = $input[EDUCATION];
        if($parents_preference->save()){
            $user->registration_step = THREE;
            $user->save();
        }
        return $parents_preference;
    }

    public function getAttributesSetterData()
    {
        $data = [];
        $data[HEIGHT] = $this->getHeightData();
        $data[RACE] = $this->getRaceData();
        $data[ETHNICITY] = $this->getEthnicityData();
        $data[WEIGHT] = $this->getWeightData();
        $data[HAIR_COLOUR] = $this->getHairColourData();
        $data[EYE_COLOUR] = $this->getEyeColourData();
        $data[EDUCATION] = $this->getEducationData();
        return $data;
    }

    public function setAttributes($user, $input)
    {
        $input[USER_ID] = $user->id;
        $doner_attribute = DonerAttribute::where(USER_ID, $input[USER_ID])->first();
        if(!$doner_attribute){
            $doner_attribute = new DonerAttribute();
        }
        $doner_attribute->user_id = $input[USER_ID];
        $doner_attribute->height_id = $input[HEIGHT_ID];
        $doner_attribute->race_id = $input[RACE_ID];
        $doner_attribute->mother_ethnicity_id = $input[MOTHER_ETHNICITY_ID];
        $doner_attribute->father_ethnicity_id = $input[FATHER_ETHNICITY_ID];
        $doner_attribute->weight_id = $input[WEIGHT_ID];
        $doner_attribute->hair_colour_id = $input[HAIR_COLOUR_ID];
        $doner_attribute->eye_colour_id = $input[EYE_COLOUR_ID];
        $doner_attribute->education_id = $input[EDUCATION_ID];
        if($doner_attribute->save()){
            $user->registration_step = THREE;
            $user->save();
        }
        return $doner_attribute;
    }
}
