<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFinance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('finance', function (Blueprint $table) {
            $table->id()->comment('自增ID');
            $table->unsignedDecimal('income', 10, 2)->comment('用户充值金额');
            $table->unsignedDecimal('withdrawal', 10, 2)->comment('用户提现金额');
            $table->unsignedInteger('order_count')->comment('订单数量');
            $table->unsignedDecimal('order_amount', 10, 2)->comment('订单金额');
            $table->unsignedDecimal('total_profit', 10, 2)->comment('平台盈利');
            $table->unsignedDecimal('register_profit', 10, 2)->comment('注册收入');
            $table->unsignedDecimal('master_portion', 10, 2)->comment('打赏贴的分成');
            $table->unsignedDecimal('withdrawal_profit', 10, 2)->comment('提现手续费收入');
            $table->date('created_at')->comment('创建时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('finance');
    }
}
