<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Requests\ApiFormRequest;
use App\Http\ValidationRule;

class KycRequest extends ApiFormRequest
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
            FIRST_NAME => ValidationRule::NAME,
            LAST_NAME => ValidationRule::NAME,
            'dob_year' => REQUIRED,
            'dob_month' => REQUIRED,
            'dob_day' => REQUIRED,
            'address' => REQUIRED,
            'city' => REQUIRED,
            'state' => REQUIRED,
            'postal_code' => REQUIRED,
            'ssn_last_4' => REQUIRED,
            'bank_token_id' => REQUIRED,
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
