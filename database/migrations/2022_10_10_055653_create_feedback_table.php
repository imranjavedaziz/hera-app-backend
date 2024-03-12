<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(FEEDBACK, function (Blueprint $table) {
            $table->id();
            $table->smallInteger(LIKE)->nullable()->default(ZERO)->comment(FEEDBACK_LIKE_COMMENT);
            $table->string(MESSAGE)->nullable();
            $table->foreignId(SENDER_ID)->nullable()->constrained(USERS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->foreignId(RECIPIENT_ID)->constrained(USERS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->smallInteger(IS_SKIP)->nullable()->default(ZERO)->comment(FEEDBACK_SKIP_COMMENT);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(FEEDBACK);
    }
}
