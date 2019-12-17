<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateThreadUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('thread_user', function (Blueprint $table) {
            $table->integer('thread_id')->unsigned()->nullable()->comment('主题 id');
            $table->integer('user_id')->unsigned()->nullable()->comment('用户 id');
            $table->dateTime('created_at')->comment('创建时间');

            $table->primary(['thread_id', 'user_id']);

            $table->foreign('thread_id')->references('id')->on('threads')->onDelete('cascade');
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
        $this->schema()->dropIfExists('thread_user');
    }
}
