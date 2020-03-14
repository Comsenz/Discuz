<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('post_user', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id')->nullable()->comment('帖子 id');
            $table->unsignedBigInteger('user_id')->nullable()->comment('用户 id');
            $table->dateTime('created_at')->comment('创建时间');

            $table->primary(['post_id', 'user_id']);

            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('post_user');
    }
}
