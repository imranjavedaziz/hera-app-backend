<?php

namespace App\Services;

use Ramsey\Uuid\Uuid;
use App\Models\Location;
use App\Models\User;
use App\Models\UserPreference;
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
        $input[DOB] = date(YMD_FORMAT,strtotime($input[DOB]));
        $user_profile = UserProfile::where(USER_ID, $input[USER_ID])->first();
        if(!$user_profile){
            $user_profile = new UserProfile();
        }
        $user_profile->user_id = $input[USER_ID];
        $user_profile->dob = $input[DOB];
        $user_profile->gender_id = $input[GENDER_ID];
        $user_profile->sexual_orientations_id = $input[SEXUAL_ORIENTATION_ID];
        $user_profile->relationship_status_id = $input[RELATIONSHIP_STATUS_ID];
        $user_profile->occupation = $input[OCCUPATION];
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
        $user_preference = UserPreference::where(USER_ID, $input[USER_ID])->first();
        if(!$user_preference){
            $user_preference = new UserPreference();
        }
        $user_preference->user_id = $input[USER_ID];
        $user_preference->role_id_looking_for = $input[ROLE_ID_LOOKING_FOR];
        $user_preference->age = $input[AGE];
        $user_preference->height = $input[HEIGHT];
        $user_preference->race = $input[RACE];
        $user_preference->ethnicity = $input[ETHNICITY];
        $user_preference->hair_colour = $input[HAIR_COLOUR];
        $user_preference->eye_colour = $input[EYE_COLOUR];
        if($user_preference->save()){
            $user->registration_step = THREE;
            $user->save();
        }
        return $user_preference;
    }
}
