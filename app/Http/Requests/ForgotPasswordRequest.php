<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\ValidationRule;

class ForgotPasswordRequest extends ApiFormRequest
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
            'user_id' => ValidationRule::USER_ID,
            'password' => ValidationRule::PASSWORD,
            'confirm_password' => ValidationRule::CONFIRM_PASSWORD,
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $msg_password_rule  = __('messages.request_validation.error_msgs.pass_regex');
        return [
            PASS_REGEX => $msg_password_rule,
            'password.min' => $msg_password_rule,
            'password.max' => $msg_password_rule,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function formatErrors($errors)
    {
        return !empty($errors) ? $errors->first()[0] : "";
    }
}
