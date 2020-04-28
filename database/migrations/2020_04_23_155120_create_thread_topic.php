<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateThreadTopic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('thread_topic', function (Blueprint $table) {
            $table->unsignedBigInteger('thread_id')->nullable()->comment('主题ID');
            $table->unsignedBigInteger('topic_id')->nullable()->comment('话题ID');
            $table->dateTime('created_at')->comment('创建时间');

            $table->primary(['thread_id', 'topic_id']);

            $table->foreign('thread_id')->references('id')->on('threads')->onDelete('cascade');
            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('thread_topic');
    }
}
