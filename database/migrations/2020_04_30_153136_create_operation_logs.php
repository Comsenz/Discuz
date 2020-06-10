<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOperationLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('operation_logs', function (Blueprint $table) {
            $table->id()->comment('日志 id');

            $table->unsignedBigInteger('user_id')->default(0)->comment('用户 id');
            $table->string('path')->default('')->comment('url路径');
            $table->string('method', 10)->default('')->comment('请求方式');
            $table->ipAddress('ip')->default('')->comment('ip 地址');
            $table->text('input')->comment('body请求数据');
            $table->unsignedTinyInteger('type')->default(0)->comment('日志类型:0后台操作');

            $table->timestamps();

            $table->index('user_id', 'idx_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('operation_logs');
    }
}
