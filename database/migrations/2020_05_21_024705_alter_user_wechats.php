<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterUserWechats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('user_wechats', function (Blueprint $table) {
            $table->string('privilege', 255)->change();
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
