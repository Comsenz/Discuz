<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

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
            $table->id()->comment('支付通知 id');
            $table->string('payment_sn', 20)->default('')->comment('支付编号');
            $table->unsignedBigInteger('user_id')->comment('付款人 id');
            $table->string('trade_no', 64)->default('')->comment('商户平台交易号');
            $table->unsignedTinyInteger('status')->default(0)->comment('0未接受到通知，1收到通知');
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
        $this->schema()->dropIfExists('pay_notify');
    }
}
