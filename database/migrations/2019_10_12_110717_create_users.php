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
            $table->string('password', 100)->comment('密码');
            $table->string('mobile', 100)->default('')->comment('手机号');
            $table->tinyInteger('mobile_confirmed')->unsigned()->default(0)->comment('手机号是否验证');
            $table->ipAddress('last_login_ip')->default('')->comment('最后登录 ip 地址');
            $table->ipAddress('register_ip')->default('')->comment('注册ip');
            $table->unsignedInteger('thread_count')->default(0)->comment('主题数');
            $table->tinyInteger('status')->default(0)->comment('用户状态');
            $table->string('avatar', 100)->default('');
            $table->dateTime('joined_at')->nullable()->comment('付费加入时间');
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
