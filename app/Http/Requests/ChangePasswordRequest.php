<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helper\CustomHelper;
use App\Http\Requests\ApiFormRequest;
use App\Http\ValidationRule;

class ChangePasswordRequest extends ApiFormRequest
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
        return [
            CURRENT_PASSWORD => ValidationRule::PASSWORD,
            NEW_PASSWORD => ValidationRule::PASSWORD,
            CONFIRM_PASSWORD => ValidationRule::CONFIRM_NEW_PASSWORD,
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
        
        $msg_new_password_min = 'New password must be 8 characters minimum.';
        return [
            CURRENT_PASSWORD_REQ => __('messages.request_validation.error_msgs.current_password_req'),
            NEW_PASSWORD_REQ => __('messages.request_validation.error_msgs.new_password_req'),
            CONFIRM_PASSWORD_REQ => __('messages.request_validation.error_msgs.confirm_password_req'),
        ];
    }
}
