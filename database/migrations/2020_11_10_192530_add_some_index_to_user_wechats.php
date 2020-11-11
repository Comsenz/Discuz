<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddSomeIndexToUserWechats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('user_wechats', function (Blueprint $table) {
            $table->index('mp_openid', 'idx_mp_openid');
            $table->index('dev_openid', 'idx_dev_openid');
            $table->index('min_openid', 'idx_min_openid');
            $table->index('unionid', 'idx_unionid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('user_wechats', function (Blueprint $table) {
            //
        });
    }
}
