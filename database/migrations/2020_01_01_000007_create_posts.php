<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('posts', function (Blueprint $table) {
            $table->id()->comment('回复 id');
            $table->integer('user_id')->unsigned()->nullable()->comment('发表用户 id');
            $table->integer('thread_id')->unsigned()->nullable()->index()->comment('关联主题 id');
            $table->integer('reply_post_id')->unsigned()->nullable()->comment('回复 id');
            $table->integer('reply_user_id')->unsigned()->nullable()->comment('回复用户 id');
            $table->text('content')->nullable()->comment('内容');
            $table->ipAddress('ip')->default('')->comment('ip 地址');
            $table->integer('reply_count')->unsigned()->default(0)->comment('关联回复数');
            $table->integer('like_count')->unsigned()->default(0)->comment('喜欢数');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('更新时间');
            $table->dateTime('deleted_at')->nullable()->comment('删除时间');
            $table->integer('deleted_user_id')->unsigned()->nullable()->comment('删除用户 id');
            $table->tinyInteger('is_first')->unsigned()->default(0)->comment('是否首个回复');
            $table->tinyInteger('is_comment')->unsigned()->default(0)->comment('是否是回复回帖的内容');
            $table->tinyInteger('is_approved')->unsigned()->default(1)->comment('是否合法');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('deleted_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('posts');
    }
}
