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
            $table->string(COUNTRY_CODE)->nullable();
            $table->string(PHONE_NO)->nullable();
            $table->string(OTP)->nullable();
            $table->integer(MAX_ATTEMPT)->nullable()->default(ZERO);
            $table->bigInteger(OTP_BLOCK_TIME)->nullable();
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
        Schema::dropIfExists(PHONE_VERIFICATIONS);
    }
}
