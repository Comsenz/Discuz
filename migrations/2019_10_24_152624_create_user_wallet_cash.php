<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->unsignedBigInteger('user_wallet_id')->comment('提现钱包ID');
            $table->unsignedBigInteger('cash_sn')->default('')->comment('提现交易编号');
            $table->unsignedDecimal('cash_charge', 10, 2)->comment('提现手续费');
            $table->unsignedDecimal('cash_actual_amount', 10, 2)->comment('实际提现金额');
            $table->unsignedDecimal('cash_apply_amount', 10, 2)->comment('提现申请金额');
            $table->unsignedTinyInteger('cash_status')->default(0)->comment(' 提现状态：1：待审核，2：审核通过，3：交易完成，4：审核不通过，5：提现失败');
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
        $this->schema()->dropIfExists('user_wallet_cash');
    }
}
