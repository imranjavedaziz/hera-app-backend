<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('price',8,2);
            $table->enum('interval',['month','year'])->default('month');
            $table->integer('interval_count');
            $table->string('description')->nullable();
            $table->string('ios_product')->nullable();
            $table->string('android_product')->nullable();
            $table->smallInteger('for_whom')->comment('1=>PTB, 2=>Donar');
            $table->foreignId('status_id')->constrained('statuses')->default(ACTIVE)->comment('1=>Active,2=>Inactive,5=>Deleted');
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
        Schema::dropIfExists('subscription_plans');
    }
}
