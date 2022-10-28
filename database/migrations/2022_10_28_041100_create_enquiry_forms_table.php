<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnquiryFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(ENQUIRY_FORMS, function (Blueprint $table) {
            $table->id();
            $table->string(NAME);
            $table->string(EMAIL);
            $table->string(COUNTRY_CODE);
            $table->string(PHONE_NO);
            $table->foreignId(ENQUIRING_AS)->nullable()->default(TWO)->constrained(ROLES)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->string(MESSAGE);
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
        Schema::dropIfExists(ENQUIRY_FORMS);
    }
}
