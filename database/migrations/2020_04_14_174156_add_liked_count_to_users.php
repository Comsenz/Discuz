<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddLikedCountToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('users', function (Blueprint $table) {
            $table->unsignedInteger('liked_count')->default(0)->after('fans_count')->comment('点赞数');
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
            $table->dropColumn('liked_count');
        });
    }
}
