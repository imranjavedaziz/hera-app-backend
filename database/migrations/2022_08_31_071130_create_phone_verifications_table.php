<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhoneVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(PHONE_VERIFICATIONS, function (Blueprint $table) {
            $table->id();
<<<<<<< Updated upstream
            $table->string('country_code')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('otp')->nullable();
            $table->integer('max_attempt')->nullable()->default(ZERO);
            $table->bigInteger('otp_block_time')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
=======
            $table->string(PHONE_NO)->nullable();
            $table->string(OTP)->nullable();
            $table->integer(MAX_ATTEMPT)->nullable()->default(ZERO);
            $table->bigInteger(OTP_BLOCK_TIME)->nullable();
            $table->timestamp(CREATED_AT)->useCurrent();
            $table->timestamp(UPDATED_AT)->default(\DB::raw(USE_UPDATE_CURRENT_TIME));
>>>>>>> Stashed changes
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(PHONE_VERIFICATIONS);
    }
}
