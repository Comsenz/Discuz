<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('orders', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('订单 id');
            $table->char('order_sn', 22)->default('')->comment('订单编号');
            $table->string('payment_sn', 20)->default('')->comment('支付编号');
            $table->unsignedDecimal('amount', 10, 2)->comment('订单总金额');
            $table->unsignedDecimal('master_amount', 10, 2)->default(0.00)->comment('站长分成金额');
            $table->unsignedBigInteger('user_id')->comment('付款人 id');
            $table->unsignedBigInteger('payee_id')->comment('收款人 id');
            $table->unsignedTinyInteger('type')->default(0)->comment('交易类型：1注册、2打赏');
            $table->unsignedInteger('thread_id')->nullable()->index()->comment('主题 id');
            $table->unsignedTinyInteger('status')->default(0)->comment('订单状态：0待付款；1已付款；2.取消订单；3支付失败；');
            $table->unsignedSmallInteger('payment_type')->default(0)->comment('付款方式：微信（10：pc扫码，11：h5支付，12：微信内支付');
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
        $this->schema()->dropIfExists('orders');
    }
}
