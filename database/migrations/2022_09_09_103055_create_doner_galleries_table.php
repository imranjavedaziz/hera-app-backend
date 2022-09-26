<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonerGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(DONER_GALLERIES, function (Blueprint $table) {
            $table->id();
            $table->foreignId(USER_ID)->constrained(USERS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->string(FILE_NAME)->nullable();
            $table->string(FILE_URL)->nullable();
            $table->string(FILE_TYPE)->nullable();
            $table->integer(POSITION)->default(ONE)->nullable();
            $table->tinyInteger(IS_LATER)->nullable()->comment(IS_LATER_STATUS_COMMENT)->default(ONE);
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
        Schema::dropIfExists(DONER_GALLERIES);
    }
}
