<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColoumnTypeEnquiryFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(ENQUIRY_FORMS, function (Blueprint $table) {
            $table->longText(ADMIN_REPLY)->after(MESSAGE)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(ENQUIRY_FORMS, function (Blueprint $table) {
            $table->string(ADMIN_REPLY)->after(MESSAGE)->nullable()->change();
        });
    }
}
