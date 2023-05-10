<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\DonerAttribute;
use App\Models\ParentsPreference;
use Hash;
use JWTAuth;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthHelper
{
    public static function createToken($email)
    {
        return base64_encode($email);
    }  

    public static function dateConvert($date, $format)
    {
        return date_format(date_create($date), $format);
    }
    
    public static function authenticatedUser()
    { 
        $returnNull = null;
        try {
            if (!JWTAuth::parseToken()->authenticate()) {
                $returnNull;
            } else {
                return JWTAuth::parseToken()->authenticate();
            }
        } catch (TokenExpiredException $e) {
            $returnNull;
        } catch (TokenInvalidException $e) {
            $returnNull;
        } catch (JWTException $e) {
            $returnNull;
        }
        return $returnNull;
    }

     /**
     * Authenticate user for unit test.
     *
     * @return void
     */
    public static function authenticateTestUser($id)
    {
        $user = User::where(ID, $id)->first();
        return \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
    }

     /**
     * Factory user create for unit test.
     *
     * @return void
     */
    public static function factoryUserCreate($role_id, $subscription_status)
    {
        $user = User::factory()->create([
            PROFILE_PIC => 'https://mbc-dev-kiwitech.s3.amazonaws.com/images/user_profile_images/6cyVUyZrb0mGR8ycURp7FYbzaRUmrtXA7kGUfaqS.jpg',
            ROLE_ID => $role_id,
            MIDDLE_NAME => "",
            COUNTRY_CODE=> TEST_COUNTRY_CODE,
            PHONE_NO=> rand(1000000000,9999999999),
            EMAIL=> rand(12345,67891).TEST_YOPMAIL,
            DOB=> TEST_DOB,
            SUBSCRIPTION_STATUS => $subscription_status
        ]);

        if($role_id == 2) {
            $user->userProfile = self::factoryUserSetProfilePtb($user);
            $user->parentPreference = self::factoryUserSetPreferencesPtb($user);
            $user->donorAttributes = null;
        }else{
            $user->userProfile = self::factoryUserSetProfileDonor($user);
            $user->donorAttributes = self::factoryUserSetAttributeDonor($user);
            $user->parentPreference = null;
        }
        return $user;
    }

     /**
     * Factory user set profile ptb for unit test.
     *
     * @return void
     */
    public static function factoryUserSetProfilePtb($user)
    {
        return UserProfile::create([
            USER_ID => $user->id,
            GENDER_ID => TEST_PTB_GENDER_ID,
            SEXUAL_ORIENTATION_ID => TEST_PTB_SEXUAL_ORIENTATION_ID,
            RELATIONSHIP_STATUS_ID => TEST_PTB_RELATIONSHIP_STATUS_ID,
            OCCUPATION => TEST_OCCUPATION,
            BIO => TEST_BIO,
            STATE_ID => TEST_STATE_ID,
            ZIPCODE => TEST_ZIPCODE,
        ]);
    }

     /**
     * Factory user set profile donor for unit test.
     *
     * @return void
     */
    public static function factoryUserSetProfileDonor($user)
    {
        return UserProfile::create([
            USER_ID => $user->id,
            GENDER_ID => TEST_DONOR_GENDER_ID,
            SEXUAL_ORIENTATION_ID => TEST_DONOR_SEXUAL_ORIENTATION_ID,
            RELATIONSHIP_STATUS_ID => TEST_DONOR_RELATIONSHIP_STATUS_ID,
            OCCUPATION => TEST_OCCUPATION,
            BIO => TEST_BIO,
            STATE_ID => TEST_STATE_ID,
            ZIPCODE => TEST_ZIPCODE,
        ]);
    }

     /**
     * Factory user set profile ptb for unit test.
     *
     * @return void
     */
    public static function factoryUserSetPreferencesPtb($user)
    {
        return ParentsPreference::create([
            USER_ID => $user->id,
            ROLE_ID_LOOKING_FOR => TEST_ROLE_ID_LOOKING_FOR,
            AGE => TEST_AGE,
            HEIGHT => TEST_HEIGHT,
            RACE => TEST_RACE,
            ETHNICITY => TEST_ETHNICITY,
            HAIR_COLOUR => TEST_HAIR_COLOUR,
            EYE_COLOUR => TEST_EYE_COLOUR,
            EDUCATION => TEST_EDUCATION,
            STATE => TEST_STATE
        ]);
    }

     /**
     * Factory user set profile donor for unit test.
     *
     * @return void
     */
    public static function factoryUserSetAttributeDonor($user)
    {
        return DonerAttribute::create([
            USER_ID => $user->id,
            HEIGHT_ID => TEST_HEIGHT_ID,
            RACE_ID => TEST_RACE_ID,
            MOTHER_ETHNICITY_ID => TEST_MOTHER_ETHNICITY_ID,
            FATHER_ETHNICITY_ID => TEST_FATHER_ETHNICITY_ID,
            WEIGHT_ID => TEST_WEIGHT_ID,
            HAIR_COLOUR_ID => TEST_HAIR_COLOUR_ID,
            EYE_COLOUR_ID => TEST_EYE_COLOUR_ID,
            EDUCATION_ID => TEST_EDUCATION_ID,
        ]);
    }
}
