<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddThirdPartyToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('third_party_id')->nullable()->after('payee_id')->comment('第三者收益人 id');
            $table->decimal('third_party_amount')->unsigned()->default(0)->after('author_amount')->comment('第三者收益金额');
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
            $table->dropColumn('third_party_id');
            $table->dropColumn('third_party_amount');
        });
    }
}
