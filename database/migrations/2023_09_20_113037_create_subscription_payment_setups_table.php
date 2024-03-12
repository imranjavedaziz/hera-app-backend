<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPaymentSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_payment_setups', function (Blueprint $table) {
            $table->id();
            $table->uuid(IPS);
            $table->foreignId(USER_ID)->constrained(USERS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->foreignId(SUBSCRIPTION_PLAN_ID)->constrained(SUBSCRIPTION_PLANS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->smallInteger(STATUS)->nullable()->default(ZERO)->comment('0=>WAITING FOR PAYMENT, 1=>PAID, 2=>PAYMENT CANCELLED');
            $table->enum(DEVICE_TYPE, [IOS, ANDROID])->default(ANDROID);
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
        Schema::dropIfExists('subscription_payment_setups');
    }
}