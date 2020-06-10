<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterAddPortToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('users', function (Blueprint $table) {
            $table->unsignedInteger('register_port')->default(0)->after('register_ip')->comment('注册端口');
            $table->unsignedInteger('last_login_port')->default(0)->after('last_login_ip')->comment('最后登录端口');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('users', function (Blueprint $table) {
            $table->dropColumn('register_port');
            $table->dropColumn('last_login_port');
        });
    }
}
