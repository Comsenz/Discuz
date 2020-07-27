<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddExpiredAtToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('orders', function (Blueprint $table) {
            $table->dateTime('expired_at')->nullable()->after('is_anonymous')->comment('付费注册过期时长');
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
            $table->dropColumn('expired_at');
        });
    }
}
