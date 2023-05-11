<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        TEMP_ID,
        USER_ID,
        ACCOUNT_ID,
        PAYMENT_INTENT,
        AMOUNT,
        NET_AMOUNT,
        DESCRIPTION,
        PAYMENT_TYPE,
        PAYMENT_STATUS,
        SUBSCRIPTION_ID,
        PRODUCT_ID,
        PRICE_ID,
        SUBSCRIPTION_START,
        SUBSCRIPTION_END,
        BRAND,
        EXP_MONTH,
        EXP_YEAR,
        LAST4,
        RECEIPT_URL,
        INVOICE_NUMBER,
        CANCELLATION_DATE,
        REFUND_AMOUNT,
        NET_REFUND_AMOUNT,
        REFUND_ID,
        REFUND_STATUS,
    ];

    public static function saveTransaction($object, $fields, $card) {
        $transPaymentIntent = self::setTransPaymentIntent($object, $fields, $card);
        self::create($transPaymentIntent);
        return true;
    }

    public static function setTransPaymentIntent($object, $fields, $card){
        return [
            USER_ID => isset($fields[USER_ID])?$fields[USER_ID]:NULL,
            PAYMENT_INTENT => isset($object[ID])?$object[ID]:NULL,
            ACCOUNT_ID => isset($fields[ACCOUNT_ID])?$fields[ACCOUNT_ID]:NULL,
            NET_AMOUNT => isset($object[AMOUNT])?$object[AMOUNT]/HUNDRED: NULL,
            DESCRIPTION => isset($fields[DESCRIPTION])?$fields[DESCRIPTION]:NULL,
            PAYMENT_TYPE => isset($fields[PAYMENT_TYPE])?$fields[PAYMENT_TYPE]:TRANSFER_AMOUNT,
            PAYMENT_STATUS => ($object[STATUS] === SUCCEEDED) ? PAYMENT_SUCCESS : PAYMENT_FAILURE,
            BRAND => isset($card->brand)?$card->brand:NULL,
            EXP_MONTH => isset($card->exp_month)?$card->exp_month:NULL,
            EXP_YEAR => isset($card->exp_year)?$card->exp_year:NULL,
            LAST4 => isset($card->last4)?$card->last4:NULL,
        ];
    }
}
