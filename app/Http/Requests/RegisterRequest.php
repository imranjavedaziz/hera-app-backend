<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use App\Http\ValidationRule;
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
        $emailValidationRule = array_merge(ValidationRule::EMAIL,[Rule::unique(USERS, EMAIL)->where(STATUS_ID, '!=', DELETED)->whereNull(DELETED_AT)]);
        $phoneValidationRule = array_merge(ValidationRule::PHONE,[Rule::unique(USERS, PHONE_NO)->where(STATUS_ID, '!=', DELETED)->whereNull(DELETED_AT)]);
        return [
            ROLE_ID => ValidationRule::ROLE_ID,
            FILE => ValidationRule::PROFILE_PIC,
            FIRST_NAME => ValidationRule::NAME,
            MIDDLE_NAME => ValidationRule::MIDDLE_NAME,
            LAST_NAME => ValidationRule::NAME,
            COUNTRY_CODE => ValidationRule::COUNTRY_CODE,
            PHONE_NO => $phoneValidationRule,
            EMAIL => $emailValidationRule,
            DOB => ValidationRule::DOB,
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
    
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            EMAIL_UNIQUE => __('messages.request_validation.error_msgs.email_unique'),
            PASS_REGEX => __('messages.request_validation.error_msgs.pass_regex'),
            PRO_PIC_MAX => __('messages.request_validation.error_msgs.pro_pic_max'),
        ];
    }
}
