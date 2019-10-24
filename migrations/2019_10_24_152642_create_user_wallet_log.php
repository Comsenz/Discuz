<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWalletLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('user_wallet_log', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('钱包明细ID');
            $table->unsignedBigInteger('user_id')->comment('明细所属用户ID');
            $table->unsignedBigInteger('user_wallet_id')->comment('明细所属钱包ID');
            $table->decimal('change_available_amount', 10, 2)->comment('变动可用金额');
            $table->decimal('change_freeze_amount', 10, 2)->comment('变动冻结金额');
            $table->unsignedSmallInteger('change_type')->default(0)->comment('10：提现冻结，11：提现成功，12：撤销提现解冻； 31：打赏收入，32：人工收入； 50：人工支出');
            $table->string('change_desc', 255)->comment('变动描述');
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
        $this->schema()->dropIfExists('user_wallet_log');
    }
}
