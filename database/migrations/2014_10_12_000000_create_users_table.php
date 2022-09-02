<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(USERS, function (Blueprint $table) {
            $table->increments(ID);
            $table->tinyInteger(ROLE_ID)->comment(ROLE_COMMENT)->default(ONE);
            $table->string(FIRST_NAME);
            $table->string(MIDDLE_NAME)->nullable();
            $table->string(LAST_NAME);
            $table->string(PHONE_NO);
            $table->string(EMAIL)->unique();
            $table->text(PROFILE_PIC)->nullable();
            $table->boolean(EMAIL_VERIFIED)->default(false);
            $table->timestamp(EMAIL_VERIFIED_AT)->nullable();
            $table->string(PASSWORD);
            $table->boolean(STATUS)->default(true);
            $table->tinyInteger(REGISTRATION_STEP)->comment(REGISTRATION_STEP_COMMENT)->default(ONE);
            $table->rememberToken();
            $table->timestamp(CREATED_AT)->useCurrent();
            $table->timestamp(UPDATED_AT)->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(USERS);
    }
}
