<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId(USER_ID)->constrained(USERS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->string('product_id')->nullable();
            $table->string('payment_for')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('cost')->nullable();
            $table->string('payment_gateway')->nullable();
            $table->foreignId('status_id')->constrained('statuses')->default(ACTIVE)->comment('1=>Active,2=>Inactive,3=>Pending');
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
        Schema::dropIfExists('payments');
    }
}
