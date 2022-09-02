<?php

namespace App\Http;

use App\Http\Requests\ApiFormRequest;

class ValidationRule
{
    public const PHONE =[BAIL,REQUIRED, NUMERIC,'digits:10'];
    public const OTP =[BAIL,REQUIRED, NUMERIC,'digits:6'];
    public const REGISTRATION_STEP =[BAIL, REQUIRED, 'in : 1,2,3'];
    public const ROLE_ID =[BAIL, REQUIRED, 'in : 2,3,4,5'];
    public const NAME =[BAIL, REQUIRED, 'min:2', CustomHelper::nameRegex(), 'max:50'];
    public const MIDDLE_NAME =[BAIL, SOMETIMES, 'min:2', CustomHelper::nameRegex(), 'max:50'];
    public const EMAIL =[BAIL, REQUIRED, EMAIL, EMAIL_MAX_LENGTH, CustomHelper::emailRegex()];
    public const PASSWORD =[BAIL, REQUIRED, 'min:8', 'max:20', CustomHelper::passwordRegex()];
    public const DOB =[BAIL, REQUIRED];
    public const USER_ID =[BAIL, REQUIRED, EXISTS_USERS_ID];
    public const GENDER_ID =[BAIL, REQUIRED, EXISTS_GENDERS_ID];
    public const SEXUAL_ORIENTATION_ID =[BAIL, REQUIRED, EXISTS_SEXUAL_ORIENTATIONS_ID];
    public const RELATIONSHIP_STATUS_ID =[BAIL, REQUIRED, EXISTS_RELATIONSHIP_STATUSES_ID];
    public const BIO =[BAIL, REQUIRED, STRING,  'max:200'];
    public const PROFILE_PIC =[BAIL, REQUIRED, IMAGE_MIMES];
}
