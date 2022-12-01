<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColoumnsEmailVerifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(EMAIL_VERIFICATIONS, function (Blueprint $table) {
            $table->integer(MAX_ATTEMPT)->after(OTP)->nullable()->default(ZERO);
            $table->bigInteger(OTP_BLOCK_TIME)->after(MAX_ATTEMPT)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(EMAIL_VERIFICATIONS, function (Blueprint $table) {
            $table->dropColumn([MAX_ATTEMPT, OTP_BLOCK_TIME]);
        });
    }
}
