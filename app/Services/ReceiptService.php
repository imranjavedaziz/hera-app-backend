<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Log;
use DB;

/**
 * Class ReceiptService
 * @package App\Services
 */
class ReceiptService
{

    private $iosSharedSecret;

    private $headers;

    private $verifyReceiptUrl;

    public function __construct()
    {
        $this->iosSharedSecret = config('constants.ITUNES_SHARED_SECRET');
        $this->headers   = [
            'Content-Type' => 'application/json'
        ];
        $this->verifyReceiptUrl = 'https://sandbox.itunes.apple.com/verifyReceipt'; // SandBox itunes url
        /***$this->verifyReceiptUrl = 'https://buy.itunes.apple.com/verifyReceipt';***/ // Live itunes url
    }

    public function verifyIosReceipt($receiptBase64Data)
    {
        try {
            $request = [
                "password" => $this->iosSharedSecret,
                "receipt-data" => $receiptBase64Data
            ];
            $response = Http::withHeaders($this->headers)->post($this->verifyReceiptUrl, $request);
            $json = $response->json();
            LOG::info($json);
            $json[MESSAGE] = ($json[STATUS] == ZERO) ? 'Receipt is valid ' : 'Receipt Not valid.';
            $json[CODE] = $response->status();
            if ($json[STATUS] === ZERO) {
                unset($json['latest_receipt']);
                $json[DATA] = self::setIosReceiptData($json);
            }
            return $json;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function setIosReceiptData($json)
    {
        if (!empty($json)) {
            $fields[PAYMENT_ID] = isset($json[LATEST_RECEIPT_INFO][0]['web_order_line_item_id']) ? $json[LATEST_RECEIPT_INFO][0]['web_order_line_item_id'] : null;
            $fields[PRODUCT_ID] = isset($json[LATEST_RECEIPT_INFO][0]['product_id']) ? $json[LATEST_RECEIPT_INFO][0]['product_id'] : null;
            $fields[TRANSACTION_ID] = isset($json[LATEST_RECEIPT_INFO][0]['transaction_id']) ? $json[LATEST_RECEIPT_INFO][0]['transaction_id'] : null;
            $fields[ORIGINAL_TRANSACTION_ID] = isset($json['receipt']['in_app'][0]['original_transaction_id']) ? $json['receipt']['in_app'][0]['original_transaction_id']:null;
            $fields[PURCHASE_DATE] = isset($json[LATEST_RECEIPT_INFO][0]['purchase_date']) ? date(DATE_TIME, strtotime($json[LATEST_RECEIPT_INFO][0]['purchase_date'])) : null;
            $fields[EXPIRES_DATE] = isset($json[LATEST_RECEIPT_INFO][0]['expires_date']) ? date(DATE_TIME, strtotime($json[LATEST_RECEIPT_INFO][0]['expires_date'])) : null;
        }
        return $fields;
    }
    
}
