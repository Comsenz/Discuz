<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserLoginLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('user_login_log', function (Blueprint $table) {
            $table->increments('id')->comment('日志 id');
            $table->integer('user_id')->unsigned()->default(0)->comment('用户 id');
            $table->string('username')->default('')->comment('用户名');
            $table->tinyInteger('type')->comment('类型(0正常1密码错误2账号错误)');
            $table->dateTime('created_at')->comment('创建时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('user_login_log');
    }
}
