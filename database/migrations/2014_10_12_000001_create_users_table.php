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
            $table->id();
            $table->tinyInteger(ROLE_ID)->comment(ROLE_COMMENT)->default(ONE);
            $table->string(USERNAME)->nullable();
            $table->string(FIRST_NAME);
            $table->string(MIDDLE_NAME)->nullable();
            $table->string(LAST_NAME);
            $table->string(COUNTRY_CODE);
            $table->string(PHONE_NO);
            $table->string(EMAIL)->unique();
            $table->date(DOB)->nullable();
            $table->string(PROFILE_PIC)->nullable();
            $table->boolean(EMAIL_VERIFIED)->default(false);
            $table->datetime(EMAIL_VERIFIED_AT)->nullable();
            $table->string(PASSWORD);
            $table->foreignId(STATUS_ID)->nullable()->default(ONE)->constrained(STATUSES)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->tinyInteger(REGISTRATION_STEP)->nullable()->comment(REGISTRATION_STEP_COMMENT)->default(ONE);
            $table->datetime(RECENT_ACTIVITY)->nullable();
            $table->rememberToken();
            $table->timestamp(CREATED_AT)->useCurrent();
            $table->timestamp(UPDATED_AT)->default(\DB::raw(USE_UPDATE_CURRENT_TIME));
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
