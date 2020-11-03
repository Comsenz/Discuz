<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterThreadAddFreePercent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('threads', function (Blueprint $table) {
            $table->unsignedFloat('free_words')->default(0)->comment('免费字数百分比')->change();
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
            //
        });
    }
}
