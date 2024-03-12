<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonorPaymentSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donor_payment_setups', function (Blueprint $table) {
            $table->id();
            $table->uuid(IPS);
            $table->foreignId(FROM_USER_ID)->constrained(USERS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->foreignId(TO_USER_ID)->constrained(USERS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->foreignId(PAYMENT_REQUEST_ID)->constrained(PAYMENT_REQUESTS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->smallInteger(STATUS)->nullable()->default(ZERO)->comment('0=>WAITING FOR PAYMENT, 1=>PAID, 2=>PAYMENT CANCELLED');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('donor_payment_setups');
    }
}
