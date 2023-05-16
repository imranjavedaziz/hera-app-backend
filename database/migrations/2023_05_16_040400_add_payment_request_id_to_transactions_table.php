<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentRequestIdToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('payment_request_id')->nullable()->after('payout_id');
            DB::statement("ALTER TABLE `transactions` CHANGE `payout_status` `payout_status` SMALLINT NULL DEFAULT '0' COMMENT '0=>Not Added, 2=>Added';");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['payment_request_id']);
        });
    }
}
