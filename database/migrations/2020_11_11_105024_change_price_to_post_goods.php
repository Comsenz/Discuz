<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class ChangePriceToPostGoods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('post_goods', function (Blueprint $table) {
            $table->string('price')->default('')->comment('价格')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('post_goods', function (Blueprint $table) {
            $table->unsignedDecimal('price', 10, 2)->default(0)->comment('价格')->change();
        });
    }
}
