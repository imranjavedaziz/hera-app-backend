<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use App\Http\ValidationRule;
use App\Http\Requests\ApiFormRequest;

class SetGalleryRequest extends ApiFormRequest
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
            IMAGE => ValidationRule::IMAGE,
            VIDEO => ValidationRule::VIDEO,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function formatErrors($errors)
    {
        return !empty($errors) ? $errors->first()[0] : "";
    }
    
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            IMAGE_REQUIRED_WITHOUT => __('messages.request_validation.error_msgs.image_required_without'),
        ];
    }
}
