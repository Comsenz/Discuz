<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddIsDisplayToThreads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('threads', function (Blueprint $table) {
            $table->tinyInteger('is_display')->default(1)->comment('是否显示 0否 1是');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('threads', function (Blueprint $table) {
            $table->dropColumn('is_display');
        });
    }
}
