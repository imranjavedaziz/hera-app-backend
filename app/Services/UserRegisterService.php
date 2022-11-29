<?php

namespace App\Services;

use Ramsey\Uuid\Uuid;
use App\Models\DonerAttribute;
use App\Models\DonerGallery;
use App\Models\EmailVerification;
use App\Models\User;
use App\Models\ParentsPreference;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use App\Traits\SetterDataTrait;
use Storage;
use App\Jobs\SendEmailVerificationJob;
use App\Jobs\SetLocationJob;
use Carbon\Carbon;
use App\Helpers\TwilioOtp;
use Facades\{
    App\Services\FirebaseService
};
use App\Jobs\CreateAdminChatFreiend;
use App\Jobs\UpdateUserDetailOnFirebase;
use DB;

class UserRegisterService
{
    use SetterDataTrait;

    public function register($input)
    {
        $input[PASSWORD] = bcrypt($input[PASSWORD]);
        $input[REGISTRATION_STEP] = ONE;
        $input[EMAIL] = strtolower($input[EMAIL]);
        $input[DOB] = date(YMD_FORMAT,strtotime($input[DOB]));
        $user = User::create($input);
        if($user){
            $user->username = $this->setUserName($input[ROLE_ID], $user->id);
            $file = $this->uploadFile($input, 'images/user_profile_images');
            $user->profile_pic = $file[FILE_URL];
            $user->save();
            /** $this->sendEmailVerification($user); **/
            if ($input[ROLE_ID] != PARENTS_TO_BE) {
                dispatch(new CreateAdminChatFreiend($user));
            }
        }
        return $user;
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
            dispatch(new SetLocationJob($input));
        }
        return $user_profile;
    }

    public function getPreferencesSetterData()
    {
        $data = [];
        $data[ROLE] = $this->getRoleData();
        $data[ETHNICITY] = $this->getEthnicityData();
        $data[EYE_COLOUR] = $this->getEyeColourData();
        $data[HAIR_COLOUR] = $this->getHairColourData();
        $data[RACE] = $this->getRaceData();
        $data[EDUCATION] = $this->getEducationData();
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
        $parents_preference->state = $input[STATE];
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

    public function uploadedFilesCountValidation($user, $input)
    {
        $response = [SUCCESS => true, MESSAGE => trans('messages.common_msg.no_data_found')];
        if(empty($input[OLD_FILE_NAME])){
            if(!empty($input[IMAGE]) && $user->donerPhotoGallery->count() == 6){
                $response = [SUCCESS => false, MESSAGE => trans('messages.register.gallery_max_image_upload')];
            }elseif (!empty($input[VIDEO]) && $user->donerVideoGallery) {
                $response = [SUCCESS => false, MESSAGE => trans('messages.register.gallery_max_video_upload')];
            }
        }
        return $response;
    }

    public function setGallery($user, $input)
    {
        $input[USER_ID] = $user->id;
        $input[FILE] = !empty($input[IMAGE]) ? $input[IMAGE] : $input[VIDEO];
        $file = $this->uploadFile($input, 'images/user_gellery');
        $doner_gallery = new DonerGallery();
        if(!empty($input[OLD_FILE_NAME])){
            $doner_gallery = DonerGallery::where(FILE_NAME, $input[OLD_FILE_NAME])->first();
            if(!empty($doner_gallery)){
                Storage::disk('s3')->delete('images/user_gellery/'.$doner_gallery->file_name);
            }else{
                return [SUCCESS => false, MESSAGE => trans('messages.register.gallery_save_old_file_error')];
            }
        }
        $doner_gallery->user_id = $input[USER_ID];
        $doner_gallery->file_name = $file[FILE_NAME];
        $doner_gallery->file_url = $file[FILE_URL];
        $doner_gallery->file_type = strstr($file[MIME], "video/") ? VIDEO : IMAGE;
        $doner_gallery->save();
        return [SUCCESS => true, DATA => $doner_gallery];
    }

    public function uploadFile($input, $path)
    {
        $mime = $input[FILE]->getMimeType();
        $filePath = Storage::disk('s3')->put($path, $input[FILE]);
        $path = Storage::disk('s3')->url($filePath);
        $pathInfo = pathinfo($path);
        $fileName = $pathInfo['filename'].'.'.$pathInfo['extension'];
        return [FILE_NAME => $fileName, FILE_URL => $path, MIME => $mime];
    }

    public function deleteGallery($userId, $galleryIds)
    {
        $doner_galleries = DonerGallery::where(USER_ID, $userId)->whereIn(ID, $galleryIds)->get();
        foreach ($doner_galleries as $doner_gallery) {
            $data = Storage::disk('s3')->delete('images/user_gellery/'.$doner_gallery->file_name);
            if($data){
                $doner_gallery->delete();
            }
        }
        return true;
    }

    public function getPreferencesAgeRangeData($input)
    {
        if($input[ROLE_ID_LOOKING_FOR] == 3 || $input[ROLE_ID_LOOKING_FOR] == 4){
            return config('constants.age_range_female');
        }else{
            return config('constants.age_range_male');
        }
    }

    public function updateProfilePic($user, $input)
    {
        $pathInfo = pathinfo($user->profile_pic);
        $fileName = $pathInfo['filename'].'.'.$pathInfo['extension'];
        $file = $this->uploadFile($input, 'images/user_profile_images');
        $user->profile_pic = $file[FILE_URL];
        if($user->save()){
            Storage::disk('s3')->delete('images/user_profile_images/'.$fileName);
            dispatch(new UpdateUserDetailOnFirebase($user));
            return $user->profile_pic;
        }
        return false;
    }

    public function getUserProfile($user_id)
    {
        return User::select(ID, ROLE_ID, FIRST_NAME, MIDDLE_NAME, LAST_NAME, EMAIL, EMAIL_VERIFIED, PHONE_NO, DOB)
        ->with([USERPROFILE => function($q) {
                return $q->select(ID, USER_ID, GENDER_ID, SEXUAL_ORIENTATION_ID, RELATIONSHIP_STATUS_ID, OCCUPATION, BIO);
            },
            LOCATION => function($q) {
                return $q->select(ID, USER_ID, STATE_ID, ZIPCODE);
            },
            SUBSCRIPTION => function($q) {
                return $q->select(ID, USER_ID, CURRENT_PERIOD_END, SUBSCRIPTION_PLAN_ID, PRICE)
                ->selectRaw('(select name from subscription_plans where id='.SUBSCRIPTION_PLAN_ID.AS_CONNECT.NAME.' ')
                ->selectRaw('(select subscription_plans.interval from subscription_plans where id='.SUBSCRIPTION_PLAN_ID.AS_CONNECT.'subscription_interval ');
            },
        ])
        ->where(ID, $user_id)
        ->first();
    }

    public function updateUser($user, $input)
    {
        $input[DOB] = date(YMD_FORMAT,strtotime($input[DOB]));
        if($user->update($input)){
            $user->userProfile->update($input);
            $user->location->update($input);
            dispatch(new UpdateUserDetailOnFirebase($user));
            return true;
        }
        return false;
    }

    public function updateUserAccountStatus($userId, $input) {
        $reason = $input[REASON_ID] ?? null;
        return User::where(ID, $userId)->update([STATUS_ID => $input[STATUS_ID], REASON_ID => $reason]);
    }

    public function sendEmailVerification($user) {
        $code = mt_rand(11,99).mt_rand(11,99).mt_rand(0,9).mt_rand(0,9);
        $emailVerify = EmailVerification::firstOrNew([EMAIL => $user->email]);
        $emailVerify->otp = $code;
        $emailVerify->save();
        dispatch(new SendEmailVerificationJob($user, $code));
        return true;
    }

    public static function verifyEmail($user, $input){
        $isVerifyOtp = EmailVerification::where([EMAIL => $user->email])->where(OTP,$input[CODE])->first();
        if($isVerifyOtp) {
            $otpExpired = $isVerifyOtp[UPDATED_AT] <= (Carbon::now()->subMinutes(30)->toDateTimeString());
            if ($otpExpired) {
                return [STATUS => false, MESSAGE => __('messages.MOBILE_OTP_EXPIRED')];
            }
            $user->email_verified = true;
            $user->email_verified_at = Carbon::now();
            $user->save();
            EmailVerification::where([EMAIL => $user->email])->where(OTP,$input[CODE])->delete();
            $data =[MESSAGE => __('messages.email_verified_success'), STATUS=> true];        
        }else{
            $data =[MESSAGE => __('messages.invalid_email_otp'), STATUS=> false];
        }

        return $data;
    }

    public function sentOtpForMobileNumberVerify($request) {
        $phoneExits = User::where([COUNTRY_CODE => $request->country_code, PHONE_NO => $request->phone_no, STATUS_ID => ONE])->count();
        if ($phoneExits > ZERO) {
            $response = response()->Error(__('messages.phone_already_exists'));
        } else {
            $result = TwilioOtp::sendOTPOnPhone($request->country_code, $request->phone_no);
            if($result[STATUS]) {
                $response = response()->Success($result[MESSAGE]);
            } else {
                $response = response()->Error($result[MESSAGE]);
            }
        }
    
        return $response;
    }

    public function sentOtpForFogotPassword($request) {
        $user = User::where([COUNTRY_CODE => $request->country_code, PHONE_NO => $request->phone_no, STATUS_ID => ONE])->first();
        if (!empty($user)) {
            $result = TwilioOtp::sendOTPOnPhone($request->country_code, $request->phone_no);
            if($result[STATUS]) {
                $response = response()->Success($result[MESSAGE], $user);
            } else {
                $response = response()->Error($result[MESSAGE]);
            }
        } else {
            $response = response()->Error(__('messages.phone_not_exists'));
        }
    
        return $response;
    }
}
