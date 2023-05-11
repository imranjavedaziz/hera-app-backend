<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;
use Facades\{
    App\Services\StripeService
};

class TransactionHistory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $payment;

    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payment, $data)
    {
        $this->payment = $payment;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $card = StripeService::getPaymentCardDetails($this->data[PAYMENT_METHOD_ID]);
        $bankAccount = StripeService::getBanckAccountDetails($this->data[ACCOUNT_ID], $this->data[BANK_ACCOUNT_TOKEN]);
        Transaction::saveTransaction($this->payment, $this->data, $card, $bankAccount);
    }
}
