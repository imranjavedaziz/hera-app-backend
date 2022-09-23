<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipient_id')->constrained(USERS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->foreignId('sender_id')->nullable()->constrained(USERS)->onDelete(CASCADE)->onUpdate(CASCADE);
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->smallInteger('notify_type')->nullable()->comment('1 => match, 2 => subscription, 3 => chat');
            $table->timestamp('read_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
