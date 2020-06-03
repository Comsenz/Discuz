<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterAddPortToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('users', function (Blueprint $table) {
            $table->unsignedInteger('port')->after('register_ip')->comment('端口');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('users', function (Blueprint $table) {
            $table->dropColumn('port');
        });
    }
}
