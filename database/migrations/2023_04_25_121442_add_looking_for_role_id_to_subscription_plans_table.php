<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLookingForRoleIdToSubscriptionPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->foreignId(ROLE_ID_LOOKING_FOR)->nullable()->after(ID)->constrained(ROLES, ID)->onDelete(CASCADE)->onUpdate(CASCADE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropForeign('subscription_plans_role_id_looking_for_foreign');
            $table->dropColumn([ROLE_ID_LOOKING_FOR]);
        });
    }
}
