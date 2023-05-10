<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(DEVICE_REGISTRATIONS, function (Blueprint $table) {
            $table->id();
            $table->foreignId(USER_ID)->constrained(USERS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->string(DEVICE_ID)->nullable();
            $table->string(DEVICE_TOKEN)->nullable();
            $table->string(DEVICE_TYPE)->nullable();
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
        Schema::dropIfExists(DEVICE_REGISTRATIONS);
    }
}
