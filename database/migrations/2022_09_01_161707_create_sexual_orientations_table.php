<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSexualOrientationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SEXUAL_ORIENTATIONS, function (Blueprint $table) {
            $table->increments(ID);
            $table->string(NAME);
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
        Schema::dropIfExists(SEXUAL_ORIENTATIONS);
    }
}
