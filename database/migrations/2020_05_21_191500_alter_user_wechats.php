<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterUserWechats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('user_wechats', function (Blueprint $table) {
            $table->string('nickname', 50)->default('')->comment('微信昵称')->change();
            $table->string('province', 100)->default('')->comment('省份')->change();
            $table->string('city', 100)->default('')->comment('城市')->change();
            $table->string('country', 100)->default('')->comment('国家')->change();
            $table->string('privilege', 255)->default('')->comment('用户特权信息')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
