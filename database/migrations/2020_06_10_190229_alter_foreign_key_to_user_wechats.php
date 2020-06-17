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
        $this->schema()->disableForeignKeyConstraints();
        $this->schema()->table('user_wechats', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        $this->schema()->enableForeignKeyConstraints();
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
