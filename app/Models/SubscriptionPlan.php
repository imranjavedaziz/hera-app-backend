<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        NAME,
        PRICE,
        INTERVAL,
        INTERVAL_COUNT,
        DESCRIPTION,
        IOS_PRODUCT,
        ANDROID_PRODUCT,
        FOR_WHOM,
        STATUS_ID,
        ROLE_ID_LOOKING_FOR,
    ];
}
