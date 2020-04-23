<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTopicThread extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('topic_thread', function (Blueprint $table) {
            $table->id()->comment('话题主题ID');
            $table->unsignedBigInteger('topic_id')->nullable()->comment('话题ID');
            $table->unsignedBigInteger('thread_id')->nullable()->comment('主题ID');
            $table->dateTime('created_at')->comment('创建时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('topic_thread');
    }
}
