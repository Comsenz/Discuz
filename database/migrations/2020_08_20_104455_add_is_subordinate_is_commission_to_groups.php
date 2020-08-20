<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class AddIsSubordinateIsCommissionToGroups
 *
 * 添加分成的：推广下线 & 收入提成 变成单独控制
 */
class AddIsSubordinateIsCommissionToGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 存当前可变动状态
        $this->schema()->table('groups', function (Blueprint $table) {
            $table->tinyInteger('is_subordinate')->unsigned()->default(0)->after('scale')->comment('是否可以 推广下线');
            $table->tinyInteger('is_commission')->unsigned()->default(0)->after('is_subordinate')->comment('是否可以 收入提成');
        });

        // 存历史不可变动状态
        $this->schema()->table('user_distributions', function (Blueprint $table) {
            $table->tinyInteger('is_subordinate')->unsigned()->default(0)->after('level')->comment('是否可以 推广下线');
            $table->tinyInteger('is_commission')->unsigned()->default(0)->after('is_subordinate')->comment('是否可以 收入提成');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('groups', function (Blueprint $table) {
            $table->dropColumn('is_subordinate');
            $table->dropColumn('is_commission');
        });

        $this->schema()->table('user_distributions', function (Blueprint $table) {
            $table->dropColumn('is_subordinate');
            $table->dropColumn('is_commission');
        });
    }
}
