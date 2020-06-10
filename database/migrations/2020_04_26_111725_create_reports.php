<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('reports', function (Blueprint $table) {
            $table->id()->comment('举报 id');

            $table->unsignedBigInteger('user_id')->default(0)->comment('用户 id');
            $table->unsignedBigInteger('thread_id')->default(0)->comment('主题 id');
            $table->unsignedBigInteger('post_id')->default(0)->comment('回复 id');
            $table->unsignedTinyInteger('type')->default(0)->comment('举报类型:0个人主页 1主题 2评论/回复');
            $table->text('reason')->comment('举报理由');
            $table->unsignedTinyInteger('status')->default(0)->comment('举报状态:0未处理 1已处理');

            $table->timestamps();

            $table->index('user_id', 'idx_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('reports');
    }
}
