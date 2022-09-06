<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneVerification extends Model
{
    use HasFactory;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       COUNTRY_CODE,
       PHONE_NO,
       OTP,
       MAX_ATTEMPT,
       OTP_BLOCK_TIME,
   ];
}
