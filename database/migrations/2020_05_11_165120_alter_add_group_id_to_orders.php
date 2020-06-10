<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterAddGroupIdToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('orders', function (Blueprint $table) {
            $table->unsignedInteger('group_id')->nullable()->after('type')->comment('用户组 id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('orders', function (Blueprint $table) {
            $table->dropColumn('group_id');
        });
    }
}
