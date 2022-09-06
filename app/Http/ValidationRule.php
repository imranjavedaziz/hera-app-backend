<?php

namespace App\Http;

use App\Http\Requests\ApiFormRequest;

class ValidationRule
{
    public const PHONE =['bail','required','numeric','digits:10'];
    public const OTP =['bail','required','numeric','digits:6'];
    public const COUNTRY_CODE =['bail','required'];
}
