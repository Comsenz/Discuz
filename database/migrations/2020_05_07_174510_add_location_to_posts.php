<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddLocationToPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('posts', function (Blueprint $table) {
            $table->unsignedDecimal('longitude', 10, 7)->default(0)->after('like_count')->comment('经度');
            $table->unsignedDecimal('latitude', 10, 7)->default(0)->after('longitude')->comment('纬度');
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
            $table->dropColumn('longitude');
            $table->dropColumn('latitude');
        });
    }
}
