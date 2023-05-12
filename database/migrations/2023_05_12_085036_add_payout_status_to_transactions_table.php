<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayoutStatusToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->smallInteger('payout_status')->nullable()->default(ONE)->comment('1=>Pending, 2=>Paid, 3 => Failed')->after('net_refund_amount');
            $table->string('payout_id')->nullable()->after('payout_status');
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
            $table->dropColumn(['payout_status']);
            $table->dropColumn(['payout_id']);
        });
    }
}
