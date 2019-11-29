<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('users', function (Blueprint $table) {
            $table->increments('id')->comment('用户 id');
            $table->string('username', 100)->unique()->comment('用户名');
            $table->string('mobile', 100)->unique()->comment('手机号');
            $table->string('password', 100)->comment('密码');
            $table->string('union_id', 100)->comment('union_id');
            $table->ipAddress('last_login_ip')->default('')->comment('最后登录 ip 地址');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('修改时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('users');
    }
}
