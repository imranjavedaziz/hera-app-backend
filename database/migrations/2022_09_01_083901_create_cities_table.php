<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(CITIES, function (Blueprint $table) {
            $table->id();
            $table->foreignId(STATE_ID)->constrained(STATES)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->string(NAME);
            $table->foreignId(STATUS_ID)->nullable()->default(ONE)->constrained(STATUSES)->onDelete(CASCADE)->onUpdate(CASCADE);
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
        Schema::dropIfExists(CITIES);
    }
}
