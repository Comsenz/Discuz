<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCashTypeToUserWalletCash extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('user_wallet_cash', function (Blueprint $table) {
            $table->tinyInteger('cash_type')->default(1)->after('cash_status')->comment('提现转账类型：0：人工转账， 1：企业零钱付款');
            $table->string('cash_mobile', 20)->default('')->after('cash_status')->comment('提现到账手机号码');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('user_wallet_cash', function (Blueprint $table) {
            $table->dropColumn('cash_type');
            $table->dropColumn('cash_mobile');
        });
    }
}
