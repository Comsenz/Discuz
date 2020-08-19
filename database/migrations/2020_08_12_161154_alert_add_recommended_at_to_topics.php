<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlertAddRecommendedAtToTopics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('topics', function (Blueprint $table) {
            $table->dateTime('recommended_at')->nullable()->comment('推荐时间');
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
            $table->dropColumn('recommended_at');
        });
    }
}
