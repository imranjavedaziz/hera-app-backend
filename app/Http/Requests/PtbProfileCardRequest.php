<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Requests\ApiFormRequest;
use App\Http\ValidationRule;

class PtbProfileCardRequest extends ApiFormRequest
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

    public function validationData()
    {
        $this->merge([
            STATE_IDS_ARRAY => explode(',', $this->state_ids),
        ]);
        return $this->all();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            KEYWORD => ValidationRule::KEYWORD,
            STATE_IDS_ARRAY => ValidationRule::STATE_IDS_ARRAY,
            STATE_IDS_ELEMENTS => ValidationRule::STATE_IDS_ELEMENTS,
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
            STATE_IDS_ELEMENTS_EXISTS => __('messages.request_validation.error_msgs.state_id_exists'),
        ];
    }

}
