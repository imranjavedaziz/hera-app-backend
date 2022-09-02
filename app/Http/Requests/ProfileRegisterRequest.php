<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Requests\ApiFormRequest;

class ProfileRegisterRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $emailValidationRule = array_merge(ValidationRule::EMAIL,[UNIQUE_USERS_EMAIL]);
        return [
            REGISTRATION_STEP => ValidationRule::REGISTRATION_STEP,
            USER_ID => ValidationRule::USER_ID,
            DOB => ValidationRule::DOB,
            GENDER_ID => ValidationRule::GENDER_ID,
            SEXUAL_ORIENTATION_ID => ValidationRule::SEXUAL_ORIENTATION_ID,
            RELATIONSHIP_STATUS_ID => ValidationRule::RELATIONSHIP_STATUS_ID,
            BIO => ValidationRule::BIO,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function formatErrors($errors)
    {
        return !empty($errors) ? $errors : "";
    }
}
