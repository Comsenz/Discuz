<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class ChangeIsAnonymous extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('threads', function (Blueprint $table) {
            $table->tinyInteger('is_anonymous')->default(0)->after('is_site')->comment('是否匿名 0否 1是');
        });
        $this->schema()->table('questions', function (Blueprint $table) {
            $table->dropColumn('is_anonymous');
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
            $table->dropColumn('is_anonymous');
        });
        $this->schema()->table('questions', function (Blueprint $table) {
            $table->tinyInteger('is_anonymous')->default(0)->after('is_answer')->comment('是否匿名 0否 1是');
        });
    }
}
