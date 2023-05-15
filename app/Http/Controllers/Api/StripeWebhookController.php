<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Log;

class StripeWebhookController extends Controller
{
    private $stripeWebhookSecret;

    public function __construct()
    {
        $this->stripeWebhookSecret = env('STRIPE_WEBHOOK_SECRET');
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function receiveEventNotifications()
    {
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $this->stripeWebhookSecret
            );
            $this->invoiceEvent($event);

        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }
        http_response_code(200);
    }

    private function invoiceEvent($event) {
        if(isset($event) && !empty($event->type) && $event->type === 'invoice.paid' ) {
            Log::info("Invoice paid");
            Log::info($event->data->object);
            Transaction::saveInvoicePayment($event->data->object);
        }

        if(isset($event) && !empty($event->type) && $event->type === 'invoice.payment_failed' ) {
            Log::info("Invoice payment failed");
            Log::info($event->data->object);
            Transaction::saveInvoicePayment($event->data->object);
        }
    }
}