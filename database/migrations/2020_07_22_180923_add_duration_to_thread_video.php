<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddDurationToThreadVideo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('thread_video', function (Blueprint $table) {
            $table->unsignedDecimal('duration', 10, 2)->default(0)->after('width')->comment('视频时长');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('thread_video', function (Blueprint $table) {
            $table->dropColumn('duration');
        });
    }
}
