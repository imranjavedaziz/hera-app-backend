<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPrefrencesAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_prefrences_attributes', function (Blueprint $table) {
            $table->increments(ID);
            $table->integer(USER_ID)->unsigned();
            $table->integer(RACE_ID)->unsigned();
            $table->integer(ETHNICITY_ID)->unsigned();
            $table->integer(HEIGHT_ID)->unsigned();
            $table->integer(WEIGHT_ID)->unsigned();
            $table->integer(HAIR_COLOUR_ID)->unsigned();
            $table->integer(EYE_COLOUR_ID)->unsigned();
            $table->integer(EDUCATION_ID)->unsigned();
            $table->timestamp(CREATED_AT)->useCurrent();
            $table->timestamp(UPDATED_AT)->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();
            $table->foreign(USER_ID)->references(ID)->on(USERS);
            $table->foreign(RACE_ID)->references(ID)->on(RACES);
            $table->foreign(ETHNICITY_ID)->references(ID)->on(ETHNICITIES);
            $table->foreign(HEIGHT_ID)->references(ID)->on(HEIGHTS);
            $table->foreign(WEIGHT_ID)->references(ID)->on(WEIGHTS);
            $table->foreign(HAIR_COLOUR_ID)->references(ID)->on(HAIR_COLOURS);
            $table->foreign(EYE_COLOUR_ID)->references(ID)->on(EYE_COLOURS);
            $table->foreign(EDUCATION_ID)->references(ID)->on(EDUCATIONS);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_prefrences_attributes');
    }
}
