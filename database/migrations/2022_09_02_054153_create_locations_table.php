<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(LOCATIONS, function (Blueprint $table) {
            $table->id();
            $table->foreignId(USER_ID)->constrained(USERS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->string(ADDRESS)->nullable();
            $table->foreignId(CITY_ID)->nullable()->constrained(CITIES)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->foreignId(STATE_ID)->constrained(STATES)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->string(ZIPCODE);
            $table->string(LATITUDE)->nullable();
            $table->string(LONGITUDE)->nullable();
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
        Schema::dropIfExists(LOCATIONS);
    }
}
