<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use App\Http\ValidationRule;
use App\Http\Requests\ApiFormRequest;

class UpdateUserProfileRequest extends ApiFormRequest
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
            MIDDLE_NAME => ValidationRule::MIDDLE_NAME,
            LAST_NAME => ValidationRule::NAME,
            DOB => ValidationRule::DOB,
            GENDER_ID => ValidationRule::GENDER_ID,
            SEXUAL_ORIENTATION_ID => ValidationRule::SEXUAL_ORIENTATION_ID,
            RELATIONSHIP_STATUS_ID => ValidationRule::RELATIONSHIP_STATUS_ID,
            BIO => ValidationRule::BIO,
            STATE_ID => ValidationRule::STATE,
            ZIPCODE => ValidationRule::ZIPCODE,
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
