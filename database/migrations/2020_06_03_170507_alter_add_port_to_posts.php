<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterAddPortToPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('posts', function (Blueprint $table) {
            $table->unsignedInteger('port')->default(0)->after('ip')->comment('端口');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('posts', function (Blueprint $table) {
            $table->dropColumn('port');
        });
    }
}
