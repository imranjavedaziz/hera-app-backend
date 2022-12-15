<?php

namespace App\Http;

use App\Http\Requests\ApiFormRequest;

class ValidationRule
{
    public const COUNTRY_CODE =[BAIL,REQUIRED];
    public const PHONE =[BAIL,REQUIRED, NUMERIC,'digits:10'];
    public const OTP =[BAIL,REQUIRED, NUMERIC,'digits:6'];
    public const REGISTRATION_STEP =[BAIL, REQUIRED, 'in : 1,2,3'];
    public const ROLE_ID =[BAIL, REQUIRED, 'in : 2,3,4,5'];
    public const NAME =[BAIL, REQUIRED, MIN_ONE, NAME_REGEX, 'max:30'];
    public const MIDDLE_NAME =[BAIL, SOMETIMES, NULLABLE, MIN_ONE, NAME_REGEX, 'max:30'];
    public const EMAIL =[BAIL, REQUIRED, EMAIL, EMAIL_MAX_LENGTH, EMAIL_REGEX];
    public const PASSWORD =[BAIL, REQUIRED, 'min:8', 'max:20', PASSWORD_REGEX];
    public const CONFIRM_PASSWORD =[BAIL, REQUIRED, 'same:password'];
    public const CONFIRM_NEW_PASSWORD =[BAIL, REQUIRED, 'same:new_password'];
    public const PROFILE_PIC =[BAIL, REQUIRED];
    public const DOB =[BAIL, REQUIRED];
    public const USER_ID =[BAIL, REQUIRED, EXISTS_USERS_ID];
    public const GENDER_ID =[BAIL, REQUIRED, EXISTS_GENDERS_ID];
    public const SEXUAL_ORIENTATION_ID =[BAIL, REQUIRED, EXISTS_SEXUAL_ORIENTATIONS_ID];
    public const RELATIONSHIP_STATUS_ID =[BAIL, REQUIRED, EXISTS_RELATIONSHIP_STATUSES_ID];
    public const BIO =[BAIL, REQUIRED, STRING,  'max:250'];
    public const STATE =[BAIL, REQUIRED, EXISTS_STATE_ID];
    public const ZIPCODE =[BAIL, REQUIRED, NUMERIC, 'digits:5'];
    public const AGE =[BAIL, REQUIRED];
    public const HEIGHT =[BAIL, REQUIRED];
    public const RACE =[BAIL, REQUIRED];
    public const ETHNICITY =[BAIL, REQUIRED];
    public const HAIR_COLOUR =[BAIL, REQUIRED];
    public const EYE_COLOUR =[BAIL, REQUIRED];
    public const EDUCATION =[BAIL, REQUIRED];
    public const HEIGHT_ID =[BAIL, REQUIRED, EXISTS_HEIGHTS_ID];
    public const RACE_ID =[BAIL, REQUIRED, EXISTS_RACES_ID];
    public const ETHNICITY_ID =[BAIL, REQUIRED, EXISTS_ETHNICITIES_ID];
    public const WEIGHT_ID =[BAIL, REQUIRED, EXISTS_WEIGHTS_ID];
    public const HAIR_COLOUR_ID =[BAIL, REQUIRED, EXISTS_HAIR_COLOURS_ID];
    public const EYE_COLOUR_ID =[BAIL, REQUIRED, EXISTS_EYE_COLOURS_ID];
    public const EDUCATION_ID =[BAIL, REQUIRED, EXISTS_EDUCATION_ID];
    public const STATUS =[BAIL, REQUIRED];
    public const IMAGE =[BAIL, REQUIRED_WITHOUT_VIDEO, SOMETIMES, NULLABLE, IMAGE];
    public const VIDEO =[BAIL, REQUIRED_WITHOUT_IMAGE, SOMETIMES, NULLABLE];
    public const PRODUCT_ID =[BAIL, REQUIRED, STRING];
    public const PURCHASE_TOKEN =[BAIL, REQUIRED, STRING];
    public const KEYWORD =[BAIL, SOMETIMES, NULLABLE, 'min:3'];
    public const STATE_IDS_ARRAY =[BAIL, SOMETIMES, NULLABLE, MIN_ONE, 'max:3'];
    public const STATE_IDS_ELEMENTS =[BAIL, SOMETIMES, NULLABLE, EXISTS_STATE_ID];
    public const DEVICE_ID =[BAIL, REQUIRED, STRING];
    public const DEVICE_TOKEN =[BAIL, REQUIRED, STRING];
    public const DEVICE_TYPE =[BAIL, REQUIRED, STRING];
    public const PROFILE_MATCH_VALIDATION_ID =[BAIL, REQUIRED, EXISTS_PROFILE_MATCH_ID];
    public const LIKE =[BAIL, REQUIRED, IN_ZERO_ONE];
    public const IS_SKIP =[BAIL, REQUIRED, IN_ZERO_ONE];
    public const REASON_ID =[BAIL, REQUIRED, NULLABLE];
    public const EMAIL_CODE =[BAIL, REQUIRED, EXISTS_EMAIL_VERIFICATION_ID];
    public const IDS_ARRAY =[BAIL, REQUIRED, ARRAYY];
    public const ALL_IDS =[BAIL, REQUIRED, NUMERIC];
    public const MESSAGE =[BAIL, REQUIRED, STRING,  'max:200'];
    public const USER_TIMEZONE =[BAIL, REQUIRED];
}
