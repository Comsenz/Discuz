<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterForeignKeyToUserWechats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->getConnection()->statement('SET FOREIGN_KEY_CHECKS = 0');
        $this->schema()->table('user_wechats', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        $this->schema()->getConnection()->statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('user_wechat', function (Blueprint $table) {
            //
        });
    }
}
