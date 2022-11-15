<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDecativateByColoumUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(USERS, function (Blueprint $table) {
            $table->tinyInteger(DEACTIVATED_BY)->nullable()->comment(DEACTIVATED_BY_COMMENT)->default(ZERO);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(USERS, function (Blueprint $table) {
            $table->dropColumn([DEACTIVATED_BY]);
        });
    }
}
