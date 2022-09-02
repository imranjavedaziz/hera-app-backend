<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Requests\ApiFormRequest;

class RegisterRequest extends ApiFormRequest
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
            ROLE_ID => ValidationRule::ROLE_ID,
            PROFILE_PIC => ValidationRule::PROFILE_PIC,
            FIRST_NAME => ValidationRule::NAME,
            MIDDLE_NAME => ValidationRule::MIDDLE_NAME,
            LAST_NAME => ValidationRule::NAME,
            PHONE_NO => ValidationRule::PHONE,
            EMAIL => $emailValidationRule,
            PASSWORD => ValidationRule::PASSWORD,
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
