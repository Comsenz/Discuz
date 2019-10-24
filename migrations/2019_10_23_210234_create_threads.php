<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('threads', function (Blueprint $table) {
            $table->increments('id')->comment('主题 id');
            $table->integer('user_id')->unsigned()->nullable()->comment('创建用户 id');
            $table->integer('last_posted_user_id')->unsigned()->nullable()->comment('最后回复用户 id');
            $table->string('title')->default('')->comment('标题');
            $table->decimal('price')->unsigned()->default(0)->comment('价格');
            $table->integer('post_count')->unsigned()->default(0)->comment('回复数');
            $table->integer('view_count')->unsigned()->default(0)->comment('查看数');
            $table->integer('like_count')->unsigned()->default(0)->comment('喜欢数');
            $table->integer('favorite_count')->unsigned()->default(0)->comment('收藏数');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('修改时间');
            $table->dateTime('deleted_at')->comment('删除时间');
            $table->integer('deleted_at_user_id')->unsigned()->nullable()->comment('删除用户 id');
            $table->tinyInteger('is_approved')->unsigned()->default(1)->comment('是否合法');
            $table->tinyInteger('is_sticky')->unsigned()->default(0)->comment('是否置顶');
            $table->tinyInteger('is_essence')->unsigned()->default(0)->comment('是否加精');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('threads');
    }
}
