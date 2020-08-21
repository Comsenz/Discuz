<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddBeScaleToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('orders', function (Blueprint $table) {
            $table->unsignedDecimal('author_amount', 10, 2)->default(0.00)->after('master_amount')->comment('作者分成金额');
            $table->float('be_scale', 3, 1)->default(0)->after('author_amount')->comment('作者受邀时的分成比例');
        });

        // 添加钱包明细分成来源用户
        $this->schema()->table('user_wallet_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('source_user_id')->default(0)->after('user_id')->comment('金额来源用户');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('orders', function (Blueprint $table) {
            $table->dropColumn('author_amount');
            $table->dropColumn('be_scale');
        });

        $this->schema()->table('user_wallet_logs', function (Blueprint $table) {
            $table->dropColumn('source_user_id');
        });
    }
}
