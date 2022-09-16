<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        USER_ID,
        PRODUCT_ID,
        PAYMENT_FOR,
        PAYMENT_ID,
        COST,
        PAYMENT_GATEWAY,
        STATUS_ID,
    ];
}
