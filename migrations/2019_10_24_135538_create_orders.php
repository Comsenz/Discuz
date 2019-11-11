<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id')->comment('订单ID');
            $table->unsignedBigInteger('order_sn')->default(0)->comment('订单编号');
            $table->unsignedBigInteger('payment_sn')->default(0)->comment('支付编号');
            $table->unsignedDecimal('amount', 10, 2);
            $table->unsignedBigInteger('user_id')->comment('付款人ID');
            $table->unsignedBigInteger('payee_id')->comment('收款人ID');
            $table->unsignedTinyInteger('type')->default(0)->comment('交易类型：1注册、2打赏');
            $table->unsignedInteger('type_id')->comment('主题ID');
            $table->unsignedTinyInteger('status')->default(0)->comment('订单状态：0待付款；1已付款；2.取消订单；3支付失败；');
            $table->unsignedSmallInteger('payment_type')->default(0)->comment('付款方式：微信（10：pc扫码，11：h5支付，12：微信内支付');
            $table->string('remark', 255)->comment('备注或原因');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
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
