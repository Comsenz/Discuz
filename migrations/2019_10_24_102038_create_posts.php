<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->increments('id')->comment('回复 id');
            $table->integer('user_id')->unsigned()->nullable()->comment('发表用户 id');
            $table->integer('thread_id')->unsigned()->nullable()->comment('关联主题 id');
            $table->integer('reply_id')->unsigned()->nullable()->comment('关联回复 id');
            $table->text('content')->nullable()->comment('内容');
            $table->string('ip', 45)->nullable()->comment('ip 地址');
            $table->integer('reply_count')->unsigned()->default(0)->comment('关联回复数');
            $table->integer('like_count')->unsigned()->default(0)->comment('喜欢数');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('修改时间');
            $table->dateTime('deleted_at')->comment('删除时间');
            $table->integer('deleted_at_user_id')->unsigned()->nullable()->comment('删除用户 id');
            $table->tinyInteger('is_first')->unsigned()->default(0)->comment('是否首个回复');
            $table->tinyInteger('is_approved')->unsigned()->default(1)->comment('是否合法');
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
