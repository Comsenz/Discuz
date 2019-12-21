<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePayNotify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('pay_notify', function (Blueprint $table) {
            $table->increments('id')->comment('支付通知 id');
            $table->string('payment_sn', 20)->comment('支付单号');
            $table->integer('user_id')->unsigned()->nullable()->comment('用户 id');
            $table->string('trade_no', 64)->nullable()->comment('交易号');
            $table->tinyInteger('status')->default(0)->comment('状态');
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
        $this->schema()->dropIfExists('pay_notify');
    }
}
