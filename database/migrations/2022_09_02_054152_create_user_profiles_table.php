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
            $table->id();
            $table->foreignId(USER_ID)->nullable()->default(ONE)->constrained(USERS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->foreignId(GENDER_ID)->nullable()->default(ONE)->constrained(GENDERS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->foreignId(SEXUAL_ORIENTATION_ID)->nullable()->default(ONE)->constrained(SEXUAL_ORIENTATIONS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->foreignId(RELATIONSHIP_STATUS_ID)->nullable()->default(ONE)->constrained(RELATIONSHIP_STATUSES)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->string(OCCUPATION)->nullable();
            $table->string(BIO);
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
        Schema::dropIfExists(USER_PROFILES);
    }
}
