<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateThreadVideo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('thread_video', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('自增ID');
            $table->integer('thread_id')->unsigned()->index()->comment('主题 id');
            $table->integer('user_id')->unsigned()->comment('用户 id');
            $table->string('file_id')->default('')->comment('媒体文件唯一标识');
            $table->string('media_url')->default('')->comment('媒体播放地址');
            $table->string('cover_url')->default('')->comment('媒体封面地址');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('thread_video');
    }
}
