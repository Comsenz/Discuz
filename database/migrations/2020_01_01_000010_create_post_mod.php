<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostMod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('post_mod', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id')->nullable()->comment('帖子 id');
            $table->string('stop_word')->comment('触发的敏感词，半角逗号隔开');

            $table->primary('post_id');

            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('post_mod');
    }
}
