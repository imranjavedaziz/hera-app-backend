<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Requests\ApiFormRequest;
use App\Http\ValidationRule;

class SetPreferencesRequest extends ApiFormRequest
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
            ROLE_ID_LOOKING_FOR => ValidationRule::ROLE_ID,
            AGE => ValidationRule::AGE,
            HEIGHT => ValidationRule::HEIGHT,
            RACE => ValidationRule::RACE,
            ETHNICITY => ValidationRule::ETHNICITY,
            HAIR_COLOUR => ValidationRule::HAIR_COLOUR,
            EYE_COLOUR => ValidationRule::EYE_COLOUR,
            EDUCATION => ValidationRule::EDUCATION,
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
