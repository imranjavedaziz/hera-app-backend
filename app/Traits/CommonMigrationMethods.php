<?php
namespace App\Traits;

use Illuminate\Database\Schema\Blueprint;

trait CommonMigrationMethods
{
    public function commonColumns(Blueprint $table)
    {
        $table->foreignId(FROM_USER_ID)->constrained(USERS)->onDelete(CASCADE)->onUpdate(CASCADE);
        $table->foreignId(TO_USER_ID)->constrained(USERS)->onDelete(CASCADE)->onUpdate(CASCADE);
        $table->tinyInteger(STATUS)->nullable()->default(ONE);
        $table->timestamp(CREATED_AT)->useCurrent();
        $table->timestamp(UPDATED_AT)->default(\DB::raw(USE_UPDATE_CURRENT_TIME));
    }
}
