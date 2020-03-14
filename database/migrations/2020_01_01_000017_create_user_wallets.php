<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserWallets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('user_wallets', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->comment('钱包所属人 id');
            $table->unsignedDecimal('available_amount', 10, 2)->comment('可用金额');
            $table->unsignedDecimal('freeze_amount', 10, 2)->comment('冻结金额');
            $table->unsignedTinyInteger('wallet_status')->default(0)->comment('钱包状态:0正常，1冻结提现');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('更新时间');

            $table->primary('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('user_wallets');
    }
}
