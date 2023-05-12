<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('temp_id');
            $table->foreignId(USER_ID)->constrained(USERS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->string('account_id')->nullable();
            $table->string('payment_intent')->nullable();
            $table->float('amount',8,2);
            $table->float('net_amount',8,2)->nullable();
            $table->string('description')->nullable();
            $table->smallInteger('payment_type')->nullable()->comment('1=>Transfer Amount, 2 => Membership Fee');
            $table->integer('payment_status');
            $table->string('subscription_id')->nullable();
            $table->string('product_id')->nullable();
            $table->string('price_id')->nullable();
            $table->dateTime('subscription_start')->nullable();
            $table->dateTime('subscription_end')->nullable();
            $table->string('brand')->nullable();
            $table->smallInteger('exp_month')->nullable();
            $table->smallInteger('exp_year')->nullable();
            $table->string('last4')->nullable();
            $table->string('receipt_url')->nullable();
            $table->string('invoice_number')->nullable();
            $table->dateTime('cancellation_date')->nullable();
            $table->string('refund_id')->nullable();
            $table->string('refund_status')->nullable();
            $table->float('refund_amount',8,2)->nullable();
            $table->float('net_refund_amount',8,2)->nullable();
            $table->timestamp(CREATED_AT)->useCurrent();
            $table->timestamp(UPDATED_AT)->default(\DB::raw(USE_UPDATE_CURRENT_TIME));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
