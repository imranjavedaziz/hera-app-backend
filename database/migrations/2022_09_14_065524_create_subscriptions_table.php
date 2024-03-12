<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId(USER_ID)->constrained(USERS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->foreignId('subscription_plan_id')->constrained(SUBSCRIPTION_PLANS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->float('price',8,2)->nullable();
            $table->dateTime('current_period_start')->nullable();
            $table->dateTime('current_period_end')->nullable();
            $table->string('subscription_id')->nullable();
            $table->string('original_transaction_id')->nullable();
            $table->string('product_id')->nullable();
            $table->longText('purchase_token')->nullable();
            $table->enum('device_type',['ios','android'])->default('android');
            $table->dateTime('canceled_at')->nullable();
            $table->smallInteger('mail_status')->nullable()->comment('1=>Create Subscriptions, 2=>Cancel Subscriptions');
            $table->foreignId('status_id')->constrained('statuses')->default(ACTIVE)->comment('1=>Active,2=>Inactive');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
