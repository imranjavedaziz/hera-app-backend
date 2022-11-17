<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAdminReplyToEnquiryForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(ENQUIRY_FORMS, function (Blueprint $table) {
            $table->string(ADMIN_REPLY)->after(MESSAGE)->nullable();
            $table->datetime(REPLIED_AT)->after(ADMIN_REPLY)->nullable();
            $table->string(USER_TIMEZONE)->nullable()->default('America/New_york');
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
            $table->dropColumn([ADMIN_REPLY, REPLIED_AT, USER_TIMEZONE]);
        });
    }
}
