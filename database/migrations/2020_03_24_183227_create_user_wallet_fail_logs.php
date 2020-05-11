<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserWalletFailLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('user_wallet_fail_logs', function (Blueprint $table) {
            $table->id()->comment('日志 id');
            $table->ipAddress('ip')->default('')->comment('ip 地址');
            $table->unsignedBigInteger('user_id')->default(0)->index()->comment('用户 id');
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
        $this->schema()->dropIfExists('user_wallet_fail_logs');
    }
}
