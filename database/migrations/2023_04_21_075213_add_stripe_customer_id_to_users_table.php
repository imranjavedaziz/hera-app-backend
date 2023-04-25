<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStripeCustomerIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_customer_id')->nullable()->after('refresh_token');
            $table->string('connected_acc_token')->nullable()->after('stripe_customer_id');
            $table->tinyInteger('connected_acc_status')->after('connected_acc_token')->nullable()->default(ZERO)->comment('0 => inactive, 1 => active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['stripe_customer_id']);
            $table->dropColumn(['connected_acc_token']);
            $table->dropColumn(['connected_acc_status']);
        });
    }
}
