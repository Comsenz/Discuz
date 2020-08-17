<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlertAddRecommendedToTopics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('topics', function (Blueprint $table) {
            $table->unsignedTinyInteger('recommended')->default(0)->comment('是否推荐');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('topics', function (Blueprint $table) {
            $table->dropColumn('recommended');
        });
    }
}
