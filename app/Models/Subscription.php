<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        USER_ID,
        SUBSCRIPTION_PLAN_ID,
        PRICE,
        CURRENT_PERIOD_START,
        CURRENT_PERIOD_END,
        SUBSCRIPTION_ID,
        ORIGINAL_TRANSACTION_ID,
        PRODUCT_ID,
        PURCHASE_TOKEN,
        DEVICE_TYPE,
        CANCELED_AT,
        MAIL_STATUS,
        STATUS_ID,
    ];

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, SUBSCRIPTION_PLAN_ID, ID);
    }

    public function user()
    {
        return $this->belongsTo(User::class, USER_ID, ID);
    }
}
