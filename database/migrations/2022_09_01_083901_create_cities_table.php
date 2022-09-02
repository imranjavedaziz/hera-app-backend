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
            $table->increments(ID);
            $table->integer(STATE_ID)->unsigned();
            $table->string(NAME);
            $table->integer(STATUS_ID)->unsigned();
            $table->timestamp(CREATED_AT)->useCurrent();
            $table->timestamp(UPDATED_AT)->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();
            $table->foreign(STATE_ID)->references(ID)->on(STATES);
            $table->foreign(STATUS_ID)->references(ID)->on(STATUSES);
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
