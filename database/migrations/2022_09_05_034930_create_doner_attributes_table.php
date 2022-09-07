<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonerAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(DONER_ATTRIBUTES, function (Blueprint $table) {
            $table->id();
            $table->foreignId(USER_ID)->constrained(USERS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->foreignId(HEIGHT_ID)->nullable()->default(ONE)->constrained(HEIGHTS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->foreignId(RACE_ID)->nullable()->default(ONE)->constrained(RACES)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->foreignId(MOTHER_ETHNICITY_ID)->nullable()->default(ONE)->constrained(ETHNICITIES)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->foreignId(FATHER_ETHNICITY_ID)->nullable()->default(ONE)->constrained(ETHNICITIES)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->foreignId(WEIGHT_ID)->nullable()->default(ONE)->constrained(WEIGHTS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->foreignId(HAIR_COLOUR_ID)->nullable()->default(ONE)->constrained(HAIR_COLOURS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->foreignId(EYE_COLOUR_ID)->nullable()->default(ONE)->constrained(EYE_COLOURS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->foreignId(EDUCATION_ID)->nullable()->default(ONE)->constrained(EDUCATION)->onDelete(CASCADE)->onUpdate(CASCADE);
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
        Schema::dropIfExists(DONER_ATTRIBUTES);
    }
}
