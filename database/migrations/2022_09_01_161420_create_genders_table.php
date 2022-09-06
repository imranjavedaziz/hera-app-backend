<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(GENDERS, function (Blueprint $table) {
            $table->id();
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
        Schema::dropIfExists(GENDERS);
    }
}
