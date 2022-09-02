<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(USER_PROFILES, function (Blueprint $table) {
            $table->increments(ID);
            $table->integer(USER_ID)->unsigned();
            $table->date(DOB)->nullable();
            $table->integer(GENDER_ID)->unsigned();
            $table->integer(SEXUAL_ORIENTATION_ID)->unsigned();
            $table->integer(RELATIONSHIP_STATUS_ID)->unsigned();
            $table->string(OCCUPATION);
            $table->string(BIO);
            $table->timestamp(CREATED_AT)->useCurrent();
            $table->timestamp(UPDATED_AT)->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();
            $table->foreign(USER_ID)->references(ID)->on(USERS);
            $table->foreign(GENDER_ID)->references(ID)->on(GENDERS);
            $table->foreign(SEXUAL_ORIENTATION_ID)->references(ID)->on(SEXUAL_ORIENTATIONS);
            $table->foreign(RELATIONSHIP_STATUS_ID)->references(ID)->on(RELATIONSHIP_STATUSES);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(USER_PROFILES);
    }
}
