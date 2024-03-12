<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\SubscriptionUpdate;
use Log;

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
        BANK_NAME,
        BANK_LAST4,
        RECEIPT_URL,
        INVOICE_NUMBER,
        CANCELLATION_DATE,
        REFUND_AMOUNT,
        NET_REFUND_AMOUNT,
        REFUND_ID,
        REFUND_STATUS,
        PAYMENT_REQUEST_ID
    ];

    public static function saveTransaction($object, $fields, $card, $bankAccount) {
        $transPaymentIntent = self::setTransPaymentIntent($object, $fields, $card, $bankAccount);
        return self::create($transPaymentIntent);
    }

    public static function setTransPaymentIntent($object, $fields, $card, $bankAccount){
        return [
            USER_ID => isset($fields[USER_ID])?$fields[USER_ID]:NULL,
            PAYMENT_INTENT => isset($object[ID])?$object[ID]:NULL,
            ACCOUNT_ID => isset($fields[ACCOUNT_ID])?$fields[ACCOUNT_ID]:NULL,
            AMOUNT => isset($fields[AMOUNT])?$fields[AMOUNT]: NULL,
            NET_AMOUNT => isset($object[AMOUNT])?$object[AMOUNT]/HUNDRED: NULL,
            DESCRIPTION => isset($fields[DESCRIPTION])?$fields[DESCRIPTION]:NULL,
            PAYMENT_TYPE => isset($fields[PAYMENT_TYPE])?$fields[PAYMENT_TYPE]:TRANSFER_AMOUNT,
            PAYMENT_STATUS => ($object[STATUS] === SUCCEEDED) ? PAYMENT_SUCCESS : PAYMENT_FAILURE,
            BRAND => isset($card->brand)?$card->brand:NULL,
            EXP_MONTH => isset($card->exp_month)?$card->exp_month:NULL,
            EXP_YEAR => isset($card->exp_year)?$card->exp_year:NULL,
            LAST4 => isset($card->last4)?$card->last4:NULL,
            BANK_NAME => isset($bankAccount->bank_name)?$bankAccount->bank_name:NULL,
            BANK_LAST4 => isset($bankAccount->last4)?$bankAccount->last4:NULL,
            PAYMENT_REQUEST_ID => isset($fields[PAYMENT_REQUEST_ID])?$fields[PAYMENT_REQUEST_ID]:NULL
        ];
    }

    public static function saveInvoicePayment($object) {
        Log::info("saveInvoicePayment | object : ".json_encode($object)."\n");
        if(!empty($object) && $object->object !== 'invoice') {
            return false;
        }
        $user = User::where(STRIPE_CUSTOMER_ID,$object->customer)->first();
        if(empty($user)) {
            return false;
        }
        if(!empty($user)) {
            $fields = self::setInvoiceTransactionFields($object,$user->id);
            Log::info("saveInvoicePayment | fields : ".json_encode($fields)."\n");
            $transaction = self::where(TEMP_ID,$object->id)->where(USER_ID,$user->id)->first();
            if(empty($transaction)) {
                self::create($fields);
            } else {
                self::where(ID, $transaction->id)->update($fields);
            }
            $subscription = self::getSubscriptionData($object);
            SubscriptionUpdate::dispatch($user,$subscription,$object);
        }
        return true;
    }

    private static function setInvoiceTransactionFields($object,$userId){
        return [
            TEMP_ID => $object->id ?? NULL,
            USER_ID => $userId ?? NULL,
            PAYMENT_INTENT => $object->payment_intent ?? NULL,
            AMOUNT => isset($object->amount_paid)?$object->amount_paid/HUNDRED: NULL,
            DESCRIPTION => isset($object->lines->data[0]->description)?$object->lines->data[0]->description:NULL,
            PAYMENT_STATUS => isset($object->status) && ($object->status === 'paid') ? PAYMENT_SUCCESS : PAYMENT_FAILURE,
            RECEIPT_URL => isset($object->hosted_invoice_url)?$object->hosted_invoice_url:NULL,
            INVOICE_NUMBER => isset($object->number)?$object->number:NULL,
            SUBSCRIPTION_ID => isset($object->subscription)?$object->subscription:NULL,
            PAYMENT_TYPE => MEMBERSHIP_FEE,
            BRAND => isset($object->charges->data[0]->payment_method_details->card->brand)?$object->charges->data[0]->payment_method_details->card->brand:NULL,
            EXP_MONTH => isset($object->charges->data[0]->payment_method_details->card->exp_month)?$object->charges->data[0]->payment_method_details->card->exp_month:NULL,
            EXP_YEAR => isset($object->charges->data[0]->payment_method_details->card->exp_year)?$object->charges->data[0]->payment_method_details->card->exp_year:NULL,
            LAST4 => isset($object->charges->data[0]->payment_method_details->card->last4)?$object->charges->data[0]->payment_method_details->card->last4:NULL,
            RECEIPT_URL => isset($object->charges->data[0]->receipt_url)?$object->charges->data[0]->receipt_url:NULL,
        ];
    }

    private static function getSubscriptionData($object) {
        return [
            SUBSCRIPTION_ID => isset($object->subscription)?$object->subscription:NULL,
            PRODUCT_ID => isset($object->lines->data[0]->price->product)? $object->lines->data[0]->price->product:NULL,
            PRICE_ID => isset($object->lines->data[0]->price->id)? $object->lines->data[0]->price->id:NULL,
            CURRENT_PERIOD_START => isset($object->lines->data[0]->period->start)?date(DATE_TIME,$object->lines->data[0]->period->start):NULL,
            CURRENT_PERIOD_END => isset($object->lines->data[0]->period->end)?date(DATE_TIME,$object->lines->data[0]->period->end):NULL,
            STATUS_ID => isset($object->status) && ($object->status === 'paid') ? ACTIVE : INACTIVE,
            PAYMENT_INTENT => $object->payment_intent ?? NULL,
        ];
    }
}
