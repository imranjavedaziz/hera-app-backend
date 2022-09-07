<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Requests\ApiFormRequest;
use App\Http\ValidationRule;

class SetAttributesRequest extends ApiFormRequest
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
            HEIGHT_ID => ValidationRule::HEIGHT_ID,
            RACE_ID => ValidationRule::RACE_ID,
            MOTHER_ETHNICITY_ID => ValidationRule::ETHNICITY_ID,
            FATHER_ETHNICITY_ID => ValidationRule::ETHNICITY_ID,
            WEIGHT_ID => ValidationRule::WEIGHT_ID,
            HAIR_COLOUR_ID => ValidationRule::HAIR_COLOUR_ID,
            EYE_COLOUR_ID => ValidationRule::EYE_COLOUR_ID,
            EDUCATION_ID => ValidationRule::EDUCATION_ID,
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
