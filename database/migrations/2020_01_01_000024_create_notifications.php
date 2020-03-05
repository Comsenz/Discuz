<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('notifications', function (Blueprint $table) {
            $table->increments('id')->comment('通知 id');
            $table->string('type')->comment('通知类型');
            $table->morphs('notifiable');
            $table->text('data')->comment('通知内容');
            $table->dateTime('read_at')->nullable()->comment('通知阅读时间');
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
        $this->schema()->dropIfExists('notifications');
    }
}
