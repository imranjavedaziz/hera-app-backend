<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use App\Http\ValidationRule;
use App\Http\Requests\ApiFormRequest;

class EnquiryRequest extends ApiFormRequest
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
            NAME => ValidationRule::NAME,
            EMAIL => ValidationRule::EMAIL,
            COUNTRY_CODE => ValidationRule::COUNTRY_CODE,
            PHONE_NO => ValidationRule::PHONE,
            ENQUIRING_AS => ValidationRule::ROLE_ID,
            MESSAGE => ValidationRule::MESSAGE,
            USER_TIMEZONE => ValidationRule::USER_TIMEZONE,
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
