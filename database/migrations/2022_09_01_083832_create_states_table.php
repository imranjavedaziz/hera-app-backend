<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(STATES, function (Blueprint $table) {
            $table->increments(ID);
            $table->string(CODE);
            $table->string(NAME);
            $table->integer(STATUS_ID)->unsigned();
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists(STATES);
    }
}
