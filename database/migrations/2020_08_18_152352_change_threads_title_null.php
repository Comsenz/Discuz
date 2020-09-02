<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class ChangeThreadsTitleNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('threads', function (Blueprint $table) {
            $table->string('title')->comment('标题')->nullable()->default(null)->change();
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
            $table->string('title')->default('')->comment('标题')->change();
        });
    }
}
