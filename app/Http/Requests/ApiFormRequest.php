<?php
namespace App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;

abstract class ApiFormRequest extends LaravelFormRequest
{
    private $statusCode;

    public function __construct() {
        $this->statusCode = Response::HTTP_EXPECTATION_FAILED;
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    
    abstract public function rules();
    

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    
    abstract public function authorize();


    /**
     * Determine the format of the errors.
     *
     * @return bool
     */
    
    abstract protected function formatErrors($errors);


    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    
    protected function failedValidation(Validator $validator) {
        $errors = collect((new ValidationException($validator))->errors());
        $errors = $this->formatErrors($errors);
        throw new HttpResponseException(
            response()
            ->json([
                'data' => (object)[],
                'message' => $errors,
            ], $this->getStatusCode())
        );
    }
        
    protected function getStatusCode()
    {
        return $this->statusCode;
    }

    protected function setStatusCode($code)
    {
        $this->statusCode = $code;
    }

    protected function guard() {
        return Auth::guard('api');
    }
}