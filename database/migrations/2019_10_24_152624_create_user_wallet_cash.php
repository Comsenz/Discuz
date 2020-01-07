<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserWalletCash extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('user_wallet_cash', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('提现ID');
            $table->unsignedBigInteger('user_id')->comment('提现用户ID');
            $table->unsignedBigInteger('cash_sn')->comment('提现交易编号');
            $table->unsignedDecimal('cash_charge', 10, 2)->comment('提现手续费');
            $table->unsignedDecimal('cash_actual_amount', 10, 2)->comment('实际提现金额');
            $table->unsignedDecimal('cash_apply_amount', 10, 2)->comment('提现申请金额');
            $table->unsignedTinyInteger('cash_status')->default(0)->comment('提现状态：1：待审核，2：审核通过，3：审核不通过，4：待打款， 5，已打款， 6：打款失败');
            $table->string('remark', 255)->nullable()->comment('备注或原因');
            $table->dateTime('trade_time')->nullable()->comment('交易时间');
            $table->string('trade_no', 64)->nullable()->comment('交易号');
            $table->string('error_code', 64)->nullable()->comment('错误代码');
            $table->string('error_message', 64)->nullable()->comment('交易失败描叙');
            $table->unsignedTinyInteger('refunds_status')->default(0)->comment('返款状态，0未返款，1已返款');
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
        $this->schema()->dropIfExists('user_wallet_cash');
    }
}
