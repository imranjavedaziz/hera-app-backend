<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPrefrencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(USER_PREFERENCES, function (Blueprint $table) {
            $table->id();
            $table->foreignId(USER_ID)->constrained(USERS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->foreignId(ROLE_ID_LOOKING_FOR)->constrained(ROLES, ID)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->string(AGE)->nullable();
            $table->string(HEIGHT)->nullable();
            $table->string(RACE)->nullable();
            $table->string(ETHNICITY)->nullable();
            $table->string(HAIR_COLOUR)->nullable();
            $table->string(EYE_COLOUR)->nullable();
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
        Schema::dropIfExists(USER_PREFERENCES);
    }
}
