<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserLoginFailLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('user_login_fail_log', function (Blueprint $table) {
            $table->id()->comment('日志 id');
            $table->ipAddress('ip')->default('')->comment('ip 地址');
            $table->unsignedBigInteger('user_id')->default(0)->index()->comment('用户 id');
            $table->string('username', 100)->nullable()->comment('用户名');
            $table->tinyInteger('count')->default(1)->comment('错误次数');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('user_login_fail_log');
    }
}
