<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('votes', function (Blueprint $table) {
            $table->id()->comment('投票ID');
            $table->string('name', '100')->comment('投票名称');
            $table->unsignedBigInteger('user_id')->comment('创建人用户ID');
            $table->unsignedBigInteger('thread_id')->comment('主题ID');
            $table->unsignedTinyInteger('type')->default(0)->comment('类型(0单选,1多选)');
            $table->unsignedBigInteger('total_count')->default(0)->comment('投票总数量');
            $table->dateTime('start_at')->comment('开始时间');
            $table->dateTime('end_at')->comment('结束时间');
            $table->dateTime('updated_at')->comment('更新时间');
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
        $this->schema()->dropIfExists('votes');
    }
}
