<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPaymentSetup extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        USER_ID,
        IPS,
        SUBSCRIPTION_PLAN_ID,
        STATUS,
        DEVICE_TYPE
    ];
}