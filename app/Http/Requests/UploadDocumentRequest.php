<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use App\Http\ValidationRule;
use App\Http\Requests\ApiFormRequest;

class UploadDocumentRequest extends ApiFormRequest
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
            FILE => ValidationRule::FILE,
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
            FILE_MAX => __('messages.request_validation.error_msgs.pro_doc_max'),
        ];
    }
}
